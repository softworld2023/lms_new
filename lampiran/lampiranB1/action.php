<?php
session_start();
include("../../include/dbconnection.php");
include("../../include/dbconnection2.php");
ini_set('max_input_vars', 10000);
if(isset($_POST['submit']))
{
	$total=$_POST['total'];
	$count = $_POST['count'];	
	
//	$i = 1;
//while($i <= $total){
	
	for($i=1;$i<= $count;$i++){
	/*$id_q = mysql_query("SELECT * FROM customer_b1 ORDER BY id DESC");
 $get_id = mysql_fetch_assoc($id_q);
	$customer_id = $get_id['id']+1;*/
	

	$nric = $_POST['nric'.$i];
	$branch_id = $_POST['branch_id'.$i];
	$name = $_POST['name'.$i];
	$loan_total = $_POST['loan_total'.$i];
	$balance = $_POST['balance'.$i];
	$loan_period= $_POST['loan_period'.$i];
	$payout_date = $_POST['payout_date'.$i];
	$mobile_contact = $_POST['mobile_contact'.$i];
	$int_percent = $_POST['int_percent'.$i];
	$jenis_cagaran = $_POST['jenis_cagaran'.$i];
	$taraf = $_POST['taraf'.$i];
	$race = $_POST['race'.$i];
	$employer = $_POST['employer'.$i];
	$salary = $_POST['salary'.$i];
	$remark = $_POST['remark'.$i];
	
	//check
	$checkic = mysql_query("SELECT * FROM customer_b1 WHERE nric = '".$nric."'", $db2);
	$cic = mysql_num_rows($checkic);
	
	if($cic == 0)
	{
		//insert customer into database 
		
		$insert  = mysql_query("INSERT INTO customer_b1 SET 
												  name = '".$name."', 
												  nric = '".$nric."',
												  loan_total = '".$loan_total."', 
												  balance = '".$balance."', 
												  loan_period = '".$loan_period."',
												   payout_date = '".$payout_date."', 
												   mobile_contact = '".$mobile_contact."', 
												   int_percent = '".$int_percent."', 
												   jenis_cagaran = '".$jenis_cagaran."', 
												  taraf = '".$taraf."', 
												  race = '".$race."', 
												  employer = '".$employer."', 
												  salary = '".$salary."',
												  remark = '".$remark."',
												  branch_id = '".$branch_id."'",$db2) or die(mysql_error());
	}	
	}
	if($insert)
		{
			$_SESSION['msg'] = "<div class='success'>New data have been added into database!</div>";
			echo "<script>window.location='../lampiranB1/edit.php?branch_id=".$branch_id."'</script>";
		}
	
	
	else

if(isset($_POST['submit']))
{
	$total=$_POST['total'];
	$count = $_POST['count'];	

	/*$id_q = mysql_query("SELECT * FROM customer_b1 ORDER BY id DESC");
 $get_id = mysql_fetch_assoc($id_q);
	$customer_id = $get_id['id']+1;*/
	

		for($i=1;$i<= $count;$i++){
	
	$nric = $_POST['nric'.$i];
	$branch_id = $_POST['branch_id'.$i];
	$name = $_POST['name'.$i];
	$loan_total = $_POST['loan_total'.$i];
	$balance = $_POST['balance'.$i];
	$loan_period= $_POST['loan_period'.$i];
	$payout_date = $_POST['payout_date'.$i];
	$mobile_contact = $_POST['mobile_contact'.$i];
	$int_percent = $_POST['int_percent'.$i];
	$jenis_cagaran = $_POST['jenis_cagaran'.$i];
	$taraf = $_POST['taraf'.$i];
	$race = $_POST['race'.$i];
	$employer = $_POST['employer'.$i];
	$salary = $_POST['salary'.$i];
	$remark = $_POST['remark'.$i];
	
	//check
	
		//insert customer into database 
		
		$update  = mysql_query("UPDATE customer_b1 SET 
												  name = '".$name."', 
												  
												  loan_total = '".$loan_total."', 
												  balance = '".$balance."', 
												  loan_period = '".$loan_period."',
												   payout_date = '".$payout_date."', 
												   mobile_contact = '".$mobile_contact."', 
												   int_percent = '".$int_percent."', 
												   jenis_cagaran = '".$jenis_cagaran."', 
												  taraf = '".$taraf."', 
												  race = '".$race."', 
												  employer = '".$employer."', 
												  salary = '".$salary."',
												  remark = '".$remark."',
												  branch_id = '".$branch_id."' WHERE nric = '".$nric."'",$db2) or die(mysql_error());
	}	 
	
	if($update)
		{
			$_SESSION['msg'] = "<div class='success'>Customer successfully updated!</div>";
			echo "<script>window.location='../lampiranB1/edit.php?branch_id=".$branch_id."'</script>";
		}
	
	
	else
	{
		$_SESSION['msg'] = "<div class='fail'>Customer update failed!.</div>";
		echo "<script>window.location='../lampiranB1/edit.php?branch_id=".$branch_id."'</script>";
	}
	
}
}
?>