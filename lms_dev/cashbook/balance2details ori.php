<?php include('../include/page_header.php');

$package_id = $_GET['id'];
$month = $_GET['mth'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
$get_p = mysql_fetch_assoc($package_q);

$cf = $get_p['initial_amount'];
$cf_date = $get_p['opening_date'];

$nmth = $month.'-01'; 

$newmth = date('Y-m-d', strtotime($nmth));

$cfmonth = date('Y-m', strtotime(date($cf_date)));

$loan_q = mysql_query("SELECT * FROM balance_rec WHERE package_id = '".$package_id."' AND bal_date LIKE '%".$month."%' ORDER BY bal_date ASC");

$prevbal_q = mysql_query("SELECT SUM(loan) AS amtloan, SUM(received) AS amtrec, SUM(commission) AS amtcom, SUM(interest) AS amtint FROM balance_rec WHERE package_id = '".$package_id."' AND bal_date < '".$newmth."'");
$get_pb = mysql_fetch_assoc($prevbal_q);


$cf = $cf - $get_pb['amtloan'] + $get_pb['amtrec'] + $get_pb['amtcom'] + $get_pb['amtint'];

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
        <td>Balance 2 : <?php echo $get_p['scheme']; ?></td>
        <td align="right">&nbsp;</td>
    </tr>
    
   	<tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php">Cash Book</a><a href="balance_1.php">Balance 1</a><a href="balance.php" id="active-menu">Balance 2</a>
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
<?php
if($get_p['type'] == 'Flexi Loan')
{
?>
<table width="1280" id="list_table">
	<tr>
    	<td colspan="7"><strong>BALANCE 2 <?php echo $get_p['scheme']." (".date('M Y', strtotime($nmth)).")"; ?></strong></td>
    </tr>
	<tr>
    	<th width="20%">DATE</th>
        <th width="16%">LOAN + EXT </th>
        <th width="16%">REC + CCM </th>
        <th width="16%">COMMISSION</th>
        <th width="16%">INT</th>
        <th width="16%">TOTAL</th>
    </tr>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php if($cfmonth >= $month) { echo date('d/m/Y', strtotime($cf_date)); } else { echo date('d/m/Y', strtotime($nmth)); } ?></td>
        <td>CF</td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo number_format($cf, '2'); ?></td>
    </tr>
    <?php 
	$tot = $cf;
	$in = 0;
	$out = 0;
	$name = '';
	$totint = 0;
	$totrec = 0;
	//$totcoms = '';
	$totloan = 0;
	$prev_d = '';
    while($get_l = mysql_fetch_assoc($loan_q)){
	
		$d = date('Y-m-d', strtotime($get_l['bal_date']));
		if($d != $prev_d)
		{
			$sum_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec, SUM(commission) AS sumCom, SUM(interest) AS sumInt FROM balance_rec WHERE bal_date LIKE '%".$d."%' AND package_id = '".$package_id."' ORDER BY bal_date ASC");
			$get_sum = mysql_fetch_assoc($sum_q);		
		
			if($get_sum['sumLoan'] != 0)
			{
				$out = $get_sum['sumLoan'];
				$tot = $tot - $out;
				$totloan += $get_sum['sumLoan'];
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td><?php if($get_sum['sumLoan'] != '0') { echo number_format($get_sum['sumLoan'], '2'); } ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
			if($get_sum['sumRec'] != 0)
			{
				$in = $get_sum['sumRec'];
				$tot = $tot + $in;
				
				$totrec += $get_sum['sumRec'];
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td></td>
        <td><?php if($get_sum['sumRec'] != '0') { echo number_format($get_sum['sumRec'], '2'); } ?></td>
        <td></td>
        <td></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
			if($get_sum['sumCom'] != 0)
			{
				$in = $get_sum['sumCom'];
				$tot = $tot + $in;
				
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td></td>
        <td></td>
        <td><?php if($get_sum['sumCom'] != '0') { echo number_format($get_sum['sumCom'], '2'); } ?></td>
        <td></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
	<?php 
			}
			if($get_sum['sumInt'] != 0)
			{
				$in = $get_sum['sumInt'];
				$tot = $tot + $in;
				
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php if($get_sum['sumInt'] != '0') { echo number_format($get_sum['sumInt'], '2'); } ?></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
		}
		$prev_d = date('Y-m-d', strtotime($get_l['bal_date']));
	} ?>
	<tr>
    	<td>&nbsp;</td>
		<td><strong><?php if($totloan != '0') { echo number_format($totloan, '2'); } ?></strong></td>
		<td><strong><?php if($totrec != '0') { echo number_format($totrec, '2'); } ?></strong></td>
		<td></td>
		<td><strong><?php if($totint != '0') { echo number_format($totint, '2'); } ?></strong></td>
		<td style="background:#CCCCCC"><strong><?php echo number_format($tot,'2'); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/balance2.php?id=<?php echo $package_id; ?>'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
<?php
} else
{
?>
<table width="1280" id="list_table">
	<tr>
    	<td colspan="7"><strong>BALANCE 2 <?php echo $get_p['scheme']." (".date('M Y', strtotime($nmth)).")"; ?></strong></td>
    </tr>
	<tr>
    	<th width="20%">DATE</th>
        <th width="16%">LOAN</th>
        <th width="16%">REC</th>
        <th width="16%">COMMISSION</th>
        <th width="16%">LATE INT</th>
        <th width="16%">TOTAL</th>
    </tr>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php if($cfmonth >= $month) { echo date('d/m/Y', strtotime($cf_date)); } else { echo date('d/m/Y', strtotime($nmth)); } ?></td>
        <td>CF</td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo number_format($cf, '2'); ?></td>
    </tr>
    <?php 
	$tot = $cf;
	$in = '';
	$out = '';
	$name = '';
	$totint = 0;
	$totrec = 0;
	//$totcoms = '';
	$totloan = 0;
	$prev_d = '';
    while($get_l = mysql_fetch_assoc($loan_q)){
	
		$d = date('Y-m-d', strtotime($get_l['bal_date']));
		if($d != $prev_d)
		{
			$sum_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec, SUM(commission) AS sumCom, SUM(interest) AS sumInt FROM balance_rec WHERE bal_date LIKE '%".$d."%' AND package_id = '".$package_id."' ORDER BY bal_date ASC");
			$get_sum = mysql_fetch_assoc($sum_q);		
		
			if($get_sum['sumLoan'] != 0)
			{
				$out = $get_sum['sumLoan'];
				$tot = $tot - $out;
				$totloan += $get_sum['sumLoan'];
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td><?php if($get_sum['sumLoan'] != '0') { echo number_format($get_sum['sumLoan'], '2'); } ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
			if($get_sum['sumRec'] != 0)
			{
				$in = $get_sum['sumRec'];
				$tot = $tot + $in;
				
				$totrec += $get_sum['sumRec'];
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td></td>
        <td><?php if($get_sum['sumRec'] != '0') { echo number_format($get_sum['sumRec'], '2'); } ?></td>
        <td></td>
        <td></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
			if($get_sum['sumCom'] != 0)
			{
				$in = $get_sum['sumCom'];
				$tot = $tot + $in;
				
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td></td>
        <td></td>
        <td><?php if($get_sum['sumCom'] != '0') { echo number_format($get_sum['sumCom'], '2'); } ?></td>
        <td></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
	<?php 
			}
			if($get_sum['sumInt'] != 0)
			{
				$in = $get_sum['sumInt'];
				$tot = $tot + $in;
				
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['bal_date'])); ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php if($get_sum['sumInt'] != '0') { echo number_format($get_sum['sumInt'], '2'); } ?></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
		}
		$prev_d = date('Y-m-d', strtotime($get_l['bal_date']));
	} ?>
	<tr>
    	<td>&nbsp;</td>
		<td><strong><?php if($totloan != '0') { echo number_format($totloan, '2'); } ?></strong></td>
		<td><strong><?php if($totrec != '0') { echo number_format($totrec, '2'); } ?></strong></td>
		<td></td>
		<td><strong><?php if($totint != '0') { echo number_format($totint, '2'); } ?></strong></td>
		<td style="background:#CCCCCC"><strong><?php echo number_format($tot,'2'); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/balance2.php?id=<?php echo $package_id; ?>'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
<?php 
} ?>
</center>
</body>
</html>