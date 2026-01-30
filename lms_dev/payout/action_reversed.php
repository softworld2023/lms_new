<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


if($_POST['action'] == 'reversed_loan')
{
	if($_SESSION['login_branchid'] != '')
	{
		$id = $_POST['id'];
	
		//get payout date
		$pdate_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
		$pdate = mysql_fetch_assoc($pdate_q);
		$paydate = $pdate['payout_date'];
		
		//update payout date
		$update = mysql_query("UPDATE customer_loanpackage SET payout_date = '', loan_status = 'Approved' WHERE id = '".$id."'");
		
		//delete payout data
		$delete_payout = mysql_query("DELETE FROM payout_details WHERE customer_loanid = '".$id."'");	
		
		
	
		if($pdate['loan_type'] == 'Fixed Loan')
		{
	
			//delete from cashbook	
			$cpa = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND date LIKE '%".$paydate."%'");
			while($cpaq = mysql_fetch_assoc($cpa))
			{
				$delete_cash = mysql_query("DELETE FROM cashbook WHERE id = '".$cpaq['id']."'");
			}	
			
			$cbdl = mysql_query("DELETE FROM cashbook WHERE date LIKE '%".$paydate."%' AND branch_id = '".$pdate['branch_id']."' AND receipt_no = '".$pdate['loan_code']."'");
			
			//delete from balance2
			$bal2_q = mysql_query("DELETE FROM balance_rec WHERE customer_loanid = '".$id."' AND bal_date LIKE '%".$paydate."%'");
			
			//delete from balance1 
			$bal1_q = mysql_query("DELETE FROM balance_transaction WHERE customer_loanid = '".$id."' AND date LIKE '%".$paydate."%'");
			
			//delete from loam_payment_details
			//$lpd_q = mysql_query("DELETE FROM loan_payment_details WHERE customer_loanid = '".$id."'");
			//select * record from loan_payment_details
			$lpdq = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
			while($lpayd = mysql_fetch_assoc($lpdq))
			{
				if($lpayd['payment'] != 0)
				{
					//DELETE BAL1
					$dbal1 = mysql_query("DELETE FROM balance_transaction WHERE customer_loanid = '".$id."' AND received = '".$lpayd['payment']."' AND date LIKE '%".$lpayd['payment_date']."%'");
					
					//DELETE BAL2
					$dbal2 = mysql_query("DELETE FROM balance_rec WHERE customer_loanid = '".$id."' AND received = '".$lpayd['payment']."' AND bal_date LIKE '%".$lpayd['payment_date']."%'");
					
					//delete CASHBOOK
					$dcash = mysql_query("DELETE FROM cashbook WHERE table_id = '".$lpayd['id']."' AND amount = '".$lpayd['payment']."' AND date LIKE '%".$lpayd['payment_date']."%'");
					
				}
				
				if($lpayd['payment_int'] != 0)
				{
					//DELETE BAL1
					$dbal1 = mysql_query("DELETE FROM balance_transaction WHERE customer_loanid = '".$id."' AND received = '".$lpayd['payment_int']."' AND date LIKE '%".$lpayd['payment_date']."%'");
					
					//DELETE BAL2
					$dbal2 = mysql_query("DELETE FROM balance_rec WHERE customer_loanid = '".$id."' AND interest2 = '".$lpayd['payment_int']."' AND bal_date LIKE '%".$lpayd['payment_date']."%'");
					
					//delete CASHBOOK
					$dcash = mysql_query("DELETE FROM cashbook WHERE receipt_no = '".$lpayd['receipt_no']."' AND amount = '".$lpayd['payment_int']."' AND date LIKE '%".$lpayd['payment_date']."%'");
					
					
				}
				
				//delete paymentrec
				$dpr = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpayd['id']."'");
			}

			$delete_temp = mysql_query("DELETE FROM temporary_payment_details WHERE loan_code = '".$pdate['loan_code']."'");
				$delete_lejar = mysql_query("DELETE FROM loan_lejar_details WHERE customer_loanid = '".$pdate['id']."'");
		}else
		{
		
			// DELETE BAL1
			$dbal1 = mysql_query("DELETE FROM balance_transaction WHERE customer_loanid = '".$id."'");
			
			//DELETE BAL2
			$dbal2 = mysql_query("DELETE FROM balance_rec WHERE customer_loanid = '".$id."'");
			
			$lpdq = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
			$ctr = 0;
			$prevdate = '';
			while($lpayd = mysql_fetch_assoc($lpdq))
			{
				$ctr++;
				
				if($ctr == 1)
				{
					//delete payout transaction
					$dcbt = mysql_query("DELETE FROM cashbook WHERE code = '".$lpayd['receipt_no']."' AND branch_id = '".$pdate['branch_id']."' AND date LIKE '%".$paydate."%'");
				}
				
				//delete CASHBOOK
				$dcash = mysql_query("DELETE FROM cashbook WHERE date LIKE '%".$lpayd['payment_date']."%' AND branch_id = '".$pdate['branch_id']."' AND receipt_no = '".$lpayd['receipt_no']."'");

				if($prevdate != '')
				{
					$loanint_q = mysql_query("DELETE FROM cashbook WHERE date LIKE '%".$prevdate."%' AND branch_id = '".$pdate['branch_id']."' AND receipt_no = '".$lpayd['receipt_no']."'");
				}
				
				$prevdate = $lpayd['payment_date'];
					
				//delete paymentrec
				$dpr = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpayd['id']."'");
			}
		}
	}
}else
if($_POST['action'] == 'delete_loan')
{
	$id = $_POST['id'];
	
	// Get the customer_id before deleting
	$result = mysql_query("SELECT customer_id FROM customer_loanpackage WHERE id = '".$id."'");
	$row = mysql_fetch_assoc($result);
	$customer_id = $row['customer_id'];

	// Delete the record
	$delete = mysql_query("DELETE FROM customer_loanpackage WHERE id = '".$id."'");

	// Update customer status
	if ($customer_id) {
		$update = mysql_query("
			UPDATE customer_details 
			SET customer_status = 'PENDING APPROVAL' 
			WHERE customer_status IS NOT NULL 
			AND id = '".$customer_id."'
		");
	}

	$msg .= 'Loan has been successfully deleted.<br>';
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
}
?>