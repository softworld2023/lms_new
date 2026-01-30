<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$mprid = $_GET['mprid'];
$detail_id = $_GET['detail_id'];

$payment_q = mysql_query("SELECT * FROM monthly_payment_record WHERE id = '".$mprid."'");
$payment = mysql_fetch_assoc($payment_q);

$payment_q1 = mysql_query("SELECT * FROM monthly_payment_details WHERE id = '".$detail_id."'");
$payment1 = mysql_fetch_assoc($payment_q1);
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
<link rel="icon" href="../img/favicon.ico" type="image/x-icon">
</head>

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
	padding-top:10px;
}
input
{
	height:25px;
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
#edit_Monthly_detail
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_Monthly_detail:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
</style>
<center>
<form method="post" action="action_payout_monthly.php" onSubmit="return checkForm()" autocomplete="off">
	<input type="hidden" name="mprid" id="mprid" value="<?php echo $mprid; ?>">
	<input type="hidden" name="detail_id" id="detail_id" value="<?php echo $detail_id; ?>">
	<input type="hidden" name="loan_code" id="loan_code" value="<?php echo $payment['loan_code']; ?>">
	<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $payment['customer_id']; ?>">
	<input type="hidden" name="payment_id" id="payment_id" value="<?php echo "32"; ?>">
<table width="100%" id="list_table">
	<tr>
    	<th colspan="2">Monthly Payment </th>
    </tr>
    <tr>
    	<td colspan="2" style="padding:0px">
			<div id="message" style="width:100%; text-align:left">
            <?php
            if($_SESSION['msg'] != '')
            {
            echo $_SESSION['msg'];
            $_SESSION['msg'] = '';
            }
            ?>						
            </div>		</td>
    </tr>
    
	
    <tr>
    	<td width="35%" align="right" style="padding-right:10px;">Amount</td>
        <td align="left">
			<input type="text" name="payment_amount" id="payment_amount" class="currency" />
		</td>
    </tr>
	
    <tr>
    	<td colspan="2" style="padding:0px">&nbsp;</td>
    </tr>
	
    <tr>
    	<td colspan="2" style="padding-right:10px" align="right">
			<input type="submit" name="edit_Monthly_detail" id="edit_Monthly_detail" value="" tabindex="4">
			&nbsp;&nbsp;&nbsp;
			<input type="reset" id="reset" name="reset" value="">
		</td>
    </tr>
	
    <tr>
    	<td>&nbsp;</td>
    </tr>
</table>
</form>
</center>
<script>
	$('#payment_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

$('#date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
$(document).ready(function() {
	document.getElementById('late_period').focus();
});

function checkForm()
{
	if((document.getElementById('payment_amount').value != '' && document.getElementById('payment_date').value != ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		var msg = "<div class='error'>You must fill in the date and amount!</div>";
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
				document.getElementById('new_receipt').value = '';
				document.getElementById('new_receipt').focus();
			}else
			{
				$('#message').empty();
			}
		}
	  }
	  
	xmlhttp.open("GET","checkReceipt.php?code="+escape(str),true);
	xmlhttp.send();
}
</script>
</body>
</html>