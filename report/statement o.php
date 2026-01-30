<?php include('../include/page_header.php'); 

if(isset($_POST['search']))
{
	if($_POST['date'] != '')
	{
		$_SESSION['statementmonth'] = $_POST['date'];
	}
	else
	{
		$_SESSION['statementmonth'] = date('Y-m');
	}
	
	$date = $_SESSION['statementmonth'];
	
	$m = date('m', strtotime($date));
	$y = date('Y', strtotime($date));
	
	$number = cal_days_in_month(CAL_GREGORIAN, $m, $y); // 31
	$newM = $y.'-'.$m.'-'.$number;
	$startM = $y.'-'.$m.'-01';
}else
{
	if($_SESSION['statementmonth'] != '')
	{
		$date = $_SESSION['statementmonth'];
	}else
	{
		$date = date('Y-m');
	}
	
	$m = date('m', strtotime($date));
	$y = date('Y', strtotime($date));
	
	$number = cal_days_in_month(CAL_GREGORIAN, $m, $y); // 31
	$newM = $y.'-'.$m.'-'.$number;
	$startM = $y.'-'.$m.'-01';
}

$nextm = $m + 1;
$nexty = $y;
if($nextm <= 9)
{
	$nextm = '0'.$nextm;
}

if($nextm == 13)
{
	$nextm = '01';
	$nexty = $y + 1;
}

$nextoutmonth = $nexty.'-'.$nextm;

?>
<style type="text/css">
<!--
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
#submit
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#submit:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
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
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Reports: <strong>Statement &amp; Outstanding  </strong></td>
        <td align="right">
		<form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Month</td>
                    <td style="padding-right:30px"><input type="text" name="date" id="date" style="height:30px" value="<?php echo $_SESSION['statementmonth']; ?>"/></td>
					<td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
				</tr>
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
			<a href="index.php">Borrow Out</a><a href="payout.php">Actual Payout</a><a href="collection.php">Total Collection</a><a href="profit.php">Profit & Loss</a><a href="expenses.php">Expenses</a><a href="interest.php">Interest Earn</a><a href="latepayment.php">Late Payment Collections</a><a href="daily.php">Daily Collections</a><a href="statement.php" id="active-menu">Statement</a>		</div>        </td>
    </tr>
</table>


