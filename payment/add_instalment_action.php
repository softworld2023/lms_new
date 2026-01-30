<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SESSION['login_branchid'] == '') {
	session_destroy();
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php
} else {
	include("../include/dbconnection.php");
	include("../config.php");

	mysql_query("SET TIME_ZONE = '+08:00'");
	$customer_id = $_POST['customer_code'];

	$branch_name = strtolower($_SESSION['login_branch']);

	$loan_package1 = stripslashes($_POST['loan_package']);
	$loan_package = mysql_real_escape_string($loan_package1);
	$loan_amount = str_replace(',', '', $_POST['loan_amount']);
	$loan_pokok = str_replace(',', '', $_POST['loan_pokok']);
	$loan_period = $_POST['loan_period'];
	$loan_interest = $_POST['loan_interest'];
	$loan_interesttotal = $_POST['loan_interesttotal'];
	$loan_amount_month = $_POST['loan_amount_month'];
	$loan_type = $_POST['loan_type'];
	$loan_code = strtoupper($_POST['loan_code']);
	$loan_remarks = addslashes(strtoupper($_POST['loan_remarks']));
	$custRemark = strtoupper($_POST['custRemark']);
	$start_month = $_POST['start_date'];

	//new
	$loanpackagetype = $_POST['loanpackagetype'];
	$actual_loanamt = $_POST['hide_loanamt'];
	$prev_settlementdate = date('Y-m-d', strtotime($_POST['previous_settlement_date']));
	$prev_loancode = strtoupper($_POST['previous_loan_code']);

	if ($loan_type == 'Fixed Loan') {
		//2021-09-23 edit
		if ($loan_package == 'NEW PACKAGE') {

			$loan_total = $loan_amount_month * $loan_period;
		} else {
			$loan_total = $loan_amount + $loan_interesttotal;
		}
	} else {
		$loan_total = $loan_amount;
	}

	$monthly = ($loan_amount * 1.2 ) / 12;
	$currentDate = date('Y-m-d');

	$loanstatus = 'Paid';
	$branch_id = $_SESSION['login_branchid'];
	$branch_name = $_SESSION['login_branch'];

	//check if the customer have customer id in customer_details, if not return customer id not found
	// $check_customer = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '" . $customer_id . "'");
	// $check_customer_row = mysql_fetch_array($check_customer);
	// if ($check_customer_row['id'] == '' || $check_customer_row['id'] == null) {
	// 	$msg .= "Customer ID not found!";
	// 	$_SESSION['msg'] = "<div class='error'>" . $msg . "</div>";
	// 	echo "<script>window.location='add_instalment.php'</script>";
	// } else {
		if ($loan_amount != '' && $loan_period != '' && $loan_pokok != '') {
	
			$insert_loanpackage = mysql_query("INSERT INTO customer_loanpackage SET 
			customer_id = '" . $customer_id . "', 
			loan_package = '" . $loan_package . "', 
			loan_code = '" . $loan_code . "', 
			loan_amount = '" . $loan_amount . "', 
			loan_period = '" . $loan_period . "', 
			loan_interest = '" . $loan_interest . "', 
			loan_interesttotal = '" . $loan_interesttotal . "', 
			loan_total = '" . $loan_total . "', 
			loan_type = '" . $loan_type . "', 
			loan_status = '" . $loanstatus . "', 
			loan_remarks = '" . $loan_remarks . "', 
			apply_date = now(), 
			approval_date = now(), 
			payout_date = now(),
			staff_id = '" . $_SESSION['taplogin_id'] . "', 
			staff_name = '" . $_SESSION['login_name'] . "', 
			branch_id = '" . $branch_id . "', 
			branch_name = '" . $branch_name . "', 
			created_date = now(), 
			loanpackagetype = '" . $loanpackagetype . "', 
			actual_loanamt = '" . $actual_loanamt . "', 
			prev_settlementdate = '" . $prev_settlementdate . "', 
			prev_loancode = '" . $prev_loancode . "', 
			loan_pokok = '" . $loan_pokok . "',
			start_month = '". $start_month ."',
			loan_lejar_date = now(),
			settlement_period = '" . $loan_period . "'");

			$msg .= "Customer loan has been successfully add into record!";
	
			$_SESSION['msg'] = "<div class='success'>" . $msg . "</div>";
			// echo back to current page
			echo "<script>window.location='add_instalment.php'</script>";
		} else {
			$msg .= "The detail have not fully filled yet!";
			$_SESSION['msg'] = "<div class='error'>" . $msg . "</div>";
			echo "<script>window.location='add_instalment.php'</script>";
		}
	// }
}
?>