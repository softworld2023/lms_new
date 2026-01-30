<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['adjust_late']))
{
	$id = $_POST['id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$amount = $_POST['amount'];
	
	$title_q = mysql_query("SELECT * FROM skim_kutu WHERE id = '".$id."'");
	$title = mysql_fetch_assoc($title_q);
	
	$month_q = mysql_query("SELECT * FROM skimkutu_payment WHERE skim_id = '".$id."'");
	$month = mysql_num_rows($month_q);
	$mth = $month + 1;
			
	//insert skim kutu
	$insert_q = mysql_query("INSERT INTO skimkutu_payment SET skim_id = '".$id."', month = '".$mth."', amount = '".$amount."', payment_date = '".$date."', created_date = now(), created_by = '".$_SESSION['login_name']."'");
	
	if($insert_q)
	{
		$description = $title['title']." ".$title['period']."/".$mth;
		
		$insert_c = mysql_query("INSERT INTO cashbook_skimkutu SET description = '".addslashes($description)."', outamt = '".$amount."', package_id = '".$id."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
		
		if($insert_c)
		{
			$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
			echo "<script>window.parent.location='payment_skimkutu.php?id=".$id."'</script>";
		}
	}
}
?>