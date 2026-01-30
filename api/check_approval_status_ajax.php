<?php
    include_once 'db_config.php';

    if (isset($_POST)) {
        $username = mysql_real_escape_string($_POST['username'], $con);
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

            $sql = "SELECT id FROM user WHERE username = '$username'";
            $query = mysql_query($sql, $con);
            $result = mysql_fetch_assoc($query);
            $user_id = $result['id'];
        
            $sql = "SELECT status FROM staff_web_login WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
            $query = mysql_query($sql, $con);
    
            $result = mysql_fetch_assoc($query);
            $status = isset($result['status']) ? $result['status'] : '';
            echo $status;

            mysql_close($con);
        }
    }
?>