<?php 
include('../include/page_header.php'); 
// ini_set( 'display_errors', 1 );
// ini_set( 'display_startup_errors', 1 );
// error_reporting( E_ALL );
// Accept year & month from GET or POST
$selected_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
$selected_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('m');

// =============================
//  Map month names to numbers
// =============================
$month_map = [
    'January' => '01',
    'February' => '02',
    'March' => '03',
    'April' => '04',
    'May' => '05',
    'June' => '06',
    'July' => '07',
    'August' => '08',
    'September' => '09',
    'October' => '10',
    'November' => '11',
    'December' => '12'
];

// Always start with WHERE 1=1
$cond = "WHERE 1=1";

// POST search filters
if (isset($_POST['search'])) {
    if (!empty($_POST['customer_code'])) {
        $customer_sql = mysql_query("SELECT * FROM customer_details 
            WHERE customercode2 = '".mysql_real_escape_string($_POST['customer_code'])."'");
        if ($cust = mysql_fetch_assoc($customer_sql)) {
            $cond .= " AND short_record.customer_id = '".$cust['id']."'";    
        }
    }

    if (!empty($_POST['customer_name'])) {
        $customer_sql = mysql_query("SELECT * FROM customer_details 
            WHERE name = '".mysql_real_escape_string($_POST['customer_name'])."'");
        if ($cust = mysql_fetch_assoc($customer_sql)) {
            $cond .= " AND short_record.customer_id = '".$cust['id']."'";    
        }
    }

    if (!empty($_POST['loan_code'])) {
        $cond .= " AND short_record.loan_code = '".mysql_real_escape_string($_POST['loan_code'])."'";
    }
}

// // GET year/month filters
// if (!empty($selected_year) && !empty($selected_month)) {
//     $start_date = $selected_year . '-' . $selected_month;
//     $end_date   = date('Y-m', strtotime($start_date));
//     $cond .= " AND short_record.month_receipt BETWEEN '" . mysql_real_escape_string($start_date) . "' 
//                AND '" . mysql_real_escape_string($end_date) . "'";
// }

// Final query
$sql = mysql_query("SELECT 
        short_record.loan_code, 
        short_record.customer_id, 
        SUM(short_record.amount) AS total_amount, 
        SUM(short_record.balance) AS total_balance
    FROM short_record
    $cond
	AND short_record.balance <= 0
    GROUP BY short_record.loan_code, short_record.customer_id
    ORDER BY MAX(short_record.id) DESC
");

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
.group-header {
	background-color: #e8f4ff;
	cursor: pointer;
}
.group-header:hover {
	background-color: #d0eaff;
}
.details-row td {
	border-top: 1px solid #ccc;
}
/* Tab style for links */
.tab-nav {
    overflow: hidden;
    border-bottom: 2px solid #ccc;
    margin-bottom: 10px;
}

.tab-nav a {
    float: left;
    display: block;
    padding: 10px 16px;
    text-decoration: none;
    font-size: 16px;
    background-color: #f1f1f1;
    color: black;
    border: 1px solid #ccc;
    border-bottom: none;
    margin-right: 5px;
}

.tab-nav a.active {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

.tab-nav a:hover {
    background-color: #ddd;
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
			<a href="lateIntPayment.php">Late Payment Listing</a>
			<a href="collection.php">Collection</a>
			<a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="return_book_monthly.php">Monthly</a>
            <a href="return_book_instalment.php">Return Book</a>
			<a href="account_book_monthly.php">Account Book (Monthly)</a>
            <a href="account_book_instalment.php">Account Book (Instalment)</a>
			<a href="shortInstalment.php" id="active-menu">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
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
</table><br>
<table width="1280" id="list_table">
    <tr>
        <td colspan="2">
            <!-- Tab Navigation -->
            <div class="tab-nav">
                <a href="shortInstalment.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'shortInstalment.php' ? 'active' : ''; ?>">Short</a>
                <a href="short_history.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'short_history.php' ? 'active' : ''; ?>">History</a>
            </div>
            </table>
            <br>
        </td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
    	<th>Agreement No.</th>
    	<th>Customer ID</th>
    	<th>Customer Name</th>
    	<th>Short Date</th>
        <th width="150">Short Amount</th>
        <th width="150">Balance</th>
        <th width="50"></th>
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

	// $month_receipt = $selected_year.'-'.$selected_month;

	// Fetch detail rows
	$details_q = mysql_query("SELECT * FROM short_record 
		WHERE loan_code = '$loan_code' AND customer_id = '$customer_id' AND short_record.balance <= 0
		-- AND month_receipt >= '$month_receipt' 
		ORDER BY month_receipt ASC
	");

	// if($group['month_receipt'] >= $month_receipt){
		// Group header
		echo "
		<tr class='group-header' onclick=\"toggleDetails('$ctr')\">
			<td><strong>$ctr.</strong></td>
			<td><strong>$loan_code</strong></td>
			<td><strong>$customer_code</strong></td>
			<td><strong>$customer_name</strong></td>
			<td>-</td>
			<td><strong>RM " . number_format($group['total_amount'], 2) . "</strong></td>
			<td><strong>RM " . number_format($group['total_balance'], 2) . "</strong></td>
			<td><strong>▼</strong></td>
		</tr>
		";

		$totout += $group['total_balance'];

		// Detail rows
		while ($row = mysql_fetch_assoc($details_q)) {
			// $totout += $row['balance'];
			echo "
			<tr class='details-row details-$ctr' style='display:none; background:#f9f9f9'>
				<td></td>
				<td><small style='color:#888;'>" . htmlspecialchars($row['month_receipt']) . "</small></td>
				<td colspan='2'><small style='color:#888;'>Type: " . htmlspecialchars($row['short_type']) . "</small></td>
				<td>{$row['short_date']}</td>
				<td>RM " . number_format($row['amount'], 2) . "</td>
				<td>RM " . number_format($row['balance'], 2) . "</td>
				<td>
					<a href='payShort.php?id={$row['id']}' title='View Payment Record'>
						<img src='../img/report-icon.png' />
					</a>
				</td>
			</tr>
			";
		}

	}
// }

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
    <!-- <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="history.back();" value=""></td>
    </tr> -->
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
</table>
</center>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js"></script>

<script>

function toggleDetails(groupId) {
	const rows = document.querySelectorAll('.details-' + groupId);
	rows.forEach(row => {
		row.style.display = (row.style.display === 'none') ? '' : 'none';
	});
}

function searchYearMonth() {
	let year = $('#selected_year').val();
	let month = $('#selected_month').val();
	window.location.href = 'shortInstalment.php?year=' + year + '&month=' + month;
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
