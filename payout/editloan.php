<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$get_l = mysql_fetch_assoc($loan_q);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_l['customer_id']."'");
$get_c = mysql_fetch_assoc($cust_q);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Edit Loan</title>
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
textarea
{
	text-transform:uppercase;
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
#edit_loan
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_loan:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	text-transform:uppercase;
}
</style>
<center>
<form method="post" action="action.php"><input type="hidden" name="loanid" id="loanid" value="<?php echo $id; ?>" />
<table width="100%" id="list_table" border="0">
	<tr>
    	<th colspan="2">Customer Details</th>
    </tr>
    
    <tr>
      <td align="right" style="padding-right:10px;">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" style="padding-right:10px;">Customer Code</td>
      <td align="left"><input type="text" name="cust_code" id="cust_code" value="<?php echo $get_c['customercode2']; ?>" readonly="readonly"></td>
    </tr>
    <tr>
    	<td width="40%" align="right" style="padding-right:10px;">Customer Name</td>
        <td align="left"><input type="text" name="customer_name" id="customer_name" value="<?php echo $get_c['name']; ?>" style="width:230px" readonly="readonly" /><input type="hidden" name="loanid" id="loanid" value="<?php echo $id; ?>" /></td>
    </tr>
    <tr>
    	<td style="padding-right:10px;" align="right">I.C. Number</td>
        <td align="left"><input type="text" name="nric" id="nric" value="<?php echo $get_c['nric']; ?>" readonly="readonly" /></td>
    </tr>
    

	<tr>
    	<th colspan="2">Loan Details </th>
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
      <td style="padding-right:10px;" align="right">Loan Package </td>
      <td align="left"><input type="text" name="loan_package" id="loan_package" value="<?php echo $get_l['loan_package']; ?>" readonly /></td>
    </tr>
    
    <tr>
    	<div id="message3"></div>
      <td style="padding-right:10px;" align="right"><b>Loan Code </b></td>
      <td align="left"><input type="text" name="loan_code" id="loan_code" style="width:80px" tabindex="16" autocomplete="OFF" onblur="checkLoanCode(this.value)" value="<?php echo $get_l['loan_code'];?>" /></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right"><b>Loan Amount (RM) </b></td>
      <td align="left"><input type="text" name="loan_amount" id="loan_amount" value="<?php echo number_format($get_l['loan_amount'], '2'); ?>" class="currency" /></td>
    </tr>
    <tr>
      <td style="padding-right:10px;" align="right"><b>Loan Period </b></td>
      <td align="left"><input type="text" name="loan_period" id="loan_period" value="<?php echo $get_l['loan_period']; ?>" style="width:40px" /> Month(s)</td>
    </tr>
    <tr>
    	<td style="padding-right:10px"  align="right"><b>Loan Pokok (RM)</b>  </td>
        <td><input type="text" name="loan_pokok" id="loan_pokok" class="currenciesOnly" value="<?php echo number_format($get_l['loan_pokok'], '2'); ?>" readonly/>
        </td>
<!--     <tr>
      <td style="padding-right:10px;" align="right">Interest Rate </td>
      <td align="left"><input type="text" name="loan_interest" id="loan_interest" style="width:40px" value="<?php echo $get_l['loan_interest'] ?>" onKeyUp="loanAmt()"> 
        %&nbsp;&nbsp;&nbsp;</td>
    </tr>
	<tr>
	  <td style="padding-right:10px;" align="right">Interest Amount </td>
	  <td><input type="text" name="interest_total" id="interest_total" value="<?php echo number_format($get_l['loan_interesttotal'], '2'); ?>" ></td>
	  </tr> -->
	<tr>
		<tr>
			 <td style="padding-right:10px" align="right">Amount Monthly (RM) </td>
                  <td><input type="text" name="loan_amount_month" id="loan_amount_month" readonly="readonly" value="<?php echo number_format(($get_l['loan_total']/$get_l['loan_period']), '2'); ?>" />
                  </td>
		</tr>
	  <td style="padding-right:10px;" align="right">Total Loan Amount (RM)  </td>
	  <td><input type="text" name="loan_total" id="loan_total" value="<?php echo number_format($get_l['loan_total'], '2'); ?>" readonly ></td>
	</tr>
