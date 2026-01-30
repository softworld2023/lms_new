<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");


if (!isset($_GET['id']) && !isset($_GET['loan_code'])) {
    // If 'id' is not present in the query string, show an alert and redirect
    echo "<script>
            alert('Loan Code is required. Please try again.');
        	window.history.back();
          </script>";
    exit; // Stop the script execution
}

$customerid = $_GET['id'];
$loan_code = $_GET['loan_code'];

$customerid = mysql_real_escape_string($customerid);
$loan_code = mysql_real_escape_string($loan_code);

$sql_1 = mysql_query("SELECT 
						r.*, cd.customercode2 , cd.id as customer_id
					FROM 
						customer_loanpackage r
					LEFT JOIN 
						customer_details cd ON r.customer_id = cd.id 
					WHERE 
						cd.id = '".$customerid."'
					AND
						r.loan_code = '".$loan_code."'");
if (mysql_num_rows($sql_1) < 1) {
	echo "<script>
			alert('Loan Code not found. Please try again.');
        	window.history.back();
		  </script>";
	exit; // Stop the script execution after redirect
}
$result_1 = mysql_fetch_assoc($sql_1);
$id = $result_1['id'];
$customer_id = $result_1['customer_id'];
$customercode = $result_1['customercode2'];
$payout_amount = $result_1['payout_amount'];
$discount_amount = $result_1['discount'];
$loan_code = $result_1['loan_code'];

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
#back
{
	background:url(../img/back-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#back:hover
{
	background:url(../img/back-btn-roll-over.jpg);
}
</style>
<center>
<form id="discountForm" method="post" action="action_editdiscount.php">
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
	<input type="hidden" name="cust_id" id="cust_id" value="<?php echo $cust_id; ?>">
	<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customerid; ?>">
	<input type="hidden" name="loan_code" id="loan_code" value="<?php echo $loan_code; ?>">
	<input type="hidden" name="loan_package" id="loan_package" value="<?php echo '32'; ?>">
<table width="100%" id="list_table" border="0">
	<tr>
    	<th style="text-align: center;">Discount & Settlement</th>
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
			</div> 
		</td>
    </tr>
    <tr>
	  	<table style="border-collapse:collapse" id="inner" align="center">
		  	<tr>
				<td>Customer Code</td>
				<td>&nbsp;</td>
				<td colspan="4">
					<input type="text" name="customer_code" id="customer_code" value="<?php echo $customercode;?>" <?php echo (!empty($customerid)) ? 'readonly' : ''; ?> />
					<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
				</td>
			</tr>	
			<tr>
				<td></td>
				<td>&nbsp;</td>
				<td colspan="4"></td>
			</tr>
			<tr>
				<td>Loan Code</td>
				<td>&nbsp;</td>
				<td colspan="4">
				<input type="text" name="loan_code" id="loan_code" value="<?php echo $loan_code;?>" <?php echo (!empty($loan_code)) ? 'readonly' : ''; ?> />
				</td>
			</tr>
			<tr>
				<td></td>
				<td>&nbsp;</td>
				<td colspan="4"></td>
			</tr>
			<tr>
				<td>Discount Amount</td>
				<td>&nbsp;</td>
				<td colspan="4">
					<input type="number" name="discount" id="discount" class="currency" value="<?php echo $discount_amount;?>" />
				</td>
			</tr>
		</table>
    </tr>
    <tr>
        <td style="padding-left:10px; padding-right:10px"><br></td>
    </tr>
    <tr>
    	<td style="padding-right:10px" align="right"><input type="submit" name="update_discount" id="send_toblacklist" value="" tabindex="4">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
    </tr>
	<div style="margin-left: auto; margin-right: auto; width: 100%; margin-top: 20px;">
		<input type="button" id="settlement_btn" value="Settlement" style="background:#4CAF50; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer;">
	</div>
</table>
<br>
</form>

<div id="settlementModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="modal-body">
      Loading settlement info...
    </div>
  </div>
</div>

<div style="margin-left: 20%;">
	<input type="button" name="back" id="back" onClick="window.location.href='index.php'" value="">
</div>

<style>
.modal {
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: #fff;
  margin: 10% auto;
  padding: 20px;
  border-radius: 6px;
  width: 60%;
  position: relative;
}

.close {
  color: #aaa;
  position: absolute;
  right: 16px;
  top: 10px;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: #000;
}
</style>

</center>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script type="text/javascript" src="/Lms/include/ckeditor/adapters/jquery.js"></script> -->
<script>
$(document).ready(function() {
    $('#discountForm').submit(function(e) {
        e.preventDefault(); // prevent default form submission

        var form = $(this);
        var formData = form.serialize();

        // Optional: validate if discount field is empty
        if ($('#discount').val().trim() === '') {
            $('#message').html("<div class='error'>Discount cannot be empty!</div>");
            return;
        }

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData,
            success: function(response) {
                $('#message').html(response);

                // Optional: refresh page after 2 seconds
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function() {
                $('#message').html("<div class='error'>There was an error processing your request.</div>");
            }
        });
    });
});

$(document).ready(function() {
    $('#settlement_btn').click(function() {
        var formData = $('#discountForm').serialize();

        $('#modal-body').html('Loading settlement info...');
        $('#settlementModal').fadeIn();

        var id = $('#id').val().trim();
        var loan_code = $('#loan_code').val().trim(); // You had mistakenly used `#discount`

        $.ajax({
            type: 'POST',
            url: 'instalment_settlement.php?id=' + encodeURIComponent(id) + '&loan_code=' + encodeURIComponent(loan_code),
            data: formData,
            success: function(response) {
                $('#modal-body').html(response);
            },
            error: function() {
                $('#modal-body').html("<div class='error'>Failed to load settlement info.</div>");
            }
        });
    });

    $('.close').click(function() {
        $('#settlementModal').fadeOut();
    });

    // Close modal on outside click
    $(window).click(function(e) {
        if ($(e.target).is('#settlementModal')) {
            $('#settlementModal').fadeOut();
        }
    });
});


$('#bd_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

</script>

</body>
</html>