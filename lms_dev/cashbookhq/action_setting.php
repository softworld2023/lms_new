<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");


if(isset($_POST['add_type']))
{
	$description = strtoupper(addslashes($_POST['description']));
	
	//insert expenses type
	$insert_q = mysql_query("INSERT INTO cashbook_setting SET description = '".$description."'");
	
	if($insert_q)
	{
		$_SESSION['msg1'] = "<div class='success'>Cashbook Transaction Type has been successfully saved into database.</div>";
		echo "<script>window.location='setting.php'</script>";
	}
}else
if($_POST['action'] == 'delete_type')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM cashbook_setting WHERE id = '".$id."'");
	
	if($delete_ac)
	{
		$_SESSION['msg1'] = "<div class='success'>Cashbook Transaction Type has been deleted from database.</div>";
		echo "<script>window.location='setting.php'</script>";
	}
}else
if($_POST['action'] == 'update_type')
{
	$id = $_POST['id'];
	$description = addslashes(strtoupper($_POST['description']));
	
	//update
	$update_q = mysql_query("UPDATE cashbook_setting SET description = '".$description."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$_SESSION['msg1'] = "<div class='success'>Cashbook Transaction Type has been updated.</div>";
	}
}
?>