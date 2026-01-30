<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_trans']))
{
	$description = addslashes($_POST['description']);
	$date = $_POST['date'];
	$amount = $_POST['amount'];
	$type = $_POST['type'];
	
	//insert expenses
	$insert_q = mysql_query("INSERT INTO hq_cashbook SET description = '".$description."', date = '".$date."', amount = '".$amount."', type = '".$type."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
		echo "<script>window.location='../cashbookhq/'</script>";

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
	
	//insert expenses
	$insert_q = mysql_query("UPDATE hq_cashbook SET description = '".$description."', date = '".$date."', amount = '".$amount."', type = '".$type."' WHERE id = '".$id."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Transaction has been successfully updated.</div>";
		echo "<script>window.location='../cashbookhq/details.php?$m'</script>";

	}
}else
if($_POST['action'] == 'delete_trans')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM hq_cashbook WHERE id = '".$id."'");
	
	if($delete_ac)
	{
		$_SESSION['msg'] = "<div class='success'>Expenses record has been deleted from database.</div>";
		echo "<script>window.location='../expenses/'</script>";
	}
}
?>