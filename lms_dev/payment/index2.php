<?php 
include('../include/page_header.php'); 
error_reporting(E_ERROR | E_PARSE);

if(isset($_POST['search']))   // click search
{
	$cond = '';
		if($_POST['month'] != '')
		{
			$cond .= " and pd.month_receipt = '".$_POST['month']."' and pd.payment_date = '0000-00-00'";
			$_SESSION['pmonth'] = $_POST['month'];
		}else
		{
			$_SESSION['pmonth'] = '';
		}
		
		if($_POST['customer_name'] != '')
		{
			$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".$_POST['customer_name']."'");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= " and lp.customer_id = '".$cust['id']."'";	
		}
		
		if($_POST['loan_code'])
		{
			$cond .= " and lp.loan_code = '".$_POST['loan_code']."'";
		}
		
		if($_POST['loan_package'] != '')
		{
			$cond .= " and lp.loan_package = '".$_POST['loan_package']."'";
			$_SESSION['loanpackage'] = $_POST['loan_package'];
		}else
		{
			$_SESSION['loanpackage'] = '';
		}
		
		if($_POST['loan_type'] != '')
		{
			$cond .= " and lp.loan_type = '".$_POST['loan_type']."'";
		}
		
		if($_POST['customer_code'] != '')
		{
			//$code_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$_POST['customer_code']."'");
			//$code = mysql_fetch_assoc($code_sql);
			//$cond .= " and lp.customer_id = '".$code['id']."'";	
			
			$code_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$_POST['customer_code']."'");
			while ($code = mysql_fetch_assoc($code_sql))
			{
				$idList[] = $code['id'];
			}
			
			$cond .= "and (";
			for($i=0; $i<sizeof($idList); $i++)
			{
				if($i==0)
				{
					$cond .= " lp.customer_id = '".$idList[$i]."'";	
				}
				else
					$cond .= " or lp.customer_id = '".$idList[$i]."'";	
			}
			
			$cond .= ")";
		}
		
		if($_POST['outdate'] != '')
		{
			$outdate = date('Y-m-d', strtotime($_POST['outdate']));
			$_SESSION['cdate'] = $outdate;
			$ccond .= $_SESSION['cdate'];
			//$cond .= " and pd.payment_date < '".$outdate."' and (lp.loan_status = 'Paid') AND (lp.payout_date <= '".$_SESSION['cdate']."' OR lp.payout_date = '0000-00-00')";
			$cond .= " and pd.payment_date < '".$outdate."' AND (lp.payout_date <= '".$_SESSION['cdate']."' OR lp.payout_date = '0000-00-00') AND lp.loan_status = 'Paid' " ;
		}else
		{
			$cond .= " and lp.loan_status = 'Paid'";
			$_SESSION['cdate'] = '';
			$ccond .= '';
		}
		
		$statement = "`loan_payment_details` pd, `customer_loanpackage` lp WHERE lp.id = pd.customer_loanid AND lp.branch_id = '".$_SESSION['login_branchid']."' $cond GROUP BY lp.id ORDER BY SUBSTRING_INDEX(lp.loan_code, IF(LOCATE(' ', lp.loan_code), ' ', '*'), 1) ASC, SUBSTRING_INDEX(lp.loan_code, IF(LOCATE(' ', lp.loan_code), ' ', '*'), -1)+0 ASC";
		$_SESSION['payment_s'] = $statement;
		
		
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
		$statement = $_SESSION['payment_s'];
	
		$packagecheck_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$_SESSION['loanpackage']."'");
		$packagecheck = mysql_fetch_assoc($packagecheck_q);
		$_SESSION['package_id'] = $packagecheck['id'];
		
		$bal2check_q = mysql_query("SELECT * FROM balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' AND package_id = '".$packagecheck['id']."' AND branch_id = '".$_SESSION['login_branchid']."' GROUP BY customer_loanid");
		$bal2checkrow = mysql_num_rows($bal2check_q);
		
		$loancheck_q = mysql_query("SELECT * FROM loan_payment_details WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$packagecheck['id']."' AND month_receipt = '".$_SESSION['pmonth']."' GROUP BY customer_loanid");
		$loancheckrow = mysql_num_rows($loancheck_q);
}
else    // not click search and also no history result
{
	$statement = "`customer_loanpackage` WHERE loan_status = 'Paid' AND branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY SUBSTRING_INDEX(loan_code, IF(LOCATE(' ', loan_code), ' ', '*'), 1) ASC, SUBSTRING_INDEX(loan_code, IF(LOCATE(' ', loan_code), ' ', '*'), -1)+0 ASC";
}

