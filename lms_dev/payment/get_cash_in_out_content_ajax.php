<?php
    include_once '../include/dbconnection.php';
    session_start();
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

    header('Content-Type: text/html; charset=UTF-8');

    if (!isset($_POST['table_no'])) { echo ''; exit; }

    $date     = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
    $table_no = $_POST['table_no'];
    $db       = isset($_SESSION['login_database']) ? $_SESSION['login_database'] : '';
    if (!$db) { echo ''; exit; }

    $year  = substr($date, 0, 4);
    $month = substr($date, 5, 2);

    $page   = isset($_POST['page'])  ? (int)$_POST['page']  : 1;
    // $limit  = isset($_POST['limit']) ? (int)$_POST['limit'] : 30;
    $limit  = 27;
    $all    = isset($_POST['all'])   ? (int)$_POST['all']   : 0;   // 1 => fetch all rows (for print)
    $force  = isset($_POST['force']) ? (int)$_POST['force'] : 0;   // 1 => force snapshot for any table

    $html = '';

    /* ---------- helpers ---------- */
    function esc($s){ return mysql_real_escape_string($s); }

    // Detect if this date has been closed already.
    function is_date_closed($db, $date) {
        $d = esc($date);

        // Try table: <db>.closing_cash_in_out
        $sql1 = "SELECT 1 FROM {$db}.closing_cash_in_out WHERE `date`='{$d}' OR `closing_date`='{$d}' LIMIT 1";
        $q1   = @mysql_query($sql1);
        if ($q1 && mysql_num_rows($q1)>0) return true;

        // Try alternate registry
        $sql2 = "SELECT 1 FROM {$db}.cash_in_out_closing WHERE `date`='{$d}' OR `closing_date`='{$d}' LIMIT 1";
        $q2   = @mysql_query($sql2);
        if ($q2 && mysql_num_rows($q2)>0) return true;

        return false;
    }

    // Fetch saved tbody snapshot for a table+date (what your Save pipeline writes)
    function fetch_saved_content($db, $tableNo, $date) {
        $d    = esc($date);
        $tn   = $db . '.cash_in_out_table' . (int)$tableNo;
        $sql  = "SELECT content FROM {$tn} WHERE `date`='{$d}' LIMIT 1";
        // var_dump($sql);
        $q    = mysql_query($sql);
        if ($q && mysql_num_rows($q) === 1) {
            $row = mysql_fetch_assoc($q);
            $content = isset($row['content']) ? $row['content'] : '';
            return trim($content);
        }
        return '';
    }

    $isClosed = is_date_closed($db, $date);

    /* ---------- Snapshot policy ----------
       - T1, T4, T7, T8 : always load saved if present
       - T2, T5         : use saved ONLY when date closed OR force=1.
                          If open, always render LIVE (pagination works).
    ------------------------------------- */

    if (in_array($table_no, array('1','4','7','8'), true)) {
        $saved = fetch_saved_content($db, $table_no, $date);
        if ($saved !== '') { echo $saved; exit; }
        // else render scaffold below…
    } elseif (in_array($table_no, array('2','5'), true)) {
        $saved = fetch_saved_content($db, $table_no, $date);
        if ($saved !== '') { echo $saved; exit; }
    }

    /* ---------- BUILDERS ---------- */
    switch ($table_no) {

        /* ===== TABLE 1 ===== */
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

            for ($row = 5; $row <= 8; $row++) {
                $html .= '<tr>';
                if ($row == 5) {
                    $html .= '<td><input type="text" style="text-align: left;background-color: #ffc499;" class="cell" id="a5" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="b5" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="c5" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="d5" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="e5" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="f5" oninput="input(this);"></td>';
                } elseif ($row == 6) {
                    $html .= '<td><input type="text" class="cell" id="a6" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="b6" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="c6" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="d6" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="e6" oninput="input(this);"></td>
                              <td><input type="text" class="cell" id="f6" oninput="input(this);"></td>';
                } elseif ($row == 7) {
                    $html .= '<td style="text-align: left;"><b>PL:</b></td>
                              <td id="b7" style="background-color: #f06b6b;"></td>
                              <td><b>100</b></td>
                              <td>RM</td>
                              <td><input type="text" class="cell" id="e7" oninput="input(this);"></td>
                              <td id="f7" style="background-color: #ffc499;"></td>';
                } else { // 8
                    $html .= '<td style="text-align: left;"><b>MBB:</b></td>
                              <td id="b8" style="background-color: #bfecc3;"></td>
                              <td><b>50</b></td>
                              <td>RM</td>
                              <td><input type="text" class="cell" id="e8" oninput="input(this);"></td>
                              <td></td>';
                }
                $html .= '</tr>';
            }

            $html .= '<tr>
                        <td style="text-align: left;"><b>PBE:</b></td>
                        <td id="b9" style="background-color: #7fd6e5;"></td>
                        <td><b>10</b></td>
                        <td>RM</td>
                        <td><input type="text" class="cell" id="e9" oninput="input(this);"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">EXPENSES:</td>
                        <td></td>
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
                    
                    ';
            for ($i = 13; $i <= 20; $i++) {
                $html .= '<tr>
                            <td><input type="text" class="cell" oninput="input(this);"></td>
                            <td><input type="text" class="cell" id="b' . $i . '" oninput="input(this);"></td>
                            <td></td><td></td><td></td><td></td>
                          </tr>';
            }
            $html .= '<tr>
                        <td>TOTAL EXP</td>
                        <td id="b21" style="background-color: #ffc499;"></td>
                        <td></td>
                        <td>TOTAL SUM</td>
                        <td id="e21" style="background-color: #ffc499;"></td>
                        <td></td>
                      </tr>';
        break;

        /* ===== TABLE 2 (LIVE with pagination until closed) ===== */
        case '2':
            
            $sql = "
                SELECT
                *
                FROM (
                SELECT
                    t1.*, t2.loan_amount,
                    t3.customercode2,
                    t4.fullname,
                    (SELECT t5.sd FROM $db.monthly_payment_record t5
                    WHERE t5.loan_code = t1.loan_code LIMIT 1) AS sd,
                    t6.balance AS half_balance,
                    t6.ori_instalment
                FROM $db.collection t1
                LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                LEFT JOIN $db.customer_details t3 ON t3.id = t2.customer_id
                JOIN `$db`.`USER` t4 ON t4.id = t1.approved_by_id
                LEFT JOIN $db.half_payment_details t6 ON t6.collection_id = t1.id
                WHERE (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                    AND t1.datetime > '2025-11-01'
                ORDER BY id DESC
                ) subquery
                ORDER BY id ASC";
            $query = mysql_query($sql);
        //  var_dump($sql);
        //                 exit;
            $f5 = 0;
            while ($row = mysql_fetch_assoc($query)) {

               
                $settle_amount = 0;
                if ($row['salary_type'] == 'settlement') {
                    $loan_code = $row['loan_code'];
                    $settle_q = "SELECT balance, discount
                                FROM $db.loan_payment_details t1
                                LEFT JOIN $db.customer_loanpackage t2 ON t2.id = t1.customer_loanid
                                WHERE t1.receipt_no != ''
                                    AND t1.loan_status = 'SETTLE'
                                    AND t1.month_receipt LIKE '$year-$month'
                                    AND loan_code = '$loan_code'";
                    $settle_query = mysql_query($settle_q);
                    $settle = mysql_fetch_assoc($settle_query);
                    if ($settle) {
                        $settle_amount = isset($settle['discount'])
                            ? ($settle['balance'] - $settle['discount'])
                            : $settle['balance'];
                    }
                }

                if (!empty($row['ori_instalment']))          $instalment = $row['ori_instalment'];
                elseif (!empty($row['collection_amount']) &&
                        $row['collection_amount'] > 0)       $instalment = $row['collection_amount'];
                else                                         $instalment = $row['instalment'];

                $tepi1 = $row['tepi1']; $tepi2 = $row['tepi2'];

                if (!empty($row['collection_amount']) && $row['collection_amount'] > 0)        
                    $f5 += $instalment;
                elseif ($settle_amount > 0)                                                     
                    $f5 += $settle_amount;
                else {
                    if ($row['half_balance'] != 0) 
                        $f5 += ($tepi1 + $tepi2);
                    else                           
                        $f5 += ($instalment + $tepi1 + $tepi2);
                }
            }

            $total_sql = "SELECT 
                            SUM(t1.payout_amount * 0.9 -
                                CASE WHEN t1.sd = 'Normal' THEN 5
                                    ELSE (SELECT stamp_duty FROM loansystem.preset_fee
                                            WHERE loan_amount = t1.payout_amount LIMIT 1)
                                END) AS total_payout
                            FROM $db.monthly_payment_record t1
                            LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                            LEFT JOIN $db.customer_details t3 ON t3.id = t1.customer_id
                            WHERE (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                            AND t1.monthly_date > '2025-11-01'
                            AND t1.status != 'DELETED'";
            $total_q = mysql_query($total_sql);
            $total_row = mysql_fetch_assoc($total_q);
            $total_m39_all = (float)(isset($total_row['total_payout']) ? $total_row['total_payout'] : 0);

            $html .= '<tr>
                        <td colspan="4" style="background-color:#5adbf7;"><b>MONTHLY CASH OUT (1)</b></td>
                        <td></td>
                        <td style="text-align:right;">Date:</td>
                        <td colspan="2">'. $date .'</td>
                        <td></td>
                    </tr>
                    <tr><td colspan="9"></td></tr>
                    <tr class="co-header">
                        <td><b>Date</b></td>
                        <td><b>Agree No.</b></td>
                        <td><b>ID No.</b></td>
                        <td><b>LOAN AMOUNT</b></td>
                        <td><b>AMOUNT</b></td>
                        <td><b>sd</b></td>
                        <td><b>Payout</b></td>
                        <td><b>Month</b></td>
                        <td><b>staff name</b></td>
                    </tr>';

            $offset = ($page - 1) * $limit;

            $count_sql = "SELECT COUNT(*) AS total
                            FROM $db.monthly_payment_record t1
                            LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                            LEFT JOIN $db.customer_details t3 ON t3.id = t1.customer_id
                            WHERE (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                            AND t1.monthly_date > '2025-11-01'
                            AND t1.status != 'DELETED'";
            $count_q = mysql_query($count_sql);
            $rowc = mysql_fetch_assoc($count_q);
            $total_records = (int)$rowc['total'];
            $total_pages   = ($limit > 0) ? ceil($total_records / $limit) : 1;

            $count = ($all === 1 || $total_pages <= 1) ? 3 : 4 ;

            $sqlCore = "SELECT
                            t1.id, t1.loan_code, t3.customercode1, t3.customercode2, t1.month,
                            t1.monthly_date AS created_date, t1.payout_amount,
                            t1.payout_amount AS loan_amount, t1.user_id, t1.sd
                        FROM $db.monthly_payment_record t1
                        LEFT JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
                        LEFT JOIN $db.customer_details t3 ON t3.id = t1.customer_id
                        WHERE (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                            AND t1.monthly_date > '2025-11-01'
                            AND t1.status != 'DELETED'
                        ORDER BY t1.monthly_date ASC";
            $sql = ($all === 1 || $total_pages <= 1)
                    ? $sqlCore
                    : $sqlCore . " LIMIT $limit OFFSET $offset";

            $query = mysql_query($sql);

            $sumJ=0; $sumK=0; $sumL=0; $sumM=0;

            while ($row = mysql_fetch_assoc($query)) {
                $loan_code     = $row['loan_code'];
                $customer_code = ($row['customercode2'] ? $row['customercode2'] : $row['customercode1']);
                $loan_amount   = (float)$row['payout_amount'];
                $amount        = $loan_amount * 0.9;

                if ($row['sd'] == 'Normal') {
                    $sd = 5;
                } else {
                    $sd_sql = "SELECT stamp_duty FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'";
                    $sd_q   = mysql_query($sd_sql);
                    $sd_res = mysql_fetch_assoc($sd_q);
                    $sd     = isset($sd_res['stamp_duty']) ? (float)$sd_res['stamp_duty'] : 0;
                }
                $payout = $amount - $sd;

                $m = DateTime::createFromFormat('Y-m', $row['month']);
                $month_name = $m ? $m->format('M') : '';

                $payment_date  = date("d M Y", strtotime($row['created_date']));

                $html .= '<tr>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($payment_date).'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($loan_code).'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($customer_code).'"></td>
                <td><input type="text" class="cell" readonly id="j'.$count.'" value="'.$loan_amount.'"></td>
                <td><input type="text" class="cell" readonly id="k'.$count.'" value="'.$amount.'"></td>
                <td><input type="text" class="cell" readonly id="l'.$count.'" value="'.$sd.'"></td>
                <td><input type="text" class="cell" readonly id="m'.$count.'" value="'.$payout.'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($month_name).'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($row['user_id']).'"></td>
                </tr>';

                $sumJ += $loan_amount;
                $sumK += $amount;
                $sumL += $sd;
                $sumM += $payout;

                $count++;
            }

            if ($all !== 1) {
                for ($i = $count; $i <= 30; $i++) {
                    $html .= '<tr>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly id="j'.$i.'"></td>
                        <td><input type="text" class="cell" readonly id="k'.$i.'" value="0"></td>
                        <td><input type="text" class="cell" readonly id="l'.$i.'"></td>
                        <td><input type="text" class="cell" readonly id="m'.$i.'" value="0"></td>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly></td>
                    </tr>';
                }
            }

            $html .= '<tr>
                <td style="display:none;"><input type="hidden" id="hidden_f5" value="'.$f5.'"></td>
                <td style="display:none;"><input type="hidden" id="total_m39_all" value="'.$total_m39_all.'"></td>
                <td></td><td></td><td></td>
                <td id="j39" style="background-color:#4285f4;">'.(int)$sumJ.'</td>
                <td id="k39" style="background-color:#4285f4;">'.(int)$sumK.'</td>
                <td id="l39" style="background-color:#4285f4;">'.(int)$sumL.'</td>
                <td id="m39" style="background-color:#4285f4;">'.(int)$sumM.'</td>
                <td></td><td></td>
            </tr>';

            if ($all !== 1 && $total_pages > 1) {
                $html .= '<tr><td colspan="9" style="text-align:center;">';
                for ($p = 1; $p <= $total_pages; $p++) {
                    if ($p == $page) $html .= "<strong>[$p]</strong> ";
                    else $html .= "<a href=\"#\" onclick=\"loadTable(2, $p)\">$p</a> ";
                }
                $html .= '</td></tr>';
            }
        break;

        /* ===== TABLE 4 ===== */
        case '4':
            $html .= '
                <tr>
                    <td colspan="6" style="background:#ff0000;color:#fff;text-align:center;font-size:28px;font-weight:bold;padding:8px 0;">
                        ONG TIAP2 HARI
                    </td>
                </tr>

                <tr>
                    <td colspan="6" id="b28" style="text-align:center;font-size:46px;font-weight:bold;padding:16px 0;">0</td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight:bold;">kena isi</td>
                </tr>

                <tr>
                    <td colspan="2" style="white-space:nowrap;">Before date MJ MBB</td>
                    <td id="val_mbb" contenteditable="true" style="width:120px;text-align:center;border-bottom:1px;"></td>
                    <td colspan="2" style="white-space:nowrap;">Before date MJ PBE</td>
                    <td id="val_pbe" contenteditable="true" style="width:120px;text-align:center;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="white-space:nowrap;">Before date PL</td>
                    <td id="val_pl" contenteditable="true" style="width:120px;text-align:center;"></td>
                    <td colspan="2"></td><td></td>
                </tr>';
        break;

        /* ===== TABLE 5 (LIVE with pagination until closed) ===== */
        case '5':
            $html .= '<tr>
                <td colspan="4" style="background-color:#ffff00;"><b>INS CASH OUT (1)</b></td>
                <td></td>
                <td style="text-align:right;">Date:</td>
                <td colspan="2">'. $date .'</td>                
                <td></td>
                </tr>
                <tr><td colspan="9"></td></tr>
                <tr class="co-header">
                <td><b>Date</b></td>
                <td><b>Agree No.</b></td>
                <td><b>ID No.</b></td>
                <td><b>LOAN AMOUNT</b></td>
                <td><b>AMOUNT</b></td>
                <td><b>sd</b></td>
                <td><b>Payout</b></td>
                <td><b>Month</b></td>
                <td><b>Staff Name</b></td>
                </tr>';

            $total_v39_sql = "SELECT 
                                SUM(t1.loan_amount - f.processing_fee - f.stamp_duty) AS total_payout
                                FROM $db.customer_loanpackage t1
                                LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                                LEFT JOIN loansystem.preset_fee f ON f.loan_amount = t1.loan_amount
                                WHERE t1.loan_status = 'Paid'
                                AND (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                                AND t1.payout_date > '2025-11-01'";
            $total_v39_q = mysql_query($total_v39_sql);
            $total_v39_row = mysql_fetch_assoc($total_v39_q);
            $total_v39_all = (float)(isset($total_v39_row['total_payout']) ? $total_v39_row['total_payout'] : 0);

            $offset = ($page - 1) * $limit;

            $count_sql = "SELECT COUNT(*) AS total
                            FROM $db.customer_loanpackage t1
                            LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                            WHERE t1.loan_status = 'Paid'
                            AND (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                            AND t1.payout_date > '2025-11-01'";
            $count_result = mysql_query($count_sql);
            $row_count = mysql_fetch_assoc($count_result);
            $total_records = (int)$row_count['total'];
            $total_pages   = ($limit > 0) ? ceil($total_records / $limit) : 1;

            $count = ($all === 1 || $total_pages <= 1) ? 3 : 4;

            $sqlCore = "SELECT t1.*, t2.customercode2
                        FROM $db.customer_loanpackage t1
                        LEFT JOIN $db.customer_details t2 ON t2.id = t1.customer_id
                        WHERE t1.loan_status = 'Paid'
                            AND (t1.closing_status != 'YES' OR t1.closing_status IS NULL)
                            AND t1.payout_date > '2025-11-01'
                        ORDER BY t1.payout_date ASC";
            $sql = ($all === 1 || $total_pages <= 1)
                    ? $sqlCore
                    : $sqlCore . " LIMIT $limit OFFSET $offset";
            $query = mysql_query($sql);

            $sumS=0; $sumT=0; $sumU=0; $sumV=0;

            while ($row = mysql_fetch_assoc($query)) {
                $loan_code     = $row['loan_code'];
                $customer_code = isset($row['customercode2']) ? $row['customercode2'] : $row['customer_id'];
                $loan_amount   = (float)$row['loan_amount'];
                $fee_q = mysql_query("SELECT processing_fee, stamp_duty FROM loansystem.preset_fee WHERE loan_amount = '$loan_amount'");
                $fee   = mysql_fetch_assoc($fee_q);
                $processing_fee = isset($fee['processing_fee']) ? (float)$fee['processing_fee'] : 0;
                $sd             = isset($fee['stamp_duty'])     ? (float)$fee['stamp_duty']     : 0;

                $amount = $loan_amount - $processing_fee;
                $payout = $amount - $sd;

                $payment_date = date("d M Y", strtotime($row['payout_date']));

                $sm = $row['start_month'];
                if (strlen($sm) == 7)      $dt = DateTime::createFromFormat('Y-m', $sm);
                elseif (strlen($sm) == 10) $dt = DateTime::createFromFormat('Y-m-d', $sm);
                else                       $dt = false;
                $start_month_name = $dt ? $dt->format('M') : '';

                $html .= '<tr>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($payment_date).'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($loan_code).'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($customer_code).'"></td>
                <td><input type="text" class="cell" readonly id="s'.$count.'" value="'.$loan_amount.'"></td>
                <td><input type="text" class="cell" readonly id="t'.$count.'" value="'.$amount.'"></td>
                <td><input type="text" class="cell" readonly id="u'.$count.'" value="'.$sd.'"></td>
                <td><input type="text" class="cell" readonly id="v'.$count.'" value="'.$payout.'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($start_month_name).'"></td>
                <td><input type="text" class="cell" readonly value="'.htmlspecialchars($row['staff_name']).'"></td>
                </tr>';

                $sumS += $loan_amount;
                $sumT += $amount;
                $sumU += $sd;
                $sumV += $payout;

                $count++;
            }

            if ($all !== 1) {
                for ($i = $count; $i <= 30; $i++) {
                    $html .= '<tr>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly id="s'.$i.'"></td>
                        <td><input type="text" class="cell" readonly id="t'.$i.'" value="0"></td>
                        <td><input type="text" class="cell" readonly id="u'.$i.'"></td>
                        <td><input type="text" class="cell" readonly id="v'.$i.'" value="0"></td>
                        <td><input type="text" class="cell" readonly></td>
                        <td><input type="text" class="cell" readonly></td>
                    </tr>';
                }
            }

            $html .= '<tr>
                <td style="display:none;"><input type="hidden" id="total_v39_all" value="'.$total_v39_all.'"></td>
                <td></td><td></td><td></td>
                <td id="s39" style="background-color:#4285f4;">'.(int)$sumS.'</td>
                <td id="t39" style="background-color:#4285f4;">'.(int)$sumT.'</td>
                <td id="u39" style="background-color:#4285f4;">'.(int)$sumU.'</td>
                <td id="v39" style="background-color:#4285f4;">'.(int)$sumV.'</td>
                <td></td><td></td>
            </tr>';

            if ($all !== 1 && $total_pages > 1) {
                $html .= '<tr><td colspan="9" style="text-align:center;">';
                for ($p = 1; $p <= $total_pages; $p++) {
                    if ($p == $page) $html .= "<b>[$p]</b> ";
                    else $html .= "<a href=\"#\" onclick=\"loadTable(5, $p, $limit); return false;\">$p</a> ";
                }
                $html .= '</td></tr>';
            }
        break;

        /* ===== TABLE 7 ===== */
        case '7':
            $startRow = 40;
            $endRow   = 58;
            $totalsId = 59;

            $dataRowCount = $endRow - $startRow + 1;
            $rowspanAll   = $dataRowCount + 1;

            $html .= '<tr>
                <td style="width: 4%;" class="band-col band-blue"  rowspan="'.$rowspanAll.'"></td>
                <td style="width: 4%;" class="band-col band-red"   rowspan="'.$rowspanAll.'"></td>
                <td style="width: 4%;" class="band-col band-green" rowspan="'.$rowspanAll.'"></td>

                <td class="hdr-plus">+ PL</td>
                <td class="hdr-minus">- PL</td>
                <td class="band-red">NAMA</td>

                <td class="hdr-plus">+ PL</td>
                <td class="hdr-minus">- PL</td>
                <td class="band-red">NAMA</td>

                <td class="hdr-plus">+ PL</td>
                <td class="hdr-minus">- PL</td>
                <td class="band-red">NAMA</td>

                <td class="hdr-plus">+ MJ / MBB</td>
                <td class="hdr-minus">- MJ / MBB</td>
                <td class="band-green">NAMA</td>

                <td class="hdr-plus">+ MJ PBE</td>
                <td class="hdr-minus">- MJ PBE</td>
                <td class="band-blue">NAMA</td>
            </tr>';

            for ($r = 40; $r <= 58; $r++) {
                $html .= '<tr>
                <td><input id="d'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="e'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="f'.$r.'" class="cell"></td>

                <td><input id="g'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="h'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="i'.$r.'" class="cell"></td>

                <td><input id="j'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="k'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="l'.$r.'" class="cell"></td>

                <td><input id="m'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="n'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="o'.$r.'" class="cell"></td>

                <td><input id="p'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="q'.$r.'" class="cell" oninput="input(this);"></td>
                <td><input id="r'.$r.'" class="cell"></td>
                </tr>';
            }

            $html .= '<tr>
                <td class="band-col band-blue"><input id="b'.$totalsId.'"  class="cell" value="0" readonly></td>
                <td class="band-col band-red"><input  id="c'.$totalsId.'"  class="cell" value="0" readonly></td>
                <td class="band-col band-green"><input id="gb'.$totalsId.'" class="cell" value="0" readonly></td>

                <td><input id="d'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="e'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="f'.$totalsId.'" class="cell" value=""  readonly></td>

                <td><input id="g'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="h'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="i'.$totalsId.'" class="cell" value=""  readonly></td>

                <td><input id="j'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="k'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="l'.$totalsId.'" class="cell" value=""  readonly></td>

                <td><input id="m'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="n'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="o'.$totalsId.'" class="cell" value=""  readonly></td>

                <td><input id="p'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="q'.$totalsId.'" class="cell" value="0" readonly></td>
                <td><input id="r'.$totalsId.'" class="cell" value=""  readonly></td>
            </tr>';
        break;

        /* ===== TABLE 8 ===== */
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
              <td style="text-align:left;"><b>stamp</b></td>
              <td style="text-align:left;"><b>to day</b></td>
              <td id="u42">0</td>
              <td><input type="text" class="cell" oninput="input(this);"></td>
              <td><input type="text" class="cell" oninput="input(this);"></td>
              <td><input type="text" class="cell" oninput="input(this);"></td>
            </tr>
            <tr>
              <td><input type="text" class="cell" oninput="input(this);"></td>
              <td><input type="text" class="cell" oninput="input(this);"></td>
              <td style="text-align:left;"><b>settle</b></td>
              <td style="text-align:left;"><b>to day</b></td>
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
              <td colspan="2" style="text-align:left;"><b>total stamp</b></td>
              <td><input type="text" class="cell" oninput="input(this);"></td>
              <td><input type="text" class="cell" oninput="input(this);"></td>
              <td colspan="2" style="text-align:left;"><b>total settle</b></td>
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

    echo $html;
    exit;
?>
