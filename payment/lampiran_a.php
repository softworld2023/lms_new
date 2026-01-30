<?php include('../include/dbconnection.php'); 
$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$loan = mysql_fetch_assoc($loan_q);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
$cust = mysql_fetch_assoc($cust_q);

$address_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$id."'");
$address = mysql_fetch_assoc($address_q);

$company_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$id."'");
$company = mysql_fetch_assoc($company_q);

$loan2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND month = '1'");
$loan2 = mysql_fetch_assoc($loan2_q);

$salary_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$id."'");
$salary = mysql_fetch_assoc($salary_q);

$loanint = number_format(($loan['loan_total'] - $loan['loan_amount']), '2', '.', '');

$repayment_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
$rrow = mysql_num_rows($repayment_q);
?>

<style>
table, tr, td {
	font-family:Arial Narrow;
	font-size:16px;
}

td {
	padding-left:5px;
}
</style>
<title>LAMPIRAN A</title><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="15%">&nbsp;</td>
    <td width="71%">&nbsp;</td>
    <td width="14%" align="right"><b>LAMPIRAN 'A'</b></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><b>AKTA PEMBERI PINJAM WANG 1951</b></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><b>[Subsekyen 18 (1)]</b></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><b><u>LEJAR AKAUN PEMINJAM</u></b></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="90%" align="center">
	<tr>
		<td><strong>1. BUTIRAN PEMINJAM</strong></td>
	</tr>
</table>
<table width="90%" border="1" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="15%">NAMA</td>
    <td colspan="3"><?php echo strtoupper($cust['name']); ?></td>
  </tr>
  <tr>
    <td>JIKA SYARIKAT</td>
    <td>TARAF: BUMI / BUKAN BUMI / ASING </td>
    <td colspan="2">NO PENDAFTARAN:</td>
  </tr>
  <tr>
    <td rowspan="4" valign="top">JIKA INDIVIDU</td>
    <td>NO KP: <?php echo $cust['nric']; ?></td>
    <td colspan="2">BANGSA: <?php echo strtoupper($cust['race']); ?></td>
  </tr>
  <tr>
    <td>PEKERJAAN: <?php echo strtoupper($company['position']); ?></td>
    <td colspan="2">PENDAPATAN: <?php if($salary['net_salary'] != '0') { echo "RM ".$salary['net_salary']; } ?></td>
  </tr>
  <tr>
    <td colspan="3">MAJIKAN: SWASTA / KERAJAAN / BERNIAGA / KERJA SENDIRI / TIADA BEKERJA </td>
  </tr>
  <tr>
    <td colspan="3" valign="top">ALAMAT: <?php if($company['c_postcode'] != '0') { echo strtoupper($company['c_address1']." ".$company['c_address2']." ".$company['c_address3']." ".$company['c_postcode']." ".$company['c_city']." ".$company['c_state']); } else {  echo strtoupper($company['c_address1']." ".$company['c_address2']." ".$company['c_address3']." ".$company['c_city']." ".$company['c_state']); } ?></td>
  </tr>
  
  <tr>
    <td colspan="4">JENIS CAGARAN (JIKA BERKAITAN)</td>
  </tr>
  <tr>
    <td colspan="3">ANGGARAN NILAI SEMASA (RM)</td>
    <td width="25%"><?php echo $loan['loan_period']; ?> BULAN</td>
  </tr>
</table>
<br />
<table width="90%" align="center">
	<tr>
		<td><strong>2. BUTIRAN PINJAMAN</strong></td>
	</tr>
</table>
<table width="90%" border="1" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="14%" align="center">TARIKH</td>
    <td width="13%" align="center">PINJAMAN <br />POKOK <br />(RM)</td>
    <td width="12%" align="center">JUMLAH <br />FAEDAH <br />(RM)</td>
    <td width="12%" align="center">JUMLAH <br />BESAR <br />(RM)</td>
    <td width="12%" align="center">KADAR <br />FAEDAH <br />SETAHUN</td>
    <td width="12%" align="center">BERCAGAR / <br />TIDAK <br />BERCAGAR</td>
    <td width="12%" align="center">TEMPOH <br />BAYARAN <br />(BULAN)</td>
    <td width="13%" align="center">BAYARAN <br />SEBULAN <br />(RM)</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><?php echo $loan['loan_amount']; ?></td>
    <td align="center"><?php echo $loanint; ?></td>
    <td align="center"><?php echo $loan['loan_total']; ?></td>
    <td align="center"><?php echo $loan2['int_percent']; ?>%</td>
    <td align="center"><?php if($loan['loan_package'] == 'SKIM HP' || $loan['loan_package'] == 'SKIM E') { echo 'B'; } else { echo 'TB'; } ?></td>
    <td align="center"><?php echo $loan['loan_period']; ?></td>
    <td align="center"><?php echo $loan2['monthly']; ?></td>
  </tr>
</table>
<br />
<table width="90%" align="center">
	<tr>
		<td><strong>3. BUTIRAN BAYARAN BALIK</strong></td>
	</tr>
</table>
<table width="90%" border="1" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="14%" align="center">TARIKH</td>
    <td width="15%" align="center">JUMLAH <br />BESAR <br />PINJAMAN <br />(RM)</td>
    <td width="15%" align="center">BAYARAN <br />BALIK <br />PINJAMAN <br />(RM)</td>
    <td width="14%" align="center">BAKI <br />PINJAMAN <br />(RM)</td>
    <td width="14%" align="center">NO <br />RESIT</td>
    <td width="28%">
    CATATAN: <br />
    1. PINJAMAN SELESAI <br />
    2. PINJAMAN SEMASA <br />
    3. DALAM PROSES DAPAT BALIK <br />
    4. DALAM TINDAKAN MAHKAMAH    </td>
  </tr>
  <?php 
  $ctr = 0;
  while($repayment = mysql_fetch_assoc($repayment_q)) { 
  $ctr++;
  if($ctr < $rrow)
  {
  ?>
  <tr>
    <td><div align="center"><?php echo date('d/m/Y', strtotime($repayment['payment_date'])); ?></div></td>
    <td><div align="center"><?php echo $loan['loan_total']; ?></div></td>
    <td><div align="center"><?php echo $repayment['payment']; ?></div></td>
    <td><div align="center"><?php echo $repayment['balance']; ?></div></td>
    <td><div align="center"><?php echo $repayment['receipt_no']; ?></div></td>
    <td>&nbsp;</td>
  </tr>
  <?php }
  } ?>
</table>
</body>