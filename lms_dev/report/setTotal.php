<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['submit']))
{
	$date = $_POST['date'];
	$amount = $_POST['office_total'];
	
	//update
	$update_q = mysql_query("UPDATE cashinoffice SET amount = '".$amount."', date = '".$date."' WHERE id = 1");
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Initial Amount has been set.</div>";
		echo "<script>window.history.go(-1)</script>";
	}
}
?>