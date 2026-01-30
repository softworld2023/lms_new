<?php include('../include/page_header.php'); ?>
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
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/cash-book/cash-book.png" style="height:47px"></td>
        <td>Balance 1 </td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php">Cash Book</a><a href="balance_1.php" id="active-menu">Balance 1</a><a href="balance.php">Balance 2</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
    </tr>
</table>
<div id="message" style="width:1280px; text-align:left">
	<?php
    if($_SESSION['msg'] != '')
    {
        echo $_SESSION['msg'];
        $_SESSION['msg'] = '';
    }
    ?>						
</div>
<br />
<table width="1280" id="list_table">
	<tr>
    	<th width="50">No.</th>
        <th>Package</th>
        <th>Opening Balance</th>
        <th>Opening Date</th>
        <th>&nbsp;</th>
        <th width="100">&nbsp;</th>
    </tr>
    <?php 
	$ctr = 0;
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme != 'SKIM KUTU' ORDER BY scheme ASC");
    while($get_p = mysql_fetch_assoc($package_q)){
	
	$cash_cf = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."'");
	$cashcf = mysql_fetch_assoc($cash_cf);
	$ctr++;
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><a href="balance1.php?id=<?php echo $get_p['id']; ?>"><?php echo $get_p['scheme']; ?></a></td>
        <td><?php echo "RM ".number_format($cashcf['amount'], '0'); ?></td>
        <td><?php if($cashcf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($cashcf['date'])); } else { echo "-"; } ?></td>
        <td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $cashcf['amount']; ?>" class="currency">                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($cashcf['date'] != '0000-00-00' || $cashcf['date'] != '') { echo date('d-m-Y', strtotime($cashcf['date'])); }?>">                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '<?php echo $get_p['id']; ?>')" value="">                    </td>
                </tr>
            </table>
        	</div>		</td>
        <td>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')){?>
			<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php } ?>
		
		</td>
    </tr>
    <?php } ?>
	<?php
	$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1000'");
	$skimh_r = mysql_num_rows($skimh_q);
	if($skimh_r > 0)
	{
		$ctr++;
		
		$b1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1000'");
		$b1cf = mysql_fetch_assoc($b1cf_q);
	?>
	<tr>
		<td><?php echo $ctr."."; ?></td>
		<td><a href="balance1_rec.php?id=1000">SKIM H</a></td>
		<td><?php echo "RM ".number_format($b1cf['amount'], '2'); ?></td>
		<td><?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); } else { echo "-"; } ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $b1cf['amount']; ?>" class="currency">                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); }?>">                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '1000')" value="">                    </td>
                </tr>
            </table>
        	</div>
		</td>
		<td>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')){?>
			<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php } ?>
		</td>
	</tr>
	<?php
	}	
	?>
	<?php
	$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1001'");
	$skimh_r = mysql_num_rows($skimh_q);
	if($skimh_r > 0)
	{
		$ctr++;
		
		$b1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1001'");
		$b1cf = mysql_fetch_assoc($b1cf_q);
	?>
	<tr>
		<td><?php echo $ctr."."; ?></td>
		<td><a href="balance1_rec.php?id=1001">SKIM AH</a></td>
		<td><?php echo "RM ".number_format($b1cf['amount'], '2'); ?></td>
		<td><?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); } else { echo "-"; } ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $b1cf['amount']; ?>" class="currency">                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); }?>">                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '1001')" value="">                    </td>
                </tr>
            </table>
        	</div>
		</td>
		<td>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')){?>
			<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php } ?>
		</td>
	</tr>
	<?php
	}	
	?>
	<?php
	$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1002'");
	$skimh_r = mysql_num_rows($skimh_q);
	if($skimh_r > 0)
	{
		$ctr++;
		
		$b1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1002'");
		$b1cf = mysql_fetch_assoc($b1cf_q);
	?>
	<tr>
		<td><?php echo $ctr."."; ?></td>
		<td><a href="balance1_rec.php?id=1002">SKIM AH CEK </a></td>
		<td><?php echo "RM ".number_format($b1cf['amount'], '2'); ?></td>
		<td><?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); } else { echo "-"; } ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $b1cf['amount']; ?>" class="currency">                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); }?>">                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '1002')" value="">                    </td>
                </tr>
            </table>
        	</div>
		</td>
		<td>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')){?>
			<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php } ?>
		</td>
	</tr>
	<?php
	}	
	?>
	<?php
	$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1003'");
	$skimh_r = mysql_num_rows($skimh_q);
	if($skimh_r > 0)
	{
		$ctr++;
		
		$b1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1003'");
		$b1cf = mysql_fetch_assoc($b1cf_q);
	?>
	<tr>
		<td><?php echo $ctr."."; ?></td>
		<td><a href="balance1_rec.php?id=1003">SKIM KH</a></td>
		<td><?php echo "RM ".number_format($b1cf['amount'], '2'); ?></td>
		<td><?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); } else { echo "-"; } ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $b1cf['amount']; ?>" class="currency">                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); }?>">                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '1003')" value="">                    </td>
                </tr>
            </table>
        	</div>
		</td>
		<td>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')){?>
			<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php } ?>
		</td>
	</tr>
	<?php
	}	
	?>
	<?php
	$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1004'");
	$skimh_r = mysql_num_rows($skimh_q);
	if($skimh_r > 0)
	{
		$ctr++;
		
		$b1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1004'");
		$b1cf = mysql_fetch_assoc($b1cf_q);
	?>
	<tr>
		<td><?php echo $ctr."."; ?></td>
		<td><a href="balance1_rec.php?id=1004">SKIM SH</a></td>
		<td><?php echo "RM ".number_format($b1cf['amount'], '2'); ?></td>
		<td><?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); } else { echo "-"; } ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $b1cf['amount']; ?>" class="currency">                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($b1cf['date'] != '0000-00-00') { echo date('d-m-Y', strtotime($b1cf['date'])); }?>">                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '1004')" value="">                    </td>
                </tr>
            </table>
        	</div>
		</td>
		<td>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong')){?>
			<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php } ?>
		</td>
	</tr>
	<?php
	}	
	?>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="hidden" name="ctr" id="ctr" value="<?php echo $ctr; ?>" /><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
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
	$date = $('#opening_' + no).val();
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amountbal1',
			id: $id,
			amount: $amount,
			opening: $date,
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

$ctr = document.getElementById('ctr').value;
for($i=1; $i<=$ctr; $i++)
{
	$('#opening_' + $i).click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
</script>
</body>
</html>