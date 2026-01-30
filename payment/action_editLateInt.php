<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


$id = $_POST['id'];
$payment_date = $_POST['payment_date'];
$amount = $_POST['amount'];
$balance = $_POST['balance'];
// $lid = $_POST['id'];

//update late interest
$update_q = mysql_query("UPDATE late_interest_payment_details 
						SET payment_date = '".$payment_date."', 
						amount = '".$amount."', 
						balance = '".$balance."' 
						WHERE id = '".$id."'");

$lid_q = mysql_query("SELECT lid FROM late_interest_payment_details WHERE id = '".$id."'");
$lid = mysql_fetch_assoc($lid_q);
	

if($update_q)
{
	//update into record
	$update = mysql_query("UPDATE late_interest_record 
							SET amount = '".$amount."', 
							balance = '".$balance."' 
							WHERE id = '".$lid['lid']."'");
	
	if($update)
	{
		//update into cashbook
		$update_c = mysql_query("UPDATE cashbook
									SET amount = '".$amount."', 
									date = '".$payment_date."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."' 
									WHERE table_id = '".$lid['lid']."'");
	
		$msg .= 'Late interest details has been successfully updated.<br>';

		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo '<script>parent.window.location.reload(true);</script>';
	}
}


?>

