<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$sql = mysql_query("SELECT * FROM late_interest_record WHERE id = '".$id."'");
// var_dump("SELECT * FROM late_interest_record WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);

$balance = 0;
$balance_without_instalment = 0;
$previous_monthly_bd = 0;
$envelope = 0;
$envelope_without_instalment = 0;
$deductible = 0;
$previous_months_bd_collected = 0;
							
$query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_amount, SUM(balance) AS previous_monthly_bd FROM monthly_payment_record WHERE loan_code = '" . $get_q['loan_code'] . "' AND status = 'BAD DEBT'");
$result_monthly_payment_record = mysql_fetch_assoc($query);

$previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
$previous_monthly_amount = $result_monthly_payment_record['previous_monthly_amount'];

$amount = $get_q['amount'];
// var_dump($amount);

if ($get_q['bd_from'] == 'Monthly') {
	if ($get_q['loan_code'] != 'YW1210') {
		$amount = $previous_monthly_amount - $previous_monthly_bd;
	}
}
// var_dump($amount);

$query = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '" . $get_q['loan_code'] . "'");
$result_late_interest_record = mysql_fetch_assoc($query);

$late_interest_record_id = $result_late_interest_record['id'];
$bd_from = $result_late_interest_record['bd_from'];
$branch_name = strtolower($result_late_interest_record['branch_name']);

if ($get_q['loan_code'] == 'KT 20032') {
	$previous_monthly_bd = 680;
}

// somehow this check is required to identify whether need to deduct previous monthly bd from amount,
// because some old records have mixed up the amount and monthly bd
if ($branch_name == 'majusama' && $bd_from == 'Instalment + Monthly' && $late_interest_record_id > 46) {
	$amount -= $previous_monthly_bd;
} else if ($branch_name != 'majusama' && ($bd_from == 'Instalment + Monthly' || $bd_from == 'Monthly')) {
	if ($get_q['loan_code'] != 'YW1210') {
		$amount -= $previous_monthly_bd;
	}
}

// var_dump($amount);

$amount_without_instalment = $amount;
// var_dump($amount_without_instalment);

$query = mysql_query("SELECT MIN(balance) AS min_balance FROM loan_payment_details WHERE receipt_no = '" . $get_q['loan_code'] . "'");
$result_loan_payment_details = mysql_fetch_assoc($query);

$loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];

if ($branch_name != 'yuwang') {
	$envelope = $loan_payment_details_min_balance - $amount;
}

$envelope_without_instalment = $envelope;
// var_dump($envelope_without_instalment);

if ($get_q['loan_code'] == 'KT 20034') {
	$envelope += 9157;
}

$query = mysql_query("SELECT SUM(amount) AS collected FROM late_interest_payment_details WHERE lid = '" . $get_q['id'] . "'");
while ($row = mysql_fetch_assoc($query)) {
	$previous_months_bd_collected += round($row['collected'],2);
}

// if ($get_q['bd_from'] != 'Monthly') {
	$amount -= $previous_months_bd_collected;
// }

if ($get_q['loan_code'] == 'YW1210') {
	$previous_monthly_bd = 0;
}

$balance_without_instalment = $amount_without_instalment + $previous_monthly_bd + $envelope_without_instalment;
// var_dump($balance_without_instalment);

if ($get_q['loan_code'] == 'KT 20034') {
	$balance_without_instalment += 9157;
}

if ($amount < 0) {
	$deductible = abs($amount);
	$amount = 0;
}

if ($amount == 0) {
	$difference = $previous_monthly_bd - $deductible;
	if ($difference <= 0) {
		$previous_monthly_bd = 0;
		$deductible = abs($difference);
	} else {
		$previous_monthly_bd = $difference;
	}								
}

if ($previous_monthly_bd == 0) {
	$envelope -= $deductible;
}

if ($envelope < 0) {
	$envelope = 0;
} 

if ($branch_name == 'majusama' && $late_interest_record_id <= 46) {
	$previous_monthly_bd = 0;
	$envelope = 0;
}

$balance = $amount + $previous_monthly_bd + $envelope;
// var_dump($balance);

//customer
$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
$cust = mysql_fetch_assoc($cust_q);

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['package_id']."'");
$package = mysql_fetch_assoc($package_q);


