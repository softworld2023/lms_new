<?php 
include('../include/page_headercb.php'); 

$cond = '';
if(isset($_POST['search']))
{
	$_SESSION['sbranch'] = $_POST['sbranch'];
	if($_POST['sbranch'] != '')
	{
		$cond .= " and branch_id = '".$_POST['sbranch']."'";
	}
	
	$_SESSION['package'] = $_POST['lpackage'];
	if($_POST['lpackage'] != '')
	{
				$cond .= " and loan_package = '".$_POST['lpackage']."'";
	}
	
	if($_POST['year'] != '')
	{
		$_SESSION['lsdf'] = '';
		$_SESSION['lsdt']  = '';
		$year = substr($_POST['year'], 0, -1);
		$_SESSION['lsyear'] = $year;
		$cond .= " and payout_date LIKE '%".$year."%'";
	}else
	{
		$_SESSION['lsyear'] = '';
		if($_POST['datefrom'] != '')
		{
			$_SESSION['lsdf'] = $_POST['datefrom'];
			
			$cond .= " and payout_date >= '".date('Y-m-d', strtotime($_POST['datefrom']))."'";
		}else
		{
			$_SESSION['lsdf'] = '';
		}
		
		if($_POST['dateto'] != '')
		{
			$_SESSION['lsdt'] = $_POST['dateto'];
			$cond .= " and payout_date <= '".date('Y-m-d', strtotime($_POST['dateto']))."'";
		}else
		{
			$_SESSION['lsdt'] = '';
		}
	}
	
	
}


