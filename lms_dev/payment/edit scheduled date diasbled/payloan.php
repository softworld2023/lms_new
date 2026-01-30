<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
$get_cust = mysql_fetch_assoc($cust_q);

$add_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$get_q['customer_id']."'");
$get_add = mysql_fetch_assoc($add_q);

$loandetails_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
$ldrow = mysql_num_rows($loandetails_q);

$company_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$get_q['customer_id']."'");
$get_c = mysql_fetch_assoc($company_q);

$salary_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$get_q['customer_id']."'");
$get_s = mysql_fetch_assoc($salary_q);

$acc_q = mysql_query("SELECT * FROM customer_account WHERE customer_id = '".$get_q['customer_id']."'");
$getacc = mysql_fetch_assoc($acc_q);

//int rate
$intrate_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
$intrate = mysql_fetch_assoc($intrate_q);
?>

<style>
input
{
	height:25px;
}
textarea
{
	font-size:12px;
	color:#666;
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
    	<td colspan="6" style="padding:0px">Personal Payment Record - Flexi Loan</td>
    </tr>
    <tr>
   	  <td style="padding:0px">Customer Code </td>
        <td><input type="text" name="customercode2" id="customercode2" value="<?php echo $get_cust['customercode2']; ?>" style="width:100px" /></td>
        <td style="padding:0px">Package</td>
      <td><input type="text" name="loan_package" value="<?php echo $get_q['loan_package']; ?>" readonly="readonly" /></td>
      <td style="padding:0px">Bank</td>
      <td><input type="text" name="bankacc" id="bankacc" value="<?php echo $getacc['a_bankname']; ?>" style="width:230px" /></td>
    </tr>
    <tr>
      <td style="padding:0px">Full Name</td>
        <td><input type="text" name="name" value="<?php echo $get_cust['name']; ?>" style="width:230px" readonly="readonly" /></td>
      <td style="padding:0px">Loan Code</td>
      <td><input type="text" name="customer_code" id="customer_code" readonly="readonly" style="width:80px" value="<?php echo $get_q['loan_code']; ?>" /></td>
      <td style="padding:0px">Pay Date</td>
      <td><div id="save_period" style="display:none"><a href="javascript:updatePeriod('<?php echo $get_q['loan_period'] ?>', '<?php echo $id; ?>')"><input type="button" name="saveperiod" id="saveperiod" value="" /></a></div>
      <input type="text" name="payday" id="payday" value="<?php echo $getacc['a_payday']; ?>" style="width:230px" /></td>
    </tr>
    <tr>
    	<td style="padding:0px">New I/C. No.</td>
        <td><input type="text" name="nric" value="<?php echo $get_cust['nric']; ?>" readonly="readonly" /></td>
      <td style="padding:0px">Loan Amount (RM) </td>
        <td><input type="text" name="loan_amount" value="<?php echo "RM ".number_format($get_q['loan_amount'], '2'); ?>" readonly="readonly" />&nbsp;<a href="payout.php?id=<?php echo $id; ?>" rel="shadowbox; width=500px; height=500px;"><img src="../img/customers/view-icon.png" /></a></td>
        <td style="padding:0px">Payment Date </td>
        <td><input type="text" name="a_paymentdate" id="a_paymentdate" value="<?php echo $getacc['a_paymentdate']; ?>" /></td>
    </tr> 
    <tr>
      <td style="padding:0px">Mobile Phone </td>
      <td><input type="text" name="contact" value="<?php echo $get_add['mobile_contact']; ?>" readonly="readonly" /></td>
      <td style="padding:0px">Interest Rate </td>
      <td><input type="text" style="width:40px" value="<?php echo $intrate['int_percent']; ?>%" readonly="readonly" /></td>
      <td style="padding:0px">Monthly Net Salary </td>
      <td><input type="text" name="salary" value="<?php echo "RM ".number_format($get_s['net_salary'], '2'); ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td style="padding:0px">Notes <a href="javascript:noteConfirm('<?php echo $getacc['id']; ?>')" title="save remarks"><img src="../img/document_save.png" /></a></td>
      <td rowspan="3"><textarea name="a_remarks" id="a_remarks" style="width:230px; height:90px"><?php echo $getacc['a_remarks']; ?></textarea></td>
      <td style="padding:0px">Loan Period</td>
      <td><input type="text" name="period" id="period" value="<?php echo $get_q['loan_period']; ?>" style="width:50px" onkeyup="changePeriod(this.value,'<?php echo $get_q['loan_period']; ?>')" />
months </td>
      <td style="padding:0px">Remarks</td>
      <td rowspan="3"><textarea name="textarea" readonly="readonly" style="width:230px; height:90px"><?php echo $get_q['loan_remarks']; ?></textarea></td>
    </tr>
    <tr>
    	<td style="padding:0px">&nbsp;</td>
        <td style="padding:0px">Total Loan (RM) </td>
        <td><input type="text" name="loan_total" value="<?php echo "RM ".number_format($get_q['loan_total'], '2'); ?>" readonly="readonly" /></td>
        <td style="padding:0px">&nbsp;</td>
    </tr>   
	<tr>
		<td  style="padding:0px">&nbsp;</td>
		<td  style="padding:0px">Monthly Payment (RM) </td>
		<td>-<input type="hidden" name="tdd" id="tdd" value="<?php echo date('Y-m-d'); ?>" /></td>
		<td  style="padding:0px">&nbsp;</td>
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
            </div>      	</td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
	  	<th>Months</th>
	  	<th>Scheduled Date</th>
	  	<th>Payment Date</th>
	  	<th>Receipt No. </th>
	  	<th>Receipt Month</th>
	  	<th width="90">Loan Balance</th>
	  	<th>Payment<br />Received - CCM</th>
    	<th>Payment<br />Received - Interest </th>
        <th>New Receipt </th>
        <th>New Loan </th>
        <th>New Receipt Month </th>
        <!--<th>Next Payment Date</th>-->
        <th width="70" style="padding:0px">&nbsp;</th>
    </tr>
    <?php 
	$ctr = 0;
	$latectr = 0;
	$todaydate = date('Y-m-d');
	
	$latecount_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND payment_date = '0000-00-00'");
	$latecount = mysql_num_rows($latecount_q);
	
	while($get_l = mysql_fetch_assoc($loandetails_q))
	{
	$ctr++;
	if($todaydate <= $get_l['next_paymentdate'])
	{
		$style = '';
	}else
	{
		if($get_l['payment_date'] != '0000-00-00')
		{
			$style = '';
		}
		else
		{
			$style = 'style="color:#F00"';
			$latectr++;
		}
	}
	?>
    <?php if($get_l['int_balance'] != 0 || $get_l['balance'] != 0) {?>
	<tr <?php echo $style; ?>>
      	<td><?php echo $ctr; ?></td>
      	<td><?php echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); ?></td>
   	  	<td><?php if($get_l['payment_date'] == '0000-00-00') {  ?>
            <?php if($style == '') {?>
            <input type="text" name="payment_date" id="payment_date" style="width:80px" value="<?php echo date('d-m-Y'); ?>" />
            <?php } else { ?>
            <?php //if($latectr == $latecount) { ?>
            <input type="text" name="payment_date" id="payment_date" style="width:80px" value="<?php echo date('d-m-Y'); ?>" />
            <?php //} else { ?>
            <?php //} ?>
            <?php } ?>
            <?php } else { echo date('d-m-Y', strtotime($get_l['payment_date'])); } ?>        </td>
   	  	<td><?php echo $get_l['receipt_no']; ?></td>
   	  	<td><?php echo $get_l['month_receipt']; ?></td>
   	  	<td><?php echo "RM ".number_format($get_l['balance'], '2'); ?></td>
   	  	<td>
			<?php $totalp = number_format(($get_l['pokok'] + $get_l['interest']), '2'); ?>
			<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
			<?php if($style == '') {?>
			<input type="hidden" name="id" id="id" value="<?php echo $get_l['id']; ?>" /><input type="text" name="payment" id="payment" class="currency" style="width:100px" placeholder="RM" />
			<?php } else { ?>
			<?php //if($latectr == $latecount) { ?>
				<input type="hidden" name="id" id="id" value="<?php echo $get_l['id']; ?>" /><input type="text" name="payment" id="payment" class="currency" style="width:100px" placeholder="RM" />
			<?php // } else { echo "RM ".$get_l['payment']; } ?>
			<?php } ?>
			<?php } else { echo "RM ".$get_l['payment']; } ?>		</td>
		<td>
			<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
			<?php if($style == '') {?>
			<input type="text" name="payment_int" id="payment_int" class="currency" style="width:100px" placeholder="RM" />
			<?php } else { ?>
			<?php //if($latectr == $latecount) { ?>
			<input type="text" name="payment_int" id="payment_int" class="currency" style="width:100px" placeholder="RM" />
			<?php //} else { echo "RM ".$get_l['payment']; } ?>
			<?php } ?>
			<?php } else { echo "RM ".$get_l['payment_int']; } ?>		</td>
		<td>
			<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
			<?php if($style == '') {?>
			<input type="text" name="new_receipt" id="new_receipt" style="width:80px" value="<?php echo $get_q['loan_code']; ?>"/>
			<?php } else { ?>
			<?php //if($latectr == $latecount) { ?>
			<input type="text" name="new_receipt" id="new_receipt" style="width:80px" value="<?php echo $get_q['loan_code']; ?>"/>
			<?php //} else { echo $get_l['receipt_no']; } ?>
			<?php } ?>
			<?php } else { echo $get_l['receipt_no']; } ?>		</td>
		<td>
			<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
			<?php if($style == '') {?>
			<input type="text" name="new_loan" id="new_loan" style="width:80px" class="currency"/>
			<?php } else { ?>
			<?php //if($latectr == $latecount) { ?>
			<input type="text" name="new_loan" id="new_loan" style="width:80px" class="currency"/>
			<?php //}?>
			<?php } ?>
			<?php }else { 
			//calculate new loan
			$newmonth = $get_l['month'] + 1;
			$newloan_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_l['customer_loanid']."' AND month = '".$newmonth."'");
				$newloan = mysql_fetch_assoc($newloan_q);
				if ($get_l['payment'] != 0)
				{
					if($get_l['payment'] == $get_l['balance'])
					{
						$newloanamt = $get_l['balance'] - $get_l['payment'] + $newloan['balance'];
					}else
					{
						$newloanamt = 0;
					}
				}
				else
				{
					$newloanamt = $newloan['balance'] - $get_l['balance'] - $get_l['payment'];
				}
				echo "RM ". number_format($newloanamt, '2');
				
			?>
			<?php } ?>
	  </td>
            <td>
				<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
				<?php if($style == '') {?>
				<input type="text" name="month_receipt" id="month_receipt" style="width:80px" onfocus="nextdate(this.value)"/>
				<?php } else { ?>
				<?php if($latectr == $latecount) { ?>
				<input type="text" name="month_receipt" id="month_receipt" style="width:80px" onfocus="nextdate(this.value)"/>
				<?php } else { 
					$nxtm = $get_l['month'] + 1;
					$nxtd_q = mysql_query("SELECT * FROM loan_payment_details WHERE month = '".$nxtm."' AND customer_loanid = '".$get_l['customer_loanid']."'");
					$nxtd = mysql_fetch_assoc($nxtd_q);
				?>
				<?php echo $get_l['month_receipt']; ?>
				<input type="hidden" name="month_receipt" id="month_receipt" style="width:80px" value="<?php echo $nxtd['month_receipt']; ?>"/>
				<?php } ?>
				<?php } ?>
				<?php } else { 
				$newmonth = $get_l['month'] + 1;
				$nrmth_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_l['customer_loanid']."' AND month = '".$newmonth."'");
				$nrmth = mysql_fetch_assoc($nrmth_q);
				
				
				
				echo $nrmth['month_receipt']; } 
				
				?>	
				<?php
				$np_day = $getacc['a_paymentdate'];
				if($np_day < 10)
				{
					$np_day = '0'.$np_day;
				}
				$npdate = date('Y-m').'-'.$np_day;
				$todaydate = date('Y-m-d');
				$np_month = date('m');
				$np_year = date('Y');
				
				if($npdate <= $todaydate)
				{
					if($np_month < 12)
					{
						$np_month = $np_month + 1;
						
						if($np_month < 10)
						{
							$np_month = '0'.$np_month;
						}
					}else
					{
						$np_month = '01';
						$np_year = $np_year + 1;
					}
				}
				
				if($np_month == '02')
				{
					if($np_day > 28)
					{
						$np_day = 28;
					}
				}
				if($np_month == '04')
				{
					if($np_day > 30)
					{
						$np_day = 30;
					}
				}
				if($np_month == '06')
				{
					if($np_day > 30)
					{
						$np_day = 30;
					}
				}
				if($np_month == '09')
				{
					if($np_day > 30)
					{
						$np_day = 30;
					}
				}
				if($np_month == '11')
				{
					if($np_day > 30)
					{
						$np_day = 30;
					}
				}
				
					
				
				$np_newdate = $np_day.'-'.$np_month.'-'.$np_year; 
				?>
				<input type="hidden" name="np_newdate" id="np_newdate" value="<?php echo $np_newdate; ?>" />
				<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
				<?php if($style == '') {?>
				<input type="hidden" name="next_paymentdate" id="next_paymentdate" style="width:80px" value="<?php echo $np_newdate; ?>" readonly="readonly" />
				<?php } else { ?>
				<?php if($latectr == $latecount) { ?>
				<input type="hidden" name="next_paymentdate" id="next_paymentdate" style="width:80px" value="<?php echo $np_newdate; ?>" readonly="readonly" />
				<?php  } else { ?>
				<input type="hidden" name="next_paymentdate" id="next_paymentdate" style="width:80px" value="<?php echo date('d-m-Y', strtotime($nxtd['next_paymentdate'])); ?>" readonly="readonly" />
				<?php } ?>
				<?php } ?>
				<?php } else { 
				$new_month = $get_l['month'] + 1; 
					$npd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND month = '".$new_month."'");	
					$get_npd = mysql_fetch_assoc($npd_q);
					/*if($get_npd['next_paymentdate'] != '0000-00-00')
					{
						echo date('d-m-Y', strtotime($get_npd['next_paymentdate']));
					}else
					{
						echo '-';
					}*/
				 } ?>		
			</td>
            <!--<td></td>-->
            <td style="padding:0px">
                <?php if($get_l['payment'] == '0' && $get_l['payment_date'] == '0000-00-00') { ?>
                <a href="javascript:payConfirm('<?php echo $get_q['loan_period']; ?>', '<?php echo $get_l['month']; ?>')" title="pay"><img src="../img/payment-received/payment-received.png" width="30" /></a>&nbsp;&nbsp;&nbsp;<a href="adjustflexi.php?id=<?php echo $get_l['id']; ?>&p=<?php echo $get_q['loan_period']; ?>" rel="shadowbox; width=800px; height=380px" title="adjust"><img src="../img/customers/edit-icon.png" /></a>
                <?php } ?>            </td>
    </tr>
    <?php } 
	else { ?>
    <tr>
      	<td><?php echo $ctr; ?></td>
      	<td>-</td>
   	  	<td>&nbsp;</td>
   	  	<td>&nbsp;</td>
   	  	<td>&nbsp;</td>
   	  	<td><?php echo "RM ".$get_l['balance']; ?></td>
   	  	<td style="color:#F00"><strong>CLEARED</strong></td>
  	  	<td>&nbsp;</td>
  	  	<td>&nbsp;</td>
  	  	<td>&nbsp;</td>
  	  	<td>&nbsp;</td>
  	  	<!--<td>&nbsp;</td>-->
        <td>&nbsp;</td>
   	</tr>
    <?php } ?>
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
   	  	<td></td>
    	<td></td>
        <td></td>
        <td></td>
        <td></td>
        <!--<td></td>-->
        <td></td>
    </tr>
    <?php }} ?>
    <tr>
    	<td colspan="13">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="13" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../payment/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="13">&nbsp;</td>
    </tr>
