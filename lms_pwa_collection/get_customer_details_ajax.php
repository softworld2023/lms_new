<?php
    include_once 'include/dbconnection.php';

    if (isset($_POST)) {
        $branch = strtoupper($_POST['branch']);
        $loan_code = isset($_POST['loan_code']) ? mysql_real_escape_string($_POST['loan_code']) : '';
        $customer_code = isset($_POST['customer_code']) ? mysql_real_escape_string($_POST['customer_code']) : '';

        $db = '';
        switch ($branch) {
            case 'MJ':
                $db = 'majusama' . $db_suffix;
                break;
            case 'MJ2':
                $db = 'majusama2' . $db_suffix;
                break;
            case 'ANSENG':
                $db = 'anseng' . $db_suffix;
                break;
            case 'YUWANG':
                $db = 'yuwang' . $db_suffix;
                break;
            case 'KTL':
                $db = 'ktl' . $db_suffix;
                break;
            case 'TSY':
                $db = 'tsy' . $db_suffix;
                break;
            case 'TSY2':
                $db = 'tsy2' . $db_suffix;
                break;
            case 'DK':
                $db = 'dk' . $db_suffix;
                break;
            case 'DEMO':
                $db = 'lms_demo';
                break;
        }

        $response = array();

        if ($db != '' && ($loan_code != '' || $customer_code != '')) {
            $sql = "SELECT
                        t1.*,
                        t2.loan_code,
                        t2.loan_amount,
                        t2.loan_period,
                        t2.loan_total,
                        t3.company       
                    FROM $db.customer_details t1
                    LEFT JOIN $db.customer_loanpackage t2 ON t2.customer_id = t1.id OR t2.customer_id = t1.customercode2
                    LEFT JOIN $db.customer_employment t3 ON t3.customer_id = t1.id
                    WHERE 1=1";
            
    
            if ($loan_code != '') {
                $sql .= " AND t2.loan_code LIKE '%$loan_code'";
            }
    
            if ($customer_code != '') {
                $sql .= " AND t1.customercode2 = '$customer_code'";
            }

            $sql .= " ORDER BY t2.approval_date DESC LIMIT 1";
            // var_dump($sql); 
            $query = mysql_query($sql);
            if (mysql_num_rows($query) == 1) {
                $result = mysql_fetch_assoc($query);
                $customer_id = $result['id'];
                $loan_code = $result['loan_code'];
                $customer_code = $result['customercode2'];
                $customer_name = $result['name'];
                $customer_ic = $result['nric'];
                $company = $result['company'];
                $loan_amount = $result['loan_amount'];
                $loan_period = $result['loan_period'];
                $loan_total = $result['loan_total'];
                $loan_monthly = 0;

                if (!empty($loan_period)) {
                    $sql = "SELECT $loan_period" . "months FROM $db.new_package WHERE loan_amount = '$loan_amount'";
                    $q = mysql_query($sql);
                    $res = mysql_fetch_assoc($q);
                    $loan_monthly = $res[$loan_period . 'months'];
                }
    
                $response = array(
                    'customer_id' => $customer_id,
                    'loan_code' => $loan_code,
                    'customer_code' => $customer_code,
                    'customer_name' => $customer_name,
                    'customer_ic' => $customer_ic,
                    'company' => $company,
                    'loan_amount' => $loan_amount,
                    'loan_monthly' => $loan_monthly,
                    'loan_period' => $loan_period,
                    'loan_total' => $loan_total
                );
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
?>