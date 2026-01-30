<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM payout_details WHERE customer_loanid = '".$id."'");
$get_l = mysql_fetch_assoc($loan_q);

$loan_pd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
$loan_pd = mysql_fetch_assoc($loan_pd_q);

$cust_id = $get_l['customer_id'];

$cust_p_q = mysql_query("SELECT * FROM customer_account where customer_id = '".$cust_id."'");
$cust_p = mysql_fetch_assoc($cust_p_q);

$cust_n_q = mysql_query("SELECT * FROM customer_details where id = '".$cust_id."'");
$cust_n = mysql_fetch_assoc($cust_n_q);

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
	padding-top:10px;
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
#update_payout
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#update_payout:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
</style>
<center>
<form method="post" action="action_update_payout.php" onSubmit="return checkForm()">
<input type="hidden" name="customer_loanid" id="customer_loanid" value="<?php echo $get_l['customer_loanid']; ?>"><input type="hidden" name="id" id="id" value="<?php echo $get_l['id']; ?>">
<div id="message" style="width:100%; text-align:left">
            <?php
            if($_SESSION['msg'] != '')
            {
            echo $_SESSION['msg'];
            $_SESSION['msg'] = '';
            }
            ?>						
    </div>
<table width="100%" id="list_table" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <th colspan="2" align="left"><p>&nbsp;</p>
	    <p><span style="padding-left:30px;">
	      <input style="border:none; font-weight:bold;" type="hidden" name="cash_pay" id="cash_pay" value="<?php echo number_format($get_l['cash_pay'], '2', '.',''); ?>"  onKeyUp="st()" readonly tabindex="1" />
        </span>Payout</p></th>   
	  </tr>
   
      
        <td align="left" style="padding-left:30px;">Date</td>
	  <td><p>
	    <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'  || $_SESSION['login_username'] == 'huizhen')) { ?>
	    <input type="text" name="payout_date" id="payout_date" style="width:80px; height:15px" value="<?php if($payoutdate['payout_date'] != '0000-00-00') { echo date('d-m-Y', strtotime($payoutdate['payout_date'])); } ?>" />
	    <?php } else { if($payoutdate['payout_date'] != '0000-00-00') { echo date('d-m-Y', strtotime($payoutdate['payout_date'])); } }?><a href="javascript:payoutdateConfirm('<?php echo $payoutdate['id']; ?>')" title="save payout date"><img src="../img/document_save.png" /></a>
	  </p></td>
    </tr>  <tr>
        <td align="left" style="padding-left:30px;">Customer Name</td>
        <td><input type="text" name="stamping2" style="height:15px; width:300px;" id="stamping2" value="<?php echo $cust_n['name']; ?>" class="currency" onKeyUp="st()" disabled="disabled"></td>
      </tr>  
      <tr>
        <td align="left" style="padding-left:30px;">I/C No.</td>
        <td><input type="text" name="stamping3" style="height:15px" id="stamping3" value="<?php echo $cust_n['nric']; ?>" class="currency" onKeyUp="st()" disabled="disabled"></td>
      </tr>
      <tr>
        <td colspan="2" align="left" bgcolor="#CCCCCC" style="height:12px">&nbsp;</td>
      </tr>
      <tr>
        <td style="padding-left:30px;">Approved Loan Amount</td>
        <td align="left"><input type="text" name="stamping4" style="height:15px" id="stamping4" value="<?php echo $get_l['approved_amount']; ?>" class="currency" onKeyUp="st()" disabled="disabled"></td>
      </tr>
      <tr>
        <td style="padding-left:30px;">Interest</td>
        <td align="left"><input type="text" name="stamping5" style="height:15px; width:30px" id="stamping5" value="<?php echo 100 - (($get_l['cash_pay'] / $get_l['approved_amount']) * 100) ?>" class="currency" onKeyUp="st()" disabled="disabled"> 
          RM  <input type="text" name="stamping7" style="height:15px" id="stamping7" value="<?php echo $get_l['approved_amount'] - $get_l['cash_pay']; ?>" class="currency" onKeyUp="st()" disabled="disabled"></td>
      </tr>
      <tr bgcolor="#CCCCCC" style="height:auto">
        <td style="padding-left:30px;;"><strong>Sub Total Loan</strong></td>
        <td align="left"><input type="text" name="stamping6" style="height:15px" id="stamping6" value="<?php echo $get_l['cash_pay']; ?>" class="currency" onKeyUp="st()" disabled="disabled"></td>
      </tr>
      <tr>
    	<td width="40%" style="padding-left:30px;">Stamping</td>
        <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'  || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="stamping" style="height:15px" id="stamping" value="<?php echo $get_l['stamping']; ?>" class="currency" onKeyUp="st()"><?php } else { echo $get_l['stamping']; } ?></td>
    </tr>
    <tr>
    	<td style="padding-left:30px;">CTOS</td>
        <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="ctos" id="ctos" style="height:15px" value="<?php echo $get_l['ctos']; ?>" class="currency" onKeyUp="st()" ><?php } else { echo $get_l['ctos']; } ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">CCRIS</td>
      <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="ccris" id="ccris" style="height:15px" value="<?php echo $get_l['ccris']; ?>" class="currency" onKeyUp="st()"><?php } else { echo $get_l['ccris']; } ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">ML Settlement </td>
      <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="mlsettle" id="mlsettle" style="height:15px" value="<?php echo $get_l['mlsettle']; ?>" class="currency" onKeyUp="st()"><?php } else { echo $get_l['mlsettle']; } ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Overlap</td>
      <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="overlap" id="overlap" style="height:15px" value="<?php echo $get_l['overlap']; ?>"class="currency" onKeyUp="st()"><?php } else { echo $get_l['overlap']; } ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Commission</td>
      <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="cm" id="cm" style="height:15px" value="<?php echo $get_l['cm']; ?>"class="currency" onKeyUp="st()"><?php } else { echo $get_l['cm']; } ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Lawyer Fee</td>
      <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="lawyer_fees" id="lawyer_fees" style="height:15px" value="<?php echo $get_l['lawyer_fees']; ?>"class="currency" onKeyUp="st()"><?php } else { echo $get_l['lawyer_fees']; } ?></td>
    </tr>
    <tr>
      <td style="padding-left:30px;">Processing Fee </td>
      <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="processing_fees" id="processing_fees" style="height:15px" value="<?php echo $get_l['processing_fees']; ?>"class="currency" onKeyUp="st()"><?php } else { echo $get_l['processing_fees']; } ?></td>
    </tr>
    <tr>
    	<td style="padding-left:30px;">Others</td>
        <td align="left">RM <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?><input type="text" name="others" id="others" style="height:15px" value="<?php echo number_format($get_l['others'], '2', '.', ''); ?>"class="currency" onKeyUp="st()"><?php } else { echo number_format($get_l['others'], '2'); } ?></td>
    </tr>
	<tr height="10">
    	<td style="padding-left:30px;"  >    Nett Payout</td>
        <td align="left"><strong>RM </strong> <input style="border:none; font-weight:bold" type="text"  name="nettp" id="nettp" value="<?php echo number_format($get_l['nettp'], '2','.',''); ?>" class="currency" onKeyUp="st()" readonly tabindex="1"></td>
    </tr>
	<tr height="10">
