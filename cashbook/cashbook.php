<?php include('../include/page_header.php');

$package_id = $_GET['id'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
$get_p = mysql_fetch_assoc($package_q);

$cf = $get_p['initial_amount'];
$cf_date = $get_p['opening_date'];

$loan_q = mysql_query("SELECT * FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' GROUP BY CONCAT(YEAR(date), '-', MONTH(date)) ORDER BY date DESC");

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
    	<td colspan="4"><strong>CASH BOOK <?php echo $get_p['scheme']; ?></strong></td>
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

		$out_q = mysql_query("SELECT SUM(amount) AS outamt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND transaction = 'LOAN'");
		$get_o = mysql_fetch_assoc($out_q);
		$out = $get_o['outamt'];
		
		$in_q = mysql_query("SELECT SUM(amount) AS inamt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND transaction = 'REC'");
		$get_i = mysql_fetch_assoc($in_q);
		$in = $get_i['inamt'];
		
		$ccm_q = mysql_query("SELECT SUM(amount) AS ccmamt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND transaction = 'CCM'");
		$get_ccm = mysql_fetch_assoc($ccm_q);
		$ccm = $get_ccm['ccmamt'];
		
		$int_q = mysql_query("SELECT SUM(amount) AS intamt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND transaction = 'INT'");
		$get_int = mysql_fetch_assoc($int_q);
		$interestamt = $get_int['intamt'];
		
		$exp_q = mysql_query("SELECT SUM(amount) AS expamt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND type = 'EXPENSES'");
		$get_exp = mysql_fetch_assoc($exp_q);
		$exp = $get_exp['expamt'];
		
		$kom_q = mysql_query("SELECT SUM(amount) AS komamt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND type = 'COMMISSION'");
		$get_kom = mysql_fetch_assoc($kom_q);
		$koms = $get_kom['komamt'];
		
		$trans1_q = mysql_query("SELECT SUM(amount) AS trans1amt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND type = 'TRANSFER1'");
		$get_tr1 = mysql_fetch_assoc($trans1_q);
		$tr1 = $get_tr1['trans1amt'];
		
		$trans2_q = mysql_query("SELECT SUM(amount) AS trans2amt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND type = 'TRANSFER2'");
		$get_tr2 = mysql_fetch_assoc($trans2_q);
		$tr2 = $get_tr2['trans2amt'];
		
		$rec2_q = mysql_query("SELECT SUM(amount) AS trans2amt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$month_cb."%' AND type = 'RECEIVED2'");
		$get_tr2 = mysql_fetch_assoc($rec2_q);
		$tr2 = $get_tr2['trans2amt'];
		
		$out_amt = $out + $exp + $tr1;
		
		$in_amt = $in + $koms + $tr2 + $ccm + $interestamt;
		
	?>
    <tr>
    	<td><?php echo $ctr; ?></td>
        <td><a href="cashbookdetails.php?id=<?php echo $package_id; ?>&mth=<?php echo date('Y-m', strtotime($get_l['date'])); ?>"><?php echo date('M Y', strtotime($get_l['date'])); ?></a></td>
        <td><?php echo "RM ".number_format($out_amt, '2') ; ?></td>
        <td><?php echo "RM ".number_format($in_amt, '2'); ?></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
</body>
</html>