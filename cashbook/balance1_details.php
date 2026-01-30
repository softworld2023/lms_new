<?php include('../include/page_header.php');

$package_id = $_GET['id'];
$month = $_GET['mth'];

$cbcf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."'");
$cbcf = mysql_fetch_assoc($cbcf_q);
$cf = $cbcf['amount'];
$cf_date = $cbcf['date'];

$nmth = $month.'-01'; 

$newmth = date('Y-m-d', strtotime($nmth));

$cfmonth = date('Y-m', strtotime(date($cf_date)));

if($package_id == '1000')
{
	$scheme = 'SKIM H';
	$cond .= " AND ttype = 'RECEIVED' AND (expenses_details LIKE '%SKIM H%' OR expenses_details LIKE '%SKIM (H*%' OR expenses_details LIKE '%H*%') AND expenses_details NOT LIKE '%AH*%' AND expenses_details NOT LIKE '%KH*%' AND expenses_details NOT LIKE '%SH*%'";
	$cond2 .= " AND ttype = 'RECEIVED' AND (expenses_details LIKE '%SKIM H%' OR expenses_details LIKE '%SKIM (H*%' OR expenses_details LIKE '%H*%') AND expenses_details NOT LIKE '%AH*%' AND expenses_details NOT LIKE '%KH*%' AND expenses_details NOT LIKE '%SH*%' AND expenses_details LIKE '%INT%'";
	$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND (expenses_details LIKE '%SKIM H%' OR expenses_details LIKE '%SKIM (H*%' OR expenses_details LIKE '%H*%') AND expenses_details NOT LIKE '%AH*%' AND date LIKE '%".$month."%' ORDER BY date ASC");

}
if($package_id == '1001')
{
	$scheme = 'SKIM AH';
	$cond .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH%' AND expenses_details NOT LIKE '%AH CEK%'";
	$cond2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH%' AND expenses_details NOT LIKE '%AH CEK%' AND expenses_details LIKE '%INT%'";
	$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED'AND expenses_details LIKE '%SKIM AH%' AND expenses_details NOT LIKE '%AH CEK%' AND date LIKE '%".$month."%' ORDER BY date ASC");
}
if($package_id == '1002')
{
	$scheme = 'SKIM AH CEK';
	$cond .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH CEK%'";
	$cond2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH CEK%' AND expenses_details LIKE '%INT%'";
	$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH CEK%' AND date LIKE '%".$month."%' ORDER BY date ASC");
}
if($package_id == '1003')
{
	$scheme = 'SKIM KH';
	$cond .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%KH*%'";
	$cond2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%KH*%' AND expenses_details LIKE '%INT%'";
	$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%KH*%' AND date LIKE '%".$month."%' ORDER BY date ASC");
}
if($package_id == '1004')
{
	$scheme = 'SKIM SH';
	$cond .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SH*%'";
	$cond2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SH*%' AND expenses_details LIKE '%INT%'";
	$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%SH*%' AND date LIKE '%".$month."%' ORDER BY date ASC");
}
$prevbal_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date < '".$newmth."' AND branch_id = '".$_SESSION['login_branchid']."' $cond");
$get_pb = mysql_fetch_assoc($prevbal_q);
$prevbal_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date < '".$newmth."' AND branch_id = '".$_SESSION['login_branchid']."' $cond2");
$get_pb2 = mysql_fetch_assoc($prevbal_q2);



$cf = $cf + $get_pb['amtrec'] - $get_pb2['amtrec'];

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
        <td>Balance 1 : <?php echo $scheme; ?></td>
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
    	<td colspan="5"><strong>BALANCE 1 <?php echo $scheme." (".date('M Y', strtotime($nmth)).")"; ?></strong></td>
    </tr>
	<tr>
    	<th width="20%">DATE</th>
        <th width="16%">LOAN</th>
        <th width="16%">REC</th>
        <th width="16%">TOTAL</th>
    </tr>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php if($cfmonth >= $month) { echo date('d/m/Y', strtotime($cf_date)); } else { echo date('d/m/Y', strtotime($nmth)); } ?></td>
        <td>CF</td>
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
	
		$d = date('Y-m-d', strtotime($get_l['date']));
		if($d != $prev_d)
		{
			$sum_q = mysql_query("SELECT SUM(amount) AS sumRec FROM expenses WHERE date LIKE '%".$d."%' AND branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY date ASC");
			$get_sum = mysql_fetch_assoc($sum_q);		
			
			if($get_sum['sumRec'] != 0)
			{
				$in = $get_sum['sumRec'];
				$tot = $tot + $in;	
				$totrec += $get_sum['sumRec'];
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['date'])); ?></td>
        <td></td>
        <td><?php if($get_sum['sumRec'] != '0') { echo number_format($get_sum['sumRec'], '2'); } ?></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
			
			$sum_q2 = mysql_query("SELECT SUM(amount) AS sumRec FROM expenses WHERE date LIKE '%".$d."%' AND branch_id = '".$_SESSION['login_branchid']."' $cond2 ORDER BY date ASC");
			$get_sum2 = mysql_fetch_assoc($sum_q2);		
			
			if($get_sum2['sumRec'] != 0)
			{
				$out = $get_sum2['sumRec'];
				$tot = $tot - $out;	
				$totloan += $get_sum2['sumRec'];
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d/m/Y', strtotime($get_l['date'])); ?></td>
        <td><?php if($get_sum2['sumRec'] != '0') { echo number_format($get_sum2['sumRec'], '2'); } ?></td>
        <td>&nbsp;</td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php 
			}
		}
		$prev_d = date('Y-m-d', strtotime($get_l['date']));
	} ?>
	<tr>
    	<td>&nbsp;</td>
		<td><strong><?php echo number_format($totloan,'2'); ?></strong></td>
		<td><strong><?php echo number_format($totrec,'2'); ?></strong></td>
		<td style="background:#CCCCCC"><strong><?php echo number_format($tot,'2'); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/balance1.php?id=<?php echo $package_id; ?>'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
</body>
</html>