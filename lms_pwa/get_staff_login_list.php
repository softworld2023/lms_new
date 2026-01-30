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
        }

        if ($db != '') {
            mysql_select_db($db, $con);

            // Get records for the past 2 minutes
            $sql = "SELECT *
                    FROM $db.staff_web_login
                    WHERE TIMESTAMPDIFF(MINUTE, datetime, NOW()) <= 2 AND status = 'REQUEST_SENT'";
            $query = mysql_query($sql, $con);
        
            $data = array();
        
            if (mysql_num_rows($query) > 0) {
                while ($row = mysql_fetch_assoc($query)) {
                    $user_id = $row['user_id'];
                    $ip_address = $row['ip_address'];
            
                    $sql = "SELECT fullname FROM $db.user WHERE id = '$user_id'";
                    $q = mysql_query($sql, $con);
                    $res = mysql_fetch_assoc($q);
                    $name = $res['fullname'];
            
                    $data[] = array(
                        'name' => $name,
                        'ipAddress' => $ip_address
                    );
                }
            }
        
            // for testing
            // $data[] = array(
            //     'name' => 'Test 1 Test 1 Test 1 Test 1',
            //     'ipAddress' => '127.0.0.1'
            // );
        
            // $data[] = array(
            //     'name' => 'Test 2',
            //     'ipAddress' => '127.0.0.1'
            // );
        
            echo json_encode($data);

            mysql_close($con);
        }
    }
?>