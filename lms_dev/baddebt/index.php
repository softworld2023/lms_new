<?php 
include('../include/page_headercb.php'); 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
#tr_list:hover {background-color: lightgrey;}

</style>
<center>
<table width="1280">
	<tr>
    	<td width="134"><img src="../img/cash-register-icon.png" width="56"></td>
        <td width="401">Bad Debt (All Branch)</td>
        <td width="729" align="right">

	  </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	
	</table>
	
<table width="840" id="list_table" >

	<tr>
    	<th width="80" style="border:1px solid black;">No.</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
        <th width="150" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Total Loan Balance</th>
    </tr>

	<?php
		$login_branch = $_SESSION['login_branch'];
		$branch_name_arr = array();
		$db_name_arr = array();

		if ($login_branch == 'KTL' || $login_branch == 'TSY'|| $login_branch == 'TSY2') {
			$branch_name_arr = array('MAJUSAMA', 'ANSENG', 'YUWANG', 'DK', 'KTL', 'TSY', 'TSY2');
			$db_name_arr = array('majusama_dev', 'anseng', 'yuwang', 'ktl', 'tsy','dk', 'tsy2');
		} else {
			$branch_name_arr = array('MAJUSAMA', 'ANSENG', 'YUWANG', 'DK');
			$db_name_arr = array('majusama_dev', 'anseng', 'yuwang', 'dk');
		}

		$total_loan_balance = 0;

		for ($i = 0, $len = count($branch_name_arr); $i < $len; $i++) {
			$db_name = $db_name_arr[$i];
			$branch = $branch_name_arr[$i];
			$subtotal_loan_balance = 0;

			// $sql = "SELECT " . $db_name . ".late_interest_record.* FROM " . $db_name . ".late_interest_record LEFT JOIN " . $db_name . ".monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code WHERE monthly_payment_record.status = 'BAD DEBT' OR monthly_payment_record.status IS NULL GROUP BY " . $db_name . ".late_interest_record.customer_id";
			$sql = "SELECT " . $db_name . ".late_interest_record.* FROM " . $db_name . ".late_interest_record LEFT JOIN " . $db_name . ".monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code WHERE monthly_payment_record.status != 'DELETED' OR monthly_payment_record.status IS NULL GROUP BY " . $db_name . ".late_interest_record.customer_id";

			// var_dump($sql);
			$q = mysql_query($sql);
			while ($result = mysql_fetch_assoc($q)) {
				$balance = 0;
				$balance_without_instalment = 0;
				$previous_monthly_bd = 0;
				$envelope = 0;
				$envelope_without_instalment = 0;
				$deductible = 0;
				$previous_months_bd_collected = 0;
											
				$query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_amount, SUM(balance) AS previous_monthly_bd FROM " . $db_name . ".monthly_payment_record WHERE loan_code = '" . $result['loan_code'] . "' AND status = 'BAD DEBT'");
				$result_monthly_payment_record = mysql_fetch_assoc($query);
	
				$previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
				$previous_monthly_amount = $result_monthly_payment_record['previous_monthly_amount'];
	
				$amount = $result['amount'];
	
				if ($result['bd_from'] == 'Monthly') {
					$amount = $previous_monthly_amount - $previous_monthly_bd;
				}
	
				$query = mysql_query("SELECT * FROM " . $db_name . ".late_interest_record WHERE loan_code = '" . $result['loan_code'] . "'");
				$result_late_interest_record = mysql_fetch_assoc($query);
	
				$late_interest_record_id = $result_late_interest_record['id'];
				$bd_from = $result_late_interest_record['bd_from'];
				$branch_name = strtolower($result_late_interest_record['branch_name']);
	
				if ($result['loan_code'] == 'KT 20032') {
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
	
				$query = mysql_query("SELECT MIN(balance) AS min_balance FROM " . $db_name . ".loan_payment_details WHERE receipt_no = '" . $result['loan_code'] . "'");
				$result_loan_payment_details = mysql_fetch_assoc($query);
	
				$loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];
	
				$envelope = $loan_payment_details_min_balance - $amount;
				$envelope_without_instalment = $envelope;
	
				if ($result['loan_code'] == 'KT 20034') {
					$envelope += 9157;
				}
				// var_dump($envelope);
	
				$query = mysql_query("SELECT SUM(amount) AS collected FROM " . $db_name . ".late_interest_payment_details WHERE lid = '" . $late_interest_record_id . "'");
				while ($row = mysql_fetch_assoc($query)) {
					$previous_months_bd_collected += round($row['collected'],2);
				}
	
				if ($result['bd_from'] != 'Monthly') {
					$amount -= $previous_months_bd_collected;
				}
	
				$balance_without_instalment = $amount_without_instalment + $previous_monthly_bd + $envelope_without_instalment;
				// var_dump($balance_without_instalment);
	
				if ($result['loan_code'] == 'KT 20032' || $result['loan_code'] == 'KT 20034') {
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
	
				$subtotal_loan_balance += $balance;
			}

			$total_loan_balance += $subtotal_loan_balance;
	?>
			<tr id="tr_list" onclick="view('<?php echo $branch; ?>', '<?php echo $db_name; ?>');">
				<td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;"><?php echo $i + 1; ?>.</td>
				<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch; ?></b></td>
				<td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($subtotal_loan_balance,2);?> </b></td>
			</tr>
	<?php
		}
	?>

    <tr>
    	<td colspan="2" style="text-align: right;border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">Total</td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($total_loan_balance,2);?> </b></td>
    </tr>
    
</table>
</center>
<script>
	function view(branch_name, db_name) {
		// console.log(e);
		location.href = "bad_debt_list.php?branch_name=" + branch_name + "&db_name=" + db_name;
	}
	
</script>