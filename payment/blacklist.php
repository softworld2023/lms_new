<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$cust_id = $_GET['id'];

	$sql_1 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$cust_id."'");
	$result_1 = mysql_fetch_assoc($sql_1);
	$loan_pokok = $result_1['loan_pokok'];
	$loan_total = $result_1['loan_total'];
	$loan_period = $result_1['loan_period'];
	$jumlah_faedah = $loan_total - $loan_pokok;


		$sql2 = mysql_query("SELECT * FROM loan_lejar_details WHERE customer_loanid = '$cust_id' and payment ='0' ORDER BY id DESC LIMIT 1");
		$result_2 = mysql_fetch_assoc($sql2);
		$baki_pinjaman = $result_2['balance'];
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
<form method="post" action="action_blacklist.php" onSubmit="return checkForm()"><input type="hidden" name="custid" id="custid" value="<?php echo $result_1['customer_id']; ?>">
	<input type="hidden" name="loan_code" id="loan_code" value="<?php echo $result_1['loan_code']; ?>"><input type="hidden" name="blacklisttype" id="blacklisttype" />
<table width="100%" id="list_table" border="0">
	<tr>
    	<th style="text-align: center;">Bad debt Customer </th>
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
        <tr>

		  		<table style="border-collapse:collapse" id="inner" align="center">
		  			<tr>
		  	<table style="border-collapse:collapse" id="inner" align="center">
		  				  		<tr>
					<td><b>Date</b></td>
					<td>&nbsp;</td>
					<td ><input type="text" name="b_date" id="b_date" required="" autocomplete="off" /></td>
				</tr>
								<tr>
					<td></td>
					<td>&nbsp;</td>
					<td colspan="4"></td>
				</tr>

				<tr>
					<td>Amount (RM)</td>
					<td>&nbsp;</td>
					<td><input type="text" name="blacklistamt" id="blacklistamt" value="<?php echo $baki_pinjaman;?>" class="currency" /></td>
				</tr>
								<tr>
					<td></td>
					<td>&nbsp;</td>
					<td colspan="4"></td>
				</tr>
			</table>
		  </td>
        </tr>
        
      <tr>
    	<td style="padding-right:10px" align="right"><input type="submit" name="send_toblacklist" id="send_toblacklist" value="" tabindex="4">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
    </tr>
</table>
<br>
</form>
</center>

<script>


$('#b_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
	if((document.getElementById('b_date').value != '' && document.getElementById('blacklistamt').value != ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		var msg = "<div class='error'>You must enter the amount and select CTOS / CCRIS!</div>";
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

function changeType(type)
{
	document.getElementById('blacklisttype').value = type;
}
</script>
</body>
</html>