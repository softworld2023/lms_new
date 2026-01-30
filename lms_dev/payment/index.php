<?php 
include('../include/page_header.php'); 
error_reporting(E_ERROR | E_PARSE);

$selected_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
$selected_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('m');

$payout_month = date('Y-m');

if ($selected_year != '' && $selected_month != '') {
	$payout_month = $selected_year . '-' . $selected_month;
}

if(isset($_POST['search']))   // click search
{
	$cond = '';
		if($_POST['month'] != '')
		{
			$cond .= " and loan_payment_details.month_receipt = '".$_POST['month']."' 
						and loan_payment_details.payment_date = '0000-00-00'";
						
			$_SESSION['pmonth'] = $_POST['month'];
		}else
		{
			$_SESSION['pmonth'] = '';
		}
		
		if($_POST['customer_name'] != '')
		{
			$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".$_POST['customer_name']."'");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= " and customer_loanpackage.customer_id = '".$cust['id']."'";	
		}
		
		if($_POST['loan_code'])
		{
			$cond .= " and customer_loanpackage.loan_code = '".$_POST['loan_code']."'";
		}
		
		if($_POST['loan_package'] != '')
		{
			$cond .= " and customer_loanpackage.loan_package = '".$_POST['loan_package']."'";
			$_SESSION['loanpackage'] = $_POST['loan_package'];
		}else
		{
			$_SESSION['loanpackage'] = '';
		}
		
		if($_POST['loan_type'] != '')
		{
			$cond .= " and customer_loanpackage.loan_type = '".$_POST['loan_type']."'";
		}
		
		if($_POST['customer_code'] != '')
		{
			//echo 'Is it here?';
			//$code_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$_POST['customer_code']."'");
			//$code = mysql_fetch_assoc($code_sql);
			//$cond .= " and customer_loanpackage.customer_id = '".$code['id']."'";	
			
			$code_sql = mysql_query("SELECT * FROM customer_details 
									WHERE customercode2 = '".$_POST['customer_code']."'");
			while ($code = mysql_fetch_assoc($code_sql))
			{//this is when we have many same customer code at a time
				$idList[] = $code['id'];
			}
			
			$cond .= "and (";
			for($i=0; $i<sizeof($idList); $i++)
			{
				if($i==0)
				{//however we fall in this category lol
					// $cond .= " customer_loanpackage.customer_id = '".$idList[$i]."'
					// 			AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished')";
					$cond .= " customer_loanpackage.customer_id = '".$idList[$i]."'";

					/*$cond .= " customer_loanpackage.customer_id = '".$idList[$i]."'
								AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished')
								AND loan_payment_details.receipt_no = (SELECT loan_payment_details.receipt_number 
									WHERE loan_payment_details.payment_date = (MAX(loan_payment_details.payment_date)))";*/
					
					/*$cond .= " customer_loanpackage.customer_id = '".$idList[$i]."'
								AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished')
								AND loan_payment_details.receipt_no = 03 046";*/
				}
				else{
					// $cond .= " or customer_loanpackage.customer_id = '".$idList[$i]."'
					// 			AND (customer_loanpackage.loan_status = 'Paid' 
					// 			OR customer_loanpackage.loan_status = 'Finished') ";
					$cond .= " or customer_loanpackage.customer_id = '".$idList[$i]."'";
				}
			}
			
			$cond .= ")";
		}
		
		if($_POST['outdate'] != '')
		{
			$outdate = date('Y-m-d', strtotime($_POST['outdate']));
			$_SESSION['cdate'] = $outdate;
			$ccond .= $_SESSION['cdate'];
			//$cond .= " and loan_payment_details.payment_date < '".$outdate."' and (customer_loanpackage.loan_status = 'Paid') AND (customer_loanpackage.payout_date <= '".$_SESSION['cdate']."' OR customer_loanpackage.payout_date = '0000-00-00')";
			
			// $cond .= " and loan_payment_details.payment_date < '".$outdate."' 
			// 			AND (customer_loanpackage.payout_date <= '".$_SESSION['cdate']."' OR customer_loanpackage.payout_date = '0000-00-00')
			// 			AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished') 
			// 			";

			$cond .= " and loan_payment_details.payment_date < '".$outdate."' 
						AND (customer_loanpackage.payout_date <= '".$_SESSION['cdate']."' OR customer_loanpackage.payout_date = '0000-00-00')
						";
		}else
		{
			// $cond .= " and customer_loanpackage.loan_status = 'Paid'";
			// $cond .= " and (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished')";
			$_SESSION['cdate'] = '';
			$ccond .= '';
		}
		
		// $statement = "loan_payment_details, customer_loanpackage
		// 				WHERE customer_loanpackage.id = loan_payment_details.customer_loanid
		// 				AND customer_loanpackage.loan_code NOT LIKE 'MS%'
		// 				AND customer_loanpackage.branch_id = '".$_SESSION['login_branchid']."' $cond 
		// 				GROUP BY customer_loanpackage.id ORDER BY date_year,loan_code ASC
		// 				";

		$statement = "`customer_loanpackage`
					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
					LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
					customer_loanpackage.branch_id = '".$_SESSION['login_branchid']."' $cond
				AND	temporary_payment_details.loan_month = '".$payout_month."'
				AND 
					temporary_payment_details.loan_code NOT IN(SELECT
				customer_loanpackage.loan_code 
				FROM
				customer_loanpackage
				LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
				LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
				customer_loanpackage.loan_package = 'NEW PACKAGE'
				AND(
				loan_payment_details.month_receipt <'".$payout_month."'
				)

				AND loan_payment_details.loan_status='SETTLE' 
				GROUP BY customer_loanpackage.loan_code
				ORDER BY customer_loanpackage.loan_code ASC )
				AND 
					temporary_payment_details.loan_code NOT IN(SELECT
				customer_loanpackage.loan_code 
				FROM
				customer_loanpackage
				LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
				LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
				customer_loanpackage.loan_package = 'NEW PACKAGE'
				AND(
				loan_payment_details.month_receipt <'".$payout_month."'
				)

				AND loan_payment_details.loan_status='BAD DEBT' 
				GROUP BY customer_loanpackage.loan_code
				ORDER BY customer_loanpackage.loan_code ASC )
				GROUP BY customer_loanpackage.loan_code
				ORDER BY
					customer_loanpackage.id, customer_loanpackage.start_month = '".$payout_month."', customer_loanpackage.payout_date ASC";
					
		// $_SESSION['payment_s'] = $statement;
		
		
		$packagecheck_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$_SESSION['loanpackage']."'");
		$packagecheck = mysql_fetch_assoc($packagecheck_q);
		$_SESSION['package_id'] = $packagecheck['id'];
		
		$bal2check_q = mysql_query("SELECT * FROM balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' AND package_id = '".$packagecheck['id']."' AND branch_id = '".$_SESSION['login_branchid']."' GROUP BY customer_loanid");
		$bal2checkrow = mysql_num_rows($bal2check_q);
		
		$loancheck_q = mysql_query("SELECT * FROM loan_payment_details WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$packagecheck['id']."' AND month_receipt = '".$_SESSION['pmonth']."' GROUP BY customer_loanid");
		$loancheckrow = mysql_num_rows($loancheck_q);
}
else    //not click search but have history record
if($_SESSION['payment_s'] != '')
{
		// $statement = $_SESSION['payment_s'];
	
		// $packagecheck_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$_SESSION['loanpackage']."'");
		// $packagecheck = mysql_fetch_assoc($packagecheck_q);
		// $_SESSION['package_id'] = $packagecheck['id'];
		
		// $bal2check_q = mysql_query("SELECT * FROM balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' AND package_id = '".$packagecheck['id']."' AND branch_id = '".$_SESSION['login_branchid']."' GROUP BY customer_loanid");
		// $bal2checkrow = mysql_num_rows($bal2check_q);
		
		// $loancheck_q = mysql_query("SELECT * FROM loan_payment_details WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$packagecheck['id']."' AND month_receipt = '".$_SESSION['pmonth']."' GROUP BY customer_loanid");
		// $loancheckrow = mysql_num_rows($loancheck_q);
}
else    // not click search and also no history result
{
	// $statement = "`customer_loanpackage` WHERE loan_code NOT LIKE 'MS%' AND branch_id = '".$_SESSION['login_branchid']."' and (loan_status = 'Paid' OR loan_status = 'Finished') ORDER BY date_year,loan_code ASC";
	$statement = "`customer_loanpackage`
					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
					LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
					customer_loanpackage.branch_id = '".$_SESSION['login_branchid']."' $cond
				AND	temporary_payment_details.loan_month = '".$payout_month."'
				AND 
					temporary_payment_details.loan_code NOT IN(SELECT
				customer_loanpackage.loan_code 
				FROM
				customer_loanpackage
				LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
				LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
				customer_loanpackage.loan_package = 'NEW PACKAGE'
				AND(
				loan_payment_details.month_receipt <'".$payout_month."'
				)

				AND loan_payment_details.loan_status='SETTLE' 
				GROUP BY customer_loanpackage.loan_code
				ORDER BY customer_loanpackage.loan_code ASC )
				AND 
					temporary_payment_details.loan_code NOT IN(SELECT
				customer_loanpackage.loan_code 
				FROM
				customer_loanpackage
				LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
				LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
				customer_loanpackage.loan_package = 'NEW PACKAGE'
				AND(
				loan_payment_details.month_receipt <'".$payout_month."'
				)

				AND loan_payment_details.loan_status='BAD DEBT' 
				GROUP BY customer_loanpackage.loan_code
				ORDER BY customer_loanpackage.loan_code ASC )
				GROUP BY customer_loanpackage.loan_code
				ORDER BY
					customer_loanpackage.id, customer_loanpackage.start_month = '".$payout_month."', customer_loanpackage.payout_date ASC";
}

$sql_q = ("SELECT customer_loanpackage.*, YEAR(customer_loanpackage.loan_lejar_date) AS date_year FROM {$statement} ");
// var_dump($sql_q);
$sql = mysql_query($sql_q);

     
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
#yearlyLedger {
  background: #fa900f;
  background-image: -webkit-linear-gradient(top, #fa900f, #fa900f);
  background-image: -moz-linear-gradient(top, #fa900f, #fa900f);
  background-image: -ms-linear-gradient(top, #fa900f, #fa900f);
  background-image: -o-linear-gradient(top, #fa900f, #fa900f);
  background-image: linear-gradient(to bottom, #fa900f, #fa900f);
  font-family: Arial;
  color: #ffffff;
  font-size: 14px;
  padding: 8px 20px 8px 20px;
  border: solid #ffbb0f 0px;
  text-decoration: none;
  cursor:pointer;
}

#yearlyLedger:hover {
  background: #f5a94c;
  background-image: -webkit-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -moz-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -ms-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -o-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: linear-gradient(to bottom, #f5a94c, #f5a94c);
  text-decoration: none;
}

.customBtn {
   border-top: 1px solid #96d1f8;
   background: #fa9c00;
   background: -webkit-gradient(linear, left top, left bottom, from(#cc7e00), to(#fa9c00));
   background: -webkit-linear-gradient(top, #cc7e00, #fa9c00);
   background: -moz-linear-gradient(top, #cc7e00, #fa9c00);
   background: -ms-linear-gradient(top, #cc7e00, #fa9c00);
   background: -o-linear-gradient(top, #cc7e00, #fa9c00);
   padding: 5px 10px;
   -webkit-border-radius: 8px;
   -moz-border-radius: 8px;
   border-radius: 8px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   }
.customBtn:hover {
   border-top-color: #fa9c00;
   background: #fa9c00;
   color: #ccc;
   }
.customBtn:active {
   border-top-color: #fa9c00;
   background: #fa9c00;
   }	
   
  .customBtn a {
   color: #FFFFFF;
   }
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Payment</td>
        <td align="right">
       	<form action="index.php" method="post">
        	<table>
				<tr>
                    <td align="right" style="padding-right:10px">Customer ID</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px"/></td>
					<td align="right" style="padding-right:10px">Customer Name</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Agreement No</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
                    <td style="padding-right:8px">
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
	
	<tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php" id="active-menu">Ledger Listing</a>
			<a href="payment_monthly.php">Monthly Listing</a>
			<a href="payment_instalment.php" >Instalment Listing</a>
			<a href="lateIntPayment.php">Late Payment Listing</a>
			<a href="collection.php">Collection</a>
			<a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="close_listing.php">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
			<a href="return_book_monthly.php">Return Book (Monthly)</a>
            <a href="return_book_instalment.php">Return Book (Instalment)</a>
			<a href="account_book_monthly.php">Account Book (Monthly)</a>
            <a href="account_book_instalment.php">Account Book (Instalment)</a>
			<!-- <?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?><?php if($kutuOffice != 0) {?><a href="kutuOffice.php">Kutu Office</a> --><?php } ?>
		</div>
<!-- 		<div style="float:right">
			<a href="index_show_all.php">Show All List</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="yearlyledger.php"><input type="button" value="Yearly Ledger" id="yearlyLedger" /></a>
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

<?php 
//SEARCH ALL
if($_SESSION['cdate'] != '' && $_SESSION['pmonth'] != '' && $_SESSION['loanpackage'] != '') { ?>

<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
		<th>Customer ID</th>
    	<th>Customer Name</th>
    	<th>Code</th>
        <th>Loan Amount</th>
        <th>Outstanding</th>
        <th>Package</th>
        <th>Loan Type</th>
        <th colspan="2">Lampiran</th>
		<th colspan="2">Lampiran 2</th>
        <th width="50"></th>
    </tr>
    <?php 

	
	$ctr = 0;
	$totout = 0;
	
	$statement = "balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' 
					AND package_id = '".$_SESSION['package_id']."' 
					AND branch_id = '".$_SESSION['login_branchid']."' 
					AND bal_date <= '".$_SESSION['cdate']."' $cond  
					GROUP BY customer_loanid";
					
	$_SESSION['payment_s'] = $statement;
	
	$sql = mysql_query("SELECT * FROM balance_rec 
						WHERE month_receipt = '".$_SESSION['pmonth']."' 
						AND package_id = '".$_SESSION['package_id']."' 
						AND branch_id = '".$_SESSION['login_branchid']."' 
						AND bal_date <= '".$_SESSION['cdate']."' 
						GROUP BY customer_loanid LIMIT {$fromRecordNum}, {$recordsPerPage}");

	
	$startno = $fromRecordNum + 1;       
	
	//$r = mysql_num_rows($sql);
	//this is how to get number of rows returned
	$num = mysql_num_rows($sql);
	// var_dump($num);

if($num>0){	

	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$loan = mysql_fetch_assoc($loan_q);
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
	// var_dump($cust_q);
	$get_cust = mysql_fetch_assoc($cust_q);

	

	if($get_cust['name'] == ''){
		continue;
	}
	
	$style = '';
	
	if($loan['bad_debt'] == 'Yes')
	{
		$style = "style='color:#FF0000'";
	}
		
	//LOAN OUTSTANDING WITH MONTH AND DATE AND PACKAGE NOT EMPTY  ***********************************************
	$loancheck_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec 
								WHERE customer_loanid = '".$get_q['customer_loanid']."' 
								AND bal_date <= '".$_SESSION['cdate']."' 
								AND month_receipt = '".$_SESSION['pmonth']."'");
								
	$loancheck = mysql_fetch_assoc($loancheck_q);
	
	if($loancheck['loan'] == '0'){	
	
	//detect old data
	
	$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND month = '1'");
	$gouts = mysql_fetch_assoc($outst_q);
	
	$loan1_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec WHERE customer_loanid = '".$get_q['customer_loanid']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
			$loan1 = mysql_fetch_assoc($loan1_q);
			
			$bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_rec WHERE customer_loanid = '".$get_q['customer_loanid']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
			$bal1 = mysql_fetch_assoc($bal1_q);
			
			$outbalance = $gouts['balance'] - $bal1['received'];
			
	}
	else{
	
	
	//detect new data
	$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND month = '1'");
	$gouts = mysql_fetch_assoc($outst_q);
	
	$loan1_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec WHERE customer_loanid = '".$get_q['customer_loanid']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
			$loan1 = mysql_fetch_assoc($loan1_q);
			
			$bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_rec WHERE customer_loanid = '".$get_q['customer_loanid']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
			$bal1 = mysql_fetch_assoc($bal1_q);
			
			$outbalance = $loan1['loan'] - $bal1['received'];
	}
			
			/*
	
	$outb_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec WHERE customer_loanid = '".$get_q['customer_loanid']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
	$outb = mysql_fetch_assoc($outb_q);
	$outb_q2 = mysql_query("SELECT SUM(received) AS received FROM balance_rec WHERE customer_loanid = '".$get_q['customer_loanid']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
	$outb2 = mysql_fetch_assoc($outb_q2);
	$outbalance = $outb['loan'] - $outb2['received'];
	
	*/
	
	$totout += $outbalance;
	
	
	
	?>
    <tr <?php echo $style; ?>>
    	<td><?php echo $startno."."; ?></td>
		<td align="center"><?php echo $get_cust['customercode2']; ?></td>
    	<td><?php echo $get_cust['name']; ?></td>
    	<td><?php echo $loan['loan_code']; ?></td>
        <td><?php echo "RM ".number_format($loan['loan_amount'], '2'); ?></td>
        <td><?php echo "RM ".number_format($outbalance, '2'); ?></td>
        <td><?php echo $loan['loan_package']; ?></td>
        <td><?php echo $loan['loan_type']; ?></td>
       <!--  <td><a href="lampiran_KPKT.php?id=<?php echo $loan['id']; ?>" target="_blank">First Schedule</a></td> -->
        <td><a href="lejar.php?id=<?php echo $loan['loan_code']; ?>" target="_blank">LEJAR</a></td>
        <td>
        <!--<?php if(strpos($loan['loan_package'], 'SKIM F') === FALSE)
		{
		?>
        	<a href="payloan_a.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>   
        <?php } else { ?>
        	<a href="payloan.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
        <?php } ?>     -->
        
        	<?php
				if($loan['loan_type'] == 'Flexi Loan')
				{
					$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".mysql_real_escape_string($loan['loan_package'])."'");
					$get_rt = mysql_fetch_assoc($rt_q);
					
					if($get_rt['receipt_type'] == 1)//receipt code still the same
					{
			?>
            		<a href="payloan_f.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
            <?php 
					}
					
					if($get_rt['receipt_type'] == 2)//receipt code changing
					{
			?>
					<a href="payloan.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
			<?php
					}
				}
				if($loan['loan_type'] == 'Fixed Loan')
				{
					if(strpos($loan['loan_package'], 'SKIM CEK') === FALSE)
					{
			?>
            			<a href="lejar_a.php?id=<?php echo $loan['id']; ?>" title="Lejar"><img src="../img/home/report.png" width="35px" height="35px" /></a> 
            			<a href="payloan_a.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
            			<a href="javascript:deleteConfirmation('<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
            		
            <?php 
					}else
					{
			?>
						<a href="payloan_CEK.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> 
			<?php
					}
				}
			?>        
		</td>
		<td>
			<a href="lejar2.php?id=<?php echo $get_q['loan_code']; ?>" target="_blank">LEJAR 2</a>
		</td>
		<td>
			<?php
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
							<a href="payloan.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>&nbsp;
				<?php
						}
					}

					if($get_q['loan_type'] == 'Fixed Loan')
					{
						if(strpos($get_q['loan_package'], 'SKIM CEK') === FALSE)
						{
				?>			&nbsp;<a href="lejar_payment_receipt2.php?id=<?php echo $get_q['loan_code']; ?>" target="_blank"><img src="../img/home/payment_receipt.png" alt="" style="height:25px;" title="View Payment Receipt" /></a> 
				<?php 
						}
					}
				?>
		</td>
    </tr>
    <?php $startno++;} ?>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td style="background:#CCCCCC"><?php echo "RM ".number_format($totout, '2'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
    </tr>
    <!--<tr>
    	<td colspan="10" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="10">&nbsp;</td>
    </tr>-->
</table>
<br/>
<?php
	 


	}
 
// tell the user if no records were found
else{
    echo "<div style='position:absolute; padding-top:70px;' class='noneFound'>
	<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >
				No records found.
			</td></tr>
		</table>
	</div>";
}
?>
<?php } 
// outstanding ........
else { ?>
<table width="1280" id="list_table">
	<tr>
     	<td colspan="2">       
     		<form method="post">
				<table width="100%" border='0'>
					<tr>              
						<td style="font-size: 16px;"><br>YEAR
							<select 
								id="selected_year"
								name="selected_year"
								class="form-control"
								style="width: 120px;height: 30px; font-size:16px;">
							<?php
								$current_year = idate('Y');
								$future_year = idate('Y', strtotime($current_year. ' + 5 years'));
								$past_year = idate('Y', strtotime($current_year. ' - 3 years'));

								for ($i = $past_year; $i <= $future_year; $i++) {
									$selected = $selected_year == $i ? 'selected' : '';
							?>
									<option value='<?php echo $i; ?>' <?php echo $selected; ?>><?php echo $i; ?></option>
							<?php
								}
							?>
							</select>
							MONTH
							<select id="selected_month" name="selected_month" style="width: 120px;height: 30px; font-size:16px;">
							<?php

								for ($i = 1; $i <= 12; $i++) {
									$month_with_zero = str_pad($i, 2, '0', STR_PAD_LEFT);
									$selected = $selected_month == $month_with_zero ? 'selected' : '';
							?>

									<option value='<?php echo $month_with_zero; ?>' <?php echo $selected; ?>><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
							<?php
								}
							?>
							</select>
							<input class="btn btn-blue" type="button" id="search_ledger" name="search_ledger" value="Search" style="width: 100px; height: 30px; font-size:16px;">
						</td>
						<td style="font-size: 16px;"></td>
					</tr>
				</table>
			</form>
        	<br>
     	</td>
     </tr>
 </table>
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
		<th>Agreement No</th>
    	<th>Customer ID</th>
    	<th width="20%">Customer Name</th>
        <th>Loan Amount</th>
        <th>Outstanding</th>
        <th>Package</th>
        <th>Loan Type</th>
        <th colspan="2">Lampiran</th>
		<th colspan="2">Lampiran 2</th>
        <th width="50"></th>
    </tr>
    <?php 
	// $ctr = 0;
	$totout = 0;
	
	$startno = $fromRecordNum + 1; 
	$r = mysql_num_rows($sql);
	$i=0;
	if($r>0){	
		$currentDate = false;
	while($get_q = mysql_fetch_assoc($sql)){
		// if (!(substr($get_q['loan_code'],0,2) == 'BP' || substr($get_q['loan_code'],0,2) == 'KT' || substr($get_q['loan_code'],0,2) == 'AS' || substr($get_q['loan_code'],0,2) == 'TS')) {
		// 	continue;
		// }

		if (substr($get_q['loan_code'],0,2) == 'MJ' || substr($get_q['loan_code'],0,2) == 'TL') {
			continue;
		}

		$i++;
		if ($get_q['date_year'] != $currentDate){     
				$i=1;
        ?>
        <!-- <tr style="background-color: #45b1e8;">
          <td colspan='11' style="color:black;font-size: 22px;"><b><?php echo 'Tahun '.$get_q['date_year']; ?><b></td>
        </tr> -->
        <?php $currentDate = $get_q['date_year'];

    	}
		//it runs the original sql from the up up there!!

	$ctr++;
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_cust = mysql_fetch_assoc($cust_q);
	
	$style = '';
	
	if($get_q['bad_debt'] == 'yes')
	{
		$style = "style='color:#FF0000'";
	}
		
	//loan outstanding - WITH DATE SEARCH
	
	if($_SESSION['cdate'] != '')
	{
		$scheme_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$get_q['loan_package']."'");
		$scheme = mysql_fetch_assoc($scheme_q);
		
		if($scheme['receipt_type'] == 1)
		{
			
			if($scheme['scheme'] != 'SKIM CEK'){///////////////////////////////////////////////////////////////////////////////////!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//NORMAL SKIM
			
			//04102017 $outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND month = '1'");
			$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND payment_date <=  '".$_SESSION['cdate']."' ORDER BY payment_date DESC");
			$gouts = mysql_fetch_assoc($outst_q);
			
			
			
			//04102017 $bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_transaction WHERE customer_loanid = '".$get_q['id']."' AND date <= '".$_SESSION['cdate']."'");			
			//$bal1 = mysql_fetch_assoc($bal1_q);
			
			$outbalance = $gouts['balance'] - $gouts['payment'];
			//echo $gouts['balance']."/////";
			//echo $gouts['payment']."/////";
			//echo $outbalance."/////";			
			
			}else{
				
			//SKIM CEK
			$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND month = '1'");
			$gouts = mysql_fetch_assoc($outst_q);
			
			$bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_rec WHERE customer_loanid = '".$get_q['id']."' AND bal_date <= '".$_SESSION['cdate']."'");
			$bal1 = mysql_fetch_assoc($bal1_q);
			
			$outbalance = $gouts['balance'] - $bal1['received'];
			
			/*
			
			$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND month = '1'");
			$gouts = mysql_fetch_assoc($outst_q);
			
			//$lptotal_q = mysql_query("SELECT loan_total FROM customer_loanpackage WHERE id = '".$get_q['id']."'");
			//$lptotal = mysql_fetch_assoc($lptotal_q);
			
			$lptotal_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND month = '1'");
			$lptotal = mysql_fetch_assoc($lptotal_q);
			
			$bal2_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_transaction WHERE customer_loanid = '".$get_q['id']."' AND date <= '".$_SESSION['cdate']."'");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			$bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_transaction WHERE customer_loanid = '".$get_q['id']."' AND date <= '".$_SESSION['cdate']."'");
			$bal1 = mysql_fetch_assoc($bal1_q);
			
			$outbalance = $bal2['loan'] + $lptotal['balance'] - $bal1['received'];
			
			*/
			}
				
			
		}
		else
		{//flexi receipt receipt type == 2 
			
			if($_SESSION['pmonth'] != '') {		
				//get from balance 2
				//OUTSTANDING WITH MONTH AND DATE ONLY.. ALL PACKAGE
								
				$outb_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec WHERE customer_loanid = '".$get_q['id']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
				$outb = mysql_fetch_assoc($outb_q);
				$outb_q2 = mysql_query("SELECT SUM(received) AS received FROM balance_rec WHERE customer_loanid = '".$get_q['id']."' AND bal_date <= '".$_SESSION['cdate']."' AND month_receipt = '".$_SESSION['pmonth']."'");
				$outb2 = mysql_fetch_assoc($outb_q2);
				
				$outbalance = $outb['loan'] - $outb2['received'];
				
				if($outbalance <= 0)
				{
					if($outb['loan'] != '' && $outb2['received'] != '')
					{
						//get opening  from loan_payment_details
						if($outbalance < 0)
						{
							$lpdob_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND month_receipt = '".$_SESSION['pmonth']."' ORDER BY month ASC");
							$lpdob = mysql_fetch_assoc($lpdob_q);
							$outbalance = $lpdob['balance'] + $outb['loan'] - $outb2['received'];
						}
					}else
					{
						//if($outbalance < 0)
						//{
							$lpdob_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' AND month_receipt = '".$_SESSION['pmonth']."' ORDER BY month ASC");
							$lpdob = mysql_fetch_assoc($lpdob_q);
							$outbalance = $lpdob['balance'];
						//}						
						
					}
				}
			}else
			{//it runs here!
				//get loan 1st amount
				
				$loanpackage_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['id']."'");
				$loanpackage = mysql_fetch_assoc($loanpackage_q);
				
				$loanpayment_q = mysql_query("SELECT * FROM loan_payment_details 
											WHERE customer_loanid = '".$get_q['id']."' 
											AND created_date = '".$_SESSION['cdate']."'
											ORDER BY month ASC");
				$loanpayment = mysql_fetch_assoc($loanpayment_q);
				
				/*$specific_q = mysql_query("SELECT * FROM loan_payment_details
										WHERE created_date = '".$_SESSION['cdate']."'");
				$specific = mysql_fetch_assoc($specific_q);*/
				
				if($loanpackage['payout_date'] != '0000-00-00')
				{			
					//get from balance 1
					$outb_q = mysql_query("SELECT SUM(loan) AS loan, 
											SUM(received) AS received 
											FROM balance_transaction 
											WHERE customer_loanid = '".$get_q['id']."' 
											AND date < '".$_SESSION['cdate']."'");
											
					$outb = mysql_fetch_assoc($outb_q);
					$outbalance = $outb['loan'] - $outb['received'];
				}else
				{
					//get from balance 1
					$outb_q = mysql_query("SELECT SUM(loan) AS loan, SUM(received) AS received FROM balance_transaction WHERE customer_loanid = '".$get_q['id']."' AND date <= '".$_SESSION['cdate']."'");
					$outb = mysql_fetch_assoc($outb_q);
					$outbalance = $loanpayment['balance'] + $outb['loan'] - $outb['received'];
				}
			}
		}
	}
	else
	{
		$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' ORDER BY month DESC");
		$gouts = mysql_fetch_assoc($outst_q);
		$outbalance = $gouts['balance'];
	}
	
	$totout += $outbalance;
	?>
    <tr <?php echo $style; ?>>
    	<!-- <td><?php echo $i."."; ?></td> -->
		<td><?php echo $ctr."."; ?></td>
		<td align="center"><?php echo $get_q['loan_code']; ?></td>
    	<td><?php echo $get_cust['customercode2']; ?></td>
		
		<?php //The reason I changed $get_q['receipt_no'] to $get_q['loan_code'] is because
				// $get_q['receipt_no'] only get the earliest receipt no, no the latest one.?>
    	<td><?php echo $get_cust['name'];//$get_q['receipt_no']; ?></td>
        <td><?php echo "RM ".number_format($get_q['loan_amount'], '2'); ?></td>
        <td><?php echo "RM ".number_format($outbalance, '2'); ?></td>
        <td><?php echo $get_q['loan_package']; ?></td>
        <td><?php echo $get_q['loan_type']; ?></td>
<!--         <td><a href="lampiran_KPKT.php?id=<?php echo $get_q['id']; ?>" target="_blank">First Schedule</a></td> -->
        <td><a href="lejar.php?id=<?php echo $get_q['loan_code']; ?>" target="_blank">LEJAR</a></td>
        <td>
			<!--<?php if(strpos($get_q['loan_package'], 'SKIM F') === FALSE)
			{
			?>
				<a href="payloan_a.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>   
			<?php } else { ?>
				<a href="payloan.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
			<?php } ?>     -->
			
				<?php
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
				?><a href="lejar_payment_receipt.php?id=<?php echo $get_q['loan_code']; ?>" target="_blank"><img src="../img/home/payment_receipt.png" alt="" style="height:25px;" title="View Payment Receipt" /></a> &nbsp;
							<a href="lejar_a.php?id=<?php echo $get_q['id']; ?>" title="Lejar"><img src="../img/home/report.png" width="35px" height="35px" /></a> 
							<!-- <a href="payloan_a.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> -->
							<a href="javascript:deleteConfirmation('<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a> 

				<?php 
						}else
						{
				?>
							<a href="payloan_CEK.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> 
				<?php
						}
					} 
				?>        
		</td>
		<td>
			<a href="lejar2.php?id=<?php echo $get_q['loan_code']; ?>" target="_blank">LEJAR 2</a>
		</td>
		<td>
			<?php
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
							<a href="payloan.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>&nbsp;
				<?php
						}
					}

					if($get_q['loan_type'] == 'Fixed Loan')
					{
						if(strpos($get_q['loan_package'], 'SKIM CEK') === FALSE)
						{
				?>			&nbsp;<a href="lejar_payment_receipt2.php?id=<?php echo $get_q['loan_code']; ?>" target="_blank"><img src="../img/home/payment_receipt.png" alt="" style="height:25px;" title="View Payment Receipt" /></a> 
				<?php 
						}
					}
				?>
		</td>
    </tr>
    <?php $startno++;} ?>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<!-- <td style="background:#CCCCCC"><?php echo "RM ".number_format($totout, '2'); ?></td> -->
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
    </tr>
    <!--<tr>
    	<td colspan="10" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="10">&nbsp;</td>
    </tr>-->
</table>
<br><?php


	}
 
// tell the user if no records were found
else{
    echo "<div style='position:absolute; padding-top:70px;' class='noneFound'>
	<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >
				No records found. 
			</td></tr>
		</table>
	</div>";
}
?>
<?php } ?>
</center>
<script>
$(document).ready(function() {   
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
	
	  $("#customer_code").autocomplete("auto_custCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
  
	$('#search_ledger').on('click', function() {
		let year = $('#selected_year').val();
		let month = $('#selected_month').val();
		window.location.href = 'index.php?year=' + year + '&month=' + month;
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
	
$('#outdate').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
<script>
function deleteConfirmation(id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this loan?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_loan',
							id: $id,
						},
						url: 'action.php',
						success: function(){
							location.reload();
						}
					})
				}
			},
			'No'	: {
				'class'	: 'gray',
				'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
			}
		}
	});
}
</script>
