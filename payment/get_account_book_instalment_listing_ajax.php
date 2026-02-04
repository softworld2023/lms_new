<?php
include_once '../include/dbconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selected_year   = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $selected_month  = isset($_POST['month']) ? $_POST['month'] : date('m');
    $loan_code       = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
    $customer_code   = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
    $customer_name   = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

    $db = $_SESSION['login_database'];
    $period = $selected_year . '-' . $selected_month; // YYYY-MM

    // =============================
    //  Loan Code Style Rules
    // =============================
    function getLoanCodeStyle($loan_code) {
        $prefix = substr($loan_code, 0, 2);
        if ($prefix == 'SD') return '';
        $allowed = array('AS','SB','BP','KT','MS','MJ','PP','NG','TS','LT','BT','DK','CL');
        if (!in_array($prefix, $allowed)) return "style='color:#FF0000'";
        return '';
    }

    function formatLoanCodeText($loan_code) {
        $red_prefix = array('SB','MS','MJ','PP','CL','BT');
        if (in_array(substr($loan_code, 0, 2), $red_prefix)) {
            return preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>', $loan_code);
        }
        return $loan_code;
    }

    // =============================
    // Initial Closing Balance
    // =============================
    $sql = "SELECT initial_closing_balance_instalment
            FROM $db.account_book_instalment
            WHERE year = '$selected_year' AND month = '$selected_month'";
    $q   = mysql_query($sql);
    $res = mysql_fetch_assoc($q);

    $initial_closing_balance_instalment = isset($res['initial_closing_balance_instalment'])
        ? (float)$res['initial_closing_balance_instalment']
        : 0.0;

    $entries        = array();
    $grouped_debits = array();

    // =====================================================================
    // 1) PAID Loan Packages -> CREDIT
    // =====================================================================
    $package = "SELECT
                    t2.*,
                    t2.loan_code,
                    t2.customer_id,
                    t1.customercode2,
                    t2.loan_amount,
                    t2.payout_date,
                    t2.start_month
                FROM $db.customer_loanpackage t2
                LEFT JOIN $db.customer_details t1 ON t2.customer_id = t1.id
                WHERE t2.start_month = '$period'
                  AND t2.loan_status = 'PAID'";

    if ($loan_code != '')     $package .= " AND t2.loan_code = '$loan_code'";
    if ($customer_code != '') $package .= " AND t1.customercode2 = '$customer_code'";
    if ($customer_name != '') $package .= " AND t1.name = '$customer_name'";

    // stable order (main sort will be later)
    $package .= " ORDER BY t2.payout_date ASC, t2.id ASC";

    $package_query = mysql_query($package);
    while ($row = mysql_fetch_assoc($package_query)) {

        $loan_amount   = (float)$row['loan_amount'];
        $amount_actual = $loan_amount * 0.9;

        $customer_id = (!empty($row['customercode2']))
            ? $row['customercode2']
            : 'UNREGISTERED - ' . $row['customer_id'];

        $loan_codes = $row['loan_code'];

        $payout_raw = trim((string)$row['payout_date']);
        if ($payout_raw === '' || $payout_raw === '0000-00-00' || $payout_raw === '0000-00-00 00:00:00') {
            if (!empty($row['start_month']) && $row['start_month'] !== '0000-00-00') {
                $date = $row['start_month'] . '-01 00:00:00';
            } else {
                $date = $period . '-01 00:00:00';
            }
        } else {
            $date = $payout_raw;
        }

        // NOTE:
        // - date    : your original display date logic
        // - sort_dt : for sorting (we keep it same as date for credit rows)
        $entries[] = array(
            'date'        => $date,
            'sort_dt'     => $date,
            'loan_code'   => $loan_codes,
            'customer_id' => $customer_id,
            'debit'       => 0.0,
            'credit'      => $amount_actual,
        );
    }

    // =====================================================================
    // 2) Payments (COLLECTED / SETTLE) + BAD DEBT
    // Main sort: follow collection.datetime
    // =====================================================================
    $sql = "SELECT
                t1.*,
                t2.loan_amount,
                t2.loan_status AS bad_debt_status,
                t3.customercode2,
                t3.name,
                t4.salary_type,
                t4.settle_money,
                t4.instalment_money,
                t4.max_collection_datetime
            FROM $db.loan_payment_details t1
            LEFT JOIN $db.customer_loanpackage t2 ON t2.id = t1.customer_loanid
            LEFT JOIN $db.customer_details t3 ON t3.id = t2.customer_id

            LEFT JOIN (
                SELECT
                    loan_code,
                    instalment_month,
                    salary_type,
                    SUM(CASE WHEN salary_type = 'settlement' THEN instalment ELSE 0 END) AS settle_money,
                    SUM(CASE WHEN salary_type <> 'settlement' THEN instalment ELSE 0 END) AS instalment_money,
                    MAX(`datetime`) AS max_collection_datetime
                FROM $db.collection
                GROUP BY loan_code, instalment_month
            ) t4
              ON t4.loan_code = t1.receipt_no
             AND t4.instalment_month = '$period'

            WHERE t1.receipt_no != ''
              AND (t1.loan_status = 'COLLECTED' OR t1.loan_status = 'SETTLE')
              AND t1.month_receipt LIKE '$period%'";

    if ($loan_code != '')     $sql .= " AND t2.loan_code = '$loan_code'";
    if ($customer_code != '') $sql .= " AND t3.customercode2 = '$customer_code'";
    if ($customer_name != '') $sql .= " AND t3.name = '$customer_name'";

    // stable fetch order by collection.datetime (fallback payment_date)
    $sql .= " ORDER BY COALESCE(t4.max_collection_datetime, t1.payment_date) ASC, t1.id ASC";
