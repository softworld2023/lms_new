<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_lateCust']))
{

	$cust_id = $_POST['cust_id'];
	$customer_id = $_POST['customer_id'];
	$package_id = $_POST['loan_package'];
	$loan_code = $_POST['loan_code'];
	$bd_date = date('Y-m-d', strtotime($_POST['bd_date']));
	$month_receipt = $_POST['month_receipt'];

	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$cust_id."' and payment ='0' ORDER BY id DESC LIMIT 1");
	$result = mysql_fetch_assoc($sql);
	$id = $result['id']; 
	// $amount = $result['balance']; 
	$amount = $result['loan_percent'];

	$update_q = mysql_query("UPDATE loan_payment_details SET month_receipt = '".$month_receipt."',loan_status = 'BAD DEBT',payment_date ='".$bd_date."',staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', created_date = now()   WHERE id = '".$id."'");

	$update_q1 = mysql_query("UPDATE customer_loanpackage SET loan_status = 'BAD DEBT' WHERE loan_code = '".$loan_code."'");

	$update_q2 = mysql_query("UPDATE customer_details SET blacklist = 'Yes' WHERE id = '".$customer_id."'");

	$insert_a = mysql_query("INSERT INTO baddebt_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$amount."', 
							package_id = '".$package_id."', 
							loan_code = '".$loan_code."', 
							month_receipt = '".$month_receipt."',
							balance = '".$amount."', 
							bd_from = 'Instalment', 
							user_id = '".$_SESSION['login_name']."', 
							branch_id = '".$_SESSION['login_branchid']."', 
							branch_name = '".$_SESSION['login_branch']."'");
		
	$sql_a = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '".$loan_code."'");
	$result_a = mysql_fetch_assoc($sql_a);
	$row_a = mysql_num_rows($sql_a);

	if($row_a>0)
	{
		if($result_a['bd_from']=='Instalment')
		{
			$amount_1 = $result_a['amount'] + $amount;
			$balance_1 = $result_a['balance'] + $amount;

			$insert_q = mysql_query("UPDATE late_interest_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$balance_1."', 
							month_receipt = '".$month_receipt."',
							balance = '".$amount_1."' WHERE loan_code = '".$loan_code."'");	
		}
		else
		{
			$amount_1 = $result_a['amount'] + $amount;
			$balance_1 = $result_a['balance'] + $amount;

			$insert_q = mysql_query("UPDATE late_interest_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$amount_1."', 
							balance = '".$balance_1."',
							month_receipt = '".$month_receipt."',
							bd_from = 'Instalment + Monthly' WHERE loan_code = '".$loan_code."'");	

		}
	}
	else
	{
		$insert_q = mysql_query("INSERT INTO late_interest_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$amount."', 
							package_id = '".$package_id."', 
							loan_code = '".$loan_code."', 
							balance = '".$amount."', 
							bd_from = 'Instalment', 
							month_receipt = '".$month_receipt."',
							user_id = '".$_SESSION['login_name']."', 
							branch_id = '".$_SESSION['login_branchid']."', 
							branch_name = '".$_SESSION['login_branch']."'");
	}

		$_SESSION['msg'] = "<div class='success'>Bad Debt Record has been successfully saved.</div>";
		echo "<script>window.parent.location='payloan_a.php?id=".$cust_id."'</script>";
	
}else
if(isset($_POST['add_lateCust_monthly']))
{

	$cust_id = $_POST['cust_id'];
	$customer_id = $_POST['customer_id'];
	$package_id = $_POST['loan_package'];
	$loan_code = $_POST['loan_code'];
	$bd_date = date('Y-m-d', strtotime($_POST['bd_date']));

	$sql = mysql_query("UPDATE monthly_payment_record SET status = 'BAD DEBT' WHERE id = '".$cust_id."'");

	$update_q2 = mysql_query("UPDATE customer_details SET blacklist = 'Yes' WHERE id = '".$customer_id."'");


	$sql_1 = mysql_query("SELECT * FROM monthly_payment_record WHERE id = '".$cust_id."'");
	$result = mysql_fetch_assoc($sql_1);

	$amount = $result['balance'];

	$month_receipt = $result['month'];
		
	$insert_q = mysql_query("INSERT INTO baddebt_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$amount."', 
							package_id = '".$package_id."', 
							loan_code = '".$loan_code."', 
							balance = '".$amount."',
							month_receipt = '".$month_receipt."',
							bd_from = 'Monthly',  
							user_id = '".$_SESSION['login_name']."', 
							branch_id = '".$_SESSION['login_branchid']."', 
							branch_name = '".$_SESSION['login_branch']."'");

	$sql_a = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '".$loan_code."'");
	$result_a = mysql_fetch_assoc($sql_a);
	$row_a = mysql_num_rows($sql_a);

	if($row_a>0)
	{
		if($result_a['bd_from']=='Monthly')
		{
			$amount_1 = $result_a['amount'] + $amount;
			$balance_1 = $result_a['balance'] + $amount;

			$insert_q = mysql_query("UPDATE late_interest_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$balance_1."', 
							month_receipt = '".$month_receipt."',
							balance = '".$amount_1."' WHERE loan_code = '".$loan_code."'");	
		}
		else
		{
			$amount_1 = $result_a['amount'] + $amount;
			$balance_1 = $result_a['balance'] + $amount;

			$insert_q = mysql_query("UPDATE late_interest_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$amount_1."', 
							balance = '".$balance_1."',
							month_receipt = '".$month_receipt."',
							bd_from = 'Instalment + Monthly' WHERE loan_code = '".$loan_code."'");	

		}
	}
	else
	{
		$insert_q = mysql_query("INSERT INTO late_interest_record 
							SET customer_id = '".$customer_id."', 
							bd_date = '".$bd_date."', 
							amount = '".$amount."', 
							package_id = '".$package_id."', 
							loan_code = '".$loan_code."', 
							balance = '".$amount."', 
							bd_from = 'Monthly', 
							month_receipt = '".$month_receipt."',
							user_id = '".$_SESSION['login_name']."', 
							branch_id = '".$_SESSION['login_branchid']."', 
							branch_name = '".$_SESSION['login_branch']."'");
	}


	
		$_SESSION['msg'] = "<div class='success'>Late Payment Record has been successfully saved.</div>";
			echo "<script>window.parent.location='view_monthly_list.php?id=".$customer_id."'</script>";
	
}else
if(isset($_POST['pay_Int']))
{//Payment receive going here!
	$lid = $_POST['lid'];
	$amount = $_POST['amount'];
	$package_id = $_POST['package_id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$month_receipt = $_POST['month_receipt'];
	
	//late interest record
	//lir = loan interest
	$lir_q = mysql_query("SELECT * FROM late_interest_record
							WHERE id = '".$lid."'");
	$lir = mysql_fetch_assoc($lir_q);
	
	//cust info
	$cust_q = mysql_query("SELECT * FROM customer_details
							WHERE id = '".$lir['customer_id']."'");
	$cust = mysql_fetch_assoc($cust_q);
	
	//check package receipt
	$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
	$get_rt = mysql_fetch_assoc($rt_q);
	
	//is this trans something wrong?
	$trans = "LATE INT - ".$cust['name']." - ".$lir['loan_code'];

	//there's no wrong here bro...
	$balance = $lir['balance'] - $amount;
	
	if($balance <= 0)
	{
		$balance = 0;
		$update_q2 = mysql_query("UPDATE customer_details SET blacklist = ' ' WHERE id = '".$lir['customer_id']."'");

	}

		
	//insert a new record in this table
	$insert_q = mysql_query("INSERT INTO late_interest_payment_details SET
							lid = '".$lid."', 
							amount = '".$amount."', 
							balance = '".$balance."',
							payment_date = '".$date."', 
							month_receipt = '".$month_receipt."',
							created_by = '".$_SESSION['login_name']."', 
							created_date = now()");
	if($insert_q)
	{
		//then update the late_interest_record,
		// each customer has only one record
		$update = mysql_query("UPDATE late_interest_record
								SET balance = '".$balance."'
								WHERE id = '".$lid."'");
		
		if($update)
		{
			//After it's updated, insert a new records in cashbook
			$insert_c_q = mysql_query("INSERT INTO cashbook 
										SET type = 'RECEIVED2', 
										package_id = '".$package_id."', 
										table_id = '".$lid."', 
										transaction = '".$trans."', 
										amount = '".$amount."', 
										date = '".$date."', 
										branch_id = '".$_SESSION['login_branchid']."', 
										branch_name = '".$_SESSION['login_branch']."', 
										staff_name = '".$_SESSION['login_name']."', 
										receipt_no = 'LATE INT ".$lir['loan_code']."', 
										created_date = now()");
			if($insert_c_q)
			{
				if($get_rt['receipt_type'] == '1')
				{
					$rcpmth = date('Y-m', strtotime($date));
					$insert_b2q = mysql_query("INSERT INTO balance_rec 
												SET package_id = '".$package_id."', 
												interest = '".$amount."', 
												bal_date = '".$date."', 
												month_receipt = '".$rcpmth."', 
												branch_id = '".$_SESSION['login_branchid']."', 
												branch_name = '".$_SESSION['login_branch']."'");
					
					$_SESSION['msg'] = "<div class='success'>Late Interest Payment has been successfully recorded into the database.</div>";
					echo "<script>window.parent.location='payLateInt.php?id=".$lid."'</script>";
				}else
				{
					$_SESSION['msg'] = "<div class='success'>Late Interest Payment has been successfully recorded into the database.</div>";
					echo "<script>window.parent.location='payLateInt.php?id=".$lid."'</script>";
				}
			}
			
		}
	}
}
?>