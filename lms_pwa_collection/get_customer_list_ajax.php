<?php
    include_once 'include/dbconnection.php';

    if (isset($_POST)) {
        $branch = strtoupper($_POST['branch']);

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
        $loan_code_arr = array();
        $customer_code_arr = array();

        if ($db != '') {
            $sql = "SELECT
                        t1.customercode2,
                        t2.loan_code
                    FROM
                        $db.customer_details t1
                    LEFT JOIN $db.customer_loanpackage t2 ON t2.customer_id = t1.id  OR t2.customer_id = t1.customercode2
                    WHERE t2.loan_status = 'PAID'
                    ORDER BY t2.loan_code";
            // var_dump($sql);
            $query = mysql_query($sql);

            while ($row = mysql_fetch_assoc($query)) {
                $loan_code = $row['loan_code'];
                $customer_code = $row['customercode2'];
    
                $loan_code_arr[] = $loan_code;
                $customer_code_arr[] = $customer_code;
            }
    
            $response['loan_code'] = $loan_code_arr;
            $response['customer_code'] = $customer_code_arr;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
?>