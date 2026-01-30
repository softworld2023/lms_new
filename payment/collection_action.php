<?php
include_once '../include/dbconnection.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set( 'Asia/Kuala_Lumpur' );

if ( isset( $_POST ) ) {
    $collection_id = isset( $_POST[ 'collection_id' ] ) ? $_POST[ 'collection_id' ] : '';
    $action = isset( $_POST[ 'action' ] ) ? $_POST[ 'action' ] : '';

    $db = $_SESSION[ 'login_database' ];

    // ==========================================
    //     Condition 1: If action is approve
    // ==========================================
    if ( $action == 'approve' ) {
        // ======================================
        //     Fetch related loan details
        // ======================================
        $sql = "SELECT
                        t1.*,
                        t1.id AS collection_id,
                        t2.id AS loan_payment_details_id,
                        -- t2.monthly,
                        t2.month,
                        -- t2.next_paymentdate,
                        t2.package_id,
                        -- t2.loan_percent,
                        -- t3.id AS customer_loanpackage_id,
                        t3.customer_id,
                        t3.loan_period,
                        t3.loan_amount
                    FROM
                        $db.collection t1
                    JOIN $db.loan_payment_details t2 ON t2.receipt_no = t1.loan_code
                    JOIN $db.customer_loanpackage t3 ON t3.loan_code = t1.loan_code
                    WHERE
                        t1.id = '$collection_id'
                    ORDER BY t2.id DESC
                    LIMIT 1
                    ";
        $query = mysql_query( $sql );
        if (mysql_num_rows($query) == 0) {
            $sql = "SELECT
                        t1.*,
                        t1.id AS collection_id,
                        t2.id AS loan_payment_details_id,
                        -- t2.monthly,
                        MONTH(t2.month) AS month,
                        -- t2.next_paymentdate,
                        t2.package_id,
                        -- t2.loan_percent,
                        -- t2.id AS customer_loanpackage_id,
                        t2.customer_id,
                        0 AS loan_period,
                        t2.payout_amount AS loan_amount
                    FROM
                        $db.collection t1
                    JOIN $db.monthly_payment_record t2 ON t2.loan_code = t1.loan_code
                    WHERE
                        t1.id = '$collection_id'
                    ORDER BY t2.id DESC
                    LIMIT 1
                    ";
            $query = mysql_query( $sql );
            $result = mysql_fetch_assoc($query); 
        } else {
            $result = mysql_fetch_assoc($query);
            // Process $result
        }
// var_dump($sql);
// exit;
        // ======================================
        //           Extract key data
        // ======================================
        $loan_code = $result[ 'loan_code' ];
        $id = $result[ 'loan_payment_details_id' ];
        $instalment = $result[ 'instalment' ];
        $instalment_type = $result[ 'instalment_type' ];
        $salary_type = $result[ 'salary_type' ];
        $payment_date = date( 'Y-m-d' );
        $period = $result[ 'loan_period' ];
        $loan_amount_ori = $result[ 'loan_amount' ];
        $month = $result[ 'month' ];
        $receipt_no = $loan_code;
        $prev_receipt = $loan_code;
        $month_receipt = $result[ 'instalment_month' ];
        $tepi1_month = $result[ 'tepi1_month' ];
        // $customer_code = $result[ 'customercode' ];
        $balance_half = 0;
        $ori_instalment = $result[ 'loan_amount' ];

        // // ======================================
        // //  Collection for Bad Debt (Instalment)
        // // ======================================
        // if($salary_type == 'BAD DEBT') {
        //     $lid_q = mysql_query( "SELECT id, package_id FROM $db.late_interest_record WHERE loan_code = '".$loan_code."'" );
        //     $lids = mysql_fetch_assoc( $lid_q );
        //     $lid = $lids[ 'id' ];
        //     $package_id = $lids[ 'package_id' ];

        //     $date = date( 'Y-m-d' );
        //     $amount = $instalment;
        
        //     //late interest record
        //     //lir = loan interest
        //     $lir_q = mysql_query( "SELECT * FROM $db.late_interest_record WHERE id = '".$lid."'" );
        //     $lir = mysql_fetch_assoc( $lir_q );

        //     //cust info
        //     $cust_q = mysql_query( "SELECT * FROM $db.customer_details
        //                                 WHERE id = '".$lir[ 'customer_id' ]."'" );
        //     $cust = mysql_fetch_assoc( $cust_q );

        //     //check package receipt
        //     $rt_q = mysql_query( "SELECT * FROM $db.loan_scheme WHERE id = '".$package_id."'" );
        //     $get_rt = mysql_fetch_assoc( $rt_q );

        //     //transaction name
        //     $trans = 'LATE INT - '.$cust[ 'name' ].' - '.$lir[ 'loan_code' ];

        //     $balance = $lir['balance'] - $amount;
            
        //     if($balance <= 0)
        //     {
        //         $balance = 0;
        //         $update_q2 = mysql_query("UPDATE $db.customer_details SET blacklist = ' ' WHERE id = '".$lir['customer_id']."'");

        //     }
 
        //     //insert a new record in this table
        //     $insert_q = mysql_query("INSERT INTO $db.late_interest_payment_details SET
        //                             lid = '".$lid."', 
        //                             amount = '".$amount."', 
        //                             balance = '".$balance."',
        //                             payment_date = '".$date."', 
        //                             month_receipt = '".$month_receipt."',
        //                             created_by = '".$_SESSION['login_name']."', 
        //                             collection_status = 'APPROVED',
        //                             collection_id = '".$collection_id."',
        //                             created_date = now()");
            
        //     if($insert_q) {
        //         $late_payment_id = mysql_insert_id();
        //         //then update the late_interest_record,
        //         // each customer has only one record
        //         $update = mysql_query("UPDATE $db.late_interest_record
        //                                 SET balance = '".$balance."'
        //                                 WHERE id = '".$lid."'");
                
        //         if($update) {
        //             //After it's updated, insert a new records in cashbook
        //             $insert_c_q = mysql_query( "INSERT INTO $db.cashbook 
        //                                         SET type = 'RECEIVED2', 
        //                                         package_id = '".$package_id."', 
        //                                         table_id = '".$lid."', 
        //                                         transaction = '".$trans."', 
        //                                         amount = '".$amount."', 
        //                                         date = '".$date."', 
        //                                         branch_id = '" . $_SESSION['login_branchid'] . "',
        //                                         branch_name = '" . $_SESSION['login_branch'] . "', 
        //                                         staff_name = '".$_SESSION['login_name']."', 
        //                                         receipt_no = 'LATE INT ".$loan_code."', 
        //                                         created_date = now()" );
        //             if ( $insert_c_q ) {
        //                 if ( $get_rt[ 'receipt_type' ] == '1' ) {
        //                     $rcpmth = date( 'Y-m', strtotime( $date ) );
        //                     $insert_b2q = mysql_query( "INSERT INTO $db.balance_rec 
        //                                                 SET package_id = '".$package_id."', 
        //                                                 interest = '".$amount."', 
        //                                                 bal_date = '".$date."', 
        //                                                 month_receipt = '".$rcpmth."', 
        //                                                 branch_id = '" . $_SESSION['login_branchid'] . "',
        //                                                 branch_name = '" . $_SESSION['login_branch'] . "'" );
        //                 }
        //             }
        //         }
        //     }

        //     // ======================================
        //     //          Return JSON Response
        //     // ======================================
        //     header( 'Content-Type: application/json' );
        //     echo json_encode( $response );
        //     exit();
        // }

        // ======================================
        //     Handle Half Month Instalment
        // ======================================
        if ( $instalment_type == 'Half Month' ) {

            if (!empty($period)) {
                $sql = "SELECT {$period}months FROM $db.new_package WHERE loan_amount = '$loan_amount_ori'";
                $q = mysql_query($sql);
                $res = mysql_fetch_assoc($q);
                $ori_instalment = $res[$period . 'months'];
            }

            // --------------------------------------
            //      Prepare to calculate balance
            // --------------------------------------
            $balance = 0;
            $is_first_payment = true;

            // Check for previous balance (previous payment)
            $sql = "SELECT balance FROM $db.half_payment_details 
                    WHERE loan_code = '$loan_code' 
                    AND month_receipt = '$month_receipt'
                    ORDER BY id DESC LIMIT 1";  // In case multiple entries, get the latest

            $query = mysql_query($sql);
            if ($row = mysql_fetch_assoc($query)) {
                $balance = (float)$row['balance'];
                $is_first_payment = false;
            }

            // --------------------------------------
            //         Calculate new balance
            // --------------------------------------
            $ori_instalment = (float)$ori_instalment;
            $instalment = (float)$instalment;

            if ($is_first_payment) {
                $balance_half = $ori_instalment - $instalment;
            } else {
                $balance_half = $balance - $instalment;
            }

            // Optional: prevent negative balance
            if ($balance_half < 0) $balance_half = 0;
        }

        // ======================================
        //  Build response array for next action
        // ======================================
        $response = array(
            'action' => 'payloan',
            'id' => $id,
            'balance_half' => $balance_half,
            'ori_instalment' => $ori_instalment,
            'amount' => $instalment,
            'date' => $payment_date,
            'period' => $period,
            'month' => $month,
            'nextdate' => $payment_date,
            'receipt' => $receipt_no,
            'prev_receipt' => $prev_receipt,
            'month_receipt' => $month_receipt
        );

        $customer_id = $result[ 'customer_id' ];
        $package_id = $result[ 'package_id' ];
        $tepi1 = $result[ 'tepi1' ];
        $tepi2 = $result[ 'tepi2' ];
        $tepi1_month = $result[ 'tepi1_month' ];
        $tepi2_month = $result[ 'tepi2_month' ];
        $salary = $result[ 'salary' ];


        // ======================================
        //           Handle payout tepi1
        // ======================================
        if ($tepi1 > 0.00) {
            $payout_amount = $tepi1 + $tepi2;

            // --------------------------------------
            //  Settle unpaid balances (oldest first)
            // --------------------------------------
            $sql = "SELECT id, balance 
                    FROM $db.monthly_payment_record 
                    WHERE loan_code = '$loan_code' AND balance > 0 AND status != 'DELETED'
                    ORDER BY id ASC"; // oldest first
            //  var_dump($sql);
            //                 exit;
            $query = mysql_query($sql);

            $remaining_payout_amount = $payout_amount;
            $count = 0;

            while ($row = mysql_fetch_assoc($query)) {
                $mprid = $row['id'];
                $balance = (float)$row['balance'];

                if ($remaining_payout_amount <= 0) {
                    break;
                }

                // Determine how much to pay for this record
                $pay_amount = ($remaining_payout_amount >= $balance) ? $balance : $remaining_payout_amount;
                $new_balance = $balance - $pay_amount;

                // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                //  Insert payment detail for this mprid
                // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                $sql = "INSERT INTO $db.monthly_payment_details SET
                            mprid = '$mprid',
                            payment_amount = '$pay_amount',
                            balance = '$new_balance',
                            payment_date = '$payment_date',
                            collection_id = '$collection_id',
                            collection_status = 'APPROVED',
                            created_by = '" . $_SESSION['login_name'] . "',
                            created_date = NOW()";
                            // var_dump($sql);
                            // exit;
                mysql_query($sql);
                // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                //   Update balance and status of mprid
                // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                $status = ($new_balance <= 0) ? 'FINISHED' : 'PAID';
                $sql = "UPDATE $db.monthly_payment_record SET
                            balance = '" . number_format($new_balance, 2, '.', '') . "',
                            status = '$status',
                            payment_date = '$payment_date'
                        WHERE id = '$mprid'";
                mysql_query($sql);

                $remaining_payout_amount -= $pay_amount;
                $count++;
            }

            // --------------------------------------
            //   If no unpaid record, insert new one
            // --------------------------------------
            if ($count == 0) {
                $sql = "INSERT INTO $db.monthly_payment_record SET
                            customer_id = '$customer_id',
                            monthly_date = '$payment_date',
                            payout_date = '$payment_date',
                            payout_amount = '$payout_amount',
                            package_id = '$package_id',
                            loan_code = '$loan_code',
                            balance = '$payout_amount',
                            month = '$tepi1_month',
                            status = 'PAID',
                            sd = 'Normal',
                            user_id = '" . $_SESSION['login_name'] . "',
                            branch_id = '" . $_SESSION['login_branchid'] . "',
                            branch_name = '" . $_SESSION['login_branch'] . "'";
                mysql_query($sql);
                $mprid = mysql_insert_id();

                if ($mprid) {
                    $sql = "INSERT INTO $db.monthly_payment_details SET
                                mprid = '$mprid',
                                payment_amount = '$payout_amount',
                                balance = '$payout_amount',
                                payment_date = '$payment_date',
                                collection_id = '$collection_id',
                                collection_status = 'APPROVED',
                                created_by = '" . $_SESSION['login_name'] . "',
                                created_date = NOW()";
                    mysql_query($sql);

                    $sql = "UPDATE $db.monthly_payment_record SET
                                balance = '0',
                                status = 'FINISHED',
                                payment_date = '$payment_date'
                            WHERE id = '$mprid'";
                    mysql_query($sql);
                }

                $msg = 'Payment has been successfully saved into record.<br>';
                $_SESSION['msg'] = "<div class='success'>$msg</div>";
            }
        }

        // ======================================
        //           Handle payout tepi2
        // ======================================
        if ( $tepi2 > 0.00 ) {


            // =============================
            //  1. Use tepi2_month directly as YYMM
            // =============================
            // Convert YYYY-MM → YYMM
            $year  = substr($tepi2_month, 2, 2);  // "25"
            $month = substr($tepi2_month, 5, 2);  // "09"
            $nextYYMM = $year . $month;           // "2509"

            // =============================
            //  2. Check for reusable deleted sequence
            //     but only if no ACTIVE record exists with same loan_code
            // =============================
            $sql = "SELECT d.loan_code
                    FROM $db.monthly_payment_record d
                    WHERE d.loan_code LIKE '{$nextYYMM}-%'
                    AND d.status = 'DELETED'
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM $db.monthly_payment_record a
                        WHERE a.loan_code = d.loan_code
                            AND a.status != 'DELETED'
                    )
                    ORDER BY d.loan_code ASC
                    LIMIT 1";
            $res = mysql_query($sql);
            // var_dump($sql);
            if ($row = mysql_fetch_assoc($res)) {
                // ✅ Reuse this deleted loan_code
                $new_loan_code = $row['loan_code'];
            } else {
                // =============================
                //  2b. Get latest NON-DELETED sequence for this YYMM (fallback)
                // =============================
                $sql = "SELECT loan_code 
                        FROM $db.monthly_payment_record 
                        WHERE loan_code LIKE '{$nextYYMM}-%'
                        AND status != 'DELETED'
                        ORDER BY loan_code DESC
                        LIMIT 1";
                $res = mysql_query($sql);

                $nextSeq = 1; // Default if no record found
                if ($row = mysql_fetch_assoc($res)) {
                    if (preg_match('/^' . preg_quote($nextYYMM, '/') . '\-(\d+)$/', $row['loan_code'], $matches)) {
                        $nextSeq = intval($matches[1]) + 1;
                    }
                }

                // =============================
                //  3. Build the new loan_code
                // =============================
                $new_loan_code = $nextYYMM . '-' . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
            }

            if (ctype_digit(substr($loan_code, 0, 1))) {
                // Loan code starts with digit → always use $new_loan_code
                $final_loan_code = $new_loan_code;
            } else {
                // Loan code starts with letter → reuse existing
                $final_loan_code = $loan_code;
            }

            if($tepi1 == 0.00) {
                $payout_amount = $tepi1 + $tepi2;

                // --------------------------------------
                //  Settle unpaid balances (oldest first)
                // --------------------------------------
                $sql = "SELECT id, balance 
                        FROM $db.monthly_payment_record 
                        WHERE loan_code = '$loan_code' AND balance > 0 AND status != 'DELETED'
                        ORDER BY id ASC"; // oldest first
                //  var_dump($sql);
                //                 exit;
                $query = mysql_query($sql);

                $remaining_payout_amount = $payout_amount;
                $count = 0;

                while ($row = mysql_fetch_assoc($query)) {
                    $mprid = $row['id'];
                    $balance = (float)$row['balance'];

                    if ($remaining_payout_amount <= 0) {
                        break;
                    }

                    // Determine how much to pay for this record
                    $pay_amount = ($remaining_payout_amount >= $balance) ? $balance : $remaining_payout_amount;
                    $new_balance = $balance - $pay_amount;

                    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                    //  Insert payment detail for this mprid
                    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                    $sql = "INSERT INTO $db.monthly_payment_details SET
                                mprid = '$mprid',
                                payment_amount = '$pay_amount',
                                balance = '$new_balance',
                                payment_date = '$payment_date',
                                collection_id = '$collection_id',
                                collection_status = 'APPROVED',
                                created_by = '" . $_SESSION['login_name'] . "',
                                created_date = NOW()";
                                // var_dump($sql);
                                // exit;
                    mysql_query($sql);
                    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                    //   Update balance and status of mprid
                    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                    $status = ($new_balance <= 0) ? 'FINISHED' : 'PAID';
                    $sql = "UPDATE $db.monthly_payment_record SET
                                balance = '" . number_format($new_balance, 2, '.', '') . "',
                                status = '$status',
                                payment_date = '$payment_date'
                            WHERE id = '$mprid'";
                    mysql_query($sql);

                    $remaining_payout_amount -= $pay_amount;
                    $count++;
                }

                // --------------------------------------
                //   If no unpaid record, insert new one
                // --------------------------------------
                if ($count == 0) {
                    $sql = "INSERT INTO $db.monthly_payment_record SET
                                customer_id = '$customer_id',
                                monthly_date = '$payment_date',
                                payout_amount = '$payout_amount',
                                payout_date = '$payment_date', 
                                package_id = '$package_id',
                                loan_code = '$final_loan_code',
                                balance = '$payout_amount',
                                month = '$tepi2_month',
                                status = 'PAID',
                                sd = 'Normal',
                                user_id = '" . $_SESSION['login_name'] . "',
                                branch_id = '" . $_SESSION['login_branchid'] . "',
                                branch_name = '" . $_SESSION['login_branch'] . "'";
                    mysql_query($sql);
                    // var_dump($sql);
                                // exit;
                    $mprid = mysql_insert_id();

                    if ($mprid) {
                        $sql = "INSERT INTO $db.monthly_payment_details SET
                                    mprid = '$mprid',
                                    payment_amount = '$payout_amount',
                                    balance = '$payout_amount',
                                    payment_date = '$payment_date',
                                    collection_id = '$collection_id',
                                    collection_status = 'APPROVED',
                                    created_by = '" . $_SESSION['login_name'] . "',
                                    created_date = NOW()";
                        mysql_query($sql);

                        $sql = "UPDATE $db.monthly_payment_record SET
                                    balance = '0',
                                    status = 'FINISHED',
                                    payment_date = '$payment_date'
                                WHERE id = '$mprid'";
                        mysql_query($sql);
                    }
                } else {
                    $sql = "INSERT INTO $db.monthly_payment_record SET
                            customer_id = '$customer_id', 
                            monthly_date = '$payment_date', 
                            payout_date = '$payment_date', 
                            payout_amount = '$tepi2', 
                            package_id = '$package_id', 
                            loan_code = '$final_loan_code', 
                            balance = '$tepi2', 
                            month = '$tepi2_month',
                            status = 'PAID',
                            sd = 'Normal',
                            user_id = '" . $_SESSION['login_name'] . "', 
                            branch_id = '" . $_SESSION['login_branchid'] . "', 
                            branch_name = '" . $_SESSION['login_branch'] . "'";
                            // var_dump('0'.$sql);
                            //     exit;
                    mysql_query($sql);
                }
            }else{
                $sql = "INSERT INTO $db.monthly_payment_record SET
                            customer_id = '$customer_id', 
                            monthly_date = '$payment_date', 
                            payout_amount = '$tepi2', 
                            payout_date = '$payment_date', 
                            package_id = '$package_id', 
                            loan_code = '$final_loan_code', 
                            balance = '$tepi2', 
                            month = '$tepi2_month',
                            status = 'PAID',
                            sd = 'Normal',
                            user_id = '" . $_SESSION['login_name'] . "', 
                            branch_id = '" . $_SESSION['login_branchid'] . "', 
                            branch_name = '" . $_SESSION['login_branch'] . "'";
                mysql_query($sql);
              
            }
            // var_dump($tepi1);
            // var_dump($new_loan_code);
            //   var_dump($sql);
            //     exit;
            $sql = "UPDATE $db.collection 
                    SET tepi2_loan_code = '$final_loan_code' 
                    WHERE id = '$collection_id'";
            mysql_query($sql);
            // var_dump($sql);
            // exit;

            // --------------------------------------
            //          Show success message
            // --------------------------------------
            if ( $query ) {
                $msg = 'Payment has been successfully saved into record.<br>';
                $_SESSION[ 'msg' ] = "<div class='success'>".$msg.'</div>';
            }
        }

        // ======================================
        //          Record Short Payments
        // ======================================
        if ( $salary_type == 'Short' ) {

            // --------------------------------------
            //   Save short payment for instalment
            // --------------------------------------
            if($instalment > 0.00 ){

                $sql = "INSERT INTO $db.short_record SET
                                customer_id = '$customer_id',
                                collection_id = '$collection_id', 
                                short_date = '$payment_date', 
                                amount = '$instalment', 
                                package_id = '$package_id', 
                                loan_code = '$loan_code', 
                                status = 'PAID',
                                user_id = '" . $_SESSION[ 'login_name' ] . "', 
                                balance = '$instalment', 
                                short_type = 'INSTALMENT',
                                month_receipt = '$month_receipt',
                                branch_id = '" . $_SESSION[ 'login_branchid' ] . "', 
                                branch_name = '" . $_SESSION[ 'login_branch' ] . "'";
                $query = mysql_query( $sql );
            }

            // --------------------------------------
            //      Save short payment for tepi1
            // --------------------------------------
            if($tepi1 > 0.00){

                $sql2 = "INSERT INTO $db.short_record SET
                                customer_id = '$customer_id',
                                collection_id = '$collection_id', 
                                short_date = '$payment_date', 
                                amount = '$tepi1', 
                                package_id = '$package_id', 
                                loan_code = '$loan_code', 
                                status = 'PAID',
                                user_id = '" . $_SESSION[ 'login_name' ] . "', 
                                balance = '$tepi1', 
                                short_type = 'MONTHLY',
                                month_receipt = '$tepi1_month',
                                branch_id = '" . $_SESSION[ 'login_branchid' ] . "', 
                                branch_name = '" . $_SESSION[ 'login_branch' ] . "'";
                $query = mysql_query( $sql2 );

            }

            // --------------------------------------
            //          Show success message
            // --------------------------------------
            if ( $query ) {
                $msg = 'Payment has been successfully saved into record.<br>';
                $_SESSION[ 'msg' ] = "<div class='success'>".$msg.'</div>';
            }

        }

        // ======================================
        //      Record Half Month Payments
        // ======================================
        if ($instalment_type == 'Half Month') {

            // --------------------------------------
            //  Get original instalment based on loan amount and period
            // --------------------------------------
            if (!empty($period)) {
                $sql = "SELECT {$period}months FROM $db.new_package WHERE loan_amount = '$loan_amount_ori'";
                $q = mysql_query($sql);
                $res = mysql_fetch_assoc($q);
                $ori_instalment = $res[$period . 'months'];
            }

            // --------------------------------------
            //      Prepare to calculate balance
            // --------------------------------------
            $balance = 0;
            $is_first_payment = true;

            // Check for previous balance (previous payment)
            $sql = "SELECT balance FROM $db.half_payment_details 
                    WHERE loan_code = '$loan_code' 
                    AND month_receipt = '$month_receipt'
                    ORDER BY id DESC LIMIT 1";  // In case multiple entries, get the latest

            $query = mysql_query($sql);
            if ($row = mysql_fetch_assoc($query)) {
                $balance = (float)$row['balance'];
                $is_first_payment = false;
            }

            // --------------------------------------
            //         Calculate new balance
            // --------------------------------------
            $ori_instalment = (float)$ori_instalment;
            $instalment = (float)$instalment;

            if ($is_first_payment) {
                $balance_half = $ori_instalment - $instalment;
            } else {
                $balance_half = $balance - $instalment;
            }

            // Optional: prevent negative balance
            if ($balance_half < 0) $balance_half = 0;

            // --------------------------------------
            //          Save half month payment
            // --------------------------------------            
            if ($instalment > 0.00) {
                $sql = "INSERT INTO $db.half_payment_details SET
                            customer_id = '$customer_id',
                            collection_id = '$collection_id', 
                            payment_date = '$payment_date', 
                            ori_instalment = '$ori_instalment', 
                            payment = '$instalment',
                            package_id = '$package_id', 
                            loan_code = '$loan_code', 
                            loan_status = 'PAID', 
                            created_date = '$payment_date', 
                            balance = '$balance_half', 
                            month_receipt = '$month_receipt',
                            branch_id = '" . $_SESSION['login_branchid'] . "', 
                            branch_name = '" . $_SESSION['login_branch'] . "'";
                
                $query = mysql_query($sql);
            }

            // --------------------------------------
            //          Show success message
            // --------------------------------------
            if ($query) {
                $msg = 'Payment has been successfully saved into record.<br>';
                $_SESSION['msg'] = "<div class='success'>" . $msg . '</div>';
            }
        }

        // ======================================
        //          Return JSON Response
        // ======================================
        header( 'Content-Type: application/json' );
        echo json_encode( $response );

        
    } 
    
    // ==========================================
    //     Condition 2: If action is delete
    // ==========================================
    else if ( $action == 'delete' ) {

        // ======================================
        //          Get Collection Info
        // ======================================
        $sql = "SELECT * FROM $db.collection WHERE id = '$collection_id'";
        $query = mysql_query( $sql );
        $res = mysql_fetch_assoc( $query );
        $datetime = $res[ 'datetime' ];
        $filename = $res[ 'filename' ];
        $salary_type = $res[ 'salary_type' ];
        $loan_code = $res[ 'loan_code' ];
        $tepi2_loan_code = $res['tepi2_loan_code'];
        $instalment_type = $res['instalment_type'];
        $instalment_month = $res[ 'instalment_month' ];
        $tepi1_month = $res[ 'tepi1_month' ];
        $tepi2_month = $res[ 'tepi2_month' ];

        // ======================================
        //          Get Payment Details
        // ======================================
        $sql = "SELECT * FROM $db.loan_payment_details WHERE receipt_no = '$loan_code' AND month_receipt = '$instalment_month'";
        $q = mysql_query( $sql );
        if ( $q ) {
            $loan_payment_details = mysql_fetch_assoc( $q );
            $loan_payment_details_id = $loan_payment_details[ 'id' ];
            $loan_id = $loan_payment_details[ 'customer_loanid' ];
            $package_id = $loan_payment_details[ 'package_id' ];
            $loan_payment = $loan_payment_details[ 'payment' ];
            $loan_payment_date = $loan_payment_details[ 'payment_date' ];

            // --------------------------------------
            //      Mark Cashbook as Deleted
            // --------------------------------------
            $sql = "UPDATE $db.cashbook SET
                        display_status = 'DELETED'
                    WHERE package_id = '$package_id' 
                        AND type = 'RECEIVED' 
                        AND table_id = '$loan_payment_details_id' 
                        AND code = '$loan_code'
                        AND amount = '$loan_payment' 
                        AND date LIKE '%$loan_payment_date%'
                    ";
            $q = mysql_query( $sql );

            if ( $q ) {
                // --------------------------------------
                //       Delete Balance Transaction
                // --------------------------------------
                $sql = "DELETE FROM $db.balance_transaction WHERE customer_loanid = '$loan_id' AND package_id = '$package_id' AND received = '$loan_payment' AND date LIKE '%$loan_payment_date%' ORDER BY id DESC LIMIT 1";
                $q = mysql_query( $sql );

                if ( $q ) {
                    // --------------------------------------
                    //     Mark Balance Record as Deleted
                    // --------------------------------------
                    $sql = "SELECT id FROM $db.balance_rec WHERE customer_loanid = '$loan_id' AND package_id = '$package_id' AND received = '$loan_payment' AND bal_date LIKE '%$loan_payment_date%' ORDER BY id DESC LIMIT 1";
                    $q = mysql_query( $sql );
                    $res = mysql_fetch_assoc( $q );
                    $balance_rec_id = $res[ 'id' ];

                    $sql = "UPDATE $db.balance_rec SET
                                    display_status = 'DELETED'
                                WHERE id = '$balance_rec_id'";
                    $q = mysql_query( $sql );

                    if ( $q ) {
                        // --------------------------------------
                        //       Clear Loan Payment Details
                        // --------------------------------------
                        $sql = "UPDATE $db.loan_payment_details SET month_receipt = '', payment = '', payment_date = '',loan_status = '' WHERE id = '$loan_payment_details_id'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //      Delete Future Loan Payments
                        // --------------------------------------
                        $sql = "DELETE FROM $db.loan_payment_details WHERE id > '$loan_payment_details_id' AND customer_loanid = '$loan_id'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //            Reset Loan Status
                        // --------------------------------------
                        $sql = "UPDATE $db.customer_loanpackage SET loan_status = 'Paid' WHERE loan_code = '$loan_code'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //  Reset Discount Value if delete Settlement
                        // --------------------------------------
                        if($salary_type == 'settlement') {
                            $sql = "UPDATE $db.customer_loanpackage SET discount = 0 WHERE loan_code = '$loan_code'";
                            mysql_query( $sql );
                        }
                        //half month delete
                        if($instalment_type == 'Half Month') {

                            // $get_half_month_payment = "SELECT * FROM $db.half_payment_details WHERE loan_code = '$loan_code' AND collection_id = '$collection_id'";
                            // $query_half = mysql_query( $get_half_month_payment );
                            // if ( $query_half ) {
                            //     $result_half = mysql_fetch_assoc( $query_half );
                            //     $balance = $result_half['balance'];
                            // }
                            $sql = "DELETE FROM $db.half_payment_details WHERE loan_code = '$loan_code' AND collection_id = '$collection_id'";
                            mysql_query( $sql );
                        }
                        // --------------------------------------
                        //      Delete Late Interest Record
                        // --------------------------------------
                        $sql = "DELETE FROM $db.late_interest_record WHERE loan_code = '$loan_code'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //         Delete Bad Debt Record
                        // --------------------------------------
                        $sql = "DELETE FROM $db.baddebt_record WHERE loan_code = '$loan_code'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //           Delete Short Record
                        // --------------------------------------
                        $sql = "DELETE FROM $db.short_record WHERE collection_id = '$collection_id'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //     Delete Monthly Payment Details
                        // --------------------------------------
                        $sql = "DELETE FROM $db.monthly_payment_details WHERE collection_id = '$collection_id'";
                        mysql_query( $sql );

                        // --------------------------------------
                        //          Delete tepi1 payment
                        // --------------------------------------
                        $sql = "SELECT id, payout_amount FROM $db.monthly_payment_record WHERE loan_code = '$loan_code' AND month = '$tepi1_month' AND status !='DELETED'";
                        $q = mysql_query($sql);

                        if ($q) {
                            while ($res = mysql_fetch_assoc($q)) {
                                $monthly_payment_record_id = $res['id'];
                                $balance = $res['payout_amount'];

                                $sql = "UPDATE $db.monthly_payment_record SET
                                            status = 'PAID',
                                            payment_date = NULL,
                                            balance = $balance
                                        WHERE id = '$monthly_payment_record_id'";
                                mysql_query($sql);
                            }
                        }

                        // --------------------------------------
                        //          Delete tepi2 payment
                        // --------------------------------------
                        $sql = "SELECT id FROM $db.monthly_payment_record 
                                WHERE loan_code = '$tepi2_loan_code' AND month = '$tepi2_month' AND status !='DELETED'";
                        $q = mysql_query($sql);

                        $sql = "SELECT id FROM $db.monthly_payment_record 
                                WHERE loan_code = '$loan_code' AND month = '$tepi2_month' AND status !='DELETED'";
                        $q2 = mysql_query($sql);

                        if ($q || $q2) {
                            while ($res = mysql_fetch_assoc($q)) {
                                $monthly_payment_record_id = $res['id'];

                                // Delete related monthly payment details
                                $sql = "DELETE FROM $db.monthly_payment_details WHERE mprid = '$monthly_payment_record_id'";
                                mysql_query($sql);

                                // Update monthly payment record to mark as deleted
                                $sql = "UPDATE $db.monthly_payment_record SET
                                            status = 'DELETED',
                                            payment_date = NULL,
                                            balance = 0,
                                            payout_amount = 0
                                        WHERE id = '$monthly_payment_record_id'";
                                mysql_query($sql);
                            }
                        }
                    }
                }
            }
        }

        // ======================================
        //             Final Cleanups
        // ======================================
        $sql = "DELETE FROM $db.return_book_monthly WHERE collection_id = '$collection_id'";
        mysql_query( $sql );

        $sql = "DELETE FROM $db.return_book_instalment WHERE collection_id = '$collection_id'";
        mysql_query( $sql );

        // ======================================
        //    Delete Collection Record & Photo
        // ======================================
        $sql = "SELECT filename, datetime, tepi1_month, tepi2_month, loan_code FROM $db.collection WHERE id = '$collection_id'";
        $q = mysql_query( $sql );
        $res = mysql_fetch_assoc( $q );
        $collection_filename = $res[ 'filename' ];
        $collection_datetime = $res[ 'datetime' ];
        $tepi1_month = $res[ 'tepi1_month' ];
        $tepi2_month = $res[ 'tepi2_month' ];
        $loan_code = $res[ 'loan_code' ];

        $sql = "DELETE FROM $db.collection WHERE id = '$collection_id'";
        $query = mysql_query( $sql );
        if ( $query ) {
            // Delete collection photo
            $dir_path = "../../collection_photo/$db/$loan_code/" . str_replace( array( '-', ' ', ':' ), '', $collection_datetime );
            $file_path = $dir_path . "/$collection_filename";
            if ( unlink( $file_path ) ) {
                rmdir( $dir_path );
            }

            $msg = 'Collection deleted succesfully.';
        } else {
            $msg = 'Fail to delete collection';
        }
        $_SESSION[ 'msg' ] = "<div class='success'>".$msg.'</div>';
        echo $msg;
    }

    exit;
}
?>