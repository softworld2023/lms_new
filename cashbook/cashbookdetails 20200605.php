<?php include('../include/page_header.php');

$package_id = $_GET['id'];
$month = $_GET['mth'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
$get_p = mysql_fetch_assoc($package_q);

$cbcf_q = mysql_query("SELECT * FROM cashbook_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."'");
$cbcf = mysql_fetch_assoc($cbcf_q);
$cf = $cbcf['amount'];
$cf_date = $cbcf['date'];

$nmth = $month.'-01'; 

$newmth = date('Y-m-d', strtotime($nmth));

$cfmonth = date('Y-m', strtotime(date($cf_date)));

$loan_q = mysql_query("SELECT * FROM cashbook WHERE 
						branch_id = '".$_SESSION['login_branchid']."' 
						AND package_id = '".$package_id."' 
						AND date LIKE '%".$month."%' 
						ORDER BY date ASC, id ASC");

$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date < '".$month."'");
$get_pb = mysql_fetch_assoc($prevbal_q);

$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'REC' AND date < '".$month."'");
$get_pb2 = mysql_fetch_assoc($prevbal2_q);

$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$package_id."' AND type = 'EXPENSES' AND branch_id = '".$_SESSION['login_branchid']."' AND date < '".$month."'");
$get_pb3 = mysql_fetch_assoc($prevbal3_q);

$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN' AND date < '".$month."'");
$get_pb4 = mysql_fetch_assoc($prevbal4_q);

$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND date < '".$month."'");
$get_pb5 = mysql_fetch_assoc($prevbal5_q);

$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN2' AND date < '".$month."'");
$get_pb6 = mysql_fetch_assoc($prevbal6_q);

$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'CCM' AND date < '".$month."'");
$get_pb7 = mysql_fetch_assoc($prevbal7_q);

$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER1' AND date < '".$month."'");
$get_pb8 = mysql_fetch_assoc($prevbal8_q);

$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER2' AND date < '".$month."'");
$get_pb9 = mysql_fetch_assoc($prevbal9_q);

$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'EXT' AND date < '".$month."'");
$get_pb10 = mysql_fetch_assoc($prevbal10_q);

$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date < '".$month."'");
$get_pb11 = mysql_fetch_assoc($prevbal11_q);

$prevbal12_q = mysql_query("SELECT SUM(amount) AS amtKInt2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'RECEIVED' AND date < '".$month."'");
$get_pb12 = mysql_fetch_assoc($prevbal12_q);


$cf = $cf - $get_pb['amtOut'] + $get_pb2['amtIn'] - $get_pb3['amtEx'] + $get_pb4['amtKom'] + $get_pb5['amtOth'] + $get_pb6['amtKom2'] + $get_pb7['amtCCM'] - $get_pb8['amtTrans'] + $get_pb9['amtTrans2'] - $get_pb10['amtExt'] + $get_pb11['amtKInt'] + $get_pb12['amtKInt2'];

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

-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/cash-book/cash-book.png" style="height:47px"></td>
        <td>Cash Book: <?php echo $get_p['scheme']; ?></td>
        <td align="right">&nbsp;</td>
    </tr>
    
   	<tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" id="active-menu">Cash Book</a><a href="balance_1.php">Balance 1</a><a href="balance.php">Balance 2</a>		
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
    	<td colspan="3"><strong>CASH BOOK <?php echo $get_p['scheme']." (".date('M Y', strtotime($nmth)).")"; ?></strong></td>
    </tr>
	<tr>
	  <th width="120">Date</th>
        <th>Particulars</th>
        <th width="130">Out</th>
        <th width="130">In</th>
        <?php if($get_p['type'] != 'Fixed Loan'){ if($get_p['receipt_type'] == '1') {?>
        <th width="130">Int</th>
        <?php } }?>
        <?php if($get_p['type'] != 'Fixed Loan'){ ?><th width="130">Expenses</th><?php } ?>
		<?php if($get_p['scheme'] == 'SKIM CEK'){ ?><th width="130">Expenses</th><?php } ?>
        <th width="130">Total</th>
		<th width="120">Record By </th>
    </tr>
    <tr style="border-bottom:thin solid #EEEEEE">
      <td><?php if($cfmonth >= $month) { echo date('d/m/Y', strtotime($cf_date)); } else { echo date('d/m/Y', strtotime($nmth)); } ?></td>
    	<td>CF</td>
        <td>&nbsp;</td>
        <td></td>
		<?php if($get_p['scheme'] == 'SKIM CEK'){ ?>
		<td></td>
		<?php }	?>
        <?php if($get_p['type'] != 'Fixed Loan'){ ?><td></td>
        <?php if($get_p['type'] != 'Fixed Loan'){ if($get_p['receipt_type'] == '1') {?><td></td><?php }} ?>
        <?php } ?>
        <td><?php echo number_format($cf, '2'); ?></td>
        <td></td>
    </tr>
    <?php 
	$tot = $cf;
	$in = '';
	$out = '';
	$name = '';
	$totE = 0;
    while($get_l = mysql_fetch_assoc($loan_q)){
		
		$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_l['customer_id']."'");
		$get_cust = mysql_fetch_assoc($cust_q);
		
		if($get_l['type'] == 'PAY')
		{
			$name = ' : '.$get_cust['name'];
		}else
		if($get_l['type'] == 'COMMISSION')
		{
			$name = ' : '.$get_cust['name'];
		}else		
		if($get_l['type'] == 'RECEIVED')
		{
			$name = ' : '.$get_cust['name'];
		}else
		{
			$name = '';
		}
		
		if($get_l['transaction'] == 'LOAN')
		{
			$out = $get_l['amount'];
			$in = '';
			$intk = '';
			$out1 = '';
			$tot -= $out;
			
			if($get_l['receipt_no'] != "")
			{
				$receipt = ' - '.$get_l['receipt_no'];
			}
			else
			{
				$receipt = ' - '.$get_l['code']; // 01(1212216) - in case receipt_no is null
			}
			
			$custcode = ' - '.$get_l['code'];
		}else	
		if($get_l['transaction'] == 'EXT')
		{
			$out = $get_l['amount'];
			$in = '';
			$out1 = '';
			$intk = '';
			$tot -= $out;
			$receipt = ' - '.$get_l['receipt_no'];
			$custcode = ' - '.$get_l['code'];
		}else		
		if($get_l['transaction'] == 'REC')
		{
			$in = $get_l['amount'];
			$out = '';
			$out1 = '';
			$intk = '';
			$tot += $in;
			$receipt = ' - '.$get_l['receipt_no'];
			$custcode = ' - '.$get_l['code'];
		}else		
		if($get_l['transaction'] == 'INT')
		{
			$in = $get_l['amount'];
			$out = '';
			$out1 = '';
			$intk = '';
			$tot += $in;
			$receipt = ' - '.$get_l['receipt_no'];
			$custcode = ' - '.$get_l['code'];
		}else		
		if($get_l['transaction'] == 'CCM')
		{
			$in = $get_l['amount'];
			$out = '';
			$out1 = '';
			$intk = '';
			$tot += $in;
			$receipt = ' - '.$get_l['receipt_no'];
			$custcode = ' - '.$get_l['code'];
		}else
		if($get_l['transaction'] == 'KOMISYEN')
		{
			$in = $get_l['amount'];
			$out = '';
			$out1 = '';
			$intk = '';
			$tot += $in;
			$receipt = '';
			$custcode = '';
		}else
		if($get_l['transaction'] == 'KOMISYEN2')
		{
			$in = '';
			$intk = $get_l['amount'];
			$out = '';
			$out1 = '';
			$tot += $intk;
			$receipt = ' - '.$get_l['receipt_no'];
			$custcode = '';
		}else
		if($get_l['type'] == 'TRANSFER1')
		{
			$in = '';
			$out = $get_l['amount'];
			$intk = '';
			$out1 = '';
			$tot -= $out;
			$receipt = '';
			$custcode = '';
		}else
		if($get_l['type'] == 'TRANSFER2')
		{
			$in = $get_l['amount'];
			$out = '';
			$intk = '';
			$out1 = '';
			$tot += $in;
			$receipt = '';
			$custcode = '';
		}else
		if($get_l['type'] == 'RECEIVED2')
		{
			$in = $get_l['amount'];
			$out = '';
			$intk = '';
			$out1 = '';
			$tot += $in;
			$receipt = '';
			$custcode = '';
		}
		else
		{
			$in = $get_l['amount'];
			$out = '';
			$intk = '';
			$out1 = $get_l['amount'];
			$totE += $get_l['amount'];
			$tot -= $out1;
			$receipt = '';
			$custcode = '';
		}
		
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
      <td><?php echo date('d/m/Y', strtotime($get_l['date'])); ?></td>
    	
        <td><?php
		if($get_p['type'] == 'Fixed Loan')
		{
			if($get_p['scheme'] != 'SKIM CEK')
			{
				echo $get_l['transaction'].$receipt.$name;
			}else
			{
				if($get_l['type'] != 'EXPENSES') { 
				
					if($get_l['transaction'] != 'KOMISYEN')
					{
						echo $get_l['transaction'].$receipt.$name;
					}else
					{
						echo "REC";
					}	
				}else
				{
					echo "EXP - ".$get_l['transaction'];
				}
			}
		}else
		{//flexi loan
			if($get_l['type'] != 'EXPENSES') { 
				if($get_l['transaction'] == 'KOMISYEN')
				{
					echo "REC";
				}else
				if($get_l['transaction'] == 'KOMISYEN2')
				{
					echo "INT".$receipt.$name;;
				}else
				{
					echo $get_l['transaction'].$receipt.$name;				
				}
			}
			else { echo "EXP - ".$get_l['transaction']; }
		} 
		?>		</td>
        <td><?php if($out != '') { echo number_format($out, '2'); } ?>		</td>
        <td><?php if($in != '') { echo number_format($in, '2'); } ?></td>
        <?php if($get_p['type'] != 'Fixed Loan'){ if($get_p['receipt_type'] == '1') {?><td><?php if($intk != '') { echo number_format($intk, '2'); } ?></td><?php } }?>
        <?php if($get_p['type'] != 'Fixed Loan'){ ?><td><?php if($out1 != '') { echo number_format($out1, '2'); } ?></td><?php } ?>
		<?php if($get_p['scheme'] == 'SKIM CEK'){ ?>
		<td><?php if($out1 != '') { echo number_format($out1, '2'); } ?></td>
		<?php }	?>
        <td><?php echo number_format($tot,'2'); ?></td>
		<td><?php if($get_l['staff_name'] != '') { echo $get_l['staff_name']; } if($get_l['created_date'] != '0000-00-00') { echo " (".date('d/m/Y', strtotime($get_l['created_date'])).")"; } ?></td>
    </tr>
    <?php } ?>
	<tr>
    	<?php if($get_p['type'] != 'Fixed Loan'){ if($get_p['receipt_type'] == '1') {?><td colspan="5">&nbsp;</td><td><strong><?php if($totE != 0) { echo number_format($totE,'2'); } ?></strong></td><?php } else { ?>
		<td colspan="4">&nbsp;</td><td><strong><?php if($totE != 0) { echo number_format($totE,'2'); } ?></strong></td>
		<?php } } else { ?>
		<td colspan="4">&nbsp;</td>
		<?php if($get_p['scheme'] == 'SKIM CEK') { ?><td>&nbsp;</td><?php } ?>
		<?php } ?>
		<!--<td style="background:#CCCCCC"><strong><?php echo number_format($totE,'2'); ?></strong></td>-->
		<td style="background:#CCCCCC"><strong><?php echo number_format($tot,'2'); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
    <tr>
		<?php if($get_p['type'] != 'Fixed Loan'){ if($get_p['receipt_type'] == '1') {?>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/cashbook.php?id=<?php echo $package_id; ?>'" value=""></td>
		<?php } else { ?>
		<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/cashbook.php?id=<?php echo $package_id; ?>'" value=""></td>
		<?php } } else { ?>
		<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/cashbook.php?id=<?php echo $package_id; ?>'" value=""></td>
		<?php } ?>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
</table>
</center>
</body>
</html>