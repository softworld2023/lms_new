<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_scheme']))
{
	$scheme1 = stripslashes($_POST['scheme']);
	$scheme = strtoupper(mysql_real_escape_string($scheme1));
	$formula1 = stripslashes($_POST['formula']);
	$formula = mysql_real_escape_string($formula1);
	$interest = $_POST['interest'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$type = $_POST['type'];
	$receipt_type = $_POST['receipt_type'];
	
	//insert new truck
	$insert_q = mysql_query("INSERT INTO loan_scheme SET scheme = '".$scheme."', formula = '".$formula."', interest =  	    '".$interest."', from_date = '".$from_date."', to_date = '".$to_date."', type = '".$type."', receipt_type = '".     $receipt_type."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>New package has been added.</div>";
		echo "<script>window.location='../package/'</script>";
	}
}else
if($_POST['action'] == 'delete_transfer')
{
	$id = $_POST['id'];
	
	//get transfer data
	$prevtrans_q = mysql_query("SELECT * FROM transfer_trans WHERE id = '".$id."'");
	$prevtrans = mysql_fetch_assoc($prevtrans_q);
	
	$date = $prevtrans['date'];
	$amount = $prevtrans['amount'];
	$tdetails = $prevtrans['transfer_details'];
	$fromp = $prevtrans['from_package'];
	$top = $prevtrans['to_package'];
	$branch_id = $prevtrans['branch_id'];
	
	//delete record in table transfer_trans
    $delete_q = mysql_query("DELETE FROM transfer_trans WHERE id = '".$id."'");
	
	//delete record in table cashbook 
	$delete_q2 = mysql_query("DELETE FROM cashbook WHERE table_id = '".$id."' AND type = 'TRANSFER1' AND transaction = '".$tdetails."' AND package_id = '".$fromp."' AND amount = '".$amount."' AND date = '".$date."' AND branch_id = '".$branch_id."'");
	
	$delete_32 = mysql_query("DELETE FROM cashbook WHERE table_id = '".$id."' AND type = 'TRANSFER2' AND transaction = '".$tdetails."' AND package_id = '".$top."' AND amount = '".$amount."' AND date = '".$date."' AND branch_id = '".$branch_id."'");
	
		$fp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$fromp."'");
		$get_fp = mysql_fetch_assoc($fp_q);
		$skimGf = substr($get_fp['scheme'], 0, 6);
		
		$tp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$top."'");
		$get_tp = mysql_fetch_assoc($tp_q);
		$skimGt = substr($get_tp['scheme'], 0, 6);
		
		//if from package fixed loan - delete from balance 2
		if($get_fp['type'] == 'Fixed Loan' || ($skimGf == 'SKIM G') || ($fromp == 0) )
		{
			$insert_bal = mysql_query("DELETE FROM balance_rec WHERE package_id = '".$fromp."' AND loan = '".$amount."' AND bal_date = '".$date."' AND branch_id = '".$branch_id."' AND customer_loanid = 0");
		}
		
		//if to package fixed loan - delete balance 2
		if($get_tp['type'] == 'Fixed Loan' || ($skimGt == 'SKIM G') || ($top == 0) )
		{
			$insert_bal2 = mysql_query("DELETE FROM balance_rec WHERE package_id = '".$top."' AND received = '".$amount."' AND bal_date = '".$date."' AND branch_id = '".$branch_id."' AND customer_loanid = 0");
		}
	

	if($delete_q && $delete_q2)
	{
		$_SESSION['msg'] = "<div class='success'>Package has been successfully deleted from database.</div>";	
	}
}else
if(isset($_POST['edit_scheme']))
{
	$id = $_POST['id'];
	$scheme1 = stripslashes($_POST['scheme']);
	$scheme = strtoupper(mysql_real_escape_string($scheme1));
	$formula1 = stripslashes($_POST['formula']);
	$formula = mysql_real_escape_string($formula1);
	$interest = $_POST['interest'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$type = $_POST['type'];
	$receipt_type = $_POST['receipt_type'];
	
	//insert new scheme
	$insert_q = mysql_query("UPDATE loan_scheme SET scheme = '".$scheme."', formula = '".$formula."', interest = '".$interest."', from_date = '".$from_date."', to_date = '".$to_date."', type = '".$type."', receipt_type = '".$receipt_type."' WHERE id = '".$id."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Package info has been successfully updated.</div>";
		echo "<script>window.location='../package/'</script>";
	}
}

//add new transfer
else
if(isset($_POST['new_transfer']))
{
	$transfer_details = addslashes(strtoupper($_POST['transfer_details']));
	$date = date('Y-m-d', strtotime($_POST['transfer_date']));
	$amount = $_POST['transfer_amount'];
	$from_package = $_POST['from_package'];
	$to_package = $_POST['to_package'];
	$mr = date('Y-m', strtotime(date($date)));
	
	//insert to table: transfer_trans
	$insert_q = mysql_query("INSERT INTO transfer_trans SET transfer_details = '".$transfer_details."', date = '".$date."', amount = '".$amount."', from_package = '".$from_package."', to_package = '".$to_package."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
	
	if($insert_q)
	{
		//get the id
		$id = mysql_insert_id();
		
		//insert into cashbook (from package)
		$insert_cashbook = mysql_query("INSERT INTO cashbook SET type = 'TRANSFER1', package_id = '".$from_package."', table_id = '".$id."', transaction = '".$transfer_details."', amount = '".$amount."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
		
		//insert into cashbook (to package)
		$insert_cashbook2 = mysql_query("INSERT INTO cashbook SET type = 'TRANSFER2', package_id = '".$to_package."', table_id = '".$id."', transaction = '".$transfer_details."', amount = '".$amount."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
		
		$fp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$from_package."'");
		$get_fp = mysql_fetch_assoc($fp_q);
		$skimGf = substr($get_fp['scheme'], 0, 6);
		
		$tp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$to_package."'");
		$get_tp = mysql_fetch_assoc($tp_q);
		$skimGt = substr($get_tp['scheme'], 0, 6);
	
		//if from package fixed loan - insert into balance 2
		if($get_fp['type'] == 'Fixed Loan' || ($skimGf == 'SKIM G') || ($from_package == 0)  )
		{
			$insert_bal = mysql_query("INSERT INTO balance_rec SET package_id = '".$from_package."', loan = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', month_receipt = '".$mr."'");
		}
		
		//if to package fixed loan - insert into balance 2
		if($get_tp['type'] == 'Fixed Loan' || ($skimGt == 'SKIM G') || ($to_package == 0))
		{
			$insert_bal2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$to_package."', received = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', month_receipt = '".$mr."'");
		}
		
		$_SESSION['msg'] = "<div class='success'>Money has been successfully transfered.</div>";
		echo "<script>window.location='../package/transfer.php'</script>";
	}
}
else
if(isset($_POST['edit_transfer']))
{
	$id = $_POST['id'];
	$transfer_details = addslashes(strtoupper($_POST['transfer_details']));
	$date = date('Y-m-d', strtotime($_POST['transfer_date']));
	$amount = $_POST['transfer_amount'];
	$from_package = $_POST['from_package'];
	$to_package = $_POST['to_package'];
	$mr = date('Y-m', strtotime(date($date)));
	
	//get prev
	$prevtrans_q = mysql_query("SELECT * FROM transfer_trans WHERE id = '".$id."'");
	$prevtrans = mysql_fetch_assoc($prevtrans_q);
	
	//update to table: trasnsfer_trans
	$insert_q = mysql_query("UPDATE transfer_trans SET transfer_details = '".$transfer_details."', date = '".$date."', amount = '".$amount."', from_package = '".$from_package."', to_package = '".$to_package."' WHERE id = '".$id."'");
	
	if($insert_q)
	{
	
		//update into cashbook (from package)
		$insert_cashbook = mysql_query("UPDATE cashbook SET package_id = '".$from_package."', transaction = '".$transfer_details."', amount = '".$amount."', date = '".$date."' WHERE type = 'TRANSFER1' AND table_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."'");
		
		//update into cashbook (to package)
		$insert_cashbook2 = mysql_query("UPDATE cashbook SET package_id = '".$to_package."', transaction = '".$transfer_details."', amount = '".$amount."', date = '".$date."' WHERE type = 'TRANSFER2' AND table_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."'");
		
		
		$fp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$from_package."'");
		$get_fp = mysql_fetch_assoc($fp_q);
		$skimGf = substr($get_fp['scheme'], 0, 6);
		
		$tp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$to_package."'");
		$get_tp = mysql_fetch_assoc($tp_q);
		$skimGt = substr($get_tp['scheme'], 0, 6);
		
		$pfp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$prevtrans['from_package']."'");
		$get_pfp = mysql_fetch_assoc($pfp_q);
		
		$ptp_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$prevtrans['to_package']."'");
		$get_ptp = mysql_fetch_assoc($ptp_q);
		
		//if from package fixed loan - insert into balance 2
		if($get_fp['type'] == 'Fixed Loan' || ($skimGf == 'SKIM G') || ($from_package == 0))
		{
			if($get_pfp['type'] == 'Fixed Loan')
			{
				$insert_bal = mysql_query("UPDATE balance_rec SET package_id = '".$from_package."', loan = '".$amount."', bal_date = '".$date."' WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$prevtrans['from_package']."' AND loan = '".$prevtrans['amount']."' AND bal_date = '".$prevtrans['date']."' AND customer_loanid = 0");
			}else
			{
				$insert_bal = mysql_query("INSERT INTO balance_rec SET package_id = '".$from_package."', loan = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			}
		}else
		{
			
			if($get_pfp['type'] == 'Fixed Loan')
			{//delete prev
				$insert_bal = mysql_query("DELETE FROM balance_rec WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$prevtrans['from_package']."' AND loan = '".$prevtrans['amount']."' AND bal_date = '".$prevtrans['date']."' AND customer_loanid = 0");
			}
		}
		
		//if to package fixed loan - insert into balance 2
		if($get_tp['type'] == 'Fixed Loan' || ($skimGt == 'SKIM G') || ($to_package == 0))
		{
			if($get_ptp['type'] == 'Fixed Loan')
			{
				$insert_bal = mysql_query("UPDATE balance_rec SET package_id = '".$to_package."', received = '".$amount."', bal_date = '".$date."' WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$prevtrans['to_package']."' AND received = '".$prevtrans['amount']."' AND bal_date = '".$prevtrans['date']."' AND customer_loanid = 0");
			}else
			{
				$insert_bal2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$to_package."', received = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			}
		}else
		{
			
			if($get_ptp['type'] == 'Fixed Loan')
			{//delete prev
				$insert_bal = mysql_query("DELETE FROM balance_rec WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$prevtrans['to_package']."' AND received = '".$prevtrans['amount']."' AND bal_date = '".$prevtrans['date']."' AND customer_loanid = 0");
			}
		}
		
		$_SESSION['msg'] = "<div class='success'>Record has been successfully updated.</div>";
		echo "<script>window.location='../package/transfer.php'</script>";
	}
}


?>