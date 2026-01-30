<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


if(isset($_POST['adjust_late']))
{
	$loan_id = $_POST['loan_id'];
	$payment_id = $_POST['payment_id'];
	$add_on = $_POST['late_int'];
	$lateintdate = $_POST['late_intdate'];
	
	//get current month
	$current_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$payment_id."'");
	$get_c = mysql_fetch_assoc($current_q);
	
	
	$balance = $get_c['balance'] + $add_on;
	$interest = $get_c['interest'] + $add_on;
//	$monthly = $get_c['monthly'] + $add_on;
	
	
	$lid_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_id."' ORDER BY month DESC");
	$get_lid = mysql_fetch_assoc($lid_q);
	
	$ldid = $get_lid['id']; 
	
	//get_customer_loanpackage
	$lp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_c['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($lp_q);
	
	//get cust info
	$custd_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_lp['customer_id']."'");
	$custd = mysql_fetch_assoc($custd_q);
	
	$int_total = $get_lp['loan_interesttotal'] + $add_on;
	$ltotal = $get_lp['loan_total'] + $add_on;
	//update customer_loanpackage
	$update = mysql_query("UPDATE customer_loanpackage SET loan_interesttotal = '".$int_total."', loan_total = '".$ltotal."' WHERE id = '".$get_lp['id']."'");
	
	if($update)
	{
		if($add_on != '')
		{
			//add into adjustment
			$insert_a = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$get_c['customer_loanid']."', customer_paymentid = '".$ldid."', add_on = '".$add_on."', reason = 'NEW RECEIPT'");
		
			if($insert_a)
			{
				//add into balance_rec
				$balancerec_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_c['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', interest = '".$add_on."', bal_date = '".$lateintdate."'");
				
				if(balancerec_q)
				{
					$transaction = "LATE INT - ".$custd['name']." - ".$get_lp['loan_code'];
					$addcashbook = mysql_query("INSERT INTO cashbook SET package_id = '".$get_c['package_id']."', table_id = '".$get_q['customer_loanid']."', amount = '".$add_on."', date = '".$lateintdate."', type = 'RECEIVED2', transaction = '".$transaction."'");
				
				
					$msg .= 'Late payment interest has been successfully recorded into balance 2 and cashbook.<br>';
	
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					
					echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
				
				}
			}
		}
	}	
}else
if(isset($_POST['adjust_lateflexi']))
{
	$loan_id = $_POST['loan_id'];
	$payment_id = $_POST['payment_id'];
	$late_period = $_POST['late_period'];
	$add_on = $_POST['late_int'];
	
	//get current month
	$current_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$payment_id."'");
	$get_c = mysql_fetch_assoc($current_q);
	
	$curr_month = $get_c['month'];
	
	$ctrmth = $late_period + $curr_month;
	
	for($i = $curr_month+1; $i<= $ctrmth; $i++)
	{
		if($i != $ctrmth)
		{
			$y = date('Y', strtotime($get_c['next_paymentdate']));
			$m = date('m', strtotime($get_c['next_paymentdate']));
			$d = date('d', strtotime($get_c['next_paymentdate']));
			if($m == 12)
			{
				$m = '01';
				$y++;
			}else
			{
				$m = $m+$i-1;
			}
			$npd = $y.'-'.$m.'-'.$d;
			$mr = $y.'-'.$m;
			
			$insert = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_c['customer_loanid']."', month = '".$i."', int_percent = '".$get_c['int_percent']."', monthly = '".$get_c['monthly']."', balance = '".$get_c['balance']."', int_balance = '".$get_c['int_balance']."', pokok = '".$get_c['pokok']."', interest = '".$get_c['interest']."', package_id = '".$get_c['package_id']."', receipt_no = '".$get_c['receipt_no']."', next_paymentdate = '".$npd."', month_receipt = '".$mr."'");
			
			//$insert = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_c['customer_loanid']."', month = '".$i."', int_percent = '".$get_c['int_percent']."', monthly = '".$get_c['monthly']."', balance = '".$get_c['balance']."', int_balance = '".$get_c['int_balance']."', pokok = '".$get_c['pokok']."', interest = '".$get_c['interest']."', package_id = '".$get_c['package_id']."', receipt_no = '".$get_c['receipt_no']."'");
		}
		else
		{
			$interest = $get_c['interest'] + $add_on;
			$int_bal = $get_c['int_balance'] + $add_on;
			$mr = date('Y-m');
					
			$insert = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_c['customer_loanid']."', month = '".$i."', int_percent = '".$get_c['int_percent']."', monthly = '".$get_c['monthly']."', balance = '".$get_c['balance']."', int_balance = '".$int_bal."', pokok = '".$get_c['pokok']."', interest = '".$interest."', package_id = '".$get_c['package_id']."', month_receipt = '".$mr."', receipt_no = '".$get_c['receipt_no']."', next_paymentdate = now()");
		
			if($insert)
			{
				$lid_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_id."' ORDER BY month DESC");
				$get_lid = mysql_fetch_assoc($lid_q);
				
				$ldid = $get_lid['id']; 
				
				//get_customer_loanpackage
				$lp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_c['customer_loanid']."'");
				$get_lp = mysql_fetch_assoc($lp_q);
				
				$int_total = $get_lp['loan_interesttotal'] + $add_on;
				$ltotal = $get_lp['loan_total'] + $add_on;
				$period = $get_lp['loan_period'] + $late_period;
				//update customer_loanpackage
				$update = mysql_query("UPDATE customer_loanpackage SET loan_interesttotal = '".$int_total."', loan_total = '".$ltotal."', loan_period = '".$period."' WHERE id = '".$get_lp['id']."'");
				
				if($update)
				{
					if($add_on != '')
					{
						//add into adjustment
						$insert_a = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$get_c['customer_loanid']."', customer_paymentid = '".$ldid."', add_on = '".$add_on."', reason = 'NEW RECEIPT'");
						
						if($insert_a)
						{
							$msg .= 'Late payment has been successfully adjusted.<br>';
			
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
							
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
					else
					{
						$msg .= 'Late payment has been successfully adjusted.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
					}
				}
			}
		}
	}
}

?>