$payment = mysql_query("SELECT * 
    FROM late_interest_payment_details 
    WHERE lid = '".$id."' 
      AND (collection_status IS NULL OR collection_status != 'PENDING')
");
// var_dump("SELECT * FROM late_interest_payment_details WHERE lid = '".$id."' AND collection_status != 'PENDING'");
// if ($get_q['bd_from'] == 'Monthly') {
// 	if ($get_q['loan_code'] == 'YW1210') {
// 		$payment = mysql_query("SELECT payout_amount AS amount, month AS month_receipt, payment_date FROM monthly_payment_record WHERE loan_code = '". $get_q['loan_code'] ."' AND status != 'BAD DEBT'");
// 	}
// }
?>

<style>
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
#search
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search:hover
{
	background:url(../img/enquiry/search-btn-roll-over.jpg);
}
.style1 {
	font-size: 16px;
	font-weight: bold;
}
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Payment Late Interest</td>
        <td align="right">
        </td>
    </tr>
    <tr>
    	<td colspan="3">
			<table width="100%">
				<tr>
					<td>
						<table id="list_table" width="100%">
							<tr>
								<td align="right">Customer Name:</td><td><?php echo $cust['name']; ?></td>
								<td align="right">Agreement No:</td><td><?php echo $get_q['loan_code']; ?></td>
								<td align="right">BD Amount:</td>
								<!-- <td>RM <?php echo $get_q['amount']; ?></td> -->
								<td>RM <?php echo $amount; ?></td>
							</tr>
							<tr>
								<td align="right">Customer ID:</td><td><?php echo $cust['customercode2']; ?></td>
								<td align="right">Package:</td><td><?php echo $package['scheme']; ?></td>
								<td align="right">Late Balance:</td>
								<!-- <td>RM <?php echo $get_q['balance']; ?></td> -->
								<td>RM <?php echo $balance; ?></td>
							</tr>
							<tr>
								<td align="right">NRIC:</td><td><?php echo $cust['nric']; ?></td>
								<td align="right"><!-- Payment Status: --> </td>
								<td><!-- <?php if($get_q['status'] == ''){ ?>
									<select name="status" id="status" onchange="statusChanged(this.value, '<?php echo $id; ?>')" style="height:30px">
										<option value=""></option>
										<option value="CLEARED">CLEARED</option>
									</select>
									<?php } else { echo $get_q['status']; } ?> -->
								</td>
								<?php
									if ($balance > 0) {
								?>
										<td align="right" colspan="2">
											<a href="lateIntpaymentForm.php?id=<?php echo $id; ?>" title="Make Payment" rel="shadowbox; width=600px; height=380px" >
												<table>
													<tr>
														<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
														<td>Make Payment</td>
													</tr>
												</table>
											</a>
										</td>
								<?php
									}
								?>
							</tr>
						</table>
				  </td>
				</tr>
			</table>
		</td>
    </tr>
	
    <tr>
    	<td colspan="3">
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
    	<th width="50">No.</th>
    	<th width="250">Month</th>
    	<th width="250">Payment Date</th>
    	<th width="250">Amount</th>
        <th width="150">Balance</th>
        <th width="80"></th>
    </tr>
	
    <?php 
	$ctr = 0;
	$previous_balance = $balance_without_instalment;
	// var_dump($previous_balance);
	while($get_p = mysql_fetch_assoc($payment)){ 
		$ctr++;
		// $pay += $get_p['amount'];
		// $balance = $get_q['amount'] - $pay;
		$paid_amount = $get_p['amount'];
		$current_balance = $previous_balance - $get_p['amount'];

		// if ($get_q['bd_from'] == 'Monthly') {
		// 	$current_balance = $balance;
		// 	$paid_amount = $current_balance;
		// }
		$previous_balance = $current_balance;
	?>
	
    <tr>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo $get_p['month_receipt']; ?></td>
    	<td><?php echo date('d/m/Y', strtotime($get_p['payment_date'])); ?></td>
    	<td><?php echo "RM ".$paid_amount; ?></td>
        <!-- <td><?php echo "RM ".number_format($balance,2); ?></td> -->
		<td><?php echo "RM ".number_format($current_balance,2); ?></td>
        <?php 
				/*$checkrow_q = mysql_query("SELECT * FROM late_interest_payment_details WHERE id > '".$get_p['id']."' AND lid = '".$id."'"); 
							$checkrow = mysql_num_rows($checkrow_q);
							if($checkrow == 0)
							{*/
						?>
         <td align="center">
		 <center>
		 <?php 
			if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) 
			{ 
		?>
				<!-- <a href="edit_payLateInt.php?id=<?php echo $get_p['id']; ?>" rel="shadowbox; width=800px; height=380px" title="Edit"><img src="../img/customers/edit-icon.png" /> -->

				<a href="javascript:deleteConfirmation('<?php echo $get_p['payment_date']; ?>', '<?php echo $get_p['id']; ?>', '<?php echo $get_p['lid']; ?>', '<?php echo $get_p['amount']; ?>', '<?php echo $get_p['balance']; ?>' )"><img src="../img/customers/delete-icon.png" title="Delete" style="margin-bottom:2px" /></a>
		<?php
			}
		?>
		</center></td>
         <?php //} ?>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4">&nbsp;</td>
       
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="history.back();" value=""></td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
</table>
</center>
<script>
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	 $("#loan_code").autocomplete("auto_loanCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
  
});

$('#month').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
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
Shadowbox.init();

function statusChanged(status, id)
{
	$status = status;
	$id = id;
	$.confirm({
		'title'		: 'Update Late Interest Status',
		'message'	:  'Are You sure want to change the status to CLEARED?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_status',
							status: $status,
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

function deleteConfirmation(payment_date, id, lid, amount, balance){
	$id = id;
	$lid = lid;
    $payment_date = payment_date;
	$amount = amount;
	$balance = balance;
	
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to DELETE this payment: ' + payment_date + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_lateint',
							id: $id,
							lid: $lid,
							payment_date: $payment_date,
							amount: $amount,
							balance: $balance,
							
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

function edit(payment_date, id, lid, amount, balance){
	$id = id;
	$lid = lid;
    $payment_date = payment_date;
	$amount = amount;
	$balance = balance;
	
	$.confirm({
		'title'		: 'Edit Confirmation',
		'message'	: 'Are you sure want to EDIT this payment: ' + payment_date + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'edit_lateint',
							id: $id,
							lid: $lid,
							payment_date: $payment_date,
							amount: $amount,
							balance: $balance,
							
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
</script>
