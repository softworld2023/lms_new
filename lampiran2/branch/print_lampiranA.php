<?php

session_start();
include("../include/dbconnection.php");
//insert path to database connection

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
		<td>
		&nbsp;
		</td>
  </tr>
  <tr>
    <td align="center">
	<form method="GET">
    <table width="100%" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="left">
        <td >NAMA <value="<?php echo $id['name']; ?>"</td>
        <td  colspan="2" size="83"></td>
        
      </tr>
      <tr align="left">
        <td width="31%">JIKA SYARIKAT</td>
        <td  width="32%" >TARAF:<input type="text" size="28"></td>
		
        <td width="37%">NO DAFTAR PERNIAGAAN:</td>
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
        <td colspan="2">MAJIKAN:<input type="text" size="73"></td>
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
 <tr>
		<td>
		&nbsp;
		</td>
  </tr>
  <tr align="left">
    <td><table width="100%" align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
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
        <td><input type="text" size="10"></td>
        <td><input type="text" size="9"></td>
        <td><input type="text" size="11"></td>
        <td><input type="text" size="14"></td>
        <td><input type="text" size="16"></td>
        <td><input type="text" size="15"></td>
        <td><input type="text" size="14"></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td>&nbsp;</td>
  </tr>
  <tr align="left">
    <td>3. BUTIRAN BAYARAN BALIK</td>
  </tr>
  <tr>
		<td>
		&nbsp;
		</td>
  </tr>
  
  <tr align="left">
    <td>
    <table width="100%" align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
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
        <td><input type="text" size="11"></td>
        <td><input type="text" size="13"></td>
        <td><input type="text" size="13"></td>
        <td><input type="text" size="13"></td>
        <td><input type="text" size="14"></td>
        <td><input type="text" size="36"></td>
      </tr>
	  <?php } ?>
    </table></td>
  </tr>
  <tr>
		<td>
		&nbsp;
		</td>
  </tr>
  <tr>
    	<td colspan="4" align="center"><input type="button" name="save" id="save" value="Save" class="all_btn">&nbsp;&nbsp;&nbsp;<input type="button" name="back" value="Back" onClick="window.location='../home/'" class="all_btn"></td>
    </tr>
</table>
</form>
</div>
</body>
</html>
<script>
$(document).ready(function() {   
   $("#name").autocomplete("auto_Name.php", {
     width: 250,
  matchContains: true,
  selectFirst: false
 });
 });
</script>