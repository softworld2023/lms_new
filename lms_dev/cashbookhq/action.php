<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_trans']))
{
	$description1 = addslashes($_POST['description']);
	$description2 = addslashes(strtoupper($_POST['description2']));
	$date = date('Y-m-d', strtotime($_POST['date']));
	$amount = $_POST['amount'];
	$type = $_POST['type'];
	
	if($description1 != 'OTHERS')
	{
		$description = $description1." ".$description2;
	}else
	{
		$description = $description2;
	}
	
	//insert expenses
	$insert_q = mysql_query("INSERT INTO hq_cashbook SET description = '".$description."', date = '".$date."', amount = '".$amount."', type = '".$type."', username = '".$_SESSION['login_name']."', created_on = now()");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
		echo "<script>window.location='../cashbookhq/add_trans.php'</script>";

	}
}else
if(isset($_POST['edit_trans']))
{
	$id = $_POST['id'];
	$description = addslashes($_POST['description']);
	$date = $_POST['date'];
	$amount = $_POST['amount'];
	$type = $_POST['type'];
	
	$m = date('Y-m', strtotime($date));
	
	$insert_q = mysql_query("UPDATE hq_cashbook SET 
								description = '".$description."', 
								date = '".$date."', 
								amount = '".$amount."', 
								type = '".$type."' 
								WHERE id = '".$id."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Transaction has been successfully updated.</div>";
		echo "<script>window.location='../cashbookhq/details.php?m=$m'</script>";

	}
}else
if($_POST['action'] == 'delete_trans')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM hq_cashbook WHERE id = '".$id."'");
	
	if($delete_ac)
	{
		$_SESSION['msg'] = "<div class='success'>Record has been deleted from database.</div>";
		echo "<script>window.location='../expenses/'</script>";
	}
}else
if(isset($_POST['edit_status']))
{
	$id = $_POST['id'];
	$database_name = $_POST['database_name'];
	$loanpackagetype = addslashes($_POST['loanpackagetype']);
	$date = date('Y-m-d', strtotime($_POST['prev_settlementdate']));
	$prev_loancode = $_POST['prev_loancode'];
	
	//insert loan status
	$insert_q = mysql_query("UPDATE ".$database_name.".customer_loanpackage 
								SET loanpackagetype = '".$loanpackagetype."', 
								prev_settlementdate = '".$date."', 
								prev_loancode = '".$prev_loancode."' 
								WHERE id = '".$id."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Loan Status info has been successfully updated.</div>";
		echo "<script>window.location='../cashbookhq/loanstatus.php'</script>";
	}
}
?>