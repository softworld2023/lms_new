<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if($_POST['action'] == 'update_remarks')
{
	$id = $_POST['id'];
	$a_remarks2 = addslashes(strtoupper($_POST['remarks']));
	
	//prev remarks 
	$prevremarks_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$prevremarks = mysql_fetch_assoc($prevremarks_q);
	
	$previousremarks = addslashes(strtoupper($prevremarks['loan_remarks']));
	
	$length = strlen($prevremarks['loan_remarks']);
	$length2 = strlen($a_remarks2);
	
	if($a_remarks2  != '')
	{
		if($a_remarks2 == $previousremarks)
		{
			$a_remarks = $a_remarks2;
		}
		else
		{
			if($length2 == $length)
			{
				$a_remarks = $a_remarks2;
			}else
			if($length2 > $length)
			{
				$a_remarks1 = substr($a_remarks2, $length);
				$a_remarks = $prevremarks['loan_remarks'].$a_remarks1." (".$_SESSION['login_name'].")";
			}else
			{
				$a_remarks1 = $a_remarks2;
				$a_remarks = $prevremarks['loan_remarks']."\n".$a_remarks1." (".$_SESSION['login_name'].")";
			}
		}
	}else
	{
		if($prevremarks['loan_remarks'] != '')
		{
			$a_remarks = $prevremarks['loan_remarks'];
		}else
		{
			$a_remarks = '';
		}
	}
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_remarks = '".$a_remarks."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Loan remarks has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_remarks_boss')
{
	$id = $_POST['id'];
	$a_remarks = addslashes(strtoupper($_POST['remarks']));
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_remarks = '".$a_remarks."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Loan remarks has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_aremarks')
{
	$id = $_POST['id'];
	$a_remarks2 = addslashes(strtoupper($_POST['remarks']));
	
	//prev remarks 
	$prevremarks_q = mysql_query("SELECT * FROM customer_account WHERE id = '".$id."'");
	$prevremarks = mysql_fetch_assoc($prevremarks_q);
	
	$previousremarks = addslashes(strtoupper($prevremarks['a_remarks']));
	
	$length = strlen($prevremarks['a_remarks']);
	$length2 = strlen($a_remarks2);
	
	if($a_remarks2  != '')
	{
		if($a_remarks2 == $previousremarks)
		{
			$a_remarks = $a_remarks2;
		}
		else
		{
			if($length2 == $length)
			{
				$a_remarks = $a_remarks2;
			}else
			if($length2 > $length)
			{
				$a_remarks1 = substr($a_remarks2, $length);
				$a_remarks = $prevremarks['a_remarks'].$a_remarks1." (".$_SESSION['login_name'].")";
			}else
			{
				$a_remarks1 = $a_remarks2;
				$a_remarks = $prevremarks['a_remarks']."\n".$a_remarks1." (".$_SESSION['login_name'].")";
			}
		}
	}else
	{
		if($prevremarks['a_remarks'] != '')
		{
			$a_remarks = $prevremarks['a_remarks'];
		}else
		{
			$a_remarks = '';
		}
	}
	
	$update_q = mysql_query("UPDATE customer_account SET a_remarks = '".$a_remarks."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Customer Notes has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}
?>