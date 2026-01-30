<?php
session_start();
$conn_hostname = "localhost";
$conn_database = "loansystem_dev";


$conn_username = "root";
$conn_password = "";
$conn_connection = mysql_connect($conn_hostname , $conn_username , $conn_password ) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($conn_database, $conn_connection);
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");
// echo '<pre>';
// print_r($_POST);
// exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['save_reject']))
{
	$reject_date = $_POST['reject_date'];
	$cust_id = $_POST['customer_id'];
	$customer_name = addslashes(strtoupper($_POST['customer_name']));
	$customer_ic = addslashes($_POST['customer_ic']);
	$customer_company = addslashes($_POST['customer_company']);
	$customer_contact_number = addslashes($_POST['customer_contact_number']);
	$reject_reason = addslashes($_POST['reject_reason']);
	$customer_from = addslashes($_POST['customer_from']);
	$branch_id = $_SESSION['login_branchid']; 
	$branch_name = $_SESSION['login_branch']; 
	$document = $_FILES["reject_doc"]["name"];


			$insert_address_q = mysql_query("INSERT INTO reject_loan 
											SET  reject_date= '".$reject_date."', 
											customer_name = '".$customer_name."', 
											customer_ic = '".$customer_ic."', 
											customer_company = '".$customer_company."', 
											customer_contact_number = '".$customer_contact_number."', 
											reject_reason = '".$reject_reason."', 
											customer_from = '".$customer_from."', 
											branch_id = '".$branch_id."', 
											branch_name = '".$branch_name."',
											document = '".$document."',
											customer_id = '".$cust_id."',
											status = 'ACTIVE'");
			$customer_id = mysql_insert_id();

			include("../include/dbconnection.php");

			$update_details_q = mysql_query("UPDATE customer_details 
										SET customer_status = 'REJECTED'
										WHERE name = '".$customer_name."' AND nric = '".$customer_ic."'");


				if (!file_exists('../reject/document/' . strtolower($branch_name) . '/' . $customer_id)) 
	{
		mkdir('../reject/document/' . strtolower($branch_name) . '/' . $customer_id, 0777, true);
	}

	move_uploaded_file($_FILES["reject_doc"]["tmp_name"], '../reject/document/' . strtolower($branch_name) . '/' . $customer_id.'/'.$document);

	
	if($insert_address_q)
	{
		echo "<script>window.parent.location='index.php'</script>";
	}
}
else 
if(isset($_POST['edit_reject']))
{
	$reject_id = $_POST['reject_id'];
	$reject_date = $_POST['reject_date'];
	$customer_name = addslashes(strtoupper($_POST['customer_name']));
	$customer_ic = addslashes($_POST['customer_ic']);
	$customer_company = addslashes($_POST['customer_company']);
	$customer_contact_number = addslashes($_POST['customer_contact_number']);
	$reject_reason = addslashes($_POST['reject_reason']);
	$customer_from = addslashes($_POST['customer_from']);
	$branch_id = $_SESSION['login_branchid']; 
	$branch_name = $_SESSION['login_branch']; 

	if($_FILES["reject_doc"]["name"]!='')
	{
	$document = $_FILES["reject_doc"]["name"];
	}
	else
	{
	$document = $_POST['reject_doc1'];
	}


			$insert_address_q = mysql_query("UPDATE reject_loan 
											SET  reject_date= '".$reject_date."', 
											customer_name = '".$customer_name."', 
											customer_ic = '".$customer_ic."', 
											customer_company = '".$customer_company."', 
											customer_contact_number = '".$customer_contact_number."', 
											reject_reason = '".$reject_reason."', 
											customer_from = '".$customer_from."', 
											branch_id = '".$branch_id."', 
											branch_name = '".$branch_name."',
											document = '".$document."',
											status = 'ACTIVE'
											WHERE id = '".$reject_id."'");


				if (!file_exists('../reject/document/' . strtolower($branch_name) . '/' . $reject_id)) 
	{
		mkdir('../reject/document/' . strtolower($branch_name) . '/' . $reject_id, 0777, true);
	}

	move_uploaded_file($_FILES["reject_doc"]["tmp_name"], '../reject/document/' . strtolower($branch_name) . '/' . $reject_id . '/' . $document);

	
	if($insert_address_q)
	{
		echo "<script>window.parent.location='index.php'</script>";
	}
}


?>