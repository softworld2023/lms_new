<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];
$db_name = isset($_GET['db_name']) ? $_GET['db_name'] : '';
$branch_name = $db_name != '' ? $db_name : strtolower($_SESSION['login_branch']);

$sql = mysql_query("SELECT * FROM $branch_name.customer_loanpackage WHERE 
					customer_id = '".$id."' 
					AND (loan_status = 'Paid' 
					OR loan_status = 'Finished'
					OR loan_status = 'Deleted'
					OR loan_status = 'BAD DEBT')");

// $sql = mysql_query("SELECT * FROM $branch_name.customer_loanpackage WHERE 
// 					customer_id = '".$id."' 
// 					AND (loan_status = 'Paid' 
// 					OR loan_status = 'Finished')");

$cust_q = mysql_query("SELECT * FROM $branch_name.customer_details WHERE id = '".$id."'");
$get_cust = mysql_fetch_assoc($cust_q);
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
#tr_list:hover {background-color: lightgray;}
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/customers/customers.png"></td>
        <td>Customers: <?php echo $get_cust['name']." (".$get_cust['nric'].")"; ?></td>
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
</table>
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
    	<th>Payout Date</th>
        <th>Agreement No</th>
        <th>Loan Amount</th>
        <th>Month</th>
   		<th>Pokok</th>
 		<th>Faedah</th>
 		<th>Total </th>
        <th width="50"></th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){
		$ctr++;
		//why unrelated people can appear in here??!!!
		
		$lcode = $get_q['loan_code'];
		
		if($lcode == '0')
		{
			$lpd_q = mysql_query("SELECT * FROM $branch_name.loan_payment_details WHERE 
									customer_loanid = '".$get_q['id']."' 
									AND receipt_no != '0' 
									ORDER BY id DESC");
			$lpd = mysql_fetch_assoc($lpd_q);
			$lcode = $lpd['receipt_no'];


		}

		// $late_interest_q = mysql_query("SELECT * FROM $branch_name.late_interest_record WHERE loan_code = '".$lcode."'") or die (mysql_error());
		// $late = mysql_fetch_assoc($late_interest_q);
	?>
    <tr id="tr_list">
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo $get_q['payout_date'];?></td>
        <td><?php echo $lcode; ?></td>
        <td><?php echo "RM ".$get_q['loan_amount']; ?></td>
        <td><?php echo $get_q['loan_period']." Months"; ?></td>
		<td><?php echo "RM ".$get_q['loan_pokok']; ?></td>
		<td><?php echo "RM ".number_format(($get_q['loan_total']-$get_q['loan_pokok']),2); ?></td>
		<td><?php echo "RM ".$get_q['loan_total']; ?></td>
        <td>
			<?php
				if ($db_name == '') {
			?>
					<a href="lejar_a.php?id=<?php echo $get_q['id']; ?>" title="Lejar"><img src="../img/home/report.png" width="35px" height="35px" /></a>
			<?php
				}
			?>
		</td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="9" align="right"><input type="button" name="back" id="back" onClick="window.location.href='index.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
</table>
</center>
