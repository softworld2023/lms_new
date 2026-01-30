<?php 
include('../include/page_header2.php'); 

//include("../include/dbconnection.php");
include("../include/dbconnection2.php");
$id = $_GET['id'];
$ic = $_GET['ic'];
$branch_id = $_GET['branch_id'];

$cust_q = mysql_query("SELECT * FROM customer_details2 WHERE id = '".$id."'",$db2);
$get_cust = mysql_fetch_assoc($cust_q);

$loandetails_q = mysql_query("SELECT * FROM loan_payment_details WHERE nric = '".$ic."' ");

$loandetails_r = mysql_query("SELECT * FROM loan_payment_details WHERE nric = '".$ic."' ");
$ldrow = mysql_fetch_assoc($loandetails_r);

/*$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);

$add_q = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$get_q['customer_id']."'");
$get_add = mysql_fetch_assoc($add_q);



$company_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$get_q['customer_id']."'");
$get_c = mysql_fetch_assoc($company_q);

$salary_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$get_q['customer_id']."'");
$get_s = mysql_fetch_assoc($salary_q);

$acc_q = mysql_query("SELECT * FROM customer_account WHERE customer_id = '".$get_q['customer_id']."'");
$getacc = mysql_fetch_assoc($acc_q);

//int rate
$intrate_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."'");
$intrate = mysql_fetch_assoc($intrate_q);
*/
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
    	<td colspan="6" style="padding:0px"><h3>Personal Payment Record</h3></td>
    </tr>
    <tr>
   	  <td style="padding:0px">Customer Code </td>
        <td><input type="text" name="customercode2" id="customercode2" value="<?php echo $get_cust['customercode2']; ?>" style="width:100px" /></td>
        <td style="padding:0px">Package</td>
      <td><input type="text" name="loan_package" id="loan_package" value="<?php echo $get_q['loan_package']; ?>" readonly="readonly" /></td>
      <td style="padding:0px">Bank</td>
      <td><input type="text" name="bankacc" id="bankacc" value="<?php echo $getacc['a_bankname']; ?>" style="width:230px" /></td>
    </tr>
    <tr>
      <td style="padding:0px">Full Name</td>
        <td><input type="text" name="name" value="<?php echo $get_cust['name']; ?>" style="width:230px" readonly="readonly" /></td>
      
      <td style="padding:0px">Pay Date</td>
      <td><div id="save_period" style="display:none"><a href="javascript:updatePeriod('<?php echo $get_q['loan_period'] ?>', '<?php echo $id; ?>')"><input type="button" name="saveperiod" id="saveperiod" value="" /></a></div>
      <input type="text" name="payday" id="payday" value="<?php echo $getacc['a_payday']; ?>" style="width:230px" /></td>
    </tr>
    <tr>
    	<td style="padding:0px">New I/C. No.</td>
        <td><input type="text" name="nric" value="<?php echo $get_cust['nric']; ?>" readonly="readonly" /></td>
      <td style="padding:0px">Loan Amount (RM) </td>
        <td><input type="text" name="loan_amount" value="<?php echo "RM ".number_format($get_q['loan_amount'], '2'); ?>" readonly="readonly" />&nbsp;</td>
        <td style="padding:0px">Payment Date </td>
        <td><input type="text" name="a_paymentdate" id="a_paymentdate" value="<?php echo $getacc['a_paymentdate']; ?>" /></td>
    </tr> 
    <tr>
      <td style="padding:0px">Mobile Phone </td>
      <td><input type="text" name="contact" value="<?php echo $get_add['mobile_contact']; ?>" readonly="readonly" /></td>
      <td style="padding:0px">Interest Rate 
        <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')) { ?>
        <a href="javascript:intrateConfirm('<?php echo $id; ?>')" title="save interest rate"><img src="../img/document_save.png" /></a>
        <?php } ?></td>
      <td><input type="text" name="intrate" id="intrate" style="width:50px" value="1.50 %" /></td>
      <td style="padding:0px">Monthly Net Salary </td>
      <td><input type="text" name="salary" value="<?php echo "RM ".number_format($get_s['net_salary'], '2'); ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td style="padding:0px">Notes <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')) { ?>
	   <a href="javascript:noteConfirm2('<?php echo $getacc['id']; ?>')" title="save notes"><img src="../img/document_save.png" /></a>
	  <?php } else { ?>
	  <a href="javascript:noteConfirm('<?php echo $getacc['id']; ?>')" title="save notes"><img src="../img/document_save.png" /></a>
	  <?php } ?>
	  </td>
      <td rowspan="3"><textarea name="a_remarks" id="a_remarks" style="width:230px; height:90px"><?php echo $getacc['a_remarks']; ?>
	  <?php if($getacc['update_date'] != '0000-00-00') { ?>
				Last Update By:  <?php echo $getacc['update_byname']; ?>
				&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php if($getacc['update_date'] != '0000-00-00') { echo "On: ".date('d-m-Y', strtotime($getacc['update_date'])); } ?>
				
				<?php } ?>
	  </textarea></td>
      <td style="padding:0px">Loan Period 
        <?php if($_SESSION['login_level'] == 'Boss' && $_SESSION['login_username'] == 'softworld') { ?>
        <a href="javascript:periodConfirm('<?php echo $id; ?>')" title="save loan period"><img src="../img/document_save.png" /></a>
        <?php } ?></td>
      <td><input type="text" name="period" id="period" value="<?php echo $ldrow['loan_period']; ?>" style="width:50px" onkeyup="changePeriod(this.value,'<?php echo $get_q['loan_period']; ?>')" />
