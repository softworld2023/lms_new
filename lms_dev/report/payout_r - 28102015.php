<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM payout_details WHERE customer_loanid = '".$id."'");
$get_l = mysql_fetch_assoc($loan_q);

$payoutdate_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$payoutdate = mysql_fetch_assoc($payoutdate_q);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Golden One Entity</title>
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

<body>	

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
	height:30px;
	background:#F0F0F0;
	text-align:left;
	padding-left:10px;
}
#list_table tr td
{
	height:30px;
	padding-top:5px;
}
#hid
{
	border-collapse:collapse;
	border:none;
}

#hid tr th
{
	height:30px;
	background:#F0F0F0;
	text-align:left;
	padding-left:10px;
}
#hid tr td
{
	height:30px;
	padding-top:3px;
}
input
{
	height:30px;
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
    	<th colspan="2">Payout (<?php echo date('d-m-Y', strtotime($payoutdate['payout_date'])); ?>)</th>
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
      <td style="padding-left:30px;">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
    	<td width="40%" style="padding-left:30px;">Stamping</td>
        <td align="left">RM <?php echo number_format($get_l['stamping'], '2'); ?></td>
    </tr>
    <tr>
    	<td style="padding-left:30px;">CTOS</td>
        <td align="left">RM <?php echo number_format($get_l['ctos'], '2'); ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">CCRIS</td>
      <td align="left">RM <?php echo number_format($get_l['ccris'], '2'); ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">ML Settlement </td>
      <td align="left">RM <?php echo number_format($get_l['mlsettle'], '2'); ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Overlap</td>
      <td align="left">RM <?php echo number_format($get_l['overlap'], '2'); ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Commission</td>
      <td align="left">RM <?php echo number_format($get_l['cm'], '2'); ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Lawyer Fee</td>
      <td align="left">RM <?php echo number_format($get_l['lawyer_fees'], '2'); ?></td>
    </tr>
	<tr>
      <td style="padding-left:30px;">Processing Fee </td>
      <td align="left">RM <?php echo number_format($get_l['processing_fees'], '2'); ?></td>
    </tr>
    <tr>
    	<td style="padding-left:30px;">Others</td>
        <td align="left">RM <?php echo number_format($get_l['others'], '2'); ?></td>
    </tr>
	<tr>
    	<td style="padding-left:30px;">   	    Nett Payout</td>
        <td align="left"><strong>RM <?php echo number_format($get_l['nettp'], '2'); ?></strong></td>
    </tr>


	
    

   <tr height="20"></tr>
</table>
<br>
</form>
</center>
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
	
$('#month_receipt').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
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
	if((document.getElementById('total_pay').value != '' && document.getElementById('payment_date').value != ''))
	{
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
	$ttl = ($t*1) * (($i*1)/100);
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
	
	$ttl = $t - $s - $ctos - $ccris - $ml - $ov - $cm - $oth;
	
	document.getElementById('cash_pay').value = $ttl.toFixed(2);
	document.getElementById('total_pay').value = $ttl.toFixed(2);
}
</script>
</body>
</html>