<?php 
include('../include/page_headercb.php'); 

$branch_name = $_GET['branch_name'];
$db_name = $_GET['db_name'];
// $bad_debt_q = mysql_query("SELECT * FROM ".$db_name.".late_interest_record AS table1 LEFT JOIN ".$db_name.".customer_details AS table2 ON table1.customer_id = table2.id LEFT JOIN ".$db_name.".customer_employment AS table3 ON table1.customer_id = table3.customer_id LEFT JOIN ".$db_name.".customer_address AS table4 ON table1.customer_id = table4.customer_id");
	


if(isset($_POST['search']))   // click search
{
	$branch_name = $_POST['branch_name'];
	$db_name = $_POST['db_name'];
	$cond = '';
		
	if($_POST['customer_name'] != '')
	{
		$customer_sql = mysql_query("SELECT * FROM ".$db_name.".customer_details AS table2 WHERE name = '".$_POST['customer_name']."'");
		$cust = mysql_fetch_assoc($customer_sql);
		// $cond .= "where table2.id = '".$cust['id']."' AND (table5.status = 'BAD DEBT' OR table5.status IS NULL)";
		$cond .= "where table2.id = '".$cust['id']."' AND (table5.status != 'DELETED' OR table5.status IS NULL)";	
	}
	
	
	
	if($_POST['customer_ic'] != '')
	{
		
		$code_sql = mysql_query("SELECT * FROM ".$db_name.".customer_details 
								WHERE nric = '".$_POST['customer_ic']."'");
		$code = mysql_fetch_assoc($code_sql);
		// $cond .= "where table2.id = '".$code['id']."' AND (table5.status = 'BAD DEBT' OR table5.status IS NULL)";
		$cond .= "where table2.id = '".$code['id']."' (table5.status != 'DELETED' OR table5.status IS NULL)";	
	}
	
	$statement =  "".$db_name.".late_interest_record AS table1 LEFT JOIN ".$db_name.".customer_details AS table2 ON table1.customer_id = table2.id LEFT JOIN ".$db_name.".customer_employment AS table3 ON table1.customer_id = table3.customer_id LEFT JOIN ".$db_name.".customer_address AS table4 ON table1.customer_id = table4.customer_id LEFT JOIN ".$db_name.".monthly_payment_record AS table5 ON table1.loan_code = table5.loan_code $cond";
						
}
else    // not click search and also no history result
{
	// $statement = "".$db_name.".late_interest_record AS table1 LEFT JOIN ".$db_name.".customer_details AS table2 ON table1.customer_id = table2.id LEFT JOIN ".$db_name.".customer_employment AS table3 ON table1.customer_id = table3.customer_id LEFT JOIN ".$db_name.".customer_address AS table4 ON table1.customer_id = table4.customer_id LEFT JOIN ".$db_name.".monthly_payment_record AS table5 ON table1.loan_code = table5.loan_code WHERE table5.status = 'BAD DEBT' OR table5.status IS NULL";
	$statement = "".$db_name.".late_interest_record AS table1 LEFT JOIN ".$db_name.".customer_details AS table2 ON table1.customer_id = table2.id LEFT JOIN ".$db_name.".customer_employment AS table3 ON table1.customer_id = table3.customer_id LEFT JOIN ".$db_name.".customer_address AS table4 ON table1.customer_id = table4.customer_id LEFT JOIN ".$db_name.".monthly_payment_record AS table5 ON table1.loan_code = table5.loan_code WHERE (table5.status != 'DELETED' OR table5.status IS NULL)";
}


$sql_q = ("SELECT table1.*, table2.*, table3.*, table4.* FROM {$statement} GROUP BY table1.customer_id ORDER BY table1.id");
// var_dump($sql_q);
$bad_debt_q = mysql_query($sql_q);
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
#approve_loan
{
	background:url(../img/approval/approve-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#approve_loan:hover
{
	background:url(../img/approval/approve-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
}
#reject_loan
{
	background:url(../img/approval/reject-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#reject_loan:hover
{
	background:url(../img/approval/reject-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
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
#search
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search:hover
{
	background:url(../img/enquiry/search-btn-roll-over.jpg);
}
/*#tr_list:hover {background-color: lightgrey;}*/

</style>
<center>
<table width="1280">
	<tr>
    	<td width="134"><img src="../img/cash-register-icon.png" width="56"></td>
        <td width="401">Bad Debt ( <b><?php echo $branch_name?></b> )</td>
 
<td align="right">
       	<form action="bad_debt_list.php" method="post">
        	<table>
				<tr>
					<td align="right" style="padding-right:10px">Customer Name</td>
					<td>
					<?php
$sql="SELECT * FROM ".$db_name.".customer_details"; 
$result=mysql_query($sql);
echo '<input id="customer_name" name="customer_name" list="names" style="height:30px;" ><datalist id="names">';
while($rows=mysql_fetch_assoc($result)){
?>
<option value="<?php echo $rows["name"]; ?>">
<?php
}
?>
				</datalist></td>
                    <td align="right" style="padding-right:10px">Customer IC</td>
                    <td><?php
$sql="SELECT * FROM ".$db_name.".customer_details"; 
$result=mysql_query($sql);
echo '<input id="customer_ic" name="customer_ic" list="nrics" style="height:30px;" ><datalist id="nrics">';
while($rows=mysql_fetch_assoc($result)){
?>
<option value="<?php echo $rows["nric"]; ?>">
<?php
}
?>
				</datalist></td>
                    <td style="padding-right:8px">
                    	<input type="hidden" id="branch_name" name="branch_name" value="<?php echo $branch_name; ?>"/>
                    	<input type="hidden" id="db_name" name="db_name" value="<?php echo $db_name; ?>"/>
                    	<input type="submit" id="search" name="search" value=""/>
					</td>
                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr><td colspan="7" style="text-align:right;"><input type="submit" id="search_1" name="search" value="Show all list"/></td></tr>

            </table>
        </form>
        </td>
	  
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	
	</table>
	
<table width="1240" id="list_table" >

	<tr>
    	<th width="80" style="border:1px solid black;">No.</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Name</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">IC</th>
        <th width="100" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Bad Debt Date</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Company Name</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Contact Number</th>
        <th width="240" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Bad Debt Amount</th>
    </tr>
    <?php 
	$ctr = 0;
	$bad_debt_amount= 0;
	$total_bd_amount= 0;

$numberofrow = mysql_num_rows($bad_debt_q);
if($numberofrow >0){
while($bad_debt = mysql_fetch_assoc($bad_debt_q))
{
	$balance = 0;
	$balance_without_instalment = 0;
	$previous_monthly_bd = 0;
	$envelope = 0;
	$envelope_without_instalment = 0;
	$deductible = 0;
	$previous_months_bd_collected = 0;
								
	$query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_amount, SUM(balance) AS previous_monthly_bd FROM " . $db_name . ".monthly_payment_record WHERE loan_code = '" . $bad_debt['loan_code'] . "' AND status = 'BAD DEBT'");
	$result_monthly_payment_record = mysql_fetch_assoc($query);

	$previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
	$previous_monthly_amount = $result_monthly_payment_record['previous_monthly_amount'];

	$amount = $bad_debt['amount'];

	if ($bad_debt['bd_from'] == 'Monthly') {
		$amount = $previous_monthly_amount - $previous_monthly_bd;
	}

	$query = mysql_query("SELECT * FROM " . $db_name . ".late_interest_record WHERE loan_code = '" . $bad_debt['loan_code'] . "'");
	$result_late_interest_record = mysql_fetch_assoc($query);

	$late_interest_record_id = $result_late_interest_record['id'];
	$bd_from = $result_late_interest_record['bd_from'];
	$branch_name = strtolower($result_late_interest_record['branch_name']);

	if ($bad_debt['loan_code'] == 'KT 20032') {
		$previous_monthly_bd = 680;
	}

	// somehow this check is required to identify whether need to deduct previous monthly bd from amount,
	// because some old records have mixed up the amount and monthly bd
	if ($branch_name == 'majusama' && $bd_from == 'Instalment + Monthly' && $late_interest_record_id > 46) {
		$amount -= $previous_monthly_bd;
	} else if ($branch_name != 'majusama' && ($bd_from == 'Instalment + Monthly' || $bd_from == 'Monthly')) {
		$amount -= $previous_monthly_bd;
	}

	$amount_without_instalment = $amount;
	// var_dump($amount_without_instalment);

	$query = mysql_query("SELECT MIN(balance) AS min_balance 
									FROM " . $db_name . ".loan_payment_details 
									WHERE receipt_no = '" . $bad_debt['loan_code'] . "'
									AND (loan_status != 'SETTLE' AND loan_status != 'COLLECTED')
								");
	$result_loan_payment_details = mysql_fetch_assoc($query);

	$loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];

	$envelope = $loan_payment_details_min_balance - $amount;
	$envelope_without_instalment = $envelope;

	if ($bad_debt['loan_code'] == 'KT 20034') {
		$envelope += 9157;
	}
	// var_dump($envelope);

	$query = mysql_query("SELECT SUM(amount) AS collected FROM " . $db_name . ".late_interest_payment_details WHERE lid = '" . $late_interest_record_id . "'");
	while ($row = mysql_fetch_assoc($query)) {
		$previous_months_bd_collected += round($row['collected'],2);
	}

	$query = mysql_query("SELECT SUM(amount) AS collected, month_receipt FROM " . $db_name . ".late_interest_payment_details WHERE lid = '" . $late_interest_record_id . "' AND month_receipt = '" . $payout_month . "'");
	while ($row = mysql_fetch_assoc($query)) {
		$current_month_bd_collected += round($row['collected'],2);
		$current_month_receipt = $row['month_receipt'];
	}
	
	// if ($result_4['bd_from'] != 'Monthly') {
		$amount -= $previous_months_bd_collected;
		$amount -= $current_month_bd_collected;
	// }

	$balance_without_instalment = $amount_without_instalment + $previous_monthly_bd + $envelope_without_instalment;
	// var_dump($balance_without_instalment);

	if ($bad_debt['loan_code'] == 'KT 20032' || $bad_debt['loan_code'] == 'KT 20034') {
		$balance_without_instalment += 9157;
	}

	if ($amount < 0) {
		$deductible = abs($amount);
		$amount = 0;
	}

	if ($amount == 0) {
		$difference = $previous_monthly_bd - $deductible;
		if ($difference <= 0) {
			$previous_monthly_bd = 0;
			$deductible = abs($difference);
		} else {
			$previous_monthly_bd = $difference;
		}								
	}

	if ($previous_monthly_bd == 0) {
		$envelope -= $deductible;
	}

	if ($envelope < 0) {
		$envelope = 0;
	} 

	if ($branch_name == 'majusama' && $late_interest_record_id <= 46) {
		$previous_monthly_bd = 0;
		$envelope = 0;
	}

	$balance = $amount + $previous_monthly_bd + $envelope;
	// var_dump($balance);

	// $total_bd_amount +=$bad_debt['balance'];
	$total_bd_amount += $balance;

	$ctr++;
	
	?>

    <tr id="tr_list" >
    	<td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;"><?php echo $ctr."."; ?></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['name']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['nric']; ?></b></td>
		<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;"><b><?php echo $bad_debt['bd_date']; ?></b></td>
     	<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['company']; ?></b></td>
     	<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['mobile_contact']; ?></b></td>
        <!-- <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($bad_debt['balance'],2);?> </b></td> -->
		<td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($balance,2);?> </b></td>
    </tr>
<?php } }else{
?> <tr id="tr_list" >
    	<td colspan="7" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
    </tr>
<?php }?>
    <tr>
    	<td colspan="5" style="text-align: right;border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">Total</td>
        <td colspan="2" style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($total_bd_amount,2);?> </b></td>
    </tr>
    <tr>
    	<td colspan="6" align="right">&nbsp;</td>
    </tr>
     <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../baddebt/'" value=""></td>
    </tr>
</table>
</center>
<script>
