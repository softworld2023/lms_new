<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_expenses']))
{
	$title = addslashes(strtoupper($_POST['title']));
	$stock = $_POST['stock'];
	$inamt = $_POST['inamt'];
	$outamt = $_POST['outamt'];
	$period = $_POST['period'];
	$package_id = $_POST['package_id'];
	$description = addslashes(strtoupper($_POST['description']));
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	$monthlyamt = $stock / $period;
	$in_minus_out = $inamt - $outamt;	

	$insert_q = mysql_query("INSERT INTO kutu_office SET 
									title = '".$title."', 
									stock = '".$stock."', 
									inamt = '".$inamt."', 
									outamt = '".$outamt."', 
									period = '".$period."', 
									package_id = '".$package_id."', 
									description = '".$description."', 
									monthlyamt = '".$monthlyamt."', 
									date_created = now(), 
									created_by = '".$_SESSION['login_name']."', 
									date = '".$date."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."'");
	
	if($insert_q)
	{
		$id = mysql_insert_id();
		$description = $title." ".$period."/1";
		
		if ($inamt == '')
		{			
			$insert_c = mysql_query("INSERT INTO cashbook_kutuoffice SET 
										month = '1', 
										description = '".addslashes($description)."', 
										inamt = '".$inamt."', 
										outamt = '".$outamt."', 
										package_id = '".$id."', 
										date = '".$date."', 
										branch_id = '".$_SESSION['login_branchid']."', 
										branch_name = '".$_SESSION['login_branch']."'");
			
			$insert_p = mysql_query("INSERT INTO kutuoffice_payment SET 
										month = '1', 
										skim_id = '".$id."', 
										inamt = '".$inamt."', 
										outamt = '".$outamt."', 
										in_minus_out = '".$in_minus_out."', 
										amount= '".$monthlyamt."', 
										payment_date = '".$date."',  
										created_date = now(), 
										created_by = '".$_SESSION['login_name']."'");			
		}
		else
		{
			$insert_c = mysql_query("INSERT INTO cashbook_kutuoffice SET 
										month = '1', 
										description = '".addslashes($description)."', 
										inamt = '".$inamt."', 
										outamt = '".$outamt."', 
										package_id = '".$id."', 
										date = '".$date."', 
										branch_id = '".$_SESSION['login_branchid']."', 
										branch_name = '".$_SESSION['login_branch']."'");		

			$stock = - ($stock - $monthlyamt);
			
			$insert_p = mysql_query("INSERT INTO kutuoffice_payment SET 
										month = '1', 
										skim_id = '".$id."', 
										inamt = '".$inamt."', 
										outamt = '".$outamt."', 
										in_minus_out = '".$in_minus_out."', 
										amount= '".$stock."', 
										payment_date = '".$date."',  
										created_date = now(), 
										created_by = '".$_SESSION['login_name']."'");			
		}
		
		if($insert_c)
		{
			$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
			echo "<script>window.location='add_kutuOffice.php'</script>";
		}
	}
}
?>