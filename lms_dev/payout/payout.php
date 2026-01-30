<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$cid = $_GET['cid'];
$lid = $_GET['lid'];

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$cid."'");
$get_c = mysql_fetch_assoc($cust_q);

$pd_q = mysql_query("SELECT * FROM customer_account WHERE customer_id = '".$cid."'");
$pd = mysql_fetch_assoc($pd_q);

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lid."'");
$get_l = mysql_fetch_assoc($loan_q);


//next payment date
$year = date('Y');
$month1 = date('m');
$month = $month1 + 1;

if($month < 10)
{
	$month = '0'.$month;
}else
{
	if($month == '13')
	{
		$month = '01';
		$year = $year + 1;
	}
}

$nextpd = $year.'-'.$month.'-'.$pd['a_paymentdate'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>MAJUSAMA</title>
<script type="text/javascript" src="../include/js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../include/js/jquery-1.8.3.min.js"></script>
<script src="../include/js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="../include/css/style.css" />
<link rel="stylesheet" type="text/css" href="../include/css/media-queries.css" />
<script type="text/javascript" src="../include/js/jquery.confirm/jquery.confirm.js"></script>
<link rel="stylesheet" type="text/css" href="../include/js/jquery.confirm/jquery.confirm.css" />
<link rel="stylesheet" type="text/css" href="../include/js/anytimer/anytime.css" />
<script type="text/javascript" src="../include/js/anytimer/anytime.js"></script>
<link rel="stylesheet" href="../include/js/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../include/js/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>
<script type='text/javascript' src="../include/plugin/autocomplete/jquery.autocomplete.js"></script> 
<link rel="stylesheet" type="text/css" href="../include/plugin/autocomplete/jquery.autocomplete.css" /> 
<script type="text/javascript" src="../include/js/tokeninput/src/jquery.tokeninput.js"></script>
<link rel="stylesheet" type="text/css" href="../include/jquery/ui/themes/smoothness/jquery-ui-1.7.2.custom.css">
<link rel="stylesheet" type="text/css" href="../include/js/tokeninput/styles/token-input.css"/>
<link rel="stylesheet" type="text/css" href="../include/js/tokeninput/styles/token-input-facebook.css"/>

<script type="text/javascript" src="../include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../include/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript" src="../include/shadowbox/shadowbox.js"></script>
<link rel="stylesheet" type="text/css" href="../include/shadowbox/shadowbox.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon"></head>

<body style="display: none;">	

<script language=JavaScript>
<!--
var message="You are not allowed to copy content from this website!";
function clickIE4(){
if (event.button==2){
<!--alert(message);-->
return false;
}
}
function clickNS4(e){
if
(document.layers||document.getElementById&&!document.all){
if
(e.which==2||e.which==3){
<!--alert(message);-->
return false;
}
}
}
if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}
document.oncontextmenu=new Function("return false")
// -->
</script>
<style>
#list_table
{
	border-collapse:collapse;
	border:none;
}

#list_table tr th
{
	height:20px;
	background:#F0F0F0;
	text-align:left;
	padding-left:10px;
}
#list_table tr td
{
	height:20px;
	padding-top:3px;
}
#hid
{
	border-collapse:collapse;
	border:none;
}

