<?php 
include('../include/page_header.php'); 

if(isset($_POST['search']))
{
	if($_POST['customer_code'] != '')
	{
		$customer_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$_POST['customer_code']."'");
		$cust = mysql_fetch_assoc($customer_sql);
		$cond .= " and late_interest_record.customer_id = '".$cust['id']."'";	
	}

	if($_POST['customer_name'] != '')
	{
		$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".$_POST['customer_name']."'");
		$cust = mysql_fetch_assoc($customer_sql);
		$cond .= " and late_interest_record.customer_id = '".$cust['id']."'";	
	}
	
	if($_POST['loan_code'])
	{
		$cond .= " and late_interest_record.loan_code = '".$_POST['loan_code']."'";
	}
	
	if($_POST['loan_package'] != '')
	{
		$cond .= " and late_interest_record.package_id = '".$_POST['loan_package']."'";
	}
	
	// $statement = "`late_interest_record` WHERE branch_id = '".$_SESSION['login_branchid']."' $cond AND package_id = '32'";
	// $statement = "late_interest_record LEFT JOIN monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code WHERE 1 = 1 $cond AND late_interest_record.package_id = '32' AND (monthly_payment_record.status = 'BAD DEBT' OR monthly_payment_record.status IS NULL)";
	$statement = "late_interest_record LEFT JOIN monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code WHERE 1 = 1 $cond AND late_interest_record.package_id = '32' AND (monthly_payment_record.status != 'DELETED' OR monthly_payment_record.status IS NULL)";

	$_SESSION['payment_ls'] = $statement;
}
else
if($_SESSION['payment_ls'] != '')
{
	$statement = $_SESSION['payment_ls'];
}
else
{
	// $statement = "`late_interest_record` WHERE status = '' AND branch_id = '".$_SESSION['login_branchid']."'AND package_id = '32'";
	// $statement = "late_interest_record LEFT JOIN monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code WHERE late_interest_record.status = '' AND late_interest_record.package_id = '32' AND (monthly_payment_record.status = 'BAD DEBT' OR monthly_payment_record.status IS NULL)";
	$statement = "late_interest_record LEFT JOIN monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code WHERE late_interest_record.status = '' AND late_interest_record.package_id = '32' AND (monthly_payment_record.status != 'DELETED' OR monthly_payment_record.status IS NULL)";
}

// var_dump("SELECT late_interest_record.* FROM {$statement} ORDER BY late_interest_record.id");

$sql = mysql_query("SELECT late_interest_record.* FROM {$statement} GROUP BY late_interest_record.customer_id ORDER BY late_interest_record.id");


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
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Late Interest Payment</td>
        <td align="right">
       	<form action="" method="post">
        	<table>
            	<tr>
					<td align="right" style="padding-right:10px">Customer ID</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px" /></td>
                	<td align="right" style="padding-right:10px">Customer Name</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Agreement No</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
<!-- 					<td align="right" style="padding-right:10px">Package</td>
                    <td style="padding-right:30px">
                    	<select name="loan_package" id="loan_package" style="height:30px">
                        	<option value="">All</option>
                            <?php
							$package_q = mysql_query("SELECT * FROM loan_scheme");
							while($get_p = mysql_fetch_assoc($package_q)){
							?>
                            <option value="<?php echo $get_p['id']; ?>"><?php echo $get_p['scheme']; ?></option>
                            <?php } ?>
                        </select>                    </td> -->
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
                </tr>
                <tr><td colspan="5">&nbsp;</td></tr>
                <tr><td colspan="5" style="text-align:right;"><input type="submit" id="search_1" name="search" value="Show all list"/></td></tr>
            </table>
        </form>
        </td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	<?php 
	//check skim kutu exist or not
	$skimkutu_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM KUTU'");
	$skimkutu = mysql_num_rows($skimkutu_q);
	
	//check kutu office exist or not
	$kutuOffice_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM KUTU'");
	$kutuOffice = mysql_num_rows($kutuOffice_q);	
	
	?>
	<tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Ledger Listing</a>
			<a href="payment_monthly.php">Monthly Listing</a>
			<a href="payment_instalment.php">Instalment Listing</a>
			<a href="lateIntPayment.php" id="active-menu">Late Payment Listing</a>
			<a href="collection.php">Collection</a>
			<a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="close_listing.php">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
			<a href="return_book_monthly.php">Return Book (Monthly)</a>
            <a href="return_book_instalment.php">Return Book (Instalment)</a>
			<a href="account_book_monthly.php">Account Book (Monthly)</a>
            <a href="account_book_instalment.php">Account Book (Instalment)</a>
			<!-- <?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?>
			<?php if($kutuOffice != 0) {?><a href="kutuOffice.php">Kutu Office</a><?php } ?> -->
		</div>	
