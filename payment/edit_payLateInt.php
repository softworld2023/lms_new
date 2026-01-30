<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$id = $_GET['id'];

$payment_q = mysql_query("SELECT * FROM late_interest_payment_details WHERE id = '".$id."'");
$get_p = mysql_fetch_assoc($payment_q);

/*
$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_p['customer_loanid']."'");
$get_l = mysql_fetch_assoc($loan_q);*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<!-- <title>Golden One Entity</title> -->
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
#edit_lateInt
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_lateInt:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
</style>
<center>
<form method="post" action="action_editLateInt.php">
<input type="hidden" name="id" id="id" value="<?php echo $get_p['id']; ?>">
<table width="100%" id="list_table">
	<tr>
    	<th colspan="2">Edit</th>
    </tr>
    <tr>
    	<td colspan="2" style="padding:0px">&nbsp;</td>
    </tr>
    <tr>
    	<td width="35%" style="padding-right:10px;" align="right">Payment Date</td>
        <td align="left"><input type="text" name="payment_date" id="payment_date" value="<?php echo $get_p['payment_date']; ?>" readonly="readonly"  style="width:75px"></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">Amount</td>
        <td align="left"><input type="text" name="amount" id="amount" class="currency" placeholder="RM <?php echo $get_p['amount']; ?>"/></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">Balance</td>
        <td align="left"><input type="text" name="balance" id="balance" class="currency" placeholder="RM <?php echo $get_p['balance']; ?>" /></td>
    </tr>
    <tr>
    	<td colspan="2" style="padding:0px">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="2" style="padding:0px">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="2" style="padding:0px">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" style="padding-right:10px" align="right">
			<input type="submit" name="edit_lateInt" id="edit_lateInt" value="" tabindex="4">
			&nbsp;&nbsp;&nbsp;
			<input type="reset" id="reset" name="reset" value=""></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
</table>
</form>
</center>
<script>
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
</script>
</body>
</html>