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
    //  Loan Code Style Helpers
    //  (PHP 5 compatible)
    // =============================
    function getLoanStyle($loanCode) {
        $prefix = strtoupper(substr($loanCode, 0, 2));

        $valid = array('SD','AS','SB','BP','KT','MS','MJ','PP','NG','TS','LT','BT','DK','CL');

        if ($prefix == 'SD') {
            return '';
        }

        if (!in_array($prefix, $valid)) {
            return 'color:#FF0000;';
        }

        return '';
    }

    function formatLoanCode($loanCode) {
        $prefix = strtoupper(substr($loanCode, 0, 2));
        $highlight = array('SB','MS','MJ','PP','CL','BT');

        if (in_array($prefix, $highlight)) {
            return preg_replace(
                '/^([a-z]{2})/i',
                '<span style="color:red;">\1</span>',
                htmlspecialchars($loanCode, ENT_QUOTES, 'UTF-8')
            );
        }

        return htmlspecialchars($loanCode, ENT_QUOTES, 'UTF-8');
    }

    // =============================
    //  Get Initial Closing Balance
    // =============================
    $sql = "SELECT initial_closing_balance_monthly
            FROM $db.account_book_monthly
            WHERE year = '$selected_year'
              AND month = '$selected_month'";
    $q   = mysql_query($sql);
    $res = mysql_fetch_assoc($q);

    $initial_closing_balance_monthly = isset($res['initial_closing_balance_monthly'])
        ? (float)$res['initial_closing_balance_monthly']
        : 0.0;

    // ======================================================
    // 1) BUILD CREDIT SUBQUERY
    //    - CREDIT = payout_amount * 0.9
    //    - sort_dt should follow mpr.datetime (created time),
    //      fallback to payout_date
    // ======================================================
    $sqlCredit = "
        SELECT
            mpr.loan_code,
            cd.customercode2,
            cd.name,
            0 AS debit,
            (mpr.payout_amount * 0.9) AS credit,
            mpr.payout_date AS tx_date,

            -- MAIN SORT KEY (follow record datetime if available)
            CASE
                WHEN mpr.datetime IS NOT NULL
                     AND mpr.datetime <> '0000-00-00 00:00:00'
                THEN mpr.datetime
                ELSE mpr.payout_date
            END AS sort_dt

        FROM $db.monthly_payment_record mpr
        LEFT JOIN $db.customer_loanpackage cl
            ON cl.loan_code = mpr.loan_code
        LEFT JOIN $db.customer_details cd
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
    //    - DEBIT = tepi1 + tepi2 (from collection)
    //    - sort_dt should follow collection.datetime (MAX per loan_code in month)
    //      fallback to rbm first_datetime
    // ======================================================
    $sqlDebit = "
        SELECT
            rbm_agg.loan_code,
            cd.customercode2,
            cd.name,
            (COALESCE(col_agg.sum_tepi1,0) + COALESCE(col_agg.sum_tepi2,0)) AS debit,
            0 AS credit,
            rbm_agg.first_datetime AS tx_date,

            -- MAIN SORT KEY: collection.datetime if exists
            CASE
                WHEN col_agg.max_collection_datetime IS NOT NULL
                     AND col_agg.max_collection_datetime <> '0000-00-00 00:00:00'
                THEN col_agg.max_collection_datetime
                ELSE rbm_agg.first_datetime
            END AS sort_dt

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
                SUM(tepi2) AS sum_tepi2,
                MAX(`datetime`) AS max_collection_datetime
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
    //    Order by sort_dt (collection.datetime priority),
    //    then tx_date, then loan_code
    // ======================================================
    $sqlMerged = "
        SELECT *
        FROM (
            $sqlCredit
            UNION ALL
            $sqlDebit
        ) t
        ORDER BY
            t.sort_dt ASC,
            t.tx_date  ASC,
            t.loan_code ASC
    ";

    $query = mysql_query($sqlMerged);

    // ======================================================
    // 4) LOOP AND CALCULATE RUNNING BALANCE
    // ======================================================
    $html  = '';
    $count = 0;
    $current_closing_balance = $initial_closing_balance_monthly;

    while ($row = mysql_fetch_assoc($query)) {

        $loanStyle   = getLoanStyle($row['loan_code']);
        $loan_code_f = formatLoanCode($row['loan_code']);
        $customer_id = htmlspecialchars($row['customercode2'], ENT_QUOTES, 'UTF-8');
        $name        = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');

        $debit  = isset($row['debit'])  ? (float)$row['debit']  : 0.0;
        $credit = isset($row['credit']) ? (float)$row['credit'] : 0.0;

        // update running balance (keep your logic)
        $current_closing_balance -= $debit;
        $current_closing_balance += $credit;

        // show datetime so user can see ordering difference
        $show_dt = isset($row['sort_dt']) ? $row['sort_dt'] : $row['tx_date'];
        $show_dt_fmt = $show_dt ? date('d/m/Y', strtotime($show_dt)) : '';

        if ($debit != 0 || $credit != 0) {
            $count++;

            $html .= '<tr>
                        <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;border-left:1px solid black;text-align:center;">' . $count . '</td>

                        <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;color:black;">
                            <b>' . $show_dt_fmt . '</b>
                        </td>

                        <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;' . $loanStyle . '">
                            <b>' . $loan_code_f . '</b>
                        </td>

                        <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;">
                            <b>' . $customer_id . '</b>
                        </td>

                        <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;">
                            <b>RM ' . number_format($debit, 2) . '</b>
                        </td>

                        <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;">
                            <b>RM ' . number_format($credit, 2) . '</b>
                        </td>
                        <td style="border-right:1px solid black;border-bottom:1px solid black;color:red;">
                            <b>RM ' . number_format($current_closing_balance, 2) . '</b>
                        </td>
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

    header('Content-Type: text/html');
    echo $html;
    exit;
}
?>
