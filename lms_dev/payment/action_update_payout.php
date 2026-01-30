<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");
if(isset($_POST['update_payout']))
{
	
	$id = $_POST['id'];
	$customer_loanid = $_POST['customer_loanid'];
	$customer_id = $_POST['customer_id'];
	$stamping = $_POST['stamping'];
	$ctos = $_POST['ctos'];
	$ccris = $_POST['ccris'];
	$mlsettle = $_POST['mlsettle'];
	$overlap = $_POST['overlap'];
	$cm = $_POST['cm'];
	$lawyer_fees = $_POST['lawyer_fees'];
	$processing_fees = $_POST['processing_fees'];
	$others = $_POST['others'];
	$nettp = $_POST['nettp'];
	
	$update_payout = mysql_query("UPDATE payout_details SET stamping = '".$stamping."', ctos = '".$ctos."', ccris = '".$ccris."', mlsettle = '".$mlsettle."', overlap = '".$overlap."', cm = '".$cm."', lawyer_fees = '".$lawyer_fees."', processing_fees = '".$processing_fees."', others = '".$others."', nettp = '".$nettp."' WHERE customer_loanid = '".$customer_loanid."'");
	
	if($update_payout)
	{
		$msg .= 'Payout details has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		
		echo "<script>window.history.go(-1)</script>";
	}
}
?>