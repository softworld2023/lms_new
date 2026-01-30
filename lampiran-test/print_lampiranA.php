<?php

session_start();
include("../include/dbconnection.php");
//insert path to database connection
$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$loan = mysql_fetch_assoc($loan_q);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
$cust = mysql_fetch_assoc($cust_q);

$add_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$loan['customer_id']."'");
$add = mysql_fetch_assoc($add_q);

$emp_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$loan['customer_id']."'");
$emp = mysql_fetch_assoc($emp_q);

$fin_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$loan['customer_id']."'");
$fin = mysql_fetch_assoc($fin_q);

$pay_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan['id']."'");
$pay = mysql_fetch_assoc($pay_q);
?>
<html>

<style>
@media print{
	.no-print{
		display:none;
	}
	.page {
		page-break-after: always;
	}
	@page { 
		margin: 0;
		size: A4;
	}
}
.no-print table tr td {
	font-family: Arial Black, Gadget, sans-serif;
}
.no-print table tr td {
	font-family: Verdana, Geneva, sans-serif;
}
.page table {
	font-size: medium;
}

.page table tr td p {
	font-size: 14px;
}
.page table {
	font-size: 20px;
	text-align: right;
}
.page table tr td {
	font-family: ARIAL;
	font-weight: bold;
	font-size: 18px;
}
.page table tr td table {
	font-size: 10px;
}
.page table tr td table tr td {
	font-size: 14px;
}
</style>
<body>
<div class="no-print">
<button class="submit-btn" onClick="print();">Print</button>
<hr/>
</div>
<div class="page">

<table width="89%"  border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right">LAMPIRAN 'A'</td>
  </tr>
  <tr>
    <td align="center"><p>AKTA PEMBERI PINJAM WANG 1951<br>
    [ Subsekyen 18 (1) ]<br>
    </p></td>
  </tr>
  <tr>
    <td align="center"><font style="font-style:oblique">LEJAR AKAUN PEMINJAM </font></td>
  </tr>
  <tr>
    <td align="left">1. BUTIRAN PEMINJAM</td>
  </tr>
  <tr>
    <td align="center">
    <table width="97%" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="left">
        <td width="31%">NAMA</td>
        <td width="32%"><?php echo $cust['name']; ?></td>
        <td width="37%">&nbsp;</td>
      </tr>
      <tr align="left">
        <td>JIKA SYARIKAT</td>
        <td>TARAF: BUMI / BUKAN BUMI/ ASING</td>
        <td>NO DAFTAR PERNIAGAAN:</td>
      </tr>
      <tr align="left">
        <td rowspan="4" a>JIKA INDIVIDU</td>
        <td>NO K/P: <?php echo $cust['nric']; ?></td>
        <td>BANGSA: <?php echo strtoupper($cust['race']); ?></td>
      </tr>
      <tr align="left">
        <td>PEKERJAAN: <?php echo $emp['position']; ?></td>
        <td>PENDAPATAN: <?php echo "RM ".$fin['net_salary']; ?></td>
      </tr>
      <tr align="left">
        <td colspan="2">MAJIKAN: SWASTA / KERAJAAN / BERNIAGA / KERJA SENDIRI / TIDAK BERKERJA</td>
        </tr>
      <tr align="left">
        <td colspan="2">
			<table style="border-collapse:collapse">
				<tr>
					<td><p>ALAMAT:</p></td>
					<td><?php echo $add['address1']." ".$add['address2']." ".$add['address3']." ".$add['postcode']." ".$add['city']." ".$add['state']; ?></td>
				</tr>
			</table> 
		</td>
        </tr>
      <tr align="left">
        <td colspan="3">JENIS CAGARAN (JIKA BERKAITAN)</td>
        </tr>
      <tr align="left">
        <td colspan="3">ANGGARAN NILAI SEMASA (RM)</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr align="left">
    <td>2. BUTIRAN PINJAMAN</td>
  </tr>
  <tr align="left">
    <td><table width="97%" align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width="11%">TARIKH</td>
        <td width="10%">PINJAMAN POKOK (RM)</td>
        <td width="9%">JUMLAH FAEDAH (RM)</td>
        <td width="11%">JUMLAH BESAR (RM)</td>
        <td width="14%">KADAR FAEDAH SETAHUN</td>
        <td width="16%">BERCAGAR / TIDAK BERCAGAR</td>
        <td width="15%">TEMPOH BAYARAN (BULAN)</td>
        <td width="14%">BAYARAN SEBULAN (RM)</td>
      </tr>
      <tr align="center">
        <td><?php if($loan['payout_date'] != '0000-00-00') { echo date('d/m/Y', strtotime($loan['payout_date'])); } ?></td>
        <td><?php echo number_format($loan['loan_amount'], '2'); ?></td>
        <td><?php echo number_format($loan['loan_interesttotal'], '2'); ?></td>
        <td><?php echo number_format($loan['loan_total'], '2'); ?></td>
        <td><?php echo $pay['int_percent']; ?></td>
        <td>&nbsp;</td>
        <td><?php echo $loan['loan_period']; ?></td>
        <td><?php echo number_format($pay['monthly'], '2'); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td>&nbsp;</td>
  </tr>
  <tr align="left">
    <td>3. BUTIRAN BAYARAN BALIK</td>
  </tr>
  <tr align="left">
    <td>
    <table width="97%" align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width="11%">TARIKH</td>
        <td width="13%">JUMLAH BESAR PINJAMAN (RM)</td>
        <td width="13%">BAYARAN BALIK PINJAMAN (RM)</td>
        <td width="13%">BAKI PINJAMAN (RM)</td>
        <td width="14%">NO. RESIT</td>
        <td width="36%" align="left"><p>CATATAN:<br>
          1. PINJAMAN SELESAI<br>
          2. PINJAMAN SEMASA<br>
          3. DALAM PROSES DAPAT BALIK<br>
        4. DALAM TINDAKAN MAHKAMAH</p></td>
      </tr>
	  <?php
	  	$paylist_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND payment_date != '0000-00-00' AND payment != 0");
		while($paylist = mysql_fetch_assoc($paylist_q))
		{
		$balance = $paylist['balance'] - $paylist['payment'];
	  ?>
      <tr  align="center">
        <td><?php echo date('d/m/Y', strtotime($paylist['payment_date'])); ?></td>
        <td><?php echo number_format($loan['loan_total'], '2'); ?></td>
        <td><?php echo number_format($paylist['payment'], '2'); ?></td>
        <td><?php echo number_format($balance, '2'); ?></td>
        <td><?php echo $paylist['receipt_no']; ?></td>
        <td>&nbsp;</td>
      </tr>
	  <?php } ?>
    </table></td>
  </tr>
</table>
</div>

</body>
</html>
