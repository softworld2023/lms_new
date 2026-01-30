<?php 
include('../include/page_header.php'); 

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_status = 'Rejected' AND branch_id = '".$_SESSION['login_branchid']."'");
?>

<style>
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
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/approval/approval.png"></td>
        <td>Approval</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
            <div id="message" style="width:100%;">
            <?php
            if($_SESSION['msg'] != '')
            {
                echo $_SESSION['msg'];
                $_SESSION['msg'] = '';
            }
            ?>
            </div>
		</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Waiting</a><a href="approved.php">Approved</a><a href="rejected.php" id="active-menu">Rejected</a><a href="kiv.php">KIV</a>
		</div>	
        </td>
    </tr>
</table>
<br />
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
        <th>Name</th>
        <th>I.C Number</th>
        <th>Income</th>
        <th>Loan Amount</th>
        <th>Approval Amount</th>
        <th style="padding:0px"><center></center></th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_c = mysql_fetch_assoc($cust_q);
	
	$financial_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$get_q['customer_id']."'");
	$get_f = mysql_fetch_assoc($financial_q);
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_c['name']; ?></td>
        <td><?php echo $get_c['nric']; ?></td>
        <td><?php echo "RM ".$get_f['net_salary']; ?></td>
        <td><?php echo "RM ".$get_q['loan_amount']; ?></td>
        <td><?php echo "RM ".$get_q['loan_amount']; ?></td>
        <td>
        	<center>
            <input type="hidden" name="loan_id" id="loan_id" value="<?php echo $get_q['id']; ?>" />
            </center>
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
</table>