<?php 
session_start();
include("../include/dbconnection.php");
//require_once('../config.php');

mysql_query("SET TIME_ZONE = '+08:00'");

/**	Error reporting		**/
error_reporting(E_ALL);
/**	Include path		**/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');  

include 'PHPExcel/IOFactory.php';

/**	Load the quadratic equation solver worksheet into memory			**/
$temp = $_FILES["file"]["tmp_name"];
$objPHPExcel = PHPExcel_IOFactory::load($temp);
$i=2;


do
{
	$customercode = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();
	$code = $objPHPExcel->getActiveSheet()->getCell("B$i")->getValue();
	$name = $objPHPExcel->getActiveSheet()->getCell("C$i")->getValue();
	$nric = $objPHPExcel->getActiveSheet()->getCell("D$i")->getValue();
	$salary = $objPHPExcel->getActiveSheet()->getCell("E$i")->getValue();
	$company = $objPHPExcel->getActiveSheet()->getCell("F$i")->getValue();
	$loan_amount = $objPHPExcel->getActiveSheet()->getCell("G$i")->getValue();
	$total_loan = $objPHPExcel->getActiveSheet()->getCell("H$i")->getValue();
	$pokok = $objPHPExcel->getActiveSheet()->getCell("I$i")->getValue();
	$interest = $objPHPExcel->getActiveSheet()->getCell("J$i")->getValue();
	$monthlyamount = $objPHPExcel->getActiveSheet()->getCell("K$i")->getValue();
	$package = $objPHPExcel->getActiveSheet()->getCell("L$i")->getValue();
	$lperiod = $objPHPExcel->getActiveSheet()->getCell("M$i")->getValue();
	$ltype = $objPHPExcel->getActiveSheet()->getCell("N$i")->getValue();
	$balance = $objPHPExcel->getActiveSheet()->getCell("O$i")->getValue();
	$npd = $objPHPExcel->getActiveSheet()->getCell("P$i")->getValue();
	$accremark = $objPHPExcel->getActiveSheet()->getCell("Q$i")->getValue();
	$bank = $objPHPExcel->getActiveSheet()->getCell("R$i")->getValue();
	$paydate = $objPHPExcel->getActiveSheet()->getCell("S$i")->getValue();

	//check customer
	$check_nric = mysql_query("SELECT * FROM customer_details WHERE nric = '".$nric."'");
	if(mysql_num_rows($check_nric))
	{
		$cust = mysql_fetch_assoc($check_nric);
		
		$cust_id = $cust['id'];
	}else
	{
		if($nric != '')
		{
			//new cust
			$insert_cust= mysql_query("INSERT INTO customer_details SET name = '".addslashes($name)."', customercode2 = '".$customercode."', nric = '".$nric."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			$cust_id = mysql_insert_id();
			
			//company
			$comp_q = mysql_query("INSERT INTO customer_employment SET customer_id = '".$cust_id."', company = '".$company."'");
			
			//salary
			$salary_q = mysql_query("INSERT INTO customer_financial SET customer_id = '".$cust_id."', net_salary = '".$salary."'");
			
			$customer_add_q = mysql_query("INSERT INTO customer_address SET customer_id = '".$cust_id."'");
			$customer_acc = mysql_query("INSERT INTO customer_account SET customer_id = '".$cust_id."', a_payday = '".$paydate."', a_bankbranch = '".$bank."', a_remarks = '".$accremark."'");
			$customer_emergency = mysql_query("INSERT INTO customer_emergency SET customer_id = '".$cust_id."'");
			$customer_relative = mysql_query("INSERT INTO customer_relative SET customer_id = '".$cust_id."'");
			$customer_spouse = mysql_query("INSERT INTO customer_spouse SET customer_id = '".$cust_id."'");
		}
	}
	
	//customer_loan_package
	$insert_clp = mysql_query("INSERT INTO customer_loanpackage SET customer_id = '".$cust_id."', loan_code = '".$code."', loan_package = '".$package."', loan_total = '".$total_loan."', loan_amount = '".$loan_amount."', loan_interesttotal = '".$interest."', loan_period = '".$lperiod."', loan_status = 'Paid', loan_type = '".$ltype."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");		
	
	if($insert_clp)
	{
		$lp_id = mysql_insert_id();
		
		$package1 = mysql_real_escape_string($package);
		
		$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$package1."'");
		$get_pack = mysql_fetch_assoc($package_q);
		
		$insert_payment = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lp_id."', int_percent = '".$get_pack['interest']."', month = '1', monthly = '".$monthlyamount."', pokok = '".$pokok."', interest = '".$interest."', balance = '".$balance."', package_id = '".$get_pack['id']."', next_paymentdate = '".$npd."', receipt_no = '".$code."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
	}
	//sql end
	$i++;
	$check= $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();

} while(!empty($check));

$msg = "Done";
?>
<html>
<head>
<title>Import Data</title>

<style>
.btn{
	cursor: pointer;
	background-color: #7BA428;
	-webkit-box-shadow: 2px 2px 2px #ccc;
	color: #FFF;
	font-weight: bold;
	height: 25px;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
}
</style>
</head>

<body>
<table height="300" align="center">
	<tr>
    	<td valign="middle" align="center">
			<strong><?php echo $msg; ?></strong> <br/>
            <input type="button" name="back" value="Back to Instruction" onClick="location='index.html'" class="btn">
		</td>
    </tr>
</table>
</body>
</html>