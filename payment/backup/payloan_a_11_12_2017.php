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
    	<td colspan="6" style="padding:0px">Personal Payment Record - Fixed Loan</td>
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
      <td><input type="text" name="customer_code" id="customer_code" readonly="readonly" value="<?php echo $get_q['loan_code']; ?>" /></td>
      <td style="padding:0px">Pay Date</td>
      <td>
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
      <td style="padding:0px">Interest Rate 
	  	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) { ?>
		<a href="javascript:intrateConfirm('<?php echo $id; ?>')" title="save interest rate"><img src="../img/document_save.png" /></a>
		<?php } ?>	  </td>
      <td><input type="text" name="intrate" id="intrate" style="width:40px" value="<?php echo $intrate['int_percent']; ?>%" /></td>
      <td style="padding:0px">Monthly Net Salary </td>
      <td><input type="text" name="salary" value="<?php echo "RM ".number_format($get_s['net_salary'], '2'); ?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td style="padding:0px"><span style="padding:0px; padding-top:5px">Notes <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) { ?>
	   <a href="javascript:noteConfirm2('<?php echo $getacc['id']; ?>')" title="save notes"><img src="../img/document_save.png" /></a>
	  <?php } else { ?>
	  <a href="javascript:noteConfirm2('<?php echo $getacc['id']; ?>')" title="save notes"><img src="../img/document_save.png" /></a>
	  <?php } ?>
	  </span></td>
      <td rowspan="3"><textarea name="a_remarks" id="a_remarks" style="width:230px; height:90px"><?php echo $getacc['a_remarks']; ?>
	   <?php if($getacc['update_date'] != '0000-00-00') { ?>
				Last Update By:  <?php echo $getacc['update_byname']; ?>
				&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php if($getacc['update_date'] != '0000-00-00') { echo "On: ".date('d-m-Y', strtotime($getacc['update_date'])); } ?>
				
				<?php } ?>
	  </textarea></td>
      <td style="padding:0px">Loan Period 
	  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) { ?>
		<a href="javascript:periodConfirm('<?php echo $id; ?>')" title="save loan period"><img src="../img/document_save.png" /></a>
		<?php } ?>	  </td>
      <td><input type="text" name="period" id="period" value="<?php echo $get_q['loan_period']; ?>" style="width:50px" onkeyup="changePeriod(this.value,'<?php echo $get_q['loan_period']; ?>')" /> 
      months </td>
      <td style="padding:0px">Remarks
	  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) { ?>
	  	<a href="javascript:remarksConfirm2('<?php echo $id; ?>')" title="save remarks"><img src="../img/document_save.png" /></a>
	  <?php } else { ?>
		<a href="javascript:remarksConfirm('<?php echo $id; ?>')" title="save remarks"><img src="../img/document_save.png" /></a>
	  <?php } ?>
	  </td>
      <td rowspan="3"><textarea name="loanremarks" id="loanremarks" style="width:230px; height:90px"><?php echo $get_q['loan_remarks']; ?></textarea></td>
    </tr>
    <tr>
    	<td style="padding:0px">&nbsp;</td>
        <td style="padding:0px">Total Loan (RM)		</td>
        <td><input type="text" name="loan_total" value="<?php echo "RM ".number_format($get_q['loan_total'], '2'); ?>" readonly="readonly" /></td>
        <td style="padding:0px">&nbsp;</td>
    </tr>   
	<tr>
		<td  style="padding:0px">&nbsp;</td>
		<td  style="padding:0px">Monthly Payment (RM) </td>
		<td><input type="text" id="monthlyamt" name="monthlyamt" readonly="readonly" value="RM <?php echo number_format($intrate['monthly'], '2'); ?>" /></td>
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
	  	<th>Receipt No </th>
	  	<th>Payment Received</th>
	  	<th>Balance</th>
	  	<!--<th>Next Payment Date</th>-->
        <th width="90">&nbsp;</th>
		
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
    <?php if($get_l['balance'] != 0) {?>
    <tr <?php echo $style; ?>>
      	<td><?php echo $ctr; ?></td>
		<!-- schedule date -->
      	<td><?php if($_SESSION['login_username'] != 'softworld' && $_SESSION['login_username'] != 'fong' && $_SESSION['login_username'] != 'staff') 
{ echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); } else { ?><input type="text" name="edit_npd_<?php echo $ctr; ?>" id="edit_npd_<?php echo $ctr; ?>" style="width:80px" value="<?php if($get_l['next_paymentdate'] != '0000-00-00') { echo date('d-m-Y', strtotime($get_l['next_paymentdate'])); } ?>" /><a href="javascript:edit_date('<?php echo $get_l['id']; ?>', '<?php echo $ctr; ?>')" title="save schedule date"><img src="../img/document_save.png" /></a><?php } ?></td>
		<!---payment date-->
   	  	<td><?php if($get_l['payment_date'] == '0000-00-00') {  ?>
            <?php if($style == '') {?><input type="hidden" name="tdy" id="tdy" value="<?php echo date('Y', strtotime($get_l['next_paymentdate'])); ?>" /><input type="hidden" name="tdm" id="tdm" value="<?php echo date('m', strtotime($get_l['next_paymentdate'])); ?>" />
            <input type="text" onblur="changeM(this.value)" name="payment_date" id="payment_date" style="width:130px" value="<?php echo date('d-m-Y'); ?>" />
            <?php } else { ?><input type="hidden" name="tdy" id="tdy" value="<?php echo date('Y'); ?>" /><input type="hidden" name="tdm" id="tdm" value="<?php echo date('m'); ?>" />
            <?php //if($latectr == $latecount) { ?>
            <input type="text"  onblur="changeM(this.value)" name="payment_date" id="payment_date" style="width:130px" value="<?php echo date('d-m-Y'); ?>" />
            <?php // } else { ?>
            <?php // } ?>
            <?php } ?>
            <?php } else { if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) 
			{ ?><input type="text" name="payment_date_<?php echo $ctr; ?>" id="payment_date_<?php echo $ctr; ?>" style="width:80px" value="<?php echo date('d-m-Y', strtotime($get_l['payment_date']));  ?>">   <a href="javascript:edit_pdate('<?php echo $get_l['id']; ?>', '<?php echo $ctr; ?>')" title="save payment date"><img src="../img/document_save.png" /></a>  <?php }else{ echo $get_l['payment_date'];}} ?>       </td>
		<!--receipt no-->
   	  	<td><?php echo $get_l['receipt_no']; ?><input type="hidden" name="prev_receipt" id="prev_receipt" value="<?php echo $get_l['receipt_no'] ?>" />
   	  	  <?php if($get_l['payment_date'] == '0000-00-00') {  ?>
          <?php if($style == '') {?>
          <input type="hidden" name="new_receipt" id="new_receipt" style="width:100px" value="<?php echo $get_q['loan_code']; ?>" autocomplete="off"/>
          <?php } else { ?>
          <?php //if($latectr == $latecount) { ?>
          <input type="hidden" name="new_receipt" id="new_receipt" style="width:100px" value="<?php echo $get_q['loan_code']; ?>" autocomplete="off"/>
          <?php //} else { ?>
          <?php //} ?>
          <?php } ?>
          <?php } ?></td>
		  
		  <!--payment received -->
   	  	<td>
		<?php
			$paymonth = date('Y-m', strtotime($get_l['next_paymentdate']));
			
			// $prevpay_q = mysql_query("SELECT * FROM loan_payment_details WHERE month = '".$prevmonth."' AND customer_loanid = '".$id."' AND next_paymentdate LIKE '%".$paymonth."%'");
			$prevpay_q = mysql_query("SELECT SUM(payment) AS total_pay FROM loan_payment_details WHERE customer_loanid = '".$id."' AND next_paymentdate LIKE '%".$paymonth."%'");			
			$prevpay = mysql_fetch_assoc($prevpay_q);
			$mbal = $get_l['monthly'] - $prevpay['total_pay'];
			
			if($mbal == 0)
			{
				$mbal = $get_l['monthly'];  
			}?><?php 
			
		if($get_l['payment'] == '0' && $get_l['payment_date'] == '0000-00-00') 
		{  ?>
			<input type="hidden" name="monthly" id="monthly" value="<?php echo number_format($mbal, '0', '.', ''); ?>" /><?php //payment for this month should be
			if($style == '') 
			{?>
				<input type="hidden" name="id" id="id" value="<?php echo $get_l['id']; ?>" />
				<input type="text" name="payment" id="payment" class="currency" style="width:130px" placeholder="RM <?php echo $get_l['monthly']; ?>" onkeyup="monthlyCheck(this.value)" autocomplete="off" /><?php 
			}
			else 
			{ ?> <?php // if($latectr == $latecount) { ?>
				<input type="hidden" name="id" id="id" value="<?php echo $get_l['id']; ?>" />
				<input type="text" name="payment" id="payment" class="currency" style="width:130px" placeholder="RM <?php echo $get_l['monthly']; ?>" autocomplete="off" onkeyup="monthlyCheck1(this.value)" /> <?php // } else { ?><?php // } ?> <?php 
			} ?>
            <?php 
		} 
		else 
		{
			echo "RM ".number_format($get_l['payment'], '2'); 
		} ?>        
		 </td>
		 
		<!-- balance -->
   	  	<td><?php $nbal = $get_l['balance'] - $get_l['payment']; echo "RM ".number_format($nbal, '2'); ?>
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
			
			$npdate1 = date('Y-m-d', strtotime($np_newdate));
			
			if($npdate1 < $get_l['next_paymentdate'])
			{
				$np_newdate = date('d-m-Y', strtotime($get_l['next_paymentdate']));
			}
			
			?>
		
		<?php if($get_l['payment_date'] == '0000-00-00') {  ?>
			<?php if($style == '') {?>
			<input type="hidden" name="oripd" id="oripd" value="<?php echo $np_newdate; ?>" />
			<input type="hidden" name="next_paymentdate" id="next_paymentdate" style="width:130px" value="<?php echo $get_l['next_paymentdate']; ?>" readonly="readonly" />
			<?php } else { ?>
			<input type="hidden" name="oripd" id="oripd" value="<?php echo date('Y-m-d', strtotime('+1 months', strtotime($get_l['next_paymentdate']))); ?>" />
			<?php if($latectr == $latecount) { ?>
			<input type="hidden" name="next_paymentdate" id="next_paymentdate" style="width:130px" value="<?php echo $get_l['next_paymentdate']; ?>" readonly="readonly" />
			<input type="hidden" name="nextpydt" id="nextpydt" style="width:130px" value="<?php echo $get_l['next_paymentdate']; ?>" readonly="readonly" />
			<?php } else { 
				$nxtm = $get_l['month'] + 1;
				$nxtd_q = mysql_query("SELECT * FROM loan_payment_details WHERE month = '".$nxtm."' AND customer_loanid = '".$get_l['customer_loanid']."'");
				$nxtd = mysql_fetch_assoc($nxtd_q);
			?>
			<input type="hidden" name="next_paymentdate" id="next_paymentdate" style="width:130px" value="<?php echo date('d-m-Y', strtotime($nxtd['next_paymentdate'])); ?>" readonly="readonly" />
			<?php } ?>
			<?php } ?>
			<?php } else { $new_month = $get_l['month'] + 1; 
                $npd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND month = '".$new_month."'");	
                $get_npd = mysql_fetch_assoc($npd_q);
                
			  } ?>		
		</td>
        <td><?php 
			if($get_l['payment'] == '0' && $get_l['payment_date'] == '0000-00-00' ) 
			{ ?>
				<a href="javascript:payConfirm('<?php echo $get_q['loan_period']; ?>', '<?php echo $get_l['month']; ?>')" title="pay"><img src="../img/payment-received/payment-received.png" width="30" /></a>&nbsp;&nbsp;&nbsp;<?php 
				if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) 
				{					
					if($style == '') 
					{?>
						<a href="adjust.php?id=<?php echo $get_l['id']; ?>&p=<?php echo $get_q['loan_period']; ?>" rel="shadowbox; width=800px; height=380px" title="adjust"><img src="../img/customers/edit-icon.png" /></a><?php 
					}
					else 
					{ ?>
						<a href="adjustlate.php?id=<?php echo $get_l['id']; ?>&p=<?php echo $get_q['loan_period']; ?>" rel="shadowbox; width=800px; height=380px" title="adjust"><img src="../img/customers/edit-icon.png" /></a><?php 
					} ?><?php 
				}
			}
			else 
			{ ?><?php 
				if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) 
				{ ?><?php 
					$checkrow_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$get_l['id']."' AND customer_loanid = '".$id."'"); 
					$checkrow = mysql_num_rows($checkrow_q);
			
					if($checkrow == 1)
					{?>
						<a href="javascript:deletepayConfirm('<?php echo $id; ?>', '<?php echo $get_l['id']; ?>', '<?php echo date('d-m-Y', strtotime($get_l['payment_date'])); ?>')" title="pay">
						<img src="../img/delete-btn.png" width="31" height="25" /></a>
			<?php } //end of $chekcrow ?>
			<?php } ?>
			<?php } ?>
			</td>
    </tr>
    <?php } 
	else { ?>
    <tr>
      	<td><?php echo $ctr; ?></td>
      	<td>-</td>
   	  	<td>&nbsp;</td>
   	  	<td style="color:#F00">&nbsp;<strong>CLEARED</strong></td>
   	  	<td></td>
   	  	<td><?php echo "RM ".$get_l['balance']; ?></td>
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
   	  	<!--<td></td>-->
        <td></td>
    </tr>
    <?php }} ?>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../payment/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
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
		$nextdate = document.getElementById('next_paymentdate').value;
		$id = document.getElementById('id').value;
		$receipt = document.getElementById('new_receipt').value;
		$prev_receipt = document.getElementById('prev_receipt').value;
	
		$period = period;
		$currmonth = month;
		
		if($amount != '' && $date != '' && $nextdate != '' && $receipt != '')
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
									action: 'payloan',
									amount: $amount,
									date: $date,
									id: $id,
									period: $period,
									month: $currmonth,
									nextdate: $nextdate,
									receipt: $receipt,
									prev_receipt: $prev_receipt,
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
				'message'	:  'Please enter the payment amount, payment date, new receipt number and next payment date!',
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

