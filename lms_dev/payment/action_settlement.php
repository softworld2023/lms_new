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


if(isset($_POST['add_settle']))
{
	$settle_date = date('Y-m-d', strtotime($_POST['settle_date']));
	$custid = $_POST['custid'];
	$payment = $_POST['settlement_amount'];
	$loan_period = $_POST['loan_period'];
	$settlement_period = $_POST['settlement_period'];
	
	$sql = mysql_query("SELECT * FROM loan_lejar_details WHERE customer_loanid = '".$custid."' and payment ='0' ORDER BY id DESC LIMIT 1");
	$result = mysql_fetch_assoc($sql);
	$id = $result['id']; 
	$receipt_no = $result['receipt_no'];

	if($settlement_period=='-'){
		$period = $result['month'];
	}else
	{
	$period = $loan_period - $settlement_period + 1 ;
	}
	// if($settlement_period!='0')
	// {

	$yes_q = mysql_query("UPDATE customer_loanpackage SET settlement = 'yes'  WHERE id = '".$custid."'");	
	// }else{
	// 	$yes_q = mysql_query("UPDATE customer_loanpackage SET settlement = ' '  WHERE id = '".$custid."'");	
	// }
	
	$sql_q = mysql_query("UPDATE customer_loanpackage SET settlement_period = '".$period."'  WHERE id = '".$custid."'");
	

	$getnoresit_q = mysql_query("SELECT MAX(no_resit) AS no_resit FROM lejar_no_resit");
	$getnoresit = mysql_fetch_assoc($getnoresit_q);
	$no_resit = $getnoresit['no_resit']+1;
	
	$update_q = mysql_query("UPDATE loan_lejar_details SET payment = '".$payment."',loan_settle = 'yes',payment_date ='".$settle_date."' , no_resit ='".$no_resit."'  WHERE id = '".$id."'");
	$insert_resit = mysql_query("INSERT INTO lejar_no_resit SET no_resit = '".$no_resit."'");

	if($settlement_period=='-'){
		$_SESSION['msg'] = "<div class='success'> ".$receipt_no." - BAD DEBT</div>";	
		echo "<script>window.parent.location='lejar_a.php?id=".$custid."'</script>";
	}else{
	if($update_q)
	{
		
		$_SESSION['msg'] = "<div class='success'> ".$receipt_no." has been successfully settle</div>";	
		echo "<script>window.parent.location='lejar_a.php?id=".$custid."'</script>";
	}
}
}
}
?>