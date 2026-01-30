<?php
    include_once '../include/dbconnection.php';
    session_start();

    if (isset($_POST)) {
        $selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y');
        $selected_month = isset($_POST['month']) ? $_POST['month'] : date('m');
        $loan_code = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
        $customer_code = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
        $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

        $db = $_SESSION['login_database'];
        $results = [];

        // =============================
        //  1. Fetch from return_book_instalment (grouped by loan_code/month)
        // =============================
        // $sql = " SELECT 
        //         r.loan_code,
        //         r.year,
        //         r.month,
        //         latest_r.datetime AS latest_datetime,
        //         SUM(r.instalment) AS total_instalment,
        //         SUM(r.tepi) AS total_tepi,
        //         c.customercode2,
        //         c.name,
        //         hpd.balance
        //     FROM majusama_dev.return_book_instalment r
        //     JOIN majusama_dev.customer_loanpackage cl ON cl.loan_code = r.loan_code
        //     JOIN majusama_dev.customer_details c ON c.id = cl.customer_id

        //     -- Get the latest datetime per loan_code
        //     JOIN (
        //         SELECT loan_code, MAX(datetime) AS datetime
        //         FROM majusama_dev.return_book_instalment
        //         GROUP BY loan_code
        //     ) AS latest_r ON r.loan_code = latest_r.loan_code

        //     -- Join to get the correct balance from latest collection_id
        //     LEFT JOIN majusama_dev.half_payment_details hpd 
        //     ON hpd.collection_id = (
        //         SELECT r2.collection_id
        //         FROM majusama_dev.return_book_instalment r2
        //         WHERE r2.loan_code = r.loan_code
        //         ORDER BY r2.datetime DESC
        //         LIMIT 1
        //     )

        //     WHERE r.year = '$selected_year' AND r.month = '$selected_month'
            
        //     ";


        // if ($loan_code != '') {
        //     $sql .= " AND return_book_instalment.loan_code = '$loan_code'";
        // }
        // if ($customer_code != '') {
        //     $sql .= " AND customer_details.customercode2 = '$customer_code'";
        // }
        // if ($customer_name != '') {
        //     $sql .= " AND customer_details.name = '$customer_name'";
        // }

        // $sql .= " GROUP BY r.loan_code
        //     ORDER BY latest_r.datetime ASC";


        // $sql .= "
        //     -- Normal records, excluding Bad Debt
        //     SELECT
        //         r.loan_code,
        //         r.YEAR,
        //         r.MONTH,
        //         latest_r.datetime AS latest_datetime,
        //         SUM(r.instalment) AS total_instalment,
        //         SUM(r.tepi) AS total_tepi,
        //         c.customercode2,
        //         c.name,
        //         hpd.balance,
        //         col.salary_type
        //     FROM
        //         $db.return_book_instalment r
        //     JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        //     JOIN $db.customer_details c ON c.id = cl.customer_id
        //     LEFT JOIN $db.collection col ON r.collection_id = col.id
        //     JOIN (
        //         SELECT
        //             loan_code,
        //             MAX(datetime) AS datetime
        //         FROM $db.return_book_instalment
        //         GROUP BY loan_code
        //     ) AS latest_r ON r.loan_code = latest_r.loan_code
        //     LEFT JOIN $db.half_payment_details hpd ON hpd.collection_id = (
        //         SELECT r2.collection_id
        //         FROM $db.return_book_instalment r2
        //         WHERE r2.loan_code = r.loan_code
        //         ORDER BY r2.datetime DESC
        //         LIMIT 1
        //     )
        //     WHERE
        //         r.YEAR = '$selected_year'
        //         AND r.MONTH = '$selected_month'
        //         AND (
        //             col.salary_type IS NULL
        //             OR (
        //                 col.salary_type != 'Bad Debt' A
        //             )
        //         )
        //     GROUP BY r.loan_code

        //     UNION ALL

        //     -- Raw Bad Debt records
        //     SELECT
        //         r.loan_code,
        //         r.YEAR,
        //         r.MONTH,
        //         r.datetime AS latest_datetime,
        //         r.instalment AS total_instalment,
        //         r.tepi AS total_tepi,
        //         c.customercode2,
        //         c.name,
        //         hpd.balance,
        //         col.salary_type
        //     FROM
        //         $db.return_book_instalment r
        //     JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        //     JOIN $db.customer_details c ON c.id = cl.customer_id
        //     LEFT JOIN $db.collection col ON r.collection_id = col.id
        //     LEFT JOIN $db.half_payment_details hpd ON hpd.collection_id = r.collection_id
        //     WHERE
        //         r.YEAR = '$selected_year'
        //         AND r.MONTH = '$selected_month'
        //         AND col.salary_type = 'Bad Debt'

        // ";
        $sql .= "
            -- Grouped records: salary_type = Gaji AND instalment_type = Half Month
            SELECT
                r.loan_code,
                r.YEAR,
                r.MONTH,
                latest_r.datetime AS latest_datetime,
                SUM(r.instalment) AS total_instalment,
                SUM(r.tepi) AS total_tepi,
                c.customercode2,
                c.name,
                hpd.balance,
                col.salary_type,
                col.instalment_type
            FROM
                $db.return_book_instalment r
            JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
            JOIN $db.customer_details c ON c.id = cl.customer_id
            LEFT JOIN $db.collection col ON r.collection_id = col.id
            JOIN (
                SELECT
                    r1.loan_code,
                    MAX(r1.datetime) AS datetime
                FROM $db.return_book_instalment r1
                LEFT JOIN $db.collection col1 ON r1.collection_id = col1.id
                WHERE col1.salary_type = 'Gaji'
                AND col1.instalment_type = 'Half Month'
                GROUP BY r1.loan_code
            ) AS latest_r ON r.loan_code = latest_r.loan_code
            LEFT JOIN $db.half_payment_details hpd ON hpd.collection_id = (
                SELECT r2.collection_id
                FROM $db.return_book_instalment r2
                WHERE r2.loan_code = r.loan_code
                ORDER BY r2.datetime DESC
                LIMIT 1
            )
            WHERE
                r.YEAR = '$selected_year'
                AND r.MONTH = '$selected_month'
                AND col.salary_type = 'Gaji'
                AND col.instalment_type = 'Half Month'
            GROUP BY r.loan_code

            UNION ALL

            -- Raw records: everything else
            SELECT
                r.loan_code,
                r.YEAR,
                r.MONTH,
                r.datetime AS latest_datetime,
                r.instalment AS total_instalment,
                r.tepi AS total_tepi,
                c.customercode2,
                c.name,
                hpd.balance,
                col.salary_type,
                col.instalment_type
            FROM
                $db.return_book_instalment r
            JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
            JOIN $db.customer_details c ON c.id = cl.customer_id
            LEFT JOIN $db.collection col ON r.collection_id = col.id
            LEFT JOIN $db.half_payment_details hpd ON hpd.collection_id = r.collection_id
            WHERE
                r.YEAR = '$selected_year'
                AND r.MONTH = '$selected_month'
                AND NOT (col.salary_type = 'Gaji' AND col.instalment_type = 'Half Month')
        ";


        if ($loan_code != '') {
            $sql .= " AND r.loan_code = '$loan_code'";
        }
        if ($customer_code != '') {
            $sql .= " AND c.customercode2 = '$customer_code'";
        }
        if ($customer_name != '') {
            $sql .= " AND c.name = '$customer_name'";
        }

        $sql .= "
            ORDER BY latest_datetime ASC
        ";

        var_dump($sql);
        $query = mysql_query($sql);
        while ($row = mysql_fetch_assoc($query)) {
            // Skip rows where balance is NOT zero
            if ($row['balance'] != 0) {
                continue;
            }

            $results[] = [
                'datetime' => $row['latest_datetime'],
                'loan_code' => $row['loan_code'],
                'customer_id' => $row['customercode2'],
                'name' => $row['name'],
                'instalment' => $row['total_instalment'],
                'tepi' => $row['total_tepi'],
            ];
        }

        // while ($row = mysql_fetch_assoc($query)) {
        //     $color_style = ($row['balance'] != 0) ? 'color: blue;' : 'color: black;';
        //     $results[] = [
        //         'datetime' => $row['latest_datetime'],
        //         'loan_code' => $row['loan_code'],
        //         'customer_id' => $row['customercode2'],
        //         'name' => $row['name'],
        //         'instalment' => $row['total_instalment'],
        //         'tepi' => $row['total_tepi'],
        //         'color' => $color_style,
        //     ];
        // }

        // =============================
        //  2. Fetch SETTLE payments from loan_payment_details
        // =============================
        $settle = "SELECT
                    t1.*,
                    t2.loan_amount,
                    t2.payout_date,
                    t2.discount,
                    t3.customercode2,
                    t3.name,
                    t4.collection_amount
                FROM
                    $db.loan_payment_details t1
                LEFT JOIN $db.customer_loanpackage t2 ON t2.id = t1.customer_loanid
                LEFT JOIN $db.customer_details t3 ON t3.id = t2.customer_id
                LEFT JOIN $db.collection t4 ON t4.loan_code = t1.receipt_no AND t4.instalment_month = '$selected_year-$selected_month'
                WHERE t1.receipt_no != ''
                    AND t1.loan_status = 'SETTLE'
                    AND t1.month_receipt LIKE '$selected_year-$selected_month'
                ORDER BY t1.payment_date ASC";
        // var_dump($settle);
        if ($loan_code != '') {
            $settle .= " AND t1.receipt_no = '$loan_code'";
        }
        if ($customer_code != '') {
            $settle .= " AND t3.customercode2 = '$customer_code'";
        }
        if ($customer_name != '') {
            $settle .= " AND t3.name = '$customer_name'";
        }

        $query = mysql_query($settle);
        while ($row = mysql_fetch_assoc($query)) {
            $amount = isset($row['collection_amount']) ? $row['collection_amount'] :
                      (isset($row['discount']) ? $row['balance'] - $row['discount'] : $row['balance']);

            $results[] = [
                'datetime' => $row['payment_date'],
                'loan_code' => $row['receipt_no'],
                'customer_id' => $row['customercode2'],
                'name' => $row['name'],
                'instalment' => $amount,
                'tepi' => '-', // No tepi for settled payments
            ];
        }

        // =============================
        //  3. Fetch unmatched return_book_monthly (not in return_book_instalment)
        // =============================
        // $sql = "SELECT
        //             return_book_monthly.*,
        //             customer_details.customercode2,
        //             customer_details.name
        //         FROM
        //             $db.return_book_monthly
        //         JOIN $db.monthly_payment_record ON monthly_payment_record.loan_code = return_book_monthly.loan_code
        //             AND DATE_FORMAT(monthly_payment_record.payment_date, '%Y-%m') = '$selected_year-$selected_month'
        //         JOIN $db.customer_details ON customer_details.id = monthly_payment_record.customer_id
        //         WHERE
        //             return_book_monthly.year = '$selected_year'
        //             AND return_book_monthly.month = '$selected_month'
        //         GROUP BY monthly_payment_record.loan_code
        //         ORDER BY return_book_monthly.datetime ASC";
        $sql = "SELECT
                    return_book_monthly.*,
                    customer_details.customercode2,
                    customer_details.name,
                    collection.tepi2
                FROM
                    $db.return_book_monthly
                JOIN $db.monthly_payment_record 
                    ON monthly_payment_record.loan_code = return_book_monthly.loan_code
                    -- AND DATE_FORMAT(monthly_payment_record.payment_date, '%Y-%m') = '$selected_year-$selected_month'
                JOIN $db.customer_details 
                    ON customer_details.id = monthly_payment_record.customer_id
                LEFT JOIN $db.half_payment_details 
                    ON half_payment_details.collection_id = return_book_monthly.collection_id AND half_payment_details.balance > 0
                LEFT JOIN $db.collection 
                    ON return_book_monthly.collection_id = collection.id
                WHERE
                    return_book_monthly.year = '$selected_year'
                    AND return_book_monthly.month = '$selected_month'
                    AND collection.instalment_type != 'Instalment'
                GROUP BY monthly_payment_record.loan_code
                ORDER BY return_book_monthly.datetime ASC";

            if ($loan_code != '') {
                $sql .= " AND return_book_monthly.loan_code = '$loan_code'";
            }
            if ($customer_code != '') {
                $sql .= " AND customer_details.customercode2 = '$customer_code'";
            }
            if ($customer_name != '') {
                $sql .= " AND customer_details.name = '$customer_name'";
            }

            var_dump($sql);
            $query = mysql_query($sql);
            while ($row = mysql_fetch_assoc($query)) {
                $results[] = [
                    'datetime' => $row['datetime'],
                    'loan_code' => $row['loan_code'],
                    'customer_id' => $row['customercode2'],
                    'name' => $row['name'],
                    'instalment' => '-', // No instalment
                    'tepi' => $row['tepi'],
                    'tepi2' => $row['tepi2'],
                ];
            }

            // =============================
            //  4. Merge records (only Gaji + Half Month), keep others raw
            // =============================
            $mergedResults = [];
            foreach ($results as $row) {
                $isBadDebt   = isset($row['salary_type']) && $row['salary_type'] === 'Bad Debt';
                $isMergeable = isset($row['salary_type'], $row['instalment_type']) 
                            && $row['salary_type'] === 'Gaji' 
                            && $row['instalment_type'] === 'Half Month';

                if ($isBadDebt || !$isMergeable) {
                    // Keep Bad Debt and all non-mergeable records as they are
                    $mergedResults[] = $row;
                } else {
                    // Merge key for loan_code + customer_id
                    $key = $row['loan_code'] . '|' . $row['customer_id'];

                    if (!isset($mergedResults[$key])) {
                        $mergedResults[$key] = $row;
                    } else {
                        // Merge instalment & tepi amounts
                        if (is_numeric($row['instalment'])) {
                            $mergedResults[$key]['instalment'] += $row['instalment'];
                        }
                        if (is_numeric($row['tepi'])) {
                            $mergedResults[$key]['tepi'] += $row['tepi']; // enable merging tepi if required
                        }

                        // Latest datetime
                        if (strtotime($row['datetime']) > strtotime($mergedResults[$key]['datetime'])) {
                            $mergedResults[$key]['datetime'] = $row['datetime'];
                        }
                    }
                }
            }

            // // If you want consistent result format (numeric + keyed merge), reindex array
            // $mergedResults = array_values($mergedResults);


            // Re-index array for sorting
            $results = array_values($mergedResults);

            // =============================
            //  5. Sort All Results by Date (ASC)
            // =============================
            usort($results, function ($a, $b) {
                return strtotime($a['datetime']) - strtotime($b['datetime']);
            });

        // =============================
        //  5. Build HTML Table Output
        // =============================
        $html = '';
        $count = 0;

        foreach ($results as $row) {
            $count++;

            // If tepi2 has a value, add it to tepi
            $displayTepi = $row['tepi'];
            if (is_numeric($row['tepi2']) && $row['tepi2'] > 0) {
                $displayTepi += $row['tepi2'];
            }
            $html .= '<tr>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; border-left:1px solid black; text-align: center;">' . $count . '</td>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; text-align: center;"><b>' . date('d/m/Y', strtotime($row['datetime'])) . '</b></td>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; text-align: center;"><b>' . $row['loan_code'] . '</b></td>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; text-align: center;"><b>' . $row['customer_id'] . '</b></td>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black;"><b>' . $row['name'] . '</b></td>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black;"><b>' . (is_numeric($row['instalment']) ? 'RM ' . number_format($row['instalment'], 2) : '-') . '</b></td>
                        <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black;"><b>' . (is_numeric($displayTepi) ? 'RM ' . number_format($displayTepi, 2) : '-') . '</b></td>
                    </tr>';
        }

        if ($count == 0) {
            $html .= '<tr>
                        <td colspan="12" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
                    </tr>';
        }

        // =============================
        //  6. Output HTML
        // =============================
        header('Content-Type: text/html');
        echo $html;
        exit;
    }
?>