months </td>
      <td style="padding:0px"Remarks
	  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')) { ?>
	  	<a href="javascript:remarksConfirm2('<?php echo $id; ?>')" title="save remarks"><img src="../img/document_save.png" /></a>
	  <?php } else { ?>
		<a href="javascript:remarksConfirm('<?php echo $id; ?>')" title="save remarks"><img src="../img/document_save.png" /></a>
	  <?php } ?>
	  
	  </td>
      <td rowspan="3"><textarea name="loanremarks" id="loanremarks" style="width:230px; height:90px"><?php echo $get_q['loan_remarks']; ?></textarea></td>
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
		<td><input type="text" name="tdd" id="tdd" value="<?php echo $ldrow['monthly_amount'];  ?>" /></td>
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
	  	<th>Loan Balance</th>
	  	<th>Payment Received </th>
    
       
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

	?>
    <?php if($get_l['total_amount'] != 0) {?>
	<tr <?php echo $style; ?>>
      	<td><?php echo $ctr; ?></td>
      	<td><?php if($_SESSION['login_username'] != 'softworld' && $_SESSION['login_username'] != 'fong') { echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); } else { ?><input type="text" name="edit_npd_<?php echo $ctr; ?>" id="edit_npd_<?php echo $ctr; ?>" style="width:80px" value="<?php if($get_l['next_paymentdate'] != '0000-00-00') { echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); } ?>" /><a href="javascript:edit_date('<?php echo $get_l['id']; ?>', '<?php echo $ctr; ?>')" title="save schedule date"><img src="../img/document_save.png" /></a><?php } ?></td>
   	  	<td><?php if($get_l['payment_date'] == '0000-00-00') {  ?><input type="hidden" name="schedule" id="schedule" value="<?php echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); ?>" />
            <?php if($style == '') {?>
            <input type="text" name="payment_date" id="payment_date" style="width:80px" value="<?php echo date('d-m-Y'); ?>" />
            <?php } else { ?>
            <?php //if($latectr == $latecount) { ?>
            <input type="text" name="payment_date" id="payment_date" style="width:80px" value="<?php echo date('d-m-Y'); ?>" />
            <?php //} else { ?>
            <?php //} ?>
            <?php } ?>
            <?php } else { echo date('d-m-Y', strtotime($get_l['payment_date'])); } ?>        </td>
   	  	<td><?php if($get_l['receipt_no'] == ''){ ?>
			<input type="text" name="receipt_no" id="receipt_no" size="10" value="">
	<?php	}
	else{
		echo $get_l['receipt_no'];
		}
		?>
		
	</td>
   	  	<td><?php if($get_l['month_receipt'] == ''){ ?>
			<input type="text" name="month_receipt" id="month_receipt"  onfocus="nextdate(this.value)" size="10" value="">
	<?php	}
		else {
		echo $get_l['month_receipt']; 
		}
		?></td>
		
		
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
		
            <!--<td></td>-->
            <td style="padding:0px">
                <?php if($get_l['payment'] == '0' && $get_l['payment_date'] == '0000-00-00') { ?><input type="hidden" name="prm" id="prm" value="<?php echo $get_l['month_receipt']; ?>" />
				<input type="hidden" name="orisched" id="orisched" style="width:80px" value="<?php if($get_l['next_paymentdate'] != '0000-00-00') { echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); } ?>" />
                <a href="javascript:payConfirm('<?php echo $get_l['loan_period']; ?>', '<?php echo $get_l['month']; ?>','<?php echo $get_l['balance']; ?>')" 
				title="pay"><img src="../img/payment-received/payment-received.png" width="30" /></a>&nbsp;&nbsp;&nbsp;
				<a href="adjustflexi.php?id=<?php echo $get_l['id']; ?>&p=<?php echo $get_q['loan_period']; ?>" rel="shadowbox; width=800px; height=380px" title="adjust">
				<img src="../img/customers/edit-icon.png" /></a>
                
                <?php 
						} 
					?>
            <?php 
        		// }
        		// else 
        		// { 
        	?>
					<?php 
						if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')) 
						{ 
					?>
						<?php 
							$checkrow_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$get_l['id']."' AND customer_loanid = '".$id."'"); 
							$checkrow = mysql_num_rows($checkrow_q);
							if($checkrow == 1)
							{
						?>			            
								<a href="javascript:deletepayConfirm('<?php echo $id; ?>', '<?php echo $get_l['id']; ?>', '<?php echo date('d-m-Y', strtotime($get_l['payment_date'])); ?>','<?php echo $get_q['loan_total']; ?>')" title="delete">
								<img src="../img/delete-btn.png" width="31" height="25" />
								</a>
						<?php 
							} //end of $chekcrow 
						?>				  		
					<?php 
						} 
					?>
			<?php 
				// } 
			?>
			</td>
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
<input type="hidden" name="rowcount" id="rowcount" value="<?php echo $ctr; ?>" />
</center>
<script>
$c = document.getElementById('rowcount').value;

