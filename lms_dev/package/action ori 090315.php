<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_scheme']))
{
	$scheme1 = stripslashes($_POST['scheme']);
	$scheme = strtoupper(mysql_real_escape_string($scheme1));
	$formula1 = stripslashes($_POST['formula']);
	$formula = mysql_real_escape_string($formula1);
	$interest = $_POST['interest'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$type = $_POST['type'];
	$receipt_type = $_POST['receipt_type'];
	
	//insert new truck
	$insert_q = mysql_query("INSERT INTO loan_scheme SET scheme = '".$scheme."', formula = '".$formula."', interest = '".$interest."', from_date = '".$from_date."', to_date = '".$to_date."', type = '".$type."', receipt_type = '".$receipt_type."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>New package has been added.</div>";
		echo "<script>window.location='../package/'</script>";
	}
}else
if($_POST['action'] == 'delete_scheme')
{
	$id = $_POST['id'];
	
	//delete record in database
	$delete_q = mysql_query("DELETE FROM loan_scheme WHERE id = '".$id."'");
	
	if($delete_q)
	{
		$_SESSION['msg'] = "<div class='success'>Package has been successfully deleted from database.</div>";	
	}
}else
if(isset($_POST['edit_scheme']))
{
	$id = $_POST['id'];
	$scheme1 = stripslashes($_POST['scheme']);
	$scheme = strtoupper(mysql_real_escape_string($scheme1));
	$formula1 = stripslashes($_POST['formula']);
	$formula = mysql_real_escape_string($formula1);
	$interest = $_POST['interest'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$type = $_POST['type'];
	$receipt_type = $_POST['receipt_type'];
	
	//insert new scheme
	$insert_q = mysql_query("UPDATE loan_scheme SET scheme = '".$scheme."', formula = '".$formula."', interest = '".$interest."', from_date = '".$from_date."', to_date = '".$to_date."', type = '".$type."', receipt_type = '".$receipt_type."' WHERE id = '".$id."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Package info has been successfully updated.</div>";
		echo "<script>window.location='../package/'</script>";
	}
}

//transfer
else
if(isset($_POST['new_transfer']))
{
	$transfer_details = addslashes($_POST['transfer_details']);
	$date = $_POST['transfer_date'];
	$amount = $_POST['transfer_amount'];
	$from_package = $_POST['from_package'];
	$to_package = $_POST['to_package'];
	
	//insert to table: trasnsfer_trans
	$insert_q = mysql_query("INSERT INTO transfer_trans SET transfer_details = '".$transfer_details."', date = '".$date."', amount = '".$amount."', from_package = '".$from_package."', to_package = '".$to_package."'");
	
	if($insert_q)
	{
		//get the id
		$id = mysql_insert_id();
		
		//insert into cashbook (from package)
		$insert_cashbook = mysql_query("INSERT INTO cashbook SET type = 'TRANSFER1', package_id = '".$from_package."', table_id = '".$id."', transaction = '".$transfer_details."', amount = '".$amount."', date = '".$date."'");
		
		//insert into cashbook (to package)
		$insert_cashbook2 = mysql_query("INSERT INTO cashbook SET type = 'TRANSFER2', package_id = '".$to_package."', table_id = '".$id."', transaction = '".$transfer_details."', amount = '".$amount."', date = '".$date."'");
		
		
		$fp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$from_package."'");
		$get_fp = mysql_fetch_assoc($fp_q);
		
		$tp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$to_package."'");
		$get_tp = mysql_fetch_assoc($tp_q);
		
		//if from package fixed loan - insert into balance 2
		if($get_fp['type'] == 'Fixed Loan')
		{
			$insert_bal = mysql_query("INSERT INTO balance_rec SET package_id = '".$from_package."', loan = '".$amount."', bal_date = '".$date."'");
		}
		
		//if to package fixed loan - insert into balance 2
		if($get_tp['type'] == 'Fixed Loan')
		{
			$insert_bal2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$to_package."', received = '".$amount."', bal_date = '".$date."'");
		}
		
		$_SESSION['msg'] = "<div class='success'>Money has been successfully transfered.</div>";
		echo "<script>window.location='../package/transfer.php'</script>";
	}
}
?>