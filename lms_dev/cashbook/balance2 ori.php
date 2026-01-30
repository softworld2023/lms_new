<?php include('../include/page_header.php');

$package_id = $_GET['id'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
$get_p = mysql_fetch_assoc($package_q);

$cf = $get_p['initial_amount'];
$cf_date = $get_p['opening_date'];

if($get_p['type'] == 'Fixed Loan')
{
	$loan_q = mysql_query("SELECT * FROM balance_rec WHERE package_id = '".$package_id."' GROUP BY CONCAT(YEAR(bal_date), '-', MONTH(bal_date)) ORDER BY bal_date DESC");
}else
{
	$loan_q = mysql_query("SELECT * FROM balance_rec WHERE package_id = '".$package_id."' GROUP BY month_receipt ORDER BY month_receipt DESC");
}	

?>
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
        <td>Balance 2: <?php echo $get_p['scheme']; ?></td>
        <td align="right">&nbsp;</td>
    </tr>
    
   	<tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php">Cash Book</a><a href="balance_1.php">Balance 1</a><a href="balance.php" id="active-menu">Balance 2</a>
		</div>
		</td>
        <td align="right" style="padding-right:10px">
			<?php if($get_p['type'] == 'Flexi Loan' && $get_p['receipt_type'] == '2') { ?>
			<a href="add_outstanding.php?id=<?php echo $package_id; ?>" title="Oustanding">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Add Outstanding</td>
                	</tr>
                </table>
            </a>
			<?php } ?>
		</td>
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
    	<td colspan="6"><strong>BALANCE 2 <?php echo $get_p['scheme']; ?></strong></td>
    </tr>
	<tr>
    	<th width="50">No.</th>
        <th>Month</th>
        <th>Out</th>
        <th>In</th> 
		<?php if($get_p['type'] == 'Flexi Loan' && $get_p['receipt_type'] == '2') { ?>
        <th colspan="2">CF Amount </th>
		<th></th>
		<?php } ?>
    </tr>
    
    <?php 
	$ctr = '';
    while($get_l = mysql_fetch_assoc($loan_q)){
		$ctr++;
		if($get_p['type'] == 'Fixed Loan')
		{
			$month_cb = date('Y-m', strtotime($get_l['bal_date']));	
			
			$month_name = date('M Y', strtotime($get_l['bal_date']));	

			$prevbal_q = mysql_query("SELECT SUM(loan) AS amtloan, SUM(received) AS amtrec, SUM(commission) AS amtcom, SUM(interest) AS amtint FROM balance_rec WHERE package_id = '".$package_id."' AND bal_date LIKE '%".$month_cb."%'");
		}else
		{
			$month_cb = $get_l['month_receipt'];	
			
			$month_name = date('M Y', strtotime($get_l['month_receipt']));	

			$prevbal_q = mysql_query("SELECT SUM(loan) AS amtloan, SUM(received) AS amtrec, SUM(commission) AS amtcom, SUM(interest) AS amtint FROM balance_rec WHERE package_id = '".$package_id."' AND month_receipt = '".$month_cb."'");
		}
		
		$get_pb = mysql_fetch_assoc($prevbal_q);
		
		$out_amt = $get_pb['amtloan'];
		$in = $get_pb['amtrec'] + $get_pb['amtcom'] + $get_pb['amtint'];
		
		$cf2_q = mysql_query("SELECT * FROM bal2_cf WHERE package_id = '".$package_id."' AND month = '".$month_cb."'");
		$cf2 = mysql_fetch_assoc($cf2_q);
		
	?>
    <tr>
    	<td><?php echo $ctr; ?></td>
        <td><a href="balance2details.php?id=<?php echo $package_id; ?>&mth=<?php echo $month_cb; ?>"><?php echo $month_name; ?></a></td>
        <td><?php echo "RM ".number_format($out_amt, '2') ; ?></td>
        <td><?php echo "RM ".number_format($in, '2'); ?></td>
		<?php if($get_p['type'] == 'Flexi Loan' && $get_p['receipt_type'] == '2') { ?>
		<td><?php if($cf2) { echo "RM ".$cf2['amount']; } else { echo "RM 0.00"; } ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $cf2['amount']; ?>" class="currency">                    
					</td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($get_p['bal2_opening_date'] != '0000-00-00') { echo $cf2['date']; }?>">                    
					</td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '<?php echo $month_cb; ?>', '<?php echo $package_id; ?>')" value="">                   
					</td>
                </tr>
            </table>
        	</div>		
		</td>
		<td><a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a></td>
		<?php } ?>
    </tr>
    <?php } ?>
    <tr>
		<?php if($get_p['type'] == 'Flexi Loan' && $get_p['receipt_type'] == '2') { ?>
    	<td colspan="7"><input type="hidden" name="ctr" id="ctr" value="<?php echo $ctr; ?>" /></td>
		<?php } else { ?>
		<td colspan="4">&nbsp;</td>
		<?php } ?>
    </tr>
    <tr>
		<?php if($get_p['type'] == 'Flexi Loan' && $get_p['receipt_type'] == '2') { ?>
    	<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/balance.php'" value=""></td>
		<?php } else { ?>
		<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/balance.php'" value=""></td>
		<?php } ?>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
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

function updateAmount(no, mth, pid)
{
	$amount = $('#amount_' + no).val();
	$mth = mth;
	$pid = pid;
	$date = $('#opening_' + no).val();
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_bal2',
			mth: $mth,
			pid: $pid,
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
	$('#opening_' + $i).click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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