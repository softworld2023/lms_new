<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];

$sql = mysql_query("SELECT * FROM skim_kutu WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);

$stock = $get_q['stock'];

$payment = mysql_query("SELECT * FROM skimkutu_payment WHERE skim_id = '".$id."'");
$paymentdone = mysql_num_rows($payment);
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
	<td>Payment Skim Kutu <strong><?php echo $get_q['title']; ?> 
		<input type="hidden" name="titledesc" id="titledesc" value="<?php echo $get_q['title'];  ?>"></strong></td>
	<td align="right">
	</td>
</tr>
<tr>
	<td colspan="3">
		<table width="100%">
		<tr>
			<td><span class="style1"><?php echo "( ".$get_q['title']." ) ".$get_q['period']." X ".number_format($get_q['monthlyamt'], '2'); ?></span></td>
			<td><span class="style1"><?php echo $get_q['description']; ?></span></td>
			<td align="right">
			<?php if($paymentdone != $get_q['period']) { ?>
				<a href="kutupayment.php?id=<?php echo $id; ?>" title="Make Payment" rel="shadowbox; width=800px; height=380px" >
					<table>
					<tr>
						<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
						<td>Make Payment</td>
					</tr>
					</table>
				</a>
			<?php } ?>
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

<table width="1250" id="list_table" border="0">
<tr>
	<th width="30">No.</th>
	<th>Date</th>
	<th>In</th>
	<th>Out</th>
	<th>Stock</th>
	<th width="40%"></th>
	<th width="30">Edit</th>
	<th width="40"></th>     
</tr>
<tr>
  <td>&nbsp;</td>
  <td><?php echo date('d/m/Y', strtotime($get_q['date'])); ?></td>
  <td><?php echo number_format($get_q['inamt'], '2'); ?></td>
  <td>&nbsp;</td>
  <td><?php echo number_format($stock, '2'); ?></td>
  <td>
	<div style="padding-left:10px; visibility:hidden" id="edit_0">
	<table>
	<tr>
		<td>Edit Date</td>
		<td style="padding:0px">
		  <input type="text" size="12" name="editdate" id="editdate" value="<?php echo $get_q['date'];  ?>">
		</td>
		<td>Edit In</td>
		<td style="padding:0px">
		  <input type="text" size="12" name="editin" id="editin" value="<?php echo $get_q['inamt']; ?>">
		</td>
		<td></td>
		<td style="padding:0px"></td>
		<td>Edit Stock</td>
		<td style="padding:0px">
		  <input type="text" size="12" name="editstock" id="editstock" value="<?php echo $stock;?>">
		</td>
		<td>
			<input type="button" name="update" id="update" onclick="updateAmount('<?php echo $get_q['id']; ?>')" value=" Update ">
		</td>
	</tr>
	</table>
	</div>
  </td>
  <td>
	<a href="javascript:showEdit('0')">
		<img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
  </td>
  <td>&nbsp;</td>
</tr>
<?php 
	$ctr = 0;
	while($get_p = mysql_fetch_assoc($payment)){ 
		$ctr++;
	
		$stock -= $get_p['amount'];
?> 
<tr>
    <td><?php echo $ctr.".".$get_p['id']; ?></td>
    <td><?php echo date('d/m/Y', strtotime($get_p['payment_date'])); ?></td>
    <td>&nbsp;</td>
    <td><?php echo "RM ".$get_p['amount']; ?></td>
    <td><?php echo number_format($stock, '2'); ?></td>
	<td>
		<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
		<table>
		<tr>
			<td>Edit Date</td>
			<td style="padding:0px">
			  <input type="text" size="12" name="editdate_<?php echo $ctr; ?>" id="editdate_<?php echo $ctr; ?>" value="<?php echo $get_p['payment_date'];  ?>">
			</td>
			<td>Edit Out</td>
			<td style="padding:0px">
			  <input type="text" size="12" name="editout_<?php echo $ctr; ?>" id="editout_<?php echo $ctr; ?>" value="<?php echo $get_p['amount']; ?>">
			</td>
			<td></td>
			<td style="padding:0px"></td>
			<!--<td>Edit Stock</td>
			<td style="padding:0px">
			  <input type="text" size="12" name="editstock_<?php echo $ctr; ?>" id="editstock_<?php echo $ctr; ?>" value="<?php echo $stock;?>">
			</td>-->
			<td>
				<input type="button" name="update" id="update" onclick="updateAmount1('<?php echo $ctr; ?>', '<?php echo $get_p['id']; ?>')" value=" Update ">
			</td>
		</tr>
		</table>
		</div>
	</td>
	<td>
		<a href="javascript:showEdit('<?php echo $ctr; ?>')">
			<img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
	</td>
	<td align="left">
<?php 
	if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { 
 
	$checkrow_q = mysql_query("SELECT * FROM skimkutu_payment WHERE id > '".$get_p['id']."' AND skim_id = '".$id."'"); 
	$checkrow = mysql_num_rows($checkrow_q);
	if($checkrow == 0)
	{
?>
		<center>
			<a href="javascript:deleteConfirmation('<?php echo $get_p['payment_date']; ?>', '<?php echo $get_p['id']; ?>', '<?php echo $get_p['amount']; ?>')">
				<img src="../img/customers/delete-icon.png" title="Delete" style="margin-bottom:2px" /></a>
		</center>
<?php 
	} 
?>   
	</td>
</tr>
<?php 
	} 
	}
?>
<?php
if($ctr != $get_q['period']) { 
	$i = $ctr + 1;
	for($x = $i; $x <= $get_q['period']; $x++)
	{
	?>
	<tr>
		<td><?php echo $x."."; ?></td>
		<td></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?php echo number_format($stock, '2'); ?></td>
	</tr>
<?php 
	}
} ?>
    
<tr>
	<td colspan="8" align="right">
		<input type="button" name="back" id="back" onClick="window.location.href='skimKutu.php'" value=""></td>
</tr>
<tr>
	<td colspan="8">&nbsp;</td>
</tr>
</table>
</center>
<script>
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

function updateAmount(id)
{
	$id = id;
	$editdate = $('#editdate').val();
	$editin = $('#editin').val();
	$titledesc = $('#titledesc').val();
	$editstock = $('#editstock').val();
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			titledesc: $titledesc,
			editdate: $editdate,
			editin: $editin,
			editstock: $editstock,
		},
		url: 'edit_skim_kutu.php',
			success: function(){
			location.reload();
		}
	})
}

function updateAmount1(no, id)
{
	$id = id;
	$editdate = $('#editdate_'+no).val();
	$editout = $('#editout_'+no).val();
	$editstock = $('#editstock_'+no).val();
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount1',
			id: $id,
			editdate: $editdate,
			editout: $editout,
			editstock: $editstock,
		},
		url: 'edit_skim_kutu.php',
			success: function(){
			location.reload();
		}
	})
}

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

function deleteConfirmation(payment_date, id, amount){
	$id = id;
    $payment_date = payment_date;
	$amount = amount;
	
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this payment late interest: ' + payment_date + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_payment',
							id: $id,
							payment_date: $payment_date,
							amount: $amount,
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
