<?php
session_start();
require_once("include/dbconnection.php");
require 'include/plugin/PasswordHash.php';

if(isset($_POST['login']))
{
    $username = mysql_real_escape_string($_POST['username']);
    $pass = $_POST['password'];
    $selectedBranch = strtoupper($_POST['branch']);

    // Determine which database to connect
    switch ($selectedBranch) {
        case 'MJ': $conn_database = 'majusama'; break;
        case 'MJ2': $conn_database = 'majusama2'; break;
        case 'ANSENG': $conn_database = 'anseng'; break;
        case 'YUWANG': $conn_database = 'yuwang'; break;
        case 'KTL': $conn_database = 'ktl'; break;
        case 'TSY': $conn_database = 'tsy'; break;
        case 'TSY2': $conn_database = 'tsy2'; break;
        case 'DK': $conn_database = 'dk'; break;
        default: $_SESSION['error'] = "Invalid branch selected."; 
                 header("Location: index.php"); exit;
    }

    mysql_select_db($conn_database, $conn_connection);

    $t_hasher = new PasswordHash(8, FALSE);

    $result = mysql_query("SELECT * FROM user WHERE username = '$username'");
    
    if(mysql_num_rows($result))
    {
        $user = mysql_fetch_assoc($result);
        $check = $t_hasher->CheckPassword($pass, $user['pswd']);

        if($check == '1')
        {
            $_SESSION['username']       = $username;
            $_SESSION['login_username'] = $user['username'];
            $_SESSION['login_level']    = $user['level'];
            $_SESSION['login_name']     = $user['fullname'];
            $_SESSION['taplogin_id']    = $user['id'];
            $_SESSION['login_branchid'] = $user['branch_id'];
            $_SESSION['login_branch']   = $user['branch_name'];
            $_SESSION['login_database'] = $conn_database;
			$_SESSION['superadmin'] = 0;

            // Super admin can switch branches
            if($user['superadmin'] === '1') {
                // Allow switching branch without logout
                $_SESSION['login_branch'] = $selectedBranch;
                $_SESSION['login_database'] = $conn_database;
				$_SESSION['superadmin'] = 1;
                // Redirect to a branch selection page or dashboard
                header("Location: branch/"); 
                exit;
            }

            // Normal user rights logic
            if($user['level'] != 'Boss')
            {
                $userright_q = mysql_query("SELECT * FROM accessright WHERE staffid = '".$_SESSION['taplogin_id']."'");
                $get_userright = mysql_fetch_assoc($userright_q);

                do {
                    if($get_userright['home_page'] == 'on') { header("Location: branch/"); exit; }
                    if($get_userright['products_page'] == 'on') { header("Location: products/product_list.php"); exit; }
                    if($get_userright['advertisement_page'] == 'on') { header("Location: advertisement/advertisement.php"); exit; }
                    if($get_userright['order_page'] == 'on') { header("Location: order/order.php"); exit; }
                    if($get_userright['promotion_page'] == 'on') { header("Location: promotion/promotion.php"); exit; }
                    if($get_userright['redemption_page'] == 'on') { header("Location: redemption/redemption.php"); exit; }
                    if($get_userright['reports_page'] == 'on') { header("Location: reports/reports.php"); exit; }
                    if($get_userright['newsletter_page'] == 'on') { header("Location: newsletter/newsletter.php"); exit; }
                    if($get_userright['clearance_page'] == 'on') { header("Location: clearance/clearance.php"); exit; }
                    if($get_userright['catalog_page'] == 'on') { header("Location: catalog/welcome.php"); exit; }
                    if($get_userright['setting_page'] == 'on') { header("Location: setting/setting.php"); exit; }
                    if($get_userright['expenses_age'] == 'on') { header("Location: expenses/expenses.php"); exit; }
                    if($get_userright['authorization_page'] == 'on') { header("Location: authorization/authorization.php"); exit; }
                } while($get_userright = mysql_fetch_assoc($userright_q));
            }
            else {
                header("Location: branch/");
                exit;
            }
        }
        else {
            $_SESSION['error'] = "Invalid login. Check with your Administrator!";
            header("Location: index.php"); exit;
        }
    }
    else {
        $_SESSION['error'] = "Invalid login. Check with your Administrator!";
        header("Location: index.php"); exit;
    }
}

// Logout
if(isset($_POST['logout']))
{
    session_start();
    session_destroy();
    header("Location: branch/");
    exit;
}   
?>