#hid tr th
{
	height:20px;
	background:#F0F0F0;
	text-align:left;
	padding-left:10px;
}
#hid tr td
{
	height:20px;
	padding-top:3px;
}
input
{
	height:20px;
}
#reset
{
	background:url(../img/add-enquiry/clear-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#reset:hover
{
	background:url(../img/add-enquiry/clear-btn-roll-over.jpg);
}
#pay_cust
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#pay_cust:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	text-transform:uppercase;
}
</style>
<center>
<form method="post" action="action.php" onSubmit="return checkForm()">
<table width="100%" id="list_table" border="0">
	<tr>
    	<th colspan="2">Payout</th>
    </tr>
    
    <tr>
    	<td width="40%" style="padding-right:10px;" align="right"><b>Date</b></td>
        <td align="left"><input type="text" name="payout_date" id="payout_date" value="<?php echo date('d-m-Y'); ?>"></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">Customer Name</td>
        <td align="left"><input type="text" name="customer_name" id="customer_name" value="<?php echo $get_c['name']; ?>" style="width:230px" readonly="readonly" /><input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>" /><input type="hidden" name="lid" id="lid" value="<?php echo $lid; ?>" /></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">I.C. Number</td>
        <td align="left"><input type="text" name="nric" id="nric" value="<?php echo $get_c['nric']; ?>" readonly="readonly" /></td>
    </tr>
      <tr>
    	<td style="padding-right:10px;" align="right"><b>Start Month</b></td>
        <td align="left"><input type="text" name="start_month" id="start_month" value="<?php echo $get_l['start_month']; ?>" /></td>
    </tr>
 <tr>
    	<td style="padding-right:10px;" align="right"><b>No Resit</b></td>
        <td align="left"><input type="text" name="no_resit" id="no_resit" /></td>
    </tr>
	<tr>
    	<th colspan="2">Payment</th>
    </tr>
	<div id="message" style="width:100%; text-align:left">
            <?php
            if($_SESSION['msg'] != '')
            {
            echo $_SESSION['msg'];
            $_SESSION['msg'] = '';
            }
            ?>						
    </div>        
    <tr>
      <td style="padding-right:10px;" align="right">Approved Amount</td>
      <td align="left"><input type="text" name="approved_amount" id="approved_amount" value="<?php echo "RM ".number_format($get_l['loan_amount'], '2'); ?>" readonly="readonly" /><input type="hidden" name="appamt" id="appamt" value="<?php echo $get_l['loan_amount']; ?>">
        <input type="hidden" name="approved_amount1" id="approved_amount1" value="<?php echo $get_l['loan_amount']; ?>" />
        <input type="hidden" name="cheque_pay" id="cheque_pay" class="currency" placeholder="RM" onKeyUp="calculateTotal()" tabindex="2" />
        <input type="hidden" name="transfer_pay" id="transfer_pay" class="currency" placeholder="RM" onKeyUp="calculateTotal()" tabindex="3" /></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right">Processing Fees (10%)</td>
      <td align="left"><!-- <input type="text" name="intup" id="intup" style="width:40px" > %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; --> RM <input type="text" name="intamt" id="intamt" style="border:none" readonly="readonly" value="<?php echo $get_l['loan_amount']*0.1; ?>"></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right">Subtotal Loan Amount </td>
      <td align="left"><strong>RM</strong> 
        <input style="border:none; font-weight:bold" type="text" name="scash_pay" id="scash_pay" class="currency" onKeyUp="calculateTotal()" readonly="readonly" tabindex="1" value="<?php $nettpayout=$get_l['loan_amount']-($get_l['loan_amount']*0.1); echo $nettpayout ; ?>"/>&nbsp;</td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">Stamping</td>
        <td align="left"><input type="text" name="s" id="s" class="currency" onKeyUp="st()"></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">CTOS</td>
        <td align="left"><input type="text" name="ctos" id="ctos" class="currency" onKeyUp="st()"></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right">CCRIS</td>
      <td align="left"><input type="text" name="ccris" id="ccris" class="currency" onKeyUp="st()"></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right">ML Settlement </td>
      <td align="left"><input type="text" name="ml" id="ml" class="currency" onKeyUp="st()"></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right">Overlap</td>
      <td align="left"><span style="padding-right:10px;">
        <input type="text" name="ov" id="ov" class="currency" onKeyUp="st()">
      </span></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right">Commission</td>
      <td align="left"><input type="text" name="cm" id="cm" class="currency" onKeyUp="st()"></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">Lawyer Fee</td>
        <td align="left"><input type="text" name="lawf" id="lawf" class="currency" onKeyUp="st()"></td>
    </tr>
<!-- 	<tr>
	  <td style="padding-right:10px;" align="right">Processing Fees </td>
	  <td align="left"><input type="text" name="processf" id="processf" class="currency" onKeyUp="st()"></td>
	  </tr> -->
	<tr>
	  <td style="padding-right:10px;" align="right">Others</td>
	  <td align="left"><input type="text" name="oth" id="oth" class="currency" onKeyUp="st()"></td>
	  </tr>
	<tr>
    	<td style="padding-right:10px;" align="right">   	    Nett Payout</td>
        <td align="left"><strong>RM </strong>
          <input style="border:none; font-weight:bold" type="text" name="cash_pay" id="cash_pay" class="currency" onKeyUp="calculateTotal()" readonly="readonly" tabindex="1" value="<?php $nettpayout=$get_l['loan_amount']-($get_l['loan_amount']*0.1); echo $nettpayout ; ?>"/></td>
    </tr>

    <tr>
    	<th colspan="2">Mode of Payment</th>
    </tr>
     <tr>
      <td style="padding-right:10px;" align="right"><input type="radio" name="modeofpay" id="cash" value="CASH" onClick="hide_field()" /></td>
      <td align="left">Cash</td>
    </tr>
      <tr>
      <td style="padding-right:10px;" align="right"><input type="radio" name="modeofpay" id="cheque" value="CHEQUE" onClick="show_field()" /></td>
      <td align="left">Cheque</td>
    </tr>
      <tr>
      <td style="padding-right:10px;" align="right"><input type="radio" name="modeofpay" id="bank_trans1" value="BANK TRANSFER" onClick="show_field()" /></td>
      <td align="left">Bank Transfer</td>
    </tr>
     <tr>
      <td style="padding-right:10px; " align="right"><label id="hidbank" style="display:none">Bank</label></td>
      <td align="left" style=""><input type="text" name="bank_t" id="bank_t" style="display:none" /></td>
    </tr>
     <tr>
      <td style="padding-right:10px;" align="right"><label id="hidaccno" style="display:none">Account No</label></td>
      <td align="left" style=""><input type="text" name="b_acc_no" id="b_acc_no" style="display:none"/></td>
    </tr>
      <tr>
      <td style="padding-right:10px;" align="right"><label id="hidholder" style="display:none">Acc Holder name</label></td>
      <td align="left" style=""><input type="text" name="b_acc_holder" id="b_acc_holder" style="display:none" /></td>
    </tr>
	<tr>
	  <td style="padding-right:10px;" align="right">Receipt No </td>
	  <td><span style="padding-right:10px;">
	    <input type="text" name="receipt_no" id="receipt_no" tabindex="3"  onBlur="checkReceipt(this.value)" value="<?php echo $get_l['loan_code']; ?>"><input type="hidden" name="loanp" id="loanp" value="<?php echo $get_l['loan_package']; ?>"><input type="hidden" name="apply_date" id="apply_date" value="<?php echo $get_l['apply_date']; ?>">
	  </span></td>
	  </tr>

	<?php
	if($get_l['loan_type'] == 'Flexi Loan')
	{
		$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".mysql_real_escape_string($get_l['loan_package'])."'");
		$get_rt = mysql_fetch_assoc($rt_q);
		
		if($get_rt['receipt_type'] == 2)//receipt code changing
		{
	?>
		<tr>
		<td style="padding-right:10px;" align="right">Month Of Receipt</td>
		<td><input type="text" name="month_receipt" id="month_receipt" tabindex="3" /></td>
	</tr>
	<?php 
		}else
		{
	?>
		<input type="hidden" name="month_receipt" id="month_receipt" tabindex="3" value="<?php echo date('Y-m'); ?>" />
	<?php
		}
	} ?>
    <tr>
      <td style="padding-right:10px;" align="right">Next Payment Date</td>
      <td align="left"><input type="text" name="payment_date" id="payment_date" value="<?php echo date('d-m-Y', strtotime($nextpd)); ?>" tabindex="3" /></td>
    </tr>

   <tr height="20"></tr>
     <tr>
    	<td colspan="2" style="padding-right:10px" align="right"><input type="submit" name="pay_cust" id="pay_cust" value="" tabindex="4">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
    </tr>
</table>
<br><input type="hidden" name="ltype" id="ltype" value="<?php echo $get_l['loan_type']; ?>">
<input type="hidden" name="total_pay" id="total_pay" class="currency" />
</form>
</center>

<script>
    window.onload = function() {
        if (document.getElementById("customer_name").value === "UNREGISTERED") {
            document.getElementById("pay_cust").click();
        }else {
            document.body.style.display = "block";
        }
    };
</script>

<script>
function show_field(){
if(bank_trans1.checked == true){
hidbank.style.display = 'block';
hidaccno.style.display = 'block';
hidholder.style.display = 'block';
bank_t.style.display = 'block';
b_acc_no.style.display = 'block';
b_acc_holder.style.display = 'block';

}

if(cheque.checked == true){
hidbank.style.display = 'block';
hidaccno.style.display = 'block';
hidholder.style.display = 'block';
bank_t.style.display = 'block';
b_acc_no.style.display = 'block';
b_acc_holder.style.display = 'block';

}
}
function hide_field()
{
hidbank.style.display = 'none';
hidaccno.style.display = 'none';
hidholder.style.display = 'none';
bank_t.style.display = 'none';
b_acc_no.style.display = 'none';
b_acc_holder.style.display = 'none';

}
</script>
<script>
$(document).ready(function() {
	document.getElementById('intup').focus();
});

$('#payment_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
	
$('#start_month').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
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

function calculateTotal()
{
	var cash = document.getElementById('cash_pay').value;
	var cheque = document.getElementById('cheque_pay').value;
	var transfer = document.getElementById('transfer_pay').value;
	var tot = (cash*1) + (cheque*1) + (transfer*1);
	
	document.getElementById('total_pay').value = tot.toFixed(2); 
}
$('#payout_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

function checkForm()
{
	//2021-09-23
	// if((document.getElementById('total_pay').value != '' && document.getElementById('payment_date').value != ''))
	if( document.getElementById('payment_date').value != '')
	{
		$loanp = document.getElementById('loanp').value;
		if($loanp == 'SKIM S')
		{
			$mr = document.getElementById('month_receipt').value;
			$receiptmonth = $mr.substr(5, 2);
			$receipt = document.getElementById('receipt_no').value;
			$newrm = $receipt.substr(0, 2);
			
			
		}
	
		if((document.getElementById('ltype').value != 'Flexi Loan'))
		{
			$('#message').empty();
			return true;	
		}else
		{
			if((document.getElementById('month_receipt').value != ''))
			{
				$('#message').empty();
				return true;
			}else
			{
				var msg = "<div class='error'>You must enter the month of the receipt!</div>";
				$('#message').empty();
				$('#message').append(msg); 
				$('#message').html();
				return false;
			}
		}
	}
	else
	{
		var msg = "<div class='error'>You must enter the total amount paid to customer and the next payment date!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;

	}
}
// mini jQuery plugin that formats to two decimal places
(function($) {
	$.fn.currencyFormat = function() {
    	this.each( function( i ) {
        $(this).change( function( e ){
        	if( isNaN( parseFloat( this.value ) ) ) return;
        	this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
    }
})( jQuery );

// apply the currencyFormat behaviour to elements with 'currency' as their class
$( function() {
    $('.currency').currencyFormat();
});

function checkReceipt(str)
{
	if (str.length==0)
	  { 
	  return;
	  }
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var ajaxDisplay = xmlhttp.responseText;
			var res=ajaxDisplay.split("#");
			var err = res[0];
			
			if(err != '')
			{
				var msg = "<div class='error'>" + res[0] + "</div>";
				$('#message').empty();
				$('#message').append(msg); 
				$('#message').html();
				document.getElementById('receipt_no').value = '';
				document.getElementById('receipt_no').focus();
			}else
			{
				$('#message').empty();
			}
		}
	  }
	  
	xmlhttp.open("GET","checkReceipt.php?code="+escape(str),true);
	xmlhttp.send();
}
function calpay(v)
{
	$t = document.getElementById('appamt').value;
	$i = (v)*1;
	$ttlbr1 = ($t*1) * ($i/100);
	$ttlbr = $ttlbr1.toFixed(2);
	$ttl2 = $t - $ttlbr;
	
	$ttlround = Math.round($ttlbr);
	
	if($ttlround < $ttlbr)
	{
		$ttl = ($ttlround + 1)*1;
	}else
	{
		$ttl = $ttlround*1;
	}
	
	$ttl2 = $t - $ttl;
	document.getElementById('intamt').value = $ttl.toFixed(2);
	document.getElementById('cash_pay').value = $ttl2.toFixed(2);
	document.getElementById('scash_pay').value = $ttl2.toFixed(2);
	document.getElementById('total_pay').value = $ttl2.toFixed(2);
	
}
function st()
{	

	$t = document.getElementById('scash_pay').value;
	$s = document.getElementById('s').value;
	$ctos = document.getElementById('ctos').value;
	$ccris = document.getElementById('ccris').value;
	$ml = document.getElementById('ml').value;
	$ov = document.getElementById('ov').value;
	$cm = document.getElementById('cm').value;
	$oth = document.getElementById('oth').value;
	$lawf = document.getElementById('lawf').value;
	$processf = document.getElementById('processf').value;
	
	$ttl = $t - $s - $ctos - $ccris - $ml - $ov - $cm - $oth - $lawf - $processf;
	
	document.getElementById('cash_pay').value = $ttl.toFixed(2);
	document.getElementById('total_pay').value = $ttl.toFixed(2);
}
</script>
</body>
</html>