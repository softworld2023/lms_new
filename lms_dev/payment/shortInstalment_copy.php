<?php 
include('../include/page_header.php'); 

if(isset($_POST['search']))
{
	$cond = "WHERE 1=1";

	if($_POST['customer_code'] != '')
	{
		$customer_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".mysql_real_escape_string($_POST['customer_code'])."'");
		$cust = mysql_fetch_assoc($customer_sql);
		$cond .= " AND short_record.customer_id = '".$cust['id']."'";	
	}

	if($_POST['customer_name'] != '')
	{
		$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".mysql_real_escape_string($_POST['customer_name'])."'");
		$cust = mysql_fetch_assoc($customer_sql);
		$cond .= " AND short_record.customer_id = '".$cust['id']."'";	
	}
	
	if($_POST['loan_code'])
	{
		$cond .= " AND short_record.loan_code = '".mysql_real_escape_string($_POST['loan_code'])."'";
	}
}
else
{
	$cond = ""; // default to no filter
}

$sql = mysql_query("
	SELECT 
		short_record.loan_code, 
		short_record.customer_id, 
		SUM(short_record.amount) AS total_amount, 
		SUM(short_record.balance) AS total_balance
	FROM short_record
	$cond
	GROUP BY short_record.loan_code, short_record.customer_id
	ORDER BY short_record.id DESC
");
?>

<style>
.submit_style, .app_style, .reject_style {
	color: #eee;
	padding: 4px;
	border: none;
	background-size: 21px 21px;
	cursor: pointer;
	text-indent: -1000em;
	width: 25px;
}
.submit_style { background: transparent url("<?php echo IMAGE_PATH.'remove.png'; ?>") no-repeat; }
.app_style { background: transparent url("<?php echo IMAGE_PATH.'sent.png'; ?>") no-repeat; }
.reject_style { background: transparent url("<?php echo IMAGE_PATH.'cancel-icon.png'; ?>") no-repeat; }

#list_table {
	border-collapse: collapse;
	border: none;	
	width: 1280px;
}
#list_table tr th {
	height: 36px;
	background: #666;
	text-align: left;
	padding-left: 10px;
	color: #FFF;
}
#list_table tr td {
	height: 35px;
	padding-left: 10px;
	padding-right: 10px;
}
.group-header:hover {
	background-color: #dfe8f6;
}
.details-row td {
	background-color: #f9f9f9;
}
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Short</td>
        <td align="right">
       	<form action="" method="post">
        	<table>
            	<tr>
					<td align="right" style="padding-right:10px">Customer ID</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px" /></td>
                	<td align="right" style="padding-right:10px">Customer Name</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Agreement No</td>
                    <td style="padding-right:30px"><input type="text"
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    
                    </td>
                </tr>
                <tr><td colspan="8" style="text-align:right;"><input type="submit" id="search_1" name="search" value="Show all list"/></td></tr>
            </table>
        </form>
        </td>
    </tr>
</table>

<table id="list_table">
	<tr>
    	<th>No.</th>
    	<th>Agreement No.</th>
    	<th>Customer ID</th>
    	<th>Customer Name</th>
    	<th>Short Date</th>
        <th>Short Amount</th>
        <th>Balance</th>
        <th>Action</th>
    </tr>

<?php
$ctr = 0;
$totout = 0;

while ($group = mysql_fetch_assoc($sql)) {
	$ctr++;
	$loan_code = $group['loan_code'];
	$customer_id = $group['customer_id'];

	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '$customer_id'");
	$cust = mysql_fetch_assoc($cust_q);
	$customer_code = $cust['customercode2'];
	$customer_name = $cust['name'];

	$details_q = mysql_query("
		SELECT * FROM short_record 
		WHERE loan_code = '$loan_code' AND customer_id = '$customer_id'
	");

	echo "
	<tr class='group-header' onclick=\"toggleDetails('$ctr')\">
		<td>$ctr.</td>
		<td>$loan_code</td>
		<td>$customer_code</td>
		<td>$customer_name</td>
		<td>-</td>
		<td>RM " . number_format($group['total_amount'], 2) . "</td>
		<td>RM " . number_format($group['total_balance'], 2) . "</td>
		<td>▼</td>
	</tr>
	";

	while ($row = mysql_fetch_assoc($details_q)) {
		$totout += $row['balance'];
		echo "
		<tr class='details-row details-$ctr' style='display:none;'>
			<td></td>
			<td colspan='2'>Short Type: " . htmlspecialchars($row['short_type']) . "</td>
			<td>Date: " . $row['short_date'] . "</td>
			<td></td>
			<td>RM " . number_format($row['amount'], 2) . "</td>
			<td>RM " . number_format($row['balance'], 2) . "</td>
			<td>
				<a href='payShort.php?id=" . $row['id'] . "' title='View Payment Record'>
					<img src='../img/report-icon.png' />
				</a>
			</td>
		</tr>
		";
	}
}
?>

	<tr>
		<td colspan="6"></td>
		<td style="background:#CCCCCC"><?php echo "RM ".number_format($totout, 2); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="8" align="right">
			<input type="button" name="back" id="back" onClick="history.back();" value="">
		</td>
	</tr>
</table>
</center>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js"></script>

<script>
function toggleDetails(groupId) {
	$('.details-' + groupId).toggle();
}

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
</script>
