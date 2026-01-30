<?php 

include("../include/dbconnection2.php");
$id = $_GET['id'];
$ic = $_GET['ic'];
$branch_id = $_GET['branch_id'];

$cust_q = mysql_query("SELECT * FROM customer_details2 WHERE nric = '".$ic."'",$db2);
$get_cust = mysql_fetch_assoc($cust_q);

$cust_a = mysql_query("SELECT * FROM customer_details WHERE nric = '".$ic."'",$db2);
$get_custa = mysql_fetch_assoc($cust_a);

$loandetails_q = mysql_query("SELECT * FROM loan_payment_details WHERE nric = '".$ic."' ORDER BY customer_loanid ",$db2);

$loandetails_r = mysql_query("SELECT * FROM  loan_payment_details WHERE nric = '".$ic."' AND month ='1' ",$db2);
$ldrow = mysql_fetch_assoc($loandetails_r);

?>

<style>
@media print{
	.no-print{
		display:none;
	}
	.page {
		page-break-after: always;
	
	}
	@page { 
		size: auto;
		margin: 0;
		
	}

}
input[type=text] { 
text-transform:uppercase;
 } 
textarea
{
	font-size:13px;
	text-transform:uppercase;
	resize:none;
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
.btn {
	text-transform: capitalize;
  font-family: Arial;
  color: #ffffff;
  height:30px;
  font-size: 13px;
  background: #ffaa00;
  padding: 7px 30px 7px 30px;
  text-decoration: none;
}

.btn:hover {
  background: #f7ff00;
  text-decoration: none;
}
</style>
<div class="no-print">
<div id="message">
<?php
if($_SESSION['msg'] != '')
{
	echo $_SESSION['msg'];
    $_SESSION['msg'] = '';
}
?>
</div>
&emsp;<button class="btn" onClick="print();">Print</button>
&emsp;<input type="button" name="back1" id="back1" value="Back" class="btn" onClick="window.location.href='../payment/index.php?branch_id=<?php echo $branch_id; ?>'" value="">
</div>
<center>
<br><br>
<form >
<table width="80%" id="" border="0" style="margin:10px 20px 10px 20px;">
	<tr>
    	<td colspan="6" style="padding:0px">Personal Payment Record - Flexi Loan</td>
    </tr>
	<tr><td>&nbsp;</td></tr>
    <tr>
   	  <td style="padding:0px">Customer Code :</td>
        <td><input type="text" name="customercode2" id="customercode2" value="<?php echo $get_custa['cust_id']; ?>" style="width:180px; border:none;" readonly /></td>
        <td style="padding:0px">Package :</td>
      <td><input type="text" name="loan_package" id="loan_package" style="width:180px; border:none;" value="<?php echo $ldrow['loan_package']; ?>"  readonly /></td>
      <td style="padding:0px">Bank :</td>
      <td><input type="text" name="bankacc" id="bankacc" value="<?php echo $get_custa['bankacc']; ?>" style="width:180px; border:none;" readonly /></td>
    </tr>
    <tr>
      <td style="padding:0px">Full Name :</td>
        <td><?php echo $get_cust['name']; ?></td>
      <td style="padding:0px">Loan Code :</td>
      <td><input type="text" name="customer_code" id="customer_code" style="width:180px; border:none;"  value="<?php echo $ldrow['customer_loanid']; ?>"  readonly  /></td>
      <td style="padding:0px">Pay Date :</td>
      <td>
      <input type="text" name="payday" id="payday" value="<?php if ($ldrow['payment_date'] == 0000-00-00){ echo "&nbsp;"; } else { $paymentdate = strtotime($ldrow['payment_date']);  echo date('d', $paymentdate); } ?>" style="width:180px; border:none;" readonly  /></td>
    </tr>
    <tr>
    	<td style="padding:0px">New I/C. No. :</td>
        <td><input type="text" name="nric" style="width:180px; border:none;" value="<?php echo $get_cust['nric']; ?>"   readonly /></td>
      <td style="padding:0px">Loan Amount (RM) :</td>
        <td><input type="text" name="loan_amount" style="width:180px; border:none;" value="<?php echo $ldrow['loan_amount']; ?>" readonly   />&nbsp;</td>
        <td style="padding:0px">Payment Date :</td>
        <td><input type="text" name="a_paymentdate" id="a_paymentdate" style="width:180px; border:none;" value="<?php  if ($ldrow['payment_date'] == 0000-00-00){ echo "&nbsp;"; } else {  echo date('d-m-Y', strtotime($ldrow['payment_date'])); } ?>" readonly  /></td>
    </tr> 
    <tr>
      <td style="padding:0px">Mobile Phone :</td>
      <td><input type="text" name="contact" style="width:180px; border:none;" value="<?php echo $get_custa['phone_no']; ?>"   readonly /></td>
      <td style="padding:0px">Interest Rate :</td>
      <td><input type="text" name="intrate" id="intrate" style="width:180px; border:none;" value="1.5%" readonly  /></td>
      <td style="padding:0px">Monthly Net Salary (RM) :</td>
      <td><input type="text" name="salary" style="width:180px; border:none;" value="<?php echo $get_custa['salary']; ?>"  readonly  /></td>
    </tr>
    <tr>
      <td style="padding:0px">Notes :
	  </td>
      <td rowspan="3"><textarea name="aremarks" id="aremarks" style="width:180px; height:90px; border:none;" readonly ><?php echo $get_custa['notes']; ?></textarea></td>
      <td style="padding:0px">Loan Period  :
        </td>
      <td><input type="text" name="period" id="period" value="<?php echo $ldrow['loan_period']; ?>" style="width:20px; border:none;" onkeyup="changePeriod(this.value,'<?php echo $get_q['loan_period']; ?>')"  readonly />
&nbsp;month(s) </td>
      <td style="padding:0px">Remarks :
	  
	  </td>
      <td rowspan="3"><textarea name="loanremarks" id="loanremarks" style="width:180px; height:90px; border:none;" readonly ><?php echo $get_custa['remark']; ?></textarea></td>
    </tr>
    <tr>
    	<td style="padding:0px">&nbsp;</td>
        <td style="padding:0px">Total Loan (RM) :</td>
        <td><input type="text" name="loan_total" style="width:180px; border:none;"value="<?php echo $ldrow['loan_total']; ?>" readonly   /></td>
        <td style="padding:0px">&nbsp;</td>
    </tr>   
	<tr>
		<td  style="padding:0px">&nbsp;</td>
		<td  style="padding:0px">Monthly Payment (RM) :</td>
		<td><input type="hidtextden" name="monthly_payment" id="monthly_payment" style="width:180px; border:none;" value="<?php echo $ldrow['monthly_payment']; ?>" readonly  /></td>
		<td  style="padding:0px">&nbsp;</td>
	</tr>  
	
    <tr>
   	  	<td colspan="6">
              &nbsp;	</td>
    </tr>
</table>

</form>
<table width="80%" border="0" >
	<tr>
	  	<th>Months</th>
	  	<th>Scheduled Date</th>
	  	<th>Payment Date</th>
	  	<th>Receipt No. </th>
	  	<th>Balance</th>
	  	<th>Payment Received</th>
    	
        
       
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
	<tr <?php echo $style; ?> align="center">
      	<td><?php echo $ctr; ?></td>
      	<td>
		<?php if($get_l['next_paymentdate'] == '0000-00-00') {  ?><input type="text" name="npd2" id="npd2"style="width:80px; border:none;" value="" readonly ><?php } else { ?>
			<input type="text" readonly="readonly" name="edit_npd_<?php echo $ctr; ?>" id="edit_npd_<?php echo $ctr; ?>" style="width:80px; border:none;" 
		value="<?php if($get_l['next_paymentdate'] != '0000-00-00') { echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); } ?>" /><?php } ?>
</td>
		
   	  	<td><?php if($get_l['payment_date'] == '0000-00-00'){ echo "&nbsp;"; } else {echo date('d-m-Y', strtotime($get_l['payment_date'])); } ?></td>
		
   	  	<td><?php echo $get_l['receipt_no']; ?></td>
   	  	
   	  	<td><?php echo $get_l['balance']; ?></td>
		
        <td><?php echo $get_l['monthly_payment']; ?></td>    
            
    </tr>
    
                
    <?php } 
	else { ?>
    <tr align="center">
      	<td><?php echo $ctr; ?></td>
      	<td>-</td>
   	  	<td>&nbsp;</td>
   	  	<td>&nbsp;</td>
   	  	<td><?php echo "RM ".$get_l['balance']; ?></td>
   	  	<td style="color:#F00"><strong>CLEARED</strong></td><!--<td>&nbsp;</td>-->
      
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
    	
        
    </tr>
    <?php }} ?>
    <tr>
    	<td colspan="13">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="13" align="right"></td>
    </tr>
    <tr>
    	<td colspan="13">&nbsp;</td>
    </tr>
