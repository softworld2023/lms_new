<?php
session_start();
//include("../include/dbconnection.php");
include("../include/dbconnection2.php");

$id = $_GET['id'];
$ic = $_GET['ic'];
$branch_id = $_GET['branch_id'];
$exp = mysql_query("SELECT * FROM customer_details WHERE nric = '".$ic."'",$db2);
$exp_d = mysql_fetch_assoc($exp);

/* $netsal = mysql_query("SELECT * FROM customer_financial WHERE id = '".$id."'",$db1);
$salary = mysql_fetch_assoc($netsal);

$post = mysql_query("SELECT * FROM customer_employment WHERE id = '".$id."'",$db1);
$post_d = mysql_fetch_assoc($post);

$add = mysql_query("SELECT * FROM customer_address WHERE id = '".$id."'",$db1);
$add_d = mysql_fetch_assoc($add);

$expd = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'",$db2);
$expq = mysql_fetch_assoc($expd);
 */
/* $no_daftar = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'",$db2);
$daftar = mysql_fetch_assoc($no_daftar); */

/* $post_q = mysql_query("SELECT * FROM customer_employment WHERE id = '".$id."'",$db1);
$position = mysql_fetch_assoc($post_q); */

/* $cagar_d = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'",$db2);
$cagaran = mysql_fetch_assoc($cagar_d);

$exp_du = mysql_query("SELECT * FROM customer_loan WHERE nric = '".$ic."'",$db2);
$exp_u = mysql_fetch_assoc($exp_du);
*/ 
$exp_q = mysql_query("SELECT * FROM customer_loan WHERE month = '1' AND nric = '".$ic."'",$db2);

$exp_s = mysql_query("SELECT * FROM loan_payment_details WHERE nric = '".$ic."'",$db2);
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

<h3>EDIT CUSTOMER DETAILS</h3><br>
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
<body>
<form method="post" action="action2.php" onSubmit="return checkForm()" enctype="multipart/form-data">
<div class="no-print">
<!--<button class="all_btn" onClick="print();">Print</button>-->
<tr>
    	<td colspan="4" align="center"><input type="Submit" name="submit" id="submit" value="Save"  class="all_btn">&nbsp;&nbsp;&nbsp;<input type="button" name="back" value="Back" class="all_btn" onClick="window.location.href='../lampiranA/index.php?branch_id=<?php echo $_GET['branch_id']?>'" ></td>
    </tr>
<hr/>
</div>
<div class="page">


