<?php
    include_once '../include/dbconnection.php';
    session_start();
    
    if (isset($_POST)) {
        $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
        $customer_ic = isset($_POST['customer_ic']) ? $_POST['customer_ic'] : '';

        $html = '';
        $db_name_arr = array();

        $login_branch = $_SESSION['login_branch'];

        if ($login_branch == 'KTL' || $login_branch == 'TSY' || $login_branch == 'TSY2') {
			$db_name_arr = array('majusama', 'majusama2','anseng', 'yuwang', 'ktl', 'tsy', 'tsy2', 'dk');
		} else {
			$db_name_arr = array('majusama', 'majusama2', 'anseng', 'yuwang', 'dk');
		}

        $count = 0;

        foreach ($db_name_arr as $db_name) {
            $sql = "SELECT
                        *,
                        t1.branch_name AS branch
                    FROM $db_name.late_interest_record AS t1
                    LEFT JOIN $db_name.customer_details AS t2 ON t1.customer_id = t2.id
                    LEFT JOIN $db_name.customer_employment AS t3 ON t1.customer_id = t3.customer_id
                    LEFT JOIN $db_name.customer_address AS t4 ON t1.customer_id = t4.customer_id
                    LEFT JOIN $db_name.monthly_payment_record AS t5 ON t1.loan_code = t5.loan_code
                    WHERE (t5.status != 'DELETED' OR t5.status IS NULL)
                    ";

            if ($customer_name != '') {
                $sql_customer = "SELECT * FROM $db_name.customer_details WHERE name = '$customer_name'";
		        $query_customer = mysql_query($sql_customer);
                $customer = mysql_fetch_assoc($query_customer);
                $customer_id = $customer['id'];
                $sql .= " AND t2.id = '$customer_id'";
            }

            if ($customer_ic != '') {
                $sql_customer = "SELECT * FROM $db_name.customer_details WHERE nric = '$customer_ic'";
		        $query_customer = mysql_query($sql_customer);
                $customer = mysql_fetch_assoc($query_customer);
                $customer_id = $customer['id'];
                $sql .= " AND t2.id = '$customer_id'";
            }

            $sql .= " GROUP BY t1.customer_id ORDER BY t1.id";
            $q = mysql_query($sql);  
    
            while ($bad_debt = mysql_fetch_assoc($q)) {
                $balance = 0;
                $balance_without_instalment = 0;
                $previous_monthly_bd = 0;
                $envelope = 0;
                $envelope_without_instalment = 0;
                $deductible = 0;
                $previous_months_bd_collected = 0;
                                            
                $query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_amount, SUM(balance) AS previous_monthly_bd FROM " . $db_name . ".monthly_payment_record WHERE loan_code = '" . $bad_debt['loan_code'] . "' AND status = 'BAD DEBT'");
                $result_monthly_payment_record = mysql_fetch_assoc($query);
    
                $previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
                $previous_monthly_amount = $result_monthly_payment_record['previous_monthly_amount'];
    
                $amount = $bad_debt['amount'];
    
                if ($bad_debt['bd_from'] == 'Monthly') {
                    $amount = $previous_monthly_amount - $previous_monthly_bd;
                }
    
                $query = mysql_query("SELECT * FROM " . $db_name . ".late_interest_record WHERE loan_code = '" . $bad_debt['loan_code'] . "'");
                $result_late_interest_record = mysql_fetch_assoc($query);
    
                $late_interest_record_id = $result_late_interest_record['id'];
                $bd_from = $result_late_interest_record['bd_from'];
                $branch_name = strtolower($result_late_interest_record['branch_name']);
    
                if ($bad_debt['loan_code'] == 'KT 20032') {
                    $previous_monthly_bd = 680;
                }
    
                // somehow this check is required to identify whether need to deduct previous monthly bd from amount,
                // because some old records have mixed up the amount and monthly bd
                if ($branch_name == 'majusama' && $bd_from == 'Instalment + Monthly' && $late_interest_record_id > 46) {
                    $amount -= $previous_monthly_bd;
                } else if ($branch_name != 'majusama' && ($bd_from == 'Instalment + Monthly' || $bd_from == 'Monthly')) {
                    $amount -= $previous_monthly_bd;
                }
    
                $amount_without_instalment = $amount;
                // var_dump($amount_without_instalment);
    
                $query = mysql_query("SELECT MIN(balance) AS min_balance FROM " . $db_name . ".loan_payment_details WHERE receipt_no = '" . $bad_debt['loan_code'] . "'");
                $result_loan_payment_details = mysql_fetch_assoc($query);
    
                $loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];
    
                $envelope = $loan_payment_details_min_balance - $amount;
                $envelope_without_instalment = $envelope;
    
                if ($bad_debt['loan_code'] == 'KT 20034') {
                    $envelope += 9157;
                }
                // var_dump($envelope);
    
                $query = mysql_query("SELECT SUM(amount) AS collected FROM " . $db_name . ".late_interest_payment_details WHERE lid = '" . $late_interest_record_id . "'");
                while ($row = mysql_fetch_assoc($query)) {
                    $previous_months_bd_collected += round($row['collected'],2);
                }
    
                if ($bad_debt['bd_from'] != 'Monthly') {
                    $amount -= $previous_months_bd_collected;
                }
    
                $balance_without_instalment = $amount_without_instalment + $previous_monthly_bd + $envelope_without_instalment;
                // var_dump($balance_without_instalment);
    
                if ($bad_debt['loan_code'] == 'KT 20032' || $bad_debt['loan_code'] == 'KT 20034') {
                    $balance_without_instalment += 9157;
                }
    
                if ($amount < 0) {
                    $deductible = abs($amount);
                    $amount = 0;
                }
    
                if ($amount == 0) {
                    $difference = $previous_monthly_bd - $deductible;
                    if ($difference <= 0) {
                        $previous_monthly_bd = 0;
                        $deductible = abs($difference);
                    } else {
                        $previous_monthly_bd = $difference;
                    }								
                }
    
                if ($previous_monthly_bd == 0) {
                    $envelope -= $deductible;
                }
    
                if ($envelope < 0) {
                    $envelope = 0;
                } 
    
                if ($branch_name == 'majusama' && $late_interest_record_id <= 46) {
                    $previous_monthly_bd = 0;
                    $envelope = 0;
                }
    
                $balance = $amount + $previous_monthly_bd + $envelope;
    
                $count++;
    
                $html .= '<tr>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">' . $count . '</td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $bad_debt['branch'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $bad_debt['name'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $bad_debt['nric'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $bad_debt['company'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $bad_debt['mobile_contact'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b>RM ' . number_format($balance, 2) . '</b></td>
                        </tr>';
            }
        }

        if ($count == 0) {
            $html .= '<tr>
                        <td colspan="7" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
                    </tr>';
        }

        header('Content-Type: text/html');
        echo $html;
        exit;
    }
?>