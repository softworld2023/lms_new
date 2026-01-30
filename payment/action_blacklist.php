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
	$loan_code = $_POST['loan_code'];
	$blacklisttype = $_POST['blacklisttype'];
	$blacklistamt = $_POST['blacklistamt'];
	$blacklistreason = $_POST['blacklistreason'];
	$b_date = date('Y-m-d', strtotime($_POST['b_date']));
	
	//update record in database
	// $update_q = mysql_query("UPDATE customer_details SET blacklist = 'Yes', blacklisttype = '".$blacklisttype."', blacklistamt = '".$blacklistamt."', blacklistby = '".$_SESSION['login_name']."', blacklistdate = now(), reason = '".$blacklistreason."' WHERE id = '".$id."'");

	$sql_loan = mysql_query("UPDATE customer_loanpackage SET bad_debt = 'yes' WHERE loan_code = '".$loan_code."'");

		$sql = mysql_query("SELECT * FROM loan_lejar_details WHERE receipt_no = '".$loan_code."' and payment ='0' ORDER BY id DESC LIMIT 1");
	$result = mysql_fetch_assoc($sql);
	$id = $result['id']; 
	$customer_loanid = $result['customer_loanid'];

	$getnoresit_q = mysql_query("SELECT MAX(no_resit) AS no_resit FROM lejar_no_resit");
	$getnoresit = mysql_fetch_assoc($getnoresit_q);
	$no_resit = $getnoresit['no_resit']+1;

	$update_q = mysql_query("UPDATE loan_lejar_details SET bad_debt = 'yes',payment_date ='".$b_date."', no_resit ='".$no_resit."'  WHERE id = '".$id."'");

	$insert_resit = mysql_query("INSERT INTO lejar_no_resit SET no_resit = '".$no_resit."'");
	
	if($update_q)
	{
		
		$_SESSION['msg'] = "<div class='success'>Customer has been successfully blacklisted.</div>";	
		echo "<script>window.parent.location='lejar_a.php?id=$customer_loanid'</script>";
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