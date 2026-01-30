<?php
session_start();
require_once("include/dbconnection.php");
require 'include/plugin/PasswordHash.php';

if(isset($_POST['login']))
{
	$username = mysql_real_escape_string($_POST['username']);
	$pass = $_POST['password'];
	
	$t_hasher = new PasswordHash(8, FALSE);
	
	$result = mysql_query("select * from user where username = '".$username."'");
	
	if(mysql_num_rows($result))
	{	
		while($user = mysql_fetch_assoc($result))
		{
			$check = $t_hasher->CheckPassword($pass, $user['pswd']);
			
			//$check = 1;
			if($check == '1')
			{
				$_SESSION['login_username'] = $user['username'];
				$_SESSION['login_level'] = $user['level'];
				$_SESSION['login_name'] = $user['fullname'];
				$_SESSION['taplogin_id'] = $user['id'];
				$_SESSION['login_branchid'] = $user['branch_id'];
				$_SESSION['login_branch'] = $user['branch_name'];
	
				if($_SESSION['login_level'] != 'Boss')
				{
					//check the userright
					$userright_q = mysql_query("select * from accessright where staffid = '".$_SESSION['taplogin_id']."'");
					$get_userright = mysql_fetch_assoc($userright_q);
						
					do
					{
						if($get_userright['home_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='home/'">
						<?php
							break;
						}else	
						if($get_userright['products_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='products/product_list.php'">
						<?php
							break;
						}else	
						if($get_userright['advertisement_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='advertisement/advertisement.php'" />
						<?php
							break;
						}else	
						if($get_userright['order_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='order/order.php'" />
						<?php
							break;
						}else	
						if($get_userright['promotion_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='promotion/promotion.php'" />
						<?php
							break;
						}else
						if($get_userright['redemption_page'] == 'on')
						{
						?>
							<meta http-equiv="refresh" content="0; url='redemption/redemption.php'" />
						 <?php
							break;
						}else
						if($get_userright['reports_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='reports/reports.php'" />
						<?php
							break;
						}else	
						if($get_userright['newsletter_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='newsletter/newsletter.php'" />
						<?php
							break;
						}else	
						if($get_userright['clearance_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='clearance/clearance.php'" />
						<?php
							break;
						}else	
						if($get_userright['catalog_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='catalog/welcome.php'" />
						<?php
							break;
						}else	
						if($get_userright['setting_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='setting/setting.php'" />
						<?php
							break;
						}else	
						if($get_userright['expenses_age'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='expenses/expenses.php'" />
						<?php
							break;
						}else	
						if($get_userright['authorization_page'] == 'on')
						{ 
						?>
							<meta http-equiv="refresh" content="0; url='authorization/authorization.php'" />
						<?php
							break;
						}
					}while($get_userright = mysql_fetch_assoc($userright_q));	
				}
				else
				{
				?>
					<meta http-equiv="refresh" content="0; url='branch/'">
				<?php
				}
			}else
			{
				$_SESSION['error'] = "Invalid Login. Please check your login account with your Administrator!";
				?><meta http-equiv="refresh" content="0; url='index.php'"><?php
			}
		}
	}else
	{	
		$_SESSION['error'] = "Invalid Login. Please check your login account with your Administrator!";
		?><meta http-equiv="refresh" content="0; url='index.php'"><?php
	}
}else
if(isset($_POST['logout']))
{
	echo "<script>window.location='index.php'</script>";
}	
?>