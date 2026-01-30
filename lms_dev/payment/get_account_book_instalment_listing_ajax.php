<?php
// =============================
//  Session & Database Setup
// =============================
include_once '../include/dbconnection.php';
session_start();

if (isset($_POST)) {
    // =============================
    //  Initialize Filters
    // ============================= 
    $selected_year   = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $selected_month  = isset($_POST['month']) ? $_POST['month'] : date('m');
    $loan_code       = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
    $customer_code   = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
    $customer_name   = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

    $db = $_SESSION['login_database'];

    // =============================
    //  Get Initial Closing Balance
    // =============================
    $sql = "SELECT initial_closing_balance_instalment
            FROM $db.account_book_instalment
            WHERE year = '$selected_year' AND month = '$selected_month'";
    $q   = mysql_query($sql);
    $res = mysql_fetch_assoc($q);

    $initial_closing_balance_instalment = isset($res['initial_closing_balance_instalment'])
        ? (float)$res['initial_closing_balance_instalment']
        : 0.0;

    // We'll collect ALL rows here, then sort by date
    $entries        = [];
    $grouped_debits = []; // for normal / SETTLE (non Bad Debt) – group by loan_code

    // =====================================================================
    //  1. Fetch PAID Loan Packages (First Outflow) -> CREDIT entries
    // =====================================================================
    $package = "SELECT
                    t2.*,
                    t2.loan_code,
                    t2.customer_id,
                    t1.customercode2,
                    t2.loan_amount,
                    t2.payout_date,
                    t2.start_month
                FROM
                    $db.customer_loanpackage t2
                LEFT JOIN 
                    $db.customer_details t1 ON t2.customer_id = t1.id
                WHERE
                    t2.start_month = '$selected_year-$selected_month'
                  AND t2.loan_status = 'PAID'";

    if ($loan_code != '') {
        $package .= " AND t2.loan_code = '$loan_code'";
    }
    if ($customer_code != '') {
        $package .= " AND t1.customercode2 = '$customer_code'";
    }
    if ($customer_name != '') {
        $package .= " AND t1.name = '$customer_name'";
    }

    // old -> new by payout_date
    $package .= " ORDER BY t2.payout_date ASC, t2.id ASC";

    $package_query = mysql_query($package);

    while ($row = mysql_fetch_assoc($package_query)) {
        $loan_amount   = (float)$row['loan_amount'];
        $amount_actual = $loan_amount * 0.9;

        $customer_id = (isset($row['customercode2']) && !empty($row['customercode2']))
            ? $row['customercode2']
            : 'UNREGISTERED - ' . $row['customer_id'];

        $loan_codes = $row['loan_code'];

        // --- Clean payout_date: avoid 0000-00-00 breaking the sort
        $payout_raw = trim($row['payout_date']);
        if (
            $payout_raw === '' ||
            $payout_raw === '0000-00-00' ||
            $payout_raw === '0000-00-00 00:00:00'
        ) {
            // If we have start_month, use that as the base (e.g. 2025-11 -> 2025-11-01)
            if (!empty($row['start_month']) && $row['start_month'] !== '0000-00-00') {
                $date = $row['start_month'] . '-01 00:00:00';
            } else {
                $date = $selected_year . '-' . $selected_month . '-01 00:00:00';
            }
        } else {
            $date = $payout_raw;
        }

        $entries[] = [
            'date'        => $date,
            'loan_code'   => $loan_codes,
            'customer_id' => $customer_id,
            'debit'       => 0.0,
            'credit'      => $amount_actual,
        ];
    }

    // =====================================================================
    //  2. Fetch Instalment Payment Records (Inflow / Outflow)
    //      (collection aggregated per loan_code + instalment_month)
    // =====================================================================
    $sql = "SELECT
                t1.*,
                t2.loan_amount,
                t2.discount,
                t2.payout_date,
                t2.loan_status AS bad_debt_status,
                t3.customercode2,
                t3.name,
                t4.collection_money
            FROM
                $db.loan_payment_details t1
            LEFT JOIN $db.customer_loanpackage t2 ON t2.id = t1.customer_loanid
            LEFT JOIN $db.customer_details t3      ON t3.id = t2.customer_id
            LEFT JOIN (
                SELECT 
                    loan_code,
                    instalment_month,
                    SUM(instalment) AS collection_money
                FROM $db.collection
                GROUP BY loan_code, instalment_month
            ) t4
                ON t4.loan_code = t1.receipt_no
               AND t4.instalment_month = '$selected_year-$selected_month'
            WHERE t1.receipt_no != ''
              AND (t1.loan_status = 'COLLECTED' OR t1.loan_status = 'SETTLE')
              AND t1.month_receipt LIKE '$selected_year-$selected_month' ";

    if ($loan_code != '') {
        $sql .= " AND t2.loan_code = '$loan_code'";
    }
    if ($customer_code != '') {
        $sql .= " AND t3.customercode2 = '$customer_code'";
    }
    if ($customer_name != '') {
        $sql .= " AND t3.name = '$customer_name'";
    }

    // old -> new by payment_date
    $sql .= " ORDER BY 
                CASE 
                    WHEN t1.month_receipt = '' OR t1.month_receipt IS NULL THEN 0 
                    ELSE 1 
                END ASC,
                t1.payment_date ASC,
                t1.id ASC";

    $query = mysql_query($sql);

    // Remember:
    // - which (loan_code, YYYY-MM) we already counted for COLLECTED
    // - which (loan_code, YYYY-MM) we already expanded for BAD DEBT
    $seen_collected = [];
    $seen_bad_debt  = [];

    while ($row = mysql_fetch_assoc($query)) {
        $loan_code_row = $row['receipt_no'];
        $month_receipt = $row['month_receipt'];   // may be '2025-11-01', '2025-11-15', etc.
        $month_key     = substr($month_receipt, 0, 7); // '2025-11'

        $loan_amount = (isset($row['collection_money']) && !empty($row['collection_money']))
            ? (float)$row['collection_money']
            : (float)$row['loan_amount'];

        $loan_status   = $row['loan_status'];  // COLLECTED / SETTLE
        $isBadDebtLoan = isset($row['bad_debt_status']) &&
                         strtoupper($row['bad_debt_status']) === 'BAD DEBT';

        $customer_id = (isset($row['customercode2']) && !empty($row['customercode2']))
            ? $row['customercode2']
            : 'UNREGISTERED - ' . $row['customer_loanid'];

        // --- Clean payment_date: avoid 0000-00-00 causing "year 0" sort
        $payment_raw = trim($row['payment_date']);
        if (
            $payment_raw === '' ||
            $payment_raw === '0000-00-00' ||
            $payment_raw === '0000-00-00 00:00:00'
        ) {
            // Prefer month_receipt if it is a valid date
            $mr_raw = trim($row['month_receipt']);
            if (
                $mr_raw !== '' &&
                $mr_raw !== '0000-00-00' &&
                $mr_raw !== '0000-00-00 00:00:00'
            ) {
                $payment_date = $mr_raw;
            } else {
                // final fallback: first day of selected month
                $payment_date = $selected_year . '-' . $selected_month . '-01 00:00:00';
            }
        } else {
            $payment_date = $payment_raw;
        }

        $debit  = 0.0;
        $credit = 0.0;

        // 2A. No Receipt = First Outflow (CREDIT)
        if (empty($month_receipt)) {
            $sql2 = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
            $q2   = mysql_query($sql2);
            $res2 = mysql_fetch_assoc($q2);

            $processing_fee = (float)$res2['processing_fee'];
            $sd             = (float)$res2['stamp_duty'];
            $amount         = $loan_amount - $processing_fee - $sd;

            $credit = $amount;

            if ($credit != 0) {
                $entries[] = [
                    'date'        => $payment_date,
                    'loan_code'   => $loan_code_row,
                    'customer_id' => $customer_id,
                    'debit'       => 0.0,
                    'credit'      => $credit,
                ];
            }
        } else {
            // 2B. Payment / Settle / Bad Debt Case
            $sql2 = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
            $q2   = mysql_query($sql2);
            $res2 = mysql_fetch_assoc($q2);

            $processing_fee = (float)$res2['processing_fee'];
            $sd             = (float)$res2['stamp_duty'];
            $amount         = $loan_amount - $processing_fee - $sd;

            if ($loan_status == 'OUT') {
                // unlikely here, but keep for safety
                $credit = $loan_amount * 0.9;
                if ($credit != 0) {
                    $entries[] = [
                        'date'        => $payment_date,
                        'loan_code'   => $loan_code_row,
                        'customer_id' => $customer_id,
                        'debit'       => 0.0,
                        'credit'      => $credit,
                    ];
                }
            } else {

                // ===== SPECIAL HANDLING: BAD DEBT =====
                if ($isBadDebtLoan) {
                    // Only expand once per (loan_code, month)
                    $bd_key = $loan_code_row . '|' . $month_key;
                    if (!isset($seen_bad_debt[$bd_key])) {
                        $seen_bad_debt[$bd_key] = true;

                        // Pull ALL collection rows for this loan & month
                        // (so both 4372 and 3598 become separate entries)
                        $colSql = "
                            SELECT instalment
                            FROM $db.collection
                            WHERE loan_code = '$loan_code_row'
                              AND instalment_month = '$selected_year-$selected_month'
                        ";
                        $colQ = mysql_query($colSql);

                        while ($crow = mysql_fetch_assoc($colQ)) {
                            $colAmount = (float)$crow['instalment'];
                            if ($colAmount == 0) {
                                continue;
                            }

                            // Use payment_date as the row date
                            $colDate = $payment_date;

                            $entries[] = [
                                'date'        => $colDate,
                                'loan_code'   => $loan_code_row,
                                'customer_id' => $customer_id,
                                'debit'       => $colAmount, // each BAD DEBT collection row
                                'credit'      => 0.0,
                            ];
                        }
                    }

                    // Skip the normal debit logic below for BAD DEBT
                    continue;
                }

                // ===== normal / SETTLE inflows (non BAD DEBT) =====
                if ($loan_status == 'SETTLE') {
                    // SETTLE logic keeps using collection_money / loan_percent as you had
                    if ($row['deleted_status'] == "YES") {
                        $debit = ((isset($row['collection_money']) && !empty($row['collection_money']))
                                  ? (float)$row['collection_money']
                                  : (float)$row['loan_percent']) - (float)$row['monthly'];
                    } else {
                        $debit = (isset($row['collection_money']) && !empty($row['collection_money']))
                                 ? (float)$row['collection_money']
                                 : (float)$row['payment'];
                    }

                } else {
                    // normal COLLECTED instalments
                    // ✅ only count once per (loan_code, month) no matter how many half-month rows
                    $key = $loan_code_row . '|' . $month_key; // e.g. 'BP24215|2025-11'
                    if (isset($seen_collected[$key])) {
                        $debit = 0.0; // already included this month → skip
                    } else {
                        $seen_collected[$key] = true;
                        // For the ledger, trust loan_payment_details.monthly, NOT aggregated collection.
                        $debit = (float)$row['monthly'];
                    }
                }

                if ($debit == 0) {
                    continue;
                }

                // Normal / SETTLE -> group by loan_code
                if (!isset($grouped_debits[$loan_code_row])) {
                    $grouped_debits[$loan_code_row] = [
                        'date'        => $payment_date,    // earliest payment date
                        'customer_id' => $customer_id,
                        'total_debit' => 0.0,
                    ];
                } else {
                    // keep earliest date for ordering
                    if (strtotime($payment_date) < strtotime($grouped_debits[$loan_code_row]['date'])) {
                        $grouped_debits[$loan_code_row]['date'] = $payment_date;
                    }
                }
                $grouped_debits[$loan_code_row]['total_debit'] += $debit;
            }
        }
    }

    // =====================================================================
    //  3. Convert grouped normal/SETTLE into entries
    // =====================================================================
    foreach ($grouped_debits as $loan_code_row => $data) {
        $entries[] = [
            'date'        => $data['date'],
            'loan_code'   => $loan_code_row,
            'customer_id' => $data['customer_id'],
            'debit'       => $data['total_debit'],
            'credit'      => 0.0,
        ];
    }

    // =====================================================================
    //  4. Sort ALL entries by date old -> new (stable by original order)
    // =====================================================================
    $indexedEntries = [];
    foreach ($entries as $idx => $entry) {
        $indexedEntries[] = [
            'idx'   => $idx,    // original position
            'entry' => $entry,
        ];
    }

    usort($indexedEntries, function ($a, $b) {
        $ta = strtotime($a['entry']['date']);
        $tb = strtotime($b['entry']['date']);

        if ($ta == $tb) {
            // Same date → keep original push order
            if ($a['idx'] == $b['idx']) {
                return 0;
            }
            return ($a['idx'] < $b['idx']) ? -1 : 1;
        }

        // Ascending by date
        return ($ta < $tb) ? -1 : 1;
    });

    // Flatten back to plain $entries
    $entries = [];
    foreach ($indexedEntries as $item) {
        $entries[] = $item['entry'];
    }

    // =====================================================================
    //  5. Build HTML + running balance
    // =====================================================================
    $html                    = '';
    $count                   = 0;
    $current_closing_balance = $initial_closing_balance_instalment;

    foreach ($entries as $row) {
        $count++;

        $debit  = (float)$row['debit'];
        $credit = (float)$row['credit'];

        // debit = inflow (+), credit = outflow (-)
        $current_closing_balance += $debit;
        $current_closing_balance -= $credit;

        $html .= '<tr>
                    <td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black; text-align: center;">' . $count . '</td>
                    <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;"><b>' . $row['loan_code'] . '</b></td>
                    <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;"><b>' . $row['customer_id'] . '</b></td>
                    <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>RM ' . number_format($debit, 2) . '</b></td>
                    <td style="border-right:1px solid black;border-bottom: 1px solid black;color: red;"><b>RM ' . number_format($credit, 2) . '</b></td>
                    <td id="balance-' . $count . '" style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>RM ' . number_format($current_closing_balance, 2) . '</b></td>
                  </tr>';
    }

    // =============================
    //  Handle Empty Results
    // =============================
    if ($count == 0) {
        $html .= '<tr>
                    <td colspan="12" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
                  </tr>';
    }

    // =============================
    //  Output Final HTML Table
    // =============================
    header('Content-Type: text/html');
    echo $html;
    exit;
}
?>
