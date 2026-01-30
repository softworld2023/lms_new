<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
$get_cust = mysql_fetch_assoc($cust_q);

$add_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$get_q['customer_id']."'");
$get_add = mysql_fetch_assoc($add_q);

$loandetails_q = mysql_query("SELECT * FROM flexi_loan_details WHERE customer_loanid = '".$id."'");

$company_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$get_q['customer_id']."'");
$get_c = mysql_fetch_assoc($company_q);

$salary_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$get_q['customer_id']."'");
$get_s = mysql_fetch_assoc($salary_q);
?>

<style>
input
{
	height:25px;
}
.submit_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'remove.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}
.app_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'sent.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}
.reject_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'cancel-icon.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}

#list_table
{
	border-collapse:collapse;
	border:none;	
}

#list_table tr th
{
	height:36px;
	background:#666;
	text-align:left;
	padding-left:10px;
	color:#FFF;
}
#list_table tr td
{
	height:35px;
	padding-left:10px;
	padding-right:10px;
}

#rl
{
	width:318px;
	height:36px;
	background:url(../img/customers/right-left.jpg);
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
#saveperiod
{
	background:url(../img/document_save.png);
	width:24px; 
	height:24px;
	cursor:pointer;
}
</style>
<center>
<table width="1280" id="list_table">
	<tr>
    	<td colspan="6" style="padding:0px">Personal Payment Record - Fixed Loan</td>
    </tr>
    <tr>
   	  <td align="right" style="padding:0px">Customer Code</td>
        <td><input type="text" name="customer_code" id="customer_code" /></td>
      <td align="right" style="padding:0px">Company Name</td>
      <td><input type="text" name="company_name" value="<?php echo $get_c['company']; ?>" readonly="readonly" style="width:230px" /></td>
      <td align="right" style="padding:0px">Loan Package</td>
      <td><input type="text" name="loan_package" value="<?php echo $get_q['loan_package']; ?>" readonly="readonly" style="width:230px" /></td>
    </tr>
    <tr>
      <td align="right" style="padding:0px">Customer Name</td>
        <td><input type="text" name="name" value="<?php echo $get_cust['name']; ?>" style="width:230px" readonly="readonly" /></td>
      <td align="right" style="padding:0px">Salary</td>
      <td><input type="text" name="salary" value="<?php echo "RM ". $get_s['net_salary']; ?>" readonly="readonly" /></td>
      <td align="right" style="padding:0px">Loan Amount</td>
      <td><input type="text" name="loan_amount" value="<?php echo "RM ".$get_q['loan_amount']; ?>" readonly="readonly" /></td>
    </tr>
    <tr>
    	<td align="right" style="padding:0px">I.C. Number</td>
        <td><input type="text" name="nric" value="<?php echo $get_cust['nric']; ?>" readonly="readonly" /></td>
      <td align="right" style="padding:0px">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" style="padding:0px">Loan Total</td>
        <td><input type="text" name="loan_total" value="<?php echo "RM ".$get_q['loan_total']; ?>" readonly="readonly" /></td>
    </tr> 
    <tr>
    	<td align="right" style="padding:0px">Contact</td>
        <td><input type="text" name="contact" value="<?php echo $get_add['mobile_contact']; ?>" readonly="readonly" /></td>
        <td align="right" style="padding:0px">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" style="padding:0px">&nbsp;</td>
        <td>&nbsp;</td>
    </tr>    
    <tr>
   	  	<td colspan="6">
        	<div id="message" style="width:100%;">
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
</table>
<table width="1280" id="list_table">
  <tr>
	  	<th>Months</th>
	  	<th>Total Loan</th>
    	<th>Balance</th>
   	  <th>Payment Received</th>
        <th>Payment Date</th>
        <th width="56">&nbsp;</th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_l = mysql_fetch_assoc($loandetails_q))
	{
	$ctr++;
	?>
    <tr>
      	<td><?php echo $ctr; ?></td>
   	  	<td><?php echo "RM ".$get_q['loan_total']; ?></td>
    	<td><?php echo "RM ".$get_l['balance']; ?></td>
   	  <td>
		  <?php if($get_l['payment'] == '0' && $get_l['payment_date'] == '0000-00-00') {  ?><input type="hidden" name="id" id="id" value="<?php echo $get_l['id']; ?>" /><input type="text" name="payment" id="payment" class="currency" style="width:130px" /><?php } else { echo "RM ".$get_l['payment']; } ?>
        </td>
        <td>
			<?php if($get_l['payment_date'] != '0000-00-00'){ echo $get_l['payment_date']; } else {?><input type="text" name="payment_date" id="payment_date" style="width:130px" value="<?php echo date('Y-m-d'); ?>" /><?php } ?>
        </td>
        <td>
			<?php if($get_l['payment'] == '0' && $get_l['payment_date'] == '0000-00-00') { ?>
            <a href="javascript:payConfirm()"><img src="../img/payment-received/payment-received.png" width="30" /></a>
            <?php } ?>
    	</td>
    </tr>
    <?php } ?>
    <?php
	if($ctr != $get_q['loan_period'])
	{
		$ctr_new = $ctr+1;
		for($ctr = $ctr_new; $ctr <= $get_q['loan_period']; $ctr++)
		{
	?>
    <tr>
      	<td><?php echo $ctr; ?></td>
   	  	<td></td>
    	<td></td>
    	<td></td>
        <td></td>
        <td></td>
    </tr>
    <?php }} ?>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../payment/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
</table>
</center>
<script>
	$('#payment_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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

function payConfirm()
{
	$amount = document.getElementById('payment').value;
	$date = document.getElementById('payment_date').value;
	$id = document.getElementById('id').value;
		
	if($amount != '' && $date != '')
	{
		$.confirm({
			'title'		: 'Payment Confirmation',
			'message'	:  'Received RM ' + $amount +' from <?php echo $get_cust['name']; ?>?',
			'buttons'	: {
				'Yes'	: {
				'class'	: 'blue',
				'action': function(){
					$.ajax({
							type: 'POST',
							data: {
								action: 'pay_flexiloan',
								amount: $amount,
								date: $date,
								id: $id,
							},
							url: 'action.php',
							success: function(){
								location.reload();
							}
						})
					}
				},
				'No'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	}else
	{
		alert("Please enter the payment amount and payment date!");
	}
}

</script>