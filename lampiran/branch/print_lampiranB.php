<?php
session_start();
include("../include/dbconnection.php");

//insert path to database connection
$id = $_GET['bid'];

?>
<html>
<header>
</header>

<style>
@media print{
	.no-print{
		display:none;
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

.page table tr td p {
	font-size: 9pt;
}
.page table {
	font-family: Arial, Helvetica, sans-serif;
}
</style>
<?php 
session_start();
//insert db connection here

$branch_id = $id; // add branch session here

$year = date('Y'); // change the year.


//pinjaman diluluskan
$plulus_q = mysql_query("SELECT SUM(loan_amount) AS plulus FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."'");
$plulus = mysql_fetch_assoc($plulus_q);

//kutipan tahunan

//kutipan terkumpul

//peminjam melayu
$pmelayu_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race = 'Malay' GROUP BY cp.customer_id");
$pmelayu = mysql_num_rows($pmelayu_q);

//peminjam cina
$pcina_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race = 'Chinese' GROUP BY cp.customer_id");
$pcina = mysql_num_rows($pcina_q);

//peminjam india
$pindia_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race = 'Indian' GROUP BY cp.customer_id");
$pindia = mysql_num_rows($pindia_q);

//peminjam lain lain
$plain_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race != 'Malay' AND cd.race != 'Chinese' AND cd.race != 'Indian' GROUP BY cp.customer_id");
$plain = mysql_num_rows($plain_q);

//nilai pinjaman melayu
$nmelayu_q = mysql_query("SELECT SUM(loan_amount) AS lamt FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race = 'Malay' GROUP BY cp.customer_id");
$nmelayu = mysql_num_rows($nmelayu_q);

///nilai pinjaman cina
$ncina_q = mysql_query("SELECT SUM(loan_amount) AS lamt FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race = 'Chinese' GROUP BY cp.customer_id");
$ncina = mysql_num_rows($ncina_q);

///nilai pinjaman india
$nindia_q = mysql_query("SELECT SUM(loan_amount) AS lamt FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race = 'Indian' GROUP BY cp.customer_id");
$nindia = mysql_num_rows($nindia_q);

///nilai pinjaman lain lain
$nlain_q = mysql_query("SELECT SUM(loan_amount) AS lamt FROM customer_loanpackage cp, customer_details cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.id = cp.customer_id AND cd.race != 'Malay' AND cd.race != 'Chinese' AND cd.race != 'Indian' GROUP BY cp.customer_id");
$nlain = mysql_num_rows($nlain_q);

//pendapatan <=500
$p500_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_financial cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.customer_id = cp.customer_id AND cd.net_salary <= 500 GROUP BY cp.customer_id");
$p500 = mysql_num_rows($p500_q);

//pendapatan 501-1000
$p1000_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_financial cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.customer_id = cp.customer_id AND cd.net_salary > 500 AND cd.net_salary <= 1000 GROUP BY cp.customer_id");
$p1000 = mysql_num_rows($p1000_q);

//pendapatan 1001-5000
$p5000_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_financial cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.customer_id = cp.customer_id AND cd.net_salary > 1000 AND cd.net_salary <= 5000 GROUP BY cp.customer_id");
$p5000 = mysql_num_rows($p5000_q);

//pendapatan 5001-10000
$p10000_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_financial cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.customer_id = cp.customer_id AND cd.net_salary > 5000 AND cd.net_salary <= 10000 GROUP BY cp.customer_id");
$p10000 = mysql_num_rows($p10000_q);

//pendapatan >= 10001
$p10001_q = mysql_query("SELECT * FROM customer_loanpackage cp, customer_financial cd WHERE cp.payout_date LIKE '%".$year."%' AND cp.branch_id = '".$branch_id."' AND cd.customer_id = cp.customer_id AND cd.net_salary > 10000 GROUP BY cp.customer_id");
$p10001 = mysql_num_rows($p10001_q);


//amaunt pinjaman <= 1000
$a1000_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount <= 1000 GROUP BY customer_id");
$a1000 = mysql_num_rows($a1000_q);

//amaunt pinjaman 1001-5000
$a5000_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount > 1000 AND loan_amount <= 5000 GROUP BY customer_id");
$a5000 = mysql_num_rows($a5000_q);

//amaunt pinjaman 5001-10000
$a10000_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount > 5000 AND loan_amount <= 10000 GROUP BY customer_id");
$a10k = mysql_num_rows($a10000_q);

//amaunt pinjaman 10001-50000
$a50000_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount > 10000 AND loan_amount <= 50000 GROUP BY customer_id");
$a50k = mysql_num_rows($a50000_q);

//amaunt pinjaman 10001-50000
$a100000_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount > 50000 AND loan_amount <= 100000 GROUP BY customer_id");
$a100k = mysql_num_rows($a100000_q);

//amaunt pinjaman 100001-1000000
$a1000000_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount > 100000 AND loan_amount <= 1000000 GROUP BY customer_id");
$a1m = mysql_num_rows($a1000000_q);

//amaunt pinjaman > 1ml
$a1ml_q = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date LIKE '%".$year."%' AND branch_id = '".$branch_id."' AND loan_amount > 1000000 GROUP BY customer_id");
$a1ml = mysql_num_rows($a1ml_q);
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
      REKOD TRANSAKSI TAHUNAN MENGIKUT PERINCIAN PINJAMAN  BAGI TAHUN <?php echo $year; ?>
      </p></td>
  </tr>
  <tr>
    <td align="center">
    <table width="80%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="36%"><p>NAMA PEMBERI PINJAM WANG</p></td>
        <td colspan="2"><p>:</p></td>
      </tr>
      <tr>
        <td><p>JENIS ORGANISASI</p></td>
        <td colspan="2"><p>:</p></td>
      </tr>
      <tr>
        <td><p>INDIVIDU BOLEH DIBUHUNGI</p></td>
        <td width="33%"><p>:</p></td>
        <td width="31%"><p>TEL:</p></td>
      </tr>
      <tr>
        <td><p>TEMPOH TRANSAKSI</p></td>
        <td><p>:DARI</p></td>
        <td><p>HINGGA:</p></td>
      </tr>
      <tr>
        <td><p>NO. LESEN PEMBERI PINJAM WANG</p></td>
        <td colspan="2"><p>:</p></td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td align="left">
    <table width="97%" align="center" border="1" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width="8%"><p>Bil</p></td>
        <td width="59%"><p>Maklumat</p></td>
        <td width="33%"><p>Jumlah/Bilangan</p></td>
      </tr>
	  <tr>
	  	<td style="border-bottom:none"><center><p>1</p></center></td>
		<td style="border-bottom:none; padding-left:5px"><p>Butiran Pinjaman Keseluruhan:</p></td>
		<td style="border-bottom:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>a) Jumlah  Pinjaman Yang diluluskan (Tahun Dilaporkan Sahaja)</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo number_format($plulus['plulus'], '2'); ?></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>b) Jumlah  Kutipan Semula Pinjaman</p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Kutipan Semula Pinjaman ( Tahun  Dilaporkan Sahaja)</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>		
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. Kutipan Terkumpul Semula Pinjaman (Selain dari Tahun  Dilaporkan)</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>c) Bil. perjanjian ditandatangan</p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Perjanjian bercagar</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>perjanjian</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. Perjanjian tak bercagar</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>perjanjian</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iii. Jumlah  perjanjian</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="50" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iv. Jenis-jenis cagaran</p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>d) Jumlah Pinjaman Mengikut Kategori Peminjam </p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Individu </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-top:none">&nbsp;</td>
		<td style="border-top:none; padding-left:20px"><p>ii. Syarikat </p></td>
		<td style="border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none"><center><p>2</p></center></td>
		<td style="border-bottom:none; padding-left:5px"><p>Butiran Peminjam Individu:</p></td>
		<td style="border-bottom:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>a) Pecahan  mengikut majikan:-</p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. K/tangan awam</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. K/tangan swasta</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iii. Peniaga/ kerja sendiri</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iv. Tiada Pekerjaan Tetap</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>v. Tiada Maklumat/Tidak Dinyatakan</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>b) Pecahan  mengikut kaum:- (Bilangan Peminjam) </p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Melayu </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $pmelayu; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. Cina </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $pcina; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iii. India </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $pindia; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iv. Lain-lain kaum </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $plain; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>c) Pecahan  mengikut kaum:- (Nilai Pinjaman) </p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Melayu </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo number_format($nmelayu['lamt'], '2'); ?></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. Cina </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo number_format($ncina['lamt'], '2'); ?></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iii. India </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo number_format($nindia['lamt'], '2'); ?></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iv. Lain-lain kaum </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo number_format($nlain['lamt'], '2'); ?></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>d) Pecahan  mengikut pendapatan peminjam:-</p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Bawah RM 500</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $p500; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. RM501 - RM1000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $p1000; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iii. RM1001- RM5000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $p5000; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iv. RM5001- RM10,000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $p10000; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>v. RM10,001- keatas</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $p10001; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>e) Pecahan  mengikut amaun pinjaman:-</p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>i. Bawah RM1000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a1000; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>ii. RM1001 - RM5000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a5000; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iii. RM5001- RM10,000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a10k; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>iv. RM10,001- RM50,000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a50k; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>v. RM50,001- RM100,000</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a100k; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>vi. RM100,001- RM1 juta </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a1m; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-top:none">&nbsp;</td>
		<td style="border-top:none; padding-left:20px"><p>vii. RM1 juta ke atas</p></td>
		<td style="border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p><?php echo $a1ml; ?></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
      <tr>
	  	<td style="border-bottom:none"><center><p>3</p></center></td>
		<td style="border-bottom:none; padding-left:5px"><p>Butiran Peminjam Syarikat (Tahun Dilaporkan) </p></td>
		<td style="border-bottom:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>a) Pecahan Mengikut Jenis Syarikat (Nilai Pinjaman) </p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>Bumiputra</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>Bukan Bumiputra </p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>b) Bilangan Peminjam Syarikat </p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>Bumiputra</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>Syarikat</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>Bukan Bumiputra</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="65" style="border-bottom:thin dotted #000"><center><p></p></center></td>
					<td width="30"><p>Syarikat</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
  </table>
  </td>
  </tr>
  </table>
</div>

</body>
</html>
