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
	$yesterdaydate = date('Y-m-d', strtotime($date .' -1 day'));
	$tmr = date('Y-m-d', strtotime($date .' +1 day'));
}else if($_SESSION['date'] != '')
{
	$date = $_SESSION['date'];
}
else
{
	$date = date('Y-m-d');
	$yesterdaydate = date('Y-m-d', strtotime($date .' -1 day'));
	$tmr = date('Y-m-d', strtotime($date .' +1 day'));
}



$calc_q = mysql_query("SELECT * FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND date < '".$date."' AND date >= '".$cio['date']."'");
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
#print_btn
{
	background:url(../img/print-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#print_btn:hover
{
	background:url(../img/print-btn-roll.jpg);
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

@media print {	
	.subnav { display:none; }
	#hideprint { display:none; }
	#message { display:none; }
	#list_table
	{
		width:1000px;
	}
}
</style>

<center>
<table width="1280" id="hideprint">
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
					<td><button id="print_btn" onClick="window.print()"></button></td>
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
<table width="1280" id="list_table">
	<?php 
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM S'");
	$ctr1 = 0;
    while($get_p = mysql_fetch_assoc($package_q)){
	$ctr1++;	
		//calculate balance 1 for each package
			$balI_q = mysql_query("SELECT * FROM bal1_cf WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."'");
			$balI = mysql_fetch_assoc($balI_q);
			$baldate = date('Y-m-d', strtotime($balI['date']));
			if($date < $baldate)
			{
				$bal1cf1 = 0;
				$bal1cf = 0;
			}
			if($date >= $baldate)
			{
				$bal1cf1 = $balI['amount'];
				
				$bal1_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec FROM balance_transaction WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND date BETWEEN '".$baldate."' AND '".$date."' ");
				$bal1 = mysql_fetch_assoc($bal1_q);
				
				$bal1cf = $bal1cf1 - $bal1['sumLoan'] + $bal1['sumRec'];
			}
		//end of balance 1	
		
		
		//cashbook initial amount
		$csh_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."'");
		$csh = mysql_fetch_assoc($csh_q);
		$cfy = $csh['amount'];
		$prevbal_qy = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'LOAN' AND date < '".$date."'");
		$get_pby = mysql_fetch_assoc($prevbal_qy);
		
		$prevbal2_qy = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'REC' AND date < '".$date."'");
		$get_pb2y = mysql_fetch_assoc($prevbal2_qy);
		
		$prevbal3_qy = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'EXPENSES' AND date < '".$date."'");
		$get_pb3y = mysql_fetch_assoc($prevbal3_qy);
		
		$prevbal4_qy = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'KOMISYEN' AND date < '".$date."'");
		$get_pb4y = mysql_fetch_assoc($prevbal4_qy);
		
		$prevbal5_qy = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'RECEIVED2' AND date < '".$date."'");
		$get_pb5y = mysql_fetch_assoc($prevbal5_qy);
		
		$prevbal6_qy = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'KOMISYEN2' AND date < '".$date."'");
		$get_pb6y = mysql_fetch_assoc($prevbal6_qy);
		
		$prevbal7_qy = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'CCM' AND date < '".$date."'");
		$get_pb7y = mysql_fetch_assoc($prevbal7_qy);
		
		$prevbal8_qy = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'TRANSFER1' AND date < '".$date."'");
		$get_pb8y = mysql_fetch_assoc($prevbal8_qy);
		
		$prevbal9_qy = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'TRANSFER2' AND date < '".$date."'");
		$get_pb9y = mysql_fetch_assoc($prevbal9_qy);
		
		$prevbal10_qy = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'EXT' AND date < '".$date."'");
		$get_pb10y = mysql_fetch_assoc($prevbal10_qy);
		
		$prevbal11_qy = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$date."'");
		$get_pb11y = mysql_fetch_assoc($prevbal11_qy);
		
		
		$cfy = $cfy - $get_pby['amtOut'] + $get_pb2y['amtIn'] - $get_pb3y['amtEx'] + $get_pb4y['amtKom'] + $get_pb5y['amtOth'] + $get_pb6y['amtKom2'] + $get_pb7y['amtCCM'] - $get_pb8y['amtTrans'] + $get_pb9y['amtTrans2'] - $get_pb10y['amtExt'] + $get_pb11y['amtKInt'];
	?>
    <tr>
    	<td><?php echo $get_p['scheme']; ?></td>
	</tr>
	<tr>
    	<td>
			<table>
				<tr>
					<td width="80"><?php echo date('d-m-Y', strtotime($yesterdaydate)); ?></td>
					<td width="150">"<?php echo $get_p['scheme']; ?>"</td>
					<td width="70">Cash</td>
					<td><strong>RM <?php echo number_format($cfy, '2'); ?></strong></td>
				</tr>
			</table>		
		</td>
	</tr>
	<?php
		$check = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' GROUP BY receipt_no ORDER BY id ASC");
		$sql_exp = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND (type = 'EXPENSES' OR type LIKE '%TRANSFER%' ) ORDER BY id ASC");
		$get_sqlexp = mysql_fetch_assoc($sql_exp);
		$getcheck = mysql_fetch_assoc($check);
	
		if($getcheck || $get_sqlexp)
		{
	?>
	<tr>
		<td>
			<table id="list_table" width="100%">
				<tr>
					<th width="50">No.</th>
					<th width="100">Date</th>
					<th>Customer's Name</th>
					<th width="100">Cust Code </th>
					<th width="100">Receipt No. </th>
					<th width="100">In</th>
					<th width="100">Out</th>
					<th width="100">Rec</th>
					<th width="100">Expenses</th>
				</tr>
				<?php
				$id = $get_p['id'];
				$sql = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' and type NOT LIKE '%TRANSFER%' GROUP BY customer_id ORDER BY id ASC");

				$month = $date;
				$cf = $get_p['initial_amount'];
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date < '".$month."'");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				
				$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'REC' AND date < '".$month."'");
				$get_pb2 = mysql_fetch_assoc($prevbal2_q);
				
				$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'EXPENSES' AND date < '".$month."'");
				$get_pb3 = mysql_fetch_assoc($prevbal3_q);
				
				$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
				$get_pb4 = mysql_fetch_assoc($prevbal4_q);
				
				$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'RECEIVED2' AND date < '".$month."'");
				$get_pb5 = mysql_fetch_assoc($prevbal5_q);
				
				$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
				$get_pb6 = mysql_fetch_assoc($prevbal6_q);
				
				$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'CCM' AND date < '".$month."'");
				$get_pb7 = mysql_fetch_assoc($prevbal7_q);
				
				$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER1' AND date < '".$month."'");
				$get_pb8 = mysql_fetch_assoc($prevbal8_q);
				
				$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER2' AND date < '".$month."'");
				$get_pb9 = mysql_fetch_assoc($prevbal9_q);
				
				$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'EXT' AND date < '".$month."'");
				$get_pb10 = mysql_fetch_assoc($prevbal10_q);
				


				$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
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
							
					$in = 0;
					$out = 0;
					$rec = 0;

					$payment = 0;
					$payment2 = 0;
					$payment3 = 0;

					$payment_q = mysql_query("SELECT * FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND date LIKE '%".$date."%' AND receipt_no = '".$get_q['receipt_no']."' AND customer_id ='".$get['customer_id']."'");
					while($pay = mysql_fetch_assoc($payment_q))
					{
						if($pay['type'] == 'RECEIVED')
						{
							$in += $pay['amount'];
						}
						if($pay['type'] == 'RECEIVED2')
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
						
						// 01(15122016) - store customer id
						if($pay['customer_id'] != 0)
						{
							$customerID = $pay['customer_id'];
						}
						// end of 01(15122016)
					}
					
																										   // 02(15122016) - add OR condition
					$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."' OR id = '".$customerID."'");
					$get_c = mysql_fetch_assoc($cust_q);
					
					$total += $payment;
					$total2 += $payment2;
					$total3 += $payment3;
				?>
				<tr>
					<td><?php echo $ctr."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($get_q['date'])); ?></td>
					<td><?php if($get_q['type'] == 'RECEIVED2') { echo $get_q['transaction']; } else{ echo $get_c['name']; } ?></td>
					<td><?php if($get_q['type'] != 'RECEIVED2') { echo strtoupper($get_c['customercode2']); } ?></td>
					<td>
						<?php if($get_q['type'] != 'RECEIVED2') { ?>
							<a href="history.php?id=<?php echo $get_c['id']; ?>" rel="shadowbox">
								<?php 
									if($get_q['receipt_no']!="")
									{echo $get_q['receipt_no'];}
									else{echo $get_q['code'];} // 03(15122016) - incase receipt_no is null ?>
							</a><?php } ?></td>
					<td><?php if($in != '') { echo "RM ".number_format($in, '2', '.', ''); } ?></td>
					<td><?php if($out != '') { echo "RM ".number_format($out, '2', '.', ''); } ?></td>
					<td><?php if($rec != '') { echo "RM ".number_format($rec, '2', '.', ''); } ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php } 
				
				
				$c = $ctr;
				$total4 = 0;
				$payment4 = 0;
				$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type = 'EXPENSES' ORDER BY id ASC");
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
					<td>&nbsp;</td>
					<td><?php if($payment4 != '') { echo "RM ".number_format($payment4, '2', '.', ''); } ?></td>
			  	</tr>
				<?php $total4 += $payment4;}  ?>
			 	<?php  
				$c = $ctr;
				$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type LIKE '%TRANSFER%' ORDER BY id ASC");
				
						
				while($q = mysql_fetch_assoc($sql2))
				{
				$c++;
				
					if($q['type'] == 'TRANSFER2')
					{
						$in1 = $q['amount'];
						$out1 = 0;
					}
					if($q['type'] == 'TRANSFER1')
					{
						$out1 = $q['amount'];
						$in1 = 0;
					}
					$payment = $in1;
					$payment2 = $out1;

					
					$total += $payment;
					$total2 += $payment2;
				?>
				<tr>
					<td><?php echo $c."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($q['date'])); ?></td>
					<td><?php echo $q['transaction']; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><?php if($payment != '') { echo "RM ".number_format($payment, '2', '.', ''); } ?></td>
					<td><?php if($payment2 != '') { echo "RM ".number_format($payment2, '2', '.', ''); } ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  	</tr>
				<?php }  ?>
			 	<?php $cf2 = $total + $total3 - $total2 - $total4; 
				?>
				<tr>
					<td colspan="5" align="right"><strong>Total</strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total2, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total3, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total4, '2', '.', ''); ?></strong></td>
				</tr>
				<tr>
					<td colspan="5" align="right"><strong>Package Total</strong></td>
					<td style="background:#EFEFEF; border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($cf2, '2', '.', ''); ?></strong></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>		</td>
	</tr>
	<tr>
    	<td>
			<table>
				<tr>
					<td width="80"></td>
					<td width="150">"<?php echo $get_p['scheme']; ?>"</td>
					<td width="70">Cash</td>
					<td><strong>RM <?php $cf3 = $cfy + $cf2; echo number_format($cf3, '2'); ?></strong></td>
				</tr>

			</table>		</td>
	</tr>
	<?php
		$cfskims = $cf3; 
	}else
	{

		$id = $get_p['id'];
		
	
		$sql = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' GROUP BY receipt_no ORDER BY id ASC");

		$month = $date;
		$cf = $get_p['initial_amount'];
		$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'LOAN' AND date < '".$month."'");
		$get_pb = mysql_fetch_assoc($prevbal_q);
		
		$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'REC' AND date < '".$month."'");
		$get_pb2 = mysql_fetch_assoc($prevbal2_q);
		
		$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'EXPENSES' AND date < '".$month."'");
		$get_pb3 = mysql_fetch_assoc($prevbal3_q);
		
		$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
		$get_pb4 = mysql_fetch_assoc($prevbal4_q);
		
		$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'RECEIVED2' AND date < '".$month."'");
		$get_pb5 = mysql_fetch_assoc($prevbal5_q);
		
		$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
		$get_pb6 = mysql_fetch_assoc($prevbal6_q);
		
		$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'CCM' AND date < '".$month."'");
		$get_pb7 = mysql_fetch_assoc($prevbal7_q);
		
		$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER1' AND date < '".$month."'");
		$get_pb8 = mysql_fetch_assoc($prevbal8_q);
		
		$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER2' AND date < '".$month."'");
		$get_pb9 = mysql_fetch_assoc($prevbal9_q);
		
		$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'EXT' AND date < '".$month."'");
		$get_pb10 = mysql_fetch_assoc($prevbal10_q);
		
		$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
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

			$payment_q = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND receipt_no = '".$get_q['receipt_no']."'");
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
		} 
		
		
		$c = $ctr;
		$total4 = 0;
		$payment4 = 0;
		$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type = 'EXPENSES' ORDER BY id ASC");
		while($q = mysql_fetch_assoc($sql2))
		{
			$c++;
			$payment4 = $q['amount'];
			
			$total4 += $payment4;
		} 
		$cf2 = $total + $total3 - $total2 - $total4; 
		
		$cfskims = $cfy; 
		
		?>
				
	<?php
	}
	?>
	<tr>
    	<td style="border-bottom:thin solid #000">
			<table>
				<tr>
					<td width="80">&nbsp;</td>
					<td width="150">&nbsp;</td>
					<td width="70">Balance1</td>
					<td><strong>RM <?php echo number_format($bal1cf, '2'); ?></strong></td>
				</tr>
			</table>		</td>
	</tr>
    <?php 

	$officetot2 = $total + $total3 - $total2 - $total4;
	if($ctr1 == 1)
	{
		$a = $officetot + $officetot2;
	}else
	{
		$a = $officetot2;
	}
	$officetot3 += $a;
	
	}
	
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme != 'SKIM S' AND scheme != 'SKIM KUTU' AND scheme != 'KUTU OFFICE' ORDER BY scheme DESC");
    while($get_p = mysql_fetch_assoc($package_q))
	{
		//check cashbook cf
		$cbcheck_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."'");
		$cbcheck = mysql_num_rows($cbcheck_q);
		if($cbcheck != 0)
		{
		$ctr1++;
		
		//calculate balance 1 for each package
			$balI_q = mysql_query("SELECT * FROM bal1_cf WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."'");
			$balI = mysql_fetch_assoc($balI_q);
			$baldate = date('Y-m-d', strtotime($balI['date']));
			if($date < $baldate)
			{
				$bal1cf1 = 0;
				$bal1cf = 0;
			}
			if($date >= $baldate)
			{
				$bal1cf1 = $balI['amount'];
				//echo $baldate." ".$date;
				/*$bal1_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec FROM balance_transaction WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND date BETWEEN '".$baldate."' AND '".$date."' ");
				$bal1 = mysql_fetch_assoc($bal1_q);
				
				$bal1cf = $bal1['sumRec'] + $bal1cf1 - $bal1['sumLoan'] ;
				*/
				
				
				$bal1_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec FROM balance_transaction WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND date BETWEEN '".$baldate."' AND '".$date."' ");
				$bal1 = mysql_fetch_assoc($bal1_q);
				
				$bal1cf = $bal1cf1 - $bal1['sumLoan'] + $bal1['sumRec'];
				/*if($get_p['id'] == '6')
				{
					echo $bal1cf1."///".$bal1['sumLoan']."///".$bal1['sumRec'];
				} */
			}
		//end of balance 1	
		//cashbook initial amount
		$csh_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."'");
		$csh = mysql_fetch_assoc($csh_q);
		$cfy = $csh['amount'];
		
		//$cfy = $get_p['initial_amount'];
		$prevbal_qy = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'LOAN' AND date < '".$date."'");
		$get_pby = mysql_fetch_assoc($prevbal_qy);
		
		$prevbal2_qy = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'REC' AND date < '".$date."'");
		$get_pb2y = mysql_fetch_assoc($prevbal2_qy);
		
		$prevbal3_qy = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'EXPENSES' AND date < '".$date."'");
		$get_pb3y = mysql_fetch_assoc($prevbal3_qy);
		
		$prevbal4_qy = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'KOMISYEN' AND date < '".$date."'");
		$get_pb4y = mysql_fetch_assoc($prevbal4_qy);
		
		$prevbal5_qy = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'RECEIVED2' AND date < '".$date."'");
		$get_pb5y = mysql_fetch_assoc($prevbal5_qy);
		
		$prevbal6_qy = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'KOMISYEN2' AND date < '".$date."'");
		$get_pb6y = mysql_fetch_assoc($prevbal6_qy);
		
		$prevbal7_qy = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'CCM' AND date < '".$date."'");
		$get_pb7y = mysql_fetch_assoc($prevbal7_qy);
		
		$prevbal8_qy = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'TRANSFER1' AND date < '".$date."'");
		$get_pb8y = mysql_fetch_assoc($prevbal8_qy);
		
		$prevbal9_qy = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND type = 'TRANSFER2' AND date < '".$date."'");
		$get_pb9y = mysql_fetch_assoc($prevbal9_qy);
		
		$prevbal10_qy = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'EXT' AND date < '".$date."'");
		$get_pb10y = mysql_fetch_assoc($prevbal10_qy);
		
		$prevbal11_qy = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$date."'");
		$get_pb11y = mysql_fetch_assoc($prevbal11_qy);
		
		$prevbal12_qy = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$get_p['id']."' AND transaction = 'INT' AND type = 'RECEIVED' AND date < '".$date."'");
		$get_pb12y = mysql_fetch_assoc($prevbal12_qy);
		
		$cfy = $cfy - $get_pby['amtOut'] + $get_pb2y['amtIn'] - $get_pb3y['amtEx'] + $get_pb4y['amtKom'] + $get_pb5y['amtOth'] + $get_pb6y['amtKom2'] + $get_pb7y['amtCCM'] - $get_pb8y['amtTrans'] + $get_pb9y['amtTrans2'] - $get_pb10y['amtExt'] + $get_pb11y['amtKInt'] + $get_pb12y['amtKInt'];
	?>
    <tr>
    	<td><?php echo $get_p['scheme']; ?></td>
	</tr>
	<tr>
    	<td>
			<table>
				<tr>
					<td width="80"><?php echo date('d-m-Y', strtotime($yesterdaydate)); ?></td>
					<td width="150">"<?php echo $get_p['scheme']; ?>"</td>
					<td width="70">Cash</td>
					<td><strong>RM <?php echo number_format($cfy, '2'); ?></strong></td>
				</tr>
			</table>		
		</td>
	</tr>
	<?php
	
		
		
		$check = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' and type NOT LIKE '%TRANSFER%' GROUP BY receipt_no ORDER BY id ASC");
		$getcheck = mysql_fetch_assoc($check);
		
		$sql_exp = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND (type = 'EXPENSES' OR type LIKE '%TRANSFER%' ) ORDER BY id ASC");
		$get_sqlexp = mysql_fetch_assoc($sql_exp);
	
		if($getcheck || $get_sqlexp)
		{
	?>
	<tr>
		<td>
			<table id="list_table" width="100%">
				<tr>
					<th width="50">No.</th>
					<th width="100">Date</th>
					<th>Customer's Name</th>
					<th width="100">Cust Code </th>
					<th width="100">Receipt No. </th>
					<th width="100">In</th>
					<th width="100">Out</th>
					<th width="100">Rec</th>
					<th width="100">Expenses</th>
				</tr>
				<?php
				$id = $get_p['id'];
				$sql = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' and type NOT LIKE '%TRANSFER%' GROUP BY receipt_no ORDER BY id ASC");

				$month = $date;
				$cf = $get_p['initial_amount'];
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date < '".$month."'");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				
				$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'REC' AND date < '".$month."'");
				$get_pb2 = mysql_fetch_assoc($prevbal2_q);
				
				$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'EXPENSES' AND date < '".$month."'");
				$get_pb3 = mysql_fetch_assoc($prevbal3_q);
				
				$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
				$get_pb4 = mysql_fetch_assoc($prevbal4_q);
				
				$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'RECEIVED2' AND date < '".$month."'");
				$get_pb5 = mysql_fetch_assoc($prevbal5_q);
				
				$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
				$get_pb6 = mysql_fetch_assoc($prevbal6_q);
				
				$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'CCM' AND date < '".$month."'");
				$get_pb7 = mysql_fetch_assoc($prevbal7_q);
				
				$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER1' AND date < '".$month."'");
				$get_pb8 = mysql_fetch_assoc($prevbal8_q);
				
				$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER2' AND date < '".$month."'");
				$get_pb9 = mysql_fetch_assoc($prevbal9_q);
				
				$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'EXT' AND date < '".$month."'");
				$get_pb10 = mysql_fetch_assoc($prevbal10_q);
				
				$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
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

					$payment_q = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND receipt_no = '".$get_q['receipt_no']."'");
					while($pay = mysql_fetch_assoc($payment_q))
					{
						if($pay['type'] == 'RECEIVED')
						{
							$in += $pay['amount'];
						}
						if($pay['type'] == 'RECEIVED2')
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
					<td><?php if($get_q['type'] == 'RECEIVED2') { echo $get_q['transaction']; } else { echo $get_c['name']; } ?></td>
					<td><?php if($get_q['type'] != 'RECEIVED2') { echo strtoupper($get_c['customercode2']); } ?></td>
					<td><?php if($get_q['type'] != 'RECEIVED2') { ?><a href="history.php?id=<?php echo $get_c['id']; ?>" rel="shadowbox"><?php echo $get_q['receipt_no']; ?></a><?php } ?></td>
					<td><?php if($payment != '') { echo "RM ".number_format($payment, '2', '.', ''); } ?></td>
					<td><?php if($payment2 != '') { echo "RM ".number_format($payment2, '2', '.', ''); } ?></td>
					<td><?php if($payment3 != '') { echo "RM ".number_format($payment3, '2', '.', ''); } ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php } 
				
				
				$c = $ctr;
				$total4 = 0;
				$payment4 = 0;
				$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type = 'EXPENSES' ORDER BY id ASC");
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
					<td>&nbsp;</td>
					<td><?php if($payment4 != '') { echo "RM ".number_format($payment4, '2', '.', ''); } ?></td>
			  	</tr>
				<?php $total4 += $payment4;}  ?>
				<?php  
				$c = $ctr;
				$sql2 = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND type LIKE '%TRANSFER%' ORDER BY id ASC");
				
						
				while($q = mysql_fetch_assoc($sql2))
				{
				$c++;
				
					if($q['type'] == 'TRANSFER2')
					{
						$in1 = $q['amount'];
						$out1 = 0;
					}
					if($q['type'] == 'TRANSFER1')
					{
						$out1 = $q['amount'];
						$in1 = 0;
					}
					$payment = $in1;
					$payment2 = $out1;

					
					$total += $payment;
					$total2 += $payment2;
				?>
				<tr>
					<td><?php echo $c."."; ?></td>
					<td><?php echo date('d-m-Y', strtotime($q['date'])); ?></td>
					<td><?php echo $q['transaction']; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><?php if($payment != '') { echo "RM ".number_format($payment, '2', '.', ''); } ?></td>
					<td><?php if($payment2 != '') { echo "RM ".number_format($payment2, '2', '.', ''); } ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  	</tr>
				<?php }  ?>
			 	<?php $cf2 = $total + $total3 - $total2 - $total4; 
				
				?>
				<tr>
					<td colspan="5" align="right"><strong>Total</strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total2, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total3, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total4, '2', '.', ''); ?></strong></td>
				</tr>
				<tr>
					<td colspan="5" align="right"><strong>Package Total</strong></td>
					<td style="background:#EFEFEF; border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($cf2, '2', '.', ''); ?></strong></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>		</td>
	</tr>
	<tr>
    	<td>
			<table>
				<tr>
					<td width="80"></td>
					<td width="150">"<?php echo $get_p['scheme']; ?>"</td>
					<td width="70">Cash</td>
					<td><strong>RM <?php $cf3 = $cfy + $cf2; echo number_format($cf3, '2'); ?></strong></td>
				</tr>
			</table>		</td>
	</tr>
	<?php 
		$cfother += $cf3;
	}else
	{
		$cfother += $cfy;
		$id = $get_p['id'];
		$sql = mysql_query("SELECT * FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND date LIKE '%".$date."%' AND type != 'EXPENSES' GROUP BY receipt_no ORDER BY id ASC");

		$month = $date;
		$cf = $get_p['initial_amount'];
		$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'LOAN' AND date < '".$month."'");
		$get_pb = mysql_fetch_assoc($prevbal_q);
		
		$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'REC' AND date < '".$month."'");
		$get_pb2 = mysql_fetch_assoc($prevbal2_q);
		
		$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'EXPENSES' AND date < '".$month."'");
		$get_pb3 = mysql_fetch_assoc($prevbal3_q);
		
		$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
		$get_pb4 = mysql_fetch_assoc($prevbal4_q);
		
		$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'RECEIVED2' AND date < '".$month."'");
		$get_pb5 = mysql_fetch_assoc($prevbal5_q);
		
		$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
		$get_pb6 = mysql_fetch_assoc($prevbal6_q);
		
		$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'CCM' AND date < '".$month."'");
		$get_pb7 = mysql_fetch_assoc($prevbal7_q);
		
		$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER1' AND date < '".$month."'");
		$get_pb8 = mysql_fetch_assoc($prevbal8_q);
		
		$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND type = 'TRANSFER2' AND date < '".$month."'");
		$get_pb9 = mysql_fetch_assoc($prevbal9_q);
		
		$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'EXT' AND date < '".$month."'");
		$get_pb10 = mysql_fetch_assoc($prevbal10_q);
		
		$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
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

			$payment_q = mysql_query("SELECT * FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND date LIKE '%".$date."%' AND receipt_no = '".$get_q['receipt_no']."'");
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
		} 
		
		
		$c = $ctr;
		$total4 = 0;
		$payment4 = 0;
		$sql2 = mysql_query("SELECT * FROM cashbook WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND date LIKE '%".$date."%' AND type = 'EXPENSES' ORDER BY id ASC");
		while($q = mysql_fetch_assoc($sql2))
		{
			$c++;
			$payment4 = $q['amount'];
			
			$total4 += $payment4;
		} 
		$cf2 = $total + $total3 - $total2 - $total4; 
		
		?>
				
	<?php
	}
	?>
	<tr>
    	<td style="border-bottom:thin solid #000">
			<table>
				<tr>
					<td width="80">&nbsp;</td>
					<td width="150">&nbsp;</td>
					<td width="70">Balance1</td>
					<td><strong>RM <?php echo number_format($bal1cf, '2'); ?></strong></td>
				</tr>
			</table>		
		</td>
	</tr>
	
	
    <?php $officetot2 = $total + $total3 - $total2 - $total4;
	$officK = 0;
	if($ctr1 == 1)
	{
		$a = $officetot + $officetot2;
	}else
	{
		$a = $officetot2;
	}
	$officetot3 += $a;
	
	}
	}
	?>
	<!-- skim kutu -->
	<?php $skt_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM KUTU'"); 
	$skt = mysql_fetch_assoc($skt_q);
	
	if($skt)
	{
		$skimKT_q = mysql_query("SELECT SUM(inamt) AS sumInK, SUM(outamt) AS sumOutK FROM cashbook_skimkutu WHERE branch_id = '".$_SESSION['login_branchid']."' AND date <= '".$date."'");
		$skimKT = mysql_fetch_assoc($skimKT_q);
		$cfskimKT = $skimKT['sumInK'] - $skimKT['sumOutK'];
		
		$officetot3 += $cfskimKT;
		
		$skimKT_q1 = mysql_query("SELECT * FROM cashbook_skimkutu WHERE branch_id = '".$_SESSION['login_branchid']."' AND date = '".$date."'");
		$skrow = mysql_num_rows($skimKT_q1);
	?>
	
	<tr>
		<td>SKIM KUTU<td>
	</tr>
	<?php
	//bal1 kutu
	$skutu_q = mysql_query("SELECT * FROM skim_kutu WHERE branch_id = '".$_SESSION['login_branchid']."' AND date <= '".$date."'");
	while($skutu = mysql_fetch_assoc($skutu_q))
	{
		$kbal_q = mysql_query("SELECT SUM(amount) AS sumKpayment FROM skimkutu_payment WHERE skim_id = '".$skutu['id']."' AND payment_date <= '".$date."'");
		$kutubalance = mysql_fetch_assoc($kbal_q);
		$bal1kutu1 = $skutu['stock'] - $kutubalance['sumKpayment'];
		
		
		$bal1Kutu += $bal1kutu1;
	}
//end of bal1 kutu
	if($skrow != 0)
	{
	
	?>
	<tr>
		<td>
			<table id="list_table" width="100%">
				<tr>
					<th width="50">No.</th>
					<th>Date</th>
					<th>Description</th>
					<th width="100">In</th>
					<th width="100">Out</th>
				</tr>
				<?php
				
				
				$kctr = 0;
				$kutuin = 0;
				$kutuout = 0;
				$totalkutu = 0;
				while($kt = mysql_fetch_assoc($skimKT_q1))
				{
					$kctr++;
					
					$kutuin += $kt['inamt'];
					$kutuout += $kt['outamt'];
					$totalkutu = $kutuin - $kutuout;
				?>
				<tr>
					<td><?php echo $kctr; ?></td>
					<td><?php echo date('d-m-Y', strtotime($kt['date'])); ?></td>
					<td><?php echo $kt['description']; ?></td>
					<td><?php if($kt['inamt'] != 0) { echo $kt['inamt']; } ?></td>
					<td><?php if($kt['outamt'] != 0) {echo $kt['outamt']; } ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="3" align="right"><strong>Total</strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($kutuin, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($kutuout, '2', '.', ''); ?></strong></td>
				</tr>
				<tr>
					<td colspan="3" align="right"><strong>Total Package</strong></td>
					<td style="background:#EFEFEF; border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($totalkutu, '2', '.', ''); ?></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
			<table>
				<tr>
					<td width="80"><?php echo date('d-m-Y', strtotime($date)); ?></td>
					<td width="150">"SKIM KUTU"</td>
					<td width="70">Cash</td>
					<td><strong>RM <?php echo number_format($cfskimKT, '2'); ?></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style = "border-bottom:thin solid #000">
			<table>
				<tr>
					<td width="80">&nbsp;</td>
					<td width="150">&nbsp;</td>
					<td width="70">Balance1</td>
					<td><strong>RM <?php echo number_format($bal1Kutu, '2'); ?></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<?php
	}
	/////////////////////////////////

?>
	<!-- kutu office-->
	<?php $sko_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'KUTU OFFICE'"); 
	$sko = mysql_fetch_assoc($sko_q);
	
	if($sko)
	{
		$cb_x_dateq = mysql_query("SELECT SUM(inamt) AS sumIn, SUM(outamt) AS sumOut FROM cashbook_kutuoffice WHERE branch_id = '".$_SESSION['login_branchid']."' AND date <= '".$date."'");
		$cb_x_date = mysql_fetch_assoc($cb_x_dateq);
		$bal = $cb_x_date['sumIn'] - $cb_x_date['sumOut'];
		
		//$officetot3 += $cfskimKT;
		
		$cb_exist_dateq = mysql_query("SELECT * FROM cashbook_kutuoffice WHERE branch_id = '".$_SESSION['login_branchid']."' AND date = '".$date."'");
		$date_row = mysql_num_rows($cb_exist_dateq);
	?>
	
	<tr>
		<td>KUTU OFFICE<td>
	</tr>
	<?php
	//bal1 kutu
	$koq = mysql_query("SELECT * FROM kutu_office WHERE branch_id = '".$_SESSION['login_branchid']."' AND date <= '".$date."'");
	while($ko = mysql_fetch_assoc($koq))
	{
		$kopq = mysql_query("SELECT amount FROM kutuoffice_payment WHERE skim_id = '".$ko['id']."' AND payment_date <= '".$date."' ORDER BY month DESC");
		$total_amount = mysql_fetch_assoc($kopq);
		$bal2 += $total_amount['amount'];
	}
//end of bal1 kutu
	if($date_row != 0)
	{
	
	?>
	<tr>
		<td>
			<table id="list_table" width="100%">
				<tr>
					<th width="50">No.</th>
					<th>Date</th>
					<th>Description</th>
					<th width="100">In</th>
					<th width="100">Out</th>
				</tr>
				<?php
				
				
				$ko_ctr = 0;
				$ko_in = 0;
				$ko_out = 0;
				$totalko = 0;
				while($cb_exist_date = mysql_fetch_assoc($cb_exist_dateq))
				{
					$ko_ctr++;
					
					$ko_in += $cb_exist_date['inamt'];
					$ko_out += $cb_exist_date['outamt'];
					$totalko = $ko_in - $ko_out;
				?>
				<tr>
					<td><?php echo $ko_ctr; ?></td>
					<td><?php echo date('d-m-Y', strtotime($cb_exist_date['date'])); ?></td>
					<td><?php echo $cb_exist_date['description']; ?></td>
					<td><?php if($cb_exist_date['inamt'] != 0) { echo $cb_exist_date['inamt']; } ?></td>
					<td><?php if($cb_exist_date['outamt'] != 0) { echo $cb_exist_date['outamt']; } ?></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="3" align="right"><strong>Total</strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($ko_in, '2', '.', ''); ?></strong></td>
					<td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($ko_out, '2', '.', ''); ?></strong></td>
				</tr>
				<tr>
					<td colspan="3" align="right"><strong>Total Package</strong></td>
					<td style="background:#EFEFEF; border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($totalko, '2', '.', ''); ?></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
			<table>
				<tr>
					<td width="80"><?php echo date('d-m-Y', strtotime($date)); ?></td>
					<td width="150">"KUTU OFFICE"</td>
					<td width="70">Cash</td>
					<td><strong>RM <?php echo number_format($bal, '2'); ?></strong></td>
				</tr>
				<tr>
					<td width="80">&nbsp;</td>
					<td width="150">&nbsp;</td>
					<td width="70">Balance1</td>
					<td><strong>RM <?php echo number_format($bal2, '2'); ?></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<?php
	}			
	/////////////////////////////////	?>
	<?php
	// totalcash
	$ttlcashinoffice = $cfskims + $cfskimKT + $cfother + $bal;

	?>
	<!-- end of skim kutu --> 
	<!--<tr>
    	<td><?php echo ($ctr+1)."."; ?></td>
        <td><a href="package_dailysum.php">SUMMARY</a></td>
	</tr>-->
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
	  	<table style="border-collapse:collapse">
			<tr style="background:#CCCCCC; border-bottom:solid thin #000; border-top:solid thin #000">
				<td><strong>Total In Office:</strong></td>
				<td><strong><?php echo number_format($ttlcashinoffice, '2'); ?></strong></td>
			</tr>
		</table>	  </td>
    </tr>
    <tr id="hideprint">
    	<td>&nbsp;</td>
    </tr>
    <tr id="hideprint">
    	<td align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr id="hideprint">
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

Shadowbox.init();
</script>
</body>
</html>