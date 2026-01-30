<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_staff']))
{
	$staffname = addslashes($_POST['staffname']);
	$username = addslashes($_POST['username']);
	$password = addslashes($_POST['password']);
	$level = addslashes($_POST['level']);
	$branch_id = $_POST['branch'];
	
	//get branch name
	$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id = '".$branch_id."'");
	$get_branch = mysql_fetch_assoc($branch_q);
	
	$t_hasher = new PasswordHash(8, FALSE);
	$hash_pswd = $t_hasher->HashPassword($password);
	
	//insert staff
	$insert_q = mysql_query("INSERT INTO user SET
							username = '".$username."',
							pswd = '".$hash_pswd."',
							fullname = '".$staffname."',
							level = '".$level."',
							branch_id = '".$branch_id."',
							branch_name = '".$get_branch['branch_name']."',
							time_created = now()");

	if($insert_q)
	{
		$id = mysql_insert_id();
		
		//insert user access page
		$ac_q = mysql_query("INSERT INTO accessright 
								SET staffid = '".$id."', 
								home_page = 'on', 
								files_page = 'on', 
								customer_page = 'on', 
								loan_page = 'on', 
								cashbook_page = 'on', 
								report_page = 'on', 
								setting_page = 'on', 
								help_page = 'on'") 
								or die(mysql_query());
		
		if($ac_q)
		{
			$_SESSION['msg'] = "<div class='success'>New user has been added into record.</div>";
			echo "<script>window.location='../setting/'</script>";	
		}
	}else
	{
		$_SESSION['msg'] = "<div class='error'>Duplicate staff name has been found in the record!</div>";
		echo "<script>window.location='add_new.php'</script>";	
	}
	
}else
if(isset($_POST['edit_staff']))
{
	$id = $_POST['id'];

	$staffname = addslashes($_POST['staffname']);
	$username = addslashes($_POST['username']);
	$password = $_POST['password'];
	$level = addslashes($_POST['level']);
	$branch_id = $_POST['branch'];
	
	//get branch name
	$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id = '".$branch_id."'");
	$get_branch = mysql_fetch_assoc($branch_q);
	
	//update staff
	$update_q = mysql_query("UPDATE user 
							SET username = '".$username."', 
							fullname = '".$staffname."', 
							level = '".$level."', 
							branch_id = '".$branch_id."', 
							branch_name = '".$get_branch['branch_name']."' 
							WHERE id = '".$id."'")
							or die(mysql_error());
	
	if($password != '')
	{
		$t_hasher = new PasswordHash(8, FALSE);
		$hash_pswd = $t_hasher->HashPassword($password);
		
		$update_q = mysql_query("UPDATE user SET pswd = '".$hash_pswd."' WHERE id = '".$id."'")or die(mysql_error());
	}

	if($update_q)
	{
		
		$_SESSION['msg'] = "<div class='success'>User's record has been successfully updated.</div>";
		echo "<script>window.location='edit_authorization.php?id=".$id."'</script>";	
		
	}	
}else
if($_POST['action'] == 'delete_staff')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM accessright WHERE staffid = '".$id."'");
	
	if($delete_ac)
	{
		$delete_q = mysql_query("DELETE FROM user WHERE id = '".$id."'");
		
		if($delete_q)
		{
			$_SESSION['msg'] = "<div class='success'>User has been deleted from record.</div>";
			echo "<script>window.location='../setting/'</script>";
		}
	}
}else
if($_POST['action'] == 'update_amount')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	
	$sql = mysql_query("SELECT * FROM approval_level WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql2 = mysql_query("SELECT * FROM approval_level WHERE approved_by = 'Boss'");
	$get_q2 = mysql_fetch_assoc($sql2);
	
	if($get_q['amount'] == $get_q2['amount'])
	{
		$update_boss = mysql_query("UPDATE approval_level SET amount = '".$amount."' WHERE id = '".$get_q2['id']."'");
	}
	
	//update
	$update_q = mysql_query("UPDATE approval_level SET amount = '".$amount."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Amount has been updated.</div>";
	}
}
?>