// page is the current page, if there's nothing set, default is page 1
if(isset($_POST['currentPage']))
{
	$page = isset($_POST['currentPage']) ? $_POST['currentPage'] : $_POST['currentPage'];
	//$page = isset($_GET['page']) ? $_GET['page'] : 1;
}
else
{
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
}

// set records or rows of data per page
$recordsPerPage = 100;
 
// calculate for the query LIMIT clause
$fromRecordNum = ($recordsPerPage * $page) - $recordsPerPage;

$sql_q = ("SELECT * FROM {$statement} LIMIT {$fromRecordNum}, {$recordsPerPage}");
$sql = mysql_query($sql_q);
//echo $sql_q;
//$sqlPaging = mysql_query("SELECT COUNT(*) as total_rows FROM {$statement}");
$sqlPaging = mysql_query("SELECT * FROM {$statement}");
     
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
					<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Loan Code</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
					<td align="right" style="padding-right:10px">Month</td>
                    <td style="padding-right:30px"><input type="text" name="month" id="month" style="height:30px; width:100px" value="<?php echo $_SESSION['pmonth'] ?>" /></td>
                    <td align="right" style="padding-right:10px">Date</td>
                    <td  style="padding-right:30px"><input type="text" name="outdate" id="outdate" style="width:100px; height:30px" value="<?php if($_SESSION['cdate'] != '') { echo date('d-m-Y', strtotime($_SESSION['cdate'])); } ?>" /></td>
                    <td style="padding-right:8px">&nbsp;</td>
				</tr>
            	<tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                    <td style="padding-right:30px">&nbsp;</td>
                    <td align="right" style="padding-right:10px">Customer Code</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px"/></td>
					<td align="right" style="padding-right:10px">Package</td>
                    <td style="padding-right:30px">
                    	<select name="loan_package" id="loan_package" style="height:30px; width:102px">
                        	<option value="">All</option>
                            <?php
							$package_q = mysql_query("SELECT * FROM loan_scheme");
							while($get_p = mysql_fetch_assoc($package_q)){
							?>
                            <option value="<?php echo $get_p['scheme']; ?>" <?php if($_SESSION['loanpackage'] == $get_p['scheme']) { echo 'selected'; } ?>><?php echo $get_p['scheme']; ?></option>
                            <?php } ?>
                        </select>                     </td>
                    <td align="right" style="padding-right:10px">Loan Type</td>
                    <td  style="padding-right:30px">
                    	<select name="loan_type" id="loan_type" style="height:30px; width:102px">
                        	<option value="">All</option>
                            <option value="Flexi Loan">Flexi Loan</option>
                            <option value="Fixed Loan">Fixed Loan</option>
                        </select>                    </td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />					
					</td>
                </tr>
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
			<a href="index.php" id="active-menu">Payment Listing</a><a href="lateIntPayment.php">Late Interest Payment Listing</a><?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?><?php if($kutuOffice != 0) {?><a href="kutuOffice.php">Kutu Office</a><?php } ?>
		</div>
		<div style="float:right">
			<a href="index_show_all.php">Show All List</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="yearlyledger.php"><input type="button" value="Yearly Ledger" id="yearlyLedger" /></a>
		</div>
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
		<th>Customer Code</th>
    	<th>Customer Name</th>
    	<th>Code</th>
        <th>Loan Amount</th>
        <th>Outstanding</th>
        <th>Package</th>
        <th>Loan Type</th>
        <th colspan="2">Lampiran</th>
        <th width="50"></th>
    </tr>
    <?php 
	if(isset($_POST['currentPage']))
	{
		$page = isset($_POST['currentPage']) ? $_POST['currentPage'] : $_POST['currentPage'];
		//$page = isset($_GET['page']) ? $_GET['page'] : 1;
	}
	else
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
	}
	
	$recordsPerPage = 100;
 
	// calculate for the query LIMIT clause
	$fromRecordNum = ($recordsPerPage * $page) - $recordsPerPage;
	
	$ctr = 0;
	$totout = 0;
	
	$statement = "balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' 
					AND package_id = '".$_SESSION['package_id']."' AND branch_id = '".$_SESSION['login_branchid']."' 
					AND bal_date <= '".$_SESSION['cdate']."' $cond  
					GROUP BY customer_loanid";
					
	$_SESSION['payment_s'] = $statement;
	
	$sql = mysql_query("SELECT * FROM balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' 
						AND package_id = '".$_SESSION['package_id']."' 
						AND branch_id = '".$_SESSION['login_branchid']."' 
						AND bal_date <= '".$_SESSION['cdate']."' 
						GROUP BY customer_loanid LIMIT {$fromRecordNum}, {$recordsPerPage}");
	
	$startno = $fromRecordNum + 1;
	//$r = mysql_num_rows($sql);
	//this is how to get number of rows returned
	$num = mysql_num_rows($sql);

if($num>0){
	//this is where all data is showed
	
	//fetch all data from $sql
	while($get_q = mysql_fetch_assoc($sql)){
		
		$ctr++;
		//take all column from TABLE customer_loanpackage which their id match id from $sql
		$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
		$loan = mysql_fetch_assoc($loan_q);
		
		//take all column from TABLE customer_details which their id match customer_id from $loan
		$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
		$get_cust = mysql_fetch_assoc($cust_q);
		
	if($get_cust['name'] == ''){
		//no idea what is this...
		continue;
	}
	
	//no style, means it just black and white
	$style = '';
	
	if($get_cust['blacklist'] == 'Yes')
	{
		//blacklisted customer is painted in red
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
		//what does loan == 0 means???

		$outst_q = mysql_query("SELECT * FROM loan_payment_details
								WHERE customer_loanid = '".$get_q['customer_loanid']."'
								AND month = '1'");
		$gouts = mysql_fetch_assoc($outst_q);

		$loan1_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec
								WHERE customer_loanid = '".$get_q['customer_loanid']."'
								AND bal_date <= '".$_SESSION['cdate']."'
								AND month_receipt = '".$_SESSION['pmonth']."'");
		$loan1 = mysql_fetch_assoc($loan1_q);

		$bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_rec
								WHERE customer_loanid = '".$get_q['customer_loanid']."'
								AND bal_date <= '".$_SESSION['cdate']."'
								AND month_receipt = '".$_SESSION['pmonth']."'");
		$bal1 = mysql_fetch_assoc($bal1_q);

		$outbalance = $gouts['balance'] - $bal1['received'];
		
	}
	else{
		//$loancheck['loan'] have some numbers
	
		//detect new data
		$outst_q = mysql_query("SELECT * FROM loan_payment_details
								WHERE customer_loanid = '".$get_q['customer_loanid']."'
								AND month = '1'");					
		$gouts = mysql_fetch_assoc($outst_q);
		
		$loan1_q = mysql_query("SELECT SUM(loan) AS loan FROM balance_rec
								WHERE customer_loanid = '".$get_q['customer_loanid']."'
								AND bal_date <= '".$_SESSION['cdate']."'
								AND month_receipt = '".$_SESSION['pmonth']."'");
		$loan1 = mysql_fetch_assoc($loan1_q);
		
		$bal1_q = mysql_query("SELECT SUM(received) AS received FROM balance_rec
								WHERE customer_loanid = '".$get_q['customer_loanid']."'
								AND bal_date <= '".$_SESSION['cdate']."'
								AND month_receipt = '".$_SESSION['pmonth']."'");
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
        <td><a href="lampiran_KPKT.php?id=<?php echo $loan['id']; ?>" target="_blank">First Schedule</a></td>
        <td><a href="lampiran_a.php?id=<?php echo $loan['id']; ?>" target="_blank">Lampiran A</a></td>
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
            			<a href="payloan_a.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> 
            <?php 
					}else
					{
			?>
						<a href="payloan_CEK.php?id=<?php echo $loan['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a> 
			<?php
					}
				}
			?>        </td>
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
	 
    // *************** <PAGING_SECTION> ***************
		echo "<div id='paging'>";
		//if($sort=='ASC')
		//{
			//$sort='DESC';
		//}
		//else
		//{
			//$sort='ASC';
		//}		
		
		// ********** show the number paging
		
		// find out total pages
        $query = "SELECT * FROM balance_rec WHERE month_receipt = '".$_SESSION['pmonth']."' AND package_id = '".$_SESSION['package_id']."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date <= '".$_SESSION['cdate']."' GROUP BY customer_loanid";
        
        $stmt=mysql_query($query);
 
        $get_list = mysql_fetch_assoc($stmt);
        $total_rows = mysql_num_rows($stmt);
 
        $total_pages = ceil($total_rows / $recordsPerPage);
 
        // range of num links to show
        $range = 4;
		
		
        // display links to 'range of pages' around 'current page'
        //$initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
		?>&nbsp;<?php
		
		//$currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 2;
		//$currPage = $_POST['currentPage'];
echo "<center><table border='0' width='100%' >";
echo "<tr>
		<td width='30%'></td>
		<td width='60%'>";
		
		echo "<center><table border='0'  class='customBtn'>";
		echo "<tr>";
		
		if($currPage = isset($_POST['currentPage']))
		{	
			$page=$_POST['currentPage'];
			$initial_num = $page - $range;
		}
		else
		{
			$initial_num = $page - $range;
		}
		
		echo "&nbsp;";
		 // ***** for 'previous' pages
        if($page>1){
            // ********** show the previous page
            $prev_page = $page - 1;
            echo "<td><a href='" . $_SERVER['PHP_SELF'] 
                    . "?page={$prev_page}' title='Previous page is {$prev_page}.'>";
                echo "<span style='margin:0 .5em;'> <  </span>";
            echo "</a></td>";
        }
		else
		{
			$prev_page = $page;
            echo "<td></td>";
		}
		echo "&nbsp;";
		for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $total_pages)) {
             
                // current page
                if ($x == $page) {
                    echo "<td>&nbsp;&nbsp;<span style='font-size:16px;color:red;font-weight:bold'>$x</span>&nbsp;&nbsp;</td>";
                } 
                 
                // not current page
                else {
                    echo " <td>&nbsp;&nbsp;<a href='{$_SERVER['PHP_SELF']}?page=$x' >$x</a> &nbsp;&nbsp;</td>";
                }
            }
        }
		
		echo "&nbsp;";
		// ***** for 'next' 
		if($page<$total_pages){
			// ********** show the next page
			$next_page = $page + 1;
			echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?page={$next_page}' title='Next page is {$next_page}.'";
			echo "<span style='margin:0 .5em;'> > </span>";
			echo "</a></td>";
		}
		else
		{
			$next_page = $page;
			echo "<td></td>";
		}
		echo "&nbsp;";
        for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
			
            if (($x > 0) && ($x <= $total_pages)) {
				// ***** allow user to enter page number
                // current page
                if ($x == $page) {
					echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
					echo "<td><input type='text' size='1' name='currentPage' value='$x' autocomplete='off' style='text-align:center'/> of $total_pages ";
					
					?>&nbsp;&nbsp;<?php	
					echo "<input type='hidden' name='submit' value='Go to'></td>";
					?><?php
					echo "</form>";
					
                } 
				
            }
        }		
		
		echo "</tr></table>";
		echo "</div>";
		
echo "
	</td>
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >&nbsp;</td></tr>
		</table>
	</td >
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >&nbsp;</td></tr>
		</table>
	</td>
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >
				<input type='button' name='back' id='back' onClick=window.location.href='../home/' value=''/></td></tr>
		</table>
	</td>
	</tr></table>";	
   
		// *************** </PAGING_SECTION> ***************

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
    	<th>No.</th>
		<th>Customer Code</th>
    	<th>Customer Name</th>
    	<th>Code</th>
        <th>Loan Amount</th>
        <th>Outstanding</th>
        <th>Package</th>
        <th>Loan Type</th>
        <th colspan="2">Lampiran</th>
        <th width="50"></th>
    </tr>
    <?php 
	$ctr = 0;
	$totout = 0;
	
	$startno = $fromRecordNum + 1; 
	$r = mysql_num_rows($sql);
	
	if($r>0){	
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_cust = mysql_fetch_assoc($cust_q);
	
	$style = '';
	
	if($get_cust['blacklist'] == 'Yes')
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
			{
				
				//get loan 1st amount
				
				$loanpackage_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['id']."'");
				$loanpackage = mysql_fetch_assoc($loanpackage_q);
				
				$loanpayment_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' ORDER BY month ASC");
				$loanpayment = mysql_fetch_assoc($loanpayment_q);
				
				if($loanpackage['payout_date'] != '0000-00-00')
				{			
					//get from balance 1
					$outb_q = mysql_query("SELECT SUM(loan) AS loan, SUM(received) AS received FROM balance_transaction WHERE customer_loanid = '".$get_q['id']."' AND date <= '".$_SESSION['cdate']."'");
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
    	<td><?php echo $startno."."; ?></td>
		<td align="center"><?php echo $get_cust['customercode2']; ?></td>
    	<td><?php echo $get_cust['name']; ?></td>
    	<td><?php echo $get_q['loan_code']; ?></td>
        <td><?php echo "RM ".number_format($get_q['loan_amount'], '2'); ?></td>
        <td><?php echo "RM ".number_format($outbalance, '2'); ?></td>
        <td><?php echo $get_q['loan_package']; ?></td>
        <td><?php echo $get_q['loan_type']; ?></td>
        <td><a href="lampiran_KPKT.php?id=<?php echo $get_q['id']; ?>" target="_blank">First Schedule</a></td>
        <td><a href="lampiran_a.php?id=<?php echo $get_q['id']; ?>" target="_blank">Lampiran A</a></td>
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
					{echo 'PORK 1';//make a new link
			?>
            		<a href="payloan_f.php?id=<?php echo $get_q['id']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
            <?php 
					}
					
					if($get_rt['receipt_type'] == 2)//receipt code changing
					{echo 'PORK 2';
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
    </tr>
    <!--<tr>
    	<td colspan="10" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="10">&nbsp;</td>
    </tr>-->
</table>
<br><?php
	 
    // *************** <PAGING_SECTION> ***************
		echo "<div id='paging'>";
		//if($sort=='ASC')
		//{
			//$sort='DESC';
		//}
		//else
		//{
			//$sort='ASC';
		//}		
		
		// ********** show the number paging
		
		// find out total pages
        //$query = "SELECT COUNT(*) as total_rows FROM addmember where status = 'Active' AND 1 $cond ORDER BY $field $sort";
        
        //$stmt=mysql_query($query);
 
        //$get_list = mysql_fetch_assoc($sqlPaging);
        //$total_rows = $get_list['total_rows'];
        $total_rows = mysql_num_rows($sqlPaging);
 
        $total_pages = ceil($total_rows / $recordsPerPage);
 
        // range of num links to show
        $range = 4;
		
		
        // display links to 'range of pages' around 'current page'
        //$initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
		?>&nbsp;<?php
		
		//$currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 2;
		//$currPage = $_POST['currentPage'];
echo "<center><table border='0' width='100%' >";
echo "<tr>
		<td width='30%'></td>
		<td width='40%'>";
		
		echo "<center><table border='0'  class='customBtn'>";
		echo "<tr>";
		
		if($currPage = isset($_POST['currentPage']))
		{	
			$page=$_POST['currentPage'];
			$initial_num = $page - $range;
		}
		else
		{
			$initial_num = $page - $range;
		}
		
		echo "&nbsp;";
		 // ***** for 'previous' pages
        if($page>1){
            // ********** show the previous page
            $prev_page = $page - 1;
            echo "<td><a href='" . $_SERVER['PHP_SELF'] 
                    . "?page={$prev_page}' title='Previous page is {$prev_page}.'>";
                echo "<span style='margin:0 .5em;'> <  </span>";
            echo "</a></td>";
        }
		else
		{
			$prev_page = $page;
            echo "<td></td>";
		}
		echo "&nbsp;";
		for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $total_pages)) {
             
                // current page
                if ($x == $page) {
                    echo "<td>&nbsp;&nbsp;<span style='font-size:16px;color:red;font-weight:bold'>$x</span>&nbsp;&nbsp;</td>";
                } 
                 
                // not current page
                else {
                    echo " <td>&nbsp;&nbsp;<a href='{$_SERVER['PHP_SELF']}?page=$x' >$x</a> &nbsp;&nbsp;</td>";
                }
            }
        }
		
		echo "&nbsp;";
		// ***** for 'next' 
		if($page<$total_pages){
			// ********** show the next page
			$next_page = $page + 1;
			echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?page={$next_page}' title='Next page is {$next_page}.'";
			echo "<span style='margin:0 .5em;'> > </span>";
			echo "</a></td>";
		}
		else
		{
			$next_page = $page;
			echo "<td></td>";
		}
		echo "&nbsp;";
        for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
			
            if (($x > 0) && ($x <= $total_pages)) {
				// ***** allow user to enter page number
                // current page
                if ($x == $page) {
					echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
					echo "<td><input type='text' size='1' name='currentPage' value='$x' autocomplete='off' style='text-align:center'/> of $total_pages ";
					
					?>&nbsp;&nbsp;<?php	
					echo "<input type='hidden' name='submit' value='Go to'></td>";
					?><?php
					echo "</form>";
					
                } 
				
            }
        }		
		
		echo "</tr></table>";
		echo "</div>";
		
echo "
	</td>
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >&nbsp;</td></tr>
		</table>
	</td >
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >&nbsp;</td></tr>
		</table>
	</td>
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >
				<input type='button' name='back' id='back' onClick=window.location.href='../home/' value=''/></td></tr>
		</table>
	</td>
	</tr></table><br/><br/>";	
   
		// *************** </PAGING_SECTION> ***************

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
