<?php
session_start();

include('../include/dbconnection.php');

if(isset($_POST['save_record']))
{
	$cust_id = $_POST['custid'];
	$date = date("Y-m-d", strtotime($_POST['date']));
	$handleby = $_POST['personic'];
	$level = $_POST['level'];
	$reason = $_POST['reason'];
	$saveby = $_SESSION['login_name'];
	$save_date = date("Y-m-d");

	$insert_record = mysql_query("INSERT INTO blacklist_reason SET customer_id = '$cust_id', date = '$date', handleby = '$handleby', level='$level', reason = '$reason', saveby = '$saveby', save_date = '$save_date'");
	
	if($insert_record)
	{
		
		$_SESSION['msg'] = "<div class='success'>Record has been successfully saved.</div>";	
		echo "<script>window.parent.location='index.php'</script>";
	}
}

?>