// var_dump($sql);
// exit;
    $query = mysql_query($sql);

    $seen_collected = array();
    $seen_bad_debt  = array();

    while ($row = mysql_fetch_assoc($query)) {

        $colAmount      = 0;
        $loan_code_row  = (string)$row['receipt_no'];
        $salary_type    = $row['salary_type'];
        $month_receipt  = trim((string)$row['month_receipt']);
        $month_key      = substr($month_receipt, 0, 7); // YYYY-MM

        $loan_status    = strtoupper(trim((string)$row['loan_status']));
        $deleted_status = strtoupper(trim((string)$row['deleted_status']));

        $isBadDebtLoan  = isset($row['bad_debt_status']) &&
                          strtoupper(trim((string)$row['bad_debt_status'])) === 'BAD DEBT';

        $customer_id = (!empty($row['customercode2']))
            ? $row['customercode2']
            : 'UNREGISTERED - ' . $row['customer_loanid'];

        // display date (keep your logic)
        $payment_raw = trim((string)$row['payment_date']);
        if ($payment_raw === '' || $payment_raw === '0000-00-00' || $payment_raw === '0000-00-00 00:00:00') {
            $mr_raw = trim((string)$row['month_receipt']);
            $payment_date = ($mr_raw !== '' && $mr_raw !== '0000-00-00' && $mr_raw !== '0000-00-00 00:00:00')
                ? $mr_raw
                : $period . '-01 00:00:00';
        } else {
            $payment_date = $payment_raw;
        }

        // sort_dt = collection.datetime if exists, else fallback to display date
        $sort_dt = trim((string)(isset($row['max_collection_datetime']) ? $row['max_collection_datetime'] : ''));
        if ($sort_dt === '' || $sort_dt === '0000-00-00' || $sort_dt === '0000-00-00 00:00:00') {
            $sort_dt = $payment_date;
        }

        // BAD DEBT: expand each collection row, each row sorted by its own collection.datetime
        if ($isBadDebtLoan || $salary_type == 'Bad Debt') {
            $bd_key = $loan_code_row . '|' . $month_key;
            if (!isset($seen_bad_debt[$bd_key])) {
                $seen_bad_debt[$bd_key] = true;

                $colSql = "SELECT instalment, `datetime`, id
                           FROM $db.collection
                           WHERE loan_code = '$loan_code_row'
                             AND instalment_month = '$period'
                           ORDER BY `datetime` ASC, id ASC";
                $colQ = mysql_query($colSql);

                while ($crow = mysql_fetch_assoc($colQ)) {
                    $colAmount = (float)$crow['instalment'];
                    if ($colAmount == 0) continue;

                    $col_dt = trim((string)$crow['datetime']);
                    if ($col_dt === '' || $col_dt === '0000-00-00' || $col_dt === '0000-00-00 00:00:00') {
                        $col_dt = $sort_dt;
                    }

                    $entries[] = array(
                        'date'        => $payment_date, // keep display behavior
                        'sort_dt'     => $col_dt,       // main sort
                        'loan_code'   => $loan_code_row,
                        'customer_id' => $customer_id,
                        'debit'       => $colAmount,
                        'credit'      => 0.0,
                    );
                }
            }
            continue;
        }

        $debit = 0.0;

        // SETTLE
        if ($loan_status === 'SETTLE') {

            $base_settle = (!empty($row['settle_money']) && (float)$row['settle_money'] > 0)
                ? (float)$row['settle_money']
                : (float)$row['payment'];

            $deduct = 0.0;
            if ($deleted_status === 'YES' && (float)$row['monthly'] > 0) {
                $deduct = (float)$row['monthly'];
            } elseif (!empty($row['instalment_money']) && (float)$row['instalment_money'] > 0) {
                $deduct = (float)$row['instalment_money'];
            }

            $debit = $base_settle - $deduct;

        } else {
            // COLLECTED (normal instalment)
            $key = $loan_code_row . '|' . $month_key;
            if (isset($seen_collected[$key])) {
                $debit = 0.0;
            } else {
                $seen_collected[$key] = true;
                $debit = (float)$row['monthly'];
            }
        }

        if ($debit == 0 && $colAmount == 0) continue;

        // group by loan_code (keep your logic) BUT store latest sort_dt so ordering is correct
        if (!isset($grouped_debits[$loan_code_row])) {

            $grouped_debits[$loan_code_row] = array(
                'date'        => $payment_date, // display
                'sort_dt'     => $sort_dt,      // for ordering
                'customer_id' => $customer_id,
                'total_debit' => 0.0,
            );
        } else {            
            // keep LATEST collection.datetime (so it lands at the bottom in ASC)
            if (strtotime($sort_dt) > strtotime($grouped_debits[$loan_code_row]['sort_dt'])) {
                $grouped_debits[$loan_code_row]['sort_dt'] = $sort_dt;
                $grouped_debits[$loan_code_row]['date']    = $payment_date;
            }
        }

        $grouped_debits[$loan_code_row]['total_debit'] += $debit;
    }

    // convert grouped debits into entries
    foreach ($grouped_debits as $lc => $data) {
        $entries[] = array(
            'date'        => $data['date'],
            'sort_dt'     => $data['sort_dt'],
            'loan_code'   => $lc,
            'customer_id' => $data['customer_id'],
            'debit'       => $data['total_debit'],
            'credit'      => 0.0,
        );
    }

    // =====================================================================
    // SORT ALL: by collection.datetime (sort_dt) ASC => latest at bottom
    // =====================================================================
    $indexedEntries = array();
    foreach ($entries as $idx => $entry) {
        if (!isset($entry['sort_dt']) || $entry['sort_dt'] === '') $entry['sort_dt'] = $entry['date'];
        $indexedEntries[] = array('idx' => $idx, 'entry' => $entry);
    }

    usort($indexedEntries, function ($a, $b) {
        $a_dt = (isset($a['entry']['sort_dt']) && $a['entry']['sort_dt'] !== '') ? $a['entry']['sort_dt'] : $a['entry']['date'];
        $b_dt = (isset($b['entry']['sort_dt']) && $b['entry']['sort_dt'] !== '') ? $b['entry']['sort_dt'] : $b['entry']['date'];

        $ta = strtotime($a_dt);
        $tb = strtotime($b_dt);

        if ($ta == $tb) {
            // tie-breaker: loan_code then insertion order
            $lc = strcmp((string)$a['entry']['loan_code'], (string)$b['entry']['loan_code']);
            if ($lc !== 0) return $lc;
            return ($a['idx'] < $b['idx']) ? -1 : 1;
        }

        return ($ta < $tb) ? -1 : 1; // ASC
    });

    $entries = array();
    foreach ($indexedEntries as $item) $entries[] = $item['entry'];

    // =====================================================================
    // build HTML (SHOW DATETIME so user can see the correct order)
    // =====================================================================
    $html = '';
    $count = 0;
    $current_closing_balance = $initial_closing_balance_instalment;

    foreach ($entries as $row) {

        $loanStyle = getLoanCodeStyle($row['loan_code']);
        $loanText  = formatLoanCodeText($row['loan_code']);

        $count++;

        $debit  = (float)$row['debit'];
        $credit = (float)$row['credit'];

        $current_closing_balance += $debit;
        $current_closing_balance -= $credit;

        // ✅ display uses sort_dt (collection.datetime) so you can see time differences
        $show_dt = (isset($row['sort_dt']) && $row['sort_dt'] !== '') ? $row['sort_dt'] : $row['date'];
        $show_dt_fmt = date('d/m/Y', strtotime($show_dt));
            
        $html .= '<tr>
            <td style="border-right:1px solid black;border-bottom:1px solid black;border-left:1px solid black;color:black;text-align:center;">' . $count . '</td>
            <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;color:black;"><b>' . $show_dt_fmt . '</b></td>
            <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;" ' . $loanStyle . '><b>' . $loanText . '</b></td>
            <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;"><b>' . $row['customer_id'] . '</b></td>
            <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($debit, 2) . '</b></td>
            <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($credit, 2) . '</b></td>
            <td style="border-right:1px solid black;border-bottom:1px solid black;color:red;"><b>RM ' . number_format($current_closing_balance, 2) . '</b></td>
        </tr>';
    }

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