<br />
<table width="1285" id="list_table">
	<tr>
		<!--monthly statement-->
		<td width="50%" valign="top">
			<table width="100%" id="list_table">
				<tr>
					<td width="1277">
						<table width="350" id="list_table">
							<tr>
								<td><div align="center"><strong>STATEMENT END OF MONTH<BR />
									<?php echo strtoupper(date('F Y', strtotime($date)));?></strong>
								</div></td>
							</tr>
						</table>
				  </td>
				</tr>
				
				<?php 
				$package_q_s = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM S'");
				$ctr1s = 0;
				while($get_ps = mysql_fetch_assoc($package_q_s)){
				$ctr1s++;
				?>
				
				<tr>
					<td>
						<table id="list_table" width="350">
							
							<?php
							
							$package_id = $get_ps['id'];
							$style = '';
							$style2 = '';
							$cash = 0;
							
							//stock <-- from bal1
							$bal1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."'");
							$get_bal1cf = mysql_fetch_assoc($bal1cf_q);
							
							$stock1 = $get_bal1cf['amount'];
							$stock_date = $get_bal1cf['date'];
							
							$stock_od = date('Y-m', strtotime($stock_date));
							
							if($stock_od == $date)
							{
								$sum_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec FROM balance_transaction WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."'");
								$get_sum = mysql_fetch_assoc($sum_q);		
							
								$stock = $stock1 + $get_sum['sumRec'] - $get_sum['sumLoan'];
								
							}else
							if($stock_od > $date)
							{
								$stock = 0;
							}
							else
							{ 
								$sum_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec FROM balance_transaction WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."'");
								$get_sum = mysql_fetch_assoc($sum_q);		
							
								$stock = $stock1 + $get_sum['sumRec'] - $get_sum['sumLoan'];
							}
							
							//total loan <-- from bal2
							if($get_ps['receipt_type'] == 1)
							{
								$sumloan_q = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
								$get_loan = mysql_fetch_assoc($sumloan_q);	
								
								$sumloan_q2 = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date < '".$startM."'");
								$get_loan2 = mysql_fetch_assoc($sumloan_q2);
								
								$totloan = $get_loan2['sumLoan'] + $get_loan['sumLoan'];					
							}else
							{
								/*$sumloan_q = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND month_receipt = '".$date."'");
								$get_loan = mysql_fetch_assoc($sumloan_q);	*/
								
								$sumloan_q = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_transaction WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%'");
								$get_loan = mysql_fetch_assoc($sumloan_q);	
							}
							
							//total rec <-- from bal2
							if($get_ps['receipt_type'] == 1)
							{
								$sumrec_q = mysql_query("SELECT SUM(received) AS sumRec FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							}else
							{
								$sumrec_q = mysql_query("SELECT SUM(received) AS sumRec FROM balance_transaction WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND month_receipt = '".$date."' AND bal_date <= '".$newM."'");
			
								$sumrec_q = mysql_query("SELECT SUM(received) AS sumRec FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date LIKE '%".$date."%'");
							}
							$get_rec = mysql_fetch_assoc($sumrec_q);	
							
							//PERSON LOAN
							$person_q = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_package = '".$get_ps['scheme']."' AND branch_id = '".$_SESSION['login_branchid']."' AND loan_status = 'Paid' AND payout_date < '".$newM."'");
							$person1 = mysql_num_rows($person_q);
							
							if($get_ps['totp_loan_date'] < $date)
							{
								$ppl1 = $get_ps['totp_loan'];
							}else
							{
								$ppl1 = 0;
							}
							
							if($get_ps['receipt_type'] == 1)
							{
								$person = $person1 + $ppl1;
							}else
							{
								$person_q = mysql_query("SELECT * FROM customer_loanpackage lp, loan_payment_details pd WHERE lp.loan_package = '".$get_ps['scheme']."' AND lp.branch_id = '".$_SESSION['login_branchid']."' AND lp.id = pd.customer_loanid AND pd.month_receipt = '".$_SESSION['statementmonth']."' GROUP BY pd.customer_loanid, pd.month_receipt");
								$person1 = mysql_num_rows($person_q);
							
								$person = $person1;
							}
							
							//LATE INT
							$lateint_q = mysql_query("SELECT SUM(interest) AS sumLate FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							$get_late = mysql_fetch_assoc($lateint_q);
							$late = $get_late['sumLate'];
							
							if($get_ps['receipt_type'] == 2)
							{
								$lateint_q2 = mysql_query("SELECT SUM(amount) AS sumlateint FROM cashbook WHERE type = 'RECEIVED2' AND transaction LIKE '%LATE INTEREST%' AND date LIKE '%".$date."%' AND package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."'"); 
								$getlate2 = mysql_fetch_assoc($lateint_q2);
								
								$late = $late + $getlate2['sumlateint'];
							}	
							
							//INT
							$lateint_q = mysql_query("SELECT SUM(interest2) AS sumLate FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							$get_intr = mysql_fetch_assoc($lateint_q);
							$intr = $get_intr['sumLate'];			
					
			
							//CASH <-- get from cashbook
							$cashcf_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."'");
							$get_cashcf = mysql_fetch_assoc($cashcf_q);
							
							$cash_cf = $get_cashcf['amount'];
							$cash_cfdate = $get_cashcf['date'];
							
							$cash_d = date('Y-m', strtotime($cash_cfdate));
							
							if($cash_d == $date)
							{
								$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date <= '".$newM."'");
								$get_psb = mysql_fetch_assoc($prevbal_q);
								
								$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'REC' AND date <= '".$newM."'");
								$get_psb2 = mysql_fetch_assoc($prevbal2_q);
								
								$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'EXPENSES' AND date <= '".$newM."'");
								$get_psb3 = mysql_fetch_assoc($prevbal3_q);
								
								$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN' AND date <= '".$newM."'");
								$get_psb4 = mysql_fetch_assoc($prevbal4_q);
								
								$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND date <= '".$newM."'");
								$get_psb5 = mysql_fetch_assoc($prevbal5_q);
								
								$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN2' AND date <= '".$newM."'");
								$get_psb6 = mysql_fetch_assoc($prevbal6_q);
								
								$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'CCM' AND date <= '".$newM."'");
								$get_psb7 = mysql_fetch_assoc($prevbal7_q);
								
								$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER1' AND date <= '".$newM."'");
								$get_psb8 = mysql_fetch_assoc($prevbal8_q);
								
								$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER2' AND date <= '".$newM."'");
								$get_psb9 = mysql_fetch_assoc($prevbal9_q);
								
								$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'EXT' AND date <= '".$newM."'");
								$get_psb10 = mysql_fetch_assoc($prevbal10_q);
								
								$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date <= '".$newM."'");
								$get_psb11 = mysql_fetch_assoc($prevbal11_q);
								
								
								$cash = $cash_cf - $get_psb['amtOut'] + $get_psb2['amtIn'] - $get_psb3['amtEx'] + $get_psb4['amtKom'] + $get_psb5['amtOth'] + $get_psb6['amtKom2'] + $get_psb7['amtCCM'] - $get_psb8['amtTrans'] + $get_psb9['amtTrans2'] - $get_psb10['amtExt'] + $get_psb11['amtKInt'];
							}else
							if($cash_d > $date)
							{
								$cash = 0;
							}
							else
							{ 
							
								$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date <= '".$newM."'");
								$get_psb = mysql_fetch_assoc($prevbal_q);
								
								$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'REC' AND date <= '".$newM."'");
								$get_psb2 = mysql_fetch_assoc($prevbal2_q);
								
								$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'EXPENSES' AND date <= '".$newM."'");
								$get_psb3 = mysql_fetch_assoc($prevbal3_q);
								
								$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN' AND date <= '".$newM."'");
								$get_psb4 = mysql_fetch_assoc($prevbal4_q);
								
								$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND date <= '".$newM."'");
								$get_psb5 = mysql_fetch_assoc($prevbal5_q);
								
								$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN2' AND date <= '".$newM."'");
								$get_psb6 = mysql_fetch_assoc($prevbal6_q);
								
								$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'CCM' AND date <= '".$newM."'");
								$get_psb7 = mysql_fetch_assoc($prevbal7_q);
								
								$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER1' AND date <= '".$newM."'");
								$get_psb8 = mysql_fetch_assoc($prevbal8_q);
								
								$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER2' AND date <= '".$newM."'");
								$get_psb9 = mysql_fetch_assoc($prevbal9_q);
								
								$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'EXT' AND date <= '".$newM."'");
								$get_psb10 = mysql_fetch_assoc($prevbal10_q);
								
								$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date <= '".$newM."'");
								$get_psb11 = mysql_fetch_assoc($prevbal11_q);
								
								
								$cash = $cash_cf - $get_psb['amtOut'] + $get_psb2['amtIn'] - $get_psb3['amtEx'] + $get_psb4['amtKom'] + $get_psb5['amtOth'] + $get_psb6['amtKom2'] + $get_psb7['amtCCM'] - $get_psb8['amtTrans'] + $get_psb9['amtTrans2'] - $get_psb10['amtExt'] + $get_psb11['amtKInt'];	
							
							}
							
							//SET STYLE
							if($stock < 0)
							{
								$style = "style='color:#FF0000'";
							}
							
							if($cash < 0)
							{
								$style2 = "style='color:#FF0000'";
							}	
			
							?>
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000"><?php echo $get_ps['scheme']; ?></span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $style; ?>><?php echo number_format($stock, '2');  ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $style2; ?>><?php echo number_format($cash, '2'); ?></td>
							</tr>
							<?php if($get_ps['receipt_type'] == 1){  ?>
							<tr>
								<td>LOAN (<?php echo strtoupper(date('M', strtotime($date))); ?>)</td>
								<td>RM</td>
								<td><?php echo number_format($get_loan['sumLoan'], '2'); ?></td>
							</tr>
							<tr>
								<td>TOTAL LOAN </td>
								<td>RM</td>
								<td><?php echo number_format($totloan, '2'); ?></td>
							</tr>
							<?php } else {?>
							<tr>
								<td>TOTAL LOAN (<?php echo strtoupper(date('M', strtotime($date))); ?>)</td>
								<td>RM</td>
								<td><?php echo number_format($get_loan['sumLoan'], '2'); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td>TOTAL COLLECTION </td>
								<td>RM</td>
								<td><?php echo number_format($get_rec['sumRec'], '2'); ?></td>
							</tr>
							<?php if($get_ps['type'] == 'Flexi Loan' && $get_ps['receipt_type'] == 2) { 
								$exp_q = mysql_query("SELECT SUM(amount) AS expAmt FROM expenses WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND ttype = 'EXPENSES'");
								$get_exp = mysql_fetch_assoc($exp_q);
								$exp = $get_exp['expAmt'];
							
							?>
							<tr>
								<td>TOTAL EXPENSES </td>
								<td>RM</td>
								<td><?php echo number_format($exp, '2'); ?></td>
							</tr>
							<?php } ?>
							<?php if($get_ps['receipt_type'] == '2'){  ?>
							<tr>
								<td>LATE INT </td>
								<td>RM</td>
								<td><?php echo number_format($late, '2'); ?></td>
							</tr>
							<?php } else { ?>
							<tr>
								<td>INT </td>
								<td>RM</td>
								<td><?php echo number_format($intr, '2'); ?></td>
							</tr>
							<tr>
								<td>LATE INT </td>
								<td>RM</td>
								<td><?php echo number_format($late, '2'); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td>PERSON LOAN </td>
								<td>&nbsp;</td>
								<td><?php echo $person; ?></td>
							</tr>
					</table>		</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php }  
				$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme != 'SKIM KUTU' AND scheme != 'SKIM S' ORDER BY scheme DESC");
				$ctr1 = 0;
				while($get_p = mysql_fetch_assoc($package_q)){
				
				//check cashbook cf
				$cbcheck_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$get_p['id']."' AND branch_id = '".$_SESSION['login_branchid']."'");
				$cbcheck = mysql_num_rows($cbcheck_q);
				if($cbcheck != 0)
				{
				$ctr1++;
				?>
				
				<tr>
					<td>
						<table id="list_table" width="350">
							
							<?php
							$package_id = $get_p['id'];
							$style = '';
							$style2 = '';
							$cash = 0;
							
							
							//stock <-- from bal1
							$bal1cf_q = mysql_query("SELECT * FROM bal1_cf WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."'");
							$get_bal1cf = mysql_fetch_assoc($bal1cf_q);
							
							$stock1 = $get_bal1cf['amount'];
							$stock_date = $get_bal1cf['date'];
							
							$stock_od = date('Y-m', strtotime($stock_date));
							
							if($stock_od == $date)
							{
								$stock = $stock1; 
							}else
							if($stock_od > $date)
							{
								$stock = 0;
							}
							else
							{ 
								$sum_q = mysql_query("SELECT SUM(loan) AS sumLoan, SUM(received) AS sumRec FROM balance_transaction WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."'");
								$get_sum = mysql_fetch_assoc($sum_q);		
							
								$stock = $stock1 + $get_sum['sumRec'] - $get_sum['sumLoan'];
							}
							
							//total loan <-- from bal2
							if($get_p['receipt_type'] == 1)
							{
								$sumloan_q = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
								$get_loan = mysql_fetch_assoc($sumloan_q);	
								
								$sumloan_q2 = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date < '".$startM."'");
								$get_loan2 = mysql_fetch_assoc($sumloan_q2);
								
								//get preset amount
								$preloan_q = mysql_query("SELECT * FROM preset_amount WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."' AND preloan_date <= '".$startM."'"); 
								$preloan = mysql_fetch_assoc($preloan_q);
								
								$totloan = $get_loan2['sumLoan'] + $get_loan['sumLoan'] + $preloan['preloan_amt'];					
							}else
							{
								$sumloan_q = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND month_receipt = '".$date."'");
								$get_loan = mysql_fetch_assoc($sumloan_q);	
							}
							
							//total rec <-- from bal2
							if($get_p['receipt_type'] == 1)
							{
								$sumrec_q = mysql_query("SELECT SUM(received) AS sumRec FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							}else
							{
								$sumrec_q = mysql_query("SELECT SUM(received) AS sumRec FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND month_receipt = '".$date."' AND bal_date <= '".$newM."'");
							}
							$get_rec = mysql_fetch_assoc($sumrec_q);	
							
							if($package_id != '6')
							{
							//LATE INT
							$lateint_q = mysql_query("SELECT SUM(interest) AS sumLate FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							$get_late = mysql_fetch_assoc($lateint_q);
							$late = $get_late['sumLate'];	
							
							//INT
							$lateint_q = mysql_query("SELECT SUM(interest2) AS sumLate FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							$get_intr = mysql_fetch_assoc($lateint_q);
							$intr = $get_intr['sumLate'];			
							}else
							{
							//INT
							$lateint_q = mysql_query("SELECT SUM(amount) AS sumLate FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND date BETWEEN '".$startM."' AND '".$newM."'");
							$get_late = mysql_fetch_assoc($lateint_q);
							$intr = $get_late['sumLate'];	
							
							//LATE INT
							$lateint_q = mysql_query("SELECT SUM(amount) AS sumLate FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND date BETWEEN '".$startM."' AND '".$newM."'");
							$get_intr = mysql_fetch_assoc($lateint_q);
							$late = $get_intr['sumLate'];	
							}
					
							//CASH <-- get from cashbook
							$cashcf_q = mysql_query("SELECT * FROM cashbook_cf WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."'");
							$get_cashcf = mysql_fetch_assoc($cashcf_q);
							
							$cash_cf = $get_cashcf['amount'];
							$cash_cfdate = $get_cashcf['date'];
							
							$cash_d = date('Y-m', strtotime($cash_cfdate));
							
							if($cash_d == $date)
							{
								$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date <= '".$newM."'");
								$get_pb = mysql_fetch_assoc($prevbal_q);
								
								$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'REC' AND date <= '".$newM."'");
								$get_pb2 = mysql_fetch_assoc($prevbal2_q);
								
								$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'EXPENSES' AND date <= '".$newM."'");
								$get_pb3 = mysql_fetch_assoc($prevbal3_q);
								
								$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN' AND date <= '".$newM."'");
								$get_pb4 = mysql_fetch_assoc($prevbal4_q);
								
								$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND date <= '".$newM."'");
								$get_pb5 = mysql_fetch_assoc($prevbal5_q);
								
								$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN2' AND date <= '".$newM."'");
								$get_pb6 = mysql_fetch_assoc($prevbal6_q);
								
								$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'CCM' AND date <= '".$newM."'");
								$get_pb7 = mysql_fetch_assoc($prevbal7_q);
								
								$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER1' AND date <= '".$newM."'");
								$get_pb8 = mysql_fetch_assoc($prevbal8_q);
								
								$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER2' AND date <= '".$newM."'");
								$get_pb9 = mysql_fetch_assoc($prevbal9_q);
								
								$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'EXT' AND date <= '".$newM."'");
								$get_pb10 = mysql_fetch_assoc($prevbal10_q);
								
								$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date <= '".$newM."'");
								$get_pb11 = mysql_fetch_assoc($prevbal11_q);
								
								$prevbal12_q = mysql_query("SELECT SUM(amount) AS amtKInt2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'RECEIVED' AND date <= '".$newM."'");
								$get_pb12 = mysql_fetch_assoc($prevbal12_q);
								
								
								$cash = $cash_cf - $get_pb['amtOut'] + $get_pb2['amtIn'] - $get_pb3['amtEx'] + $get_pb4['amtKom'] + $get_pb5['amtOth'] + $get_pb6['amtKom2'] + $get_pb7['amtCCM'] - $get_pb8['amtTrans'] + $get_pb9['amtTrans2'] - $get_pb10['amtExt'] + $get_pb11['amtKInt'] + $get_pb12['amtKInt2'];	 
							}else
							if($cash_d > $date)
							{
								$cash = 0;
							}
							else
							{ 
							
								$prevbal_q = mysql_query("SELECT SUM(amount) AS amtOut FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'LOAN' AND date <= '".$newM."'");
								$get_pb = mysql_fetch_assoc($prevbal_q);
								
								$prevbal2_q = mysql_query("SELECT SUM(amount) AS amtIn FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'REC' AND date <= '".$newM."'");
								$get_pb2 = mysql_fetch_assoc($prevbal2_q);
								
								$prevbal3_q = mysql_query("SELECT SUM(amount) AS amtEx FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'EXPENSES' AND date <= '".$newM."'");
								$get_pb3 = mysql_fetch_assoc($prevbal3_q);
								
								$prevbal4_q = mysql_query("SELECT SUM(amount) AS amtKom FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN' AND date <= '".$newM."'");
								$get_pb4 = mysql_fetch_assoc($prevbal4_q);
								
								$prevbal5_q = mysql_query("SELECT SUM(amount) AS amtOth FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND date <= '".$newM."'");
								$get_pb5 = mysql_fetch_assoc($prevbal5_q);
								
								$prevbal6_q = mysql_query("SELECT SUM(amount) AS amtKom2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'KOMISYEN2' AND date <= '".$newM."'");
								$get_pb6 = mysql_fetch_assoc($prevbal6_q);
								
								$prevbal7_q = mysql_query("SELECT SUM(amount) AS amtCCM FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'CCM' AND date <= '".$newM."'");
								$get_pb7 = mysql_fetch_assoc($prevbal7_q);
								
								$prevbal8_q = mysql_query("SELECT SUM(amount) AS amtTrans FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER1' AND date <= '".$newM."'");
								$get_pb8 = mysql_fetch_assoc($prevbal8_q);
								
								$prevbal9_q = mysql_query("SELECT SUM(amount) AS amtTrans2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'TRANSFER2' AND date <= '".$newM."'");
								$get_pb9 = mysql_fetch_assoc($prevbal9_q);
								
								$prevbal10_q = mysql_query("SELECT SUM(amount) AS amtExt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'EXT' AND date <= '".$newM."'");
								$get_pb10 = mysql_fetch_assoc($prevbal10_q);
								
								$prevbal11_q = mysql_query("SELECT SUM(amount) AS amtKInt FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'COMMISSION' AND date <= '".$newM."'");
								$get_pb11 = mysql_fetch_assoc($prevbal11_q);
								
								$prevbal12_q = mysql_query("SELECT SUM(amount) AS amtKInt2 FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND transaction = 'INT' AND type = 'RECEIVED' AND date <= '".$newM."'");
								$get_pb12 = mysql_fetch_assoc($prevbal12_q);
								
								$cash = $cash_cf - $get_pb['amtOut'] + $get_pb2['amtIn'] - $get_pb3['amtEx'] + $get_pb4['amtKom'] + $get_pb5['amtOth'] + $get_pb6['amtKom2'] + $get_pb7['amtCCM'] - $get_pb8['amtTrans'] + $get_pb9['amtTrans2'] - $get_pb10['amtExt'] + $get_pb11['amtKInt'] + $get_pb12['amtKInt2'];
							
							}
							
							//PERSON LOAN
							$person_q = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_package = '".$get_p['scheme']."' AND branch_id = '".$_SESSION['login_branchid']."' AND (loan_status = 'Paid' OR loan_status = 'Finished') AND apply_date <= '".$newM."'");
							$person1 = mysql_num_rows($person_q);
							
							if($preloan['preloan_date'] < $date)
							{
								$ppl1 = $preloan['preperson'];
							}else
							{
								$ppl1 = 0;
							}
							
							if($get_p['receipt_type'] == 1)
							{
								$person = $person1 + $ppl1;
							}
							else
							{
								$person_q = mysql_query("SELECT * FROM customer_loanpackage lp, loan_payment_details pd WHERE lp.loan_package = '".$get_p['scheme']."' AND lp.branch_id = '".$_SESSION['login_branchid']."' AND lp.id = pd.customer_loanid AND pd.month_receipt = '".$_SESSION['statementmonth']."' GROUP BY pd.customer_loanid, pd.month_receipt");
								$person1 = mysql_num_rows($person_q);
								$person = $person1;
							}
							
							//SET STYLE
							if($stock < 0)
							{
								$style = "style='color:#FF0000'";
							}
							
							if($cash < 0)
							{
								$style2 = "style='color:#FF0000'";
							}	
							
							if($stock != 0)
							?>
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000"><?php echo $get_p['scheme']; ?></span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $style; ?>><?php echo number_format($stock, '2');  ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $style2; ?>><?php echo number_format($cash, '2'); ?></td>
							</tr>
							<?php if($get_p['receipt_type'] == 1){  ?>
							<tr>
								<td>LOAN (<?php echo strtoupper(date('M', strtotime($date))); ?>)</td>
								<td>RM</td>
								<td><?php echo number_format($get_loan['sumLoan'], '2'); ?></td>
							</tr>
							<tr>
								<td>TOTAL LOAN </td>
								<td>RM</td>
								<td><?php echo number_format($totloan, '2'); ?></td>
							</tr>
							<?php } else {?>
							<tr>
								<td>TOTAL LOAN (<?php echo strtoupper(date('M', strtotime($date))); ?>)</td>
								<td>RM</td>
								<td><?php echo number_format($get_loan['sumLoan'], '2'); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td>TOTAL COLLECTION </td>
								<td>RM</td>
								<td><?php echo number_format($get_rec['sumRec'], '2'); ?></td>
							</tr>
							<?php if($get_p['type'] == 'Flexi Loan' && $get_p['receipt_type'] == 2) { 
								$exp_q = mysql_query("SELECT SUM(amount) AS expAmt FROM expenses WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND ttype = 'EXPENSES'");
								$get_exp = mysql_fetch_assoc($exp_q);
								$exp = $get_exp['expAmt'];
							
							?>
							<tr>
								<td>TOTAL EXPENSES </td>
								<td>RM</td>
								<td><?php echo number_format($exp, '2'); ?></td>
							</tr>
							<?php } ?>
							<?php if($get_p['receipt_type'] == '2'){  ?>
							<tr>
								<td>LATE INT </td>
								<td>RM</td>
								<td><?php echo number_format($late, '2'); ?></td>
							</tr>
							<?php } else { ?>
							<tr>
								<td>INT </td>
								<td>RM</td>
								<td><?php echo number_format($intr, '2'); ?></td>
							</tr>
							<tr>
								<td>LATE INT </td>
								<td>RM</td>
								<td><?php echo number_format($late, '2'); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td>PERSON LOAN </td>
								<td>&nbsp;</td>
								<td><?php echo $person; ?></td>
							</tr>
					</table>		</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php }
				} 
				//SKIM KUTU
				$sk_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM KUTU'");
				$kt = mysql_fetch_assoc($sk_q);
				if($kt)
				{
				?>
				<tr>
					<td>
						<table id="list_table" width="350">
							
							<?php
							
								$kutu_sq = mysql_query("SELECT SUM(stock) AS sumS FROM skim_kutu WHERE branch_id = '".$_SESSION['login_branchid']."' AND date <= '".$newM."'");
								$kutuS = mysql_fetch_assoc($kutu_sq);
								
								$kutu_Cq = mysql_query("SELECT SUM(inamt) AS sumStock, SUM(outAmt) AS sumCash FROM cashbook_skimkutu WHERE branch_id = '".$_SESSION['login_branchid']."' AND date <= '".$newM."'");
								$kutuC = mysql_fetch_assoc($kutu_Cq);
								
								$kutustock = $kutuS['sumS'] - $kutuC['sumCash'];
								$kutucash = $kutuC['sumStock'] - $kutuC['sumCash'];
							?>
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000"><?php echo $kt['scheme']; ?></span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" style="color:#FF0000"><?php echo number_format($kutustock, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td><?php echo number_format($kutucash, '2'); ?></td>
							</tr>
						</table>		
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php 
				}
				?>
			</table>
		</td>
		<!--end of monthly statement-->
		<!--outstanding statement-->
		<td width="50%" valign="top">
			<table width="100%" id="list_table">
				<tr>
					<td width="1277">
						<table width="350" id="list_table">
							<tr>
								<td colspan="2"><div align="center"><strong>OUTSTANDING<BR />
									<?php echo strtoupper(date('F Y', strtotime($date))); ?></strong>
								</div></td>
							</tr>
							<?php
								$b2out_q = mysql_query("SELECT * FROM bal2_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1' AND month <= '".$nextoutmonth."' ORDER BY month DESC");
								while($b2out = mysql_fetch_assoc($b2out_q))
								{
									$outstandingq = mysql_query("SELECT SUM(loan) AS sloan, SUM(received) AS srec, SUM(commission) AS scom, SUM(interest) AS sint, SUM(expenses) AS sexp FROM balance_rec WHERE package_id = '1' AND branch_id = '".$_SESSION['login_branchid']."' AND month_receipt = '".$b2out['month']."' AND bal_date <= '".$newM."'");
									$getoutstanding = mysql_fetch_assoc($outstandingq);
									
									$bout2 = $b2out['amount'] - $getoutstanding['sloan'] + $getoutstanding['srec'] + $getoutstanding['scom'] + $getoutstanding['sint'] - $getoutstanding['sexp']; 
							?>
							<tr>
								<td><?php echo strtoupper(date('M Y', strtotime($b2out['month']))); ?></td>
								<td><?php echo "RM ".number_format($bout2, '2'); ?></td>
							</tr>
							<?php } ?>
						</table>				  
					</td>
				</tr>
			</table>
		</td>
		<!--end of outstanding statement-->
	</tr>
	<tr>
		<td align="right" colspan="2"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>

</center>
<script>
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this user: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_staff',
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

function showEdit(no)
{
	if(document.getElementById('edit_' + no).style.visibility == 'hidden')
	{
		document.getElementById('edit_' + no).style.visibility = 'visible';	
	}else
	if(document.getElementById('edit_' + no).style.visibility == 'visible')
	{
		document.getElementById('edit_' + no).style.visibility = 'hidden';
	}
}

function updateAmount(no, id)
{
	$amount = $('#amount_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
}

// mini jQuery plugin that formats to two decimal places
(function($) {
	$.fn.currencyFormat = function() {
    	this.each( function( i ) {
        $(this).change( function( e ){
        	if( isNaN( parseFloat( this.value ) ) ) return;
        	this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
    }
})( jQuery );

// apply the currencyFormat behaviour to elements with 'currency' as their class
$( function() {
    $('.currency').currencyFormat();
});
$('#date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Date"}).focus(); } ).
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
// mini jQuery plugin that formats to two decimal places
(function($) {
	$.fn.currencyFormat = function() {
    	this.each( function( i ) {
        $(this).change( function( e ){
        	if( isNaN( parseFloat( this.value ) ) ) return;
        	this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
    }
})( jQuery );
</script>
</body>
</html>