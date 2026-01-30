<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_expenses']))
{
	$details1 = addslashes(strtoupper($_POST['details1']));
	$details2 = addslashes(strtoupper($_POST['details2']));
	
	if($details1 != 'OTHER')
	{
		$expenses_details = $details1." ".$details2;
	}else
	{
		$expenses_details = $details2;
	}
	$date = $_POST['date'];
	$amount = $_POST['amount'];
	$package_id = $_POST['package_id'];
	$ttype = addslashes($_POST['ttype']);
	
	//check package receipt
	$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
	$get_rt = mysql_fetch_assoc($rt_q);
	
	//insert expenses
	$insert_q = mysql_query("INSERT INTO expenses SET expenses_details = '".$expenses_details."', date = '".$date."', amount = '".$amount."', package_id = '".$package_id."', ttype = '".$ttype."'");
	
	if($insert_q)
	{
		$id = mysql_insert_id();
		
		if($ttype == 'RECEIVED')
		{
			$ttype = 'RECEIVED2';
		}
		//insert into cashbook
		$insert_c_q = mysql_query("INSERT INTO cashbook SET type = '".$ttype."', package_id = '".$package_id."', table_id = '".$id."', transaction = '".$expenses_details."', amount = '".$amount."', date = '".$date."'");
		
		if($ttype == 'RECEIVED2')
		{
			if($get_rt['receipt_type'] == '1')
			{
				$rcpmth = date('Y-m', strtotime($date));
				$insert_b2q = mysql_query("INSERT INTO balance_rec SET package_id = '".$package_id."', interest = '".$amount."', bal_date = '".$date."', month_receipt = '".$rcpmth."'");
			}
		}
		
		if($insert_c_q)
		{
			$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
			echo "<script>window.location='../expenses/'</script>";
		}
	}
}else
if(isset($_POST['edit_expenses']))
{
	$id = $_POST['id'];
	$expenses_details = addslashes($_POST['expenses_details']);
	$date = $_POST['date'];
	$amount = $_POST['amount'];
	$package_id = $_POST['package_id'];
	$ttype = addslashes($_POST['ttype']);
	
	
	//update expenses
	$insert_q = mysql_query("UPDATE expenses SET expenses_details = '".$expenses_details."', date = '".$date."', amount = '".$amount."', package_id = '".$package_id."', ttype = '".$ttype."' WHERE id = '".$id."'");
	
	if($insert_q)
	{
		//update cashbook
		$cashbook_q = mysql_query("UPDATE cashbook SET package_id = '".$package_id."', transaction = '".$expenses_details."', amount = '".$amount."', date = '".$date."' WHERE type = '".$ttype."' AND table_id = '".$id."'");
		
		if($cashbook_q)
		{
			$_SESSION['msg'] = "<div class='success'>Expenses has been successfully updated.</div>";
			echo "<script>window.location='../expenses/'</script>";
		}
	}
}else
if($_POST['action'] == 'delete_exp')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM expenses WHERE id = '".$id."'");
	
	if($delete_ac)
	{
		$_SESSION['msg'] = "<div class='success'>Expenses record has been deleted from database.</div>";
		echo "<script>window.location='../expenses/'</script>";
	}
}else
if(isset($_POST['add_type']))
{
	$description = strtoupper(addslashes($_POST['description']));
	
	//insert expenses type
	$insert_q = mysql_query("INSERT INTO expenses_setting SET description = '".$description."'");
	
	if($insert_q)
	{
		$_SESSION['msg1'] = "<div class='success'>Expenses Type has been successfully saved into database.</div>";
		echo "<script>window.location='../expenses/setting.php'</script>";
	}
}else
if($_POST['action'] == 'delete_type')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM expenses_setting WHERE id = '".$id."'");
	
	if($delete_ac)
	{
		$_SESSION['msg1'] = "<div class='success'>Expenses Type has been deleted from database.</div>";
		echo "<script>window.location='../expenses/setting.php'</script>";
	}
}else
if($_POST['action'] == 'update_type')
{
	$id = $_POST['id'];
	$description = $_POST['description'];
	
	//update
	$update_q = mysql_query("UPDATE expenses_setting SET description = '".$description."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$_SESSION['msg1'] = "<div class='success'>Expenses Type has been updated.</div>";
	}
}
?>