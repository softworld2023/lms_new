<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

// Get user and branch session data
$user_id = $_SESSION['taplogin_id'];
$branch_name = strtolower($_SESSION['login_branch']);
$brand_id = $_SESSION['login_branchid'];
$staff_id = $_SESSION['taplogin_id'];
$staff_name = $_SESSION['login_name'];

// =============================
//  Add New Monthly Payment Record
// =============================
if (isset($_POST['add_monthly'])) {
	// Get the latest customer ID to increment
	$id_q = mysql_query("SELECT * FROM customer_details ORDER BY id DESC");
	$get_id = mysql_fetch_assoc($id_q);

	// Retrieve posted customer code
	$customer_code = $_POST['customer_code'];

	// Check if customer already exists for the current branch
	$q = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$customer_code."' AND branch_id = '".$brand_id."'");

	// If customer doesn't exist, insert a new one
	if (mysql_num_rows($q) == 0) {
		$customer_id = $get_id['id'] + 1100;
		$name = !empty($_POST['customer_name']) ? addslashes(strtoupper($_POST['customer_name'])) : 'UNREGISTERED';
		$nric = isset($_POST['nric']) ? addslashes($_POST['nric']) : '';

		$insert_details_q = mysql_query("INSERT INTO customer_details 
										SET id = '".$customer_id."', 
										name = '".$name."', 
										nric = '".$nric."', 
										customercode2 = '".$customer_code."', 
										staff_id = '".$staff_id."', 
										staff_name = '".$staff_name."', 
										branch_id = '".$brand_id."', 
										branch_name = '".$branch_name."', 
										created_date = now()");
	} else {
	$customer_id = $_POST['customer_id'];
	}

	// Prepare monthly payment record data
	$amount = $_POST['payout_amount'];
	$package_id = $_POST['loan_package'];
	$loan_code = strtoupper($_POST['loan_code']);
	$monthly_date = date('Y-m-d', strtotime($_POST['monthly_date']));
	$month = $_POST['month'];
	$sd_type = $_POST['sd'];
		
	// Insert monthly payment record
	$insert_q = mysql_query("INSERT INTO monthly_payment_record 
							SET customer_id = '".$customer_id."', 
							customercode = '".$customer_code."',
							monthly_date = '".$monthly_date."', 
							payout_amount = '".$amount."', 
							package_id = '".$package_id."', 
							loan_code = '".$loan_code."', 
							balance = '".$amount."', 
							month = '".$month."',
							status = 'PAID',  
							sd = '".$sd_type."',
							payout_date = '".$monthly_date."',
							user_id = '".$staff_name."', 
							branch_id = '".$brand_id."', 
							branch_name = '".$branch_name."'");
	
	// Redirect with success message
	if ($insert_q) {
		$_SESSION['msg'] = "<div class='success'>Monthly has been successfully saved.</div>";
		echo "<script>window.location='payment_monthly.php'</script>";
	}
}

// =============================
//  Record Payment for Monthly Instalment
// =============================
else if (isset($_POST['pay_Monthly'])) {
	// Retrieve form values
	$mprid = $_POST['mprid'];
	$loan_code = $_POST['loan_code'];
	$customer_id = $_POST['customer_id'];
	$amount = $_POST['payment_amount'];
	$package_id = $_POST['package_id'];
	$date = date('Y-m-d', strtotime($_POST['payment_date']));
	$date_ym = date('Y-m', strtotime($_POST['payment_date']));
	
	// Get loan amount from main record
	$lir_q = mysql_query("SELECT * FROM monthly_payment_record WHERE id = '".$mprid."'");
	$lir = mysql_fetch_assoc($lir_q);
	$loan_amount = $lir['payout_amount'];

	// Calculate total amount paid so far
	$totalamount = 0;
	$sql = mysql_query("SELECT * FROM monthly_payment_details WHERE mprid = '".$mprid."'");
	while($get_q  = mysql_fetch_assoc($sql)) {
	$totalamount += $get_q['payment_amount'];
	}
	
	$balance = $loan_amount - $amount - $totalamount;
		
	// Prevent overpayment
	if ($balance < 0) {
		$_SESSION['msg'] = "<div class='error'> Invalid! The total payment amount exceeds the loan amount.</div>";
					echo "<script>window.parent.location='view_monthly_detail.php?loan_code=".$loan_code."&id=".$customer_id."&mprid=".$mprid."'</script>";
	}
	else {
		// Insert payment detail
	$insert_q = mysql_query("INSERT INTO monthly_payment_details SET
							mprid = '".$mprid."', 
							payment_amount = '".$amount."', 
							balance = '0',
							payment_date = '".$date."', 
							created_by = '".$staff_name."'");

		if ($insert_q) {
			// Update payment status in main record
			if ($balance == 0) {
		$update = mysql_query("UPDATE monthly_payment_record
									SET balance = '".$balance."', status = 'FINISHED', payment_date = '".$date."'
								WHERE id = '".$mprid."'");
			} else {
				$update = mysql_query("UPDATE monthly_payment_record
									SET balance = '".$balance."', status = 'PAID', payment_date = '".$date."'
								WHERE id = '".$mprid."'");
	}
		
			// Insert collection entry if update successful
			if ($update) {
				$sql = "INSERT INTO collection SET
						loan_code = '$loan_code',
						salary = '0',
						salary_type = 'Monthly',
						instalment = '$amount',
						instalment_type = 'Monthly',
						instalment_month = '".$date_ym."',
						tepi1 = '0',
						tepi1_month = '".$date_ym."',
						tepi2 = '0',
						tepi2_month = '0',
						tepi2_bunga = '0',
						balance_received = '0',
						datetime = '".NOW()."', 
						submitted_by_id = '$user_id',
						approved_by_id = '$user_id',
						status = 'PENDING'";
				$query = mysql_query($sql);

				// Redirect with success message
					$_SESSION['msg'] = "<div class='success'> Payment has been successfully recorded into the database.</div>";
					echo "<script>window.parent.location='view_monthly_detail.php?loan_code=".$loan_code."&id=".$customer_id."&mprid=".$mprid."'</script>";
		}
	}
}
}

// =============================
//  Edit Monthly Payout Amount
// =============================
else if ($_POST['action'] == 'edit_Monthly') {
	$mprid = $_POST['id'];
	$amount = $_POST['payout_amount'];
	$totalamount = 0;
	$balance = 0;

	// Sum all existing payments for this MPR
	$sql = mysql_query("SELECT * FROM monthly_payment_details WHERE mprid = '".$mprid."'");
	while ($get_q = mysql_fetch_assoc($sql)) {
	$totalamount += $get_q['payment_amount'];
	}
	$balance = $amount - $totalamount;

	// Update payout amount and balance in monthly_payment_record
	$update = mysql_query("UPDATE monthly_payment_record
								SET payout_amount = '".$amount."', balance = '".$balance."'
								WHERE id = '".$mprid."'");

	if ($update) {
		$msg .= 'Loan Payout Amount has been successfully updated.<br>';
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
	}

// =============================
//  Edit Specific Monthly Payment Detail
// =============================
else if (isset($_POST['edit_Monthly_detail'])) {
	$mprid = $_POST['mprid'];
	$detail_id = $_POST['detail_id'];
	$loan_code = $_POST['loan_code'];
	$customer_id = $_POST['customer_id'];
	$amount = $_POST['payment_amount'];
	$previous_payment_amount = 0;
	$balance = 0;

	// Sum all other payments (excluding current edit)
	$lir_q = mysql_query("SELECT * FROM monthly_payment_details WHERE mprid = '".$mprid."' AND id != '".$detail_id."'");
	while ($lir = mysql_fetch_assoc($lir_q)) {
		$previous_payment_amount += $lir['payment_amount'];
}

	// Get loan amount
$sql = mysql_query("SELECT * FROM monthly_payment_record WHERE id = '".$mprid."'");
$get_q = mysql_fetch_assoc($sql);
$loan_amount = $get_q['payout_amount'];

	// Recalculate balance
$balance = $loan_amount - $amount - $previous_payment_amount;

	// Validate: prevent overpayment
	if ($balance < 0) {
					$_SESSION['msg'] = "<div class='error'> Invalid! The total payment amount exceeds the loan amount</div>";
					echo "<script>window.parent.location='view_monthly_detail.php?loan_code=".$loan_code."&id=".$customer_id."&mprid=".$mprid."'</script>";
	} else {
		// Update payment detail record
	$update = mysql_query("UPDATE monthly_payment_details
								SET payment_amount = '".$amount."'
								WHERE id = '".$detail_id."'");

		// Update payment record balance and status
		if ($balance == 0) {
	$update1 = mysql_query("UPDATE monthly_payment_record
								SET balance = '".$balance."', status = 'FINISHED'
								WHERE id = '".$mprid."'");
		} else {
		$update1 = mysql_query("UPDATE monthly_payment_record
								SET balance = '".$balance."', status = 'PAID'
								WHERE id = '".$mprid."'");
	}

		// Set success message and redirect
					$_SESSION['msg'] = "<div class='success'> Payment has been successfully recorded into the database.</div>";
					echo "<script>window.parent.location='view_monthly_detail.php?loan_code=".$loan_code."&id=".$customer_id."&mprid=".$mprid."'</script>";
	}
}

// =============================
//  Delete Monthly Payment Detail
// =============================
else if ($_POST['action'] == 'delete_monthly_detail') {
	$detail_id = $_POST['id'];
	$mprid = $_POST['mprid'];

	//  Fetch collection_id before deletion
	$get_coll_q = mysql_query("SELECT payment_amount, collection_id FROM monthly_payment_details WHERE mprid = '$mprid'");
	$get_coll = mysql_fetch_assoc($get_coll_q);
	$collection_id = $get_coll['collection_id'];
	$deleted_amount = $get_coll['payment_amount'];

	// Delete the selected payment detail
	$delete_q = mysql_query("DELETE FROM monthly_payment_details WHERE mprid = '".$mprid."' AND id = '".$detail_id."'");

		$previous_payment_amount = 0;
	$balance = 0;

	// Recalculate total payment after deletion
	$lir_q = mysql_query("SELECT * FROM monthly_payment_details WHERE mprid = '".$mprid."'");
	while ($lir = mysql_fetch_assoc($lir_q)) {
		$previous_payment_amount += $lir['payment_amount'];
}

	// Fetch original loan payout amount
	$sql = mysql_query("SELECT * FROM monthly_payment_record WHERE id = '".$mprid."'");
	$get_q = mysql_fetch_assoc($sql);
	$loan_amount = $get_q['payout_amount'];

		// Recalculate balance
	$balance = $loan_amount - $previous_payment_amount;

	// Update main record with new balance and status
	$update1 = mysql_query("UPDATE monthly_payment_record SET balance = '".$balance."', status = 'PAID' WHERE id = '".$mprid."'");

	//  Get current tepi1 value
	$get_tepi1_q = mysql_query("SELECT tepi1 FROM collection WHERE id = '$collection_id'");
	$get_tepi1 = mysql_fetch_assoc($get_tepi1_q);

	$current_tepi1 = (float) $get_tepi1['tepi1'];

	//  Calculate new tepi1 value and update
	$new_tepi1 = $current_tepi1 - $deleted_amount;

	$update_collection = mysql_query("UPDATE collection SET tepi1 = '$new_tepi1' WHERE id = '$collection_id'");	
		
	if ($update1) {
		$msg .= 'This Loan Payment Amount has been successfully deleted.<br>';
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
	}
?>