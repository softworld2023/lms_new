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
	
	$cf_q = mysql_query("SELECT * FROM kutu_office WHERE id = '".$id."'");
	$cfq = mysql_fetch_assoc($cf_q);
	
	if($cfq)
	{
		//update
		$update_q = mysql_query("UPDATE kutu_office SET date = '".$editdate."', inamt = '".$editin."', stock = '".$editstock."' WHERE id = '".$cfq['id']."'");
		
		$update_cash = mysql_query("UPDATE cashbook_kutuOffice SET date = '".$editdate."', inamt = '".$editin."' WHERE description = '".$titledesc."'");
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
	$editin1 = $_POST['editin'];
	$editout1 = $_POST['editout']; 
   
	$cf_q1 = mysql_query("SELECT * FROM kutuoffice_payment WHERE id = '".$id1."'");
	$cfq1 = mysql_fetch_assoc($cf_q1);
	
	if($cfq1['month'] = 1 && $cfq1['inamt'] != '')
	{
		mysql_query("UPDATE kutu_office SET inamt = '".$editin1."', outamt = '".$editout1."' WHERE id = '".$cfq1['skim_id']."' ");
	}
	
	$prev_month = $cfq1['month'] - 1;
	
	$ko_q = mysql_query("SELECT * FROM kutu_office WHERE id = '".$cfq1['skim_id']."'"); 
	$ko = mysql_fetch_assoc($ko_q);
	
	$prev_balance_q = mysql_query("SELECT * FROM kutuoffice_payment WHERE month = '".$prev_month."' AND skim_id = '".$cfq1['skim_id']."' ");
	$prev_exist = mysql_num_rows($prev_balance_q);	
	$prev_balance = mysql_fetch_assoc($prev_balance_q);
	
	if($prev_exist == 0)
	{
		$editin_minus_out = $editin1 - $editout1;
	}
	else
	{
		$editin_minus_out = $prev_balance['in_minus_out'] + $editin1 - $editout1;	
	}
	
	if($cfq1)
	{
		//update
		$update_q1 = mysql_query("UPDATE kutuoffice_payment SET payment_date = '".$editdate1."', inamt= '".$editin1."', outamt = '".$editout1."', in_minus_out = '".$editin_minus_out."' WHERE id = '".$id1."'");		
	}
	
	if($update_q1)
	{
		$_SESSION['msg'] = "<div class='success'>Data</div>";
		
		$cf_q1 = mysql_query("SELECT * FROM kutuoffice_payment WHERE id = '".$id1."'");
		$cfq1 = mysql_fetch_assoc($cf_q1);		
		
		$update_cashbook = mysql_query("UPDATE cashbook_kutuoffice SET date = '".$editdate1."', inamt= '".$editin1."' , outamt = '".$editout1."' WHERE package_id='".$cfq1['skim_id']."' AND month = '".$cfq1['month']."' ");
		
		$editbalance_q = mysql_query("SELECT * FROM kutuoffice_payment WHERE month > '".$cfq1['month']."' AND skim_id = '".$cfq1['skim_id']."' ORDER BY month ASC");
	
		while($editbalance = mysql_fetch_assoc($editbalance_q))
		{
			$prev_mth = $editbalance['month'] - 1;
			$prev_mth_details_q = mysql_query("SELECT * FROM kutuoffice_payment WHERE month = '".$prev_mth."' AND skim_id = '".$cfq1['skim_id']."' ");
			$prev_mth_details = mysql_fetch_assoc($prev_mth_details_q);
			$coming_month_exist = mysql_num_rows($prev_mth_details_q);
			
			if($coming_month_exist != 0)
			{
				$coming_balance = $prev_mth_details['in_minus_out'] + $editbalance['inamt'] - $editbalance['outamt'];
				$coming_month_q = mysql_query("UPDATE kutuoffice_payment SET in_minus_out = '".$coming_balance."' WHERE id = '".$editbalance['id']."' ");	
			}
		}
				
		// $editbalance_cb_q = mysql_query("SELECT * FROM cashbook_kutuoffice WHERE package_id='".$cfq1['skim_id']."' AND month > '".$cfq1['month']."' ORDER BY month ASC");
		
		// while($editbalance_cb = mysql_fetch_assoc($editbalance_cb_q))
		// {
			// $prev_mth = $editbalance_cb['month'] - 1;
			// $prev_mth_details_q = mysql_query("SELECT * FROM cashbook_kutuoffice WHERE month = '".$prev_mth."' AND package_id='".$cfq1['skim_id']."' ");
			// $prev_mth_details = mysql_fetch_assoc($prev_mth_details_q);
			// $coming_month_exist = mysql_num_rows($prev_mth_details_q);
			
			// if($coming_month_exist != 0)
			// {
				// $coming_balance = $prev_mth_details['balance'] + $editbalance_cb['inamt'] - $editbalance_cb['outamt'];
				// $coming_month_q = mysql_query("UPDATE cashbook_kutuoffice SET balance = '".$coming_balance."' WHERE id = '".$editbalance_cb['id']."' ");	
			// }			
		// }
	}
	else
	{
		$_SESSION['msg'] = "<div class='success'>Error while updating data. </div>";
	}	
}
?>