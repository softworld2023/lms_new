<?php
    include_once 'include/dbconnection.php';

    if (isset($_POST)) {
        $branch = strtoupper($_POST['branch']);
        $ip_address = $_POST['ipAddress'];
        $status = $_POST['status'];

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

            $sql = "UPDATE $db.staff_web_login SET status = '$status' WHERE ip_address = '$ip_address'";
            $query = mysql_query($sql, $con);
            if ($query) {
                if ($status == 'APPROVED') {
                    $sql = "SELECT user_id FROM $db.staff_web_login WHERE ip_address = '$ip_address'";
                    $query = mysql_query($sql, $con);
                    $result = mysql_fetch_assoc($query);
                    $user_id = $result['user_id'];
                    
                    $datetime = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO $db.web_login_history SET user_id = '$user_id', login_datetime = '$datetime'";
                    mysql_query($sql, $con);
                }
                echo 'success';
            } else {
                echo 'fail';
            }

            mysql_close($con);
        }
    }
?>