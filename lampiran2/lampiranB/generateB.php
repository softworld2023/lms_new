<?php
session_start();
include("../include/dbconnection2.php");
//include("../include/dbconnection.php");
//error_reporting(0);
//ini_set('display_errors',0);
$id = $_GET['branch_id'];

//$expq = mysql_query("SELECT * FROM customer_details WHERE id = '".$_GET['id']."'",$db1);
//$exp_q = mysql_fetch_assoc($expq);

$exp = mysql_query("SELECT * FROM customer_no WHERE branch_id = '".$id."'");
$exp_d = mysql_fetch_assoc($exp);


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
.all_btn {
	-moz-box-shadow: 0px 1px 2px 0px #ffffff;
	-webkit-box-shadow: 0px 1px 2px 0px #ffffff;
	box-shadow: 0px 1px 2px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #E8E8E8), color-stop(1, #9A9A9A) );
	background:-moz-linear-gradient( center top, #E8E8E8 5%, #9A9A9A 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#E8E8E8', endColorstr='#9A9A9A');
	background-color:#f9f9f9;
	-webkit-border-top-left-radius:5px;
	-moz-border-radius-topleft:5px;
	border-top-left-radius:5px;
	-webkit-border-top-right-radius:5px;
	-moz-border-radius-topright:5px;
	border-top-right-radius:5px;
	-webkit-border-bottom-right-radius:5px;
	-moz-border-radius-bottomright:5px;
	border-bottom-right-radius:5px;
	-webkit-border-bottom-left-radius:5px;
	-moz-border-radius-bottomleft:5px;
	border-bottom-left-radius:5px;
	text-indent:0;
	border:1px solid #9A9A9A;
	display:inline-block;
	color:#000;
	font-family:Arial;
	font-size:13px;
	font-style:normal;
	padding:3px 13px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #fff;
	cursor:pointer;
}
.all_btn:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #9A9A9A), color-stop(1, #E8E8E8) );
	background:-moz-linear-gradient( center top, #9A9A9A 5%, #E8E8E8 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#9A9A9A', endColorstr='#E8E8E8');
	background-color:#e9e9e9;
}.all_btn:active {
	position:relative;
	top:1px;
}
/*-------------------------------------------*/

/*------------ notification message box ----------*/
.info, .success, .warning, .error, .validation {
    border: 1px solid;
    margin-right: 10px;
    background-repeat: no-repeat;
    background-position: 10px center;
	padding:7px 10px 7px 40px;
	background-size:20px 20px;
}
.info {
    color: #00529B;
    background-color: #BDE5F8;
    background-image: url('../../img/info.png');
}
.success {
    color: #4F8A10;
    background-color: #DFF2BF;
    background-image:url('../../img/success.png');
}
.warning {
    color: #9F6000;
    background-color: #FEEFB3;
    background-image: url('../../img/warning.png');
}
.error {
    color: #D8000C;
    background-color: #FFBABA;
    background-image: url('../../img/error.png');
}
.error2 {
	display:inline-table;
    color: #D8000C;
    background-color: #FFBABA;
}
#error2{
	font-size:11px;
	margin-bottom:10px;
	color:#ff0000;
}

/*----------- notification end --------------*/
</style>
</style>
<div id="message">
<?php
if($_SESSION['msg'] != '')
{
	echo $_SESSION['msg'];
    $_SESSION['msg'] = '';
}
?>
</div>
<body>

<div class="page">
<form method="post" action="action.php">
<div class="no-print">

<!--<button class="submit-btn" onClick="print();">Print</button>-->
<tr>
    	<td colspan="4" align="center"><input type="Submit" name="save" id="save" value="Save"  class="all_btn">&nbsp;&nbsp;&nbsp;<input type="button" name="back" value="Back" class="all_btn" onClick="window.location.href='../lampiranB/index.php?branch_id=<?php echo $_GET['branch_id']; ?>'" ></td>
    </tr>
