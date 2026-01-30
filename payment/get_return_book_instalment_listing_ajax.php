    <?php
    include_once '../include/dbconnection.php';
    session_start();

    if (isset($_POST)) {
        $selected_year  = isset($_POST['year']) ? $_POST['year'] : date('Y');
        $selected_month = isset($_POST['month']) ? $_POST['month'] : date('m');
        $loan_code      = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
        $customer_code  = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
        $customer_name  = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

        $db = $_SESSION['login_database'];
        $results = [];

        // =============================
        //  Loan Code Style Rules
        // =============================
        function getLoanCodeStyle($loan_code) {
            $prefix = substr($loan_code, 0, 2);

            // SD → black
            if ($prefix == 'SD') {
                return '';
            }

            // Allowed prefixes (black)
            $allowed = array(
                'AS','SB','BP','KT','MS','MJ','PP','NG','TS','LT','BT','DK','CL'
            );

            if (!in_array($prefix, $allowed)) {
                return "style='color:#FF0000'";
            }

            return '';
        }

        function formatLoanCodeText($loan_code) {
            $red_prefix = array('SB','MS','MJ','PP','CL','BT');

            if (in_array(substr($loan_code, 0, 2), $red_prefix)) {
                return preg_replace(
                    '/^([a-z]{2})/i',
                    '<span style="color:red;">\1</span>',
                    $loan_code
                );
            }

            return $loan_code;
        }


        // =============================
        //  1) return_book_instalment
        //     - Half Month:
        //          balance > 0  => show tepi only (if tepi > 0), else show nothing
        //          balance = 0  => show instalment AND tepi
        //     - Non-Half-Month (except Bad Debt): merge by instalment_type (sum)
        //     - Bad Debt: raw
        // =============================

        // Build reusable filters for r/c tables (aliases must match SQL)
        $filter = '';
        if ($loan_code != '')       { $filter .= " AND r.loan_code = '$loan_code'"; }
        if ($customer_code != '')   { $filter .= " AND c.customercode2 = '$customer_code'"; }
        if ($customer_name != '')   { $filter .= " AND c.name = '$customer_name'"; }

        $sql = "
        (
            -- ✅ HALF MONTH: grouped (we decide display in PHP based on balance + tepi)
            SELECT
                r.loan_code,
                r.year,
                r.month,
                MAX(r.datetime) AS latest_datetime,
                c.customercode2,
                c.name,
                'Half Month' AS instalment_type,
                col.salary_type AS salary_type,
                SUM(r.instalment) AS total_instalment,
                SUM(r.tepi)       AS total_tepi,
                COALESCE((
                    SELECT hpd2.balance
                    FROM $db.return_book_instalment r2
                    JOIN $db.half_payment_details hpd2 ON hpd2.collection_id = r2.collection_id
                    LEFT JOIN $db.collection col2 ON col2.id = r2.collection_id
                    WHERE r2.loan_code = r.loan_code
                    AND r2.year      = r.year
                    AND r2.month     = r.month
                    AND col2.instalment_type = 'Half Month'
                    ORDER BY r2.datetime DESC
                    LIMIT 1
                ), 0) AS balance
            FROM $db.return_book_instalment r
            JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
            JOIN $db.customer_details c ON c.id = cl.customer_id
            LEFT JOIN $db.collection col ON r.collection_id = col.id
            WHERE r.year = '$selected_year'
            AND r.month = '$selected_month'
            AND col.instalment_type = 'Half Month'
            $filter
            GROUP BY r.loan_code, r.year, r.month, c.customercode2, c.name, col.salary_type
        )

        UNION ALL
        (
            -- ✅ NON-HALF-MONTH (EXCEPT Bad Debt): MERGE (sum) => Rule #2
            SELECT
                r.loan_code,
                r.year,
                r.month,
                MAX(r.datetime) AS latest_datetime,
                c.customercode2,
                c.name,
                COALESCE(col.instalment_type, 'Unknown') AS instalment_type,
                col.salary_type AS salary_type,
                SUM(r.instalment) AS total_instalment,
                SUM(r.tepi)       AS total_tepi,
                0 AS balance
            FROM $db.return_book_instalment r
            JOIN $db.customer_loanpackage cl ON cl.loan_code = r.loan_code
            JOIN $db.customer_details c ON c.id = cl.customer_id
            LEFT JOIN $db.collection col ON r.collection_id = col.id
            WHERE r.year = '$selected_year'
            AND r.month = '$selected_month'
            AND (col.instalment_type IS NULL OR col.instalment_type <> 'Half Month')
            AND (col.salary_type IS NULL OR col.salary_type <> 'Bad Debt')
            $filter
            GROUP BY
                r.loan_code, r.year, r.month,
                c.customercode2, c.name,
                col.instalment_type, col.salary_type
        )

        UNION ALL
        (
            -- ✅ BAD DEBT: raw (no merge)
            SELECT
                r.loan_code,
                r.year,
                r.month,
                r.datetime AS latest_datetime,
                c.customercode2,
                c.name,
                COALESCE(col.instalment_type, 'Unknown') AS instalment_type,
                col.salary_type AS salary_type,
                r.instalment AS total_instalment,
                r.tepi AS total_tepi,
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
        ORDER BY latest_datetime DESC
        ";

        // var_dump($sql);

        $query = mysql_query($sql);
        while ($row = mysql_fetch_assoc($query)) {

            $instVal = (is_numeric($row['total_instalment']) ? (float)$row['total_instalment'] : 0.0);
            $tepiVal = (is_numeric($row['total_tepi']) ? (float)$row['total_tepi'] : 0.0);
            $balVal  = (isset($row['balance']) ? (float)$row['balance'] : 0.0);

            $isHalfMonth = isset($row['instalment_type']) && $row['instalment_type'] === 'Half Month';

            // ✅ Apply Half Month rules
            if ($isHalfMonth) {

                // balance > 0 (not fully paid)
                if ($balVal > 0) {
                    // show tepi only if tepi1 > 0
                    if ($tepiVal > 0) {
                        $results[] = [
                            'datetime'    => $row['latest_datetime'],
                            'loan_code'   => $row['loan_code'],
                            'customer_id' => $row['customercode2'],
                            'name'        => $row['name'],
                            'instalment'  => '-',
                            'tepi'        => $tepiVal,
                        ];
                    }
                    // if tepi1 = 0 => show nothing
                    continue;
                }

                // balance = 0 (fully paid): show instalment AND tepi
                if ($balVal == 0) {

                    $hasInst = ($instVal != 0);
                    $hasTepi = ($tepiVal != 0);

                    // Keep your original behavior: split into 2 lines when both exist
                    if ($hasInst && $hasTepi) {
                        // instalment row
                        $results[] = [
                            'datetime'    => $row['latest_datetime'],
                            'loan_code'   => $row['loan_code'],
                            'customer_id' => $row['customercode2'],
                            'name'        => $row['name'],
                            'instalment'  => $instVal,
                            'tepi'        => '-',
                        ];
                        // tepi row
                        $results[] = [
                            'datetime'    => $row['latest_datetime'],
                            'loan_code'   => $row['loan_code'],
                            'customer_id' => $row['customercode2'],
                            'name'        => $row['name'],
                            'instalment'  => '-',
                            'tepi'        => $tepiVal,
                        ];
                        continue;
                    }

                    // If only one exists, show the one that exists
                    if ($hasInst || $hasTepi) {
                        $results[] = [
                            'datetime'    => $row['latest_datetime'],
                            'loan_code'   => $row['loan_code'],
                            'customer_id' => $row['customercode2'],
                            'name'        => $row['name'],
                            'instalment'  => $hasInst ? $instVal : '-',
                            'tepi'        => $hasTepi ? $tepiVal : '-',
                        ];
                    }

                    continue;
                }

                // fallback (shouldn't happen)
                continue;
            }

            // ✅ Non-Half-Month normal display
            $hasInstalment = ($instVal != 0);
            $hasTepi       = ($tepiVal != 0);

            $results[] = [
                'datetime'    => $row['latest_datetime'],
                'loan_code'   => $row['loan_code'],
                'customer_id' => $row['customercode2'],
                'name'        => $row['name'],
                'instalment'  => $hasInstalment ? $instVal : '-',
                'tepi'        => $hasTepi       ? $tepiVal : '-',
            ];
        }

        // =============================
        //  2) SETTLE payments (same)
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
                ORDER BY t1.payment_date DESC";
                

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
                'datetime'    => $row['payment_date'],
                'loan_code'   => $row['receipt_no'],
                'customer_id' => $row['customercode2'],
                'name'        => $row['name'],
                'instalment'  => $amount,
                'tepi'        => '-',
            ];
        }

        // =============================
        //  3) return_book_monthly (remain same)
        // =============================
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

                JOIN (
                    SELECT rbm1.loan_code, rbm1.id
                    FROM $db.return_book_monthly rbm1
                    INNER JOIN (
                        SELECT r.loan_code, MAX(r.datetime) AS latest_datetime
                        FROM $db.return_book_monthly r
                        LEFT JOIN $db.collection c ON c.id = r.collection_id
                        WHERE r.year = '$selected_year'
                        AND r.month = '$selected_month'
                        AND c.instalment_month = '$selected_year-$selected_month'
                        GROUP BY r.loan_code
                    ) latest
                    ON latest.loan_code = rbm1.loan_code
                    AND latest.latest_datetime = rbm1.datetime
                    WHERE rbm1.year = '$selected_year'
                    AND rbm1.month = '$selected_month'
                ) lrm
                ON lrm.loan_code = rbm.loan_code
                AND lrm.id = rbm.id

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
                AND NOT EXISTS (
                    SELECT 1
                    FROM $db.return_book_instalment r
                    WHERE r.loan_code = rbm.loan_code
                        AND r.year  = '$selected_year'
                        AND r.month = '$selected_month'
                )
                $filterM
                ORDER BY rbm.datetime DESC
        ";

        $query = mysql_query($sql);
        while ($row = mysql_fetch_assoc($query)) {
            $results[] = [
                'datetime'    => $row['datetime'],
                'loan_code'   => $row['loan_code'],
                'customer_id' => $row['customercode2'],
                'name'        => $row['name'],
                'instalment'  => '-',
                'tepi'        => $row['tepi'],
                'tepi2'       => $row['tepi2'],
            ];
        }

        // =============================
        //  4) Sort all by date DESC
        // =============================
        usort($results, function ($a, $b) {
            return strtotime($a['datetime']) - strtotime($b['datetime']);
        });

        // =============================
        //  5) Build HTML
        // =============================
        $html = '';
        $count = 1;

        foreach ($results as $row) {
            

            // Monthly: tepi2 fallback (remain same)
            $displayTepi = $row['tepi'];
            if ((!is_numeric($displayTepi) || (float)$displayTepi == 0) && isset($row['tepi2']) && is_numeric($row['tepi2'])) {
                $displayTepi = $row['tepi2'];
            }

            $loanStyle = getLoanCodeStyle($row['loan_code']);
            $loanText  = formatLoanCodeText($row['loan_code']);

            if($row['instalment'] != 0 || $displayTepi != 0){
                $html .= '<tr>
                    <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; border-left:1px solid black; text-align: center;">' . $count . '</td>
                    <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; text-align: center;"><b>' . date('d/m/Y', strtotime($row['datetime'])) . '</b></td>
                    <td style="border-right:1px solid black; border-bottom: 1px solid black; text-align: center;" ' . $loanStyle . '><b>' . $loanText . '</b></td>
                    <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black; text-align: center;"><b>' . $row['customer_id'] . '</b></td>
                    <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black;"><b>' . $row['name'] . '</b></td>
                    <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black;"><b>' . (is_numeric($row['instalment']) ? 'RM ' . number_format($row['instalment'], 2) : '-') . '</b></td>
                    <td style="border-right:1px solid black; color: black; border-bottom: 1px solid black;"><b>' . (is_numeric($displayTepi) ? 'RM ' . number_format($displayTepi, 2) : '-') . '</b></td>
                </tr>';

                 $count++;
            }

           
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
