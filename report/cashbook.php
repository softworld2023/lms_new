<?php include('../include/page_header.php');

$package_id = $_GET['id'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
$get_p = mysql_fetch_assoc($package_q);

$cf = $get_p['initial_amount'];
$cf_date = '2014-10-01';

$loan_q = mysql_query("SELECT * FROM customer_loanpackage c, cashbook b WHERE c.loan_package = '".addslashes($get_p['scheme'])."' AND c.loan_status != 'Approved' AND c.loan_status != 'Pending' AND c.loan_status != 'KIV' AND b.customer_loanid = c.id AND b.date >= '".$cf_date."'");

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
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" id="active-menu">Cash Book List</a>
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
    	<td colspan="2"><strong>CASH BOOK <?php echo $get_p['scheme']; ?></strong></td>
    </tr>
	<tr>
    	<th width="120">Date</th>
        <th>Particulars</th>
        <th>Out</th>
        <th>In</th>
        <th>Total</th>
    </tr>
    <tr>
    	<td><?php echo date('d/m/Y', strtotime($cf_date)); ?></td>
        <td>CF</td>
        <td></td>
        <td></td>
        <td><?php echo $cf; ?></td>
    </tr>
    <?php 
	$tot = $cf;
	$in = '';
	$out = '';
    while($get_l = mysql_fetch_assoc($loan_q)){
	if($get_l['transaction'] == 'LOAN')
	{
		$out = $get_l['amount'];
		$in = '';
		$tot -= $out;
	}
	
	if($get_l['transaction'] == 'REC')
	{
		$in = $get_l['amount'];
		$out = '';
		$tot += $in;
	}
	?>
    <tr>
    	<td><?php echo date('d/m/Y', strtotime($get_l['date'])); ?></td>
        <td><?php echo $get_l['transaction']." - ".$get_l['code']; ?></td>
        <td><?php if($out != '') { echo number_format($out, '2', '.',''); } ?></td>
        <td><?php if($in != '') { echo number_format($in, '2', '.',''); } ?></td>
        <td><?php echo number_format($tot,'2'); ?></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
</body>
</html>