<?php
// =============================
//   Session & Database Setup
// =============================
include_once '../include/dbconnection.php';
session_start();

if (isset($_POST)) {
    // =============================
    //      Initialize Filters
    // =============================
    $selected_year   = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $selected_month  = isset($_POST['month']) ? $_POST['month'] : date('m');
    $loan_code       = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
    $customer_code   = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
    $customer_name   = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

    $db = $_SESSION['login_database'];

    // build YYYY-MM once
    $selected_year_month = $selected_year . '-' . $selected_month;

    // =============================
    //  Get Initial Closing Balance
    // =============================
    $sql = "SELECT
                initial_closing_balance_monthly
            FROM $db.account_book_monthly
            WHERE year = '$selected_year' 
              AND month = '$selected_month'";
    $q   = mysql_query($sql);
    $res = mysql_fetch_assoc($q);

    $initial_closing_balance_monthly = isset($res['initial_closing_balance_monthly'])
        ? (float)$res['initial_closing_balance_monthly']
        : 0.0;

    $current_closing_balance = 0.0;

    // ======================================================
    // 1) BUILD CREDIT SUBQUERY
    //    - From monthly_payment_record
    //    - only PAID/FINISHED with payout_date in this month
    //    - CREDIT = payout_amount * 0.9
    // ======================================================
    $sqlCredit = "
        SELECT
            mpr.loan_code,
            cd.customercode2,
            cd.name,
            0 AS debit,
            (mpr.payout_amount * 0.9) AS credit,
            mpr.payout_date AS tx_date
        FROM
            $db.monthly_payment_record mpr
        LEFT JOIN
            $db.customer_loanpackage cl 
                ON cl.loan_code = mpr.loan_code
        LEFT JOIN
            $db.customer_details cd 
                ON cd.id = mpr.customer_id
        WHERE
            mpr.status IN ('PAID','FINISHED')
            AND mpr.payout_date IS NOT NULL
            AND mpr.month = '$selected_year_month'
    ";

    if ($loan_code != '') {
        $sqlCredit .= " AND mpr.loan_code = '$loan_code'";
    }
    if ($customer_code != '') {
        $sqlCredit .= " AND cd.customercode2 = '$customer_code'";
    }
    if ($customer_name != '') {
        $sqlCredit .= " AND cd.name = '$customer_name'";
    }

    // ======================================================
    // 2) BUILD DEBIT SUBQUERY
    //    - From return_book_monthly + collection
    //    - Grouped by loan_code (to avoid duplicates)
    //    - tepi1/tepi2 summed per loan_code for that month
    //    - DEBIT = tepi1 + tepi2
    //    - Use first_datetime (earliest) as tx_date
    // ======================================================
    $sqlDebit = "
        SELECT 
            rbm_agg.loan_code,
            cd.customercode2,
            cd.name,
            (COALESCE(col_agg.sum_tepi1,0) + COALESCE(col_agg.sum_tepi2,0)) AS debit,
            0 AS credit,
            rbm_agg.first_datetime AS tx_date
        FROM (
            SELECT 
                loan_code,
                MIN(datetime) AS first_datetime
            FROM $db.return_book_monthly
            WHERE year  = '$selected_year'
              AND month = '$selected_month'
            GROUP BY loan_code
        ) rbm_agg
        LEFT JOIN (
            SELECT 
                loan_code,
                SUM(tepi1) AS sum_tepi1,
                SUM(tepi2) AS sum_tepi2
            FROM $db.collection
            WHERE tepi1_month = '$selected_year_month'
            GROUP BY loan_code
        ) col_agg
            ON col_agg.loan_code = rbm_agg.loan_code
        LEFT JOIN (
            SELECT 
                loan_code,
                MAX(customer_id) AS customer_id
            FROM $db.monthly_payment_record
            GROUP BY loan_code
        ) mpr_agg
            ON mpr_agg.loan_code = rbm_agg.loan_code
        LEFT JOIN $db.customer_details cd 
            ON cd.id = mpr_agg.customer_id
        WHERE 1 = 1
    ";

    if ($loan_code != '') {
        $sqlDebit .= " AND rbm_agg.loan_code = '$loan_code'";
    }
    if ($customer_code != '') {
        $sqlDebit .= " AND cd.customercode2 = '$customer_code'";
    }
    if ($customer_name != '') {
        $sqlDebit .= " AND cd.name = '$customer_name'";
    }

    // ======================================================
    // 3) MERGE CREDIT + DEBIT WITH UNION ALL
    //    Then order by tx_date (old -> new), then loan_code
    // ======================================================
    $sqlMerged = "
        SELECT *
        FROM (
            $sqlCredit
            UNION ALL
            $sqlDebit
        ) t
        ORDER BY 
            t.tx_date ASC,
            t.loan_code ASC
    ";

    $query = mysql_query($sqlMerged);

    // ======================================================
    // 4) LOOP ONCE AND CALCULATE RUNNING BALANCE
    // ======================================================
    $html  = '';
    $count = 0;
    $current_closing_balance = $initial_closing_balance_monthly;

    while ($row = mysql_fetch_assoc($query)) {
        $loan_code   = $row['loan_code'];
        $customer_id = $row['customercode2'];
        $name        = $row['name'];
        $tx_date     = $row['tx_date'];

        $debit  = isset($row['debit'])  ? (float)$row['debit']  : 0.0;
        $credit = isset($row['credit']) ? (float)$row['credit'] : 0.0;

        // update running balance
        $current_closing_balance -= $debit;
        $current_closing_balance += $credit;

        $count++;

        // only show rows that actually have movement (optional; remove if you want all)
        if ($debit != 0 || $credit != 0) {
            $html .= '<tr>
                <td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black; text-align: center;">' . $count . '</td>
                <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;"><b>' . $loan_code . '</b></td>
                <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;"><b>' . $customer_id . '</b></td>
                <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>RM ' . number_format($debit, 2) . '</b></td>
                <td style="border-right:1px solid black;border-bottom: 1px solid black;color: red;"><b>RM ' . number_format($credit, 2) . '</b></td>
                <td id="balance-' . $count . '" style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>RM ' . number_format($current_closing_balance, 2) . '</b></td>
            </tr>';
        }
    }

    // =============================
    //        Handle No Records
    // =============================
    if ($count == 0) {
        $html .= '<tr>
            <td colspan="12" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
        </tr>';
    }

    // =============================
    //        Output Final HTML
    // =============================
    header('Content-Type: text/html');
    echo $html;
    exit;
}
?>