<!-- 	<tr>
	  <td style="padding-right:10px;padding-top:5px" valign="top" align="right">Loan Remarks</td>
	  <td><textarea name="loan_remarks" id="loan_remarks" style="width:300px; height:50px"><?php echo $get_l['loan_remarks']; ?></textarea></td>
	</tr> -->

	<?php
	if($get_l['loan_type'] == 'Flexi Loan')
	{
		$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".mysql_real_escape_string($get_l['loan_package'])."'");
		$get_rt = mysql_fetch_assoc($rt_q);
		
		if($get_rt['receipt_type'] == 2)//receipt code changing
		{
	?>
	<?php 
		}else
		{
	?>
		<input type="hidden" name="month_receipt" id="month_receipt" tabindex="3" value="<?php echo date('Y-m'); ?>" />
	<?php
		}
	} ?>
     <tr>
    	<td colspan="2" style="padding-right:10px" align="right"><input type="submit" name="edit_loan" id="edit_loan" value="" tabindex="4">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
    </tr>
</table>
<br><input type="hidden" name="ltype" id="ltype" value="<?php echo $get_l['loan_type']; ?>" style="text-transform:none">
<input type="hidden" name="total_pay" id="total_pay" class="currency" />
</form>
</center>
<script>

	function checkLoanCode(str)
{
	$loan_package = document.getElementById('loan_package').value;
	
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
				$('#message3').empty();
				$('#message3').append(msg); 
				$('#message3').html();
				document.getElementById('loan_code').value = '';
				document.getElementById('loan_code').focus();
			}else
			{
				$('#message3').empty();
			}
		}
	  }
	  
	xmlhttp.open("GET","checkLoanCode.php?loan_package="+$loan_package+"&code="+escape(str),true);
	xmlhttp.send();
}

	$("#loan_period,#loan_amount").on("blur",function(){

		var loan_package = $("#loan_package").val();
		var loan_amount = $("#loan_amount").val();
		var loan_period = $("#loan_period").val();
		var dataString = 'loan_amount='+loan_amount+'&loan_period='+loan_period;
		if(loan_package=='NEW PACKAGE'){
			
			$.ajax({
				url: 'autofill_newpackage.php',
				type: "post",
				data: dataString,
				cache: true,
				success: function (result){
					if(result != ""){
						var parsed = jQuery.parseJSON(result);
						$("#loan_amount_month").val(parsed[0]);
						$("#loan_total").val(parsed[1]);
						$("#loan_pokok").val(parsed[2]);
						console.log(result);
					}
				}
			})
		}
	});
function loanAmt()
{
	$package = document.getElementById('loan_package').value;
	$loan = document.getElementById('loan_amt').value;
	$int = document.getElementById('loan_interest').value;
	$month = document.getElementById('loan_period').value;
	
	if($package != 'SKIM CEK')
	{
		if($int == '13')
		{
			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
		}else
		if($int == '18')
		{
			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
		}else
		{
			$loan_inttotal = (($int/100) * $loan) * $month;
		}
	}else
	{	
		$loan_inttotal = (($int/100) * $loan);
	}
	
	if($loan_inttotal != 0)
	{
		document.getElementById('interest_total').value = $loan_inttotal.toFixed(2);
	}
	
	$loantot = ($loan*1) + ($loan_inttotal*1);
	
	document.getElementById('loan_total').value = $loantot.toFixed(2) ;
}

function calculateInt()
{
	$package = document.getElementById('loan_package').value;
	$loan = document.getElementById('loan_amt').value;
	$int = document.getElementById('loan_interest').value;
	$month = document.getElementById('loan_period').value;
	
	if($package != 'SKIM CEK')
	{
		if($int == '13')
		{
			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
		}else
		if($int == '18')
		{
			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
		}else
		{
			$loan_inttotal = (($int/100) * $loan) * $month;
		}
	}else
	{	
		$loan_inttotal = (($int/100) * $loan);
	}
	
	if($loan_inttotal != 0)
	{
		document.getElementById('interest_total').value = $loan_inttotal;
	}
	
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