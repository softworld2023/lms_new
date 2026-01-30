<?php
session_start();

if ($_SESSION['login_branchid'] == '') {
	session_destroy();
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php
}else
{
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


if(isset($_POST['send_toblacklist']))
{
	$id = $_POST['custid'];
	$blacklisttype = $_POST['blacklisttype'];
	$blacklistamt = $_POST['blacklistamt'];
	
	//delete record in database
	$update_q = mysql_query("UPDATE customer_details SET blacklist = 'Yes', blacklisttype = '".$blacklisttype."', blacklistamt = '".$blacklistamt."', blacklistby = '".$_SESSION['login_name']."', blacklistdate = now() WHERE id = '".$id."'");
	
	if($update_q)
	{
		
		$_SESSION['msg'] = "<div class='success'>Customer has been successfully blacklisted.</div>";	
		echo "<script>window.parent.location='index.php'</script>";
	}
}else
if($_POST['action'] == 'remove_blacklist')
{
	$id = $_POST['id'];
	
	//delete record in database
	$update_q = mysql_query("UPDATE customer_details SET blacklist = '' WHERE id = '".$id."'");
	
	if($update_q)
	{
		
		$_SESSION['msg'] = "<div class='success'>Customer has been successfully removed from blacklist.</div>";	
	}
}
}
?>