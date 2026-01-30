<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$loan_code = $_GET['id'];

$sql_1 = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$loan_code."'");
$result_1 = mysql_fetch_assoc($sql_1);
$loan_faedah = $result_1['loan_total'] - $result_1['loan_pokok'];

$sql_2 = mysql_query("SELECT * FROM customer_details WHERE id = '".$result_1['customer_id']."'");
$result_2 = mysql_fetch_assoc($sql_2);
$race_1 = $result_2['race'];

if($race_1 == 'Malay')
{
	$race = 'MELAYU';
	$taraf = ' * <u>Bumi</u> / Bukan Bumi / Asing';
}elseif ($race_1 == 'Chinese')
{
	$race = 'CINA';
	$taraf = ' * Bumi / <u>Bukan Bumi</u> / Asing';
}elseif ($race_1 == 'Indian') {
	$race = 'INDIA';
	$taraf = ' * Bumi / <u>Bukan Bumi</u> / Asing';
}

$sql_3 = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$result_1['customer_id']."'");
$result_3 = mysql_fetch_assoc($sql_3);

$sql_4 = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$result_1['customer_id']."'");
$result_4 = mysql_fetch_assoc($sql_4);
$c_workingtype = $result_4['c_workingtype'];

if($c_workingtype == 'PRIVATE')
{
	$majikan = '* Kerajaan / <u>Swasta</u> / Berniaga / Kerja sendiri / Tidak bekerja';
}elseif($c_workingtype == 'GOVERNMENT')
{
	$majikan = '* <u>Kerajaan</u> / Swasta / Berniaga / Kerja sendiri / Tidak bekerja';
}elseif($c_workingtype == 'SELF EMPLOYED')
{
	$majikan = '* Kerajaan / Swasta / Berniaga / <u>Kerja sendiri</u> / Tidak bekerja';
}
elseif($c_workingtype == 'RETIRED')
{
	$majikan = '* Kerajaan / Swasta / Berniaga / Kerja sendiri / <u>Tidak bekerja</u>';
}elseif($c_workingtype == 'OTHERS')
{
	$majikan = '* <u>Kerajaan</u> / Swasta / Berniaga / Kerja sendiri / Tidak Bekerja';
}

$sql_5 = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$result_1['customer_id']."'");
$result_5 = mysql_fetch_assoc($sql_5);

$loan_period = $result_1['loan_period'];
$tempoh_bayaran_minggu = $loan_period * 4;
$loan_amount = $result_1['loan_amount'];
$loan_period_month = $loan_period.'months';
$sql_6 = mysql_query("SELECT $loan_period_month FROM new_package WHERE loan_amount = '$loan_amount'");
$result_6 = mysql_fetch_row($sql_6);
$mth_payment= $result_6[0];


if($result_3['address2']!='' || $result_3['address3']!='')
{
	$address2 = '&nbsp;'.$result_3['address2'].','.$result_3['address3'].'';
	$address3 = '&nbsp;'.$result_3['postcode'].'&nbsp;'.$result_3['city'].'&nbsp;'.$result_3['state'].' ';
}
else
{
	$address2 = '&nbsp;'.$result_3['postcode'].'&nbsp;'.$result_3['city'].'&nbsp;'.$result_3['state'].' ';
	$address3 = '&nbsp;';
}



$mpdf = new mPDF('','A4','','',10,10,10,5,10,10,'P');
$html.='<div style="text-align:center;font-size:16px;">
			<b>AKTA PEMBERI PINJAM WANG 1951</b>
			<br>
			&lt;Subseksyen 18(1)&gt;
			<br>
			<br>
			<b>LEJAR AKAUN PEMINJAM</b>
		</div>
		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
			<tr>
				<td width="25%" style="text-align:left;border-bottom: 1px solid;"><b>1. BUTIRAN PEMINJAM</b></td>
				<td  style="border-bottom: 1px solid;"></td>
				<td style="border-bottom: 1px solid;text-align:center;">'.$result_3['mobile_contact'].'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left; border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">&nbsp;Nama</td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">'.$result_2['name'].' </td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">&nbsp;Jika Syarikat</td>
				<td style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Taraf: '.$taraf.'</td>
				<td style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;No.Daftar Perniagaan:</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">&nbsp;Jika Individu</td>
				<td style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;No. K.P: '.$result_2['nric'].'</td>
				<td style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Bangsa: '.$race.'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;"></td>
				<td style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Pekerjaan: '.$result_4['position'].'</td>
				<td style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Pendapatan: RM '.number_format($result_5['basic_salary'],'2').'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;">&nbsp;'.$loan_code.'</td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Majikan: '.$majikan.'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;">&nbsp;'.$result_2['customercode2'].'</td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Alamat Rumah: '.$result_3['address1'].'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;"></td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">'.$address2.'</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;"></td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">'.$address3.'</td>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-bottom: 1px solid;">&nbsp;Jenis Cagaran(jika berkaitan)</td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;</td>
			</tr>
			<tr>
				<td width="30%" style="text-align:left;border-left: 1px solid; border-bottom: 1px solid;">&nbsp;Anggaran nilai semasa (RM)</td>
				<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;</td>
			</tr>
			</table>
		</div>

		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
			<tr>
				<td colspan="8" style="text-align:left;border-bottom: 1px solid;"><b>2. BUTIRAN PINJAMAN</b></td>
			</tr>
			<tr>
				<td width="13%" style="text-align:center; border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">Tarikh</td>
				<td width="8%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Pinjaman Pokok (RM)</td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Jumlah Faedah (RM)</td>
				<td width="8%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Jumlah Besar (RM)</td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Kadar faedah setahun </td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Bercagar / Tidak Bercagar</td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Tempoh bayaran (minggu)</td>
				<td width="17%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Bayaran sebulan (RM)</td>
			</tr>
			<tr>
				<td width="13%" style="text-align:center; border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">'.date("d-m-Y",strtotime($result_1['loan_lejar_date'])).'</td>
				<td width="8%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($result_1['loan_pokok'],'2').'</td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($loan_faedah,'2').' </td>
				<td width="8%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($result_1['loan_total'],'2').'</td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;"> 18% </td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;"> TB </td>
				<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;"> '.$tempoh_bayaran_minggu.'</td>
				<td width="17%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($mth_payment,'2').'</td>
			</tr>
			</table>
		</div>
		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
			<tr>
				<td colspan="6" style="text-align:left;"><b>3. BUTIRAN BAYAR BALIK</b></td>
			</tr>
			</table>
			<table width="100%" align="center"  cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;border-collapse: collapse;
