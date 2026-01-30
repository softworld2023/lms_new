<?php
session_start();
include("../include/dbconnection.php");
error_reporting(0);
//insert path to database connection
$id = $_GET['branch_id'];

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
<?php 
session_start();
//insert db connection here

$branch_id = $id; // add branch session here

$year = date('Y'); // change the year.
$sql_q = mysql_query("SELECT * FROM  customer_details WHERE branch_id = '".$branch_id."'" );

//$sql_q = mysql_query("SELECT * FROM  customer_details cd, customer_loanpackage lp WHERE lp.payout_date LIKE '%".$year."%' AND lp.branch_id = '".$branch_id."' AND cd.id = lp.customer_id");

?>
<body>
<div class="no-print">

<div id="message">
<?php
if($_SESSION['msg'] != '')
{
	echo $_SESSION['msg'];
    $_SESSION['msg'] = '';
}
?>
</div>
<h3>Generate Details</h3>
<!--<br>
&nbsp;&nbsp;&nbsp;<button class="all_btn" onClick="print();">Print</button>
<br>-->
</div>
<div class="page">
<form method="post" action="action.php">
<div class="no-print">


<tr>
    	<td colspan="4" align="center">&nbsp;<input type="submit" name="submit" id="submit" value="Save"  class="all_btn">&nbsp;&nbsp;&nbsp;
		<input type="button" name="back" value="Back" class="all_btn" onClick="window.location.href='../lampiranB1/index.php?branch_id=<?php echo $_GET['branch_id']; ?>'" ></td>
    </tr>
	<br><br>
<hr/>
</div>
<table width="85%"  border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right">LAMPIRAN 'B1'</td>
  </tr>
  <tr>
    <td align="center"><p align="center">PERATURAN-PERATURAN PEMBEN PINJAM WANG ( KAWALAN DAN  PERLESENAN ) 2003<br>
      ( Subperaturan 16(1) )<br>
      REKOD TRANSAKSI TAHUNAN MENGIKUT PERINCIAN PINJAMAN BAGI TAHUN <?php echo $year; ?>
      <br>
      <br>
      <br>
    </p></td>
  </tr>
  <tr>
    <td align="left">
    <p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;NAMA SYARIKAT PEMBERI PINJAM WANG: 
	<input type="text" style="font-weight:bold; font-size:14px; border: none;" size="30" id="branch_name<?php echo $c; ?>" name="branch_name<?php echo $c; ?>">
	<br>
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
        <td width="6%" rowspan="2"><p>Bercagar/Tanpa Cagaran </p>
          <p><br>
            (9)</p></td>
        <td width="8%" height="38" colspan="2">Syarikat </td>
        <td width="8%" colspan="3">Individu</td>
        <td width="10%">Nota #</td>
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
		$cust_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$sql['id']."'");
		$cust = mysql_fetch_assoc($cust_q);
		
		$payment_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$sql['id']."'");
		$payment = mysql_fetch_assoc($payment_q);
		
		$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$sql['id']."' ");
		$loan = mysql_fetch_assoc($loan_q);
		
		$sal_q = mysql_query("SELECT * FROM  customer_financial WHERE customer_id = '".$sql['id']."'");
		$sal = mysql_fetch_assoc($sal_q);
		
		$emp_q = mysql_query("SELECT * FROM  customer_employment WHERE customer_id = '".$sql['id']."'");
		$emp = mysql_fetch_assoc($emp_q);
		
		$total_q = mysql_query("SELECT * FROM customer_details WHERE branch_id = '".$_GET['branch_id']."'");
		$total = mysql_num_rows($total_q);

	  ?>
      <tr align="center">
        <td><input type="hidden" id="count" name="count" value="<?php echo $c; ?>"><?php echo $c; ?><input type="hidden" size="11" id="branch_id<?php echo $c; ?>" name="branch_id<?php echo $c; ?>" value="<?php echo $_GET['branch_id']; ?>"><input type="hidden" size="11" id="nric<?php echo $c; ?>" name="nric<?php echo $c; ?>" value="<?php echo $sql['nric']; ?>">
		<input type="hidden" size="11" id="total" name="total" value="<?php echo $total; ?>"></td>
        <td><input type="hidden" style="font-weight:bold; font-size:14px; border: none;" size="13" id="name<?php echo $c; ?>" name="name<?php echo $c; ?>" value="<?php echo strtoupper($sql['name']); ?>"><?php echo strtoupper($sql['name']); ?></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none;" size="7" id="loan_total<?php echo $c; ?>" name="loan_total<?php echo $c; ?>" value="<?php echo $loan['loan_total']; ?>"></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none;"size="7"  id="balance<?php echo $c; ?>" name="balance<?php echo $c; ?>"></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none;" size="7" id="loan_period<?php echo $c; ?>" name="loan_period<?php echo $c; ?>" value="<?php echo $loan['loan_period']; ?>"></td>
        <td><input type="date" style="font-weight:bold; font-size:14px; border: none;" size="7" id="payout_date<?php echo $c; ?>" name="payout_date<?php echo $c; ?>" value="<?php echo $loan['payout_date']; ?>"></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none;" size="9" id="mobile_contact<?php echo $c; ?>" name="mobile_contact<?php echo $c; ?>" value="<?php echo $cust['mobile_contact']; ?>"></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none;" size="4" id="int_percent<?php echo $c; ?>" name="int_percent<?php echo $c; ?>" value="1.5"></td>
        <td><select id="jenis_cagaran<?php echo $c; ?>" name="jenis_cagaran<?php echo $c; ?>"  style="width: 142px;">
										<option value=""></option>
										<option value="BERCAGAR">BERCAGAR</option>
										<option value="TANPA CAGARAN">TANPA CAGARAN</option>
										</select></td>
        <td colspan="2"><select id="taraf<?php echo $c; ?>" name="taraf<?php echo $c; ?>">
										<option value=""></option>
										<option value="BUMI">BUMI</option>
										<option value="BUKAN BUMI">BUKAN BUMI</option>
										</select></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none; text-transform: uppercase;" size="7" id="race<?php echo $c; ?>" name="race<?php echo $c; ?>" value="<?php echo strtoupper($sql['race']); ?>"></td>
		<td><input type="text" style="font-weight:bold; font-size:14px; border: none; text-transform: uppercase;" size="8" id="employer<?php echo $c; ?>" name="employer<?php echo $c; ?>" value="<?php echo strtoupper($emp['c_working_type']); ?>"></td>
		<td><input type="text" style="font-weight:bold; font-size:14px; border: none; text-transform: uppercase;" size="6" id="salary<?php echo $c; ?>" name="salary<?php echo $c; ?>" value="<?php echo $sal['net_salary']; ?>"></td>
        <td><input type="text" style="font-weight:bold; font-size:14px; border: none;" size="4" id="remark<?php echo $c; ?>" name="remark<?php echo $c; ?>"></td>
      </tr>
	  <?php } ?>
    </table></td>
  </tr>
  </table>
</div>

</body>
<script>
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
			var $loan_inttotal = (($int/100) * $loan) * $month;
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
		document.getElementById('bayaran_sebulan').value = $monthly.toFixed(2);
		
	/* } */
	
}
</script>
</html>
