<?php
    include_once '../include/dbconnection.php';
    session_start();
    
    if (isset($_POST)) {
        $login_branch = $_SESSION['login_branch'];

        $response = array();
        $name_arr = array();
        $ic_arr = array();

        if ($login_branch == 'KTL' || $login_branch == 'TSY') {
			$db_name_arr = array('majusama', 'anseng', 'yuwang', 'lskl_mca', 'tsy');
		} else {
			$db_name_arr = array('majusama', 'anseng', 'yuwang');
		}
    
        $sql = "SELECT * FROM customer_details ORDER BY name";
        $query = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($query)) {
            $name = $row['name'];
            $ic = $row['nric'];

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