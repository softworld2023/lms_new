<?php include('../include/page_header.php');

$package_id = $_GET['id'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
$get_p = mysql_fetch_assoc($package_q);

$cf = 0;
$cf_date = $get_p['opening_date'];

$loan_q = mysql_query("SELECT * FROM balance_transaction WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."' GROUP BY CONCAT(YEAR(date), '-', MONTH(date)) ORDER BY date DESC");

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
        <td>Balance 1: <?php echo $get_p['scheme']; ?></td>
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
    	<td colspan="4"><strong>BALANCE 1 <?php echo $get_p['scheme']; ?></strong></td>
    </tr>
	<tr>
    	<th width="50">No.</th>
        <th>Month</th>
        <th>Out</th>
        <th>In</th>
    </tr>
    
    <?php 
	$ctr = '';
    while($get_l = mysql_fetch_assoc($loan_q)){
		$ctr++;
		
		$month_cb = date('Y-m-', strtotime($get_l['date']));		

		$prevbal_q = mysql_query("SELECT SUM(loan) AS amtloan, SUM(received) AS amtrec FROM balance_transaction WHERE package_id = '".$package_id."' AND date LIKE '%".$month_cb."%' AND branch_id = '".$_SESSION['login_branchid']."'");
		$get_pb = mysql_fetch_assoc($prevbal_q);
		
		$out_amt = $get_pb['amtloan'];
		$in = $get_pb['amtrec'];
		
	?>
    <tr>
    	<td><?php echo $ctr; ?></td>
        <td><a href="balance1details.php?id=<?php echo $package_id; ?>&mth=<?php echo date('Y-m', strtotime($get_l['date'])); ?>"><?php echo date('M Y', strtotime($get_l['date'])); ?></a></td>
        <td><?php echo "RM ".number_format($out_amt, '2') ; ?></td>
        <td><?php echo "RM ".number_format($in, '2'); ?></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/balance_1.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
</body>
</html>