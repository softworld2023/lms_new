<?php
session_start();
include("../../include/dbconnection.php");
include("../../include/dbconnection2.php");

	
if(isset($_POST['submit']))
{
	$id = $_POST['id'];
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
												  
												  position = '".$position."', 
												  employer = '".$employer."', 
												  address = '".$address."',
												  no_daftar = '".$no_daftar."', 
												  race = '".$race."', 
												  salary = '".$salary."',
												  jenis_cagaran = '".$jenis_cagaran."', 
												  anggaran_nilai = '".$anggaran_nilai."'WHERE nric = '".$nric."'",$db2) or die(mysql_error());
												  
	$update_q = mysql_query("UPDATE  customer_loan SET date_borrow ='".$date_borrow."', 
												  pinjaman_pokok = '".$pinjaman_pokok."', 
												  jumlah_faedah = '".$jumlah_faedah."', 
												  jumlah_besar = '".$jumlah_besar."', 
												  kadar_faedah = '".$kadar_faedah."', 
												  bercagar = '".$bercagar."', 
												  tempoh_bayaran = '".$tempoh_bayaran."', 
												  bayaran_sebulan = '".$bayaran_sebulan."'WHERE nric = '".$nric."'",$db2) or die(mysql_error());
												 

$update_q = mysql_query("UPDATE  customer_payment SET date_pay = '".$date_pay."',
												  jumlah_pinjaman = '".$jumlah_pinjaman."', 
												  bayaran_pinjaman = '".$bayar_pinjaman."', 
												  baki_pinjaman = '".$baki_pinjaman."', 
												  resit_no = '".$resit_no."', 
												  remark = '".$remark."' WHERE nric = '".$nric."'",$db2) or die(mysql_error());
												  
		if ($update_q)
		{
			$_SESSION['msg'] = "<div class='success'>Customer record has been successfully updated!</div>";
			echo "<script>window.location.href='../lampiranA/view_details.php?ic=".$nric."'</script>";
		}
	
	
	//echo "<script>window.location='../customers'</script>";
}
?>