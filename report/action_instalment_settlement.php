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
	$month_receipt = $_POST['month_receipt'];
	$date = DateTime::createFromFormat('Y-m', $month_receipt);
	$date->modify('+1 month');
	$next_month = $date->format('Y-m'); 
	$period = $loan_period - $settlement_period + 1 ;

	// if($settlement_period!='0')
	// {

	//$yes_q = mysql_query("UPDATE customer_loanpackage SET settlement = 'yes'  WHERE id = '".$custid."'");	
	// }else{
	// 	$yes_q = mysql_query("UPDATE customer_loanpackage SET settlement = ' '  WHERE id = '".$custid."'");	
	// }
	
	$sql_q = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished'  WHERE id = '".$custid."'");
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$custid."' and payment ='0' ORDER BY id DESC LIMIT 1");

	$result = mysql_fetch_assoc($sql);
	$id = $result['id']; 
	$receipt_no = $result['receipt_no'];
	
	$update_q = mysql_query("UPDATE loan_payment_details SET month_receipt = '".$month_receipt."',payment = '".$payment."',loan_status = 'SETTLE',payment_date ='".$settle_date."'  WHERE id = '".$id."'");
	if($update_q)
	{
		$insert_q = mysql_query(" INSERT INTO collection (
					loan_code, 
					salary, 
					salary_type, 
					instalment, 
					instalment_type, 
					instalment_month, 
					tepi1, 
					tepi1_month, 
					tepi2, 
					tepi2_month, 
					tepi2_bunga, 
					balance_received, 
					datetime, 
					submitted_by_id, 
					approved_by_id, 
					status
				) VALUES (
					'".$receipt_no."', 
					0, 
					'settlement', 
					'".$payment."', 
					'Instalment', 
					'".$month_receipt."', 
					0, 
					'".$month_receipt."', 
					0, 
					'".$next_month."', 
					0, 
					0, 
					'".date('Y-m-d H:i:s')."', 
					'".$_SESSION['taplogin_id']."', 
					'".$_SESSION['taplogin_id']."', 
					'APPROVED'
				)
			");

		if($insert_q) {
		$_SESSION['msg'] = "<div class='success'> ".$receipt_no." has been successfully settle</div>";	
		echo "<script>window.parent.location='payloan_a.php?id=".$custid."'</script>";
	}
}
}
}
?>