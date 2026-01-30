<?php
session_start();
include("../../include/dbconnection.php");
include("../../include/dbconnection2.php");
//error_reporting(0);

if(isset($_POST['save']))
{	
	$branch_id = $_POST['branch_id'];
	$name = $_POST['name'];
	$organisasi = $_POST['organisasi'];
	$hubungan = $_POST['hubungan'];
	$tel = $_POST['tel'];
	$trans_from = $_POST['trans_from'];
	$trans_until = $_POST['trans_until'];
	$no_lesen = $_POST['no_lesen'];
	$plulus = $_POST['plulus'];
	$k_tahun = $_POST['k_tahun'];
	$k_terkumpul = $_POST['k_terkumpul'];
	$bercagar = $_POST['bercagar'];
	
	$t_bercagar = $_POST['t_bercagar'];
	$jumlah_pinjaman = $_POST['jumlah_pinjaman'];
	$k_individu = $_POST['k_individu'];
	$k_syarikat = $_POST['k_syarikat'];
	$k_awam = $_POST['k_awam'];
	$k_swasta = $_POST['k_swasta'];
	$peniaga = $_POST['peniaga'];
	$t_kerja = $_POST['t_kerja'];
	
	$t_info = $_POST['t_info'];
	$pmelayu = $_POST['pmelayu'];
	$pcina = $_POST['pcina'];
	$pindia = $_POST['pindia'];
	$plain = $_POST['plain'];
	$nmelayu = $_POST['nmelayu'];
	$ncina = $_POST['ncina'];
	$nindia = $_POST['nindia'];
	$nlain = $_POST['nlain'];
	$p500 = $_POST['p500'];
	$p1000 = $_POST['p1000'];
	$p5000 = $_POST['p5000'];
	$p10000 = $_POST['p10000'];
	$p10001 = $_POST['p10001'];
	$a1000 = $_POST['a1000'];
	$a5000 = $_POST['a5000'];
	$a10000 = $_POST['a10000'];
	$a50000 = $_POST['a50000'];
	$a100000 = $_POST['a100000'];
	$a1ml = $_POST['a1ml'];
	$p_bumiputera = $_POST['p_bumiputera'];
	$p_bukan_bumi = $_POST['p_bukan_bumi'];
	$s_bumiputera = $_POST['s_bumiputera'];
	$s_bukan_bumi = $_POST['s_bukan_bumi'];
	
	
		$update_q = mysql_query("UPDATE  customer_no SET name = '".$name."',
															organisasi = '".$organisasi."',
															hubungan = '".$hubungan."',
															tel = '".$tel."',
															trans_from = '".$trans_from."',
															trans_until = '".$trans_until."',
															no_lesen = '".$no_lesen."',
															plulus = '".$plulus."',
															k_tahun = '".$k_tahun."',
															k_terkumpul = '".$k_terkumpul."',
															bercagar = '".$bercagar."',
															t_bercagar = '".$t_bercagar."',
															jumlah_pinjaman = '".$jumlah_pinjaman."',
															k_individu = '".$k_individu."',
															k_syarikat = '".$k_syarikat."',
															k_awam = '".$k_awam."',
															k_swasta = '".$k_swasta."',
															peniaga = '".$peniaga."',
															t_kerja = '".$t_kerja."',
															t_info = '".$t_info."',
															pmelayu = '".$pmelayu."',
															pcina = '".$pcina."',
															pindia = '".$pindia."',
															plain = '".$plain."',
															nmelayu = '".$nmelayu."',
															ncina = '".$ncina."',
															nindia = '".$nindia."',
															nlain = '".$nlain."',
															p500 = '".$p500."',
															p1000 = '".$p1000."',
															p5000 = '".$p5000."',
															p10000 = '".$p10000."',
															p10001 = '".$p10001."',
															a1000 = '".$a1000."',
															a5000 = '".$a5000."',
															a10000 = '".$a10000."',
															a50000 = '".$a50000."',
															a100000 = '".$a100000."',
															a1ml = '".$a1ml."',
															p_bumiputera = '".$p_bumiputera."',
															p_bukanbumi = '".$p_bukanbumi."',
															s_bumiputera = '".$s_bumiputera."',
															s_bukanbumi = '".$s_bukanbumi."' WHERE branch_id = '".$branch_id."'",$db2) or die(mysql_error());
	
												  
		if ($update_q)
		{
			$_SESSION['msg'] = "<div class='success'>Customer has been successfully updated!</div>";
			echo "<script>window.location='../lampiranB/view_details.php?branch_id=".$branch_id."'</script>";
		}
	
	
	//echo "<script>window.location='../customers'</script>";
}
?>