<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">
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
        <td  colspan="2" size="83"><input type="text" size="100"  name="name" id="name" font style="font-weight:bold; border: none" value="<?php echo $exp_d['name']; ?>"/></td>
      
      </tr>
      <tr align="left">
        <td width="31%">JIKA SYARIKAT</td>
          <td  width="32%" >TARAF:&nbsp;
			<select id="taraf" name="taraf"  style="width: 142px;">
										<option value="">- SELECT ONE -</option>
										<option value="BUMI" <?php if($exp_d['taraf']=="BUMI") echo 'selected="selected"'; ?> >BUMI</option>
										<option value="BUKAN BUMI" <?php if($exp_d['taraf']=="BUKAN BUMI") echo 'selected="selected"'; ?> >BUKAN BUMI</option>
										<option value="ASING" <?php if($exp_d['taraf']=="ASING") echo 'selected="selected"'; ?> >ASING</option>
										
										</select></td>
        <td width="37%">NO DAFTAR PERNIAGAAN:<input type="text" name="no_daftar" id="no_daftar"  font style="font-weight:bold; border: none" size="20" value="<?php echo $exp_d['no_daftar']; ?>"/></td>
      </tr>
      <tr align="left">
        <td rowspan="4" a>JIKA INDIVIDU</td>
        <td>NO K/P: <input type="text" name="nric" id="nric"  font style="font-weight:bold; border: none" size="43" value="<?php echo $exp_d['nric']; ?>"</td>
        <td>BANGSA: <input type="text" name="race" id="race"  font style="font-weight:bold; border: none" size="43" value="<?php echo strtoupper($exp_d['race']); ?>"</td>
      </tr>
      <tr align="left">
        <td>PEKERJAAN: <input type="text" name="position" id="position"  font style="font-weight:bold; border: none" size="32" value="<?php echo $exp_d['position']; ?>"</td>
        <td>PENDAPATAN: RM<input type="text" name="salary" id="salary"  font style="font-weight:bold; border: none" value="<?php echo $exp_d['salary']; ?>"></td>
      </tr>
      <tr align="left">
          <td colspan="2">MAJIKAN:
		<select id="employer" name="employer"  style="width: 142px;">
										<option value="">- SELECT ONE -</option>
										<option value="SWASTA" <?php if($exp_d['employer']=="SWASTA") echo 'selected="selected"'; ?> >SWASTA</option>
										<option value="KERAJAAN" <?php if($exp_d['employer']=="KERAJAAN") echo 'selected="selected"'; ?> >KERAJAAN</option>
										<option value="BERNIAGA" <?php if($exp_d['employer']=="BERNIAGA") echo 'selected="selected"'; ?> >BERNIAGA</option>
										<option value="KERJA SENDIRI" <?php if($exp_d['employer']=="KERJA SENDIRI") echo 'selected="selected"'; ?> >KERJA SENDIRI</option>
										<option value="TIDAK BEKERJA" <?php if($exp_d['employer']=="TIDAK BEKERJA") echo 'selected="selected"'; ?> >TIDAK BEKERJA</option>
										</select>
		</td>
        </tr>
      <tr align="left">
        <td colspan="2">
			<table style="border-collapse:collapse">
				<tr>
					<td>ALAMAT: </td>
					<td><input type="text" name="address" id="address"  font style="font-weight:bold; border: none" size="120" value="<?php echo $exp_d['address']; ?>" ></td>
					
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
	  <?php 
	  $ctr=0;
	  while($exp_t = mysql_fetch_assoc($exp_q)){
	   $ctr++;	
	   ?>
      <tr align="center">
        <td><input type="hidden" name="count" id="count" size="4" value="<?php echo $ctr; ?>"><input type="date" size="11" name="date_borrow" id="date_borrow<" font style="font-weight:bold; border: none"  value="<?php echo $exp_t['date_borrow']; ?>"></td></td>
        <td><input type="text" size="10" name="pinjaman_pokok" id="pinjaman_pokok" font style="font-weight:bold; border: none" onkeyup="calculateInt()" value="<?php echo $exp_t['loan_amount']; ?>"></td>
        <td><input type="text" size="9" name="jumlah_faedah" id="jumlah_faedah" font style="font-weight:bold; border: none"  value="<?php echo $exp_t['int_total']; ?>"></td>
        <td><input type="text" size="11" name="jumlah_besar" id="jumlah_besar" font style="font-weight:bold; border: none"  value="<?php echo $exp_t['loan_total']; ?>"></td>
        <td><input type="text" size="14" name="kadar_faedah" id="kadar_faedah" font style="font-weight:bold; border: none" onkeyup="calculateInt()"  value="18"></td>
        <td><select id="bercagar" name="bercagar"  style="width: 152px;">
										<option value="">- SELECT ONE -</option>
										<option value="BERCAGAR" <?php if($exp_t['bercagar']=="BERCAGAR") echo 'selected="selected"'; ?> >BERCAGAR</option>
										<option value="TIDAK BERCAGAR" <?php if($exp_t['bercagar']=="TIDAK BERCAGAR") echo 'selected="selected"'; ?> >TIDAK BERCAGAR</option>
										
										</select></td>
        <td><input type="text" size="15" name="tempoh_bayaran" id="tempoh_bayaran" font style="font-weight:bold; border: none" onkeyup="calculateInt()" value="<?php echo $exp_t['loan_period']; ?>"></td>
        <td><input type="text" size="14" name="bayaran_sebulan" id="bayaran_sebulan" font style="font-weight:bold; border: none"  value="<?php echo $exp_t['monthly_payment']; ?>"></td>
		 
      </tr>
	 <?php } ?>
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
		<input type="hidden" name="ctr" id="ctr" size="4" value="<?php echo $ctr; ?>">
		</td>
  </tr>
  
  <tr align="center">
    <td>
    <table width="97%" id="my-table"   align="center" bordercolor="#000000" border="1" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width="11%" >TARIKH</td>
        <td width="13%">JUMLAH BESAR PINJAMAN (RM)</td>
        <td width="13%">BAYARAN BALIK PINJAMAN (RM)</td>
        <td width="13%">BAKI PINJAMAN (RM)</td>
        <td width="14%">NO. RESIT</td>
        <td width="20%" align="left"><p>CATATAN:<br>
          &nbsp;&nbsp;1. PINJAMAN SELESAI<br>
          &nbsp;&nbsp;2. PINJAMAN SEMASA<br>
          &nbsp;&nbsp;3. DALAM PROSES DAPAT BALIK<br>
		  &nbsp;&nbsp;4. DALAM TINDAKAN MAHKAMAH</p></td>
		  <td width="5%">ACTION</td>
      </tr>
	  <?php 
	  $count = 0;
	  while($exp_v = mysql_fetch_assoc($exp_s)){
		$count ++;
	  ?>
      <tr  align="center">
	  
        <td align="center">
		<input type="hidden" name="count" id="count" size="4" value="<?php echo $count; ?>">
		<input type="hidden" name="idd<?php echo $count; ?>" id="idd<?php echo $count; ?>" size="4" value="<?php echo $exp_v['id'];  ?>">
		<input type="date" size="11" name="date_pay<?php echo $count; ?>" id="date_pay<?php echo $count; ?>"  value="<?php echo $exp_v['payment_date']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="13" name="jumlah_pinjaman<?php echo $count; ?>" id="jumlah_pinjaman<?php echo $count; ?>"  value="<?php echo $exp_v['loan_total']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="13" name="bayaran_pinjaman<?php echo $count; ?>" id="bayaran_pinjaman<?php echo $count; ?>"  value="<?php echo $exp_v['monthly_payment']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="13" name="baki_pinjaman<?php echo $count; ?>" id="baki_pinjaman<?php echo $count; ?>"  value="<?php echo $exp_v['balance']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="center"><input type="text" size="14" name="resit_no<?php echo $count; ?>" id="resit_no<?php echo $count; ?>"  value="<?php echo $exp_v['receipt_no']; ?>" font style="font-weight:bold; border: none"></td>
        <td align="left"><select id="remark<?php echo $count; ?>" name="remark<?php echo $count; ?>"  style="width: 242px;">
										<option value="">- SELECT ONE -</option>
										<option value="1. PINJAMAN SELESAI" <?php if($exp_v['remark']=="1. PINJAMAN SELESAI") echo 'selected="selected"'; ?> >1. PINJAMAN SELESAI</option>
										<option value="2. PINJAMAN SEMASA" <?php if($exp_v['remark']=="2. PINJAMAN SEMASA") echo 'selected="selected"'; ?> >2. PINJAMAN SEMASA</option>
										<option value="3. DALAM PROSES DAPAT BALIK" <?php if($exp_v['remark']=="3. DALAM PROSES DAPAT BALIK") echo 'selected="selected"'; ?> >3. DALAM PROSES DAPAT BALIK</option>
										<option value="4. DALAM TINDAKAN MAHKAMAH" <?php if($exp_v['remark']=="4. DALAM TINDAKAN MAHKAMAH") echo 'selected="selected"'; ?> >4. DALAM TINDAKAN MAHKAMAH</option>
										
										
										</select></td>
		<td><input type="button" id="btnAdd" class="button-add" onClick="insertRow()" value="Add Row(s)"></input></td>
      </tr>
	  <?php } ?>
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
var index = parseInt(document.getElementById('count').value) + 1;
function insertRow(){
            var table=document.getElementById("my-table");
            var row=table.insertRow(table.rows.length);
            var cell1=row.insertCell(0);
            var t0=document.createElement("input");
				t0.type = "hidden";
				t0.size = "4";
				t0.name = "count";
                t0.id = "count";
				t0.value = index;
                cell1.appendChild(t0);
				var t1=document.createElement("input");
				t1.type = "date";
				t1.size = "15";
				t1.name = "date_pay"+index;
                t1.id = "date_pay"+index;
				
                cell1.appendChild(t1);
            var cell2=row.insertCell(1);
            var t2=document.createElement("input");
				t2.type = "text";
				t2.size = "13";
				t2.name = "jumlah_pinjaman"+index;
                t2.id = "jumlah_pinjaman"+index;
				t2.style ="width:150px";
                cell2.appendChild(t2);
            var cell3=row.insertCell(2);
            var t3=document.createElement("input");
				t3.type = "text";
				t3.size = "12";
				t3.name = "bayaran_pinjaman"+index;
                t3.id = "bayaran_pinjaman"+index;
				t3.style ="width:150px";
                cell3.appendChild(t3);
            var cell4=row.insertCell(3);
            var t4=document.createElement("input");
				t4.type = "text";
				t4.size = "14";
				t4.name = "baki_pinjaman"+index;
                t4.id = "baki_pinjaman"+index;
				t4.style ="width:150px";
                cell4.appendChild(t4);
			var cell5=row.insertCell(4);
            var t5=document.createElement("input");
				t5.type = "text";
				t5.size = "19";
				t5.name = "resit_no"+index;
                t5.id = "resit_no"+index;
				t5.style ="width:170px";
                cell5.appendChild(t5);
			var cell6=row.insertCell(5);
            var t6=document.createElement("input");
				t6.type = "text";
				t6.size = "23";
				t6.name = "remark"+index;
                t6.id = "remark"+index;
				t6.style ="width:280px";
                cell6.appendChild(t6);
			var cell7=row.insertCell(6);
           
		
			
      index++;
	
}


function calculateInt()
{
	//$package = document.getElementById('loan_package').value;
	$loan = document.getElementById('pinjaman_pokok').value;
	$int = document.getElementById('kadar_faedah').value;
	$month = document.getElementById('tempoh_bayaran').value;
	
	/*if($package != 'SKIM CEK')
	{
		if($int == '13')
		{
			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
		}else
		if($int == '18')
		{
			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
		}else
		{*/
			var $loan_inttotal = (($int/100) / 12 * $loan) * $month;
			$loan_total = +$loan_inttotal+ +$loan;
			$monthly = $loan_total/$month
		/*}
	}else
	{
		$loan_inttotal = (($int/100) * $loan);
	}
	if($loan_inttotal != 0)
	{*/

		document.getElementById('jumlah_faedah').value = $loan_inttotal.toFixed(2);
		document.getElementById('jumlah_besar').value = $loan_total.toFixed(2);
		document.getElementById('bayaran_sebulan').value = (isFinite($monthly) && $monthly || 0).toFixed(2);
		
	/* } */
	
}
</script>
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