</table>
<input type="hidden" name="rowcount" id="rowcount" value="<?php echo $ctr; ?>" />
</center>
<script>

	//$a=1;
function calculateInt()
{

	//$package = document.getElementById('loan_package').value;
	$loan = document.getElementById('loan_amount').value;
	$int = document.getElementById('intrate').value;
	$month = document.getElementById('period').value;
	

			var $loan_inttotal = (($int/100) * $loan) * $month;
			$loan_total = +$loan_inttotal+ +$loan;
			$monthly = $loan_total/$month
		/*}
	}else
	{
		$loan_inttotal = (($int/100) * $loan);
	}
	if($loan_inttotal != 0)
	{*/

		document.getElementById('loan_total).value = $loan_inttotal.toFixed(2);
		//document.getElementById('jumlah_besar').value = $loan_total.toFixed(2);
		//document.getElementById('balance').value = $loan_total.toFixed(2);
		document.getElementById('monthly').value = $monthly.toFixed(2);
		//$a++;
	/* } */
	
}
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
		
		$receiptmonth = $mr.substr(5, 2);
		$newrm = $receipt.substr(0, 2);
		
		$loanp = document.getElementById('loan_package').value;
		
		if($loanp == 'SKIM S')
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
		
		$totamount = (($amount*1) + ($int_amount*1)).toFixed(2);
		
		$period = period;
		$currmonth = month;
		$balance = balance;
		$pamount = document.getElementById('payment').value
			
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