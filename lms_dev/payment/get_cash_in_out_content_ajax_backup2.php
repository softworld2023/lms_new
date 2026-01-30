<?php
    include_once '../include/dbconnection.php';
    session_start();

    if (isset($_POST)) {
        // =======================================
        //           Initialize Variables
        // =======================================
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
        $first_day_of_month = date('Y-m-01', strtotime($date));
        $is_first_day_of_month = ($date == $first_day_of_month);

        $table_no = $_POST['table_no'];
        $db = $_SESSION['login_database'];
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);

        $current_date = date('Y-m', strtotime($year . '-' . $month));
        $next_month = date('Y-m', strtotime('+1 month', strtotime($year . '-' . $month)));

        $html = '';

        // =======================================
        //     Load Existing Table Content
        // =======================================
        
        $sql = "SELECT * FROM $db.cash_in_out_table$table_no WHERE date = '$date'";
        // var_dump($sql);
        $query = mysql_query($sql);
        $result = mysql_fetch_assoc($query);
        if ($result['date'] != NULL) {
            $html .= $result['content'];
        } else {
            switch ($table_no) {
                // ---------------------------------------
                //     Table No. 1: Locker & Expenses
                // ---------------------------------------
                case '1':
                    $html .= '<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;">Time:&nbsp;</td>
                                <td><input type="text" id="time" class="cell"></td>
                                <td style="color: red; font-size: 10px;">locker out money</td>
                            </tr>
                            <tr>
                                <td style="color: red;">locker OUT +</td>
                                <td><input type="text" class="cell" id="b4" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="c4" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="d4" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="e4" oninput="input(this);"></td>
                                <td id="f4" style="background-color: #ffc499;"></td>
                            </tr>';

                    // Expenses input rows
                    for ($row = 5; $row <= 6; $row++) {
                        $html .= '<tr>';
                        for ($col = 'a'; $col <= 'f'; $col++) {
                            $html .= '<td><input type="text" class="cell" id="' . $col . $row . '" oninput="input(this);"></td>';
                        }
                        $html .= '</tr>';
                    }

                    $html .= '<tr>
                                <td style="text-align: left;"><b>L:</b></td>
                                <td id="b7" style="background-color: #ffc499;"></td>
                                <td><b>100</b></td>
                                <td>RM</td>
                                <td><input type="text" class="cell" id="e7" oninput="input(this);"></td>
                                <td id="f7" style="background-color: #ffc499;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">EXPENSES:</td>
                                <td></td>
                                <td><b>50</b></td>
                                <td>RM</td>
                                <td><input type="text" class="cell" id="e8" oninput="input(this);"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="b9" oninput="input(this);"></td>
                                <td><b>10</b></td>
                                <td>RM</td>
                                <td><input type="text" class="cell" id="e9" oninput="input(this);"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="b10" oninput="input(this);"></td>
                                <td><b>5</b></td>
                                <td>RM</td>
                                <td><input type="text" class="cell" id="e10" oninput="input(this);"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="b11" oninput="input(this);"></td>
                                <td><b>1</b></td>
                                <td>RM</td>
                                <td><input type="text" class="cell" id="e11" oninput="input(this);"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="b12" oninput="input(this);"></td>
                                <td>TOTAL</td>
                                <td>RM</td>
                                <td id="e12" style="background-color: #ffc499;"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="b13" oninput="input(this);"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';

                    // Empty rows
                    for ($i = 14; $i <= 20; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="b' . $i . '" oninput="input(this);"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
                    }

                    // Footer Summary Row
                    $html .= '<tr>
                                <td>TOTAL EXP</td>
                                <td id="b21" style="background-color: #ffc499;"></td>
                                <td></td>
                                <td>TOTAL SUM</td>
                                <td id="e21" style="background-color: #ffc499;"></td>
                                <td></td>
                            </tr>';
                    break;

                // ---------------------------------------
                //     Table No. 2: Loan Payout Details
                // ---------------------------------------
                case '2':
                    $html .= ' <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>Agree No.</b></td>
                                    <td><b>ID No.</b></td>
                                    <td><b>LOAN AMOUNT</b></td>
                                    <td><b>AMOUNT</b></td>
                                    <td><b>sd</b></td>
                                    <td><b>Payout</b></td>
                                    <td><b>Month</b></td>
                                    <td><b>staff name</b></td>
                                </tr>';

                    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                    $limit = 15;
                    $offset = ($page - 1) * $limit;

                    $prev_month_condition = $is_first_day_of_month ? "OR t1.payout_date < '$first_day_of_month'" : "";

                    // --- count total records ---
                    $count_sql = "SELECT COUNT(*) as total
                                FROM $db.monthly_payment_record t1
                                LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                                LEFT JOIN $db.customer_details t3 ON t3.id = t1.customer_id
                                WHERE (t1.payout_date = '$date' $prev_month_condition)
                                AND t1.status != 'DELETED'
                                AND t1.month = '$year-$month'";
                    $count_q = mysql_query($count_sql);
                    $total_records = mysql_fetch_assoc($count_q)['total'];
                    $total_pages = ceil($total_records / $limit);

                    $count = 6;

                    if($total_pages == 1){
                        $limit = 20;
                        $count = 5;
                    } 
                    if ($total_pages < 1){
                        $count = 5;
                    }
                    // --- paginated query ---
                    $sql = "SELECT t1.*, 
                                t1.payout_amount as loan_amount,
                                t3.customercode2
                            FROM $db.monthly_payment_record t1
                            LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                            LEFT JOIN $db.customer_details t3 ON t3.id = t1.customer_id
                            WHERE (t1.payout_date = '$date' $prev_month_condition)
                            AND t1.status != 'DELETED'
                            AND t1.month = '$year-$month'
                            ORDER BY t1.monthly_date ASC
                            LIMIT $limit OFFSET $offset";

                    VAR_DUMP($sql);
                    
                    $query = mysql_query($sql);

                    while ($row = mysql_fetch_assoc($query)) {
                        $loan_code = $row['loan_code'];
                        $customer_code = $row['customercode2'] ? $row['customercode2'] : $row['customercode'];
                        $payout_amount = $row['payout_amount'];
                        $month = $row['month'];
                        $user_id = $row['user_id'];

                        $loan_amount = (int) $payout_amount;
                        $amount = $loan_amount * 0.9;
                        $actual_amount = $row['loan_amount'];

                        if ($row['sd'] == 'Normal') {
                            $sd = 5;
                        } else {
                            $sd_sql = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$actual_amount'";
                            $sd_q = mysql_query($sd_sql);
                            $sd_res = mysql_fetch_assoc($sd_q);
                            $sd = (int) $sd_res['stamp_duty'];
                        }
                        $payout = $amount - $sd;

                        $month = DateTime::createFromFormat('Y-m', $month);
                        $month_name = $month->format('M');

                        $html .= '<tr>
                                    <td><input type="text" class="cell" value="' . $loan_code . '"></td>
                                    <td><input type="text" class="cell" value="' . $customer_code . '"></td>
                                    <td><input type="text" class="cell" id="j' . $count . '" value="' . $loan_amount . '"></td>
                                    <td><input type="text" class="cell" id="k' . $count . '" value="' . $amount . '" readonly></td>
                                    <td><input type="text" class="cell" id="l' . $count . '" value="' . $sd . '"></td>
                                    <td><input type="text" class="cell" id="m' . $count . '" value="' . $payout . '" readonly></td>
                                    <td><input type="text" class="cell" value="' . $month_name . '"></td>
                                    <td><input type="text" class="cell" value="' . $user_id . '"></td>
                                </tr>';

                        $count++;
                    }

                    // Add Empty Rows if Needed
                    for ($i = $count; $i <= 20; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell"></td>
                                    <td><input type="text" class="cell"></td>
                                    <td><input type="text" class="cell" id="j' . $i . '"></td>
                                    <td><input type="text" class="cell" id="k' . $i . '" value="0" readonly></td>
                                    <td><input type="text" class="cell" id="l' . $i . '"></td>
                                    <td><input type="text" class="cell" id="m' . $i . '" value="0" readonly></td>
                                    <td><input type="text" class="cell"></td>
                                    <td><input type="text" class="cell"></td>
                                </tr>';
                    }

                    // Footer Summary Row
                    $html .= '<tr>
                                <td colspan="2"></td>
                                <td id="j21" style="background-color: #4285f4;">0</td>
                                <td id="k21" style="background-color: #4285f4;">0</td>
                                <td id="l21" style="background-color: #4285f4;">0</td>
                                <td id="m21" style="background-color: #4285f4;">0</td>
                                <td colspan="2"></td>
                            </tr>';

                    // Pagination controls
                    if ($total_pages > 1) {
                        $html .= '<tr><td colspan="8" style="text-align:center;">';
                        for ($p = 1; $p <= $total_pages; $p++) {
                            if ($p == $page) {
                                $html .= "<strong>[$p]</strong> ";
                            } else {
                                $html .= "<a href='#' onclick='loadTable(2, $p)'>$p</a> ";
                            }
                        }
                        $html .= '</td></tr>';
                    }
                    break;

                case '3':

                    $sql = "SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        t1.*, t2.loan_amount,
                                        t3.customercode2,
                                        t4.fullname,
                                        (
                                            SELECT
                                                t5.sd
                                            FROM
                                                $db.monthly_payment_record t5
                                            WHERE
                                                t5.loan_code = t1.loan_code
                                            LIMIT 1
                                        ) AS sd,
                                        t6.balance AS half_balance,
                                        t6.ori_instalment
                                    FROM
                                        $db.collection t1
                                    LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                                    LEFT JOIN $db.customer_details t3 ON t3.id = t2.customer_id
                                    JOIN $db. USER t4 ON t4.id = t1.approved_by_id
                                    LEFT JOIN $db.half_payment_details t6 ON t6.collection_id = t1.id
                                    WHERE
                                        YEAR (t1.datetime) = '$year'
                                    AND MONTH (t1.datetime) = '$month'
                                    ORDER BY
                                        id DESC
                                    LIMIT 16
                                ) subquery
                            ORDER BY id ASC "; 

                    $query = mysql_query($sql);
                    
                    $f5 = 0;

                    // Loop Over Collection Records
                    while ($row = mysql_fetch_assoc($query)) {
                        $loan_code = $row['loan_code'];
                        $settle_amount = 0;

                        // Handle Settlement Loans
                        if ($row['salary_type'] == 'settlement') {
                            $settle_q = "SELECT balance, discount 
                                        FROM $db.loan_payment_details t1
                                        LEFT JOIN $db.customer_loanpackage t2 ON t2.id = t1.customer_loanid
                                        WHERE t1.receipt_no != ''
                                        AND t1.loan_status = 'SETTLE'
                                        AND t1.month_receipt LIKE '$year-$month'
                                        AND loan_code = '$loan_code' ";
                            $settle_query = mysql_query($settle_q);
                            $settle = mysql_fetch_assoc($settle_query);

                            if (isset($settle['discount'])) {
                                $settle_amount = $settle['balance'] - $settle['discount'];
                            } else {
                                $settle_amount = $settle['balance'];
                            }
                        }

                        // Determine Instalment
                        if (!empty($row['ori_instalment'])) {
                            $instalment = $row['ori_instalment'];
                        } elseif (!empty($row['collection_amount']) && $row['collection_amount'] > 0) {
                            $instalment = $row['collection_amount'];
                        } else {
                            $instalment = $row['instalment'];
                        }

                        $tepi1 = $row['tepi1'];
                        $tepi2 = $row['tepi2'];

                        // Total Calculation for F5
                        if (!empty($row['collection_amount']) && $row['collection_amount'] > 0) {
                            $f5 += $instalment;
                        } elseif ($settle_amount > 0) {
                            $f5 += $settle_amount;
                        } else {
                            if ($row['half_balance'] != 0) {
                                $f5 += $tepi1 + $tepi2;
                            } else {
                                $f5 += $instalment + $tepi1 + $tepi2;
                            }
                        }
                    }

                    // =============================
                    //  Pagination Setup
                    // =============================
                    $page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                    $limit = 15;
                    $offset = ($page - 1) * $limit;

                    $html .= '
                        <tr>
                            <td></td><td></td><td></td><td></td>
                            <td></td><td></td><td></td><td></td>
                        </tr>
                        <tr>
                            <td><b>Agree No.</b></td>
                            <td><b>ID No.</b></td>
                            <td><b>LOAN AMOUNT</b></td>
                            <td><b>AMOUNT</b></td>
                            <td><b>sd</b></td>
                            <td><b>Payout</b></td>
                            <td><b>Month</b></td>
                            <td><b>staff name</b></td>
                        </tr>';

                    // =============================
                    //  Main Query: Merge Collections + Next Month Loans
                    // =============================
                    $union_sql = "SELECT loan_code,
                            customercode2,
                            loan_amount,
                            collection_amount,
                            ori_instalment,
                            staff_name,
                            month,
                            source,
                            record_date,
                            sd
                        FROM (
                            SELECT 
                                t1.loan_code,
                                t3.customercode2,
                                t2.loan_amount,
                                t1.collection_amount,
                                t6.ori_instalment,
                                t4.fullname AS staff_name,
                                DATE_FORMAT(t1.datetime, '%Y-%m') AS month,
                                t1.datetime AS record_date,
                                'Normal' AS sd,
                                'collection' AS source
                            FROM $db.collection t1
                            LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                            LEFT JOIN $db.customer_details t3 ON t3.id = t2.customer_id
                            JOIN $db.user t4 ON t4.id = t1.approved_by_id
                            LEFT JOIN $db.half_payment_details t6 ON t6.collection_id = t1.id
                            WHERE YEAR(t1.datetime) = '$year'
                            AND MONTH(t1.datetime) = '$month'

                            UNION ALL

                            SELECT 
                                t1.loan_code,
                                t2.customercode2,
                                t1.payout_amount AS loan_amount,
                                NULL AS collection_amount,
                                NULL AS ori_instalment,
                                t1.user_id AS staff_name,
                                t1.MONTH AS month,
                                t1.monthly_date AS record_date,
                                t1.sd,
                                'next_month' AS source
                            FROM $db.monthly_payment_record t1
                            LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                            WHERE t1.MONTH LIKE '$next_month%'
                            AND t1.status != 'DELETED'
                        ) merged
                    ";

                    var_dump($union_sql);
                    // =============================
                    //  Count Total Rows (for pagination)
                    // =============================
                    $count_sql = "SELECT COUNT(*) AS total FROM ($union_sql) AS c";
                    $count_res = mysql_query($count_sql);
                    $total_rows = 0;
                    if ($count_res) {
                        $count_data = mysql_fetch_assoc($count_res);
                        $total_rows = (int)$count_data['total'];
                    }
                    $total_pages = ceil($total_rows / $limit);

                    $count = 6;

                    if($total_pages == 1){
                        $limit = 20;
                        $count = 5;
                    } 
                    if ($total_pages < 1){
                        $limit = 20;
                        $count = 5;
                    }
                    // =============================
                    //  Apply Pagination
                    // =============================
                    $sql = "$union_sql ORDER BY record_date ASC LIMIT $offset, $limit";
                    $query = mysql_query($sql);

                   
                    var_dump($sql);

                    $current_next_month = date("M", strtotime($next_month . "-01"));
                    // =============================
                    //  Render Rows
                    // =============================
                    while ($row = mysql_fetch_assoc($query)) {
                        $loan_code     = $row['loan_code'];
                        $customer_code = $row['customercode2'];
                        $loan_amount   = (int)$row['loan_amount'];
                        $instalment    = !empty($row['ori_instalment']) ? $row['ori_instalment'] : $loan_amount;
                        $staff_name    = $row['staff_name'];
                        $month_name    = !empty($row['month']) ? date("M", strtotime($row['month'] . "-01")) : "";

                        // Calculate Amount, SD, Payout
                        $amount = $loan_amount * 0.9;

                        if ($row['sd'] == 'Normal') {
                            $sd = 5;
                        } else {
                            $sd_sql = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
                            $sd_q = mysql_query($sd_sql);
                            $sd_res = mysql_fetch_assoc($sd_q);
                            $sd = isset($sd_res['stamp_duty']) ? (float)$sd_res['stamp_duty'] : 0;
                        }

                        $payout = $amount - $sd;;
                        if($month_name == $current_next_month){
                            // var_dump($month_name);
                            $html .= '<tr>
                                <td><input type="text" class="cell" value="' . $loan_code . '" oninput="input(this);"></td>
                                <td><input type="text" class="cell" value="' . $customer_code . '" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="s' . $count . '" value="' . $loan_amount . '" oninput="input(this);"></td>
                                <td><input type="text" class="cell" id="t' . $count . '" value="' . $amount . '" readonly></td>
                                <td><input type="text" class="cell" id="u' . $count . '" value="' . $sd . '"></td>
                                <td><input type="text" class="cell" id="w' . $count . '" value="' . $payout . '" readonly></td>
                                <td><input type="text" class="cell" value="' . $month_name . '"></td>
                                <td><input type="text" class="cell" value="' . $staff_name . '"></td>
                            </tr>';

                            $count++;
                        }
                        
                    }

                    // =============================
                    //  Fill Remaining Rows (up to $limit)
                    // =============================
                    for ($i = $count; $i <= 20; $i++) {
                        $html .= '<tr>
                            <td><input type="text" class="cell" oninput="input(this);"></td>
                            <td><input type="text" class="cell" oninput="input(this);"></td>
                            <td><input type="text" class="cell" id="s' . $i . '" oninput="input(this);"></td>
                            <td><input type="text" class="cell" id="t' . $i . '" value="0" readonly></td>
                            <td><input type="text" class="cell" id="u' . $i . '" oninput="input(this);"></td>
                            <td><input type="text" class="cell" id="w' . $i . '" value="0" readonly></td>
                            <td><input type="text" class="cell" oninput="input(this);"></td>
                            <td><input type="text" class="cell" oninput="input(this);"></td>
                        </tr>';
                    }

                    // =============================
                    //  Summary Row
                    // =============================
                    $html .= '<tr>
                        <td><input type="hidden" id="hidden_f5" value="' . $f5 . '"></td>
                        <td></td>
                        <td id="s21" style="background-color: #4285f4;">0</td>
                        <td id="t21" style="background-color: #4285f4;">0</td>
                        <td id="u21" style="background-color: #4285f4;">0</td>
                        <td id="v21" style="background-color: #4285f4;">0</td>
                        <td></td>
                        <td></td>
                    </tr>';

                    // =============================
                    //  Pagination Links
                    // =============================
                    if ($total_pages > 1) {
                        $html .= '<tr><td colspan="8" style="text-align:center;">';
                        for ($p = 1; $p <= $total_pages; $p++) {
                            if ($p == $page) {
                                $html .= "<strong>[$p]</strong> ";
                            } else {
                                $html .= "<a href='#' onclick='loadTable(3, $p)'>$p</a> ";
                            }
                        }
                        $html .= '</td></tr>';
                    }

                 break;
                case '4':
                    $html .= '<tr>
                                <td></td>
                                <td rowspan="5" colspan="3" style="background-color: #ff0000; font-size: 30px;"><b>DUIT TONG ONG MARI ONG MARI</b></td>
                                <td></td>
                                <td></td>
                            </tr>';

                    for ($i = 0; $i < 4; $i++) {
                        $html .= '<tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
                    }

                    $html .= '<tr>
                                <td></td>
                                <td rowspan="5" colspan="3" id="b28" style="font-size: 50px; font-weight: bold;">0</td>
                                <td></td>
                                <td></td>
                            </tr>';

                    for ($i = 0; $i < 4; $i++) {
                        $html .= '<tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
                    }

                    $html .= '<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"><b>kena isi</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Before date PL</b></td>
                                <td><input type="text" class="cell" id="c36" oninput="input(this);"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                 break;
                // case '5':
                //     $html .= '  <tr>
                //                     <td colspan="3" style="background-color: #ffff00;"><b>INS CASH OUT (1)</b></td>
                //                     <td></td>
                //                     <td style="text-align: right;">Date:</td>
                //                     <td colspan="2"><input type="text" class="cell" style="text-align: left;" oninput="input(this);"></td>
                //                     <td></td>
                //                 </tr>
                //                 <tr>
                //                     <td></td>
                //                     <td></td>
                //                     <td></td>
                //                     <td></td>
                //                     <td></td>
                //                     <td></td>
                //                     <td></td>
                //                     <td></td>
                //                 </tr>
                //                 <tr>
                //                     <td><b>Agree No.</b></td>
                //                     <td><b>ID No.</b></td>
                //                     <td><b>LOAN AMOUNT</b></td>
                //                     <td><b>AMOUNT</b></td>
                //                     <td><b>sd</b></td>
                //                     <td><b>Payout</b></td>
                //                     <td><b>Month</b></td>
                //                     <td><b>staff name</b></td>
                //                 </tr>';

                //     $month_now = date('Y-m', strtotime($date));
                //     $sql = "SELECT * FROM (
                //                 SELECT
                //                     t1.*,
                //                     t2.customercode2
                //                 FROM
                //                     $db.customer_loanpackage t1
                //                 LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                //                 WHERE
                //                     t1.payout_date = '$date' AND t1.loan_status = 'Paid' AND STR_TO_DATE(t1.start_month, '%Y-%m') <= STR_TO_DATE('$current_date', '%Y-%m')
                //                 ORDER BY id DESC
                //                 LIMIT 10
                //             ) subquery
                //             ORDER BY id ASC
                //             ";
                //     // var_dump($sql);
                //     $query = mysql_query($sql);
    
                //     $count = 26;
                //     while ($row = mysql_fetch_assoc($query)) {
                //         $loan_code = $row['loan_code'];
                //         $customer_code = isset($row['customercode2']) ? $row['customercode2'] : $row['customer_id'];
                //         $loan_amount = (int) $row['loan_amount'];
                //         $payout_date = $row['payout_date'];
                //         $staff_name = $row['staff_name'];
                //         $start_month = $row['start_month'];
                //         // show only month name
                //         if (strlen($start_month) == 7) {
                //             // Format: Y-m
                //             $start_month = DateTime::createFromFormat('Y-m', $start_month);
                //         } elseif (strlen($start_month) == 10) {
                //             // Format: Y-m-d
                //             $start_month = DateTime::createFromFormat('Y-m-d', $start_month);
                //         }

                //         $start_month_name = $start_month->format('M');

                //         // if ($start_month_name === date('M', strtotime($date))) {
                //             $sql = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
                //             $q = mysql_query($sql);
                //             $res = mysql_fetch_assoc($q);
                //             $processing_fee = $res['processing_fee'];
                //             $sd = (int) $res['stamp_duty'];
                //             $amount = $loan_amount - $processing_fee;
                //             $payout = $amount - $sd;
    
                //             $payout_date = DateTime::createFromFormat('Y-m-d', $payout_date);
                //             $payout_month_name = $payout_date->format('M');
    
                //             $html .= '<tr>
                //                         <td><input type="text" class="cell" value="' . $loan_code . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" value="' . $customer_code . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" id="j' . $count . '" value="' . $loan_amount . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" id="k' . $count . '" value="' . $amount . '" oninput="input(this);" readonly></td>
                //                         <td><input type="text" class="cell" id="l' . $count . '" value="' . $sd . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" id="m' . $count . '" value="' . $payout . '" oninput="input(this);" readonly></td>
                //                         <td><input type="text" class="cell" value="' . $start_month_name . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" value="' . $staff_name . '" oninput="input(this);"></td>
                //                     </tr>';
    
                //             $count++;
                //         // }
                //     }

                //     for ($i = $count; $i <= 35; $i++) {
                //         $html .= '<tr>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" id="j' . $i . '" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" id="k' . $i . '" value="0" oninput="input(this);" readonly></td>
                //                     <td><input type="text" class="cell" id="l' . $i . '" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" id="m' . $i . '" value="0" oninput="input(this);" readonly></td>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                 </tr>';
                //     }

                //     $html .= '<tr>
                //                 <td></td>
                //                 <td></td>
                //                 <td id="j36" style="background-color: #4285f4;">0</td>
                //                 <td id="k36" style="background-color: #4285f4;">0</td>
                //                 <td id="l36" style="background-color: #4285f4;">0</td>
                //                 <td id="m36" style="background-color: #4285f4;">0</td>
                //                 <td></td>
                //                 <td></td>
                //             </tr>';
                // break;
                case '5':
                    $html .= '  <tr>
                                    <td colspan="3" style="background-color: #ffff00;"><b>INS CASH OUT (1)</b></td>
                                    <td></td>
                                    <td style="text-align: right;">Date:</td>
                                    <td colspan="2"><input type="text" class="cell" style="text-align: left;" oninput="input(this);"></td>
                                    <td></td>
                                </tr>
                                <tr><td colspan="8"></td></tr>
                                <tr>
                                    <td><b>Agree No.</b></td>
                                    <td><b>ID No.</b></td>
                                    <td><b>LOAN AMOUNT</b></td>
                                    <td><b>AMOUNT</b></td>
                                    <td><b>sd</b></td>
                                    <td><b>Payout</b></td>
                                    <td><b>Month</b></td>
                                    <td><b>Staff Name</b></td>
                                </tr>';

                    // =============================
                    //  Pagination Setup
                    // =============================
                    $page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
                    $offset = ($page - 1) * $limit;

                    // Count total records (for pagination links)
                    $count_sql = "SELECT COUNT(*) AS total
                                FROM $db.customer_loanpackage t1
                                LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                                WHERE t1.loan_status = 'Paid'
                                    -- AND t1.payout_date = '$date'
                                    AND STR_TO_DATE(t1.start_month, '%Y-%m') = STR_TO_DATE('$current_date', '%Y-%m')";
                    $count_result = mysql_query($count_sql);
                    $row_count = mysql_fetch_assoc($count_result);
                    $total_records = (int)$row_count['total'];
                    $total_pages = ceil($total_records / $limit);

                    $count = 26;

                    if($total_pages <= 1){
                        $count = 25;
                    }

                    // =============================
                    //  Main Query with LIMIT/OFFSET
                    // =============================
                    $sql = "SELECT t1.*, t2.customercode2
                            FROM $db.customer_loanpackage t1
                            LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                            WHERE t1.loan_status = 'Paid'
                            -- AND t1.payout_date = '$date'
                            AND STR_TO_DATE(t1.start_month, '%Y-%m') = STR_TO_DATE('$current_date', '%Y-%m')
                            ORDER BY payout_date DESC
                            LIMIT $limit OFFSET $offset";
                            var_dump($sql);
                    $query = mysql_query($sql);

                    while ($row = mysql_fetch_assoc($query)) {
                        $loan_code = $row['loan_code'];
                        $customer_code = isset($row['customercode2']) ? $row['customercode2'] : $row['customer_id'];
                        $loan_amount = (int)$row['loan_amount'];
                        $payout_date = $row['payout_date'];
                        $staff_name = $row['staff_name'];
                        $start_month = $row['start_month'];

                        // Format start_month
                        if (strlen($start_month) == 7) {
                            $start_month = DateTime::createFromFormat('Y-m', $start_month);
                        } elseif (strlen($start_month) == 10) {
                            $start_month = DateTime::createFromFormat('Y-m-d', $start_month);
                        }
                        $start_month_name = $start_month->format('M');

                        // Fetch processing_fee and sd
                        $sql = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
                        $q = mysql_query($sql);
                        $res = mysql_fetch_assoc($q);
                        $processing_fee = $res['processing_fee'];
                        $sd = (int)$res['stamp_duty'];
                        $amount = $loan_amount - $processing_fee;
                        $payout = $amount - $sd;

                        $payout_date = DateTime::createFromFormat('Y-m-d', $payout_date);
                        $payout_month_name = $payout_date->format('M');

                        $html .= '<tr>
                                    <td><input type="text" class="cell" value="' . $loan_code . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" value="' . $customer_code . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="j' . $count . '" value="' . $loan_amount . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="k' . $count . '" value="' . $amount . '" oninput="input(this);" readonly></td>
                                    <td><input type="text" class="cell" id="l' . $count . '" value="' . $sd . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="m' . $count . '" value="' . $payout . '" oninput="input(this);" readonly></td>
                                    <td><input type="text" class="cell" value="' . $start_month_name . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" value="' . $staff_name . '" oninput="input(this);"></td>
                                </tr>';
                        $count++;
                    }

                    // Empty rows filler
                    for ($i = $count; $i <= 35; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="j' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="k' . $i . '" value="0" oninput="input(this);" readonly></td>
                                    <td><input type="text" class="cell" id="l' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="m' . $i . '" value="0" oninput="input(this);" readonly></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                </tr>';
                    }

                    // Totals row
                    $html .= '<tr>
                                <td colspan="2"></td>
                                <td id="j36" style="background-color: #4285f4;">0</td>
                                <td id="k36" style="background-color: #4285f4;">0</td>
                                <td id="l36" style="background-color: #4285f4;">0</td>
                                <td id="m36" style="background-color: #4285f4;">0</td>
                                <td colspan="2"></td>
                            </tr>';

                    // =============================
                    //  Pagination Links
                    // =============================
                    if ($total_pages > 1) {
                        $html .= '<tr><td colspan="8" style="text-align:center;">';
                        for ($p = 1; $p <= $total_pages; $p++) {
                            if ($p == $page) {
                                $html .= "<b>[$p]</b> ";
                            } else {
                                $html .= "<a href='#' onclick='loadTable(5, $p, $limit); return false;'>$p</a> ";
                            }
                        }
                        $html .= '</td></tr>';
                    }
                break;

                // case '6':
                //     $html .= '<tr>
                //                 <td colspan="3" style="background-color: #00ff00;"><b>INS CASH OUT (2)</b></td>
                //                 <td></td>
                //                 <td style="text-align: right;">Date:</td>
                //                 <td colspan="2"><input type="text" class="cell" style="text-align: left;" oninput="input(this);"></td>
                //                 <td></td>
                //             </tr>
                //             <tr>
                //                 <td></td>
                //                 <td></td>
                //                 <td></td>
                //                 <td></td>
                //                 <td></td>
                //                 <td></td>
                //                 <td></td>
                //                 <td></td>
                //             </tr>
                //             <tr>
                //                 <td><b>Agree No.</b></td>
                //                 <td><b>ID No.</b></td>
                //                 <td><b>LOAN AMOUNT</b></td>
                //                 <td><b>AMOUNT</b></td>
                //                 <td><b>sd</b></td>
                //                 <td><b>Payout</b></td>
                //                 <td><b>Month</b></td>
                //                 <td><b>staff name</b></td>
                //             </tr>';

                //     $sql = "SELECT
                //                 t1.id,
                //                 t1.customer_id,
                //                 t1.loan_status,
                //                 t1.start_month,
                //                 t1.loan_code,
                //                 t1.loan_amount,
                //                 t1.staff_name,
                //                 t2.customercode2
                //             FROM
                //                 $db.customer_loanpackage t1
                //             LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                //             WHERE
                //                 t1.loan_status = 'Paid'
                //             AND t1.start_month LIKE '$next_month%'
                //             ORDER BY
                //                 id ASC
                //             LIMIT 10;";
                //     // var_dump($sql);
                //     $query = mysql_query($sql);
    
                //     $count = 26;
                //     while ($row = mysql_fetch_assoc($query)) {
                //         $loan_code = $row['loan_code'];
                //         $customer_code = isset($row['customercode2']) ? $row['customercode2'] : $row['customer_id'];
                //         $loan_amount = (int) $row['loan_amount'];
                //         // $payout_date = $row['payout_date'];
                //         $staff_name = $row['staff_name'];
                //         $start_month = $row['start_month'];
                //         // show only month name
                //         $start_month = date('Y-m', strtotime($start_month));
                //         if ($start_month > $current_date) {
                //             $sql = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
                //             $q = mysql_query($sql);
                //             $res = mysql_fetch_assoc($q);
                //             $processing_fee = $res['processing_fee'];
                //             $sd = (int) $res['stamp_duty'];
                //             $amount = $loan_amount - $processing_fee;
                //             $payout = $amount - $sd;
    
                //             // $payout_date = DateTime::createFromFormat('Y-m-d', $payout_date);
                //             // $payout_month_name = $payout_date->format('M');
                //             $start_month = DateTime::createFromFormat('Y-m', $start_month);
                //             $start_month_name = $start_month->format('M');
    
                //             $html .= '<tr>
                //                         <td><input type="text" class="cell" value="' . $loan_code . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" value="' . $customer_code . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" id="s' . $count . '" value="' . $loan_amount . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" id="t' . $count . '" value="' . $amount . '" oninput="input(this);" readonly></td>
                //                         <td><input type="text" class="cell" id="u' . $count . '" value="' . $sd . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" id="w' . $count . '" value="' . $payout . '" oninput="input(this);" readonly></td>
                //                         <td><input type="text" class="cell" value="' . $start_month_name . '" oninput="input(this);"></td>
                //                         <td><input type="text" class="cell" value="' . $staff_name . '" oninput="input(this);"></td>
                //                     </tr>';
    
                //             $count++;
                //         }
                //     }

                //     for ($i = $count; $i <= 35; $i++) {
                //         $html .= '<tr>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" id="s' . $i . '" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" id="t' . $i . '" value="0" oninput="input(this);" readonly></td>
                //                     <td><input type="text" class="cell" id="u' . $i . '" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" id="w' . $i . '" value="0" oninput="input(this);" readonly></td>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                     <td><input type="text" class="cell" oninput="input(this);"></td>
                //                 </tr>';
                //     }

                //     $html .= '<tr>
                //                 <td></td>
                //                 <td></td>
                //                 <td id="s36" style="background-color: #4285f4;">0</td>
                //                 <td id="t36" style="background-color: #4285f4;">0</td>
                //                 <td id="u36" style="background-color: #4285f4;">0</td>
                //                 <td id="v36" style="background-color: #4285f4;">0</td>
                //                 <td></td>
                //                 <td></td>
                //             </tr>';
                // break;
                
                case '6':
                    $html .= '<tr>
                                <td colspan="3" style="background-color: #00ff00;"><b>INS CASH OUT (2)</b></td>
                                <td></td>
                                <td style="text-align: right;">Date:</td>
                                <td colspan="2"><input type="text" class="cell" style="text-align: left;" oninput="input(this);"></td>
                                <td></td>
                            </tr>
                            <tr><td colspan="8"></td></tr>
                            <tr>
                                <td><b>Agree No.</b></td>
                                <td><b>ID No.</b></td>
                                <td><b>LOAN AMOUNT</b></td>
                                <td><b>AMOUNT</b></td>
                                <td><b>sd</b></td>
                                <td><b>Payout</b></td>
                                <td><b>Month</b></td>
                                <td><b>Staff Name</b></td>
                            </tr>';

                    // =============================
                    //  Pagination Setup
                    // =============================
                    $page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
                    $offset = ($page - 1) * $limit;

                    // Count total records
                    $count_sql = "SELECT COUNT(*) AS total
                                FROM $db.customer_loanpackage t1
                                LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                                WHERE t1.loan_status = 'Paid'
                                AND t1.start_month LIKE '$next_month%'";
                    $count_result = mysql_query($count_sql);
                    $row_count = mysql_fetch_assoc($count_result);
                    $total_records = (int)$row_count['total'];
                    $total_pages = ceil($total_records / $limit);

                    // =============================
                    //  Main Query with LIMIT/OFFSET
                    // =============================
                    $sql = "SELECT
                                t1.id,
                                t1.customer_id,
                                t1.loan_status,
                                t1.start_month,
                                t1.loan_code,
                                t1.loan_amount,
                                t1.staff_name,
                                t2.customercode2
                            FROM $db.customer_loanpackage t1
                            LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                            WHERE t1.loan_status = 'Paid'
                            AND t1.start_month LIKE '$next_month%'
                            ORDER BY t1.payout_date ASC
                            LIMIT $limit OFFSET $offset";
                    $query = mysql_query($sql);

                    $count = 26;

                    if($total_pages <= 1){
                        $count = 25;
                    } 
                    
                    while ($row = mysql_fetch_assoc($query)) {
                        $loan_code = $row['loan_code'];
                        $customer_code = isset($row['customercode2']) ? $row['customercode2'] : $row['customer_id'];
                        $loan_amount = (int) $row['loan_amount'];
                        $staff_name = $row['staff_name'];
                        $start_month = $row['start_month'];

                        // convert to Y-m
                        $start_month = date('Y-m', strtotime($start_month));
                        if ($start_month > $current_date) {
                            $sql = "SELECT * FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
                            $q = mysql_query($sql);
                            $res = mysql_fetch_assoc($q);
                            $processing_fee = $res['processing_fee'];
                            $sd = (int)$res['stamp_duty'];
                            $amount = $loan_amount - $processing_fee;
                            $payout = $amount - $sd;

                            $start_month = DateTime::createFromFormat('Y-m', $start_month);
                            $start_month_name = $start_month->format('M');

                            $html .= '<tr>
                                        <td><input type="text" class="cell" value="' . $loan_code . '" oninput="input(this);"></td>
                                        <td><input type="text" class="cell" value="' . $customer_code . '" oninput="input(this);"></td>
                                        <td><input type="text" class="cell" id="s' . $count . '" value="' . $loan_amount . '" oninput="input(this);"></td>
                                        <td><input type="text" class="cell" id="t' . $count . '" value="' . $amount . '" oninput="input(this);" readonly></td>
                                        <td><input type="text" class="cell" id="u' . $count . '" value="' . $sd . '" oninput="input(this);"></td>
                                        <td><input type="text" class="cell" id="w' . $count . '" value="' . $payout . '" oninput="input(this);" readonly></td>
                                        <td><input type="text" class="cell" value="' . $start_month_name . '" oninput="input(this);"></td>
                                        <td><input type="text" class="cell" value="' . $staff_name . '" oninput="input(this);"></td>
                                    </tr>';
                            $count++;
                        }
                    }

                    // Empty rows filler
                    for ($i = $count; $i <= 35; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="s' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="t' . $i . '" value="0" oninput="input(this);" readonly></td>
                                    <td><input type="text" class="cell" id="u' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="w' . $i . '" value="0" oninput="input(this);" readonly></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                </tr>';
                    }

                    // Totals row
                    $html .= '<tr>
                                <td></td>
                                <td></td>
                                <td id="s36" style="background-color: #4285f4;">0</td>
                                <td id="t36" style="background-color: #4285f4;">0</td>
                                <td id="u36" style="background-color: #4285f4;">0</td>
                                <td id="v36" style="background-color: #4285f4;">0</td>
                                <td></td>
                                <td></td>
                            </tr>';

                    // =============================
                    //  Pagination Links
                    // =============================
                    if ($total_pages > 1) {
                        $html .= '<tr><td colspan="8" style="text-align:center;">';
                        for ($p = 1; $p <= $total_pages; $p++) {
                            if ($p == $page) {
                                $html .= "<b>[$p]</b> ";
                            } else {
                                $html .= "<a href='#' onclick='loadTable(6, $p, $limit); return false;'>$p</a> ";
                            }
                        }
                        $html .= '</td></tr>';
                    }
                break;

                case '7':
                    $html .= '<tr>
                                <td rowspan="21" style="width: 200px; vertical-align: bottom;">Balance PL</td>
                                <td rowspan="21" id="c39" style="vertical-align: bottom;">0</td>
                                <td>" +</td>
                                <td>" -</td>
                                <td class="yellow">NAMA</td>
                                <td>" +</td>
                                <td>-</td>
                                <td class="yellow">NAMA</td>
                                <td></td>
                                <td>" +</td>
                                <td>-</td>
                                <td class="yellow">NAMA</td>
                                <td></td>
                            </tr>';

                    for ($i = 40; $i <= 58; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" id="d' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="e' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="f' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="g' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="h' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="i' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="j' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="k' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="l' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="m' . $i . '" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" id="n' . $i . '" oninput="input(this);"></td>
                                </tr>';
                    }

                    $html .= '<tr>
                                <td><input type="text" class="cell" id="d59" value="0" oninput="input(this);" readonly></td>
                                <td><input type="text" class="cell" id="e59" value="0" oninput="input(this);" readonly></td>
                                <td></td>
                                <td><input type="text" class="cell" id="g59" value="0" oninput="input(this);" readonly></td>
                                <td><input type="text" class="cell" id="h59" value="0" oninput="input(this);" readonly></td>
                                <td></td>
                                <td><input type="text" class="cell" id="j59" value="0" oninput="input(this);" readonly></td>
                                <td><input type="text" class="cell" id="k59" value="0" oninput="input(this);" readonly></td>
                                <td><input type="text" class="cell" id="l59" value="0" oninput="input(this);" readonly></td>
                                <td></td>
                                <td></td>
                            </tr>';
                    break;
                case '8':
                    for ($i = 0; $i < 3; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                </tr>';
                    }

                    $html .= '<tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td style="text-align: left;"><b>stamp</b></td>
                                <td style="text-align: left;"><b>to day</b></td>
                                <td id="u42">0</td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td style="text-align: left;"><b>settle</b></td>
                                <td style="text-align: left;"><b>to day</b></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                            </tr>';

                    for ($i = 0; $i < 3; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                </tr>';
                    }

                    $html .= '<tr>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td colspan="2" style="text-align: left;"><b>total stamp</b></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td><input type="text" class="cell" oninput="input(this);"></td>
                                <td colspan="2" style="text-align: left;"><b>total settle</b></td>
                            </tr>';

                    for ($i = 0; $i < 12; $i++) {
                        $html .= '<tr>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                    <td><input type="text" class="cell" oninput="input(this);"></td>
                                </tr>';
                    }
                    break;
                default:
                    break;
            }
        }

        header('Content-Type: text/html');
        echo $html;
        exit;
    }
?>