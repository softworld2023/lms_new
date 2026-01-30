<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

$id = $_POST['id'];
$payment_date = $_POST['payment_date'];
$amount = $_POST['amount'];
$balance = $_POST['balance'];

//update late interest
$update_q = mysql_query("UPDATE late_interest_payment_details SET payment_date = '".$payment_date."', amount = '".$amount."', balance = '".$balance."' WHERE id = '".$id."'");

if($update_q)
{
	$msg .= 'Late interest details has been successfully updated.<br>';

	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo '<script>parent.window.location.reload(true);</script>';
}

?>