<!-- 		<div style="float:right">
			<a href="add_lateCust.php" title="New Late Payment Customer">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Add New Bad Debt </td>
                	</tr>
                </table>
            </a>
		</div> -->
        </td>
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
    	<th>Agreement No.</th>
    	<th>Customer ID</th>
    	<th>Customer Name</th>
    	<th>BD Date</th>
        <th width="150">Bad Debt Amount</th>
        <th width="150">Balance</th>
        <th width="50"></th>
    </tr>
    <?php 
		$ctr = 0;
		$totout = 0;
		while($get_q = mysql_fetch_assoc($sql)){ 
			$ctr++;
			$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
			$get_cust = mysql_fetch_assoc($cust_q);
			
			//package
			$scheme_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['package_id']."'");
			$get_scheme = mysql_fetch_assoc($scheme_q);

			$balance = 0;
			$previous_monthly_bd = 0;
			$envelope = 0;
			$deductible = 0;
			$previous_months_bd_collected = 0;
										
			$query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_amount, SUM(balance) AS previous_monthly_bd FROM monthly_payment_record WHERE loan_code = '" . $get_q['loan_code'] . "' AND status = 'BAD DEBT'");
			$result_monthly_payment_record = mysql_fetch_assoc($query);

			$previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
			$previous_monthly_amount = $result_monthly_payment_record['previous_monthly_amount'];

			$amount = $get_q['amount'];

			if ($get_q['bd_from'] == 'Monthly') {
				$amount = $previous_monthly_amount - $previous_monthly_bd;
			}

			$query = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '" . $get_q['loan_code'] . "'");
			$result_late_interest_record = mysql_fetch_assoc($query);

			$late_interest_record_id = $result_late_interest_record['id'];
			$bd_from = $result_late_interest_record['bd_from'];
			$branch_name = strtolower($result_late_interest_record['branch_name']);

			if ($get_q['loan_code'] == 'KT 20032') {
				$previous_monthly_bd = 680;
			}

			// somehow this check is required to identify whether need to deduct previous monthly bd from amount,
			// because some old records have mixed up the amount and monthly bd
			if ($branch_name == 'majusama' && $bd_from == 'Instalment + Monthly' && $late_interest_record_id > 46) {
				$amount -= $previous_monthly_bd;
			} else if ($branch_name != 'majusama' && ($bd_from == 'Instalment + Monthly' || $bd_from == 'Monthly')) {
				$amount -= $previous_monthly_bd;
			}

			$query = mysql_query("SELECT MIN(balance) AS min_balance FROM loan_payment_details WHERE receipt_no = '" . $get_q['loan_code'] . "'");
			$result_loan_payment_details = mysql_fetch_assoc($query);

			$loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];

			$envelope = $loan_payment_details_min_balance - $amount;
			$envelope_without_instalment = $envelope;

			if ($get_q['loan_code'] == 'KT 20034') {
				$envelope += 9157;
			}

			$query = mysql_query("SELECT SUM(amount) AS collected FROM late_interest_payment_details WHERE lid = '" . $late_interest_record_id . "'");
			while ($row = mysql_fetch_assoc($query)) {
				$previous_months_bd_collected += round($row['collected'],2);
			}

			// if ($get_q['bd_from'] != 'Monthly') {
				$amount -= $previous_months_bd_collected;
			// }

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
			
			$totout += $balance;
			?>
		    <tr>
		    	<td><?php echo $ctr."."; ?></td>
		    	<td><?php echo $get_q['loan_code']; ?></td>
		    	<td><?php echo $get_cust['customercode2']; ?></td>
		    	<td><?php echo $get_cust['name']; ?></td>
		    	<td><?php echo date("d-m-Y",strtotime($get_q['bd_date'])); ?></td>
		    	<!-- <td><?php echo "RM ".number_format($get_q['amount'],'2'); ?></td> -->
				<td><?php echo "RM ".number_format($amount,'2'); ?></td>
		        <!-- <td><?php echo "RM ".number_format($get_q['balance'],'2'); ?></td> -->
				<td><?php echo "RM ".number_format($balance,'2'); ?></td>
		        <td><a href="payLateInt.php?id=<?php echo $get_q['id']; ?>" title="View Payment Record"><img src="../img/report-icon.png" /></a></td>
		    </tr>
    <?php 
		} 
	?>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="background:#CCCCCC"><?php echo "RM ".number_format($totout, '2'); ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="history.back();" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
</table>
</center>
<script>
$(document).ready(function() {
	$("#customer_code").autocomplete("auto_custCode.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});

   	$("#customer_name").autocomplete("auto_custName.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	$("#loan_code").autocomplete("auto_loanCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
  
});

$('#month').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
keydown(
	function(e)
	{
		var key = e.keyCode || e.which;
		if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
		{
			$(this).off('keydown').AnyTime_picker().focus();
			e.preventDefault();
		}
	} );
</script>
