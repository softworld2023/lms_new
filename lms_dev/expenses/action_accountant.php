<?php
session_start();
include("../include/dbconnection.php");
require '../include/plugin/PasswordHash.php';

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['add_expenses']))
{
	$details1 = addslashes($_POST['details1']);
	$details2 = addslashes($_POST['details2']);
	
	/*if($details1 != 'OTHER')
	{
		$expenses_details = $details1." ".$details2;
	}else
	{
		$expenses_details = $details2;
	}*/
	
	$expenses_details = $details1." ".$details2;
	
	$date = date("Y-m-d",strtotime($_POST['date']));
	$amount = $_POST['amount'];
	$package_id = '32';
	// $ttype = addslashes($_POST['ttype']);
	$ttype = 'EXPENSES';
	$receiptno = '';
	$month_receipt = $_POST['month_receipt'];
	
	//check package receipt
	$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
	$get_rt = mysql_fetch_assoc($rt_q);
	$skimGrt = substr($get_rt['scheme'], 0, 6);
	//insert expenses
	$insert_q = mysql_query("INSERT INTO expenses_accountant SET 
								expenses_type = '".$details1."', 
								expenses_details = '".$details2."', 
								date = '".$date."', 
								amount = '".$amount."', 
								package_id = '".$package_id."', 
								ttype = '".$ttype."', 
								staff_id = '".$_SESSION['taplogin_id']."', 
								staff_name = '".$_SESSION['login_name']."', 
								branch_id = '".$_SESSION['login_branchid']."', 
								branch_name = '".$_SESSION['login_branch']."', 
								month_receipt ='".$month_receipt."',
								created_date = now() ");
	
	if($insert_q)
	{
		$id = mysql_insert_id();
		
		if($ttype == 'RECEIVED')
		{
			$ttype = 'RECEIVED2';
			$receiptno = $expenses_details;
		}
		//insert into cashbook
		$insert_c_q = mysql_query("INSERT INTO cashbook SET 
									type = '".$ttype."', 
									package_id = '".$package_id."', 
									table_id = '".$id."', 
									transaction = '".$expenses_details."', 
									amount = '".$amount."', 
									date = '".$date."', 
									receipt_no = '".$receiptno."', 
									staff_id = '".$_SESSION['taplogin_id']."', 
									staff_name = '".$_SESSION['login_name']."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."', 
									created_date = now() ");
		
		if($ttype == 'RECEIVED2')
		{
			if($get_rt['receipt_type'] == '1')
			{
				$rcpmth = date('Y-m', strtotime($date));
				$insert_b2q = mysql_query("INSERT INTO balance_rec SET 
											package_id = '".$package_id."', 
											interest = '".$amount."', 
											bal_date = '".$date." ', 
											month_receipt= '".$rcpmth."', 
											branch_id = '".$_SESSION['login_branchid']."', 
											branch_name = '".$_SESSION['login_branch']."'");
			}
		}else
		{
			if($get_rt['scheme'] == 'SKIM CEK')
			{
				$rcpmth = date('Y-m', strtotime($date));
				$insert_b2q = mysql_query("INSERT INTO balance_rec SET 
											package_id = '".$package_id."', 
											expenses = '".$amount."', 
											bal_date = '".$date."', 
											branch_id = '".$_SESSION['login_branchid']."', 
											month_receipt= '".$rcpmth."', 
											branch_name = '".$_SESSION['login_branch']."'");
			}
			
			if($skimGrt == 'SKIM G')
			{
				$rcpmth = date('Y-m', strtotime($date));
				$insert_b2q = mysql_query("INSERT INTO balance_rec SET 
											package_id = '".$package_id."', 
											expenses = '".$amount."', 
											bal_date = '".$date."', 
											branch_id = '".$_SESSION['login_branchid']."', 
											month_receipt= '".$rcpmth."', 
											branch_name = '".$_SESSION['login_branch']."'");
			}
		}
		
		if($insert_c_q)
		{
			$_SESSION['msg'] = "<div class='success'>Transaction has been successfully saved into database.</div>";
			echo "<script>window.location='../expenses/expenses_accountant.php'</script>";
		}
	}
}else
if(isset($_POST['edit_expenses']))
{
	$id = $_POST['id'];
	$details1 = addslashes($_POST['expenses_details']);
	$details2 = addslashes($_POST['details2']);
	$date = $_POST['date'];
	$amount = $_POST['amount'];
	$package_id = '32';
	$month_receipt = $_POST['month_receipt'];
	$ttype = 'EXPENSES';


	$expenses_details = $details1." ".$details2;
	
	
	//get prev rec
	$pexp_q = mysql_query("SELECT * FROM expenses_accountant WHERE id = '".$id."'");
	$pexp = mysql_fetch_assoc($pexp_q);
	
	
	//update expenses
	$insert_q = mysql_query("UPDATE expenses_accountant SET expenses_type = '".$details1."', expenses_details = '".$details2."', date = '".$date."', amount = '".$amount."', package_id = '".$package_id."', ttype = '".$ttype."', month_receipt = '".$month_receipt."' WHERE id = '".$id."'");
	
	if($ttype == 'RECEIVED')
	{
		$ttype = 'RECEIVED2';
	}
	
	$ptype = $pexp['ttype'];
	
	if($ptype == 'RECEIVED')
	{
		$ptype = 'RECEIVED2';
	}
	
	if($insert_q)
	{
		//update cashbook
		$cashbook_q = mysql_query("UPDATE cashbook SET package_id = '".$package_id."', transaction = '".$expenses_details."', amount = '".$amount."', date = '".$date."', type = '".$ttype."' WHERE table_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = '".$pexp['expenses_details']."' AND date LIKE '%".$pexp['date']."%' AND amount = '".$pexp['amount']."' AND type = '".$ptype."'");
		
		if($cashbook_q)
		{
			//check receipt type
			$prectype_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$pexp['package_id']."'");
			$prectype = mysql_fetch_assoc($prectype_q);
			$skimGprec = substr($prectype['scheme'], 0, 6);
			
			$nrectype_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
			$nrectype = mysql_fetch_assoc($nrectype_q);
			$skimGnrec = substr($nrectype['scheme'], 0, 6);
			
			//update balance2
			if($ttype == 'EXPENSES')
			{
				//prev exp, same package
				if($pexp['ttype'] == 'EXPENSES' && $pexp['package_id'] == $package_id)
				{
					if($prectype['scheme'] == 'SKIM CEK')
					{
						$rcpmth = date('Y-m', strtotime($date));
						$insert_b2q = mysql_query("UPDATE balance_rec SET package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['expenses']."' AND customer_loanid = '0'");
					}
					if($skimGprec == 'SKIM G')
					{
						$rcpmth = date('Y-m', strtotime($date));
						$insert_b2q = mysql_query("UPDATE balance_rec SET package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['expenses']."' AND customer_loanid = '0'");
					}
				}
				
				//prev exp, diff package
				if($pexp['ttype'] == 'EXPENSES' && $pexp['package_id'] != $package_id)
				{
					//if prev receipt type == 2, new receipt type == 1, add new
					if($prectype['receipt_type'] == 2 && $nrectype['receipt_type'] == 1)
					{
						
						if($nrectype['scheme'] == 'SKIM CEK')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("INSERT INTO balance_rec SET package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', month_receipt= '".$rcpmth."', branch_name = '".$_SESSION['login_branch']."'");
						}
						
						if($skimGnrec == 'SKIM G')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("INSERT INTO balance_rec SET package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', month_receipt= '".$rcpmth."', branch_name = '".$_SESSION['login_branch']."'");
						}
					}
					
					//prev receipt_type == 1, new receipt type ==1, skim G&CEK update, others delete
					if($prectype['receipt_type'] == 1 && $nrectype['receipt_type'] == 1)
					{
						if($nrectype['scheme'] == 'SKIM CEK')
						{
							
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						}else						
						if($skimGnrec == 'SKIM G')
						{
							
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
							
						}else //delete
						{
							$insert_b2q = mysql_query("DELETE FROM balance_rec WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						}
					}
					
					//if prev receipt type == 1, new receipt type == 2, delete prev
					if($prectype['receipt_type'] == 1 && $nrectype['receipt_type'] == 2)
					{
						
						$insert_b2q = mysql_query("DELETE FROM balance_rec WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						
					}
				}
				
				//prev received
				if($pexp['ttype'] == 'RECEIVED')
				{
					//same package
					if($pexp['package_id'] == $package_id)
					{
						//receipt type == 1, update int = 0, exp = amount
						if($prectype['receipt_type'] == '1')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET interest = '', expenses = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND interest = '".$pexp['amount']."' AND customer_loanid = 0");
							
						}
					}
					
					//diff pacakage
					if($pexp['package_id'] != $package_id)
					{
						//diff package, same receipt type == 1, update int = 0, exp = amount
						if($prectype['receipt_type'] == '1' && $nrectype['receipt_type'] == '1')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET interest = '', package_id = '".$package_id."', expenses = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND interest = '".$pexp['amount']."' AND customer_loanid = 0");
						}
						
						//diff package, new receipt type == 2, delete
						if($prectype['receipt_type'] == '1' && $nrectype['receipt_type'] == '2')
						{
							$insert_b2q = mysql_query("DELETE FROM balance_rec WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND interest = '".$pexp['amount']."' AND customer_loanid = 0");
						}
					}
					
				}
			}
			
			//update balance2
			if($ttype == 'RECEIVED2')
			{
				//prev exp, same package
				if($pexp['ttype'] == 'EXPENSES' && $pexp['package_id'] == $package_id)
				{
					
					
					if($prectype['scheme'] == 'SKIM CEK')
					{
						$rcpmth = date('Y-m', strtotime($date));
						$insert_b2q = mysql_query("UPDATE balance_rec SET expenses = '', interest = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = 0");
						
					}					
					if($skimPnrec== 'SKIM G')
					{
						$rcpmth = date('Y-m', strtotime($date));
						$insert_b2q = mysql_query("UPDATE balance_rec SET expenses = '', interest = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['expenses']."' AND customer_loanid = 0");
					}
				}
				
				//prev exp, diff package
				if($pexp['ttype'] == 'EXPENSES' && $pexp['package_id'] != $package_id)
				{
					//if prev receipt type == 2, new receipt type == 1, add new
					if($prectype['receipt_type'] == 2 && $nrectype['receipt_type'] == 1)
					{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("INSERT INTO balance_rec SET package_id = '".$package_id."', interest = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', month_receipt= '".$rcpmth."', branch_name = '".$_SESSION['login_branch']."'");
					}
					
					//prev receipt_type == 1, new receipt type ==1 : skim G & CEK = update, others delete
					if($prectype['receipt_type'] == 1 && $nrectype['receipt_type'] == 1)
					{
						if($nrectype['scheme'] == 'SKIM CEK')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET package_id = '".$package_id."', expenses = '', interest = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						}else						
						if($skimGnrec == 'SKIM G')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET package_id = '".$package_id."', expenses = '', interest = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						}else //delete
						{
							$insert_b2q = mysql_query("DELETE FROM balance_rec WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						}
					}
					
					//if prev receipt type == 1, new receipt type == 2, delete prev
					if($prectype['receipt_type'] == 1 && $nrectype['receipt_type'] == 2)
					{
						$insert_b2q = mysql_query("DELETE FROM balance_rec WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND expenses = '".$pexp['amount']."' AND customer_loanid = '0'");
						
					}
				}
				
				//prev received
				if($pexp['ttype'] == 'RECEIVED')
				{
					//same package
					if($pexp['package_id'] == $package_id)
					{
						//receipt type == 1, int = amount
						if($prectype['receipt_type'] == '1')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET expenses = '', interest = '".$amount."', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND interest = '".$pexp['amount']."' AND customer_loanid = 0");
						}
					}
					
					//diff pacakage
					if($pexp['package_id'] != $package_id)
					{
						//diff package, same receipt type == 1, update int = amount, exp = amount
						if($prectype['receipt_type'] == '1' && $nrectype['receipt_type'] == '1')
						{
							$rcpmth = date('Y-m', strtotime($date));
							$insert_b2q = mysql_query("UPDATE balance_rec SET interest = '".$amount."', package_id = '".$package_id."', expenses = '', bal_date = '".$date."', month_receipt= '".$rcpmth."' WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND interest = '".$pexp['amount']."' AND customer_loanid = 0");
						}
						
						//diff package, new receipt type == 2, delete
						if($prectype['receipt_type'] == '1' && $nrectype['receipt_type'] == '2')
						{
							$insert_b2q = mysql_query("DELETE FROM balance_rec WHERE bal_date LIKE '%".$pexp['date']."%' AND branch_id = '".$pexp['branch_id']."' AND package_id = '".$pexp['package_id']."' AND interest = '".$pexp['amount']."' AND customer_loanid = 0");
						}
					}
					
				}
			}
			
			//end of balance 2
			$_SESSION['msg'] .= "<div class='success'>Expenses has been successfully updated.</div>";
			echo "<script>window.location='../expenses/expenses_accountant.php'</script>";
		}
	}
}else
if($_POST['action'] == 'delete_expenses')
{
	$id = $_POST['id'];
	
	//get prev rec
	$pexp_q = mysql_query("SELECT * FROM expenses_accountant WHERE id = '".$id."'");
	$pexp = mysql_fetch_assoc($pexp_q);
	
	$package_id = $pexp['package_id'];
	$amount = $pexp['amount'];
	$date = $pexp['date'];
	$details1 = $pexp['expenses_type'];
	$details2 = $pexp['expenses_details'];
	$branch_id = $pexp['branch_id'];
	
	$type = $pexp['ttype'];
	if($type == 'RECEIVED')
	{
		$type = 'RECEIVED2';
	}
	$expenses_details = $details1." ".$details2;

	//delete from cashbook
	// $deletecb = mysql_query("DELETE FROM cashbook WHERE package_id = '".$package_id."' AND type = '".$type."' AND transaction = '".$expenses_details."' AND table_id = '".$id."' AND amount = '".$amount."' AND date LIKE '%".$date."%' AND branch_id = '".$branch_id."'");
	$deletecb = mysql_query("UPDATE cashbook SET display_status = 'DELETED' WHERE package_id = '".$package_id."' AND type = '".$type."' AND transaction = '".$expenses_details."' AND table_id = '".$id."' AND amount = '".$amount."' AND date LIKE '%".$date."%' AND branch_id = '".$branch_id."'");
	
	if($deletecb)
	{
		//delete from balance 2
		if($type == 'EXPENSES')
		{
			// $deleteb2 = mysql_query("DELETE FROM balance_rec WHERE package_id = '".$package_id."' AND expenses = '".$amount."' AND bal_date LIKE '%".$date."%' AND branch_id = '".$branch_id."'");
			$deleteb2 = mysql_query("UPDATE balance_rec SET display_status = 'DELETED' WHERE package_id = '".$package_id."' AND expenses = '".$amount."' AND bal_date LIKE '%".$date."%' AND branch_id = '".$branch_id."'");
		}
		
		if($type == 'RECEIVED2')
		{
			// $deleteb2 = mysql_query("DELETE FROM balance_rec WHERE package_id = '".$package_id."' AND interest = '".$amount."' AND bal_date LIKE '%".$date."%' AND branch_id = '".$branch_id."'");
			$deleteb2 = mysql_query("UPDATE balance_rec SET display_status = 'DELETED' WHERE package_id = '".$package_id."' AND interest = '".$amount."' AND bal_date LIKE '%".$date."%' AND branch_id = '".$branch_id."'");
		}
		
		
		//delete from expenses
		// $delete_ac = mysql_query("DELETE FROM expenses_accountant WHERE id = '".$id."'");
		$delete_ac = mysql_query("UPDATE expenses_accountant SET display_status = 'DELETED' WHERE id = '".$id."'");
		
		if($delete_ac)
		{
			$_SESSION['msg'] = "<div class='success'>Expenses record has been deleted from database.</div>";
			
		}
	}
}else
if(isset($_POST['add_type']))
{
	$description = addslashes($_POST['description']);
	
	//insert expenses type
	$insert_q = mysql_query("INSERT INTO expenses_setting SET description = '".$description."'");
	
	if($insert_q)
	{
		$_SESSION['msg1'] = "<div class='success'>Expenses Type has been successfully saved into database.</div>";
		echo "<script>window.location='../expenses/setting.php'</script>";
	}
}else
if($_POST['action'] == 'delete_type')
{
	$id = $_POST['id'];
	
	//delete staff's access right
	$delete_ac = mysql_query("DELETE FROM expenses_setting WHERE id = '".$id."'");
	
	if($delete_ac)
	{
		$_SESSION['msg1'] = "<div class='success'>Expenses Type has been deleted from database.</div>";
		echo "<script>window.location='../expenses/setting.php'</script>";
	}
}else
if($_POST['action'] == 'update_type')
{
	$id = $_POST['id'];
	$description = $_POST['description'];
	
	//update
	$update_q = mysql_query("UPDATE expenses_setting SET description = '".$description."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$_SESSION['msg1'] = "<div class='success'>Expenses Type has been updated.</div>";
	}
}
?>