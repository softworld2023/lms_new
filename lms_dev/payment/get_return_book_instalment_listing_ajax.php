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

        //old
        // $sql .= "
        //     -- Grouped records: salary_type = Gaji AND instalment_type = Half Month
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
        //         col.salary_type,
        //         col.instalment_type
        //     FROM
        //         $db.return_book_instalment r
        //     JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        //     JOIN $db.customer_details c ON c.id = cl.customer_id
        //     LEFT JOIN $db.collection col ON r.collection_id = col.id
        //     JOIN (
        //         SELECT
        //             r1.loan_code,
        //             MAX(r1.datetime) AS datetime
        //         FROM $db.return_book_instalment r1
        //         LEFT JOIN $db.collection col1 ON r1.collection_id = col1.id
        //         WHERE col1.salary_type = 'Gaji'
        //         AND col1.instalment_type = 'Half Month'
        //         GROUP BY r1.loan_code
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
        //         AND col.salary_type = 'Gaji'
        //         AND col.instalment_type = 'Half Month'
        //     GROUP BY r.loan_code

        //     UNION ALL

        //     -- Raw records: everything else
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
        //         col.salary_type,
        //         col.instalment_type
        //     FROM
        //         $db.return_book_instalment r
        //     JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        //     JOIN $db.customer_details c ON c.id = cl.customer_id
        //     LEFT JOIN $db.collection col ON r.collection_id = col.id
        //     LEFT JOIN $db.half_payment_details hpd ON hpd.collection_id = r.collection_id
        //     WHERE
        //         r.YEAR = '$selected_year'
        //         AND r.MONTH = '$selected_month'
        //         AND NOT (col.salary_type = 'Gaji' AND col.instalment_type = 'Half Month')
        // ";

        // Build reusable filters for r/c tables
        $filter = '';
        if ($loan_code != '')       { $filter .= " AND r.loan_code = '$loan_code'"; }
        if ($customer_code != '')   { $filter .= " AND c.customercode2 = '$customer_code'"; }
        if ($customer_name != '')   { $filter .= " AND c.name = '$customer_name'"; }

        // Half Month: sum both halves, show latest date, include latest half_payment_details.balance
        $sql = "(
        SELECT
            r.loan_code,
            r.year,
            r.month,
            MAX(r.datetime) AS latest_datetime,
            c.customercode2,
            c.name,
            SUM(r.instalment) AS total_instalment,
            SUM(r.tepi)       AS total_tepi,
            COALESCE((
                SELECT hpd2.balance
                FROM $db.return_book_instalment r2
                JOIN $db.half_payment_details hpd2 ON hpd2.collection_id = r2.collection_id
                WHERE r2.loan_code = r.loan_code
                    AND r2.year      = r.year
                    AND r2.month     = r.month
                ORDER BY r2.datetime DESC
                LIMIT 1
            ), 0) AS balance
        FROM $db.return_book_instalment r
        JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        JOIN $db.customer_details    c  ON c.id        = cl.customer_id
        LEFT JOIN $db.collection     col ON r.collection_id = col.id
        WHERE r.year = '$selected_year'
            AND r.month = '$selected_month'
            AND col.instalment_type = 'Half Month'
            $filter
        GROUP BY r.loan_code, r.year, r.month, c.customercode2, c.name
        )

        UNION ALL
        (
        -- Other types (EXCEPT Bad Debt): take latest record only
        SELECT r.loan_code, r.year, r.month, r.datetime AS latest_datetime,
                c.customercode2, c.name,
                r.instalment AS total_instalment, r.tepi AS total_tepi,
                0 AS balance
        FROM $db.return_book_instalment r
        JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        JOIN $db.customer_details c ON c.id = cl.customer_id
        LEFT JOIN $db.collection col ON r.collection_id = col.id
        WHERE r.year = '$selected_year'
            AND r.month = '$selected_month'
            AND NOT (col.instalment_type = 'Half Month')
            AND (col.salary_type IS NULL OR col.salary_type != 'Bad Debt')
            $filter
            AND r.datetime = (
            SELECT MAX(r2.datetime)
            FROM $db.return_book_instalment r2
            WHERE r2.loan_code = r.loan_code
                AND r2.year = r.year
                AND r2.month = r.month
            )
        )

        UNION ALL
        (
        -- Bad Debt: show every record (no collapsing by MAX(datetime))
        SELECT r.loan_code, r.year, r.month, r.datetime AS latest_datetime,
                c.customercode2, c.name,
                r.instalment AS total_instalment, r.tepi AS total_tepi,
                0 AS balance
        FROM $db.return_book_instalment r
        JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
        JOIN $db.customer_details c ON c.id = cl.customer_id
        LEFT JOIN $db.collection col ON r.collection_id = col.id
        WHERE r.year = '$selected_year'
            AND r.month = '$selected_month'
            AND col.salary_type = 'Bad Debt'
            $filter
        )
            
        UNION ALL

        (
        -- Special case: Half Month instalments that are NOT fully paid
            SELECT
                r.loan_code,
                r.year,
                r.month,
                r.datetime          AS latest_datetime,
                c.customercode2,
                c.name,
                0                   AS total_instalment,
                r.tepi              AS total_tepi,
                0                   AS balance
            FROM $db.return_book_instalment r
            JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
            JOIN $db.customer_details    c  ON c.id        = cl.customer_id
            LEFT JOIN $db.collection     col ON r.collection_id = col.id
            WHERE r.year = '$selected_year'
            AND r.month = '$selected_month'
            $filter
            AND col.instalment_type = 'Half Month'
            AND r.datetime = (
                SELECT MAX(r2.datetime)
                FROM $db.return_book_instalment r2
                WHERE r2.loan_code = r.loan_code
                    AND r2.year      = r.year
                    AND r2.month     = r.month
            )
            AND EXISTS (
                SELECT 1
                FROM $db.half_payment_details hpd
                WHERE hpd.collection_id = r.collection_id
                    AND hpd.balance > 0   -- only NOT fully paid
            )
        )
        ORDER BY latest_datetime ASC
        ";

        var_dump($sql);
        $query = mysql_query($sql);
        while ($row = mysql_fetch_assoc($query)) {
            // Skip rows where balance is NOT zero (same rule as before)
            if ($row['balance'] != 0) {
                continue;
            }

            $hasInstalment = is_numeric($row['total_instalment']) && (float)$row['total_instalment'] != 0;
            $hasTepi       = is_numeric($row['total_tepi'])       && (float)$row['total_tepi']       != 0;

            // 🔹 Case: both instalment AND tepi exist (your Half Month fully-paid case)
            if ($hasInstalment && $hasTepi) {
                // 1st row: instalment only
                $results[] = [
                    'datetime'    => $row['latest_datetime'],
                    'loan_code'   => $row['loan_code'],
                    'customer_id' => $row['customercode2'],
                    'name'        => $row['name'],
                    'instalment'  => $row['total_instalment'],
                    'tepi'        => '-',   // no tepi in this row
                ];

                // 2nd row: tepi only
                $results[] = [
                    'datetime'    => $row['latest_datetime'],
                    'loan_code'   => $row['loan_code'],
                    'customer_id' => $row['customercode2'],
                    'name'        => $row['name'],
                    'instalment'  => '-',   // no instalment in this row
                    'tepi'        => $row['total_tepi'],
                ];

                // go next row
                continue;
            }

            // 🔹 Other cases (only instalment, or only tepi, or both zero) keep old behaviour
            $results[] = [
                'datetime'    => $row['latest_datetime'],
                'loan_code'   => $row['loan_code'],
                'customer_id' => $row['customercode2'],
                'name'        => $row['name'],
                'instalment'  => $hasInstalment ? $row['total_instalment'] : '-',
                'tepi'        => $hasTepi       ? $row['total_tepi']       : '-',
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
        // Filters for monthly/cust tables
            $filterM = '';
            if ($loan_code != '')       { $filterM .= " AND rbm.loan_code = '$loan_code'"; }
            if ($customer_code != '')   { $filterM .= " AND cd.customercode2 = '$customer_code'"; }
            if ($customer_name != '')   { $filterM .= " AND cd.name = '$customer_name'"; }

            $sql = "SELECT
                        rbm.*,
                        cd.customercode2,
                        cd.name,
                        col.tepi2
                    FROM $db.return_book_monthly rbm

                    -- ✅ still only latest monthly row per loan_code/month
                    JOIN (
                        SELECT rbm1.loan_code, rbm1.id
                        FROM $db.return_book_monthly rbm1
                        INNER JOIN (
                            SELECT r.loan_code, MAX(r.datetime) AS latest_datetime
                            FROM $db.return_book_monthly r
                            LEFT JOIN majusama_dev.collection c ON c.id = r.collection_id
                            WHERE r.year = '$selected_year' AND r.month = '$selected_month' AND c.instalment_month = '$selected_year-$selected_month'
                            GROUP BY r.loan_code
                        ) latest
                        ON latest.loan_code = rbm1.loan_code
                        AND latest.latest_datetime = rbm1.datetime
                        WHERE rbm1.year = '$selected_year'
                        AND rbm1.month = '$selected_month'
                    ) lrm
                    ON lrm.loan_code = rbm.loan_code
                    AND lrm.id = rbm.id

                    -- ✅ make this LEFT JOIN so loan without mpr still shows
                    LEFT JOIN (
                        SELECT loan_code, MAX(id) AS latest_mpr_id
                        FROM $db.monthly_payment_record
                        GROUP BY loan_code
                    ) lmpr ON lmpr.loan_code = rbm.loan_code
                    LEFT JOIN $db.monthly_payment_record mpr
                        ON mpr.id = lmpr.latest_mpr_id

                    LEFT JOIN $db.customer_details cd
                        ON cd.id = mpr.customer_id

                    LEFT JOIN $db.collection col
                        ON rbm.collection_id = col.id

                    WHERE rbm.year = '$selected_year'
                    AND rbm.month = '$selected_month'
                    -- AND (col.instalment_type IS NULL OR col.instalment_type != 'Instalment')
                    AND NOT EXISTS (
                        SELECT 1
                        FROM $db.return_book_instalment r
                        WHERE r.loan_code = rbm.loan_code
                            AND r.year  = '$selected_year'
                            AND r.month = '$selected_month'
                    )
                    $filterM
                    ORDER BY rbm.datetime ASC
            ";


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
            //  4. Merge records (only Half Month), keep others raw
            // =============================
            $mergedResults = [];
            foreach ($results as $row) {
                $isBadDebt   = isset($row['salary_type']) && $row['salary_type'] === 'Bad Debt';
                $isMergeable = isset($row['salary_type'], $row['instalment_type']) 
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
            if ((!is_numeric($displayTepi) || $displayTepi == 0) && is_numeric($row['tepi2'])) {
                $displayTepi = $row['tepi2'];
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