">
			<tr style="border:1px solid black;">
				<td width="11%" style="text-align:center;border:1px solid black;  ">Tarikh</td>
				<td width="11%" style="text-align:center;border:1px solid black;">Jumlah Besar Pinjaman (RM)</td>
				<td width="11%" style="text-align:center;border:1px solid black;">Bayaran Balik Pinjaman (RM)</td>
				<td width="11%" style="text-align:center;border:1px solid black;">Baki Pinjaman (RM)</td>
				<td width="11%" style="text-align:center;border:1px solid black;">No.Resit</td>
				<td width="30%" style="text-align:left;border:1px solid black;">Catatan: <br> 1. Pinjaman Selesai <br> 2. Pinjaman Semasa <br> 3. Dalam Proses Dapat Balik <br> 4. Dalam Tindakan Mahkamah</td>
			</tr>';
			$ctr=0;
			$sql_7 = mysql_query("SELECT *,loan_lejar_details.bad_debt FROM loan_lejar_details JOIN customer_loanpackage ON loan_lejar_details.customer_loanid = customer_loanpackage.id JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE loan_lejar_details.receipt_no = '".$loan_code."'AND loan_lejar_details.payment_date!='0000-00-00'");
						while($result_7 = mysql_fetch_assoc($sql_7))
						{ 
							$payment_date = $result_7['payment_date'];
							$balance = $result_7['balance'];
							$payment = $result_7['payment'];
							$settle = $result_7['loan_settle'];
							$blacklist = $result_7['blacklist'];
							$blacklistamt = $result_7['blacklistamt'];

							if($settle =='yes'){
							$baki_pinjaman ='0';
							}
							else
							{
							$baki_pinjaman = $balance - $payment;
							}

							if($baki_pinjaman !='0')
							{
								$catatan = '2';
							}else {
								$catatan = '1';
							}

							if($result_7['bad_debt']=='yes')
							{
								$catatan = '3';
							}

							$ctr++;



	$html.='<tr style="border:1px solid black;">
				<td width="11%" style="text-align:center;border:1px solid black;  ">'.date("d-m-Y",strtotime($payment_date)).'</td>
				<td width="11%" style="text-align:center;border:1px solid black;">'.number_format($balance,'2').'</td>
				<td width="11%" style="text-align:center;border:1px solid black;">'.number_format($payment,'2').'</td>
				<td width="11%" style="text-align:center;border:1px solid black;">'.number_format($baki_pinjaman,'2').'</td>
				<td width="11%" style="text-align:center;border:1px solid black;">'.$result_7['no_resit'].'</td>
				<td width="30%" style="text-align:left;border:1px solid black;">'.$catatan.'</td></tr>';}

						$sql_8 = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$loan_code."'");
						$result_8 = mysql_fetch_assoc($sql_8);
							$maxrow = $result_8['loan_period'];



			for($i=$ctr;$i<$maxrow;$i++){

				$html.='<tr style="border:1px solid black;">
				<td width="11%" style="text-align:center;border:1px solid black;">&nbsp;</td>
				<td width="11%" style="text-align:center;border:1px solid black;"></td>
				<td width="11%" style="text-align:center;border:1px solid black;"></td>
				<td width="11%" style="text-align:center;border:1px solid black;"></td>
				<td width="11%" style="text-align:center;border:1px solid black;"></td>
				<td width="30%" style="text-align:center;border:1px solid black;"></td></tr>';
			}
$html.='
			</table>
		</div>


		';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>