function monthlyCheck(a)
{
	$monthly1 = document.getElementById('monthly').value;
	$monthly = $monthly1 * 1;
	$m1 = document.getElementById('tdm').value;
	$y1 = document.getElementById('tdy').value;
	$d = document.getElementById('a_paymentdate').value;
	$a = a;
	$m = $m1 * 1;
	$y = $y1 * 1;
	
	if($a > $monthly || $a == $monthly)
	{	

		$m = $m + 1;		
		
		if($m == 2)
		{
			if($d > 28)
			{
				$d = 28;
			}
			$m = '0'+$m;
		}
		if($m == 3)
		{
			$m = '0'+$m;
		}
		if($m == 4)
		{
			if($d > 30)
			{
				$d = 30;
			}
			$m = '0'+$m;
		}
		if($m == 5)
		{
			$m = '0'+$m;
		}
		if($m == 6)
		{
			if($d > 30)
			{
				$d = 30;
			}
			$m = '0'+$m;
		}
		if($m == 7)
		{
			$m = '0'+$m;
		}
		if($m == 8)
		{
			$m = '0'+$m;
		}
		if($m == 9)
		{
			if($d > 30)
			{
				$d = 30;
			}
			$m = '0'+$m;
		}
		if($m == 13)
		{
			$m = '01';
			$y = $y+1;
		}
		
		
		document.getElementById('next_paymentdate').value = $d+"-"+$m+"-"+$y;
	}else
	{
		$oripd = document.getElementById('oripd').value;
		document.getElementById('next_paymentdate').value = $oripd;
	}
	
}

