<?php
session_start();
include("../include/dbconnection.php");
// echo '<pre>';
// print_r($_POST);
// exit;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($_SESSION['login_branchid'] == '') {
	session_destroy();
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php

}else if(isset($_POST['apply_loan'])){
	
	// include("../include/dbconnection.php");
	// include("../config.php");

	mysql_query("SET TIME_ZONE = '+08:00'");

	$branch_name = strtolower($_SESSION['login_branch']);

	// if(isset($_POST)){
		$id_q = mysql_query("SELECT * FROM customer_details ORDER BY id DESC");
		$get_id = mysql_fetch_assoc($id_q);

		$customer_id = $get_id['id']+1100;
		
		//customer_details
		$bis = addslashes(strtoupper($_POST['bis']));
		$name = addslashes(strtoupper($_POST['name']));
		$title = addslashes(strtoupper($_POST['title']));
		if($_POST['dob'] != ''){
			$dob = date('Y-m-d',strtotime($_POST['dob']));
		}else{
			$dob = $_POST['dob'];
		}
		$nationality = addslashes($_POST['nationality']);
		$old_ic = addslashes($_POST['old_ic']);
		$nric = addslashes($_POST['nric']);
		$source = isset($_POST['source']) ? addslashes(strtoupper($_POST['source'])) : '';
		
		$gender = addslashes($_POST['gender']);
		$race = addslashes($_POST['race']);
		$marital_status = addslashes($_POST['marital_status']);
		$no_dependents = $_POST['no_dependents'];
		$dependent1 = $_POST['dependent1'];
		$dependent2 = $_POST['dependent2'];
		$dependent3 = $_POST['dependent3'];
		$dependent4 = $_POST['dependent4'];
		$dependent5 = $_POST['dependent5'];
		$dependent6 = $_POST['dependent6'];
		$academic_qualification = addslashes(strtoupper($_POST['academic_qualification']));
		$mother_name = addslashes(strtoupper($_POST['mother_name']));
		//$customercode1 = $_POST['customercode1'];
		$customercode2 = $_POST['customercode2'];
		$customercode1 = '';
		$recruitor = $_POST['recruitor'];
		$cust_pic = '('.date("Y-m-d-H-i-s").')'.$_FILES["cust_pic"]["name"];
		$cust_pic2 = '('.date("Y-m-d-H-i-s").')'.$_FILES["cust_pic3"]["name"];
		$cust_pic3 = '('.date("Y-m-d-H-i-s").')'.$_FILES["cust_pic5"]["name"];
		
		if($race == 'Others'){
			$race = addslashes(strtoupper($_POST['race_other']));
		}
		$others_ic = $_POST['others_ic'];
		$others_ic2 = $_POST['others_ic2'];
		
		//customer_address
		$address1 = addslashes(strtoupper($_POST['address1']));
		$address2 = addslashes(strtoupper($_POST['address2']));
		$address3 = addslashes(strtoupper($_POST['address3']));
		$postcode = $_POST['postcode'];
		$city = addslashes(strtoupper($_POST['city']));
		$state = addslashes(strtoupper($_POST['state']));
		$residence = addslashes($_POST['residence']);
		$year_stay = $_POST['year_stay'];
		$home_contact = $_POST['home_contact'];
		$mobile_contact = $_POST['mobile_contact'];
		$m_address1 = addslashes(strtoupper($_POST['m_address1']));
		$m_address2 = addslashes(strtoupper($_POST['m_address2']));
		$m_address3 = addslashes(strtoupper($_POST['m_address3']));
		$m_postcode = $_POST['m_postcode'];
		$m_city = addslashes(strtoupper($_POST['m_city']));
		$m_state = addslashes(strtoupper($_POST['m_state']));
		$m_residence = addslashes($_POST['m_residence']);
		$m_year_stay = $_POST['m_year_stay'];
		$month_stay = $_POST['month_stay'];
		$m_month_stay = $_POST['m_month_stay'];
		
		$insert_details_q = mysql_query("INSERT INTO customer_details 
											SET id = '".$customer_id."', 
											bis = '".$bis."', 
											name = '".$name."', 
											title = '".$title."', 
											`from` = '".$source."',
											dob = '".$dob."', 
											nationality = '".$nationality."', 
											old_ic = '".$old_ic."', 
											nric = '".$nric."', 
											gender = '".$gender."', 
											race = '".$race."', 
											marital_status = '".$marital_status."',
											no_dependents = '".$no_dependents."',  
											academic_qualification = '".$academic_qualification."', 
											mother_name = '".$mother_name."', 
											customercode1 = '".$customercode1."', 
											customercode2 = '".$customercode2."', 
											staff_id = '".$_SESSION['taplogin_id']."', 
											staff_name = '".$_SESSION['login_name']."', 
											branch_id = '".$_SESSION['login_branchid']."', 
											branch_name = '".$_SESSION['login_branch']."', 
											recruitor = '".$recruitor."', 
											others_ic = '".$others_ic."', 
											others_ic2 = '".$others_ic2."', 
											cust_pic = '".$cust_pic."', 
											cust_pic2 = '".$cust_pic2."', 
											cust_pic3 = '".$cust_pic3."', 
											dependent1 = '".$dependent1."',
											dependent2 = '".$dependent2."',
											dependent3 = '".$dependent3."',
											dependent4 = '".$dependent4."',
											dependent5 = '".$dependent5."',
											dependent6 = '".$dependent6."',
											customer_status = 'PENDING APPROVAL',
											created_date = now()");
		
		$customer_id = mysql_insert_id();
		
		if (!file_exists('../files/customer/' . $branch_name . '/' . $customer_id)) {
			mkdir('../files/customer/' . $branch_name . '/' . $customer_id, 0777, true);
		}
		
		move_uploaded_file($_FILES["cust_pic"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic);
		move_uploaded_file($_FILES["cust_pic3"]["tmp_name"], '../files/customer/'. $branch_name . '/' . $customer_id.'/'.$cust_pic2);
		move_uploaded_file($_FILES["cust_pic5"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic3);
		
		if($insert_details_q){
		
			$insert_address_q = mysql_query("INSERT INTO customer_address 
												SET customer_id = '".$customer_id."', 
												address1 = '".$address1."', 
												address2 = '".$address2."', 
												address3 = '".$address3."', 
												postcode = '".$postcode."', 
												city = '".$city."', 
												state = '".$state."', 
												residence = '".$residence."', 
												year_stay = '".$year_stay."', 
												home_contact = '".$home_contact."', 
												mobile_contact = '".$mobile_contact."', 
												m_address1 = '".$m_address1."', 
												m_address2 = '".$m_address2."', 
												m_address3 = '".$m_address3."', 
												m_postcode = '".$m_postcode."', 
												m_city = '".$m_city."', 
												m_state = '".$m_state."', 
												m_residence = '".$m_residence."', 
												m_year_stay = '".$m_year_stay."', 
												m_month_stay = '".$m_month_stay."', 
												month_stay = '".$month_stay."'");

			echo "<script>
					window.location='apply_loan2.php?id=".$customer_id."';
				</script>";

		}
	// }

}else if(isset($_POST['save_loan'])){
	$customer_id = $_POST['customer_id'];
	
	//customer_details
	$bis = addslashes(strtoupper($_POST['bis']));
	$name = addslashes(strtoupper($_POST['name']));
	$title = addslashes(strtoupper($_POST['title']));
	if($_POST['dob'] != ''){
		$dob = date('Y-m-d',strtotime($_POST['dob']));
	}else{
		$dob = $_POST['dob'];
	}
	$nationality = addslashes($_POST['nationality']);
	$source = isset($_POST['source']) ? addslashes(strtoupper($_POST['source'])) : '';
		
	$old_ic = addslashes($_POST['old_ic']);
	$nric = addslashes($_POST['nric']);
	$gender = addslashes($_POST['gender']);
	$race = addslashes($_POST['race']);
	$marital_status = addslashes($_POST['marital_status']);
	$no_dependents = $_POST['no_dependents'];
	$academic_qualification = addslashes(strtoupper($_POST['academic_qualification']));
	$mother_name = addslashes(strtoupper($_POST['mother_name']));
	/*$customercode1 = $_POST['customercode1'];*/
	$customercode2 = $_POST['customercode2'];
	$customercode1 = '';
	$recruitor = $_POST['recruitor'];
	$others_ic = $_POST['others_ic'];
	$others_ic2 = $_POST['others_ic2'];
	$cust_pic = $_FILES["cust_pic"]["name"];
	$cust_pic2 = $_FILES["cust_pic3"]["name"];
	$cust_pic3 = $_FILES["cust_pic5"]["name"];

	if($race == 'Others'){
		$race = addslashes(strtoupper($_POST['race_other']));
	}
	
	//customer_address
	$address1 = addslashes(strtoupper($_POST['address1']));
	$address2 = addslashes(strtoupper($_POST['address2']));
	$address3 = addslashes(strtoupper($_POST['address3']));
	$postcode = $_POST['postcode'];
	$city = addslashes(strtoupper($_POST['city']));
	$state = addslashes(strtoupper($_POST['state']));
	$residence = addslashes($_POST['residence']);
	$year_stay = $_POST['year_stay'];
	$home_contact = $_POST['home_contact'];
	$mobile_contact = $_POST['mobile_contact'];
	$m_address1 = addslashes(strtoupper($_POST['m_address1']));
	$m_address2 = addslashes(strtoupper($_POST['m_address2']));
	$m_address3 = addslashes(strtoupper($_POST['m_address3']));
	$m_postcode = $_POST['m_postcode'];
	$m_city = addslashes(strtoupper($_POST['m_city']));
	$m_state = addslashes(strtoupper($_POST['m_state']));
	$m_residence = addslashes($_POST['m_residence']);
	$m_year_stay = $_POST['m_year_stay'];
	$m_month_stay = $_POST['m_month_stay'];
	$month_stay = $_POST['month_stay'];
	
	//customer_employment
	$company = addslashes(strtoupper($_POST['company']));
	$department = addslashes(strtoupper($_POST['department']));
	$position = addslashes(strtoupper($_POST['position']));
	$nature_business = addslashes(strtoupper($_POST['nature_business']));
	$c_address1 = addslashes(strtoupper($_POST['c_address1']));
	$c_address2 = addslashes(strtoupper($_POST['c_address2']));
	$c_address3 = addslashes(strtoupper($_POST['c_address3']));
	$c_postcode = $_POST['c_postcode'];
	$c_city = addslashes(strtoupper($_POST['c_city']));
	$c_state = addslashes(strtoupper($_POST['c_state']));
	$c_contactno = $_POST['c_contactno'];
	$c_ext = addslashes(strtoupper($_POST['c_ext']));
	$c_yearworking = $_POST['c_yearworking'];
	$c_monthworking = $_POST['c_monthworking'];
	$c_workingtype = addslashes(strtoupper($_POST['c_workingtype']));
	$c_email = $_POST['email'];
	
	if($c_workingtype == 'others'){
		$c_workingtype = addslashes($_POST['other_wt']);
	}
	
	//customer_financial
	$basic_salary = $_POST['basic_salary'];
	$net_salary = $_POST['net_salary'];
	$total_cc = $_POST['total_cc'];
	$car_installment = $_POST['car_installment'];
	$house_installment = $_POST['house_installment'];
	$personal_loan = $_POST['personal_loan'];
	$bank_loan = $_POST['bank_loan'];
	$remarks = addslashes(strtoupper($_POST['remarks']));
	
	//customer_emergency
	$e_contactperson = addslashes(strtoupper($_POST['e_contactperson']));
	$e_relationship = addslashes(strtoupper($_POST['e_relationship']));
	$e_contactno = $_POST['e_contactno'];
	$e_officeno = $_POST['e_officeno'];
	
	
	//customer_spouse
	$s_name = addslashes(strtoupper($_POST['s_name']));
	$s_oldic = $_POST['s_oldic'];
	$s_nric = $_POST['s_nric'];
	$s_company = addslashes(strtoupper($_POST['s_company']));
	$s_workas = addslashes(strtoupper($_POST['s_workas']));
	$s_relationship = addslashes(strtoupper($_POST['s_relationship']));
	$s_officeno = $_POST['s_officeno'];
	$s_mobile = $_POST['s_mobile'];
	$s_other_ic = $_POST['s_other_ic'];
	$s_other_ic2 = $_POST['s_other_ic2'];
	$s_email = addslashes($_POST['s_email']);
	$s_workingtype = $_POST['s_workingtype'];
	$s_monthworking = $_POST['s_monthworking'];
	$s_yearworking = $_POST['s_yearworking'];

	$cust_apply_date = $_POST['cust_apply_date'];

	if($s_workingtype == 'others'){
		$s_workingtype = addslashes($_POST['s_other_wt']);
	}
		
	//update customer_details
	$update_details_q = mysql_query("UPDATE customer_details SET bis = '".$bis."',cust_apply_date = '".$cust_apply_date."', name = '".$name."', title = '".$title."', dob = '".$dob."', nationality = '".$nationality."', old_ic = '".$old_ic."', nric = '".$nric."', gender = '".$gender."', race = '".$race."', marital_status = '".$marital_status."', no_dependents = '".$no_dependents."', academic_qualification = '".$academic_qualification."', mother_name = '".$mother_name."', customercode1 = '".$customercode1."', customercode2 = '".$customercode2."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', recruitor = '".$recruitor."', others_ic = '".$others_ic."', `from` = '".$source."', others_ic2 = '".$others_ic2."' WHERE id = '".$customer_id."'");
	
	if($cust_pic != ''){
		$update_cp = mysql_query("UPDATE customer_details SET cust_pic = '".$cust_pic."' WHERE id = '".$customer_id."'");
		move_uploaded_file($_FILES["cust_pic"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic);
	}
	if($cust_pic2 != ''){
		$update_cp = mysql_query("UPDATE customer_details SET cust_pic2 = '".$cust_pic2."' WHERE id = '".$customer_id."'");
		move_uploaded_file($_FILES["cust_pic3"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic2);
	}
	if($cust_pic3 != ''){
		$update_cp = mysql_query("UPDATE customer_details SET cust_pic3 = '".$cust_pic3."' WHERE id = '".$customer_id."'");
		move_uploaded_file($_FILES["cust_pic5"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic3);
	}
	
	//update customer_address
	$update_address_q = mysql_query("UPDATE customer_address SET address1 = '".$address1."', address2 = '".$address2."', address3 = '".$address3."', postcode = '".$postcode."', city = '".$city."', state = '".$state."', residence = '".$residence."', year_stay = '".$year_stay."', home_contact = '".$home_contact."', mobile_contact = '".$mobile_contact."', m_address1 = '".$m_address1."', m_address2 = '".$m_address2."', m_address3 = '".$m_address3."', m_postcode = '".$m_postcode."', m_city = '".$m_city."', m_state = '".$m_state."', m_residence = '".$m_residence."', m_year_stay = '".$m_year_stay."', m_month_stay = '".$m_month_stay."', month_stay = '".$month_stay."' WHERE customer_id = '".$customer_id."'");
	
	//insert customer_employment
	$insert_employemt_q = mysql_query("INSERT INTO customer_employment SET customer_id = '".$customer_id."', company = '".$company."', department = '".$department."', position = '".$position."', nature_business = '".$nature_business."', c_address1 = '".$c_address1."', c_address2 = '".$c_address2."', c_address3 = '".$c_address3."', c_postcode = '".$c_postcode."', c_city = '".$c_city."', c_state = '".$c_state."', c_contactno = '".$c_contactno."', c_ext = '".$c_ext."', c_yearworking = '".$c_yearworking."', c_monthworking = '".$c_monthworking."', c_workingtype = '".$c_workingtype."', c_email = '".$c_email."'");
	
	//insert customer_financial
	$insert_financial_q = mysql_query("INSERT INTO customer_financial SET customer_id = '".$customer_id."', basic_salary = '".$basic_salary."', net_salary = '".$net_salary."', total_cc = '".$total_cc."', car_installment = '".$car_installment."', house_installment = '".$house_installment."', personal_loan = '".$personal_loan."', bank_loan = '".$bank_loan."', remarks = '".$remarks."'");
	
	//insert customer_emergency
	$insert_emergency_q = mysql_query("INSERT INTO customer_emergency SET customer_id = '".$customer_id."', e_contactperson = '".$e_contactperson."', e_relationship = '".$e_relationship."', e_contactno = '".$e_contactno."', e_officeno = '".$e_officeno."'");
	
	//insert customer_spouse
	$insert_spouse_q = mysql_query("INSERT INTO customer_spouse SET customer_id = '".$customer_id."', s_name = '".$s_name."', s_oldic = '".$s_oldic."', s_nric = '".$s_nric."', s_company = '".$s_company."', s_workas = '".$s_workas."', s_relationship = '".$s_relationship."', s_officeno = '".$s_officeno."', s_mobile = '".$s_mobile."', s_other_ic = '".$s_other_ic."', s_other_ic2 = '".$s_other_ic2."', s_email = '".$s_email."', s_workingtype = '".$s_workingtype."', s_monthworking = '".$s_monthworking."', s_yearworking = '".$s_yearworking."'");
	
	//customer_relative
	$ctr = $_POST['ctr'];
	for($i = 1; $i <= $ctr; $i++){
		$r_name = addslashes(strtoupper($_POST['r_name_'.$i]));
		$r_relationship = addslashes(strtoupper($_POST['r_relationship_'.$i]));
		$r_workas = addslashes(strtoupper($_POST['r_workas_'.$i]));
		$r_contact = $_POST['r_contact_'.$i];
		$r_address = addslashes(strtoupper($_POST['r_address_'.$i]));
		
		if($r_name != '')
		{
			//insert customer_relative
			$insert_relative_q = mysql_query("INSERT INTO customer_relative SET customer_id = '".$customer_id."', r_name = '".$r_name."', r_relationship = '".$r_relationship."', r_workas = '".$r_workas."', r_contact = '".$r_contact."', r_address = '".$r_address."'");
		}
	}
	
	//customer_account
	$a_bankbranch = addslashes(strtoupper($_POST['a_bankbranch']));
	$a_payday = addslashes($_POST['a_payday']);
	$a_name = addslashes(strtoupper($_POST['a_name']));
	$a_nric = $_POST['a_nric'];
	$a_date = $_POST['a_date'];
	$a_icfile = $_FILES["a_icfile"]["name"];
	$a_bankfile = $_FILES["a_bankfile"]["name"];
	$a_payslipfile = $_FILES["a_payslipfile"]["name"];
	$a_atmfile = $_FILES["a_atmfile"]["name"];
	$a_pinno = $_POST['a_pinno'];
	$a_housefile = $_FILES["a_housefile"]["name"];
	$a_landfile = $_FILES["a_landfile"]["name"];
	$a_shoplotfile = $_FILES["a_shoplotfile"]["name"];
	$a_lefthand = $_FILES["a_lefthand"]["name"];
	$a_righthand = $_FILES["a_righthand"]["name"];
	$a_remarks = addslashes(strtoupper($_POST['a_remarks']))." (".$_SESSION['login_name'].")";
	$a_bankname = addslashes(strtoupper($_POST['a_bankname']));
	$a_bankaccno = addslashes(strtoupper($_POST['a_bankaccno']));
	$transfer_accountbank = addslashes(strtoupper($_POST['transfer_accountbank']));
	$transfer_accountno = addslashes(strtoupper($_POST['transfer_accountno']));
	$transfer_accountholder = addslashes(strtoupper($_POST['transfer_accountholder']));
	$a_paymentdate = $_POST['a_paymentdate'];
	$internet_username = addslashes($_POST['internet_username']);
	$internet_password = addslashes($_POST['internet_password']);
	
	$insert_account_q = mysql_query("INSERT INTO customer_account SET customer_id = '".$customer_id."', a_bankbranch = '".$a_bankbranch."', a_payday = '".$a_payday."', a_paymentdate ='".$a_paymentdate."' , a_name = '".$a_name."', a_nric = '".$a_nric."', a_date = '".$a_date."', a_pinno = '".$a_pinno."', a_remarks = '".$a_remarks."', a_bankname = '".$a_bankname."', a_bankaccno = '".$a_bankaccno."', transfer_accountbank = '".$transfer_accountbank."', transfer_accountno = '".$transfer_accountno."', transfer_accountholder = '".$transfer_accountholder."', internet_username = '".$internet_username."', internet_password = '".$internet_password."'");
	//$insert_account_q = mysql_query("INSERT INTO customer_account SET customer_id = '".$customer_id."', a_bankbranch = '".$a_bankbranch."', a_payday = '".$a_payday."', a_name = '".$a_name."', a_nric = '".$a_nric."', a_date = '".$a_date."', a_icfile = '".$a_icfile."', a_bankfile = '".$a_bankfile."', a_payslipfile = '".$a_payslipfile."', a_atmfile = '".$a_atmfile."', a_pinno = '".$a_pinno."', a_housefile = '".$a_housefile."', a_landfile = '".$a_landfile."', a_shoplotfile = '".$a_shoplotfile."', a_lefthand = '".$a_lefthand."', a_righthand = '".$a_righthand."', a_bankname = '".$a_bankname."', a_bankaccno = '".$a_bankaccno."', a_remarks = '".$a_remarks."'");
	
	
	if (!file_exists('../files/customer/' . $branch_name . '/' .$customer_id)) {
		mkdir('../files/customer/' . $branch_name . '/' .$customer_id, 0777, true);
	}
	
	move_uploaded_file($_FILES["a_icfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_icfile);
	move_uploaded_file($_FILES["a_bankfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_bankfile);
	move_uploaded_file($_FILES["a_payslipfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_payslipfile);
	move_uploaded_file($_FILES["a_atmfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_atmfile);
	move_uploaded_file($_FILES["a_housefile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_housefile);
	move_uploaded_file($_FILES["a_landfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_landfile);
	move_uploaded_file($_FILES["a_shoplotfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' .$customer_id.'/'.$a_shoplotfile);
	move_uploaded_file($_FILES["a_lefthand"]["tmp_name"], '../files/customer/' . $branch_name . '/' .$customer_id.'/'.$a_lefthand);
	move_uploaded_file($_FILES["a_righthand"]["tmp_name"], '../files/customer/' . $branch_name . '/' .$customer_id.'/'.$a_righthand);
											  
	//customer_loanpackage
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
	$start_month = $_POST['start_date'];
	
	//new
	$loanpackagetype = $_POST['loanpackagetype'];
	$actual_loanamt = $_POST['hide_loanamt'];
	$prev_settlementdate = date('Y-m-d', strtotime($_POST['previous_settlement_date']));
	$prev_loancode = strtoupper($_POST['previous_loan_code']);	
	
	if($loan_type == 'Fixed Loan'){
		if($loan_package == 'NEW PACKAGE'){
			$loan_total = $loan_amount_month*$loan_period;
		}else{
			$loan_total = $loan_amount + $loan_interesttotal;
		}
	}else{
		$loan_total = $loan_amount;
	}
	
	$loanstatus = 'PENDING';

	if($loan_amount!='' && $loan_period!='' && $loan_pokok!=''){
		$check_exist_loanpackage = mysql_query("SELECT * FROM customer_loanpackage_draft WHERE customer_id = '".$customer_id."'");
		$check_exist_loanpackage_num = mysql_num_rows($check_exist_loanpackage);
		
		if($check_exist_loanpackage_num > 0){
			$update_loanpackage = mysql_query("UPDATE customer_loanpackage_draft SET 
														loan_package       = '".$loan_package."', 
														loan_code          = '".$loan_code."',
														loan_amount        = '".$loan_amount."', 
														loan_period        = '".$loan_period."', 
														loan_interest      = '".$loan_interest."', 
														loan_interesttotal = '".$loan_interesttotal."', 
														loan_total         = '".$loan_total."', 
														loan_type          = '".$loan_type."', 
														approval_date      = NOW(), 
														staff_id           = '".$_SESSION['taplogin_id']."', 
														staff_name         = '".$_SESSION['login_name']."', 
														branch_id          = '".$_SESSION['login_branchid']."', 
														branch_name        = '".$_SESSION['login_branch']."', 
														created_date       = NOW(), 
														loanpackagetype    = '".$loanpackagetype."', 
														actual_loanamt     = '".$actual_loanamt."', 
														loan_pokok         = '".$loan_pokok."', 
														start_month= '".$start_month."',
														settlement_period  = '".$loan_period."'
													WHERE
														customer_id = '".$customer_id."'	
												");

		}else {
			$insert_loanpackage = mysql_query("INSERT INTO 
													customer_loanpackage_draft 
												SET customer_id = '".$customer_id."', 
													loan_package = '".$loan_package."', 
													loan_code          = '".$loan_code."',
													loan_amount = '".$loan_amount."', 
													loan_period = '".$loan_period."', 
													loan_interest = '".$loan_interest."', 
													loan_interesttotal = '".$loan_interesttotal."', 
													loan_total = '".$loan_total."', 
													loan_type = '".$loan_type."', 
													loan_status = '".$loanstatus."', 
													apply_date = now(), 
													approval_date = now(), 
													staff_id = '".$_SESSION['taplogin_id']."', 
													staff_name = '".$_SESSION['login_name']."', 
													branch_id = '".$_SESSION['login_branchid']."', 
													branch_name = '".$_SESSION['login_branch']."', 
													created_date = now(), 
													loanpackagetype = '".$loanpackagetype."', 
													actual_loanamt = '".$actual_loanamt."', 
													loan_pokok = '".$loan_pokok."',
													start_month= '".$start_month."',
													settlement_period = '".$loan_period."'");
		}

		// var_dump("INSERT INTO 
		// 											customer_loanpackage_draft 
		// 										SET customer_id = '".$customer_id."', 
		// 											loan_package = '".$loan_package."', 
		// 											loan_amount = '".$loan_amount."', 
		// 											loan_period = '".$loan_period."', 
		// 											loan_interest = '".$loan_interest."', 
		// 											loan_interesttotal = '".$loan_interesttotal."', 
		// 											loan_total = '".$loan_total."', 
		// 											loan_type = '".$loan_type."', 
		// 											loan_status = '".$loanstatus."', 
		// 											loan_remarks = '".$loan_remarks."', 
		// 											apply_date = now(), 
		// 											approval_date = now(), 
		// 											staff_id = '".$_SESSION['taplogin_id']."', 
		// 											staff_name = '".$_SESSION['login_name']."', 
		// 											branch_id = '".$_SESSION['login_branchid']."', 
		// 											branch_name = '".$_SESSION['login_branch']."', 
		// 											created_date = now(), 
		// 											loanpackagetype = '".$loanpackagetype."', 
		// 											actual_loanamt = '".$actual_loanamt."', 
		// 											loan_pokok = '".$loan_pokok."',
		// 											settlement_period = '".$loan_period."'");
		// 											exit;
		// $msg .= "Customer information has been successfully saved!";
	
		// $_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		// echo "<script>window.location='pending_approval.php'</script>";

	}
	if($_POST['loan_code_monthly']!='' && $_POST['payout_amount']!= 0){

		$amount = $_POST['payout_amount'];
		$package_id = '32';
		$loan_code_monthly = strtoupper($_POST['loan_code_monthly']);
		$monthly_date = $_POST['monthly_date'];
		$month = $_POST['month'];
		$sd_type = $_POST['sd'];
			
		$check_exist_record_q = mysql_query("SELECT * FROM monthly_payment_record_draft WHERE customer_id = '".$customer_id."' AND status = 'PENDING'");
		$check_exist_record = mysql_num_rows($check_exist_record_q);
			
			if($check_exist_record > 0){
				$update_q = mysql_query("UPDATE monthly_payment_record_draft SET
												monthly_date   = '".$monthly_date."', 
												loan_code      = '".$loan_code_monthly."',
												payout_amount  = '".$amount."', 
												package_id     = '".$package_id."', 
												balance        = '".$amount."', 
												month          = '".$month."',
												status         = 'PENDING',
												payout_date    = '".$monthly_date."',
												sd             = '".$sd_type."',
												user_id        = '".$_SESSION['login_name']."', 
												branch_id      = '".$_SESSION['login_branchid']."', 
												branch_name    = '".$_SESSION['login_branch']."'
											WHERE
												customer_id = '".$customer_id."'
										");

			} else {
					//insert skim kutu
					$insert_q = mysql_query("INSERT INTO monthly_payment_record_draft 
											SET customer_id = '".$customer_id."', 
											monthly_date    = '".$monthly_date."', 
											loan_code       = '".$loan_code_monthly."',
											payout_amount   = '".$amount."', 
											package_id      = '".$package_id."', 
											balance         = '".$amount."', 
											month           = '".$month."',
											status          = 'PENDING',
											payout_date     = '".$monthly_date."',
											sd              = '".$sd_type."',
											user_id         = '".$_SESSION['login_name']."', 
											branch_id       = '".$_SESSION['login_branchid']."', 
											branch_name     = '".$_SESSION['login_branch']."'");

				// var_dump("INSERT INTO monthly_payment_record_draft 
				// 							SET customer_id = '".$customer_id."', 
				// 							monthly_date = '".$monthly_date."', 
				// 							loan_code      = '".$loan_code_monthly."',
				// 							payout_amount = '".$amount."', 
				// 							package_id = '".$package_id."', 
				// 							balance = '".$amount."', 
				// 							month = '".$month."',
				// 							status = 'PENDING',
				// 							payout_date = '".$monthly_date."',
				// 							user_id = '".$_SESSION['login_name']."', 
				// 							branch_id = '".$_SESSION['login_branchid']."', 
				// 							branch_name = '".$_SESSION['login_branch']."'");
				// exit;
			}

	}

		$msg .= "Customer information has been successfully saved!";
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='pending_approval.php'</script>";
	
}else if(isset($_POST['approve_cust'])){

	$customer_id = $_POST['customer_id'];
	
	//customer_details
	$bis = addslashes(strtoupper($_POST['bis']));
	$name = addslashes(strtoupper($_POST['name']));
	$title = addslashes(strtoupper($_POST['title']));
	if($_POST['dob'] != ''){
		$dob = date('Y-m-d',strtotime($_POST['dob']));
	}else{
		$dob = $_POST['dob'];
	}
	$nationality = addslashes($_POST['nationality']);
	$old_ic = addslashes($_POST['old_ic']);
	$nric = addslashes($_POST['nric']);
	$gender = addslashes($_POST['gender']);
	$source = isset($_POST['source']) ? addslashes(strtoupper($_POST['source'])) : '';
		
	$race = addslashes($_POST['race']);
	$marital_status = addslashes($_POST['marital_status']);
	$no_dependents = $_POST['no_dependents'];
	$academic_qualification = addslashes(strtoupper($_POST['academic_qualification']));
	$mother_name = addslashes(strtoupper($_POST['mother_name']));
	/*$customercode1 = $_POST['customercode1'];*/
	$customercode2 = $_POST['customercode2'];
	$customercode1 = '';
	$recruitor = $_POST['recruitor'];
	$others_ic = $_POST['others_ic'];
	$others_ic2 = $_POST['others_ic2'];
	$cust_pic = $_FILES["cust_pic"]["name"];
	$cust_pic2 = $_FILES["cust_pic3"]["name"];
	$cust_pic3 = $_FILES["cust_pic5"]["name"];

	if($race == 'Others'){
		$race = addslashes(strtoupper($_POST['race_other']));
	}
	
	//customer_address
	$address1 = addslashes(strtoupper($_POST['address1']));
	$address2 = addslashes(strtoupper($_POST['address2']));
	$address3 = addslashes(strtoupper($_POST['address3']));
	$postcode = $_POST['postcode'];
	$city = addslashes(strtoupper($_POST['city']));
	$state = addslashes(strtoupper($_POST['state']));
	$residence = addslashes($_POST['residence']);
	$year_stay = $_POST['year_stay'];
	$home_contact = $_POST['home_contact'];
	$mobile_contact = $_POST['mobile_contact'];
	$m_address1 = addslashes(strtoupper($_POST['m_address1']));
	$m_address2 = addslashes(strtoupper($_POST['m_address2']));
	$m_address3 = addslashes(strtoupper($_POST['m_address3']));
	$m_postcode = $_POST['m_postcode'];
	$m_city = addslashes(strtoupper($_POST['m_city']));
	$m_state = addslashes(strtoupper($_POST['m_state']));
	$m_residence = addslashes($_POST['m_residence']);
	$m_year_stay = $_POST['m_year_stay'];
	$m_month_stay = $_POST['m_month_stay'];
	$month_stay = $_POST['month_stay'];
	
	//customer_employment
	$company = addslashes(strtoupper($_POST['company']));
	$department = addslashes(strtoupper($_POST['department']));
	$position = addslashes(strtoupper($_POST['position']));
	$nature_business = addslashes(strtoupper($_POST['nature_business']));
	$c_address1 = addslashes(strtoupper($_POST['c_address1']));
	$c_address2 = addslashes(strtoupper($_POST['c_address2']));
	$c_address3 = addslashes(strtoupper($_POST['c_address3']));
	$c_postcode = $_POST['c_postcode'];
	$c_city = addslashes(strtoupper($_POST['c_city']));
	$c_state = addslashes(strtoupper($_POST['c_state']));
	$c_contactno = $_POST['c_contactno'];
	$c_ext = addslashes(strtoupper($_POST['c_ext']));
	$c_yearworking = $_POST['c_yearworking'];
	$c_monthworking = $_POST['c_monthworking'];
	$c_workingtype = addslashes(strtoupper($_POST['c_workingtype']));
	$c_email = $_POST['email'];
	
	if($c_workingtype == 'others'){
		$c_workingtype = addslashes($_POST['other_wt']);
	}
	
	//customer_financial
	$basic_salary = $_POST['basic_salary'];
	$net_salary = $_POST['net_salary'];
	$total_cc = $_POST['total_cc'];
	$car_installment = $_POST['car_installment'];
	$house_installment = $_POST['house_installment'];
	$personal_loan = $_POST['personal_loan'];
	$bank_loan = $_POST['bank_loan'];
	$remarks = addslashes(strtoupper($_POST['remarks']));
	
	//customer_emergency
	$e_contactperson = addslashes(strtoupper($_POST['e_contactperson']));
	$e_relationship = addslashes(strtoupper($_POST['e_relationship']));
	$e_contactno = $_POST['e_contactno'];
	$e_officeno = $_POST['e_officeno'];
	
	
	//customer_spouse
	$s_name = addslashes(strtoupper($_POST['s_name']));
	$s_oldic = $_POST['s_oldic'];
	$s_nric = $_POST['s_nric'];
	$s_company = addslashes(strtoupper($_POST['s_company']));
	$s_workas = addslashes(strtoupper($_POST['s_workas']));
	$s_relationship = addslashes(strtoupper($_POST['s_relationship']));
	$s_officeno = $_POST['s_officeno'];
	$s_mobile = $_POST['s_mobile'];
	$s_other_ic = $_POST['s_other_ic'];
	$s_other_ic2 = $_POST['s_other_ic2'];
	$s_email = addslashes($_POST['s_email']);
	$s_workingtype = $_POST['s_workingtype'];
	$s_monthworking = $_POST['s_monthworking'];
	$s_yearworking = $_POST['s_yearworking'];

	$cust_apply_date = $_POST['cust_apply_date'];

	if($s_workingtype == 'others'){
		$s_workingtype = addslashes($_POST['s_other_wt']);
	}
		
	//update customer_details
	$update_details_q = mysql_query("UPDATE customer_details SET bis = '".$bis."', `from` = '".$source."', cust_apply_date = '".$cust_apply_date."', name = '".$name."', title = '".$title."', dob = '".$dob."', nationality = '".$nationality."', old_ic = '".$old_ic."', nric = '".$nric."', gender = '".$gender."', race = '".$race."', marital_status = '".$marital_status."', no_dependents = '".$no_dependents."', academic_qualification = '".$academic_qualification."', mother_name = '".$mother_name."', customercode1 = '".$customercode1."', customercode2 = '".$customercode2."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', recruitor = '".$recruitor."', others_ic = '".$others_ic."', others_ic2 = '".$others_ic2."' WHERE id = '".$customer_id."'");
	
	if($cust_pic != ''){
		$update_cp = mysql_query("UPDATE customer_details SET cust_pic = '".$cust_pic."' WHERE id = '".$customer_id."'");
		move_uploaded_file($_FILES["cust_pic"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic);
	}
	if($cust_pic2 != ''){
		$update_cp = mysql_query("UPDATE customer_details SET cust_pic2 = '".$cust_pic2."' WHERE id = '".$customer_id."'");
		move_uploaded_file($_FILES["cust_pic3"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic2);
	}
	if($cust_pic3 != ''){
		$update_cp = mysql_query("UPDATE customer_details SET cust_pic3 = '".$cust_pic3."' WHERE id = '".$customer_id."'");
		move_uploaded_file($_FILES["cust_pic5"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$cust_pic3);
	}
	
	//update customer_address
	$update_address_q = mysql_query("UPDATE customer_address SET address1 = '".$address1."', address2 = '".$address2."', address3 = '".$address3."', postcode = '".$postcode."', city = '".$city."', state = '".$state."', residence = '".$residence."', year_stay = '".$year_stay."', home_contact = '".$home_contact."', mobile_contact = '".$mobile_contact."', m_address1 = '".$m_address1."', m_address2 = '".$m_address2."', m_address3 = '".$m_address3."', m_postcode = '".$m_postcode."', m_city = '".$m_city."', m_state = '".$m_state."', m_residence = '".$m_residence."', m_year_stay = '".$m_year_stay."', m_month_stay = '".$m_month_stay."', month_stay = '".$month_stay."' WHERE customer_id = '".$customer_id."'");
	
	//insert customer_employment
	$insert_employemt_q = mysql_query("INSERT INTO customer_employment SET customer_id = '".$customer_id."', company = '".$company."', department = '".$department."', position = '".$position."', nature_business = '".$nature_business."', c_address1 = '".$c_address1."', c_address2 = '".$c_address2."', c_address3 = '".$c_address3."', c_postcode = '".$c_postcode."', c_city = '".$c_city."', c_state = '".$c_state."', c_contactno = '".$c_contactno."', c_ext = '".$c_ext."', c_yearworking = '".$c_yearworking."', c_monthworking = '".$c_monthworking."', c_workingtype = '".$c_workingtype."', c_email = '".$c_email."'");
	
	//insert customer_financial
	$insert_financial_q = mysql_query("INSERT INTO customer_financial SET customer_id = '".$customer_id."', basic_salary = '".$basic_salary."', net_salary = '".$net_salary."', total_cc = '".$total_cc."', car_installment = '".$car_installment."', house_installment = '".$house_installment."', personal_loan = '".$personal_loan."', bank_loan = '".$bank_loan."', remarks = '".$remarks."'");
	
	//insert customer_emergency
	$insert_emergency_q = mysql_query("INSERT INTO customer_emergency SET customer_id = '".$customer_id."', e_contactperson = '".$e_contactperson."', e_relationship = '".$e_relationship."', e_contactno = '".$e_contactno."', e_officeno = '".$e_officeno."'");
	
	//insert customer_spouse
	$insert_spouse_q = mysql_query("INSERT INTO customer_spouse SET customer_id = '".$customer_id."', s_name = '".$s_name."', s_oldic = '".$s_oldic."', s_nric = '".$s_nric."', s_company = '".$s_company."', s_workas = '".$s_workas."', s_relationship = '".$s_relationship."', s_officeno = '".$s_officeno."', s_mobile = '".$s_mobile."', s_other_ic = '".$s_other_ic."', s_other_ic2 = '".$s_other_ic2."', s_email = '".$s_email."', s_workingtype = '".$s_workingtype."', s_monthworking = '".$s_monthworking."', s_yearworking = '".$s_yearworking."'");
	
	//customer_relative
	$ctr = $_POST['ctr'];
	for($i = 1; $i <= $ctr; $i++){
		$r_name = addslashes(strtoupper($_POST['r_name_'.$i]));
		$r_relationship = addslashes(strtoupper($_POST['r_relationship_'.$i]));
		$r_workas = addslashes(strtoupper($_POST['r_workas_'.$i]));
		$r_contact = $_POST['r_contact_'.$i];
		$r_address = addslashes(strtoupper($_POST['r_address_'.$i]));
		
		if($r_name != '')
		{
			//insert customer_relative
			$insert_relative_q = mysql_query("INSERT INTO customer_relative SET customer_id = '".$customer_id."', r_name = '".$r_name."', r_relationship = '".$r_relationship."', r_workas = '".$r_workas."', r_contact = '".$r_contact."', r_address = '".$r_address."'");
		}
	}
	
	//customer_account
	$a_bankbranch = addslashes(strtoupper($_POST['a_bankbranch']));
	$a_payday = addslashes($_POST['a_payday']);
	$a_name = addslashes(strtoupper($_POST['a_name']));
	$a_nric = $_POST['a_nric'];
	$a_date = $_POST['a_date'];
	$a_icfile = $_FILES["a_icfile"]["name"];
	$a_bankfile = $_FILES["a_bankfile"]["name"];
	$a_payslipfile = $_FILES["a_payslipfile"]["name"];
	$a_atmfile = $_FILES["a_atmfile"]["name"];
	$a_pinno = $_POST['a_pinno'];
	$a_housefile = $_FILES["a_housefile"]["name"];
	$a_landfile = $_FILES["a_landfile"]["name"];
	$a_shoplotfile = $_FILES["a_shoplotfile"]["name"];
	$a_lefthand = $_FILES["a_lefthand"]["name"];
	$a_righthand = $_FILES["a_righthand"]["name"];
	$a_remarks = addslashes(strtoupper($_POST['a_remarks']))." (".$_SESSION['login_name'].")";
	$a_bankname = addslashes(strtoupper($_POST['a_bankname']));
	$a_bankaccno = addslashes(strtoupper($_POST['a_bankaccno']));
	$transfer_accountbank = addslashes(strtoupper($_POST['transfer_accountbank']));
	$transfer_accountno = addslashes(strtoupper($_POST['transfer_accountno']));
	$transfer_accountholder = addslashes(strtoupper($_POST['transfer_accountholder']));
	$a_paymentdate = $_POST['a_paymentdate'];
	$internet_username = addslashes($_POST['internet_username']);
	$internet_password = addslashes($_POST['internet_password']);
	
	$insert_account_q = mysql_query("INSERT INTO customer_account SET customer_id = '".$customer_id."', a_bankbranch = '".$a_bankbranch."', a_payday = '".$a_payday."', a_paymentdate ='".$a_paymentdate."' , a_name = '".$a_name."', a_nric = '".$a_nric."', a_date = '".$a_date."', a_pinno = '".$a_pinno."', a_remarks = '".$a_remarks."', a_bankname = '".$a_bankname."', a_bankaccno = '".$a_bankaccno."', transfer_accountbank = '".$transfer_accountbank."', transfer_accountno = '".$transfer_accountno."', transfer_accountholder = '".$transfer_accountholder."', internet_username = '".$internet_username."', internet_password = '".$internet_password."'");
	//$insert_account_q = mysql_query("INSERT INTO customer_account SET customer_id = '".$customer_id."', a_bankbranch = '".$a_bankbranch."', a_payday = '".$a_payday."', a_name = '".$a_name."', a_nric = '".$a_nric."', a_date = '".$a_date."', a_icfile = '".$a_icfile."', a_bankfile = '".$a_bankfile."', a_payslipfile = '".$a_payslipfile."', a_atmfile = '".$a_atmfile."', a_pinno = '".$a_pinno."', a_housefile = '".$a_housefile."', a_landfile = '".$a_landfile."', a_shoplotfile = '".$a_shoplotfile."', a_lefthand = '".$a_lefthand."', a_righthand = '".$a_righthand."', a_bankname = '".$a_bankname."', a_bankaccno = '".$a_bankaccno."', a_remarks = '".$a_remarks."'");
	
	
	if (!file_exists('../files/customer/' . $branch_name . '/' .$customer_id)) {
		mkdir('../files/customer/' . $branch_name . '/' .$customer_id, 0777, true);
	}
	
	move_uploaded_file($_FILES["a_icfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_icfile);
	move_uploaded_file($_FILES["a_bankfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_bankfile);
	move_uploaded_file($_FILES["a_payslipfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_payslipfile);
	move_uploaded_file($_FILES["a_atmfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_atmfile);
	move_uploaded_file($_FILES["a_housefile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_housefile);
	move_uploaded_file($_FILES["a_landfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' . $customer_id.'/'.$a_landfile);
	move_uploaded_file($_FILES["a_shoplotfile"]["tmp_name"], '../files/customer/' . $branch_name . '/' .$customer_id.'/'.$a_shoplotfile);
	move_uploaded_file($_FILES["a_lefthand"]["tmp_name"], '../files/customer/' . $branch_name . '/' .$customer_id.'/'.$a_lefthand);
	move_uploaded_file($_FILES["a_righthand"]["tmp_name"], '../files/customer/' . $branch_name . '/' .$customer_id.'/'.$a_righthand);

	$customer_id = $_POST['customer_id'];

	//update customer_details
	$update_details_q = mysql_query("UPDATE customer_details 
										SET 
											customer_status = 'APPROVED' 
											WHERE id = '".$customer_id."'");
	

	//customer_loanpackage
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
	$start_month = $_POST['start_date'];
	
	//new
	$loanpackagetype = $_POST['loanpackagetype'];
	$actual_loanamt = $_POST['hide_loanamt'];
	$prev_settlementdate = date('Y-m-d', strtotime($_POST['previous_settlement_date']));
	$prev_loancode = strtoupper($_POST['previous_loan_code']);	
	
	if($loan_type == 'Fixed Loan'){
		if($loan_package == 'NEW PACKAGE'){
			$loan_total = $loan_amount_month*$loan_period;
		}else{
			$loan_total = $loan_amount + $loan_interesttotal;
		}
	}else{
		$loan_total = $loan_amount;
	}
	
	$success = 0;
	$loanstatus = 'Approved';
	
	if($loan_amount!='' && $loan_period!='' && $loan_pokok!=''){
		$check_exist_loanpackage = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$customercode2."' AND loan_code = '".$loan_code."'");
		$check_exist_loanpackage_num = mysql_num_rows($check_exist_loanpackage);
		
		if($check_exist_loanpackage_num > 0){
			$update_loanpackage = mysql_query("UPDATE customer_loanpackage SET 
														loan_package       = '".$loan_package."', 
														loan_code          = '".$loan_code."',
														loan_amount        = '".$loan_amount."', 
														loan_period        = '".$loan_period."', 
														loan_interest      = '".$loan_interest."', 
														loan_interesttotal = '".$loan_interesttotal."', 
														loan_total         = '".$loan_total."', 
														loan_type          = '".$loan_type."', 
														approval_date      = NOW(), 
														staff_id           = '".$_SESSION['taplogin_id']."', 
														staff_name         = '".$_SESSION['login_name']."', 
														branch_id          = '".$_SESSION['login_branchid']."', 
														branch_name        = '".$_SESSION['login_branch']."', 
														created_date       = NOW(), 
														loanpackagetype    = '".$loanpackagetype."', 
														actual_loanamt     = '".$actual_loanamt."', 
														loan_pokok         = '".$loan_pokok."', 
														start_month= '".$start_month."',
														settlement_period  = '".$loan_period."'
													WHERE
														customer_id = '".$customer_id."'");
		} else {
			$insert_loanpackage = mysql_query("INSERT INTO customer_loanpackage 
												SET customer_id = '".$customer_id."',
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
												start_month= '".$start_month."',
												staff_id = '".$_SESSION['taplogin_id']."', 
												staff_name = '".$_SESSION['login_name']."', 
												branch_id = '".$_SESSION['login_branchid']."', 
												branch_name = '".$_SESSION['login_branch']."', 
												created_date = now(), 
												loanpackagetype = '".$loanpackagetype."', 
												actual_loanamt = '".$actual_loanamt."', 
												prev_settlementdate = '".$prev_settlementdate."',
												prev_loancode = '".$prev_loancode."',
												loan_pokok = '".$loan_pokok."',
												settlement_period = '".$loan_period."'");
							// var_dump("INSERT INTO customer_loanpackage 
							// 					SET customer_id = '".$customer_id."',
							// 					loan_package = '".$loan_package."', 
							// 					loan_code = '".$loan_code."', 
							// 					loan_amount = '".$loan_amount."', 
							// 					loan_period = '".$loan_period."', 
							// 					loan_interest = '".$loan_interest."', 
							// 					loan_interesttotal = '".$loan_interesttotal."', 
							// 					loan_total = '".$loan_total."', 
							// 					loan_type = '".$loan_type."', 
							// 					loan_status = '".$loanstatus."', 
							// 					loan_remarks = '".$loan_remarks."', 
							// 					apply_date = now(), 
							// 					approval_date = now(), 
							// 					start_month= '".$start_month."',
							// 					staff_id = '".$_SESSION['taplogin_id']."', s
							// 					taff_name = '".$_SESSION['login_name']."', 
							// 					branch_id = '".$_SESSION['login_branchid']."', 
							// 					branch_name = '".$_SESSION['login_branch']."', 
							// 					created_date = now(), 
							// 					loanpackagetype = '".$loanpackagetype."', 
							// 					actual_loanamt = '".$actual_loanamt."', 
							// 					prev_settlementdate = '".$prev_settlementdate."',
							// 					prev_loancode = '".$prev_loancode."',
							// 					loan_pokok = '".$loan_pokok."',
							// 					settlement_period = '".$loan_period."'");
							// 					exit;

		}

		$success = 1;

		// $msg .= "Customer information has been successfully saved!";
	
		// $_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		// echo "<script>window.location='index.php'</script>";

	}
	if($_POST['loan_code_monthly']!='' &&  $_POST['payout_amount']!= 0){

		$amount = $_POST['payout_amount'];
		$package_id = '32';
		$loan_code_monthly = strtoupper($_POST['loan_code_monthly']);
		$monthly_date = $_POST['monthly_date'];
		$month = $_POST['month'];
		$sd_type = $_POST['sd'];
			
		$check_exist_record_q = mysql_query("SELECT * FROM monthly_payment_record WHERE customer_id = '".$customer_id."' AND status = 'PAID'");
		$check_exist_record = mysql_num_rows($check_exist_record_q);
		
		if($check_exist_record > 0)
		{
			$update_q = mysql_query("UPDATE monthly_payment_record SET
												monthly_date   = '".$monthly_date."', 
												loan_code      = '".$loan_code_monthly."',
												payout_amount  = '".$amount."', 
												package_id     = '".$package_id."', 
												balance        = '".$amount."', 
												month          = '".$month."',
												status         = 'PAID',
												payout_date    = '".$monthly_date."',
												sd             = '".$sd_type."',
												user_id        = '".$_SESSION['login_name']."', 
												branch_id      = '".$_SESSION['login_branchid']."', 
												branch_name    = '".$_SESSION['login_branch']."'
											WHERE
												customer_id = '".$customer_id."'");
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
										sd             = '".$sd_type."',
										user_id = '".$_SESSION['login_name']."', 
										branch_id = '".$_SESSION['login_branchid']."', 
										branch_name = '".$_SESSION['login_branch']."'");

			// var_dump("INSERT INTO monthly_payment_record 
			// 							SET customer_id = '".$customer_id."', 
			// 							monthly_date = '".$monthly_date."', 
			// 							payout_amount = '".$amount."', 
			// 							package_id = '".$package_id."', 
			// 							loan_code = '".$loan_code_monthly."', 
			// 							balance = '".$amount."', 
			// 							month = '".$month."',
			// 							status = 'PAID',
			// 							payout_date = '".$monthly_date."',
			// 							user_id = '".$_SESSION['login_name']."', 
			// 							branch_id = '".$_SESSION['login_branchid']."', 
			// 							branch_name = '".$_SESSION['login_branch']."'");
			// 							exit;
		}
		$success = 1;
	}

	if ($success = 1){
		$msg .= "Customer information has been successfully saved!";
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='index.php'</script>";
	}else {
		$msg .= "Something went wrong. Please Try Again!";
	
		$_SESSION['msg'] = "<div class='danger'>".$msg."</div>";
		echo "<script>window.location='index.php'</script>";
	}
	
}
?>