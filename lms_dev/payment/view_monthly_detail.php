<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];
$loan_code = $_GET['loan_code'];
$mprid = $_GET['mprid'];

//customer


$sql = mysql_query("SELECT * FROM monthly_payment_record WHERE  id = '".$mprid."' AND customer_id = '".$id."' AND (status ='PAID' OR status = 'FINISHED' OR status = 'BAD DEBT') order by monthly_date DESC");
$get_q = mysql_fetch_assoc($sql);

$sql1 = mysql_query("SELECT * FROM monthly_payment_details WHERE  mprid = '".$get_q['id']."'");

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
$cust = mysql_fetch_assoc($cust_q);


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
        	<input type="hidden" name="mprid" id="mprid" value="<?php echo $get_q['id']; ?>">
        </td>
    </tr>
    <tr>
    	<td colspan="3">
			<table width="100%">
				<tr>
					<td>
						<table id="list_table" width="100%">
							<tr>
								<td align="left" ><b>Customer Name:</b></td><td style="color:blue;"><?php echo $cust['name']; ?></td>
								<td align="left" ><b>Payout Date:</b></td><td style="color:blue;"><?php echo date('d/m/Y', strtotime($get_q['monthly_date'])); ?></td>
							</tr>
							<tr>
								<td align="left"><b>Customer ID:</b></td><td style="color:blue;"><?php echo $cust['customercode2']; ?></td>
								<td align="left"><b>Loan Code:</b></td><td style="color:blue;"><?php echo $get_q['loan_code']; ?></td>
							</tr>
							<tr>
								<td align="left"><b>NRIC:</b></td><td style="color:blue;"><?php echo $cust['nric']; ?></td>
								<td align="left"><b>Loan Amount:</b></td>
								<td>RM <input type="text" name="payout_amount" id="payout_amount" value="<?php echo $get_q['payout_amount']; ?>" />        	
									<a href="javascript:editpayoutamount('<?php echo $get_q['id']; ?>')" title="save payout amount"><img src="../img/document_save.png" /></a>
								</td>
								<td>
									<a href="monthlyPaymentForm.php?mprid=<?php echo $get_q['id']; ?>" title="Make Payment" rel="shadowbox; width=600px; height=380px" >
										<table>
											<tr>
												<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
												<td>Make Payment</td>
											</tr>
										</table>
									</a>
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
    	<th width="200">Payment Date</th>
    	<th width="200">Payment Amount</th>
        <th width="200">Balance</th>
        <th width="150"></th>
    </tr>
	
    <?php 
	$ctr = 0;


	while($get_q1 = mysql_fetch_assoc($sql1)){
		$ctr++;

		$loan_amount = $get_q['payout_amount'];
		$payment_amount += $get_q1['payment_amount'];


                  
	?>
	
    
    <tr>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo date('d/m/Y', strtotime($get_q1['payment_date'])); ?></td>
    	<td><?php echo "RM ".$get_q1['payment_amount']; ?></td>
        <td><?php echo "RM ".($loan_amount - $payment_amount); ?></td>
		<td>
			<a href="edit_monthly.php?mprid=<?php echo $get_q['id']; ?>&detail_id=<?php echo $get_q1['id']?>" rel="shadowbox; width=600px; height=380px" ><img src="../img/customers/edit-icon.png" /></a>
			&nbsp;&nbsp;
			<a href="javascript:deleteConfirmation('<?php echo $get_q1['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
		</td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="5">&nbsp;</td>
       
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='view_monthly_list.php?loan_code=<?php echo $loan_code; ?>&id=<?php echo $id;?>'" value=""></td>
    </tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
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

function editpayoutamount(id)
{
	$payout_amount = document.getElementById('payout_amount').value;
	$id = id;
	$.confirm({
		'title'		: 'Update Loan Payout Amount',
		'message'	:  'Are You sure want to change the loan payout amount?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'edit_Monthly',
							payout_amount: $payout_amount,
							id: $id,
						},
						url: 'action_payout_monthly.php',
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
	$mprid = document.getElementById('mprid').value;
	
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
							action: 'delete_monthly_detail',
							id: $id,
							mprid: $mprid,
						},
						url: 'action_payout_monthly.php',
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
