<?php
    include_once 'include/dbconnection.php';
    // ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
    if (isset($_POST)) {
        $uid = $_POST['uid'];
        $branch = strtoupper($_POST['branch']);
        $date = isset($_POST['date']) ? $_POST['date'] : '';

        $db = '';
        $can_view_all_history = false;

        switch ($branch) {
            case 'MJ':
                $db = 'majusama' . $db_suffix;
                mysql_select_db($db, $con);

                // Check if user id is 10 in branch MJ, and allow access to all branches' login history
                $check_user_id_sql = "SELECT DISTINCT
                                        $db.user.id AS user_id
                                    FROM
                                        $db.pwa_login
                                    JOIN $db.user ON $db.user.username = $db.pwa_login.username
                                    WHERE $db.pwa_login.uid = '$uid'";
                $q = mysql_query($check_user_id_sql, $con);
                $res = mysql_fetch_assoc($q);
                $user_id = $res['user_id'];

                if ($user_id == 10) {
                    $can_view_all_history = TRUE;
                }
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
            case 'MJ2':
                $db = 'majusama2' . $db_suffix;
                break;
        }

        if ($db != '') {
            mysql_select_db($db, $con);

            $data = array();

            if ($can_view_all_history) {
                $arr = array(
                    array('branch' => 'MJ', 'db' => "majusama$db_suffix"), 
                    array('branch' => 'MJ2', 'db' => "majusama2$db_suffix"), 
                    array('branch' => 'ANSENG', 'db' => "anseng$db_suffix"), 
                    array('branch' => 'YUWANG', 'db' => "yuwang$db_suffix"), 
                    array('branch' => 'KTL', 'db' => "ktl$db_suffix"), 
                    array('branch' => 'TSY', 'db' => "tsy$db_suffix"),
                    array('branch' => 'TSY2', 'db' => "tsy2$db_suffix"),
                    array('branch' => 'DK', 'db' => "dk$db_suffix")
                );

                $sql = '';

                foreach ($arr as $item) {
                    $branch = $item['branch'];
                    $db = $item['db'];

                    // Get records
                    $sql .= "(SELECT
                                '$branch' AS branch,
                                $db.user.username,
                                $db.user.fullname,
                                $db.user.level,
                                $db.web_login_history.login_datetime
                            FROM
                                $db.web_login_history
                            JOIN $db.user ON $db.user.id = $db.web_login_history.user_id
                            ";

                    if ($date != '') {
                        $sql .= " WHERE DATE($db.web_login_history.login_datetime) = '$date'";
                    } else {
                        $today = date('Y-m-d');
                        $sql .= " WHERE DATE($db.web_login_history.login_datetime) = '$today'";
                    }
                    $sql .= " AND $db.user.username != 'tester' AND $db.user.username != 'sw'";
                    $sql .= ") UNION ALL ";
                }

                // remove the last ' UNION ALL ';
                if ($last_position = strrpos($sql, ' UNION ALL ')) {
                    $sql = substr_replace($sql, '', $last_position);
                }

                $sql .= " ORDER BY login_datetime DESC";

                $query = mysql_query($sql, $con);

                if (mysql_num_rows($query) > 0) {
                    while ($row = mysql_fetch_assoc($query)) {
                        $name = $row['fullname'];
                        $login_datetime = $row['login_datetime'];
                        $branch = $row['branch'];
                        $level = $row['level'] == 'Staff' ? 'Staff' : 'Main';

                        $data[] = array(
                            'name' => $name . " ($branch - $level)",
                            'login_datetime' => date('H:i:s a', strtotime($login_datetime))
                        );
                    }
                }
            } else {
                // Get records
                $sql = "SELECT
                            user.username,
                            user.fullname,
                            user.level,
                            web_login_history.login_datetime
                        FROM
                            $db.web_login_history
                        JOIN $db.user ON user.id = web_login_history.user_id
                        ";
    
                if ($date != '') {
                    $sql .= " WHERE DATE(web_login_history.login_datetime) = '$date'";
                } else {
                    $today = date('Y-m-d');
                    $sql .= " WHERE DATE(web_login_history.login_datetime) = '$today'";
                }
                $sql .= " AND user.username != 'tester' AND user.username != 'sw'";
                $sql .= " ORDER BY login_datetime DESC";
                // var_dump($sql);
                
                $query = mysql_query($sql, $con);
            
                if (mysql_num_rows($query) > 0) {
                    while ($row = mysql_fetch_assoc($query)) {
                        $name = $row['fullname'];
                        $login_datetime = $row['login_datetime'];
                        $level = $row['level'] == 'Staff' ? 'Staff' : 'Main';
                
                        $data[] = array(
                            'name' => $name . " ($branch - $level)",
                            'login_datetime' => date('H:i:s a', strtotime($login_datetime))
                        );
                    }
                }
            }

            // // for testing
            // $data[] = array(
            //     'name' => 'Test 1 Test 1 Test 1 Test 1',
            //     'login_datetime' => date('h:i:s a')
            // );
        
            // $data[] = array(
            //     'name' => 'Test 2',
            //     'login_datetime' => date('h:i:s a')
            // );
        
            echo json_encode($data);

            mysql_close($con);
        }
    }
?>