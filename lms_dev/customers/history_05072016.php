<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$id."' AND (loan_status = 'Paid' OR loan_status = 'Finished')");

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
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
        <th>Loan Amount</th>
        <th>Loan Code </th>
        <th>Package</th>
        <th>Loan Type</th>
        <th width="50"></th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$lcode = $get_q['loan_code'];
	
	if($lcode == '0')
	{
		$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND receipt_no != '0' ORDER BY id DESC");
		$lpd = mysql_fetch_assoc($lpd_q);
		$lcode = $lpd['receipt_no'];
	}
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php if($get_q['loan_status'] == 'Paid') { echo "RM ".$get_q['loan_amount']; } if($get_q['loan_status'] == 'Finished') { echo "RM 0.00"; } ?></td>
        <td><?php echo $lcode; ?></td>
        <td><?php echo $get_q['loan_package']; ?></td>
        <td><?php echo $get_q['loan_type']; ?></td>
        <td><?php
				if($get_q['loan_type'] == 'Flexi Loan')
				{
					$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".mysql_real_escape_string($get_q['loan_package'])."'");
					$get_rt = mysql_fetch_assoc($rt_q);
					
					if($get_rt['receipt_type'] == 1)//receipt code still the same
					{
			?>
            		<a href="payloan_f.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
            <?php 
					}
					
					if($get_rt['receipt_type'] == 2)//receipt code changing
					{
			?>
					<a href="payloan.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
			<?php
					}
				}
				if($get_q['loan_type'] == 'Fixed Loan')
				{
					if(strpos($get_q['loan_package'], 'SKIM CEK') === FALSE)
					{
			?>
            			<a href="payloan_a.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> 
            <?php 
					}else
					{
			?>
						<a href="payloan_CEK.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> 
			<?php
					}
				}
			?>        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='index.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
</table>
</center>