// ****** paging ****** 
// page is the current page, if there's nothing set, default is page 1
if(isset($_POST['currentPage']))
{
	$page = isset($_GET['page']) ? $_GET['page'] : $_POST['currentPage'];
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

$sql = mysql_query("SELECT * FROM ktl.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') 
						$cond UNION 
						SELECT * FROM lskl_tsh.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_grandtip.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_setplan.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_shah_alam.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_jiningo.customer_loanpackage  
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_wellstart.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_success.customer_loanpackage 
						WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_maju_jaya.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_barcelona.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_libyee.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_dasia.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_hq.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_maju_kajang.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_city.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_bayu.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_jaya_kaya.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_bengen.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION 
						SELECT * FROM lskl_cap_phillo.customer_loanpackage 
						WHERE loanpackagetype != '' 
						AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond 
						LIMIT {$fromRecordNum}, {$recordsPerPage}");


//$sql = mysql_query("SELECT * FROM loan_goldenone.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond LIMIT {$fromRecordNum}, {$recordsPerPage}");

//$rs = mysql_query("SELECT * FROM customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond LIMIT {$fromRecordNum}, {$recordsPerPage}");

$startno = $fromRecordNum + 1; 
			

//this is how to get number of rows returned
//$num = mysql_num_rows($sql);
$num = mysql_num_rows($sql);

//$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond");

/* $sql = mysql_query("SELECT * FROM loan_capital.customer_loanpackage WHERE  (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_ccv.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_easy.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_goldenone.customer_loanpackage WHERE  (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_marketing.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_master.customer_loanpackage  WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_mastersatok.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_paolyta.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_popular.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_wereliance.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION
SELECT * FROM loan_westfree.customer_loanpackage WHERE (loan_status = 'Paid' OR loan_status = 'Finished') $cond 
"); */
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
	
	
}

#list_table tr th
{
	height:36px;
	background:#666;
	text-align:left;
	padding-left:10px;
	color:#FFF;
	border:thin solid #333;
	
}
#list_table tr td
{
	height:35px;
	padding-left:10px;
	padding-right:10px;
	border:thin solid #333;
	
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
#print_btn
{
	background:url(../img/print-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#print_btn:hover
{
	background:url(../img/print-btn-roll.jpg);
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
@media print {	
	.subnav { display:none; }
	#hideprint { display:none; }
	#back { display:none; }
	#message { display:none; }
	#list_table
	{
		width:1000px;
	}
}
#grid td
{
	border-left:thin solid #333;
	border-right:thin solid #333;
	border-bottom:thin solid #333;
	border-top:thin solid #333;
}
#grid th
{
	border-left:thin solid #333;
	border-right:thin solid #333;
	border-bottom:thin solid #333;
	border-top:thin solid #333;
}
#grid2
{
	background:#CCCCCC;
	border-bottom:double #333;
}
</style>

<style>
a{
	color:#ffffff;
	text-decoration: none;
}
.customBtn {
   border-top: 1px solid #96d1f8;
   background: #999999;
   background: -webkit-gradient(linear, left top, left bottom, from(#666666), to(#999999));
   background: -webkit-linear-gradient(top, #666666, #999999);
   background: -moz-linear-gradient(top, #666666, #999999);
   background: -ms-linear-gradient(top, #666666, #999999);
   background: -o-linear-gradient(top, #666666, #999999);
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
   border-top-color: #999999;
   background: #999999;
   color: #ccc;
   }
.customBtn:active {
   border-top-color: #666666;
   background: #666666;
   }	
  .btn {
  font-family: Arial;
  color: #524752;
  font-size: 12px;
  font-weight:bold;
  background: #e6e6e6;
  padding: 10px 25px 10px 25px;
  text-decoration: none;
}

.btn:hover {
	color:#ffffff;
  background: #666666;
  text-decoration: none;
}
</style>

<center>
<table width="1280" id="hideprint">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Loan Status  Report</td>
        <td align="right">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
         <div class="subnav">
			<a href="report.php">HQ Cashbook Daily</a>
			<a href="customerChannnel.php">Customer Channel</a>
			<a href="loanstatus.php" id="active-menu">Loan Status</a>
			<a href="settle.php">Customer Settle Report</a>
		</div>	
        </td>
    </tr>
	<tr>
    	<td colspan="3"><div id="message" style="width:1280px; text-align:left">
	<?php
	if($_SESSION['msg'] != '')
	{
		echo $_SESSION['msg'];
		$_SESSION['msg'] = '';
	}
	?>
	</div>&nbsp;</td>
    </tr>
	<tr>
    
    	<td colspan="3">
		<form action="" method="post">
        	<table>
            	<tr>
            	  <td style="padding-right:30px">Branch<br />&nbsp;</td>
           	      <td colspan="9" style="padding-right:30px"><select name="sbranch" id="sbranch" style="height:30px">
                    <option value="">ALL</option>
                    <?php 
							$branch_q = mysql_query("SELECT * FROM loansystem.branch WHERE branch_id != '13'");
							while($branch = mysql_fetch_assoc($branch_q)){
							?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch['branch_id'] == $_SESSION['sbranch']) { echo 'selected'; } ?>><?php echo $branch['branch_name']; ?></option>
                    <?php } ?>
                  </select><br />&nbsp;</td>
       	      </tr>
            	<tr>
					<td align="right" style="padding-right:10px">Date From </td>
                    <td style="padding-right:30px"><input type="text" name="datefrom" id="datefrom" style="height:30px; width:100px" value="<?php echo $_SESSION['lsdf']; ?>" /></td>
					<td align="right" style="padding-right:10px">To</td>
                    <td style="padding-right:30px"><input type="text" name="dateto" id="dateto" style="height:30px; width:100px" value="<?php echo $_SESSION['lsdt']; ?>" /></td>
					<td align="right" style="padding-right:10px">Year</td>
                    <td style="padding-right:30px"><input type="text" name="year" id="year" style="height:30px; width:80px" maxlength="4" value="<?php echo $_SESSION['lsyear']; ?>" /></td>
                	<td align="right" style="padding-right:10px">Package</td>
                    <td style="padding-right:30px">
						<select name="lpackage" id="lpackage" style="height:30px;">
							<option value="">ALL</option>
							<?php $packageq = mysql_query("SELECT * FROM
							(SELECT * FROM ktl.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_tsh.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_grandtip.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_setplan.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_shah_alam.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_jiningo.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_wellstart.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_success.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_maju_jaya.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_barcelona.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_libyee.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_dasia.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_hq.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_maju_kajang.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_city.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_bayu.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_jaya_kaya.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_bengen.loan_scheme WHERE SCHEME != 'SKIM KUTU' UNION
							SELECT * FROM lskl_cap_phillo.loan_scheme WHERE SCHEME != 'SKIM KUTU')  AS A
							GROUP BY A.scheme
							"); 

							
							
												

							while($package = mysql_fetch_assoc($packageq)){
							?>
							<option value="<?php echo $package['scheme']; ?>" <?php if($_SESSION['package'] == $package['scheme']) { echo 'selected'; } ?>><?php echo $package['scheme']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
					<!--<td><button id="print_btn" onClick="window.print()"></button></td>-->
				</tr>
            </table>
        </form>
		</td>
    </tr>
</table>
<br />
<table width="1280" id="list_table">
	<tr id="grid">
    	<th>No.</th>
        <th>Application<br />Date</th>
        <th>Branch</th>
        <th>Cust<br />Code </th>
        <th>Customer Name </th>
        <th>Package</th>
        <th>Loan<br />Code</th>
        <th>New Loan<br />(RM)</th>
        <th>Reloan<br />(RM)</th>
        <th>Overlap<br />(RM)</th>
        <th>Account 2<br />(RM)</th>
        <th>Settlement Date for<br />Previous Loan</th>
        <th>Previous<br />Loan Code</th>
        <th>Total Loan<br />(RM)</th>
		<?php if($_SESSION['login_level'] != 'Staff') { ?>
        <th width="80">&nbsp;</th>
		<?php } ?>
    </tr>
	
    <?php 
	$ctr = 0;
	$tot = 0;
	$nloan = 0;
	$rloan = 0;
	$over = 0;
	$acc2 = 0;
if($num>0){	
	while($get_q = mysql_fetch_assoc($sql)){ 
	//branch 
	$branchq = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_q['branch_id']."'");	
	$gbranch = mysql_fetch_assoc($branchq);
	
	//customer
	//$custq = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$custq = mysql_query("SELECT * FROM ktl.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_tsh.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_grandtip.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_setplan.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_shah_alam.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_jiningo.customer_details  WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_wellstart.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_success.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_maju_jaya.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_barcelona.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_libyee.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_dasia.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_hq.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_maju_kajang.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_city.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_bayu.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_jaya_kaya.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_bengen.customer_details WHERE id = '".$get_q['customer_id']."' UNION
	SELECT * FROM lskl_cap_phillo.customer_details WHERE id = '".$get_q['customer_id']."'
	");
	$cust = mysql_fetch_assoc($custq);
	
	$ctr++;
	//$lcode = $get_q['loan_code'];
	//if($lcode == '0')
	//{
		//$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."'");
		$lpd_q = mysql_query("SELECT * FROM  ktl.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_tsh.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_grandtip.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_setplan.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_shah_alam.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_jiningo.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_wellstart.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_success.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_maju_jaya.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_barcelona.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_libyee.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_dasia.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_hq.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_maju_kajang.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_city.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_bayu.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_jaya_kaya.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_bengen.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' UNION
		SELECT * FROM  lskl_cap_phillo.customer_details.loan_payment_details WHERE customer_loanid = '".$get_q['id']."' 
		");
		$lpd = mysql_fetch_assoc($lpd_q);
		
		$lcode = $lpd['receipt_no'];
	//}
	
	if($get_q['loanpackagetype'] == 'NEW LOAN') { $nloan += $get_q['actual_loanamt']; }
	if($get_q['loanpackagetype'] == 'RELOAN') { $rloan += $get_q['actual_loanamt']; }
	if($get_q['loanpackagetype'] == 'OVERLAP') { $over += $get_q['actual_loanamt']; }
	if($get_q['loanpackagetype'] == 'ACCOUNT 2') { $acc2 += $get_q['actual_loanamt']; }
	?>
    <tr id="grid">
    	<td><?php echo $startno."."; ?></td>
        <td><?php echo date('d-m-Y', strtotime($get_q['payout_date'])); ?></td>
        <td><?php echo $gbranch['branch_name']; ?></td>
        <td><!--<a href="../customers/view_customer2.php?id=<?php echo $get_q['customer_id']; ?>" rel="shadowbox">--><?php echo $_SESSION['login_database'] .strtoupper($cust['customercode2']); ?></a></td>
        <td><?php echo $cust['name']; ?></td>
        <td><?php echo $get_q['loan_package']; ?></td>
        <td><?php echo $lcode; ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'NEW LOAN') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'RELOAN') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'OVERLAP') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'ACCOUNT 2') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['prev_settlementdate'] != '1970-01-01' && $get_q['prev_settlementdate'] != '0000-00-00')  { echo date('d-m-Y', strtotime($get_q['prev_settlementdate'])); } ?></td>
        <td><?php echo $get_q['prev_loancode']; ?></td>
        <td><?php echo number_format($get_q['actual_loanamt']); ?></td>
		<?php if($_SESSION['login_level'] != 'Staff') { ?>
        <td>
       
        
			<center>
				<a href="edit_loanstatus.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;
			</center>        
		
		</td>
		<?php } ?>
    </tr>
    <?php $startno++;} ?>
    
        <?php
	 
    // *************** <PAGING_SECTION> ***************
		
		// ********** show the number paging
		
		// find out total pages
        //$sql = "SELECT COUNT(*) as total_rows FROM loan_goldenone.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond";
		
		$sql = mysql_query("SELECT * FROM ktl.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_tsh.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_grandtip.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_setplan.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_shah_alam.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_jiningo.customer_loanpackage  WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_wellstart.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_success.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_maju_jaya.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_barcelona.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_libyee.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_dasia.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_hq.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_maju_kajang.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_city.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_bayu.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_jaya_kaya.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_bengen.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond UNION SELECT * FROM lskl_cap_phillo.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond");
		
				
			
		$total_rows = mysql_num_rows($sql);
		
        //$get_list = mysql_fetch_assoc($rs);
		
        //$total_rows = $get_list['total_rows'];
 
        $total_pages = ceil($total_rows / $recordsPerPage);
 
        // range of num links to show
        $range = 4;
		
		
        // display links to 'range of pages' around 'current page'
        //$initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
		?>&nbsp;<?php
		
		//$currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 2;
		//$currPage = $_POST['currentPage'];
		
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
            echo "<td><a href='" . $_SERVER['PHP_SELF'] 
                    . "?page={$prev_page}' title='Previous page is {$prev_page}.'>";
                //echo "<span style='margin:0 .5em;'> < </span>";
            echo "</a></td>";
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
			echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?page={$next_page}' title='Next page is {$next_page}.' >";
			//echo "<span style='margin:0 .5em;'> >  </span>";
			echo "</a></td>";
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
   
		// *************** </PAGING_SECTION> 
}else{
    echo "<tr><td colspan='8' style='border:none;' align='center'>No records found.</td></tr>";
}?>
</table>
</center>
<script>
function deleteConfirmation(date, name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this transaction: ' + name + ' (' + date + ')?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_trans',
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
$('#year').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y ", labelTitle: "Select Date"}).focus(); } ).
keydown(
	function(e)
	{
		var key = e.keyCode || e.which;
		if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
		{
			$v = this;
			$($v).off('keydown').AnyTime_picker().focus();
			e.preventDefault();
		}
	} );
$('#datefrom').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
$('#dateto').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

Shadowbox.init();
</script>