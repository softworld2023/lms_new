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

if($_POST['action'] == 'update_amount')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	
	//update
	$update_q = mysql_query("UPDATE loan_scheme SET initial_amount = '".$amount."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if(isset($_POST['update']))
{
	$nama = $_POST['nama'];
	$postal_address = $_POST['postal_address'];
	$no_ssm = $_POST['no_ssm'];
	$alamat_operasi = $_POST['alamat_operasi'];
	$poskod = $_POST['poskod'];
	$bandar = $_POST['bandar'];
	$no_telephone = $_POST['no_telephone'];
	$telefon_bimbit = $_POST['telefon_bimbit'];
	$email = $_POST['email'];
	$catatan = $_POST['catatan'];
	$jenis_organisasi = $_POST['jenis_organisasi'];
	$nama_pengurus = $_POST['nama_pengurus'];
	$no_rujukan = $_POST['no_rujukan'];
	$no_lesen = $_POST['no_lesen'];
	$no_permit = $_POST['no_permit'];
	$tamat_tempoh = $_POST['tamat_tempoh'];
	$tarikh_mula = $_POST['tarikh_mula'];
	$tarikh_tamat = $_POST['tarikh_tamat'];
	$no_kp = $_POST['no_kp'];
	$alamat_rumah = $_POST['alamat_rumah'];
	$favourite_city = $_POST['favourite_city'];
	$password = $_POST['password'];
	$cl_tarikh1 = $_POST['cl_tarikh1'];
	$cl_tarikh2 = $_POST['cl_tarikh2'];
	$cl_tarikh3 = $_POST['cl_tarikh3'];
	$cl_year = $_POST['cl_year'];

	
	//update
	$update_q = mysql_query("UPDATE profile_kpkt SET nama = '".$nama."', postal_address = '".$postal_address."', no_ssm = '".$no_ssm."', alamat_operasi = '".$alamat_operasi."', poskod = '".$poskod."', bandar = '".$bandar."', no_telephone = '".$no_telephone."', telefon_bimbit = '".$telefon_bimbit."', email = '".$email."', catatan = '".$catatan."', jenis_organisasi = '".$jenis_organisasi."' , nama_pengurus = '".$nama_pengurus."' , no_rujukan = '".$no_rujukan."', no_lesen = '".$no_lesen."' , no_permit = '".$no_permit."' , tamat_tempoh = '".$tamat_tempoh."', tarikh_mula = '".$tarikh_mula."', tarikh_tamat = '".$tarikh_tamat."', no_kp = '".$no_kp."' , alamat_rumah = '".$alamat_rumah."', favourite_city = '".$favourite_city."' ,password = '".$password."' , cl_tarikh1 = '".$cl_tarikh1."', cl_tarikh2 = '".$cl_tarikh2."', cl_tarikh3 = '".$cl_tarikh3."', cl_year = '".$cl_year."' WHERE id = '1'");
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Profile details has been updated.</div>";
		echo "<script>window.location='index.php'</script>";
	}
}else
if($_POST['action'] == 'update_interest')
{
	$pay_month = $_POST['pay_month'];
	$interest_paid_out = str_replace(',','',$_POST['interest_paid_out']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);


	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET interest_paid_out = '".$interest_paid_out."', opening_balance = '".$opening_balance_k."'  WHERE pay_month = '".$pay_month."'");

}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET interest_paid_out = '".$interest_paid_out."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if($_POST['action'] == 'update_capital')
{
	$pay_month = $_POST['pay_month'];
	$return_capital = str_replace(',','',$_POST['return_capital']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET return_capital = '".$return_capital."', opening_balance = '".$opening_balance_k."'  WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET return_capital = '".$return_capital."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if($_POST['action'] == 'update_capital_in')
{
	$pay_month = $_POST['pay_month'];
	$capital_in = str_replace(',','',$_POST['capital_in']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET capital_in = '".$capital_in."', opening_balance = '".$opening_balance_k."' WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET capital_in = '".$capital_in."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
//11032022
else
if($_POST['action'] == 'update_xingfeng')
{
	$pay_month = $_POST['pay_month'];
	$xingfeng = $_POST['xingfeng'];
	$loan_code = $_POST['loan_code'];

	$sql = mysql_query("SELECT * FROM testing WHERE pay_month = '".$pay_month."' AND loan_code = '".$loan_code."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE testing SET xingfeng = '".$xingfeng."' WHERE pay_month = '".$pay_month."'AND loan_code = '".$loan_code."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO testing SET xingfeng = '".$xingfeng."', pay_month = '".$pay_month."',loan_code = '".$loan_code."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_opening_balance')
{
	$pay_month = $_POST['pay_month'];
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET opening_balance = '".$opening_balance_k."' WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET opening_balance = '".$opening_balance_k."', pay_month = '".$pay_month."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_settle')
{
	$pay_month = $_POST['pay_month'];
	$settle_k = str_replace(',','',$_POST['settle_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET settle = '".$settle_k."' WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET settle = '".$settle_k."', pay_month = '".$pay_month."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_collected')
{
	$pay_month = $_POST['pay_month'];
	$collected_k = str_replace(',','',$_POST['collected_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET collected = '".$collected_k."' WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET collected = '".$collected_k."', pay_month = '".$pay_month."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_bad_debt')
{
	$pay_month = $_POST['pay_month'];
	$baddebt_k = str_replace(',','',$_POST['baddebt_k']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET baddebt = '".$baddebt_k."', opening_balance = '".$opening_balance_k."'  WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET baddebt = '".$baddebt_k."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_monthly')
{
	$pay_month = $_POST['pay_month'];
	$monthly_k = str_replace(',','',$_POST['monthly_k']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET monthly = '".$monthly_k."', opening_balance = '".$opening_balance_k."'  WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET monthly = '".$monthly_k."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_payout')
{
	$pay_month = $_POST['pay_month'];
	$payout_k = str_replace(',','',$_POST['payout_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET payout = '".$payout_k."' WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET payout = '".$payout_k."', pay_month = '".$pay_month."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_expenses')
{
	$pay_month = $_POST['pay_month'];
	$expenses_k = str_replace(',','',$_POST['expenses_k']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET expenses = '".$expenses_k."', opening_balance = '".$opening_balance_k."'  WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET expenses = '".$expenses_k."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}else
if($_POST['action'] == 'update_expenses2')
{
	$pay_month = $_POST['pay_month'];
	$expenses_k2 = str_replace(',','',$_POST['expenses_k2']);
	$opening_balance_k = str_replace(',','',$_POST['opening_balance_k']);
	
	$sql = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$pay_month."'");
	$result = mysql_num_rows($sql);

	if($result >0){
	//update
	$update_q = mysql_query("UPDATE instalment_balance SET expenses_2 = '".$expenses_k2."', opening_balance = '".$opening_balance_k."'  WHERE pay_month = '".$pay_month."'");
	}else
{
	//insert
	$insert_q = mysql_query("INSERT INTO instalment_balance SET expenses_2 = '".$expenses_k2."', pay_month = '".$pay_month."', opening_balance = '".$opening_balance_k."'");
}
	
	if($update_q)
	{
		// $_SESSION['msg'] = "<div class='success'>Initial Amount has been updated.</div>";
	}
}
else
if($_POST['action'] == 'update_statement')
{
	$loan_out_monthly = str_replace(',','',$_POST['loan_out_monthly']);
	$loan_out_10percent = str_replace(',','',$_POST['loan_out_10percent']);
	$loan_out_8percent = str_replace(',','',$_POST['loan_out_8percent']);
	$loan_out_6_2percent = str_replace(',','',$_POST['loan_out_6_2percent']);
	$loan_out_5_5percent = str_replace(',','',$_POST['loan_out_5_5percent']);
	$loan_out_5percent = str_replace(',','',$_POST['loan_out_5percent']);
	$interest_received_monthly = str_replace(',','',$_POST['interest_received_monthly']);
	$interest_received_10percent = str_replace(',','',$_POST['interest_received_10percent']);
	$interest_received_8percent = str_replace(',','',$_POST['interest_received_8percent']);
	$interest_received_6_2percent = str_replace(',','',$_POST['interest_received_6_2percent']);
	$interest_received_5_5percent = str_replace(',','',$_POST['interest_received_5_5percent']);
	$interest_received_5percent = str_replace(',','',$_POST['interest_received_5percent']);
	$capital_in = str_replace(',','',$_POST['capital_in']);
	$pay_out_interest = str_replace(',','',$_POST['pay_out_interest']);
	$expenses = str_replace(',','',$_POST['expenses']);
	$expenses_2 = str_replace(',','',$_POST['expenses_2']);
	$loan_return_monthly = str_replace(',','',$_POST['loan_return_monthly']);
	$loan_return_10percent = str_replace(',','',$_POST['loan_return_10percent']);
	$loan_return_8percent = str_replace(',','',$_POST['loan_return_8percent']);
	$loan_return_6_2percent = str_replace(',','',$_POST['loan_return_6_2percent']);
	$loan_return_5_5percent = str_replace(',','',$_POST['loan_return_5_5percent']);
	$loan_return_5percent = str_replace(',','',$_POST['loan_return_5percent']);
	$bad_debt_monthly = str_replace(',','',$_POST['bad_debt_monthly']);
	$bad_debt_10percent = str_replace(',','',$_POST['bad_debt_10percent']);
	$bad_debt_8percent = str_replace(',','',$_POST['bad_debt_8percent']);
	$bad_debt_6_2percent = str_replace(',','',$_POST['bad_debt_6_2percent']);
	$bad_debt_5_5percent = str_replace(',','',$_POST['bad_debt_5_5percent']);
	$bad_debt_5percent = str_replace(',','',$_POST['bad_debt_5percent']);
	$bad_debt_collected = str_replace(',','',$_POST['bad_debt_collected']);
	$monthly_profit = str_replace(',','',$_POST['monthly_profit']);
	$total_profit = str_replace(',','',$_POST['total_profit']);
	$balance_in_hand = str_replace(',','',$_POST['balance_in_hand']);
	$customer = str_replace(',','',$_POST['customer']);
	$cash_balance = str_replace(',','',$_POST['cash_balance']);
	$bf_payout_month = $_POST['bf_payout_month'];

	// check if statement row for $bf_payout_month is created or not in database, insert new row for it if it's not exists yet
	$sql = "SELECT id FROM statement WHERE month_year = '$bf_payout_month'";
	$query = mysql_query($sql);
	if (mysql_num_rows($query) == 0) {
		$sql = "INSERT INTO statement SET month_year = '$bf_payout_month'";
		mysql_query($sql);
	}
	
	$update_q = mysql_query("UPDATE statement SET loan_out_monthly = '".$loan_out_monthly."',
												  loan_out_10percent = '".$loan_out_10percent."',
												  loan_out_8percent = '".$loan_out_8percent."',
												  loan_out_6_2percent = '".$loan_out_6_2percent."',
												  loan_out_5_5percent = '".$loan_out_5_5percent."',
												  loan_out_5percent = '".$loan_out_5percent."',
												  interest_received_monthly = '".$interest_received_monthly."',
												  interest_received_10percent = '".$interest_received_10percent."',
												  interest_received_8percent = '".$interest_received_8percent."',
												  interest_received_6_2percent = '".$interest_received_6_2percent."',
												  interest_received_5_5percent = '".$interest_received_5_5percent."',
												  interest_received_5percent = '".$interest_received_5percent."',
												  capital_in = '".$capital_in."',
												  pay_out_interest = '".$pay_out_interest."',
												  expenses = '".$expenses."',
												  expenses_2 = '".$expenses_2."',
												  loan_return_monthly = '".$loan_return_monthly."',
												  loan_return_10percent = '".$loan_return_10percent."',
												  loan_return_8percent = '".$loan_return_8percent."',
												  loan_return_6_2percent = '".$loan_return_6_2percent."',
												  loan_return_5_5percent = '".$loan_return_5_5percent."',
												  loan_return_5percent = '".$loan_return_5percent."',
												  bad_debt_monthly = '".$bad_debt_monthly."',
												  bad_debt_10percent = '".$bad_debt_10percent."',
												  bad_debt_8percent = '".$bad_debt_8percent."',
												  bad_debt_6_2percent = '".$bad_debt_6_2percent."',
												  bad_debt_5_5percent = '".$bad_debt_5_5percent."',
												  bad_debt_5percent = '".$bad_debt_5percent."',
												  bad_debt_collected = '".$bad_debt_collected."',
												  monthly_profit = '".$monthly_profit."',
												  balance_in_hand = '".$balance_in_hand."',
												  customer = '".$customer."',
												  total_profit = '".$total_profit."',
												  cash_balance = '".$cash_balance."'
												  WHERE month_year = '".$bf_payout_month."'");
	
}
if($_POST['action'] == 'update_statement_2022')
{

	$loan_out_monthly = str_replace(',','',$_POST['loan_out_monthly']);
	$loan_out_10percent = str_replace(',','',$_POST['loan_out_10percent']);
	$loan_out_8percent = str_replace(',','',$_POST['loan_out_8percent']);
	$loan_out_6_2percent = str_replace(',','',$_POST['loan_out_6_2percent']);
	$loan_out_5_5percent = str_replace(',','',$_POST['loan_out_5_5percent']);
	$loan_out_5percent = str_replace(',','',$_POST['loan_out_5percent']);
	$interest_received_monthly = str_replace(',','',$_POST['interest_received_monthly']);
	$interest_received_10percent = str_replace(',','',$_POST['interest_received_10percent']);
	$interest_received_8percent = str_replace(',','',$_POST['interest_received_8percent']);
	$interest_received_6_2percent = str_replace(',','',$_POST['interest_received_6_2percent']);
	$interest_received_5_5percent = str_replace(',','',$_POST['interest_received_5_5percent']);
	$interest_received_5percent = str_replace(',','',$_POST['interest_received_5percent']);
	$capital_in = str_replace(',','',$_POST['capital_in']);
	$pay_out_interest = str_replace(',','',$_POST['pay_out_interest']);
	$expenses = str_replace(',','',$_POST['expenses']);
	$expenses_2 = str_replace(',','',$_POST['expenses_2']);
	$loan_return_monthly = str_replace(',','',$_POST['loan_return_monthly']);
	$loan_return_10percent = str_replace(',','',$_POST['loan_return_10percent']);
	$loan_return_8percent = str_replace(',','',$_POST['loan_return_8percent']);
	$loan_return_6_2percent = str_replace(',','',$_POST['loan_return_6_2percent']);
	$loan_return_5_5percent = str_replace(',','',$_POST['loan_return_5_5percent']);
	$loan_return_5percent = str_replace(',','',$_POST['loan_return_5percent']);
	$bad_debt_monthly = str_replace(',','',$_POST['bad_debt_monthly']);
	$bad_debt_10percent = str_replace(',','',$_POST['bad_debt_10percent']);
	$bad_debt_8percent = str_replace(',','',$_POST['bad_debt_8percent']);
	$bad_debt_6_2percent = str_replace(',','',$_POST['bad_debt_6_2percent']);
	$bad_debt_5_5percent = str_replace(',','',$_POST['bad_debt_5_5percent']);
	$bad_debt_5percent = str_replace(',','',$_POST['bad_debt_5percent']);
	$bad_debt_collected = str_replace(',','',$_POST['bad_debt_collected']);
	$monthly_profit = str_replace(',','',$_POST['monthly_profit']);
	$total_profit = str_replace(',','',$_POST['total_profit']);
	$balance_in_hand = str_replace(',','',$_POST['balance_in_hand']);
	$customer = str_replace(',','',$_POST['customer']);
	$cash_balance = str_replace(',','',$_POST['cash_balance']);
	$bf_payout_month = $_POST['bf_payout_month'];
	$counter_mth = $_POST['counter_mth'];
	
	$update_q = mysql_query("UPDATE statement_2022 SET loan_out_monthly = '".$loan_out_monthly."',
												  loan_out_10percent = '".$loan_out_10percent."',
												  loan_out_8percent = '".$loan_out_8percent."',
												  loan_out_6_2percent = '".$loan_out_6_2percent."',
												  loan_out_5_5percent = '".$loan_out_5_5percent."',
												  loan_out_5percent = '".$loan_out_5percent."',
												  interest_received_monthly = '".$interest_received_monthly."',
												  interest_received_10percent = '".$interest_received_10percent."',
												  interest_received_8percent = '".$interest_received_8percent."',
												  interest_received_6_2percent = '".$interest_received_6_2percent."',
												  interest_received_5_5percent = '".$interest_received_5_5percent."',
												  interest_received_5percent = '".$interest_received_5percent."',
												  capital_in = '".$capital_in."',
												  pay_out_interest = '".$pay_out_interest."',
												  expenses = '".$expenses."',
												  expenses_2 = '".$expenses_2."',
												  loan_return_monthly = '".$loan_return_monthly."',
												  loan_return_10percent = '".$loan_return_10percent."',
												  loan_return_8percent = '".$loan_return_8percent."',
												  loan_return_6_2percent = '".$loan_return_6_2percent."',
												  loan_return_5_5percent = '".$loan_return_5_5percent."',
												  loan_return_5percent = '".$loan_return_5percent."',
												  bad_debt_monthly = '".$bad_debt_monthly."',
												  bad_debt_10percent = '".$bad_debt_10percent."',
												  bad_debt_8percent = '".$bad_debt_8percent."',
												  bad_debt_6_2percent = '".$bad_debt_6_2percent."',
												  bad_debt_5_5percent = '".$bad_debt_5_5percent."',
												  bad_debt_5percent = '".$bad_debt_5percent."',
												  bad_debt_collected = '".$bad_debt_collected."',
												  monthly_profit = '".$monthly_profit."',
												  balance_in_hand = '".$balance_in_hand."',
												  customer = '".$customer."',
												  total_profit = '".$total_profit."',
												  cash_balance = '".$cash_balance."'
												  WHERE id = '".$counter_mth."'");
	
} else if ($_POST['action'] == 'update_statement_2023') {
	$loan_out_monthly = str_replace(',','',$_POST['loan_out_monthly']);
	$loan_out_10percent = str_replace(',','',$_POST['loan_out_10percent']);
	$loan_out_8percent = str_replace(',','',$_POST['loan_out_8percent']);
	$loan_out_6_2percent = str_replace(',','',$_POST['loan_out_6_2percent']);
	$loan_out_5_5percent = str_replace(',','',$_POST['loan_out_5_5percent']);
	$loan_out_5percent = str_replace(',','',$_POST['loan_out_5percent']);
	$interest_received_monthly = str_replace(',','',$_POST['interest_received_monthly']);
	$interest_received_10percent = str_replace(',','',$_POST['interest_received_10percent']);
	$interest_received_8percent = str_replace(',','',$_POST['interest_received_8percent']);
	$interest_received_6_2percent = str_replace(',','',$_POST['interest_received_6_2percent']);
	$interest_received_5_5percent = str_replace(',','',$_POST['interest_received_5_5percent']);
	$interest_received_5percent = str_replace(',','',$_POST['interest_received_5percent']);
	$capital_in = str_replace(',','',$_POST['capital_in']);
	$pay_out_interest = str_replace(',','',$_POST['pay_out_interest']);
	$expenses = str_replace(',','',$_POST['expenses']);
	$expenses_2 = str_replace(',','',$_POST['expenses_2']);
	$loan_return_monthly = str_replace(',','',$_POST['loan_return_monthly']);
	$loan_return_10percent = str_replace(',','',$_POST['loan_return_10percent']);
	$loan_return_8percent = str_replace(',','',$_POST['loan_return_8percent']);
	$loan_return_6_2percent = str_replace(',','',$_POST['loan_return_6_2percent']);
	$loan_return_5_5percent = str_replace(',','',$_POST['loan_return_5_5percent']);
	$loan_return_5percent = str_replace(',','',$_POST['loan_return_5percent']);
	$bad_debt_monthly = str_replace(',','',$_POST['bad_debt_monthly']);
	$bad_debt_10percent = str_replace(',','',$_POST['bad_debt_10percent']);
	$bad_debt_8percent = str_replace(',','',$_POST['bad_debt_8percent']);
	$bad_debt_6_2percent = str_replace(',','',$_POST['bad_debt_6_2percent']);
	$bad_debt_5_5percent = str_replace(',','',$_POST['bad_debt_5_5percent']);
	$bad_debt_5percent = str_replace(',','',$_POST['bad_debt_5percent']);
	$bad_debt_collected = str_replace(',','',$_POST['bad_debt_collected']);
	$monthly_profit = str_replace(',','',$_POST['monthly_profit']);
	$total_profit = str_replace(',','',$_POST['total_profit']);
	$balance_in_hand = str_replace(',','',$_POST['balance_in_hand']);
	$customer = str_replace(',','',$_POST['customer']);
	$cash_balance = str_replace(',','',$_POST['cash_balance']);
	$bf_payout_month = $_POST['bf_payout_month'];
	$counter_mth = $_POST['counter_mth'];
	
	$update_q = mysql_query("UPDATE statement_2023 SET loan_out_monthly = '".$loan_out_monthly."',
												  loan_out_10percent = '".$loan_out_10percent."',
												  loan_out_8percent = '".$loan_out_8percent."',
												  loan_out_6_2percent = '".$loan_out_6_2percent."',
												  loan_out_5_5percent = '".$loan_out_5_5percent."',
												  loan_out_5percent = '".$loan_out_5percent."',
												  interest_received_monthly = '".$interest_received_monthly."',
												  interest_received_10percent = '".$interest_received_10percent."',
												  interest_received_8percent = '".$interest_received_8percent."',
												  interest_received_6_2percent = '".$interest_received_6_2percent."',
												  interest_received_5_5percent = '".$interest_received_5_5percent."',
												  interest_received_5percent = '".$interest_received_5percent."',
												  capital_in = '".$capital_in."',
												  pay_out_interest = '".$pay_out_interest."',
												  expenses = '".$expenses."',
												  expenses_2 = '".$expenses_2."',
												  loan_return_monthly = '".$loan_return_monthly."',
												  loan_return_10percent = '".$loan_return_10percent."',
												  loan_return_8percent = '".$loan_return_8percent."',
												  loan_return_6_2percent = '".$loan_return_6_2percent."',
												  loan_return_5_5percent = '".$loan_return_5_5percent."',
												  loan_return_5percent = '".$loan_return_5percent."',
												  bad_debt_monthly = '".$bad_debt_monthly."',
												  bad_debt_10percent = '".$bad_debt_10percent."',
												  bad_debt_8percent = '".$bad_debt_8percent."',
												  bad_debt_6_2percent = '".$bad_debt_6_2percent."',
												  bad_debt_5_5percent = '".$bad_debt_5_5percent."',
												  bad_debt_5percent = '".$bad_debt_5percent."',
												  bad_debt_collected = '".$bad_debt_collected."',
												  monthly_profit = '".$monthly_profit."',
												  balance_in_hand = '".$balance_in_hand."',
												  customer = '".$customer."',
												  total_profit = '".$total_profit."',
												  cash_balance = '".$cash_balance."'
												  WHERE id = '".$counter_mth."'");
}
else
if($_POST['action'] == 'delete_lateint')
{
	$id = $_POST['id'];
	$lid = $_POST['lid'];
	$amount = $_POST['amount'];
	$package_id = $_POST['package_id'];
	$date = $_POST['date'];
	$payment_date = $_POST['payment_date'];
	$rcpmth = date('Y-m', strtotime($date));
	
	
	
	//late interest payment details
	$lipd_q = mysql_query("SELECT * FROM late_interest_payment_details WHERE id = '".$id."'");
	$lipd = mysql_fetch_assoc($lipd_q);
	
	//balance rec
	$br_q = mysql_query("SELECT * FROM balance_rec WHERE id = '".$id."'");
	$br = mysql_fetch_assoc($br_q);
	
	//cashbook
	$cb_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."'");
	$cb = mysql_fetch_assoc($cb_q);

	//late interest record
	$lir_q = mysql_query("SELECT * FROM late_interest_record WHERE id = '".$lid."'");
	$lir = mysql_fetch_assoc($lir_q);
	
	//cust info
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$lir['customer_id']."'");
	$cust = mysql_fetch_assoc($cust_q);
	
	//check package receipt(update balance at late interest record)
	$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
	$get_rt = mysql_fetch_assoc($rt_q);
	
	$trans = "LATE INT - ".$cust['name']." - ".$lir['loan_code'];

	$balance = $lir['balance'] + $amount;
	
	if($balance < 0)
	{
		$balance = 0;
	}	
		
		$delete_b2q = mysql_query("DELETE FROM balance_rec 
									WHERE customer_loanid = '0' 
									AND package_id = '".$lir['package_id']."' 
									AND interest = '".$lipd['amount']."' 
									AND bal_date LIKE '%".$lipd['payment_date']."%' 
									AND branch_id = '".$lir['branch_id']."'");
		
	if($delete_b2q)
	{
		//delete from cashbook
		$delete_c_q = mysql_query("DELETE FROM cashbook
									WHERE table_id = '".$lipd['lid']."' 
									AND amount = '".$lipd['amount']."' 
									AND date LIKE '%".$lipd['payment_date']."%' 
									AND type = 'RECEIVED2' ");
		
		//delete payment record
	$delete_q = mysql_query("DELETE FROM late_interest_payment_details 
								WHERE id = '".$id."'");
	
		if($delete_q)
		{
			//update late interest record
			$update = mysql_query("UPDATE late_interest_record 
									SET balance = '".$balance."' 
									WHERE id = '".$lid."'");
						
						$_SESSION['msg'] = "<div class='success'>Payment has been successfully deleted from the database.</div>";
						echo "<script>window.parent.location='paybaddebt.php?id=".$lid."'</script>";
		}else{
			$_SESSION['msg'] = "<div class='success'>Payment has been successfully deleted from the database.</div>";
				echo "<script>window.parent.location='paybaddebt.php?id=".$lid."'</script>";
			}
	
	}
} else if ($_POST['action'] == 'update_statement_capital') {
	$net_capital = str_replace(',', '', $_POST['net_capital']);
	$month_year = $_POST['month_year'];

	$sql = "UPDATE statement SET
				capital_in = '$net_capital'
			WHERE month_year = '$month_year'
			";
	mysql_query($sql);
}

}?>