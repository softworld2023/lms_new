<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$loan_code = $_GET['id'];

$sql_1 = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$loan_code."'");
$result_1 = mysql_fetch_assoc($sql_1);
$loan_pokok = $result_1['loan_pokok'];
// $loan_faedah = $result_1['loan_total'] - $result_1['loan_pokok'];

$sql_2 = mysql_query("SELECT * FROM customer_details WHERE id = '".$result_1['customer_id']."'");
$result_2 = mysql_fetch_assoc($sql_2);
$race_1 = $result_2['race'];
$race = $race_1;

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
else
{
	$majikan = '* Kerajaan / Swasta / Berniaga / Kerja sendiri / Tidak Bekerja';
}

$sql_5 = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$result_1['customer_id']."'");
$result_5 = mysql_fetch_assoc($sql_5);

$loan_period = $result_1['loan_period'];
$tempoh_bayaran_minggu = $loan_period * 4;
$tempoh_bayaran_bulan = $loan_period;
$loan_amount = $result_1['loan_amount'];
$loan_period_month = $loan_period.'months';
$sql_6 = mysql_query("SELECT $loan_period_month FROM new_package WHERE loan_amount = '$loan_amount'");
$result_6 = mysql_fetch_row($sql_6);
$mth_payment= $result_6[0];

$sql = "SELECT balance FROM loan_lejar_details WHERE receipt_no = '".$result_1['loan_code']."' ORDER BY id ASC LIMIT 1";
$query = mysql_query($sql);
$result = mysql_fetch_assoc($query);
$balance = $result['balance'];
$loan_faedah = $balance - $loan_pokok;

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

// if($result_1['loan_lejar_date']!='')
// {
// 	$loan_lejar_date = date("d-m-Y",strtotime($result_1['loan_lejar_date']));
// }
// else
// {
	$loan_lejar_date = '';	
// }

$tempoh_bulanan=$tempoh_bayaran_minggu/4;

// $mpdf = new mPDF('','A4','','',10,10,128,5,5,5,'P');
$mpdf = new mPDF('','A4','','',10,10,10,5,5,5,'P');


$header = '<div style="text-align:center;font-size:16px;">
			<b>AKTA PEMBERI PINJAM WANG 1951</b>
			<br>
			&lt;Subseksyen 18(1)&gt;
			<br>
			<br>
			<b>LEJAR AKAUN PEMINJAM</b>
		</div>';