<hr/>
</div>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $id; ?>">
<table width="89%"  border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" font style="font-weight:bold" >LAMPIRAN 'B'</td>
  </tr>
  <tr>
    <td align="center" font style="font-weight:bold"><p align="center">PERATURAN-PERATURAN PEMBERIAN PINJAM WANG ( KAWALAN DAN  PERLESENAN ) 2003<br>
      ( Subperaturan 16(1) )<br>
      REKOD TRANSAKSI TAHUNAN MENGIKUT PERINCIAN PINJAMAN  BAGI TAHUN <input type="text" style="font-weight:bold; font-size:12px; border: none;" size="7" id="year<?php echo $c; ?>" name="year<?php echo $c; ?>" value="<?php echo date("Y"); ?>">
      </p>  </br></br></td>
  </tr>

  <tr>
    <td align="center">
     <table width="80%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="36%"> <p>NAMA PEMBERI PINJAM WANG</p></td>
        <td colspan="2"><p> : &nbsp;<input type="text" align="center" name="name" id="name" font style="font-weight:bold; border: none" size= "50" value="<?php echo $exp_d['name']; ?>"/></p></td>
      </tr>
	  
      <tr>
        <td><p>JENIS ORGANISASI</p></td>
        <td colspan="2"><p>:&nbsp;<input type="text" align="center" name="organisasi" id="organisasi" value="<?php echo $exp_d['organisasi']; ?>" /></p></td>
      </tr>
	
      <tr>
        <td><p>INDIVIDU BOLEH DIBUHUNGI</p></td>
        <td width="33%"><p>:&nbsp;<input type="text" align="center" name="hubungan" id="hubungan" value="<?php echo $exp_d['hubungan']; ?>"  /></p></td>
        <td width="31%"><p>TEL&nbsp;:&nbsp;<input type="text" align="center" name="tel" id="tel" value="<?php echo $exp_d['tel']; ?>" /></p></td>
      </tr>
      <tr>
        <td><p>TEMPOH TRANSAKSI</p></td>
        <td><p>&nbsp;:&nbsp;DARI &nbsp;<input type="text" align="center" name="trans_from" id="trans_from" value="<?php echo $exp_d['trans_from']; ?>"/></p></td>
        <td><p>HINGGA&nbsp;:&nbsp;<input type="text" align="center" name="trans_until" id="trans_until" value="<?php echo $exp_d['trans_until']; ?>"/></p></td>
      </tr>
      <tr>
        <td><p>NO. LESEN PEMBERI PINJAMAN WANG</p></td>
        <td colspan="2"><p>:&nbsp;<input type="text" align="center" name="no_lesen" id="no_lesen" value="<?php echo $exp_d['tel']; ?>"/></p></td>
      </tr>
    </table>
	</td>
  </tr>
 
    <td align="left">
    <table width="97%" align="center" border="1" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width="8%" font style="font-weight:bold"><p>Bil</p></td>
        <td width="59%" font style="font-weight:bold"><p>Maklumat</p></td>
        <td width="33%" font style="font-weight:bold"><p>Jumlah/Bilangan</p></td>
      </tr>
	  <tr>
	  	<td style="border-bottom:none"><center><p>1</p></center></td>
		<td style="border-bottom:none; padding-left:5px; font-weight:bold"><p>Butiran Pinjaman Keseluruhan:</p></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="plulus" id="plulus" value="<?php echo number_format($plulus['plulus'], '2'); ?>"/></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="k_tahun" id="k_tahun" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="k_tahun" id="k_tahun" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="bercagar" id="bercagar" /></p></center></td>
					<td width="30" ><p>  perjanjian</p></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="t_bercagar" id="t_bercagar" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="jumlah_pinjaman" id="jumlah_pinjaman"/></p></center></td>
					<td width="30"><p>perjanjian</p></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="k_individu" id="k_individu" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="k_syarikat" id="k_syarikat" /></p></center></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none"><center><p>2</p></center></td>
		<td style="border-bottom:none; padding-left:5px; font-weight:bold"><p>Butiran Peminjam Individu:</p></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="k_awam" id="k_awam"/></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="k_swasta" id="k_swasta"/></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="peniaga" id="peniaga" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="t_kerja" id="t_kerja"/></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="t_info" id="t_info"/></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="pmelayu" id="pmelayu" value="<?php echo $pmelayu; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="pcina" id="pcina" value="<?php echo $pcina; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="pindia" id="pindia" value="<?php echo $pindia; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="plain" id="plain"  value="<?php echo $plain; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="nmelayu" id="nmelayu" value="<?php echo number_format($nmelayu['lamt'], '2'); ?>"></p></center></td>
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
					<td width="65"><center><p><input type="text" align="center" name="ncina" id="ncina" value="<?php echo number_format($ncina['lamt'], '2'); ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="nindia" id="nindia" value="<?php echo number_format($nindia['lamt'], '2'); ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="nlain" id="nlain" value="<?php echo number_format($nlain['lamt'], '2'); ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="p500" id="p500" value="<?php echo $p500; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="p1000" id="p1000" value="<?php echo $p1000; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="p5000" id="p5000"  value="<?php echo $p5000; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="p10000" id="p10000" value="<?php echo $p10000; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="p10001" id="p10001" value="<?php echo $p10001; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="a1000" id="a1000" value="<?php echo $a1000; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="a5000" id="a5000" value="<?php echo $a5000; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="a10000" id="a10000" value="<?php echo $a10k; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="a50000" id="a50000" value="<?php echo $a50k; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="a100000" id="a100000" value="<?php echo $a100k; ?>"></p></center></td>
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
					<td width="65"><center><p><input type="text" align="center" name="a1ml" id="a1ml" value="<?php echo $a1m; ?>"></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="a1ml" id="a1ml" value="<?php echo $a1ml; ?>"></p></center></td>
					<td width="30"><p>orang</p></td>
				</tr>
			</table>
		</center>
		</td>
	  </tr>
      <tr>
	  	<td style="border-bottom:none"><center><p>3</p></center></td>
		<td style="border-bottom:none; padding-left:5px; font-weight:bold"><p>Butiran Peminjam Syarikat (Tahun Dilaporkan) </p></td>
		<td style="border-bottom:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:5px"><p>a) Pecahan Mengikut Jenis Syarikat (Nilai Pinjaman) </p></td>
		<td style="border-bottom:none; border-top:none">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td style="border-bottom:none; border-top:none">&nbsp;</td>
		<td style="border-bottom:none; border-top:none; padding-left:20px"><p>Bumiputera</p></td>
		<td style="border-bottom:none; border-top:none">
		<center>
			<table style="border-collapse:collapse">
				<tr>
					<td width="30"><p>RM</p></td>
					<td width="65" ><center><p><input type="text" align="center" name="p_bumiputera" id="p_bumiputera" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="p_bukan_bumi" id="p_bukan_bumi"  /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="s_bumiputera" id="s_bumiputera" /></p></center></td>
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
					<td width="65" ><center><p><input type="text" align="center" name="s_bukan_bumi" id="s_bukan_bumi" /></p></center></td>
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
  </form>
</div>

</body>
</html>
