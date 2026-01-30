<?php
session_start();
include("../../include/dbconnection.php");
include("../../include/dbconnection2.php");

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
	
	$date_borrow = $_POST['date_borrow'];
	$pinjaman_pokok = $_POST['pinjaman_pokok'];
	$jumlah_faedah = $_POST['jumlah_faedah'];
	$jumlah_besar = $_POST['jumlah_besar'];
	$kadar_faedah = $_POST['kadar_faedah'];
	$bercagar = $_POST['bercagar'];
	$tempoh_bayaran = $_POST['tempoh_bayaran'];
	$bayaran_sebulan = $_POST['bayaran_sebulan'];
	
	$date_pay = $_POST['date_pay'];
	$jumlah_pinjaman = $_POST['jumlah_pinjaman'];
	$bayaran_pinjaman = $_POST['bayaran_pinjaman'];
	$baki_pinjaman = $_POST['baki_pinjaman'];
	$resit_no = $_POST['resit_no'];
	$remark = $_POST['remark'];
	
	//check
	$checkic = mysql_query("SELECT * FROM customer_details WHERE nric = '".$nric."'", $db2);
	$cic = mysql_num_rows($checkic);
	
	if($cic == 0)
	{
		//insert customer into database 
		$insert  = mysql_query("INSERT INTO customer_details SET name = '".$customer_name."', 
												  taraf = '".$taraf."', 
												  nric = '".$nric."', 
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
												  bayaran_pinjaman = '".$bayaran_pinjaman."', 
												  baki_pinjaman = '".$baki_pinjaman."', 
												  resit_no = '".$resit_no."', 
												  remark = '".$remark."'",$db2) or die(mysql_error());
	
		if($insert)
		{
			$_SESSION['msg'] = "<div class='success'>New data have been added into database!</div>";
			echo "<script>window.location='../customers/print_lampiranA.php'</script>";
		}
	}
	else
	{
		$_SESSION['msg'] = "<div class='success'>Customer already existed!.</div>";
		echo "<script>window.location='../customers/print_lampiranA.php'</script>";
	}
}else

	$id = $_GET['id'];
	$delete_q = mysql_query ("DELETE FROM customer_details WHERE id = '".$id."'",$db2);
	
	if ($delete_q)
	{
		$_SESSION['msg'] = "<div class='success'>Customer has been successfully deleted from database.</div>";	
		echo "<script>window.location.href='../'</script>";
	}

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
	
	$date_borrow = $_POST['date_borrow'];
	$pinjaman_pokok = $_POST['pinjaman_pokok'];
	$jumlah_faedah = $_POST['jumlah_faedah'];
	$jumlah_besar = $_POST['jumlah_besar'];
	$kadar_faedah = $_POST['kadar_faedah'];
	$bercagar = $_POST['bercagar'];
	$tempoh_bayaran = $_POST['tempoh_bayaran'];
	$bayaran_sebulan = $_POST['bayaran_sebulan'];
	
	$date_pay = $_POST['date_pay'];
	$jumlah_pinjaman = $_POST['jumlah_pinjaman'];
	$bayaran_pinjaman = $_POST['bayaran_pinjaman'];
	$baki_pinjaman = $_POST['baki_pinjaman'];
	$resit_no = $_POST['resit_no'];
	$remark = $_POST['remark'];
	
	
		$update_q = mysql_query("UPDATE  customer_details SET name = '".$customer_name."', 
												  taraf = '".$taraf."', 
												  nric = '".$nric."', 
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
												  remark = '".$remark."'",$db2) or die(mysql_error());
												  
		if ($update_q)
		{
			$_SESSION['msg'] = "<div class='success'>Customer has been successfully updated!</div>";
			echo "<script>window.location.href='../'</script>";
		}
	
	
	//echo "<script>window.location='../customers'</script>";
}
?>