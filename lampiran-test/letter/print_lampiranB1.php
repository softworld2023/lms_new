<?php
session_start();
include("../include/dbconnection.php");

//insert path to database connection
$id = $_GET['id'];

?>
<html>
<header>
</header>

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

.page table {
	font-size: medium;
}

.page table tr td p {
	font-size: 14px;
}
.page table {
	font-size: 20px;
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
	text-align: left;
}
</style>
<?php 
session_start();
//insert db connection here

$branch_id = 1; // add branch session here

$year = date('Y'); // change the year.

$sql_q = mysql_query("SELECT * FROM  customer_details cd, customer_loanpackage lp WHERE lp.payout_date LIKE '%".$year."%' AND lp.branch_id = '".$branch_id."' AND cd.id = lp.customer_id");

?>
<body>
<div class="no-print">
<button class="submit-btn" onClick="print();">Print</button>
<hr/>
</div>
<div class="page">

<table width="89%"  border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right">LAMPIRAN 'B1'</td>
  </tr>
  <tr>
    <td align="center"><p align="center">PERATURAN-PERATURAN PEMBEN PINJAM WANG ( KAWALAN DAN  PERLESENAN ) 2003<br>
      ( Subperaturan 16(1) )<br>
      REKOD TRANSAKSI TAHUNAN MENGIKUT PERINCIAN PINJAMAN BAGI TAHUN 2015
      <br>
      <br>
      <br>
    </p></td>
  </tr>
  <tr>
    <td align="left">
    <p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;NAMA SYARIKAT PEMBERI PINJAM WANG: <br>
    </p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <table width="97%" align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td width="3%" rowspan="2" align="center"><p>Bil<br>
        </p>
          (1)</td>
        <td width="14%" rowspan="2" align="center"><p>Nama Pinjaman <br>
        </p>
          <p>(2)</p></td>
        <td width="15%" rowspan="2"><p>Jumlah Pinjaman (RM) (Jumlah Pokok + Faedah) </p>
          <p>(3)</p></td>
        <td width="4%" rowspan="2"><p>Baki Pinjaman (RM) </p>
          <p>(4)</p></td>
        <td width="8%" rowspan="2" ><p>Tempoh Pinjaman (Bulan) </p>
          <p>(5)</p></td>
        <td width="8%" rowspan="2"><p>Tarikh Pinjaman<br>
        </p>
          <p>(6)</p></td>
        <td width="8%" rowspan="2"><p>No. Telefon Pinjaman</p>
          <p>(7)</p></td>
        <td width="8%" rowspan="2"><p>Kadar Faedah Setahun (%)<br>
          (8)
        </p></td>
        <td width="8%" rowspan="2"><p>Bercagar/Tanpa Cagaran </p>
          <p><br>
            (9)</p></td>
        <td width="8%" height="38" colspan="2">Syarikat </td>
        <td width="8%" colspan="3">Individu</td>
        <td width="8%">Nota #</td>
      </tr>
      <tr align="center">
        <td><p>Bumi</p>
          (10)</td>
        <td>Bukan Bumi<br>
          (11)</td>
        <td width="4%">Kaum<br>
          <br>
          (12)</td>
        <td width="4%">Majikan*<br>
          <br>
          (13)</td>
        <td width="8%">Gaji (RM)<br>
          (14)</td>
        <td width="8%"><p>&nbsp;</p>
          <p>(15)</p></td>
      </tr>
	  <?php
	  $c = 0;
	  
	  while($sql = mysql_fetch_assoc($sql_q))
	  {
	  	$c++;
		$cust_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$sql['customer_id']."'");
		$cust = mysql_fetch_assoc($cust_q);
		
		$payment_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$sql['id']."'");
		$payment = mysql_fetch_assoc($payment_q);
		
		$balance_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$sql['id']."' ORDER BY id DESC");
		$balance = mysql_fetch_assoc($balance_q);
	  ?>
      <tr align="center">
        <td><?php echo $c; ?></td>
        <td><?php echo strtoupper($sql['name']); ?></td>
        <td><?php echo number_format($sql['loan_total'], '2'); ?></td>
        <td><?php echo number_format($balance['balance'], '2'); ?></td>
        <td><?php echo $sql['loan_period']; ?></td>
        <td><?php echo date('d/m/Y', strtotime($sql['payout_date'])); ?></td>
        <td><?php echo $cust['mobile_contact']; ?></td>
        <td><?php echo $payment['int_percent']; ?></td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
	  <?php } ?>
    </table></td>
  </tr>
  </table>
</div>

</body>
</html>