</table>
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
/*$('#next_paymentdate').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
*/
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

function payConfirm(period, month)
{
	$apaydate = document.getElementById('a_paymentdate').value;
	
	if($apaydate == 0)
	{
		$.confirm({
				'title'		: 'Payment Date Checking',
				'message'	:  'Please update the customer payment date first!',
				'buttons'	: {
					'Ok'	: {
					'class'	: 'blue',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});

	}
	else
	{
		$amount = document.getElementById('payment').value;
	
		$date = document.getElementById('payment_date').value;
			$int_amount = document.getElementById('payment_int').value;
		$nextdate = document.getElementById('next_paymentdate').value;
		$id = document.getElementById('id').value;
		$receipt = document.getElementById('new_receipt').value;
		$mr = document.getElementById('month_receipt').value;
		$newloan = document.getElementById('new_loan').value;
		
		$totamount = (($amount*1) + ($int_amount*1)).toFixed(2);
		
		$period = period;
		$currmonth = month;
			
		if(($amount != '' || $int_amount != '' ) && $date != '' && $mr != '' && $nextdate != '' && $receipt != '')
		{
			$.confirm({
				'title'		: 'Payment Confirmation',
				'message'	:  'Received RM ' + $totamount +' from <?php echo $get_cust['name']; ?>?',
				'buttons'	: {
					'Yes'	: {
					'class'	: 'blue',
					'action': function(){
						$.ajax({
								type: 'POST',
								data: {
									action: 'payloanf',
									amount: $amount,
									intamount: $int_amount,
									date: $date,
									id: $id,
									period: $period,
									month: $currmonth,
									nextdate: $nextdate,
									receipt: $receipt,
									monthreceipt: $mr,
									newloan: $newloan,
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
			$.confirm({
				'title'		: 'Payment Checking',
				'message'	:  'Please enter the payment amount, payment date, new receipt number, receipt month and next payment date!',
				'buttons'	: {
					'Ok'	: {
					'class'	: 'blue',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
			//alert("Please enter the payment amount, payment date and next payment date!");
		}
	}
}
function changePeriod(newP,oldP)
{
	$new_period = newP;
	$old_period = oldP;
	
	if($new_period != $old_period)
	{
		$('#save_period').css("display", "inline");
	}
	else
	{
		$('#save_period').css("display", "none");
	}
}

function updatePeriod(period, id)
{
	$newP = document.getElementById('period').value;
	$oldP = period;	
	$id = id;
	$.confirm({
		'title'		: 'Update Period Confirmation',
		'message'	:  'Update from ' + $oldP + ' months to ' + $newP + ' months?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_period',
							newP: $newP,
							oldP: $oldP,
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
Shadowbox.init();

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
function nextdate(m)
{
	$date1 = document.getElementById('a_paymentdate').value;
	$date = ($date1*1);
	if($date <= 9)
	{
		$date = "0"+$date;
	}
		 
	$tdd = document.getElementById('tdd').value;
	$npnewdate = document.getElementById('np_newdate').value;
	$monthnpnewdate1 = $npnewdate.substr(-7, 2);
	$monthnpnewdate = ($monthnpnewdate1 * 1);
		
	$month1 = m.substr(-2, 2);
	
	$month2 = ($month1 * 1)
	
	if($monthnpnewdate > $month2)
	{
		$month = $monthnpnewdate;
	}
	else
	{
		$month = $month2;
	}
	
	if($month <= 9)
	{
		$month = "0"+$month;
	}
	
	$year = m.substr(0, 4);
	
	if($month == '02')
	{
		if($date > '28')
		{
			$date = '28';
		}
	}
	
	if($month == '04')
	{
		if($date > 30)
		{
			$date = 30;
		}
	}
	if($month == '06')
	{
		if($date > 30)
		{
			$date = 30;
		}
	}
	if($month == '09')
	{
		if($date > 30)
		{
			$date = 30;
		}
	}
	if($month == '11')
	{
		if($date > 30)
		{
			$date = 30;
		}
	}
	
	
	$np = $date+"-"+$month+"-"+$year;

	document.getElementById('next_paymentdate').value = $np;
	
	
}
function noteConfirm(id)
{
	$remarks = document.getElementById('a_remarks').value;
	$id = id;
	$.confirm({
		'title'		: 'Update Notes Confirmation',
		'message'	:  'Are You sure want to change the notes?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_aremarks',
							remarks: $remarks,
							id: $id,
						},
						url: 'action_remarks.php',
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

</script>