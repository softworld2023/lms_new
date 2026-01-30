<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
if(isset($_POST['search']))   // click search
{
	$year = $_POST['select'];
$date_from = $year.'-01-01';
$date_to = $year.'-12-31';

	$date = date("Y");

$yr1 = date('Y', strtotime($date_from));
$mth1 = date('m', strtotime($date_from));
$day1 = date('d', strtotime($date_from));

$yr2 = date('Y', strtotime($date_to));
$mth2 = date('m', strtotime($date_to));
$day2 = date('d', strtotime($date_to));

		if($mth1=='01'){$mth1 = 'Januari';}
		else if($mth1=='02'){$mth1 = 'Februari';}
		else if($mth1=='03'){$mth1 = 'Mac';}
		else if($mth1=='04'){$mth1 = 'April';}
		else if($mth1=='05'){$mth1 = 'Mei';}
		else if($mth1=='06'){$mth1 = 'Jun';}
		else if($mth1=='07'){$mth1 = 'Julai';}
		else if($mth1=='08'){$mth1 = 'Ogos';}
		else if($mth1=='09'){$mth1 = 'September';}
		else if($mth1=='10'){$mth1 = 'Oktober';}
		else if($mth1=='11'){$mth1 = 'November';}
		else if($mth1=='12'){$mth1 = 'Disember';}

		if($mth2=='01'){$mth2 = 'Januari';}
		else if($mth2=='02'){$mth2 = 'Februari';}
		else if($mth2=='03'){$mth2 = 'Mac';}
		else if($mth2=='04'){$mth2 = 'April';}
		else if($mth2=='05'){$mth2 = 'Mei';}
		else if($mth2=='06'){$mth2 = 'Jun';}
		else if($mth2=='07'){$mth2 = 'Julai';}
		else if($mth2=='08'){$mth2 = 'Ogos';}
		else if($mth2=='09'){$mth2 = 'September';}
		else if($mth2=='10'){$mth2 = 'Oktober';}
		else if($mth2=='11'){$mth2 = 'November';}
		else if($mth2=='12'){$mth2 = 'Disember';}

$date_dari = $day1.'hb '.$mth1.' '.$yr1;
$date_hingga = $day2.'hb '.$mth2.' '.$yr2;

	//2 b) Pecahan mengikut kaum (Bilangan peminjam) :-
	//Malay
	$sql = mysql_query("SELECT count(customer_loanpackage.customer_id) as count_race FROM customer_loanpackage JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_details.race = 'Malay' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result = mysql_fetch_assoc($sql);
	$malay_org = $result['count_race'];

	//Chinese
	$sql_1 = mysql_query("SELECT count(customer_loanpackage.customer_id) as count_race FROM customer_loanpackage JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_details.race = 'Chinese' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_1 = mysql_fetch_assoc($sql_1);
	$chinese_org = $result_1['count_race'];

	//Indian
	$sql_2 = mysql_query("SELECT count(customer_loanpackage.customer_id) as count_race FROM customer_loanpackage JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_details.race = 'Indian' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_2 = mysql_fetch_assoc($sql_2);
	$indian_org = $result_2['count_race'];

	//Bumiputera 
	$sql_3 = mysql_query("SELECT count(customer_loanpackage.customer_id) as count_race FROM customer_loanpackage JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_details.race = 'Bumiputera' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_3 = mysql_fetch_assoc($sql_3);
	$bumiputera_org = $result_3['count_race'];

	//Lain-lain 
	$sql_4 = mysql_query("SELECT count(customer_loanpackage.customer_id) as count_race FROM customer_loanpackage JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_details.race = 'Lain-lain' AND customer_details.race = ' ' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_4 = mysql_fetch_assoc($sql_4);
	$lain_org = $result_4['count_race'];

	//Bukan Warganegara 
	$sql_5 = mysql_query("SELECT count(customer_loanpackage.customer_id) as count_race FROM customer_loanpackage JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_details.race = 'Bukan Warganegara' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_5 = mysql_fetch_assoc($sql_5);
	$bukan_warganegara_org = $result_5['count_race'];

	$jumlah_kaum = $malay_org + $chinese_org + $indian_org + $bumiputera_org + $lain_org + $bukan_warganegara_org;

	// 2 a) Pecahan mengikut majikan : -
	//Kakitangan Awam
	$sql_6 = mysql_query("SELECT count(customer_employment.c_workingtype) as count_workingtype FROM customer_employment LEFT JOIN customer_loanpackage ON customer_employment.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_employment.c_workingtype='GOVERNMENT' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_6 = mysql_fetch_assoc($sql_6);
	$kakitangan_awam = $result_6['count_workingtype'];

	//Kakitangan Swasta
	$sql_7 = mysql_query("SELECT count(customer_employment.c_workingtype) as count_workingtype FROM customer_employment LEFT JOIN customer_loanpackage ON customer_employment.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_employment.c_workingtype='PRIVATE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_7 = mysql_fetch_assoc($sql_7);
	$kakitangan_swasta = $result_7['count_workingtype'];

	//Peniaga
	$sql_8 = mysql_query("SELECT count(customer_employment.c_workingtype) as count_workingtype FROM customer_employment LEFT JOIN customer_loanpackage ON customer_employment.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_employment.c_workingtype='OTHERS' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_8 = mysql_fetch_assoc($sql_8);
	$peniaga = $result_8['count_workingtype'];

	//Tidak Perkerjaan Tetap
	$sql_9 = mysql_query("SELECT count(customer_employment.c_workingtype) as count_workingtype FROM customer_employment LEFT JOIN customer_loanpackage ON customer_employment.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_employment.c_workingtype!=' ' AND customer_employment.c_workingtype!='GOVERNMENT' AND customer_employment.c_workingtype!='PRIVATE' AND customer_employment.c_workingtype!='OTHERS' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_9 = mysql_fetch_assoc($sql_9);
	$tidak_perkerjaan = $result_9['count_workingtype'];

	//Tiada maklumat / Tidak dinyatakan
	$sql_10 = mysql_query("SELECT count(customer_employment.c_workingtype) as count_workingtype FROM customer_employment LEFT JOIN customer_loanpackage ON customer_employment.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_employment.c_workingtype=' ' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_10 = mysql_fetch_assoc($sql_10);
	$tidak_maklumat = $result_10['count_workingtype'];

	$jumlah_majikan = $kakitangan_awam + $kakitangan_swasta + $peniaga + $tidak_perkerjaan + $tidak_maklumat;

	// 2 d) Pecahan mengikut pendapatan/ gaji :-
	//Bawah RM500
	$sql_11 = mysql_query("SELECT count(customer_financial.net_salary) as count_netsalary FROM customer_financial LEFT JOIN customer_loanpackage ON customer_financial.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_financial.net_salary <= '500' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_11 = mysql_fetch_assoc($sql_11);
	$gaji1 = $result_11['count_netsalary'];

	//RM501 - RM1,000
	$sql_12 = mysql_query("SELECT count(customer_financial.net_salary) as count_netsalary FROM customer_financial LEFT JOIN customer_loanpackage ON customer_financial.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_financial.net_salary >= '501' AND customer_financial.net_salary <='1000' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_12 = mysql_fetch_assoc($sql_12);
	$gaji2 = $result_12['count_netsalary'];

	//RM1,001 - RM5,000
	$sql_13 = mysql_query("SELECT count(customer_financial.net_salary) as count_netsalary FROM customer_financial LEFT JOIN customer_loanpackage ON customer_financial.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_financial.net_salary >= '1001' AND customer_financial.net_salary <='5000' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_13 = mysql_fetch_assoc($sql_13);
	$gaji3 = $result_13['count_netsalary'];

	//RM5,001 - RM10,000
	$sql_14 = mysql_query("SELECT count(customer_financial.net_salary) as count_netsalary FROM customer_financial LEFT JOIN customer_loanpackage ON customer_financial.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_financial.net_salary >= '5001' AND customer_financial.net_salary <='10000' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_14 = mysql_fetch_assoc($sql_14);
	$gaji4 = $result_14['count_netsalary'];

	//RM10,001 dan ke atas
	$sql_15 = mysql_query("SELECT count(customer_financial.net_salary) as count_netsalary FROM customer_financial LEFT JOIN customer_loanpackage ON customer_financial.customer_id = customer_loanpackage.customer_id WHERE customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_financial.net_salary >= '10001' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_15 = mysql_fetch_assoc($sql_15);
	$gaji5 = $result_15['count_netsalary'];

	$jumlah_gaji = $gaji1 + $gaji2 + $gaji3 + $gaji4 + $gaji5;

	// 2 c)Pecahan mengikut kaum (Nilai pinjaman) :-
	//Melayu
	$sql_16 = mysql_query("SELECT sum(loan_amount) as sum_loan_amount FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_details.race ='Malay' AND customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_16 = mysql_fetch_assoc($sql_16);
	$malay_pinjaman = number_format($result_16['sum_loan_amount'],'2');
	if($malay_pinjaman !=''){
		$malay_pinjaman = $malay_pinjaman;
	}else{
		$malay_pinjaman = '0.00';
	}

	//Chinese
	$sql_17 = mysql_query("SELECT sum(loan_amount) as sum_loan_amount FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_details.race ='Chinese' AND customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_17 = mysql_fetch_assoc($sql_17);
	$cina_pinjaman = number_format($result_17['sum_loan_amount'],'2');
	if($cina_pinjaman !=''){
		$cina_pinjaman = $cina_pinjaman;
	}else{
		$cina_pinjaman = '0.00';
	}

	//Indian
	$sql_18 = mysql_query("SELECT sum(loan_amount) as sum_loan_amount FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_details.race ='Indian' AND customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_18 = mysql_fetch_assoc($sql_18);
	$indian_pinjaman = number_format($result_18['sum_loan_amount'],'2');
	if($indian_pinjaman !=''){
		$indian_pinjaman = $indian_pinjaman;
	}else{
		$indian_pinjaman = '0.00';
	}

	//Bumiputera
	$sql_19 = mysql_query("SELECT sum(loan_amount) as sum_loan_amount FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_details.race ='Bumiputera' AND customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_19 = mysql_fetch_assoc($sql_19);
	$bumiputera_pinjaman = number_format($result_19['sum_loan_amount'],'2');
	if($bumiputera_pinjaman !=''){
		$bumiputera_pinjaman = $bumiputera_pinjaman;
	}else{
		$bumiputera_pinjaman = '0.00';
	}

	//Lain-lain
	$sql_20 = mysql_query("SELECT sum(loan_amount) as sum_loan_amount FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_details.race='Lain-lain' OR customer_details.race=' ' AND customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_20 = mysql_fetch_assoc($sql_20);
	$lain_pinjaman = number_format($result_20['sum_loan_amount'],'2');
	if($lain_pinjaman !=''){
		$lain_pinjaman = $lain_pinjaman;
	}else{
		$lain_pinjaman = '0.00';
	}

	//Bukan Warganegara
	$sql_21 = mysql_query("SELECT sum(loan_amount) as sum_loan_amount FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE customer_details.race ='Bukan Warganegara' AND customer_loanpackage.loan_package = 'NEW PACKAGE' AND customer_loanpackage.payout_date >='".$date_from."' AND customer_loanpackage.payout_date <='".$date_to."'");
	$result_21 = mysql_fetch_assoc($sql_21);
	$bukan_warganegara_pinjaman = number_format($result_21['sum_loan_amount'],'2');
	if($bukan_warganegara_pinjaman !=''){
		$bukan_warganegara_pinjaman = $bukan_warganegara_pinjaman;
	}else{
		$bukan_warganegara_pinjaman = '0.00';
	}

	$kaum_pinjaman = $result_16['sum_loan_amount'] + $result_17['sum_loan_amount'] + $result_18['sum_loan_amount'] + $result_19['sum_loan_amount'] + $result_20['sum_loan_amount'] + $result_21['sum_loan_amount'];
	$jumlah_kaum_pinjaman = number_format($kaum_pinjaman,'2');

	// 2 e) Pecahan mengikut amaun pinjaman :-
	//Bawah RM1,000
	$sql_22 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount <= '1000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_22 = mysql_fetch_assoc($sql_22);
	$pinjaman1 = $result_22['count_loan_amount'];

	//RM1,001 - RM5,000
	$sql_23 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount >= '1001' AND loan_amount <= '5000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_23 = mysql_fetch_assoc($sql_23);
	$pinjaman2 = $result_23['count_loan_amount'];

	//RM5,001 - RM10,000
	$sql_24 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount >= '5001' AND loan_amount <= '10000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_24 = mysql_fetch_assoc($sql_24);
	$pinjaman3 = $result_24['count_loan_amount'];

	//RM10,001 - RM50,000
	$sql_25 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount >= '10001' AND loan_amount <= '50000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_25 = mysql_fetch_assoc($sql_25);
	$pinjaman4 = $result_25['count_loan_amount'];

	//RM50,001 - RM100,000
	$sql_26 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount >= '50001' AND loan_amount <= '100000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_26 = mysql_fetch_assoc($sql_26);
	$pinjaman5 = $result_26['count_loan_amount'];

	//RM100,001 - RM1 juta
	$sql_27 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount >= '100001' AND loan_amount <= '1000000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_27 = mysql_fetch_assoc($sql_27);
	$pinjaman6 = $result_27['count_loan_amount'];

	//RM1 juta > dan ke atas
	$sql_28 = mysql_query("SELECT count(loan_amount) as count_loan_amount FROM customer_loanpackage WHERE loan_amount > '1000000' AND loan_package = 'NEW PACKAGE' AND payout_date >='".$date_from."' AND payout_date <='".$date_to."'");
	$result_28 = mysql_fetch_assoc($sql_28);
	$pinjaman7 = $result_28['count_loan_amount'];

	$jumlah_pinjaman = $pinjaman1 + $pinjaman2 + $pinjaman3 + $pinjaman4 + $pinjaman5 + $pinjaman6 + $pinjaman7;

	// 1 a) Jumlah Pinjaman (Pokok) yang diluluskan - (Tahun dilaporkan sahaja)
	$sql_29 = mysql_query("SELECT sum(loan_pokok) as sum_loan_pokok, sum(loan_total) as sum_loan_amount FROM customer_loanpackage WHERE payout_date >='".$date_from."' AND payout_date <='".$date_to."' AND loan_package = 'NEW PACKAGE'");	
	$result_29 = mysql_fetch_assoc($sql_29);
	$jumlah_pinjaman_pokok = number_format($result_29['sum_loan_pokok'],'2');
	$jumlah_loan_amount = number_format($result_29['sum_loan_amount'],'2');
	// 1 b) Jumlah faedah (Bunga) yang patut dikutip (Dibayar balik)
	$jumlah_faedah_1 = $result_29['sum_loan_amount']-$result_29['sum_loan_pokok'];
	$jumlah_faedah = number_format($jumlah_faedah_1,'2');
	//1 e) Jumlah pinjaman mengikut kategori peminjam i.Individu (Sila isi butiran di No. 2)
	$jumlah_pinjaman_1 = $result_29['sum_loan_pokok']+$jumlah_faedah_1;
	$jumlah_pinjaman_individu = number_format($jumlah_pinjaman_1,'2');

	$sql_30 = mysql_query("SELECT count(id) as perjanjian FROM customer_loanpackage WHERE payout_date >='".$date_from."' AND payout_date <='".$date_to."' AND loan_package = 'NEW PACKAGE'");
	$result_30 = mysql_fetch_assoc($sql_30);
	$perjanjian = $result_30['perjanjian'];

	//c) Jumlah kutipan semula pinjaman i. Kutipan semula pinjaman (Pokok + Faedah) - (Tahun dilaporkan sahaja)
	$sql_31 = mysql_query("SELECT sum(loan_lejar_details.payment) as payment FROM loan_lejar_details JOIN customer_loanpackage ON loan_lejar_details.customer_loanid = customer_loanpackage.id WHERE loan_lejar_details.payment_date >='".$date_from."'  AND loan_lejar_details.payment_date <='".$date_to."' AND customer_loanpackage.payout_date>='".$date_from."'  AND customer_loanpackage.payout_date <='".$date_to."' AND (customer_loanpackage.settlement is null or customer_loanpackage.settlement=' ' )");
	$result_31 = mysql_fetch_assoc($sql_31);

	$sql_32 = mysql_query("SELECT sum(loan_total) as loan_total FROM customer_loanpackage where payout_date>='".$date_from."' AND payout_date <='".$date_to."' AND (settlement is null or settlement=' ' )");
	$result_32 = mysql_fetch_assoc($sql_32);

	$total_baki_1 = $result_32['loan_total'] - $result_31['payment'];
	$total_baki = number_format($total_baki_1,'2'); 

	//c) Jumlah kutipan semula pinjaman ii. Kutipan terkumpul semula pinjaman (Pokok + Faedah) - (Selain dari tahun yang dilaporkan)
	$sql_33 = mysql_query("SELECT sum(loan_lejar_details.payment) as payment FROM loan_lejar_details JOIN customer_loanpackage ON loan_lejar_details.customer_loanid = customer_loanpackage.id WHERE customer_loanpackage.payout_date <='".$date_from."' AND loan_lejar_details.payment_date >='".$date_from."'  AND loan_lejar_details.payment_date <='".$date_to."' ");
	$result_33 = mysql_fetch_assoc($sql_33);

	$total_baki_2 = $result_33['payment'];
	$total_baki2 = number_format($total_baki_2,'2'); 


	
}


$mpdf = new mPDF('','A4','','',10,10,10,5,10,10,'P');
$html.='<div style="text-align:right;">
			<b>LAMPIRAN B</b>
		</div>

		<br>

		<div style="text-align:center;font-size:12px;">
			<b>PERATURAN-PERATURAN PEMBERI PINJAM WANG (KAWALAN DAN PELESENAN) 2003 <br> [Subperaturan 16(1) Akta 400] <br> REKOD TRANSAKSI TAHUNAN PEMBERI PINJAM WANG BAGI TAHUN '.$date.'
		</div>

		<br>

		<div style="font-size:12px;">
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:12px;">
			<tr>
				<td width="30%" style="text-align:left;">Nama Syarikat Pemberi Pinjam Wang</td>
				<td width="1%">:</td>
				<td colspan="4" style="border-bottom: 1px solid;">KTL SETIA REALTY SDN BHD</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">No.Lesen Pemberi Pinjam Wang</td>
				<td width="1%">:</td>
				<td  colspan="4" style="border-bottom: 1px solid;">WL4283/07/01-8/121020</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">Bentuk Orgranisasi</td>
				<td width="1%">:</td>
				<td colspan="4" style="border-bottom: 1px solid;">Enterprise (Pemilik Tunggal/ Perkongsian)/ Sendirian Berhad/ Berhad * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SDN BHD</b> </td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">Poskod Alamat Operasi</td>
				<td width="1%">:</td>
				<td style="border-bottom: 1px solid;">12000</td>
				<td width="15%" style="text-align:left;">&nbsp;&nbsp;Bandar</td>
				<td width="1%">:</td>
				<td style="border-bottom: 1px solid;">BUTTERWORTH</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">Tempoh Laporan Operasi</td>
				<td width="1%">:</td>
				<td style="border-bottom: 1px solid;">Dari <b> '.$date_dari.' </b></td>
				<td style="text-align:left;">&nbsp;&nbsp;hingga</td>
				<td width="1%">:</td>
				<td style="border-bottom: 1px solid;"> <b> '.$date_hingga.' </b></td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">Individu Yang Boleh Dihubungi</td>
				<td width="1%">:</td>
				<td colspan="4" style="border-bottom: 1px solid;"><b>TIEW BOK KIAK</b></td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">No. Telefon Talian Tetap</td>
				<td width="1%">:</td>
				<td  style="border-bottom: 1px solid;"><b>04-3319999</b></td>
				<td style="text-align:left;">No. Telefon Bimbit</td>
				<td width="1%">:</td>
				<td  style="border-bottom: 1px solid;"><b>012-4718882</b></td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;">Emel</td>
				<td width="1%">:</td>
				<td colspan="4" style="border-bottom: 1px solid;">ktlsetiarealtysdnbhd@gmail.com</td>
			</tr>
			</table>
		</div>

		<br>

		<div style="font-size:12px;">
			<table width="100%" align="center"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:12px;">
			<tr style="background-color:lightgrey;">
				<td width="7%" style="text-align:center;border:1px solid;">Bil</td>
				<td style="text-align:center;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Maklumat</td>
				<td width="20%" style="text-align:center;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Jumlah & Bilangan</td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;">1</td>
				<td style="text-align:left;border-right:1px solid;"><b>&nbsp;&nbsp;&nbsp;Butiran Pinjaman Keseluruhan : </b></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;a) Jumlah Pinjaman (Pokok) yang diluluskan - (Tahun dilaporkan sahaja)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$jumlah_pinjaman_pokok.'</td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;b) Jumlah faedah (Bunga) yang patut dikutip (Dibayar balik)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$jumlah_faedah.'</td>			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;c) Jumlah kutipan semula pinjaman</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. Kutipan semula pinjaman (Pokok + Faedah) - (Tahun dilaporkan sahaja)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$total_baki.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. Kutipan terkumpul semula pinjaman (Pokok + Faedah) - (Selain dari tahun yang &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dilaporkan)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$total_baki2.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;d) Bilangan perjanjian yang ditandatangani</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. Perjanjian bercagar (Jadual K)</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">perjanjian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. Perjanjian tak bercagar (Jadual J)</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$perjanjian.'&nbsp;&nbsp;&nbsp;perjanjian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii. Jumlah perjanjian</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$perjanjian.'&nbsp;&nbsp;&nbsp;perjanjian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv. Jenis-jenis cagaran</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">Cagaran&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;e) Jumlah pinjaman mengikut kategori peminjam</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i.Individu (Sila isi butiran di No. 2)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$jumlah_pinjaman_individu.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii.Syarikat (Sila isi butiran di No. 3)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM </td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;border-bottom:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;border-bottom:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;">2</td>
				<td style="text-align:left;border-right:1px solid;"><b>&nbsp;&nbsp;&nbsp;Butiran Peminjam Individu  :</b></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;a) Pecahan mengikut majikan : -</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. K/tangan awam</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$kakitangan_awam.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. K/tangan swasta</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$kakitangan_swasta.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii. Peniaga/kerja sendiri</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$peniaga.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv. Tiada pekerjaan tetap</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$tidak_perkerjaan.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;v. Tiada maklumat / Tidak dinyatakan</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$tidak_maklumat.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:right;border-right:1px solid;"><b>'.$jumlah_majikan.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;b) Pecahan mengikut kaum (Bilangan peminjam) :-</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. Melayu</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$malay_org.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. Cina</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$chinese_org.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii. India</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$indian_org.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv. Bumiputera (Sabah/ Sarawak)</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$bumiputera_org.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;v. Lain-lain</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$lain_org.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vi. Bukan Warganegara</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$bukan_warganegara_org.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:right;border-right:1px solid;"><b>'.$jumlah_kaum.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;c) Pecahan mengikut kaum (Nilai pinjaman) :-</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. Melayu</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$malay_pinjaman.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. Cina</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$cina_pinjaman.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii. India</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$indian_pinjaman.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv. Bumiputera (Sabah/ Sarawak)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$bumiputera_pinjaman.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;v. Lain-lain</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$lain_pinjaman.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vi. Bukan Warganegara</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$bukan_warganegara_pinjaman.'</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"><b>&nbsp;&nbsp;&nbsp;&nbsp;RM&nbsp;&nbsp;&nbsp;'.$jumlah_kaum_pinjaman.'</b></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;d) Pecahan mengikut pendapatan/ gaji :-</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. Bawah RM500</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$gaji1.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. RM501 - RM1,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$gaji2.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii. RM1,001 - RM5,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$gaji3.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv. RM5,001 - RM10,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$gaji4.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;v. RM10,001 dan ke atas</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$gaji5.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:right;border-right:1px solid;"><b>'.$jumlah_gaji.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;e) Pecahan mengikut amaun pinjaman :-</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i. Bawah RM1,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman1.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii. RM1,001 - RM5,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman2.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii. RM5,001 - RM10,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman3.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv. RM10,001 - RM50,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman4.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;v. RM50,001 - RM100,000</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman5.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vi. RM100,001 - RM1 juta</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman6.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vii. RM1 juta > dan ke atas</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">'.$pinjaman7.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:right;border-right:1px solid;"><b>'.$jumlah_pinjaman.'&nbsp;&nbsp;&nbsp;orang&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;border-bottom:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;border-bottom:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;">3</td>
				<td style="text-align:left;border-right:1px solid;"><b>&nbsp;&nbsp;&nbsp;Butiran Peminjam Syarikat (Tahun Dilaporkan) : </b></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;a) Pecahan mengikut jenis syarikat (Nilai pinjaman)</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bumiputra</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bukan Bumiputra</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Syarikat Asing</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RM</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"><b>&nbsp;&nbsp;&nbsp;&nbsp;RM</b></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;"></td>
			</tr>

			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;b) Bilangan peminjam syarikat</p></td>
				<td width="20%" style="text-align:left;border-right:1px solid;"></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bumiputra</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">syarikat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bukan Bumiputra</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">syarikat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"></td>
				<td style="text-align:left;border-right:1px solid;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Syarikat Asing</p></td>
				<td width="20%" style="text-align:right;border-right:1px solid;">syarikat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;"><table align="right"><tr><td><b>Jumlah</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>
				<td width="20%" style="text-align:right;border-right:1px solid;"><b>syarikat&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			</tr>
			<tr>
				<td width="7%" style="text-align:center;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;"><br></td>
				<td style="text-align:left;border-right:1px solid;border-bottom:1px solid;"></td>
				<td width="20%" style="text-align:center;border-right:1px solid;border-bottom:1px solid;"></td>
			</tr>
			</table>
		</div>

		<div>
			<table width="100%" align="center"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:12px;">
				<tr>
					<td>* Potong yang mana tidak berkenaan</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
				<td><p><span>Saya dengan ini mengaku bahawa butir-butir di atas adalah benar. Saya faham bahawa apa-apa kenyataan yang mengelirukan, atau perihal palsu butir-butir di atas adalah suatu kesalahan di bawah Peraturan-Peraturan Pemberi Pinjam Wang (Kawalan dan Pelesenan 2003) serta menurut Akta Akuan Berkanun 1960.</span></p></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
			</table>
		</div>

		<div>
			<table width="50%" align="left"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:12px;">
				<tr>
					<td width="23%">Tandatangan</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;"></td>
				</tr>
				<tr>
					<td width="23%">Nama</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;"></td>
				</tr>
				<tr>
					<td width="23%">Jawatan</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;"></td>
				</tr>
				<tr>
					<td width="23%">Tarikh</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;"></td>
				</tr>
			</table>
		</div>';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>