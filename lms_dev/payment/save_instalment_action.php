<?php
session_start();

if ($_SESSION['login_branchid'] == '') {
	session_destroy();
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php
}else
{
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

$branch_name = strtolower($_SESSION['login_branch']);
$brand_id = $_SESSION['login_branchid'];
$staff_id = $_SESSION['taplogin_id'];
$staff_name = $_SESSION['login_name'];

if(isset($_POST['apply_loan']))
{

	// var_dump($_POST);
	// exit;
	$id_q = mysql_query("SELECT * FROM customer_details ORDER BY id DESC");
	$get_id = mysql_fetch_assoc($id_q);

	$customercode2 = $_POST['customer_code'];
	
	$q = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$customercode2."' AND branch_id = '".$brand_id."'");
	if (mysql_num_rows($q) == 0) {
		$customer_id = $get_id['id']+1100;
		$name = !empty($_POST['name']) ? addslashes(strtoupper($_POST['name'])) : 'UNREGISTERED';
		$nric = isset($_POST['nric']) ? addslashes($_POST['nric']) : '';
		$mobile_contact = isset($_POST['mobile_contact']) ? $_POST['mobile_contact'] : '';

		$insert_details_q = mysql_query("INSERT INTO customer_details 
										SET id = '".$customer_id."', 
										name = '".$name."', 
										nric = '".$nric."', 
										customercode2 = '".$customercode2."', 
										staff_id = '".$_SESSION['taplogin_id']."', 
										staff_name = '".$_SESSION['login_name']."', 
										branch_id = '".$_SESSION['login_branchid']."', 
										branch_name = '".$_SESSION['login_branch']."', 
										created_date = now()");
		$customer_id = mysql_insert_id();
		
		if($insert_details_q)
		{
			$insert_address_q = mysql_query("INSERT INTO customer_address 
												SET customer_id = '".$customer_id."', 
												mobile_contact = '".$mobile_contact."'");
		}
	}else {
		$row = mysql_fetch_assoc($q);
    	$customer_id = $row['id'];
	}
	
	$loan_package1 = stripslashes($_POST['loan_package']);
	$loan_package = mysql_real_escape_string($loan_package1);
	$loan_amount = str_replace(',','',$_POST['loan_amount']);
	$loan_pokok = str_replace(',','',$_POST['loan_pokok']);
	$loan_period = $_POST['loan_period'];
	$loan_interest = $_POST['loan_interest'];
	$loan_interesttotal = $_POST['loan_interesttotal'];
	$loan_amount_month = $_POST['loan_amount_month'];
	$loan_type = $_POST['loan_type'];
	$loan_code = strtoupper($_POST['loan_code']);
	$loan_remarks = addslashes(strtoupper($_POST['loan_remarks']));
	
	//new
	$loanpackagetype = $_POST['loanpackagetype'];
	$actual_loanamt = $_POST['hide_loanamt'];
	$prev_settlementdate = date('Y-m-d', strtotime($_POST['previous_settlement_date']));
	$prev_loancode = strtoupper($_POST['previous_loan_code']);	
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	} else if($_POST['loan_code_monthly']!='') {
	$amount = $_POST['payout_amount'];
	$package_id = '32';
	$loan_code_monthly = strtoupper($_POST['loan_code_monthly']);
	$monthly_date = $_POST['monthly_date'];
	$month = $_POST['month'];
		
	$check_exist_record_q = mysql_query("SELECT * FROM monthly_payment_record WHERE customercode = '".$customercode2."' AND status = 'PAID'");
	$check_exist_record = mysql_num_rows($check_exist_record_q);
		
		if($check_exist_record > 0)
		{
			$update_q = mysql_query("UPDATE monthly_payment_record SET customer_id = '".$customer_id."' WHERE customercode = '".$customercode2."' AND status = 'PAID' AND loan_code = '".$loan_code_monthly."'");
		} else {
			//insert skim kutu
			$insert_q = mysql_query("INSERT INTO monthly_payment_record 
									SET customer_id = '".$customer_id."', 
									monthly_date = '".$monthly_date."', 
									payout_amount = '".$amount."', 
									package_id = '".$package_id."', 
									loan_code = '".$loan_code_monthly."', 
									balance = '".$amount."', 
									month = '".$month."',
									status = 'PAID',
									payout_date = '".$monthly_date."',
									user_id = '".$_SESSION['login_name']."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."'");
								}
		$msg .= "Customer information has been successfully saved!";
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}else{
	$msg .= "The detail have not fully filled yet!";
		$_SESSION['msg'] = "<div class='error'>".$msg."</div>";
}

	//new
	$loanpackagetype = $_POST['loanpackagetype'];
	$actual_loanamt = $_POST['hide_loanamt'];
	$prev_settlementdate = date('Y-m-d', strtotime($_POST['previous_settlement_date']));
	$prev_loancode = strtoupper($_POST['previous_loan_code']);
	$start_month = $_POST['start_date'];
	
	if($loan_type == 'Fixed Loan')
	{
		//2021-09-23 edit
		if($loan_package == 'NEW PACKAGE'){

			$loan_total = $loan_amount_month*$loan_period;
		}
		else{
		$loan_total = $loan_amount + $loan_interesttotal;
		}
	}else
	{
		$loan_total = $loan_amount;
	}
	

	$loanstatus = 'Approved';

	$branch_name = strtoupper($branch_name);

	if($loan_amount!='' && $loan_period!='' && $loan_pokok!=''){
		
		$insert_loanpackage = mysql_query("INSERT INTO 
												customer_loanpackage
											SET 
												customer_id = '".$customer_id."',
												loan_package = '".$loan_package."',
												loan_code = '".$loan_code."',
												loan_amount = '".$loan_amount."',
												loan_period = '".$loan_period."',
												loan_interest = '".$loan_interest."',
												loan_interesttotal = '".$loan_interesttotal."',
												loan_total = '".$loan_total."',
												loan_type = '".$loan_type."',
												loan_status = '".$loanstatus."',
												loan_remarks = '".$loan_remarks."',
												apply_date = now(),
												approval_date = now(),
												staff_id = '".$staff_id."',
												staff_name = '".$staff_name."',
												branch_id = '".$brand_id."',
												branch_name = '".$branch_name."',
												created_date = now(),
												start_month = '".$start_month."',
												loanpackagetype = '".$loanpackagetype."',
												actual_loanamt = '".$actual_loanamt."',
												prev_settlementdate = '".$prev_settlementdate."',
												prev_loancode = '".$prev_loancode."',
												loan_pokok = '".$loan_pokok."',
												settlement_period = '".$loan_period."'");
		$msg .= "Customer loan has been successfully add into record!";
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='../payout'</script>";
}}
?>