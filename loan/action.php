<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['approve_loan']))
{
	$ctr = $_POST['ctr'];	
	
	for($i=1; $i<=$ctr; $i++)
	{
		$value = $_POST['approved_'.$i];
		$id = $_POST['loan_id_'.$i];
		
		if($value != '')
		{
			$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = '".$value."', approval_date = now() WHERE id = '".$id."'");
		}
	}
		
		$msg .= 'Customer loan has been successfully approved.<br>';
		
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='index.php'</script>";
	
}else
if(isset($_POST['reject_loan']))
{
	$ctr = $_POST['ctr'];	
	
	for($i=1; $i<=$ctr; $i++)
	{
		$value = $_POST['approved_'.$i];
		$id = $_POST['loan_id_'.$i];
		
		if($value != '')
		{
			$value = 'Rejected';
			$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = '".$value."' WHERE id = '".$id."'");
		}
	}
		
		$msg .= 'Customer loan has been successfully rejected.<br>';
		
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='index.php'</script>";
	
}else
if(isset($_POST['approve_loan2']))
{
	$ctr = $_POST['ctr'];	
	
	for($i=1; $i<=$ctr; $i++)
	{
		$value = $_POST['approved_'.$i];
		$id = $_POST['loan_id_'.$i];
		
		if($value != '')
		{
			$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = '".$value."', approval_date = now() WHERE id = '".$id."'");
		}
	}
		
		$msg .= 'Customer loan has been successfully approved.<br>';
		
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='kiv.php'</script>";
	
}else
if(isset($_POST['reject_loan2']))
{
	$ctr = $_POST['ctr'];	
	
	for($i=1; $i<=$ctr; $i++)
	{
		$value = $_POST['approved_'.$i];
		$id = $_POST['loan_id_'.$i];
		
		if($value != '')
		{
			$value = 'Rejected';
			$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = '".$value."' WHERE id = '".$id."'");
		}
	}
		
		$msg .= 'Customer loan has been successfully rejected.<br>';
		
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='kiv.php'</script>";
	
}else
if(isset($_POST['kiv_loan']))
{
	$ctr = $_POST['ctr'];	
	
	for($i=1; $i<=$ctr; $i++)
	{
		$value = $_POST['approved_'.$i];
		$id = $_POST['loan_id_'.$i];
		
		if($value != '')
		{
			$value = 'KIV';
			$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = '".$value."' WHERE id = '".$id."'");
		}
	}
		
		$msg .= 'Customer loan has been successfully send to KIV.<br>';
		
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		echo "<script>window.location='index.php'</script>";
	
}
?>