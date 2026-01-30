<?php include('../include/page_header.php'); 

$cio_q = mysql_query("SELECT * FROM cashinoffice WHERE id = 1");
$cio = mysql_fetch_assoc($cio_q);

if($cio['date'] == '0000-00-00')
{
	
	$officetot = 0;
	$officetot2 = 0;
	$officetot3= 0;
}else
{
	$officetot = $cio['amount'];
	$officetot2 = 0;
	$officetot3= 0;
}
if(isset($_POST['search']))
{
	if($_POST['date'] != '')
	{
		$_SESSION['date'] = $_POST['date'];
	}
	else
	{
		$_SESSION['date'] = date('Y-m-d');
	}
	
	$date = $_SESSION['date'];
}
else
{
	$date = date('Y-m-d');
}



$calc_q = mysql_query("SELECT * FROM cashbook WHERE date < '".$date."' AND date >= '".$cio['date']."'");
while($calc = mysql_fetch_assoc($calc_q))
{
	if($calc['type'] == 'RECEIVED')
	{
		$in += $calc['amount'];
	}
	if($calc['type'] == 'PAY')
	{
		$out += $calc['amount'];
	}
	if($calc['type'] == 'COMMISSION')
	{
		$rec += $calc['amount'];
	}
	if($calc['type'] == 'EXPENSES')
	{
		$exp += $calc['amount'];
	}
}

