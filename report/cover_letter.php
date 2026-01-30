<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$year = $_GET['select'];
$date_from = $year.'-01-01';
$date_to = $year.'-12-31';

$sql = mysql_query("SELECT * FROM profile_kpkt");
$result_1 = mysql_fetch_assoc($sql);

$date1 = $result_1['cl_tarikh1'];
$date2 = $result_1['cl_tarikh2'];
$date3 = $result_1['cl_tarikh3'];

$yr1 = date('Y', strtotime($date1));
$mth1 = date('m', strtotime($date1));
$day1 = date('d', strtotime($date1));

$yr2 = date('Y', strtotime($date2));
$mth2 = date('m', strtotime($date2));
$day2 = date('d', strtotime($date2));

$yr3 = date('Y', strtotime($date3));
$mth3 = date('m', strtotime($date3));
$day3 = date('d', strtotime($date3));

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

		if($mth3=='01'){$mth3 = 'Januari';}
		else if($mth3=='02'){$mth3 = 'Februari';}
		else if($mth3=='03'){$mth3 = 'Mac';}
		else if($mth3=='04'){$mth3 = 'April';}
		else if($mth3=='05'){$mth3 = 'Mei';}
		else if($mth3=='06'){$mth3 = 'Jun';}
		else if($mth3=='07'){$mth3 = 'Julai';}
		else if($mth3=='08'){$mth3 = 'Ogos';}
		else if($mth3=='09'){$mth3 = 'September';}
		else if($mth3=='10'){$mth3 = 'Oktober';}
		else if($mth3=='11'){$mth3 = 'November';}
		else if($mth3=='12'){$mth3 = 'Disember';}

$cl_tarikh1 = $day1.' '.$mth1.' '.$yr1;
$cl_tarikh2 = $day2.' '.$mth2.' '.$yr2;
$cl_tarikh3 = $day3.' '.$mth3.' '.$yr3;


$mpdf = new mPDF('','A4','','',20,20,10,20,20,20,'P');
$html.='<div style="text-align:center;font-size:10px;">
			<b><span style="font-size:22px;">MJ MAJUSAMA SDN BHD</span></b>
			<br>
			932747-M
			<br>
			No 3627 Jalan Bagan Luar 12000 Butterworth Pulau Pinang
			<br>
			Tel:04-3319999
		</div>
		<br><br><br>
		<div>
			<table width="50%" align="left" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:12px;">
			<tr>
				<td width="30%" style="text-align:left;">Rujukan Tuan:</td>
				<td style="text-align:left;">KPKT/BL/19/W4283/07/01</td>
			</tr>
			<tr>
				<td style="text-align:left;">Tarikh</td>
				<td style="text-align:left;">'.$cl_tarikh1.'</td>
			</tr>
			</table>
		</div>
		<br>
		<diV>
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:12px;">
		<tr><td> <b>KEMENTERIAN KESEJAHTERAAN BANDAR, PERUMAHAN DAN KERAJAAN TEMPATAN</b> </td></tr>
		<tr><td>Bahagian Pemberi Pinjam Wang & Pemegang Pajak Gadai</td></tr>
		<tr><td>No. 51, Persiaran Perdana, Presint 4</td></tr>
		<tr><td>62100 <b>PUTRAJAYA</b></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>Tuan,</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><b>PER: PERATURAN-PERATURAN PEMBERI PINJAM WANG (KAWALAN DAN PELESENAN) 2003 – REKOD TRANSAKSI '.$result_1['cl_year'].'</b></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>Dengan hormatnya saya merujuk perkara diatas.</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>Bagi mematuhi keperluan peraturan dibawah Subperaturan 16 (1), Peraturan-Peraturan Pemberi Pinjam Wang (Kawalan Dan Pelesenan) 2003, saya kemukakan Rekod Transaksi Lesen Semasa (Lampiran ’B’ dan ’B1’ ) bagi tempoh <b>'.$cl_tarikh2.'</b> hingga <b>'.$cl_tarikh3.'</b>.</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>Yang Benar,</td></tr>
		<tr><td style="padding-top:50px;"><b>_____________________________</b>&nbsp;</td></tr>
		<tr><td><b>TIEW BOK KIAK</b></td></tr>
		<tr><td>Pengarah</td></tr>
			</table>
		</div>

		<br>

		';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>