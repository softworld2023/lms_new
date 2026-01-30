<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['adjust_late']))
{
	$id = $_POST['id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$inamt = $_POST['inamt'];
	$outamt = $_POST['outamt'];
	$amount = $_POST['amount'];
	
	$kutu_office_q = mysql_query("SELECT * FROM kutu_office WHERE id = '".$id."'");
	$kutu_office = mysql_fetch_assoc($kutu_office_q);
	
	$month_q = mysql_query("SELECT * FROM kutuoffice_payment WHERE skim_id = '".$id."'");
	$month = mysql_num_rows($month_q);
	$mth = $month + 1;
			
	$prev_month = $mth - 1;		
	$prev = mysql_query("SELECT * FROM kutuoffice_payment WHERE skim_id = '".$id."'AND month = '".$prev_month."' ");	
	$prev_q = mysql_fetch_assoc($prev);
	
	$in_minus_out = $prev_q['in_minus_out'] + $inamt - $outamt;
	
	if($kutu_office['inamt'] == 0)//if column inamt in table kutu_office is zero...
	{
		if ($inamt == '')//if $intamt is NULL
		{
			$amount = $prev_q['amount'] + $kutu_office['monthlyamt'];
		}
		else
		{	
			//The abs() is used to remove the negative sign in $prev_q['amount']
			$amount = -($kutu_office['stock'] - abs($prev_q['amount']) - $kutu_office['monthlyamt']);
			
			if(abs($amount ) > abs($prev_q['amount'])){
				$amount = -( abs($prev_q['amount']) - $kutu_office['monthlyamt']);
			}
		}
	}
	else
	{
		$amount = $prev_q['amount'] + $kutu_office['monthlyamt'];
	}
			
	//insert skim kutu
	$insert_q = mysql_query("INSERT INTO kutuoffice_payment SET 
								skim_id = '".$id."', 
								month = '".$mth."', 
								inamt = '".$inamt."', 
								outamt = '".$outamt."', 
								in_minus_out = '".$in_minus_out."', 
								amount = '".$amount."', 
								payment_date = '".$date."', 
								created_date = now(), 
								created_by = '".$_SESSION['login_name']."'");
	
	if($insert_q)
	{
		$description = $kutu_office['title']." ".$kutu_office['period']."/".$mth;
		
		$insert_c = mysql_query("INSERT INTO cashbook_kutuoffice SET 
									month = '".$mth."', 
									description = '".addslashes($description)."', 
									inamt = '".$inamt."', 
									outamt = '".$outamt."', 
									package_id = '".$id."', 
									date = '".$date."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."'");
		
		if($insert_c)
		{
			$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
			echo "<script>window.parent.location='payment_kutuOffice.php?id=".$id."'</script>";
		}
	}
}
?>