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
	$customername = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();
	$nric = $objPHPExcel->getActiveSheet()->getCell("B$i")->getValue();
	$branch_id = $objPHPExcel->getActiveSheet()->getCell("C$i")->getValue();
	
	if($branch_id != 'OTH')
	{
		$branchname_q = mysql_query("SELECT * FROM branch WHERE branch_id = '".$branch_id."'");
		$branchname = mysql_fetch_assoc($branchname_q);
		
		$branch_name = $branchname['branch_name'];
	}else
	{
		$branch_name = $objPHPExcel->getActiveSheet()->getCell("D$i")->getValue();
	}
	//check customer
	$check_nric = mysql_query("SELECT * FROM customer_details WHERE nric = '".$nric."' AND branch_id = '".$branch_id."'");
	if(mysql_num_rows($check_nric))
	{
		//dont do anything
	}else
	{

			//new cust
			$insert_cust= mysql_query("INSERT INTO customer_details SET name = '".addslashes($customername)."', nric = '".$nric."', branch_id = '".$branch_id."', branch_name = '".$branch_name."'");
			$cust_id = mysql_insert_id();
			
			//company
			$comp_q = mysql_query("INSERT INTO customer_employment SET customer_id = '".$cust_id."'");
			
			//salary
			$salary_q = mysql_query("INSERT INTO customer_financial SET customer_id = '".$cust_id."'");
			
			$customer_add_q = mysql_query("INSERT INTO customer_address SET customer_id = '".$cust_id."'");
			$customer_acc = mysql_query("INSERT INTO customer_account SET customer_id = '".$cust_id."'");
			$customer_emergency = mysql_query("INSERT INTO customer_emergency SET customer_id = '".$cust_id."'");
			$customer_relative = mysql_query("INSERT INTO customer_relative SET customer_id = '".$cust_id."'");
			$customer_spouse = mysql_query("INSERT INTO customer_spouse SET customer_id = '".$cust_id."'");
		
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
            <input type="button" name="back" value="Back to Instruction" onClick="location='customer.html'" class="btn">
		</td>
    </tr>
</table>
</body>
</html>