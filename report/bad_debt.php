<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$cust_id = $_GET['id'];

	$sql_1 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$cust_id."'");
	$result_1 = mysql_fetch_assoc($sql_1);
	$customerid = $result_1['customer_id'];
	$loan_code = $result_1['loan_code'];


		$sql2 = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$cust_id."' and payment ='0' ORDER BY id DESC LIMIT 1");
		$result_2 = mysql_fetch_assoc($sql2);
		$balance = $result_2['balance'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title></title>
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
#send_toblacklist
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#send_toblacklist:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	text-transform:uppercase;
}

#inner tr td
{
	padding-right:20px;
}
</style>
<center>
<form method="post" action="action_lateCust.php" onSubmit="return checkForm()">
	<input type="hidden" name="cust_id" id="cust_id" value="<?php echo $cust_id; ?>">
	<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customerid; ?>">
	<input type="hidden" name="loan_code" id="loan_code" value="<?php echo $loan_code; ?>">
	<input type="hidden" name="loan_package" id="loan_package" value="<?php echo '32'; ?>">
<table width="100%" id="list_table" border="0">
	<tr>
    	<th style="text-align: center;">Bad Debt</th>
    </tr>
        <tr>
          <td style="padding-left:10px; padding-right:10px">	
          	<div id="message" style="width:100%; text-align:left">
            <?php
            if($_SESSION['msg'] != '')
            {
            echo $_SESSION['msg'];
            $_SESSION['msg'] = '';
            }
            ?>						
    </div> </td>
          	</tr>
          		<tr>
		  	<table style="border-collapse:collapse" id="inner" align="center">
		  				<tr>
					<td><b>Bad Debt Month</b></td>
					<td>&nbsp;</td>
					<td colspan="4"><input type="text" name="month_receipt" id="month_receipt" style="width:100px" value="<?php echo date('Y-m'); ?>" autocomplete="off"/></td>
				</tr>
								<tr>
					<td></td>
					<td>&nbsp;</td>
					<td colspan="4"></td>
				</tr>
		  						<tr>
					<td><b>Date</b></td>
					<td>&nbsp;</td>
					<td colspan="4"><input type="date" name="bd_date" id="bd_date" required="" /></td>
				</tr>
								<tr>
					<td></td>
					<td>&nbsp;</td>
					<td colspan="4"></td>
				</tr>
				<tr>
					<td>Bad Debt Amount</td>
					<td>&nbsp;</td>
					<td colspan="4"><input type="text" name="baki_pinjaman" id="baki_pinjaman" class="currency" value="<?php echo $balance;?>" readonly/></td>
				</tr>
			</table>
		  </td>
        </tr>
                <tr>
          <td style="padding-left:10px; padding-right:10px"><br></td>
          	</tr>
      <tr>
    	<td style="padding-right:10px" align="right"><input type="submit" name="add_lateCust" id="send_toblacklist" value="" tabindex="4">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
    </tr>
</table>
<br>
</form>
</center>

<script>
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

function checkForm()
{
	if((document.getElementById('settlement_period').value != ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		var msg = "<div class='error'>You must enter the settlement period!</div>";
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


function cal()
{
	$jf = document.getElementById('jumlah_faedah').value;
	$lp = document.getElementById('loan_period').value;
	$bp = document.getElementById('baki_pinjaman').value;
	$sp = document.getElementById('settlement_period').value;

	

	$sa = $bp - (($jf/$lp)*$sp);
	document.getElementById('settlement_amount').value = $sa.toFixed(0);
}
</script>
</body>
</html>