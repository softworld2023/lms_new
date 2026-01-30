<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


if(isset($_POST['adjust_loan']))
{
	$id = $_POST['id'];
	$ctr = $_POST['ctr'];
	
	for($i = 1; $i <= $ctr; $i++)
	{
		$monthly = $_POST['monthly_'.$i];
		$mid = $_POST['mid_'.$i];
		
		$update = mysql_query("UPDATE loan_payment_details SET monthly = '".$monthly."' WHERE id = '".$mid."'");
	}
	
	$msg .= 'Loan monthly amount has been successfully updated.<br>';
					
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	
	echo "<script>window.parent.location='payloan_CEK.php?id=$id'</script>";
}else
if(isset($_POST['adjust_flexiloan']))
{
	$loan_id = $_POST['loan_id'];
	$payment_id = $_POST['payment_id'];
	$month = $_POST['loan_period'];
	$add_on = $_POST['add_on'];
	$reduce = $_POST['reduce'];
	$reason = addslashes($_POST['reason']);
	
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$loan_id."'");
	$get_l = mysql_fetch_assoc($loan_q);
	
	$payment_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$payment_id."'");
	$get_p = mysql_fetch_assoc($payment_q);
		
	$adjustment_q = mysql_query("SELECT * FROM adjustment WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
	$get_a = mysql_fetch_assoc($adjustment_q);
	$ar = mysql_num_rows($adjustment_q);
	
	if($ar != 0)
	{
		if($get_l['loan_period'] != $month)
		{
			$update_l = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$month."' WHERE id = '".$loan_id."'");
			
			if($update_l)
			{
				if($add_on != '' && $reduce == '')
				{
					$new_int = $get_p['int_balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_int = $get_p['int_balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_int = $get_p['int_balance'];
					$loan_total = $get_l['loan_total'];
				}
					
				$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_int = $get_p['int_balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_int = $get_p['int_balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_int = $get_p['int_balance'];
				$loan_total = $get_l['loan_total'];
			}
			
			$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
			
			if($update_q)
			{
				$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
				if($insert_q)
				{
					$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
					if($update_lp)
					{
						$msg .= 'Loan period has been successfully updated.<br>';
					
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
					}
				}
			}
		}
	}else
	{
		if($get_l['loan_period'] != $month)
		{
			$update_l = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$month."' WHERE id = '".$loan_id."'");
			
			if($update_l)
			{
				if($add_on != '' && $reduce == '')
				{
					$new_int = $get_p['int_balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_int = $get_p['int_balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_int = $get_p['int_balance'];
					$loan_total = $get_l['loan_total'];
				}
				
				$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_int = $get_p['int_balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_int = $get_p['int_balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_int = $get_p['int_balance'];
				$loan_total = $get_l['loan_total'];
			}
				
			$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
			
			if($update_q)
			{
				$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
				if($insert_q)
				{
					$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
					if($update_lp)
					{
						$msg .= 'Loan period has been successfully updated.<br>';
					
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
					}
				}
			}
		}
	}
}else
if($_POST['action'] == 'payloan_CEK')
{
	$amount = $_POST['amount'];
	$id = $_POST['id'];
	$date = $_POST['date'];
	$receipt = $_POST['receipt'];
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_l = mysql_fetch_assoc($loan_q);
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment_date = '".$date."', payment = '".$amount."' WHERE id = '".$id."'");
	
	if($update_q)
	{					
		$balance = $get_q['balance'] - $amount;
		//update balance 
		$update_bal_q = mysql_query("UPDATE loan_payment_details SET balance = '".$balance."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");
		
		if($update_bal_q)
		{
			//if balance = 0
			if($balance == 0)
			{
				$nm = $get_q['month'] + 1;
				$insert_bal_q = mysql_query("INSERT INTO loan_payment_details SET balance = '".$balance."', customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."'");
			}
		
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', transaction = 'REC', code = '".$receipt."', amount = '".$amount."', receipt_no = '".$receipt."', customer_id = '".$get_l['customer_id']."', date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET received = '".$amount."', package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', date = now()");
				
				if($insert_bal1_q)
				{
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET received = '".$amount."', package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', bal_date = now()");
					
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}	
	}
}/*else
if($_POST['action'] == 'payloan_CEKccm')
{
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$id = $_POST['id'];
	$ptype = $_POST['ptype'];
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND payment_date = '0000-00-00' ORDER BY month ASC");
	$get_q2 = mysql_fetch_assoc($sql2_q);
	
	if($ptype == 'CCM')
	{
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$id."', int_percent = '".$get_q2['int_percent']."', balance = '".$get_q2['balance']."', payment_date = '".$date."', payment = '".$amount."', package_id = '".$get_q2['package_id']."', receipt_no = '".$get_q2['receipt_no']."'");
	}else
	{//$ptype = INTEREST	
	
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$id."', int_percent = '".$get_q2['int_percent']."', balance = '".$get_q2['balance']."', payment_date = '".$date."', payment = '".$amount."', package_id = '".$get_q2['package_id']."', receipt_no = '".$get_q2['receipt_no']."'");
		
		if($insert_q)
		{
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q2['package_id']."', type = 'RECEIVED', transaction = 'INT', code = '".$get_q2['receipt_no']."', amount = '".$amount."', receipt_no = '".get_q2['receipt_no']."', customer_id = '".$get_q['customer_id']."', date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET received = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', date = now()");
				
				if($insert_bal1_q)
				{
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET interest = '".$amount."', package_id = '".$get_2q['package_id']."', customer_loanid = '".$id."', bal_date = now()");
					
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}	
	}
}*/

?>