$officetot = $officetot - $out + $in + $rec - $exp;
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
#submit
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#submit:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
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
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Reports: <strong>Daily Collections </strong></td>
        <td align="right">
		<?php if($cio['date'] == '0000-00-00') { ?>
		<form action="setTotal.php" method="post">
			<table>
				<tr>
					<td align="right" style="padding-right:10px">Date</td>
                    <td style="padding-right:30px"><input type="text" name="date" id="date" style="height:30px" value="<?php echo $date; ?>" /></td>
					<td align="right" style="padding-right:10px">Total In Office</td>
					<td style="padding-right:30px"><input type="text" name="office_total" id="office_total" style="height:30px" class="currency" value="<?php echo number_format($officetot, '2', '.', ''); ?>" /></td>
					<td style="padding-right:8px">
						<input type="submit" id="submit" name="submit" value="" />                    </td>
				</tr>
			</table>
		</form>
		<?php } else { ?>
		<form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Date</td>
                    <td style="padding-right:30px"><input type="text" name="date" id="date" style="height:30px" value="<?php echo $date; ?>" /></td>
					<td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
				</tr>
            </table>
        </form>
		<?php } ?>
		</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Borrow Out</a><a href="payout.php">Actual Payout</a><a href="collection.php">Total Collection</a><a href="profit.php">Profit & Loss</a><a href="expenses.php">Expenses</a><a href="interest.php">Interest Earn</a><a href="latepayment.php">Late Payment Collections</a><a href="daily.php" id="active-menu">Daily Collections</a><a href="statement.php">Statement</a>		</div>        </td>
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
	 <?php 
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM S'");
	$ctr1 = 0;
    while($get_p = mysql_fetch_assoc($package_q)){
	$ctr1++;
	?>
    <tr>
    	<td><?php echo $get_p['scheme']; ?></td>
	</tr>
	<tr>
		<td>
			<table id="list_table" width="100%">
				<tr>
					<th width="50">No.</th>
					<th>Date</th>
					<th>Customer's Name</th>
					<th width="100">Receipt No. </th>
					<th width="100">In</th>
					<th width="100">Out</th>
					<th width="100">Rec</th>
					<th width="100">Expenses</th>
				</tr>
				<?php
				$id = $get_p['id'];
				$sql = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' GROUP BY receipt_no ORDER BY id ASC");

				$month = $date;
				$cf = $get_p['initial_amount'];
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$id."' AND transaction = 'LOAN' AND date < '".$month."'");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				
				$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$id."' AND transaction = 'REC' AND date < '".$month."'");
				$get_pb2 = mysql_fetch_assoc($prevbal2_q);
				
				$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$id."' AND type = 'EXPENSES' AND date < '".$month."'");
				$get_pb3 = mysql_fetch_assoc($prevbal3_q);
				
				$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$id."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
				$get_pb4 = mysql_fetch_assoc($prevbal4_q);
				
				$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$id."' AND type = 'RECEIVED2' AND date < '".$month."'");
				$get_pb5 = mysql_fetch_assoc($prevbal5_q);
				
				$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$id."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
				$get_pb6 = mysql_fetch_assoc($prevbal6_q);
				
				$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$id."' AND transaction = 'CCM' AND date < '".$month."'");
				$get_pb7 = mysql_fetch_assoc($prevbal7_q);
				
				$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$id."' AND type = 'TRANSFER1' AND date < '".$month."'");
				$get_pb8 = mysql_fetch_assoc($prevbal8_q);
				
				$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$id."' AND type = 'TRANSFER2' AND date < '".$month."'");
				$get_pb9 = mysql_fetch_assoc($prevbal9_q);
				
				$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$id."' AND transaction = 'EXT' AND date < '".$month."'");
				$get_pb10 = mysql_fetch_assoc($prevbal10_q);
				
				$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$id."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
				$get_pb11 = mysql_fetch_assoc($prevbal11_q);
				
				
				$cf = $cf - $get_pb['amtOut'] + $get_pb2['amtIn'] - $get_pb3['amtEx'] + $get_pb4['amtKom'] + $get_pb5['amtOth'] + $get_pb6['amtKom2'] + $get_pb7['amtCCM'] - $get_pb8['amtTrans'] + $get_pb9['amtTrans2'] - $get_pb10['amtExt'] + $get_pb11['amtKInt'];
				?>
				<?php 
				$ctr = 0;
				$total = 0;
				$total2 = 0;
				$total3 = 0;
				while($get_q = mysql_fetch_assoc($sql))
				{
					$ctr++;
					$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
					$get_c = mysql_fetch_assoc($cust_q);
					
					$in = 0;
					$out = 0;
					$rec = 0;

					$payment = 0;
					$payment2 = 0;
					$payment3 = 0;

					$payment_q = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND date LIKE '%".$date."%' AND receipt_no = '".$get_q['receipt_no']."'");
					while($pay = mysql_fetch_assoc($payment_q))
					{
						if($pay['type'] == 'RECEIVED')
						{
							$in += $pay['amount'];
						}
						if($pay['type'] == 'PAY')
						{
							$out += $pay['amount'];
						}
						if($pay['type'] == 'COMMISSION')
						{
							$rec += $pay['amount'];
						}
						
						
						$payment = $in;
						$payment2 = $out;
						$payment3 = $rec;
					}
					
					$total += $payment;
					$total2 += $payment2;
					$total3 += $payment3;
				?>
				<tr>
					<td><?php echo $ctr."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($get_q['date'])); ?></td>
					<td><?php echo $get_c['name']; ?></td>
					<td><?php echo $get_q['receipt_no']; ?></td>
					<td><?php if($payment != '') { echo "RM ".number_format($payment, '2', '.', ''); } ?></td>
					<td><?php if($payment2 != '') { echo "RM ".number_format($payment2, '2', '.', ''); } ?></td>
					<td><?php if($payment3 != '') { echo "RM ".number_format($payment3, '2', '.', ''); } ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php } 
				
				
				$c = $ctr;
				$total4 = 0;
				$payment4 = 0;
				$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND date LIKE '%".$date."%' AND type = 'EXPENSES' ORDER BY id ASC");
				while($q = mysql_fetch_assoc($sql2))
				{
				$c++;
				$payment4 = $q['amount'];
				?>
				<tr>
					<td><?php echo $c."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($q['date'])); ?></td>
					<td><?php echo "EXP - ".$q['transaction']; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><?php if($payment4 != '') { echo "RM ".number_format($payment4, '2', '.', ''); } ?></td>
			  	</tr>
				<?php $total4 += $payment4;}  ?>
			 	<?php $cf2 = $total + $total3 - $total2 - $total4; 
				
				?>
				<tr>
					<td colspan="4" align="right"><strong>Total</strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total2, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total3, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total4, '2', '.', ''); ?></strong></td>
				</tr>
				<tr>
					<td colspan="4" align="right"><strong>Package Total</strong></td>
					<td style="background:#EFEFEF; border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($cf2, '2', '.', ''); ?></strong></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
		</table>		</td>
	</tr>
    <?php 
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme != 'SKIM S' ORDER BY scheme DESC");
	$ctr1 = 0;
    while($get_p = mysql_fetch_assoc($package_q)){
	$ctr1++;
	?>
    <tr>
    	<td><?php echo $get_p['scheme']; ?></td>
	</tr>
	<tr>
		<td>
			<table id="list_table" width="100%">
				<tr>
					<th width="50">No.</th>
					<th>Date</th>
					<th>Customer's Name</th>
					<th width="100">Receipt No. </th>
					<th width="100">In</th>
					<th width="100">Out</th>
					<th width="100">Rec</th>
					<th width="100">Expenses</th>
				</tr>
				<?php
				$id = $get_p['id'];
				$sql = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' GROUP BY receipt_no ORDER BY id ASC");

				$month = $date;
				$cf = $get_p['initial_amount'];
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$id."' AND transaction = 'LOAN' AND date < '".$month."'");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				
				$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$id."' AND transaction = 'REC' AND date < '".$month."'");
				$get_pb2 = mysql_fetch_assoc($prevbal2_q);
				
				$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$id."' AND type = 'EXPENSES' AND date < '".$month."'");
				$get_pb3 = mysql_fetch_assoc($prevbal3_q);
				
				$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$id."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
				$get_pb4 = mysql_fetch_assoc($prevbal4_q);
				
				$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$id."' AND type = 'RECEIVED2' AND date < '".$month."'");
				$get_pb5 = mysql_fetch_assoc($prevbal5_q);
				
				$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$id."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
				$get_pb6 = mysql_fetch_assoc($prevbal6_q);
				
				$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$id."' AND transaction = 'CCM' AND date < '".$month."'");
				$get_pb7 = mysql_fetch_assoc($prevbal7_q);
				
				$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$id."' AND type = 'TRANSFER1' AND date < '".$month."'");
				$get_pb8 = mysql_fetch_assoc($prevbal8_q);
				
				$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$id."' AND type = 'TRANSFER2' AND date < '".$month."'");
				$get_pb9 = mysql_fetch_assoc($prevbal9_q);
				
				$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$id."' AND transaction = 'EXT' AND date < '".$month."'");
				$get_pb10 = mysql_fetch_assoc($prevbal10_q);
				
				$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$id."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
				$get_pb11 = mysql_fetch_assoc($prevbal11_q);
				
				
				$cf = $cf - $get_pb['amtOut'] + $get_pb2['amtIn'] - $get_pb3['amtEx'] + $get_pb4['amtKom'] + $get_pb5['amtOth'] + $get_pb6['amtKom2'] + $get_pb7['amtCCM'] - $get_pb8['amtTrans'] + $get_pb9['amtTrans2'] - $get_pb10['amtExt'] + $get_pb11['amtKInt'];
				?>
				<?php 
				$ctr = 0;
				$total = 0;
				$total2 = 0;
				$total3 = 0;
				while($get_q = mysql_fetch_assoc($sql))
				{
					$ctr++;
					$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
					$get_c = mysql_fetch_assoc($cust_q);
					
					$in = 0;
					$out = 0;
					$rec = 0;

					$payment = 0;
					$payment2 = 0;
					$payment3 = 0;

					$payment_q = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND date LIKE '%".$date."%' AND receipt_no = '".$get_q['receipt_no']."'");
					while($pay = mysql_fetch_assoc($payment_q))
					{
						if($pay['type'] == 'RECEIVED')
						{
							$in += $pay['amount'];
						}
						if($pay['type'] == 'PAY')
						{
							$out += $pay['amount'];
						}
						if($pay['type'] == 'COMMISSION')
						{
							$rec += $pay['amount'];
						}
						
						
						$payment = $in;
						$payment2 = $out;
						$payment3 = $rec;
					}
					
					$total += $payment;
					$total2 += $payment2;
					$total3 += $payment3;
				?>
				<tr>
					<td><?php echo $ctr."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($get_q['date'])); ?></td>
					<td><?php echo $get_c['name']; ?></td>
					<td><?php echo $get_q['receipt_no']; ?></td>
					<td><?php if($payment != '') { echo "RM ".number_format($payment, '2', '.', ''); } ?></td>
					<td><?php if($payment2 != '') { echo "RM ".number_format($payment2, '2', '.', ''); } ?></td>
					<td><?php if($payment3 != '') { echo "RM ".number_format($payment3, '2', '.', ''); } ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php } 
				
				
				$c = $ctr;
				$total4 = 0;
				$payment4 = 0;
				$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND date LIKE '%".$date."%' AND type = 'EXPENSES' ORDER BY id ASC");
				while($q = mysql_fetch_assoc($sql2))
				{
				$c++;
				$payment4 = $q['amount'];
				?>
				<tr>
					<td><?php echo $c."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($q['date'])); ?></td>
					<td><?php echo "EXP - ".$q['transaction']; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><?php if($payment4 != '') { echo "RM ".number_format($payment4, '2', '.', ''); } ?></td>
			  	</tr>
				<?php $total4 += $payment4;}  ?>
			 	<?php $cf2 = $total + $total3 - $total2 - $total4; 
				
				?>
				<tr>
					<td colspan="4" align="right"><strong>Total</strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total2, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total3, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total4, '2', '.', ''); ?></strong></td>
				</tr>
				<tr>
					<td colspan="4" align="right"><strong>Package Total</strong></td>
					<td style="background:#EFEFEF; border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($cf2, '2', '.', ''); ?></strong></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
		</table>		</td>
	</tr>
    <?php $officetot2 = $total + $total3 - $total2 - $total4;
	if($ctr1 == 1)
	{
		$a = $officetot + $officetot2;
	}else
	{
		$a = $officetot2;
	}
	$officetot3 += $a;
	
	}
	 ?>
    <!--<tr>
    	<td><?php echo ($ctr+1)."."; ?></td>
        <td><a href="package_dailysum.php">SUMMARY</a></td>
	</tr>-->
    <tr>
      <td>
	  	<table style="border-collapse:collapse">
			<tr style="background:#CCCCCC; border-bottom:solid thin #000; border-top:solid thin #000">
				<td><strong>Total In Office:</strong></td>
				<td><strong><?php echo number_format($officetot3, '2'); ?></strong></td>
			</tr>
		</table>
	  </td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
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
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
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
$('#date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
</script>
</body>
</html>