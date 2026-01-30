<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_expenses']))
{
	$title = addslashes(strtoupper($_POST['title']));
	$stock = $_POST['stock'];
	$inamt = $_POST['inamt'];
	$period = $_POST['period'];
	$package_id = $_POST['package_id'];
	$description = addslashes(strtoupper($_POST['description']));
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	$monthlyamt = $stock / $period;
		
	//insert skim kutu
	$insert_q = mysql_query("INSERT INTO skim_kutu SET title = '".$title."', stock = '".$stock."', inamt = '".$inamt."', period = '".$period."', package_id = '".$package_id."', description = '".$description."', monthlyamt = '".$monthlyamt."', date_created = now(), created_by = '".$_SESSION['login_name']."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
	
	if($insert_q)
	{
		$id = mysql_insert_id();
		
		$insert_c = mysql_query("INSERT INTO cashbook_skimkutu SET description = '".$title."', inamt = '".$inamt."', package_id = '".$id."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
		
		if($insert_c)
		{
			$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
			echo "<script>window.location='add_kutu.php'</script>";
		}
	}
}
?>