function monthlyCheck1(a)
{
	$monthly1 = document.getElementById('monthly').value;
	$monthly = $monthly1 * 1;
	$m1 = document.getElementById('tdm').value;
	$y1 = document.getElementById('tdy').value;
	$d = document.getElementById('a_paymentdate').value;
	$a = a;
	$m = $m1 * 1;
	$y = $y1 * 1;
	
	if($a > $monthly || $a == $monthly)
	{	
		$oripd = document.getElementById('oripd').value;
		document.getElementById('next_paymentdate').value = $oripd;
	}else
	{
		$oripd = document.getElementById('nextpydt').value;
		document.getElementById('next_paymentdate').value = $oripd;
	}
	
}

function changeM(m)
{
	$m = m.substring(5, 3);
	$y = m.substring(10, 6);
 
	document.getElementById('tdm').value = $m;
	document.getElementById('tdy').value = $y;
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
function edit_pdate(id, ctr)
{
	$id = id;
	$ctr = ctr;
	$paydate = document.getElementById('payment_date_'+$ctr).value;
	
	$.ajax({
			type: 'POST',
			data: {
				action: 'update_pd',
				date: $paydate,
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
							action: 'update_intrateFixed',
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

function deletepayConfirm(loanid, id, date)
{
	$id = id;
	$loanid = loanid;
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
							action: 'delete_payfixed',
							loanid: $loanid,
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
</script>