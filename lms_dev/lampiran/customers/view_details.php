<?php
session_start();
include("../include/dbconnection.php");
include("../include/dbconnection2.php");

$id = $_GET['id'];
$exp = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'",$db2);
$exp_d = mysql_fetch_assoc($exp);
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
<html>
<h3>VIEW CUSTOMER DETAILS</h3><br>
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
<tr>
    	<td colspan="4" align="center"><input type="Submit" name="submit" id="submit" value="Save"  class="all_btn">&nbsp;&nbsp;&nbsp;<input type="button" name="back" value="Back" class="all_btn" onClick="window.location.href='../'" ></td>
    </tr>
<hr/>
</div>
<div class="page">
<form method="post" action="action2.php" onSubmit="return checkForm()" enctype="multipart/form-data">
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
		<div id="name" style="display:none;"></div> 
        <td>NAMA</td>
        <td  colspan="2" size="83"><input type="text" size="100" readonly="readonly" name="name" id="name" font style="font-weight:bold; border: none" value="<?php echo $exp_d['name']; ?>"/></td>
      
      </tr>
      <tr align="left">
        <td width="31%">JIKA SYARIKAT</td>
        <td  width="32%" >TARAF:<input type="text" name="taraf" id="taraf" readonly="readonly" font style="font-weight:bold; border: none" size="43" value="<?php echo $exp_d['taraf']; ?>"/></td>
		
        <td width="37%">NO DAFTAR PERNIAGAAN:<input type="text" name="no_daftar" id="no_daftar" readonly="readonly" font style="font-weight:bold; border: none" size="20" value="<?php echo $exp_d['no_daftar']; ?>"/></td>
      </tr>
      <tr align="left">
        <td rowspan="4" a>JIKA INDIVIDU</td>
        <td>NO K/P: <input type="text" name="nric" id="nric" readonly="readonly" font style="font-weight:bold; border: none" size="43" value="<?php echo $exp_d['nric']; ?>"</td>
        <td>BANGSA: <input type="text" name="race" id="race" readonly="readonly" font style="font-weight:bold; border: none" size="43" value="<?php echo strtoupper($exp_d['race']); ?>"</td>
      </tr>
      <tr align="left">
        <td>PEKERJAAN: <input type="text" name="position" id="position" readonly="readonly" font style="font-weight:bold; border: none" size="32" value="<?php echo $exp_d['position']; ?>"</td>
        <td>PENDAPATAN: RM<input type="text" name="salary" id="salary" readonly="readonly" font style="font-weight:bold; border: none" value="<?php echo $exp_d['salary']; ?>"></td>
      </tr>
      <tr align="left">
        <td colspan="2">MAJIKAN:<input type="text" name="employer" id="employer" readonly="readonly" font style="font-weight:bold; border: none" size="73" value="<?php echo $exp_d['employer']; ?>"></td>
        </tr>
      <tr align="left">
        <td colspan="2">
			<table style="border-collapse:collapse">
				<tr>
					<td>ALAMAT: </td>
					<td><input type="text" name="address" id="address" readonly="readonly" font style="font-weight:bold; border: none" size="120" value="<?php echo $exp_d['address']; ?>"></td>
					
				</tr>
			</table> 
		</td>
        </tr>
      <tr align="left">
        <td colspan="3">JENIS CAGARAN (JIKA BERKAITAN):<input type="text" name="jenis_cagaran" id="jenis_cagaran" font style="font-weight:bold; border: none" readonly="readonly" size="20" value="<?php echo $exp_d['jenis_cagaran']; ?>"></td>
        </tr>
      <tr align="left">
        <td colspan="3">ANGGARAN NILAI SEMASA (RM):<input type="text" name="anggaran_nilai" id="anggaran_nilai" font style="font-weight:bold; border: none" readonly="readonly" size="20" value="<?php echo $exp_d['anggaran_nilai']; ?>"></td>
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
        <td><input type="text" size="11" name="date_borrow" id="date_borrow" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['date_borrow']; ?>"></td></td>
        <td><input type="text" size="10" name="pinjaman_pokok" id="pinjaman_pokok" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['pinjaman_pokok']; ?>"></td>
        <td><input type="text" size="9" name="jumlah_faedah" id="jumlah_faedah" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['jumlah_faedah']; ?>"></td>
        <td><input type="text" size="11" name="jumlah_besar" id="jumlah_besar" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['jumlah_besar']; ?>"></td>
        <td><input type="text" size="14" name="kadar_faedah" id="kadar_faedah" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['kadar_faedah']; ?>"></td>
        <td><input type="text" size="16" name="bercagar" id="bercagar" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['bercagar']; ?>"></td>
        <td><input type="text" size="15" name="tempoh_bayaran" id="tempoh_bayaran" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['tempoh_bayaran']; ?>"></td>
        <td><input type="text" size="14" name="bayaran_sebulan" id="bayaran_sebulan" font style="font-weight:bold; border: none" readonly="readonly" value="<?php echo $exp_d['bayaran_sebulan']; ?>"></td>
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
  
  <tr align="center">
    <td>
    <table width="97%" align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width="11%" >TARIKH</td>
        <td width="13%">JUMLAH BESAR PINJAMAN (RM)</td>
        <td width="13%">BAYARAN BALIK PINJAMAN (RM)</td>
        <td width="13%">BAKI PINJAMAN (RM)</td>
        <td width="14%">NO. RESIT</td>
        <td width="20%" align="left"><p>CATATAN:<br>
          1. PINJAMAN SELESAI<br>
          2. PINJAMAN SEMASA<br>
          3. DALAM PROSES DAPAT BALIK<br>
		  4. DALAM TINDAKAN MAHKAMAH</p></td>
      </tr
      <tr  align="center">
        <td align="center"><input type="text" size="11" name="date_pay" id="date_pay" readonly="readonly" value="<?php echo $exp_d['date_pay']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="13" name="jumlah_pinjaman" id="jumlah_pinjaman" readonly="readonly" value="<?php echo $exp_d['jumlah_pinjaman']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="13" name="bayaran_pinjaman" id="bayaran_pinjaman" readonly="readonly" value="<?php echo $exp_d['bayaran_pinjaman']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="13" name="baki_pinjaman" id="baki_pinjaman" readonly="readonly" value="<?php echo $exp_d['baki_pinjaman']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="14" name="resit_no" id="resit_no" readonly="readonly" value="<?php echo $exp_d['resit_no']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="36" name="remark" id="remark" readonly="readonly" value="<?php echo $exp_d['remark']; ?>" font style="font-weight:bold; border: none"></td>
      </tr>
	 
    </table></td>
  </tr>
  <tr>
		<td>&nbsp;
		
		</td>
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
<script>
function checkForm()
{
	if((document.getElementById('nric').value != '' ))
	{
		$('#message').empty();
		return true;	
	}else
	{
		var msg = "<div class='error'>Please fill in all the mandatory fields!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}

function readURL(input, location) {
	if (input.files && input.files[0]) {
    	var reader = new FileReader();

        reader.onload = function (e) {
        	$('#' + location)
            	.attr('src', e.target.result)
                .width(200)
                .height(auto);
            };

        reader.readAsDataURL(input.files[0]);
    }
}

</script>
<script>
$('#date_borrow').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
keydown(
	function(e)
    {
    	var key = e.keyCode || e.which;
        if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
        {
        	$(this).off('keydown').AnyTime_picker().focus();
            e.preventDefault();
        }
    } );
</script>