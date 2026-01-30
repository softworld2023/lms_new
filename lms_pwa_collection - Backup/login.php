<?php
    include_once 'include/dbconnection.php';
    include_once 'include/plugin/PasswordHash.php';

    date_default_timezone_set('Asia/Kuching');

    if (isset($_POST)) {
        $username = mysql_real_escape_string($_POST['username'], $con);
        $password = mysql_real_escape_string($_POST['password'], $con);
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
            // case 'TSY':
            //     $db = 'tsy' . $db_suffix;
            //     break;
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

        if ($db != '') {
            mysql_select_db($db, $con);

            $t_hasher = new PasswordHash(8, FALSE);
        
            $sql = "SELECT * FROM $db.user WHERE username = '$username'";
            $query = mysql_query($sql, $con);
            $user = mysql_fetch_assoc($query);
            $status = $t_hasher->CheckPassword($password, $user['pswd']);
            $user_id = $user['id'];
            $name = $user['fullname'];
            $level = $user['level'];
    
            if ($status == '1') {
                echo 'success';
            } else {
                echo 'fail';
            }

            mysql_close($con);
        }
    }
?>