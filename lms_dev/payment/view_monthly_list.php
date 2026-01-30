<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];
$loan_code = $_GET['loan_code'];

//customer

$sql = mysql_query("SELECT * FROM monthly_payment_record WHERE  customer_id = '".$id."' AND (status ='PAID' OR status = 'FINISHED' OR status = 'BAD DEBT') order by month DESC, monthly_date DESC");

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
$cust = mysql_fetch_assoc($cust_q);


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
			<table width="50%">
				<tr>
					<td>
						<table id="list_table" width="60%">
							<tr>
								<td align="left" ><b>Customer Name:</b></td><td style="color:blue;"><?php echo $cust['name']; ?></td>
							</tr>
							<tr>
								<td align="left"><b>Customer ID:</b></td><td style="color:blue;"><?php echo $cust['customercode2']; ?></td>
							</tr>
							<tr>
								<td align="left"><b>NRIC:</b></td><td style="color:blue;"><?php echo $cust['nric']; ?></td>
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
    	<th width="150">Agreement Code</th>
    	<th width="150">Payout Date</th>
    	<th width="150">Month</th>
    	<th width="150">Amount</th>
        <th width="100">Balance</th>
        <th width="200">STATUS</th>
        <th width="150"></th>
    </tr>
	
    <?php 
	$ctr = 0;
	$r = mysql_num_rows($sql);

	$i=0;
	if($r>0){	
		$currentDate = false;
	while($get_q = mysql_fetch_assoc($sql)){
                    $i++;
                    if ($get_q['month'] != $currentDate){     
                    	 $i=1;
        ?>
        <tr style="background-color: lightblue;">
          <td colspan='11' style="color:black;font-size: 22px;"><b><?php echo date('M-y', strtotime($get_q['month'])); ?><b></td>
        </tr>
        <?php $currentDate = $get_q['month'];

            }
	$ctr++;

	if($get_q['status']=='PAID'){
		$status = 'Payout';
		$style = " ";

	}else if($get_q['status']=='FINISHED'){
		$status = 'Done Payment ('.date('d/m/Y', strtotime($get_q['payment_date'])).')';
		$style = " ";
	}
	else
	{
		$status = 'BAD DEBT';
		$style = "style='color:#FF0000'";
	}
	
	?>
	
    
    <tr <?php echo $style; ?>>
    	<td><?php echo $i."."; ?></td>
    	<td><?php echo $get_q['loan_code']; ?></td>
    	<td><?php echo date('d/m/Y', strtotime($get_q['monthly_date'])); ?></td>
    	<td><?php echo date('M-y', strtotime($get_q['month'])); ?></td>
    	<td><?php echo "RM ".$get_q['payout_amount']; ?></td>
        <td><?php echo "RM ".$get_q['balance']; ?></td>
        <td><?php echo $status;?></td>
		<td>
			<?php 
			if( $get_q['status']=='BAD DEBT')
			{ ?>
				
			<?php }

		else if( $get_q['balance']=='0')
		{
		 ?>
			<a href="view_monthly_detail.php?loan_code=<?php echo $get_q['loan_code']; ?>&id=<?php echo $get_q['customer_id'];?>&mprid=<?php echo $get_q['id'];?>"><img src="../img/payout/payout-icon.png"></a>
			&nbsp;&nbsp;&nbsp;
		<?php }else{?>

			<a href="view_monthly_detail.php?loan_code=<?php echo $get_q['loan_code']; ?>&id=<?php echo $get_q['customer_id'];?>&mprid=<?php echo $get_q['id'];?>"><img src="../img/payout/payout-icon.png"></a>
			&nbsp;&nbsp;

			<?php 
			if($get_q['balance'] <= $get_q['payout_amount']){?>
			<a href="bad_debt_monthly.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox; width=600px; height=420px"><img src="../img/error.png" title="Bad Debt" width="25"></a>
			&nbsp;&nbsp;
			<a href="javascript:deleteConfirmation('<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a><?php } }?>
        	</td>
         <?php //} ?>
    </tr>
    <?php } }?>
    <tr>
    	<td colspan="8">&nbsp;</td>
       
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='payment_monthly.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
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

function deleteConfirmation(id){
	$id = id;
	
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to DELETE this ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_payout',
							id: $id,
						},
						url: '../report/action_payment.php',
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
function BadDebtConfirmation(id){
	$id = id;
	
	$.confirm({
		'title'		: 'Bad Debt Confirmation',
		'message'	: 'Are you sure want to record this to be bad debt ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'bad_debt_payout',
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
