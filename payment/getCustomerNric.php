<?php
	session_start();
	include("../include/dbconnection.php");
	
	$customer_code = $_POST['customer_code'];

	$getvalue="SELECT * from customer_details WHERE customercode2='$customer_code' AND branch_id = '".$_SESSION['login_branchid']."'";
	$result=mysql_query($getvalue) or die(mysql_error());

	while($row=mysql_fetch_array($result)){
		echo $row['nric'];
	}
?>