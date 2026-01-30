<?php include('../include/page_header.php');

$year = $_GET['yr'];

//get_cf
$cf_q = mysql_query("SELECT * FROM cashbook_skimkutu WHERE date < '".$year."-'");
$get_cf = mysql_fetch_assoc($cf_q);
$row = mysql_num_rows($cf_q);


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
        <td>Cash Book: SKIM KUTU</td>
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
    	<td colspan="2"><strong>CASH BOOK SKIM KUTU</strong></td>
    </tr>
	<tr>
    	<th width="120">Date</th>
        <th>Particulars</th>
        <th width="130">Out</th>
        <th width="130">In</th>
        <th width="130">Total</th>
    </tr>	
    <?php 
	if($row == 0)// for the 1st year without CF
	{
	$ctr = 0;
	$cf = 0;
	$sql = mysql_query("SELECT * FROM cashbook_skimkutu WHERE date LIKE '%".$year."%' ORDER BY date ASC, id ASC");
	while($get_q = mysql_fetch_assoc($sql))
	{
	$ctr++;
	if($ctr == 1)
	{
		$cf = $get_q['inamt'];
	}else
	{
		$cf += $get_q['inamt'];
		$cf -= $get_q['outamt'];
	}
	?>
	<tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d-m-Y', strtotime($get_q['date'])); ?></td>
        <td><?php echo $get_q['description']; ?></td>
		<td><?php if($get_q['outamt'] != 0) { echo number_format($get_q['outamt'], '2'); } ?></td>
        <td><?php if($get_q['inamt'] != 0) { echo number_format($get_q['inamt'], '2'); } ?></td>
        <td><?php echo number_format($cf, '2'); ?></td>
    </tr>
	<?php }
	} else { // 2nd year and ++
	
	$ctr = 0;
	
	$sql1 = mysql_query("SELECT * FROM cashbook_skimkutu WHERE id = '1'");
	$get1 = mysql_fetch_assoc($sql1);
	$cf = $get1['inamt'];
	
	$year1 = $year .'-01-01';
	
	$sum1q = mysql_query("SELECT SUM(inamt) as sumIn FROM cashbook_skimkutu WHERE date < '".$year1."-' AND id != '1'");
	$sum1 = mysql_fetch_assoc($sum1q);
	$sum2q = mysql_query("SELECT SUM(outamt) as sumOut FROM cashbook_skimkutu WHERE date < '".$year1."-'");
	$sum2 = mysql_fetch_assoc($sum2q);
	
	$cf = $cf + $sum1['sumIn'] - $sum2['sumOut'];
	
	$sql = mysql_query("SELECT * FROM cashbook_skimkutu WHERE date LIKE '%".$year."-%' ORDER BY date ASC, id ASC");
	?>
	<tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d-m-Y', strtotime($year1)); ?></td>
        <td>CF</td>
		<td></td>
        <td></td>
        <td><?php echo number_format($cf, '2'); ?></td>
    </tr>
	<?php
	
    while($get_q = mysql_fetch_assoc($sql))
	{
		$cf += $get_q['inamt'];
		$cf -= $get_q['outamt']; 
	?>
    <tr style="border-bottom:thin solid #EEEEEE">
    	<td><?php echo date('d-m-Y', strtotime($get_q['date'])); ?></td>
        <td><?php echo $get_q['description']; ?></td>
		<td><?php if($get_q['outamt'] != 0) { echo number_format($get_q['outamt'], '2'); } ?></td>
        <td><?php if($get_q['inamt'] != 0) { echo number_format($get_q['inamt'], '2'); } ?></td>
        <td><?php echo number_format($cf, '2'); ?></td>
    </tr>
    <?php }
	}?>
	<tr>
    	
		<td colspan="4">&nbsp;</td>

		<td style="background:#CCCCCC"><strong><?php echo number_format($cf,'2'); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbook/cashbookKutu.php'" value=""></td>
		
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
</body>
</html>