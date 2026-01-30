<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if($_POST['action'] == 'update_amount')
{
	$id = $_POST['id'];
	$editdate = $_POST['editdate'];
	$titledesc = $_POST['titledesc'];
	$editin = $_POST['editin'];
	$editstock = $_POST['editstock'];
	
	$cf_q = mysql_query("SELECT * FROM skim_kutu WHERE id = '".$id."'");
	$cfq = mysql_fetch_assoc($cf_q);
	
	if($cfq)
	{
		//update
		$update_q = mysql_query("UPDATE skim_kutu SET date = '".$editdate."', inamt = '".$editin."', stock = '".$editstock."' WHERE id = '".$cfq['id']."'");
		
		$update_cash = mysql_query("UPDATE cashbook_skimkutu SET date = '".$editdate."', inamt = '".$editin."' WHERE description = '".$titledesc."'");
	}
	
	if($update_q)
		
	{
		$_SESSION['msg'] = "<div class='success'>Data has been updated.</div>";
	}
	else 
	{
		$_SESSION['msg'] = "<div class='success'>Error while updating data. </div>";
	}
	
}
elseif($_POST['action'] == 'update_amount1')
{
	$id1 = $_POST['id'];
	$editdate1 = $_POST['editdate'];
	$editout1 = $_POST['editout'];
	$editstock1 = $_POST['editstock'];

	
	$cf_q1 = mysql_query("SELECT * FROM skimkutu_payment WHERE id = '".$id1."'");
	$cfq1 = mysql_fetch_assoc($cf_q1);
	
	if($cfq1)
	{
		//update
		$update_q1 = mysql_query("UPDATE skimkutu_payment SET payment_date = '".$editdate1."', amount = '".$editout1."' WHERE id = '".$cfq1['id']."'");
		
	}
	
	if($update_q1)
	{
		$_SESSION['msg'] = "<div class='success'>Data</div>";
		
		$skim_id_q = mysql_query("SELECT * FROM skimkutu_payment WHERE id = '".$id1."'");
		$skim1 = mysql_fetch_assoc($skim_id_q);
		$update_cashbook = mysql_query("UPDATE cashbook_skimkutu SET date = '".$skim1['payment_date']."', outamt = '".$skim1['amount']."' WHERE package_id='".$skim1['skim_id']."' and description like '%$skim1[month]%'");
	}
	else
	{
		$_SESSION['msg'] = "<div class='success'>Error while updating data. </div>";
	}
	
}
?>