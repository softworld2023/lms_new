<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if($_POST['action'] == 'update_amount')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$opening = date('Y-m-d', strtotime($_POST['opening']));
	
	$cf_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."'");
	$cfq = mysql_fetch_assoc($cf_q);
	
	if($cfq)
	{
		//update
		$update_q = mysql_query("UPDATE cashbook_cf SET amount = '".$amount."', date = '".$opening."' WHERE id = '".$cfq['id']."'");
	}else
	{
		$update_q = mysql_query("INSERT INTO cashbook_cf SET amount = '".$amount."', date = '".$opening."', package_id = '".$id."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
	}		
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if($_POST['action'] == 'update_amountbal1')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$opening = date('Y-m-d', strtotime($_POST['opening']));
	
	
	$cf_q = mysql_query("SELECT * FROM bal1_cf WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."'");
	$cfq = mysql_fetch_assoc($cf_q);
	
	if($cfq)
	{
		//update
		$update_q = mysql_query("UPDATE bal1_cf SET amount = '".$amount."', date = '".$opening."' WHERE id = '".$cfq['id']."'");
	}else
	{
		$update_q = mysql_query("INSERT INTO bal1_cf SET amount = '".$amount."', date = '".$opening."', package_id = '".$id."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
	}
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if($_POST['action'] == 'update_amount_bal2')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$opening = date('Y-m-d', strtotime($_POST['opening']));
	
	
	$cf_q = mysql_query("SELECT * FROM bal2_cf WHERE package_id = '".$id."'  AND branch_id = '".$_SESSION['login_branchid']."' AND month = ''");
	$cfq = mysql_fetch_assoc($cf_q);
	
	if($cfq)
	{
	//update
		$update_q = mysql_query("UPDATE bal2_cf SET amount = '".$amount."', date = '".$opening."' WHERE id = '".$cfq['id']."'");
	}else
	{
		$update_q = mysql_query("INSERT INTO bal2_cf SET amount = '".$amount."', date = '".$opening."', package_id = '".$id."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
	}
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if($_POST['action'] == 'update_bal2')
{
	$mth = $_POST['mth'];
	$pid = $_POST['pid'];
	$amount = $_POST['amount'];
	$opening = $_POST['opening'];
	
	$cf_q = mysql_query("SELECT * FROM bal2_cf WHERE package_id = '".$pid."' AND month = '".$mth."' AND  branch_id = '".$_SESSION['login_branchid']."'");
	$get_cf = mysql_fetch_assoc($cf_q);
	
	if($get_cf)
	{
		//update
		$update_q = mysql_query("UPDATE bal2_cf SET amount = '".$amount."', date = '".$opening."' WHERE id = '".$get_cf['id']."'");
	}else
	{
		//insert
		$update_q = mysql_query("INSERT INTO bal2_cf SET amount = '".$amount."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', date = '".$opening."', package_id = '".$pid."', month = '".$mth."'");
	}
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if(isset($_POST['add_out']))
{
	$mth = $_POST['out_month'];
	$pid = $_POST['id'];
	$amount = $_POST['amount'];
	
	$cf_q = mysql_query("SELECT * FROM bal2_cf WHERE package_id = '".$pid."' AND branch_id = '".$_SESSION['login_branchid']."' AND month = '".$mth."'");
	$get_cf = mysql_fetch_assoc($cf_q);
	
	$opening = $mth."-01";
	
	if($get_cf)
	{
		//update
		$update_q = mysql_query("UPDATE bal2_cf SET amount = '".$amount."', date = '".$opening."' WHERE id = '".$get_cf['id']."'");
	}else
	{
		//insert
		$update_q = mysql_query("INSERT INTO bal2_cf SET amount = '".$amount."', date = '".$opening."', package_id = '".$pid."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', month = '".$mth."'");
	}
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Outstanding has been successfully saved.</div>";
		echo "<script>window.location='add_outstanding.php?id=".$pid."'</script>";
	}
}
?>