<?php
    include_once 'db_config.php';
    include_once '../include/plugin/PasswordHash.php';

    date_default_timezone_set('Asia/Kuching');

    if (isset($_POST)) {
        $username = mysql_real_escape_string($_POST['username'], $con);
        $password = mysql_real_escape_string($_POST['password'], $con);
        $branch = strtoupper($_POST['branch']);
        $ip_address = isset($_POST['ip_address']) ? $_POST['ip_address'] : '';

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

            $t_hasher = new PasswordHash(8, FALSE);
        
            $sql = "SELECT * FROM $db.user WHERE username = '$username'";
            $query = mysql_query($sql, $con);
            $user = mysql_fetch_assoc($query);
            $status = $t_hasher->CheckPassword($password, $user['pswd']);
            $user_id = $user['id'];
            $name = $user['fullname'];
            $level = $user['level'];
    
            if ($status == '1') {
                $datetime = date('Y-m-d H:i:s');
    
                if ($level == 'Staff') {
                    $sql = "INSERT INTO $db.staff_web_login SET datetime = '$datetime', user_id = '$user_id', ip_address = '$ip_address'";
                    $query = mysql_query($sql, $con);

                    $subscriptions = '';

                    if ($query) {
                        $sql = "UPDATE $db.staff_web_login SET status = 'REQUEST_SENT' WHERE user_id = '$user_id'";
                        $query = mysql_query($sql, $con);
                        if ($query) {
                            $sql = "SELECT * FROM $db.pwa_login WHERE is_logged_in = 1 AND subscription IS NOT NULL";
                            $query = mysql_query($sql, $con);
            
                            while ($manager = mysql_fetch_assoc($query)) {
                                $sub = $manager['subscription'];
                                $subscriptions .= $sub . '|';
                            }
                        }
                    }

                    // remove the last '|';
                    if ($last_position = strrpos($subscriptions, '|')) {
                        $subscriptions = substr_replace($subscriptions, '', $last_position);
                    }

                    echo "staff-success;$name;$subscriptions";
                } else {
                    echo 'boss-success';

                    // web login not posting device_platform, so it's empty
                    if ($device_platform == '') {
                        $datetime = date('Y-m-d H:i:s');
                        $sql = "INSERT INTO $db.web_login_history SET user_id = '$user_id', login_datetime = '$datetime'";
                        mysql_query($sql, $con);
                    }

                }
            } else {
                echo 'fail';
            }

            mysql_close($con);
        }
    }
?>