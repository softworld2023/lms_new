<?php
session_start();
include("../../include/dbconnection.php");
include("../../include/dbconnection2.php");

if(isset($_POST['submit']))
{
		$id_q = mysql_query("SELECT * FROM customer_details ORDER BY id DESC", $db2);
		 $get_id = mysql_fetch_assoc($id_q);
		
		 $customer_id = $get_id['id']+1;
	
	$branch_id = $_POST['branch_id'];
	$customer_name = $_POST['name'];
	$taraf = $_POST['taraf'];
	$nric = $_POST['nric'];
	$position = $_POST['position'];
	$employer = $_POST['employer'];
	$address = $_POST['address'];
	$no_daftar = $_POST['no_daftar'];
	$race = $_POST['race'];
	$salary = $_POST['salary'];
	$jenis_cagaran = $_POST['jenis_cagaran'];
	$anggaran_nilai = $_POST['anggaran_nilai'];
	
	
	
	$date_pay = $_POST['date_pay'];
	$jumlah_pinjaman = $_POST['jumlah_pinjaman'];
	$bayaran_pinjaman = $_POST['bayaran_pinjaman'];
	$baki_pinjaman = $_POST['baki_pinjaman'];
	$resit_no = $_POST['resit_no'];
	$remark = $_POST['remark'];
	$month = 1;
	//check
	$checkic = mysql_query("SELECT * FROM customer_details WHERE nric = '".$nric."'", $db2);
	$cic = mysql_num_rows($checkic);
	
	if($cic == 0)
	{
		//insert customer into database 
		$insert  = mysql_query("INSERT INTO customer_details SET cust_id = '".$customer_id."',
												  branch_id = '".$branch_id."',
												  name = '".$customer_name."', 
												  taraf = '".$taraf."', 
												  nric = '".$nric."', 
												  position = '".$position."', 
												  employer = '".$employer."', 
												  address = '".$address."',
												  no_daftar = '".$no_daftar."', 
												  race = '".$race."', 
												  salary = '".$salary."',
												  jenis_cagaran = '".$jenis_cagaran."', 
												  anggaran_nilai = '".$anggaran_nilai."'",$db2) or die(mysql_error());
	
	$count = $_POST['count'];	

	/*$id_q = mysql_query("SELECT * FROM customer_b1 ORDER BY id DESC");
 $get_id = mysql_fetch_assoc($id_q);
	$customer_id = $get_id['id']+1;*/
		for($i=1;$i<= $count;$i++){
	$date_borrow = $_POST['date_borrow'.$i];
	$pinjaman_pokok = $_POST['pinjaman_pokok'.$i];
	$jumlah_faedah = $_POST['jumlah_faedah'.$i];
	$jumlah_besar = $_POST['jumlah_besar'.$i];
	$kadar_faedah = $_POST['kadar_faedah'.$i];
	$bercagar = $_POST['bercagar'.$i];
	$tempoh_bayaran = $_POST['tempoh_bayaran'.$i];
	$bayaran_sebulan = $_POST['bayaran_sebulan'.$i];
									  
		$insert1  = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$i."',
												  nric = '".$nric."', 
											   	  date_borrow = '".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												  int_total = '".$jumlah_faedah."', 
												  total_amount = '".$jumlah_besar."', 
												  balance = '".$jumlah_besar."', 
												  int_percent = '".$kadar_faedah."', 
												  month = '".$month."',
												  bercagar = '".$bercagar."', 
												  loan_period = '".$tempoh_bayaran."', 
												  monthly_amount = '".$bayaran_sebulan."'",$db2) or die(mysql_error());
												
		$insert2  = mysql_query("INSERT INTO customer_loan SET
												  nric = '".$nric."', 
											   	  date_borrow = '".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												  jumlah_faedah = '".$jumlah_faedah."', 
												  jumlah_besar = '".$jumlah_besar."', 
												  kadar_faedah = '".$kadar_faedah."', 
												  bercagar = '".$bercagar."', 
												  tempoh_bayaran = '".$tempoh_bayaran."', 
												  bayaran_sebulan = '".$bayaran_sebulan."'",$db2) or die(mysql_error());
		}				
		$insert3  = mysql_query("INSERT INTO customer_payment SET cust_id = '".$customer_id."',
												  nric = '".$nric."', 
												  date_pay = '".$date_pay."',
												  jumlah_pinjaman = '".$jumlah_pinjaman."', 
												  bayaran_pinjaman = '".$bayaran_pinjaman."', 
												  baki_pinjaman = '".$baki_pinjaman."', 
												  resit_no = '".$resit_no."', 
												  remark = '".$remark."'",$db2) or die(mysql_error());

		if($insert || $insert1 || $insert2 || $insert3)
		{
			$_SESSION['msg'] = "<div class='success'>New data have been added into database!</div>";
			echo "<script>window.location='../lampiranA/view_details.php?ic=".$nric."&branch_id=".$branch_id."'</script>";
		}
	}

		else	
if(isset($_POST['submit']))
{
	$customer_name = $_POST['name'];
	$taraf = $_POST['taraf'];
	$nric = $_POST['nric'];
	$position = $_POST['position'];
	$employer = $_POST['employer'];
	$address = $_POST['address'];
	$no_daftar = $_POST['no_daftar'];
	$race = $_POST['race'];
	$salary = $_POST['salary'];
	$jenis_cagaran = $_POST['jenis_cagaran'];
	$anggaran_nilai = $_POST['anggaran_nilai'];

	
	$date_pay = $_POST['date_pay'];
	$jumlah_pinjaman = $_POST['jumlah_pinjaman'];
	$bayaran_pinjaman = $_POST['bayaran_pinjaman'];
	$baki_pinjaman = $_POST['baki_pinjaman'];
	$resit_no = $_POST['resit_no'];
	$remark = $_POST['remark'];
	
	
		$update_q = mysql_query("UPDATE  customer_details SET name = '".$customer_name."', 
												  taraf = '".$taraf."', 
												  
												  position = '".$position."', 
												  employer = '".$employer."', 
												  address = '".$address."',
												  no_daftar = '".$no_daftar."', 
												  race = '".$race."', 
												  salary = '".$salary."',
												  jenis_cagaran = '".$jenis_cagaran."', 
												  anggaran_nilai = '".$anggaran_nilai."', 
												  date_borrow = '".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												  jumlah_faedah = '".$jumlah_faedah."', 
												  jumlah_besar = '".$jumlah_besar."', 
												  kadar_faedah = '".$kadar_faedah."', 
												  bercagar = '".$bercagar."', 
												  tempoh_bayaran = '".$tempoh_bayaran."', 
												  bayaran_sebulan = '".$bayaran_sebulan."',
												  date_pay = '".$date_pay."',
												  jumlah_pinjaman = '".$jumlah_pinjaman."', 
												  bayaran_pinjaman = '".$bayar_pinjaman."', 
												  baki_pinjaman = '".$baki_pinjaman."', 
												  resit_no = '".$resit_no."', 
												  remark = '".$remark."' WHERE nric = '".$nric."'",$db2) or die(mysql_error());
			$count = $_POST['count'];	

	/*$id_q = mysql_query("SELECT * FROM customer_b1 ORDER BY id DESC");
 $get_id = mysql_fetch_assoc($id_q);
	$customer_id = $get_id['id']+1;*/
		for($i=1;$i<= $count;$i++){
	$date_borrow = $_POST['date_borrow'.$i];
	$pinjaman_pokok = $_POST['pinjaman_pokok'.$i];
	$jumlah_faedah = $_POST['jumlah_faedah'.$i];
	$jumlah_besar = $_POST['jumlah_besar'.$i];
	$kadar_faedah = $_POST['kadar_faedah'.$i];
	$bercagar = $_POST['bercagar'.$i];
	$tempoh_bayaran = $_POST['tempoh_bayaran'.$i];
	$bayaran_sebulan = $_POST['bayaran_sebulan'.$i];
	$month = 1;
	$checkid = mysql_query("SELECT * FROM customer_loan WHERE cust_id = '".$i."' AND
												  nric = '".$nric."'", $db2);
	$cid = mysql_num_rows($checkid);

	if($cid == 0)
	{	
			$insert4  = mysql_query("INSERT INTO customer_loan SET cust_id = '".$i."',
												  nric = '".$nric."', 
											   	  date_borrow = '".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												  jumlah_faedah = '".$jumlah_faedah."', 
												  jumlah_besar = '".$jumlah_besar."', 
												  kadar_faedah = '".$kadar_faedah."', 
												  bercagar = '".$bercagar."', 
												  tempoh_bayaran = '".$tempoh_bayaran."', 
												  bayaran_sebulan = '".$bayaran_sebulan."'",$db2) or die(mysql_error());
	
			$insert5  = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$i."',
												  nric = '".$nric."', 
											   	  date_borrow = '".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												  int_total = '".$jumlah_faedah."', 
												  month = '".$month."',
												  total_amount = '".$jumlah_besar."', 
												  balance = '".$jumlah_besar."', 
												  int_percent = '".$kadar_faedah."', 
												  bercagar = '".$bercagar."', 
												  loan_period = '".$tempoh_bayaran."', 
												  monthly_amount = '".$bayaran_sebulan."'",$db2) or die(mysql_error());
												}  
	else{
			$update1  = mysql_query("UPDATE customer_loan SET
											   	  date_borrow = '".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												 jumlah_faedah = '".$jumlah_faedah."', 
												  jumlah_besar = '".$jumlah_besar."', 
												  kadar_faedah = '".$kadar_faedah."', 
												  bercagar = '".$bercagar."', 
												  tempoh_bayaran = '".$tempoh_bayaran."', 
												  bayaran_sebulan = '".$bayaran_sebulan."'
												  WHERE cust_id = '".$i."' AND
												  nric = '".$nric."'",$db2) or die(mysql_error());
				
			$update2  = mysql_query("UPDATE loan_payment_details SET customer_loanid = '".$i."',
													  
													  date_borrow = '".$date_borrow."', 
													  pinjaman_pokok = '".$pinjaman_pokok."', 
													  int_total = '".$jumlah_faedah."', 
													  total_amount = '".$jumlah_besar."', 
													  balance = '".$jumlah_besar."', 
													  int_percent = '".$kadar_faedah."', 
													  bercagar = '".$bercagar."', 
													  loan_period = '".$tempoh_bayaran."', 
													  monthly_amount = '".$bayaran_sebulan."' WHERE nric = '".$nric."'",$db2) or die(mysql_error());
								
		
		$update3  = mysql_query("UPDATE customer_payment SET cust_id = '".$customer_id."',
												  
												  date_pay = '".$date_pay."',
												  jumlah_pinjaman = '".$jumlah_pinjaman."', 
												  bayaran_pinjaman = '".$bayaran_pinjaman."', 
												  baki_pinjaman = '".$baki_pinjaman."', 
												  resit_no = '".$resit_no."', 
												  remark = '".$remark."'
												  WHERE nric = '".$nric."'",$db2) or die(mysql_error());
				}	
		}				
		if ($update_q || $update1 || $update2 || $update3)
		{
			$_SESSION['msg'] = "<div class='success'>Customer has been successfully updated!</div>";
			echo "<script>window.location='../lampiranA/view_details.php?ic=".$nric."&branch_id=".$branch_id."'</script>";
		}
	
	}

	
	//echo "<script>window.location='../customers'</script>";
}
?>