<?php
    include_once '../include/dbconnection.php';
    session_start();
    
    if (isset($_POST)) {
        $login_branch = $_SESSION['login_branch'];

        $response = array();
        $name_arr = array();
        $ic_arr = array();
        $db_name_arr = array();

        if ($login_branch == 'KTL' || $login_branch == 'TSY' || $login_branch == 'TSY2') {
			$db_name_arr = array('majusama', 'majusama2','anseng', 'yuwang', 'ktl', 'tsy', 'DK', 'tsy2');
		} else {
			$db_name_arr = array('majusama', 'majusama2','anseng', 'yuwang', 'dk');
		}

        foreach ($db_name_arr as $db_name) {
            $sql = "SELECT * FROM $db_name.customer_details ORDER BY name";
            $query = mysql_query($sql);
            
            while ($row = mysql_fetch_assoc($query)) {
                $name = $row['name'];
                $ic = $row['nric'];

                $name_arr[] = $name;
                $ic_arr[] = $ic;
            }
        }

        $sql = "SELECT * FROM loansystem.reject_loan WHERE status = 'ACTIVE'";
        
        if ($login_branch == "ANSENG" || $login_branch == "MAJUSAMA" || $login_branch == "MAJUSAMA2" || $login_branch == "YUWANG" || $login_branch == "DK") {
            $sql .= " AND (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA' OR branch_name = 'MAJUSAMA2' OR branch_name = 'YUWANG' OR branch_name = 'DK')";
        }

        $query = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($query)) {
            $name = $row['customer_name'];
            $ic = $row['customer_ic'];

            $name_arr[] = $name;
            $ic_arr[] = $ic;
        }

        $response['name'] = $name_arr;
        $response['ic'] = $ic_arr;

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
?>