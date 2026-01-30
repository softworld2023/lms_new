<?php
session_start();
include("../include/dbconnection.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

mysql_query("SET TIME_ZONE = '+08:00'");
if(isset($_POST['pay_short']))
{//Payment receive going here!
	// var_dump("hii");
	$short_id = $_POST['short_id'];
	$amount = $_POST['amount'];
	$package_id = $_POST['package_id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$month_receipt = $_POST['month_receipt'];
	
	//late interest record
	//lir = loan interest
	$short_q = mysql_query("SELECT * FROM short_record
							WHERE id = '".$short_id."'");
	$short = mysql_fetch_assoc($short_q);
	
	//cust info
	$cust_q = mysql_query("SELECT * FROM customer_details
							WHERE id = '".$short['customer_id']."'");
	$cust = mysql_fetch_assoc($cust_q);
	
	//check package receipt
	$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
	$get_rt = mysql_fetch_assoc($rt_q);
	
	//is this trans something wrong?
	$trans = "Short - ".$cust['name']." - ".$short['loan_code'];

	//there's no wrong here bro...
	$balance = $short['balance'] - $amount;
	var_dump($balance);
	//insert a new record in this table
	$insert_q = mysql_query("INSERT INTO short_details SET
							short_id = '".$short_id."', 
							amount = '".$amount."', 
							balance = '".$balance."',
							payment_date = '".$date."', 
							month_receipt = '".$month_receipt."',
							created_by = '".$_SESSION['login_name']."', 
							created_date = now()");

	// var_dump("INSERT INTO short_details SET
	// 						short_id = '".$short_id."', 
	// 						amount = '".$amount."', 
	// 						balance = '".$balance."',
	// 						payment_date = '".$date."', 
	// 						month_receipt = '".$month_receipt."',
	// 						created_by = '".$_SESSION['login_name']."', 
	// 						created_date = now()");
	if($insert_q)
	{
		$update = mysql_query("UPDATE short_record
								SET balance = '".$balance."'
								WHERE id = '".$short_id."'");
		
	}

	$_SESSION['msg'] = "<div class='success'>Short has been successfully recorded into the database.</div>";
	echo "<script>window.parent.location='payShort.php?id=".$short_id."'</script>";
}
?>