<?php
    include_once 'db_config.php';

    if (isset($_POST)) {
        $branch = strtoupper($_POST['branch']);
        $uid = $_POST['uid'];

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
                $db = 'lskl_mca' . $db_suffix;
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

            $sql = "UPDATE $db.pwa_login SET is_logged_in = 0 WHERE uid = '$uid'";
            $query = mysql_query($sql, $con);

            mysql_close($con);
        }
    }
?>