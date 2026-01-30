<?php include('../include/dbconnection.php'); 
$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$loan = mysql_fetch_assoc($loan_q);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
$cust = mysql_fetch_assoc($cust_q);

$address_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$id."'");
$address = mysql_fetch_assoc($address_q);

$loan2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND month = '1'");
$loan2 = mysql_fetch_assoc($loan2_q);

$rate = $loan2['int_percent'] / 12;
?>
<style type="text/css">
<!--
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
	border:solid thin #000;
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
#update
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#update:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
}

#listingtbl tr td
{
	height:20px
}
-->
</style>

<title>FIRST SCHEDULE</title><center>
<table width="1280">
    <tr>
    	<td width="65" align="center" style="font-size:16px;"><b>FIRST SCHEDULE</b></td>
    </tr>
    <tr>
      <td align="center"><b>(which is to be read and construed as an intrgral part of this Agreement )</b></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
    </tr>
</table>
<br />

<table width="1091" id="list_table" >
   
    <tr>
    	<td width="127" align="center"><div align="center"><strong>Section No. </strong></div></td>
        <td width="498"><div align="center"><strong>Item</strong></div></td>
        <td width="633"><div align="center"><strong>Particulars</strong></div></td>
    </tr>
    <tr>
    	<td align="center">1</td>
    	<td >The day and year of this Agreement </td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="5" align="center" valign="top" style="padding-top:10px;">2</td>
        <td style="border-bottom:none"><p>Name</p>        </td>
        <td style="border-bottom:none">&nbsp;</td>
    </tr>
    <tr>
      <td style="border-top:none; border-bottom:none">I.C. No. </td>
      <td style="border-top:none; border-bottom:none">&nbsp;</td>
    </tr>
    <tr>
      <td style="border-top:none; border-bottom:none">Company Registration No. </td>
      <td style="border-top:none; border-bottom:none">&nbsp;</td>
    </tr>
    <tr>
      <td style="border-top:none; border-bottom:none">Lisence No. </td>
      <td style="border-top:none; border-bottom:none">&nbsp;</td>
    </tr>
    <tr>
      <td style="border-top:none">And address of the Lender </td>
      <td style="border-top:none">&nbsp;</td>
    </tr>
    <tr>
    	<td rowspan="4" align="center" valign="top" style="padding-top:10px;">3</td>
        <td style="border-bottom:none"><p>Name</p>        </td>
        <td style="border-bottom:none"><?php echo strtoupper($cust['name']); ?></td>
    </tr>
    <tr>
      <td style="border-top:none; border-bottom:none">I.C. No. </td>
      <td style="border-top:none; border-bottom:none"><?php echo $cust['nric']; ?></td>
    </tr>
    <tr>
      <td style="border-top:none; border-bottom:none">Company Registration No. </td>
      <td rowspan="2" style="border-top:none; border-bottom:none; padding-top:10px"><?php if($address['postcode'] != '0') { echo strtoupper($address['address1']." ".$address['address2']." ".$address['address3']." ".$address['postcode']." ".$address['city']." ".$address['state']); } else { echo strtoupper($address['address1']." ".$address['address2']." ".$address['address3']." ".$address['city']." ".$address['state']); } ?></td>
    </tr>
    
    <tr>
      <td style="border-top:none">And address of the Borrower</td>
    </tr>
    
    <tr>
      <td rowspan="2" align="center" valign="top" style="padding-top:10px">4</td>
      <td rowspan="2" valign="top" style="padding-top:10px">Principal Sum </td>
      <td style="border-bottom:none">RINGGIT MALAYSIA         <?php include('convert_text4.php'); ?></td>
    </tr>
    <tr>
      <td style="border-top:none">RM <?php echo $loan2['balance']; ?></td>
    </tr>
    <tr>
      <td align="center">5</td>
      <td>Interest rate </td>
      <td>The rate of interest is at <span style="text-decoration:underline">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($rate, '2'); ?>%&nbsp;&nbsp;&nbsp;&nbsp;( <?php echo $loan2['int_percent']; ?>% )</span> per annum </td>
    </tr>
    <tr>
      <td align="center">6</td>
      <td>Amount of each instalment repayment </td>
      <td>RM <?php echo $loan2['monthly']; ?> X <?php $lp = $loan['loan_period'] - 1; echo $lp; ?> MONTHS</td>
    </tr>
    <tr>
      <td align="center">7</td>
      <td>Amount od final instalment repayment </td>
      <td>RM <?php echo $loan2['monthly']; ?> X 1 MONTH</td>
    </tr>
</table>
</center>
<script>
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this user: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_staff',
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
}

function showEdit(no)
{
	if(document.getElementById('edit_' + no).style.visibility == 'hidden')
	{
		document.getElementById('edit_' + no).style.visibility = 'visible';	
	}else
	if(document.getElementById('edit_' + no).style.visibility == 'visible')
	{
		document.getElementById('edit_' + no).style.visibility = 'hidden';
	}
}

function updateAmount(no, id)
{
	$amount = $('#amount_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
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