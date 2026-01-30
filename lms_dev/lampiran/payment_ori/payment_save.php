<?php
session_start();

include("../../include/dbconnection2.php");

if(isset($_POST['save']))
{
	$branch_id = $_POST['branch_id'];
	$customer_id = $_POST['customercode'];
	$customer_name = $_POST['name'];
	$nric = $_POST['nric'];
	$phone_no =  $_POST['contact'];
	$notes = $_POST['aremarks'];
	$loan_package = $_POST['loan_package'];
	$loan_code = $_POST['loan_code'];
	$loan_amount = $_POST['loan_amount'];
	$intrate = $_POST['intrate'];
	$period = $_POST['period'];
	$int_total = $_POST['int_total'];
	$loan_total = $_POST['loan_total'];
	$monthly_payment = $_POST['monthly_payment'];
	$bankacc = $_POST['bankacc'];
	$paydate = $_POST['payday'];
	$paymentdate = $_POST['a_paymentdate'];
	$salary = $_POST['salary'];
	$loanremarks = $_POST['loanremarks'];
	$balance = $_POST['loan_total'];
	
	//check
		$checkic2 = mysql_query("SELECT * FROM customer_details2 WHERE nric = '".$nric."'", $db2);
	$cic2 = mysql_num_rows($checkic2);
	
	if($cic2 == 0)
	{
		//insert customer into database 
		$insert  = mysql_query("INSERT INTO customer_details2 SET customercode2 = '".$customer_id."',
												  branch_id = '".$branch_id."',
												  name = '".$customer_name."', 
												  nric = '".$nric."'",$db2) or die(mysql_error());
	}

	$checkic = mysql_query("SELECT * FROM customer_details WHERE nric = '".$nric."'", $db2);
	$cic = mysql_num_rows($checkic);
	
	if($cic == 0)
	{
		//insert customer into database 
		$insert  = mysql_query("INSERT INTO customer_details SET cust_id = '".$customer_id."',
												  branch_id = '".$branch_id."',
												  name = '".$customer_name."', 
												  nric = '".$nric."', 
												  phone_no = '".$phone_no."', 
												  notes= '".$notes."', 
												  bankacc= '".$bankacc."', 
												  remark= '".$loanremarks."', 
												  salary = '".$salary."'
												 ",$db2) or die(mysql_error());

	}
	
		$checkl = mysql_query("SELECT * FROM loan_payment_details WHERE nric = '".$nric."'", $db2);
	$cl = mysql_num_rows($checkl);
	
	if($cl == 0)
	{
	
	$checkid = mysql_query("SELECT * FROM customer_details WHERE nric = '".$nric."'", $db2);
	$cid = mysql_fetch_assoc($checkid);
	$cust_id = $cid['id'];
		$insert1  = mysql_query("INSERT INTO loan_payment_details SET cust_id = '".$cust_id."',
												  nric = '".$nric."', 
												  balance = '".$balance."', 
												  int_percent = '1.5', 
												  month = '1',
												  loan_period = '".$period."', 
												  loan_package = '".$loan_package."',
												  customer_loanid = '".$loan_code."',
												  loan_amount = '".$loan_amount."',
												  int_total = '".$int_total."',
												  loan_total = '".$loan_total."',
												  monthly_payment = '".$monthly_payment."',
												  branch_id = '".$branch_id."',
												  monthly_amount = '".$bayaran_sebulan."'",$db2) or die(mysql_error());
												
			$insert2  = mysql_query("INSERT INTO customer_loan SET cust_id = '".$i."',
												  nric = '".$nric."', 
											   	  date_borrow = '".$date_borrow."', 
												  loan_amount = '".$loan_amount."', 
												  int_total = '".$int_total."', 
												  loan_total = '".$loan_total."', 
												  int_percent = '1.5', 
												
												  loan_period = '".$period."', 
												  month = '1',
												  monthly_payment = '".$monthly_payment."'",$db2) or die(mysql_error());
	}			
		if($insert || $insert1 || $insert2)
		{
			$_SESSION['msg'] = "<div class='success'>New data have been added into database!</div>";
			echo "<script>window.location='../payment/view_details.php?ic=".$nric."&branch_id=".$branch_id."'</script>";
		}
	

		else	

	if(isset($_POST['save']))
{
	$branch_id = $_POST['branch_id'];
	$customer_id = $_POST['customercode'];
	$customer_name = $_POST['name'];
	$nric = $_POST['nric'];
	$phone_no =  $_POST['contact'];
	$notes = $_POST['aremarks'];
	$loan_package = $_POST['loan_package'];
	$loan_code = $_POST['loan_code'];
	$loan_amount = $_POST['loan_amount'];
	$intrate = $_POST['intrate'];
	$period = $_POST['period'];
	$loan_total = $_POST['loan_total'];
	$monthly_payment = $_POST['monthly_payment'];
	$bankacc = $_POST['bankacc'];
	$paydate = $_POST['payday'];
	$paymentdate = $_POST['a_paymentdate'];
	$salary = $_POST['salary'];
	$loanremarks = $_POST['loanremarks'];
	$balance = $_POST['balance'];
	//check
	
		//insert customer into database 
		$update_q  = mysql_query("UPDATE customer_details SET cust_id = '".$customer_id."',
												  branch_id = '".$branch_id."',
												  name = '".$customer_name."', 
												  phone_no = '".$phone_no."', 
												  notes= '".$notes."', 
												  bankacc= '".$bankacc."', 
												  remark= '".$loanremarks."', 
												  branch_id = '".$branch_id."',
												  salary = '".$salary."' WHERE nric = '".$nric."' ", $db2) or die(mysql_error());

									  
		
		if ($update_q)
		{
			$_SESSION['msg'] = "<div class='success'>Customer has been successfully updated!</div>";
			echo "<script>window.location='../payment/view_details.php?ic=".$nric."&branch_id=".$branch_id."'</script>";
		}
	
	}

	
	//echo "<script>window.location='../customers'</script>";
}
?>