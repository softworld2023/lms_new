<?php
session_start();
include("../../include/dbconnection.php");
include("../../include/dbconnection2.php");

$id = $_GET['id'];
$exp = mysql_query("SELECT * FROM customer_details WHERE id = '".$_GET['cus_id']."'",$db1);
$exp_d = mysql_fetch_assoc($exp);

$netsal = mysql_query("SELECT * FROM customer_financial WHERE id = '".$_GET['cus_id']."'",$db1);
$salary = mysql_fetch_assoc($netsal);

$post = mysql_query("SELECT * FROM customer_employment WHERE id = '".$_GET['cus_id']."'",$db1);
$post_d = mysql_fetch_assoc($post);

$add = mysql_query("SELECT * FROM customer_address WHERE id = '".$_GET['cus_id']."'",$db1);
$add_d = mysql_fetch_assoc($add);

$taraf = mysql_query("SELECT * FROM customer_details WHERE id = '".$_GET['cus_id']."'",$db2);
$trf = mysql_fetch_assoc($taraf);
?>
<style>
input[type=text] { text-transform:uppercase; } 
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
<h3>EDIT CUSTOMER RECORD</h3><br>
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
<form method="post" action="action2.php" onSubmit="return checkForm()" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
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
		<td>&nbsp;
		
		</td>
  </tr>
  <tr>
    <td align="center">
    <table width="97%" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="left">
       <td>NAMA</td>
        <td  colspan="2" size="83"><input type="text" size="100"  name="name" id="name" font style="font-weight:bold; border: none" value="<?php echo $exp_d['name']; ?>"/></td>
      
      </tr>
      <tr align="left">
        <td width="31%">JIKA SYARIKAT</td>
        <td  width="32%" >TARAF:<input type="text" name="taraf" id="taraf"  font style="font-weight:bold; border: none" size="43" value="<?php echo $trf['taraf']; ?>"/></td>
		
        <td width="37%">NO DAFTAR PERNIAGAAN:<input type="text" name="no_daftar" id="no_daftar"  font style="font-weight:bold; border: none" size="20" value="<?php echo $exp_d['no_daftar']; ?>"/></td>
      </tr>
      <tr align="left">
        <td rowspan="4" a>JIKA INDIVIDU</td>
        <td>NO K/P: <input type="text" name="nric" id="nric"  font style="font-weight:bold; border: none" size="43" value="<?php echo $exp_d['nric']; ?>"</td>
        <td>BANGSA: <input type="text" name="race" id="race"  font style="font-weight:bold; border: none" size="43" value="<?php echo strtoupper($exp_d['race']); ?>"</td>
      </tr>
      <tr align="left">
        <td>PEKERJAAN: <input type="text" name="position" id="position"  font style="font-weight:bold; border: none" size="32" value="<?php echo $post_d['position']; ?>"</td>
        <td>PENDAPATAN: RM<input type="text" name="salary" id="salary"  font style="font-weight:bold; border: none" value="<?php echo $salary['net_salary']; ?>"></td>
      </tr>
      <tr align="left">
        <td colspan="2">MAJIKAN:<input type="text" name="employer" id="employer"  font style="font-weight:bold; border: none" size="73" value="<?php echo $exp_d['employer']; ?>"></td>
        </tr>
      <tr align="left">
        <td colspan="2">
			<table style="border-collapse:collapse">
				<tr>
					<td>ALAMAT: </td>
					<td><input type="text" name="address" id="address"  font style="font-weight:bold; border: none" size="120" value="<?php echo $add_d['address1'].", ".$add_d['address2'].", ".$add_d['address3'].", ".$add_d['postcode'].", ".$add_d['city']." ".$add_d['state']; ?>"></td>
					
				</tr>
			</table> 
		</td>
        </tr>
      <tr align="left">
        <td colspan="3">JENIS CAGARAN (JIKA BERKAITAN):<input type="text" name="jenis_cagaran" id="jenis_cagaran" font style="font-weight:bold; border: none"  size="20" value="<?php echo $exp_d['jenis_cagaran']; ?>"></td>
        </tr>
      <tr align="left">
        <td colspan="3">ANGGARAN NILAI SEMASA (RM):<input type="text" name="anggaran_nilai" id="anggaran_nilai" font style="font-weight:bold; border: none"  size="20" value="<?php echo $exp_d['anggaran_nilai']; ?>"></td>
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
		<td>&nbsp;
		
		</td>
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
        <td><input type="text" size="15" name="date_borrow" id="date_borrow" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['date_borrow']; ?>"></td></td>
        <td><input type="text" size="13" name="pinjaman_pokok" id="pinjaman_pokok" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['pinjaman_pokok']; ?>"></td>
        <td><input type="text" size="12" name="jumlah_faedah" id="jumlah_faedah" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['jumlah_faedah']; ?>"></td>
        <td><input type="text" size="14" name="jumlah_besar" id="jumlah_besar" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['jumlah_besar']; ?>"></td>
        <td><input type="text" size="19" name="kadar_faedah" id="kadar_faedah" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['kadar_faedah']; ?>"></td>
        <td><input type="text" size="23" name="bercagar" id="bercagar" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['bercagar']; ?>"></td>
        <td><input type="text" size="21" name="tempoh_bayaran" id="tempoh_bayaran" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['tempoh_bayaran']; ?>"></td>
        <td><input type="text" size="19" name="bayaran_sebulan" id="bayaran_sebulan" font style="font-weight:bold; border: none"  value="<?php echo $exp_d['bayaran_sebulan']; ?>"></td>
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
		<td>&nbsp;
		
		</td>
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
      <tr>
        <td align="left"><input type="text" size="15" name="date_pay" id="date_pay" font style="font-weight:bold; border: none"></td>
        <td align="left"><input type="text" size="19" name="jumlah_pinjaman" id="jumlah_pinjaman" font style="font-weight:bold; border: none"></td>
        <td align="left"><input type="text" size="19" name="bayaran_pinjaman" id="bayaran_pinjaman" font style="font-weight:bold; border: none"></td>
        <td align="left"><input type="text" size="19" name="baki_pinjaman" id="baki_pinjaman" font style="font-weight:bold; border: none"></td>
        <td align="left"><input type="text" size="19" name="resit_no" id="resit_no" font style="font-weight:bold; border: none"></td>
        <td align="left"><input type="text" size="58" name="remark" id="remark" font style="font-weight:bold; border: none"></td>
      </tr>
	 
    </table></td>
  </tr>
  <tr>
		<td>&nbsp;
		
		</td>
  </tr>
  <tr>
    	<td colspan="4" align="center"><input type="Submit" name="submit" id="submit" value="Save"  class="all_btn">&nbsp;&nbsp;&nbsp;<input type="button" name="back" value="Back" onClick="window.location.href='../'" class="all_btn"></td>
    </tr>
</table>
</form>
</div>
</body>
</html>