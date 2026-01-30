<?php 
include('../include/page_header.php'); 
$mprid = $_GET['mprid'];

$sql = mysql_query("SELECT * FROM monthly_payment_record WHERE id = '".$mprid."'");
$get_q = mysql_fetch_assoc($sql);

//customer
$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
$cust = mysql_fetch_assoc($cust_q);

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['package_id']."'");
$package = mysql_fetch_assoc($package_q);

$payment = mysql_query("SELECT * FROM monthly_payment_details WHERE mprid = '".$mprid."'");
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
        <td>Monthly Payment</td>
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
								<td align="right">Loan Code:</td><td><?php echo $get_q['loan_code']; ?></td>
								<td align="right">Payout Amount:</td>
								<td>RM <?php echo $get_q['payout_amount']; ?></td>
							</tr>
							<tr>
								<td align="right">Customer Code:</td><td><?php echo $cust['customercode2']; ?></td>
								<td align="right">Package:</td><td><?php echo $package['scheme']; ?></td>
								<td align="right">Balance:</td>
								<td>RM <?php echo $get_q['balance']; ?></td>
							</tr>
							<tr>
								<td align="right">NRIC:</td><td><?php echo $cust['nric']; ?></td>
								<td align="right">Payment Status: </td>
								<td><?php if($get_q['status'] == ''){ ?>
									<select name="status" id="status" onchange="statusChanged(this.value, '<?php echo $mprid; ?>')" style="height:30px">
										<option value=""></option>
										<option value="CLEARED">CLEARED</option>
									</select>
									<?php } else { echo $get_q['status']; } ?>
								</td>
								<td align="right" colspan="2">
									<?php //if($get_q['balance'] != '0') { ?>
									<a href="monthlyPaymentForm.php?mprid=<?php echo $mprid; ?>" title="Make Payment" rel="shadowbox; width=800px; height=380px" >
										<table>
											<tr>
												<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
												<td>Make Payment</td>
											</tr>
										</table>
									</a>
								  <?php //} ?>
								</td>
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
    	<th>Payment Date</th>
    	<th width="150">Amount</th>
        <th width="150">Balance</th>
        <th width="80"></th>
    </tr>
	
    <?php 
	$ctr = 0;
	while($get_p = mysql_fetch_assoc($payment)){ 
	$ctr++;
	
	?>
	
    
    <tr>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo date('d/m/Y', strtotime($get_p['payment_date'])); ?></td>
    	<td><?php echo "RM ".$get_p['payment_amount']; ?></td>
        <td><?php echo "RM ".$get_p['balance']; ?></td>
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
				<a href="edit_payLateInt.php?id=<?php echo $get_p['id']; ?>" rel="shadowbox; width=800px; height=380px" title="Edit"><img src="../img/customers/edit-icon.png" />

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
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='view_monthly_list.php?loan_code=<?php echo $get_q['loan_code']; ?>&id=<?php echo $get_q['customer_id'];?>'" value=""></td>
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
	$mprid = id;
	$.confirm({
		'title'		: 'Update Month Payment Status',
		'message'	:  'Are You sure want to change the status to CLEARED?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_mpr_status',
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