$header .=
	'<div>
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px; overflow: visible !important; ">
		<tr>
			<td colspan="2" style="text-align:left;border-bottom: 1px solid;"><b>1. BUTIRAN PEMINJAM</b></td>
			<td colspan="1" style="border-bottom: 1px solid;"></td>
			<td colspan="1" style="border-bottom: 1px solid;text-align:center;">'.$result_3['mobile_contact'].'</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left; border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">&nbsp;Nama</td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">'.$result_2['name'].' </td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">&nbsp;Jika Syarikat</td>
			<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Taraf: '.$taraf.'</td>
			<td colspan="1" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;No.Daftar Perniagaan:</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">&nbsp;Jika Individu</td>
			<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;No. K.P: '.$result_2['nric'].'</td>
			<td colspan="1" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Bangsa: '.$race.'</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;"></td>
			<td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Pekerjaan: '.$result_4['position'].'</td>
			<td colspan="1" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Pendapatan: RM '.number_format($result_5['basic_salary'],'2').'</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;">&nbsp;'.$loan_code.'</td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Majikan: '.$majikan.'</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;">&nbsp;'.$result_2['customercode2'].'</td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;Alamat Rumah: '.$result_3['address1'].'</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;"></td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">'.$address2.'</td>
		</tr>
		<tr>
			<td colspan="1" style="text-align:left;border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;"></td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">'.$address3.'</td>
		<tr>
			<td colspan="3" style="text-align:left;border-left: 1px solid; border-bottom: 1px solid;">&nbsp;Jenis Cagaran(jika berkaitan)</td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:left;border-left: 1px solid; border-bottom: 1px solid;">&nbsp;Anggaran nilai semasa (RM)</td>
			<td colspan="3" style="border-bottom: 1px solid; border-right: 1px solid;">&nbsp;</td>
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
			<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Tempoh bayaran (bulan)</td>
			<td width="17%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">Bayaran sebulan (RM)</td>
		</tr>
		<tr>
			<td width="13%" style="text-align:center; border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;">'.$loan_lejar_date.'</td>
			<td width="8%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($loan_pokok,'2').'</td>
			<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($loan_faedah,'2').' </td>
			<td width="8%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($balance,'2').'</td>
			<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;"> 18% </td>
			<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;"> TB </td>
			<td width="10%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;"> '.$tempoh_bayaran_bulan.'</td>
			<td width="17%" style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;">'.number_format($mth_payment,'2').'</td>
		</tr>
		</table>
	</div>

	<div>
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
		<tr>
			<td colspan="6" style="text-align:left;border-bottom: 1px solid;"><b>3. BUTIRAN BAYAR BALIK</b></td>
		</tr>
		<tr>
			<td width="11%" style="text-align:center;border-left: 1px solid;border-right:1px solid;border-bottom: 1px solid;">Tarikh</td>
			<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;">Jumlah Besar Pinjaman (RM)</td>
			<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;">Bayaran Balik Pinjaman (RM)</td>
			<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;">Baki Pinjaman (RM)</td>
			<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;">No.Resit</td>
			<td width="30%" style="text-align:left;border-bottom: 1px solid;border-right: 1px solid;">Catatan: <br> 1. Pinjaman Selesai <br> 2. Pinjaman Semasa <br> 3. Dalam Proses Dapat Balik <br> 4. Dalam Tindakan Mahkamah</td>
		</tr>
	';

$html .= $header;

$ctr=0;
$sql_7 = mysql_query("SELECT *,loan_lejar_details.bad_debt FROM loan_lejar_details JOIN customer_loanpackage ON loan_lejar_details.customer_loanid = customer_loanpackage.id JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id WHERE loan_lejar_details.receipt_no = '".$loan_code."' ORDER BY loan_lejar_details.id ASC LIMIT 1");
$no_resit = '';

// this will loop through all records with the current loan code and the final $no_resit is from the last record
while($row = mysql_fetch_assoc($sql_7))
{
	$no_resit = !empty($row['no_resit']) ? str_pad($row['no_resit'], 4, '0', STR_PAD_LEFT) : '';
}

$sql_8 = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$loan_code."'");
$result_8 = mysql_fetch_assoc($sql_8);
$maxrow = $result_8['loan_period'];

$len = $maxrow > 24 ? 24 : $maxrow;

for ($i = $ctr; $i < $len; $i++) {
	$receipt_no = $i == 0 ? $no_resit : '';

	$html.='<tr style="border:1px solid black;">
	<td width="11%" style="text-align:center;border-left: 1px solid;border-right:1px solid;border-bottom: 1px solid;">&nbsp;</td>
	<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
	<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
	<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
	<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;">' . $receipt_no . '</td>
	<td width="30%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td></tr>';
}

$html .= '</table>
</div>
';

if ($maxrow > 24) {
	$html .= $header;
	
	for ($i=0; $i < $maxrow - 24; $i++){
		$html.='<tr style="border:1px solid black;">
					<td width="11%" style="text-align:center;border-left: 1px solid;border-right:1px solid;border-bottom: 1px solid;">&nbsp;</td>
					<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
					<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
					<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
					<td width="11%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
					<td width="30%" style="text-align:center;border-bottom: 1px solid;border-right: 1px solid;"></td>
				</tr>';
	}
	
	$html .= '</table>
	</div>
	';
}

$mpdf->WriteHTML($html);
$mpdf->Output();

?>