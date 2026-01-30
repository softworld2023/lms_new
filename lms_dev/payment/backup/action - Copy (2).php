<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

/*if($_POST['action'] == 'pay_loan')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$period = $_POST['period'];
	$month = $_POST['month'];
	
	$period_bal = $period - $month;
	
	$update_q = mysql_query("UPDATE fixed_loan_details SET payment = '".$amount."', payment_date = '".$date."' WHERE id = '".$id."'");
	
	$sql = mysql_query("SELECT * FROM fixed_loan_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	
	if($update_q)
	{
		$newmonth = $month + 1;
		
		if($newmonth <= $period)
		{
			$newbalance = $get_q['loan_balance'] - $get_q['payment'];
			
			if($amount == $get_q['payment_permonth'])
			{
					
				$insert_new = mysql_query("INSERT INTO fixed_loan_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', loan_balance = '".$newbalance."', loan_permonth = '".$get_q['loan_permonth']."', interest_permonth = '".$get_q['interest_permonth']."', payment_permonth = '".$get_q['payment_permonth']."'");
		
				if($insert_new)
				{
			
					$msg .= 'Payment has been successfully saved into record.<br>';
		
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
			else
			{
				$new_monthlypayment1 = $newbalance / $period_bal;
				$new_monthlypayment = round($new_monthlypayment1);
				$new_totint = $get_q['interest_permonth'] * $period_bal;
				
				$pokok_bal = $newbalance - $new_totint;
				$pokok1 = $pokok_bal / $period_bal;
				$pokok = round($pokok1);
				
				$insert_new = mysql_query("INSERT INTO fixed_loan_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', loan_balance = '".$newbalance."', loan_permonth = '".$pokok."', interest_permonth = '".$get_q['interest_permonth']."', payment_permonth = '".$new_monthlypayment."'");
		
				if($insert_new)
				{
			
					$msg .= 'Payment has been successfully saved into record.<br>';
		
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			//}
		}
	}
}else
if($_POST['action'] == 'pay_flexiloan')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	
	
	$update_q = mysql_query("UPDATE flexi_loan_details SET payment = '".$amount."', payment_date = '".$date."' WHERE id = '".$id."'");
	
	$sql = mysql_query("SELECT * FROM flexi_loan_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	
	if($update_q)
	{
		$newbalance = $get_q['balance'] - $get_q['payment'];
		$newmonth = $get_q['month'] + 1;
		
		if($newbalance > 0)
		{
			$insert_new = mysql_query("INSERT INTO flexi_loan_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '".$newbalance."'");
	
			if($insert_new)
			{
		
				$msg .= 'Payment has been successfully saved into record.<br>';
	
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
			}
		}
		else
		{
			$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
			
			if(update_lp)
			{
				$msg .= 'Payment has been successfully saved into record.<br>';
	
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
			}
		}
	}
}*/
if($_POST['action'] == 'payloan')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$period = $_POST['period'];
	$month = $_POST['month'];
	
	$period_bal = $period - $month;
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	
	$adjustment_q = mysql_query("SELECT * FROM adjustment WHERE customer_loanid = '".$get_q['customer_loanid']."' AND customer_paymentid = '".$id."'");
	$get_a = mysql_fetch_assoc($adjustment_q);
	$ar = mysql_num_rows($adjustment_q);
	
	if($ar == 0)
	{
		$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_date = '".$date."' WHERE id = '".$id."'");
	
		if($update_q)
		{
			$newmonth = $month + 1;
			
			if($newmonth <= $period)
			{
				$newbalance = $get_q['balance'] - $amount;
				
				$new_monthlypayment1 = $newbalance / $period_bal;
				$new_monthlypayment = round($new_monthlypayment1);
				$new_totint = $get_q['interest'] * $period_bal;
				
				$pokok_bal = $newbalance - $new_totint;
				$pokok1 = $pokok_bal / $period_bal;
				$pokok = round($pokok1);
				
				$monthlypay = $newbalance / $period_bal;
				$monthly = round($monthlypay);
				
				if($newbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', monthly = '".$monthly."', pokok = '".$pokok."', interest = '".$get_q['interest']."'");
					
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0'");
					
					if($insert_new)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
						
						if(update_lp)
						{
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				}
			}
		}
	}else
	{
		$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_date = '".$date."' WHERE id = '".$id."'");
		
		if($update_q)
		{
			$newmonth = $month + 1;
			
			if($newmonth <= $period)
			{
				$newbalance = $get_q['balance'] - $amount;
				
				$new_monthlypayment1 = $newbalance / $period_bal;
				$new_monthlypayment = round($new_monthlypayment1);
				
				if($get_a['add_on'] != 0)
				{
					$new_totint = ($get_q['interest'] - $get_a['add_on']) * $period_bal;
				}
				if($get_a['reduce'] != 0)
				{
					$new_totint = ($get_q['interest'] + $get_a['reduce']) * $period_bal;
				}
				
				$pokok_bal = $newbalance - $new_totint;
				$pokok1 = $pokok_bal / $period_bal;
				$pokok = round($pokok1);
				
				$monthlypay = $newbalance / $period_bal;
				$monthly = round($monthlypay);
				
				$intmonthly = round($new_totint / $period_bal);
				
				if($newbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', monthly = '".$monthly."', pokok = '".$pokok."', interest = '".$intmonthly."'");
					
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0'");
					
					if($insert_new)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
						
						if(update_lp)
						{
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				}
			}
		}
	}
}else
if($_POST['action'] == 'update_period')
{
	$id = $_POST['id'];
	$newP = $_POST['newP'];
	$oldP = $_POST['oldP'];
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$newP."' WHERE id = '".$id."'");
	
	
	if($update_q)
	{
		$msg .= 'Loan period has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if(isset($_POST['adjust_loan']))
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
	
	$bal_month = $month - $get_p['month'] + 1;
	$prev_balmonth = $get_l['loan_period'] - $get_p['month'] + 1;
	
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
					$new_intpermonth = $get_p['interest'] + $add_on;
					$new_monthly = $get_p['monthly'] + $add_on;
					$new_totbal = $get_p['balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_intpermonth = $get_p['interest'] - $reduce;
					$new_monthly = $get_p['monthly'] - $reduce;
					$new_totbal = $get_p['balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_intbal = $int_bal;
					$new_intpermonth = $get_p['interest'];
					$new_monthly = $get_p['monthly'];
					$new_totbal = $get_p['balance'];
					$loan_total = $get_l['loan_total'];
				}
					
				$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
							{
								echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
							}else
							{
								echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
							}
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_intpermonth = $get_p['interest'] + $add_on;
				$new_monthly = $get_p['monthly'] + $add_on;
				$new_totbal = $get_p['balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_intpermonth = $get_p['interest'] - $reduce;
				$new_monthly = $get_p['monthly'] - $reduce;
				$new_totbal = $get_p['balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_intbal = $int_bal;
				$new_intpermonth = $get_p['interest'];
				$new_monthly = $get_p['monthly'];
				$new_totbal = $get_p['balance'];
				$loan_total = $get_l['loan_total'];
			}
				
			$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
			
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
						
						if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
						{
							echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
						}else
						{
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
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
					$new_intpermonth = $get_p['interest'] + $add_on;
					$new_monthly = $get_p['monthly'] + $add_on;
					$new_totbal = $get_p['balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_intpermonth = $get_p['interest'] - $reduce;
					$new_monthly = $get_p['monthly'] - $reduce;
					$new_totbal = $get_p['balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_intbal = $int_bal;
					$new_intpermonth = $get_p['interest'];
					$new_monthly = $get_p['monthly'];
					$new_totbal = $get_p['balance'];
					$loan_total = $get_l['loan_total'];
				}
					
				$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
							{
								echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
							}else
							{
								echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
							}
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_intpermonth = $get_p['interest'] + $add_on;
				$new_monthly = $get_p['monthly'] + $add_on;
				$new_totbal = $get_p['balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_intpermonth = $get_p['interest'] - $reduce;
				$new_monthly = $get_p['monthly'] - $reduce;
				$new_totbal = $get_p['balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_intbal = $int_bal;
				$new_intpermonth = $get_p['interest'];
				$new_monthly = $get_p['monthly'];
				$new_totbal = $get_p['balance'];
				$loan_total = $get_l['loan_total'];
			}
				
			$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
			
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
						
						if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
						{
							echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
						}else
						{
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}
	}
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
}

?>