<td colspan="2" style="padding-right:10px" align="right"><input type="submit" name="update_payout" id="update_payout" value="" tabindex="4">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
	  </tr>
	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
<?php } ?>	
   </table>
<br>
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

<script>
$(document).ready(function() {
	document.getElementById('intup').focus();
	document.getElementById('stamping').focus();
});
function payoutdateConfirm(id)
{
	$id = id;

	$payoutdate = document.getElementById('payout_date').value;
	
	$.ajax({
			type: 'POST',
			data: {
				action: 'update_npd2',
				date: $payoutdate,
				id: $id
			},
			url: 'action_editdate.php',
			success: function(){
				location.reload();
			}
		})	
}


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
	var cash = document.getElementById('nettp').value;
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
	document.getElementById('nettp').value = $ttl2.toFixed(2);
	document.getElementById('cash_pay').value = $ttl2.toFixed(2);
	document.getElementById('total_pay').value = $ttl2.toFixed(2);
	
}
function st()
{	
	
	$t = document.getElementById('cash_pay').value*1;
	$s = document.getElementById('stamping').value*1;
	$ctos = document.getElementById('ctos').value*1;
	$ccris = document.getElementById('ccris').value*1;
	$ml = document.getElementById('mlsettle').value*1;
	$ov = document.getElementById('overlap').value*1;
	$cm = document.getElementById('cm').value*1;
	$oth = document.getElementById('others').value*1;
	$lawf = document.getElementById('lawyer_fees').value*1;
	$processf = document.getElementById('processing_fees').value*1;
	
	$ttl = $t - $s - $ctos - $ccris - $ml - $ov - $cm - $oth - $lawf - $processf;
	
	
	document.getElementById('nettp').value = $ttl.toFixed(2);
	document.getElementById('total_pay').value = $ttl.toFixed(2);
}


</script>
</body>
</html>