for($i = 1; $i <= $c; $i++)
{
	$('#edit_npd_'+$i).click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

}
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

function payConfirm(period, month, balance)
{
	$apaydate = document.getElementById('a_paymentdate').value;
	
	/*if($apaydate == 0)
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
	else*/
	{
		$amount = document.getElementById('payment').value;
	$total_amount = document.getElementById('total_amount').value;
		$date = document.getElementById('payment_date').value;
		//$int_amount = document.getElementById('payment_int').value;
		$nextdate = document.getElementById('next_paymentdate').value;
		$id = document.getElementById('id').value;
		//$receipt = document.getElementById('new_receipt').value;
		//$mr = document.getElementById('month_receipt').value;
		//$newloan = document.getElementById('new_loan').value;
		
		//$receiptmonth = $mr.substr(5, 2);
		//$newrm = $receipt.substr(0, 2);
		
		//$loanp = document.getElementById('loan_package').value;
		
		/*if($loanp == 'SKIM S')
		{
			if($receiptmonth != $newrm)
			{
				$.confirm({
					'title'		: 'Receipt Checking',
					'message'	:  'Receipt number not belongs to the receipt month. Please check again!',
					'buttons'	: {
						'Ok'	: {
						'class'	: 'blue',
						'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
						}
					}
				});
			}
		}
		*/
		$totamount = (($amount*1) + ($int_amount*1)).toFixed(2);
		
		$period = period;
		$currmonth = month;
		$balance = balance;
		$pamount = document.getElementById('payment').value
			
		if($amount != '' )
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
									//monthreceipt: $mr,
									//newloan: $newloan,
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
				'message'	:  'Please enter the accurate payment amount, payment date, new receipt number, receipt month and next payment date!',
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

function checkReceipt(str, loanid)
{
	$rno = document.getElementById('new_receipt').value;
	
	if ($rno.length==0)
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
	  
	xmlhttp.open("GET","checkReceipt2.php?code="+escape($rno)+"&loanid="+escape(loanid)+"&month="+escape(str),true);
	xmlhttp.send();
}

function checkReceipt2(str, loanid)
{
	$month = document.getElementById('month_receipt').value;
	
	if ($rno.length==0)
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
	  
	xmlhttp.open("GET","checkReceipt2.php?code="+escape(str)+"&loanid="+escape(loanid)+"&month="+escape($month),true);
	xmlhttp.send();
}
function nextdate(m)
{
	$precm = document.getElementById('prm').value;
	
	if(m != $precm )
	{
		$date1 = document.getElementById('a_paymentdate').value;
		$date = ($date1*1);
		$schedule = document.getElementById('schedule').value;
		if($date <= 9)
		{
			$date = "0"+$date;
		}
		
		$pnpdate = document.getElementById('pnpdate').value;
		
		$prm = document.getElementById('prm').value;
		$nprm = m.substr(-6, 2);
		
			 
		$tdd = document.getElementById('tdd').value;
		$npnewdate = document.getElementById('np_newdate').value;
		$monthnpnewdate1 = $npnewdate.substr(-7, 2);
		$monthnpnewdate = ($monthnpnewdate1 * 1);
		$month1 = m.substr(-2, 2);
		
		$month2 = ($month1 * 1);
		
		if($prm != m)
		{
		
			if($pnpdate == $nprm)
			{
				$month2 = $month2 + 1;
				
			}
		}
			
		
		
		/*if($monthnpnewdate > $month2)
		{
			$month = $monthnpnewdate;
		}
		else
		{*/
			$month = $month2;
		//}
		
		
		
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
		
		if($np == $schedule)
		{
			
			$month = ($month*1) + 1;
			if($month <= 9)
			{
				$month = "0"+$month;
			}
			
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
		}
	}else
	{
		$np = document.getElementById('orisched').value;
	}

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
function edit_date(id, ctr)
{
	$id = id;
	$ctr = ctr;
	$editdate = document.getElementById('edit_npd_'+$ctr).value;
	
	$.ajax({
			type: 'POST',
			data: {
				action: 'update_npd',
				date: $editdate,
				id: $id
			},
			url: 'action_editdate.php',
			success: function(){
				location.reload();
			}
		})	
}

function remarksConfirm(id)
{
	$remarks = document.getElementById('loanremarks').value;
	$id = id;
	$.confirm({
		'title'		: 'Update Remarks Confirmation',
		'message'	:  'Are You sure want to change the loan remarks?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_remarks',
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
function remarksConfirm2(id)
{
	$remarks = document.getElementById('loanremarks').value;
	$id = id;
	$.confirm({
		'title'		: 'Update Remarks Confirmation',
		'message'	:  'Are You sure want to change the loan remarks?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_remarks_boss',
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

function intrateConfirm(id)
{
	$intrate = document.getElementById('intrate').value;
	$id = id;
	$.confirm({
		'title'		: 'Update Interest Rate Confirmation',
		'message'	:  'Are You sure want to change the loan interest rate?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_intrate',
							intrate: $intrate,
							id: $id,
						},
						url: 'action_update.php',
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

function periodConfirm(id)
{
	$period = document.getElementById('period').value;
	$id = id;
	$.confirm({
		'title'		: 'Update Loan Period Confirmation',
		'message'	:  'Are You sure want to change the loan period?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_period',
							period: $period,
							id: $id,
						},
						url: 'action_update.php',
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

function noteConfirm2(id)
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
						url: 'action_update.php',
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
function deletepayConfirm(loanid, id, date,amount,package_id)
{
	$id = id;
	$loanid = loanid;
	$amount = amount;
	$package_id = package_id
	$.confirm({
		'title'		: 'Delete Payment Confirmation',
		'message'	:  'Are You sure want to delete the payment made on ' + date +'?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_payfixed3',
							loanid: $loanid,
							id: $id,
							amount: $amount,
							package_id: $package_id,
						},
						url: 'action_update.php',
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