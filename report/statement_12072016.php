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
								//echo $m; AND receipt_no LIKE '%".$m." %'
								//mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND date LIKE '%".$date."%' AND branch_id = '1' AND package_id = '1' AND receipt_no LIKE '%".$m." %' ORDER BY receipt_no DESC");
								$person_q = mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."'  ORDER BY receipt_no DESC");
								$person1 = mysql_fetch_assoc($person_q);
								
								$person2 = explode(' ', $person1['receipt_no']);
								//$state = $state2[0];
							//echo $person1['receipt_no'];
								$person = ($person2[1] * 1);
							}
							
							//LATE INT
							$lateint_q = mysql_query("SELECT SUM(interest) AS sumLate FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							$get_late = mysql_fetch_assoc($lateint_q);
							$late = $get_late['sumLate'];
							
							if($get_ps['receipt_type'] == 2)
							{
								$lateint_q2 = mysql_query("SELECT SUM(amount) AS sumlateint FROM cashbook WHERE type = 'RECEIVED2' AND transaction LIKE '%LATE INT%' AND transaction NOT LIKE '%SKIM AH%' AND transaction NOT LIKE '%SKIM H%' AND date LIKE '%".$date."%' AND package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."'"); 
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
							//SKIM S
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
				//other scheme except SKIM KUTU and SKIM S 
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
							
							//total loan <-- from bal2 (total loan selain dari skim kutu dan skim s)
							if($get_p['receipt_type'] == 2)
							{
								$sumloan_q = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
								$get_loan = mysql_fetch_assoc($sumloan_q);
								
								$sumloan_q2 = mysql_query("SELECT SUM(loan) AS sumLoan FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date < '".$startM."'");
								$get_loan2 = mysql_fetch_assoc($sumloan_q2);
								
							}else if($get_p['receipt_type'] == 1)
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
							
							//total rec <-- from bal2(total collection)
							if($get_p['receipt_type'] == 1)
							{
								$sumrec_q = mysql_query("SELECT SUM(received) AS sumRec FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							}else if ($get_p['receipt_type'] == 2)
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
							/*$lateint_q = mysql_query("SELECT SUM(interest) AS sumLate FROM balance_rec WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date BETWEEN '".$startM."' AND '".$newM."'");
							$get_late = mysql_fetch_assoc($lateint_q);
							$late = $get_late['sumLate'];	*/
							
							//INTR
							$lateint_q = mysql_query("SELECT SUM(amount) AS sumLate FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND transaction LIKE '%LATE INT%' AND transaction NOT LIKE '%SEWA%' AND date BETWEEN '".$startM."' AND '".$newM."'");
							$get_intr = mysql_fetch_assoc($lateint_q);
							$late = $get_intr['sumLate'];	
							
							//RENTAL
							$rental_q = mysql_query("SELECT SUM(amount) AS sumLate FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND transaction LIKE '%SEWA%' AND transaction NOT LIKE '%REFUND%' AND date BETWEEN '".$startM."' AND '".$newM."'");
							$get_rental = mysql_fetch_assoc($rental_q);
							$rental = $get_rental['sumLate'];	
							
							//REFUND
							$refund_q = mysql_query("SELECT SUM(amount) AS sumLate FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND transaction LIKE '%REFUND%' AND date BETWEEN '".$startM."' AND '".$newM."'");
							$get_refund = mysql_fetch_assoc($refund_q);
							$refund = $get_refund['sumLate'];	
							
							
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
							$lateint_q = mysql_query("SELECT SUM(amount) AS sumLate FROM cashbook WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND type = 'RECEIVED2' AND transaction LIKE '%LATE INT%' AND date BETWEEN '".$startM."' AND '".$newM."'");
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
							$person_q = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_package = '".$get_p['scheme']."' AND branch_id = '".$_SESSION['login_branchid']."' AND (loan_status = 'Paid' OR loan_status = 'Finished') AND payout_date <= '".$newM."'");
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
								/*$person_q = mysql_query("SELECT * FROM customer_loanpackage lp, loan_payment_details pd WHERE lp.loan_package = '".$get_p['scheme']."' AND lp.branch_id = '".$_SESSION['login_branchid']."' AND lp.id = pd.customer_loanid AND pd.month_receipt = '".$_SESSION['statementmonth']."' GROUP BY pd.customer_loanid, pd.month_receipt");
								$person1 = mysql_num_rows($person_q);
								$person = $person1;*/
								
								//mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND date LIKE '%2015-04%' AND branch_id = '1' AND package_id = '1' AND receipt_no LIKE '%04 %' ORDER BY receipt_no DESC");
								$person_q = mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."' AND receipt_no LIKE '%".$m." %' ORDER BY receipt_no DESC");
								$person1 = mysql_fetch_assoc($person_q);
								
								
								$person2 = explode(' ', $person1['receipt_no']);
								//$state = $state2[0];
							
								$person = ($person2[1] * 1);
								
								if($_SESSION['login_branchid'] == '12' && $package_id == '13')
								{
									$person = 1;
								}
								
								if($package_id == '8')
								{
									$person_q = mysql_query("SELECT * FROM loan_payment_details WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$package_id."' AND month_receipt = '".$date."' GROUP BY customer_loanid, month_receipt");
									$person1 = mysql_num_rows($person_q);
									$person = $person1;
								}
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
								<td>TOTAL LOAN  (<?php echo strtoupper(date('M', strtotime($date))); ?>)</td>
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
                            <?php if($get_p['scheme'] == 'SKIM G') {?>
							<tr>
								<td>REFUND</td>
								<td>RM</td>
								<td><?php echo number_format($refund, '2'); ?></td>
							</tr>
                            <tr>
								<td>RENTAL</td>
								<td>RM</td>
								<td><?php echo number_format($rental, '2'); ?></td>
							</tr>
                            <?php } ?>
							<?php if($get_p['scheme'] == 'SKIM G') {
							$gexp_q = mysql_query("SELECT SUM(amount) AS expAmt FROM expenses WHERE package_id = '".$package_id."' AND branch_id = '".$_SESSION['login_branchid']."' AND date LIKE '%".$date."%' AND ttype = 'EXPENSES'");
							$gget_exp = mysql_fetch_assoc($gexp_q);
							$gexpenses = $gget_exp['expAmt'];
							
							?>
                            <tr>
								<td>EXPENSES</td>
								<td>RM</td>
								<td><?php echo number_format($gexpenses, '2'); ?></td>
							</tr>
                            <?php } ?>
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
				
				?>
				<?php //skim H
				$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1000'");
				$skimh_r = mysql_num_rows($skimh_q);
				if($skimh_r > 0)
				{
				
				
				
					$condh .= " AND ttype = 'RECEIVED' AND (expenses_details LIKE '%SKIM H%' OR expenses_details LIKE '%SKIM (H*%' OR expenses_details LIKE '%H*%') AND expenses_details NOT LIKE '%AH*%' AND expenses_details NOT LIKE '%KH*%' AND expenses_details NOT LIKE '%SH*%'";
					$condh2 .= " AND ttype = 'RECEIVED' AND (expenses_details LIKE '%SKIM H%' OR expenses_details LIKE '%SKIM (H*%' OR expenses_details LIKE '%H*%') AND expenses_details NOT LIKE '%AH*%' AND expenses_details NOT LIKE '%KH*%' AND expenses_details NOT LIKE '%SH*%' AND expenses_details LIKE '%INT%'";
					$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND (expenses_details LIKE '%SKIM H%' OR expenses_details LIKE '%SKIM (H*%' OR expenses_details LIKE '%H*%') AND expenses_details NOT LIKE '%AH*%' AND expenses_details NOT LIKE '%KH*%' AND expenses_details NOT LIKE '%SH*%' AND date <= '".$newM."' ORDER BY date ASC");
				

				
				
				$cbcf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1000'");
				$cbcf = mysql_fetch_assoc($cbcf_q);
				$cfh = $cbcf['amount'];
				$cf_date = $cbcf['date'];
				
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $condh");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				
				$prevbal_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $condh2");
				$get_pb2 = mysql_fetch_assoc($prevbal_q2);
				
				
				$hcollection_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $condh");
				$hcollection = mysql_fetch_assoc($hcollection_q);
				
				$hcollection_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $condh2");
				$hcollection2 = mysql_fetch_assoc($hcollection_q2);
				
				$hcollectiontot = $hcollection['amtrec'] - $hcollection2['amtrec'];
				
				$cfh = $cfh + $get_pb['amtrec']- $get_pb2['amtrec'];
				
				if($cfh < 0)
				{
					$stylestock = "style='color:#FF0000'";
				}else
				{
					$stylestock = '';
				}
				?>
				<tr>
					<td>
						<table id="list_table" width="350">
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000">SKIM H</span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $stylestock; ?>><?php echo number_format($cfh, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $stylecash; ?>><?php echo number_format($hcollectiontot, '2'); ?></td>
							</tr>
						</table>		
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php 
				}//end of skim h
				?>
				<?php //skim AH
				$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1001'");
				$skimh_r = mysql_num_rows($skimh_q);
				if($skimh_r > 0)
				{
					$cond_ah .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH%' AND expenses_details NOT LIKE '%AH CEK%'";
					$cond_ah2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH%' AND expenses_details NOT LIKE '%AH CEK%' AND expenses_details LIKE '%INT%'";
					$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH%' AND expenses_details NOT LIKE '%AH CEK%' AND date <= '".$newM."' ORDER BY date ASC");
				
				$cbcf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1001'");
				$cbcf = mysql_fetch_assoc($cbcf_q);
				$cfah = $cbcf['amount'];
				$cf_date = $cbcf['date'];
				
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ah");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				$prevbal_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ah2");
				$get_pb2 = mysql_fetch_assoc($prevbal_q2);
				
				
				$ahcollection_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ah");
				$ahcollection = mysql_fetch_assoc($ahcollection_q);
				
				$ahcollection_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ah2");
				$ahcollection2 = mysql_fetch_assoc($ahcollection_q2);
				
				$cfah = $cfah + $get_pb['amtrec'] - $get_pb2['amtrec'];
				
				$ahcollectiontot = $ahcollection['amtrec'] - $ahcollection2['amtrec'];
				
				if($cfah < 0)
				{
					$stylestock = "style='color:#FF0000'";
				}else
				{
					$stylestock = '';
				}
				?>
				<tr>
					<td>
						<table id="list_table" width="350">
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000">SKIM AH</span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $stylestock; ?>><?php echo number_format($cfah, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $stylecash; ?>><?php echo number_format($ahcollectiontot, '2'); ?></td>
							</tr>
						</table>		
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php 
				}//end of skim ah
				?>
				<?php //skim AH CEK
				$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1002'");
				$skimh_r = mysql_num_rows($skimh_q);
				if($skimh_r > 0)
				{
					$cond_ahcek .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH CEK%'";
					$cond_ahcek2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH CEK%' AND expenses_details LIKE '%INT%'";
					$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%SKIM AH CEK%' AND date <= '".$newM."' ORDER BY date ASC");
				
				$cbcf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1002'");
				$cbcf = mysql_fetch_assoc($cbcf_q);
				$cfahcek = $cbcf['amount'];
				$cf_date = $cbcf['date'];
				
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ahcek");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				$prevbal_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ahcek2");
				$get_pb2 = mysql_fetch_assoc($prevbal_q2);
				
				
				$ahcekcollection_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ahcek");
				$ahcekcollection = mysql_fetch_assoc($ahcekcollection_q);
				$ahcekcollection_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $cond_ahcek2");
				$ahcekcollection2 = mysql_fetch_assoc($ahcekcollection_q2);
				
				$cfahcek = $cfahcek + $get_pb['amtrec'] - $get_pb2['amtrec'];
				
				$ahcekcollectiontot = $ahcekcollection['amtrec'] - $ahcekcollection2['amtrec'];
				
				if($cfahcek < 0)
				{
					$stylestock = "style='color:#FF0000'";
				}else
				{
					$stylestock = '';
				}
				?>
				<tr>
					<td>
						<table id="list_table" width="350">
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000">SKIM AH CEK</span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $stylestock; ?>><?php echo number_format($cfahcek, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $stylecash; ?>><?php echo number_format($ahcekcollectiontot, '2'); ?></td>
							</tr>
						</table>		
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php 
				}//end of skim ah cek
				?>
				<?php //skim KH
				$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1003'");
				$skimh_r = mysql_num_rows($skimh_q);
				if($skimh_r > 0)
				{
					$condkh.= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%KH*%'";
					$condkh2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%KH*%' AND expenses_details LIKE '%INT%'";
					$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%KH*%' AND date <= '".$newM."' ORDER BY date ASC");
				
				$cbcf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1003'");
				$cbcf = mysql_fetch_assoc($cbcf_q);
				$cfkh = $cbcf['amount'];
				$cf_date = $cbcf['date'];
				
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $condkh");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				$prevbal_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $condkh2");
				$get_pb2 = mysql_fetch_assoc($prevbal_q2);
				
				
				$khcollection_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $condkh");
				$khcollection = mysql_fetch_assoc($khcollection_q);
				$khcollection_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $condkh2");
				$khcollection2 = mysql_fetch_assoc($khcollection_q2);
				
				$cfkh = $cfkh + $get_pb['amtrec'] - $get_pb2['amtrec'];
				
				$khcollectiontot = $khcollection['amtrec'] - $khcollection2['amtrec'];
				
				if($cfkh < 0)
				{
					$stylestock = "style='color:#FF0000'";
				}else
				{
					$stylestock = '';
				}
				?>
				<tr>
					<td>
						<table id="list_table" width="350">
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000">SKIM KH</span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $stylestock; ?>><?php echo number_format($cfkh, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $stylecash; ?>><?php echo number_format($khcollectiontot, '2'); ?></td>
							</tr>
						</table>		
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php 
				}//end of skim kh
				?>
				<?php //skim SH
				$skimh_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1004'");
				$skimh_r = mysql_num_rows($skimh_q);
				if($skimh_r > 0)
				{
					$condsh.= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SH*%'";
					$condsh2 .= " AND ttype = 'RECEIVED' AND expenses_details LIKE '%SH*%' AND expenses_details LIKE '%INT%'";
					$loan_q = mysql_query("SELECT * FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND ttype = 'RECEIVED' AND expenses_details LIKE '%SH*%' AND date <= '".$newM."' ORDER BY date ASC");
				
				$cbcf_q = mysql_query("SELECT * FROM bal1_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1004'");
				$cbcf = mysql_fetch_assoc($cbcf_q);
				$cfsh = $cbcf['amount'];
				$cf_date = $cbcf['date'];
				
				$prevbal_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $condsh");
				$get_pb = mysql_fetch_assoc($prevbal_q);
				$prevbal_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' $condsh2");
				$get_pb2 = mysql_fetch_assoc($prevbal_q2);
				
				
				$shcollection_q = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $condsh");
				$shcollection = mysql_fetch_assoc($shcollection_q);
				$shcollection_q2 = mysql_query("SELECT SUM(amount) AS amtrec FROM expenses WHERE date LIKE '%".$_SESSION['statementmonth']."%' AND branch_id = '".$_SESSION['login_branchid']."' $condsh2");
				$shcollection2 = mysql_fetch_assoc($shcollection_q2);
				
				$cfsh = $cfsh + $get_pb['amtrec'] - $get_pb2['amtrec'];
				
				$shcollectiontot = $shcollection['amtrec'] - $shcollection2['amtrec'];
				
				if($cfsh < 0)
				{
					$stylestock = "style='color:#FF0000'";
				}else
				{
					$stylestock = '';
				}
				?>
				<tr>
					<td>
						<table id="list_table" width="350">
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000">SKIM SH</span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $stylestock; ?>><?php echo number_format($cfsh, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $stylecash; ?>><?php echo number_format($shcollectiontot, '2'); ?></td>
							</tr>
						</table>		
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php 
				}//end of skim sh
				?>
				<?php
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
								
								if($kutustock < 0)
								{
									$stylestock = "style='color:#FF0000'";
								}else
								{
									$stylestock = '';
								}
								
								if($kutucash < 0)
								{
									$stylecash = "style='color:#FF0000'";
								}else
								{
									$stylecash = '';
								}
							?>
							<tr>
								<td colspan="3"><div align="center"><strong><span style="border-bottom:double medium #000"><?php echo $kt['scheme']; ?></span></strong></div></td>
							</tr>
							<tr>
								  <td width="180">STOCK</td>
								  <td width="50">RM</td>
								  <td width="120" <?php echo $stylestock; ?>><?php echo number_format($kutustock, '2'); ?></td>
							</tr>
							<tr>
								  <td>CASH</td>
								  <td>RM</td>
								  <td <?php echo $stylecash; ?>><?php echo number_format($kutucash, '2'); ?></td>
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
				<!--<tr>
					<td>
						<table id="list_table" width="350" style="color:#000099">
							<tr>
								<td width="180"><strong>HQ (A) RETURN</strong></td>
							  	<td width="50"><strong>RM</strong></td>
							  	<td width="120">
								<strong>
									<?php
										$trans_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date LIKE '%".$date."%' AND branch_id = '".$_SESSION['login_branchid']."' AND transfer_details LIKE '%RETURN%'");
										$trans = mysql_fetch_assoc($trans_q);
										$hqreturn = $trans['amount'];
										$balincome_q = mysql_query("SELECT * FROM preset_amount WHERE package_id = 0 AND branch_id = '".$_SESSION['login_branchid']."' AND preloan_date < '".$startM."'");
										$balincome = mysql_fetch_assoc($balincome_q);
										$trans_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date <= '".$newM."' AND branch_id = '".$_SESSION['login_branchid']."' AND transfer_details LIKE '%INCOME%'");
										$trans = mysql_fetch_assoc($trans_q);
										$income = $trans['amount'];
										$tincome = $income + $balincome['preloan_amt'] - $hqreturn;
										
										$hqreturn_p = $hqreturn;
										if($tincome < 0)
										{							
											$trans_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date < '".$startM."' AND branch_id = '".$_SESSION['login_branchid']."' AND transfer_details LIKE '%INCOME%'");
											$trans = mysql_fetch_assoc($trans_q);
											
											$trans1_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date < '".$startM."' AND branch_id = '".$_SESSION['login_branchid']."' AND transfer_details LIKE '%RETURN%'");
											$trans1 = mysql_fetch_assoc($trans1_q);
											$hqreturn = $trans1['amount'];
										
											$income = $trans['amount'];
											$tincome = $income + $balincome['preloan_amt'] - $hqreturn;
											
											
											$hqsave = $hqreturn_p - $tincome;
											$hqreturn = 0;
										}
										
										echo number_format($hqreturn, '2');
									?>
								</strong>								
							</td>
							</tr>
							<tr>
								<td width="180"><strong>HQ (A) INT</strong></td>
							  	<td width="50"><strong>RM</strong></td>
							  	<td width="120">
									<strong>
									<?php
										$trans_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date LIKE '%".$date."%' AND branch_id = '".$_SESSION['login_branchid']."' AND transfer_details LIKE '%INT%'");
										$trans = mysql_fetch_assoc($trans_q);
										echo number_format($trans['amount'], '2');
									?>
								</strong>								
							</td>
							</tr>
							<tr>
								<td width="180"><strong>HQ (A) INCOME</strong></td>
							  	<td width="50"><strong>RM</strong></td>
							  	<td width="120">
									<strong>
									<?php
											$trans1_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date < '".$startM."' AND branch_id = '".$_SESSION['login_branchid']."' AND transfer_details LIKE '%RETURN%'");
											$trans1 = mysql_fetch_assoc($trans1_q);
											$hqreturn_p = $trans1['amount'];
											
											$tincome = $tincome - $hqreturn_p;
											
											if($tincome < 0)
											{
												$tincome = 0;
											}
										echo number_format($tincome, '2');
									?>
								</strong>								
							</td>
							</tr>
							<tr>
								<td width="180"><strong>HQ A SAVE</strong></td>
							  	<td width="50"><strong>RM</strong></td>
							  	<td width="120">
								<strong>
									<?php
										$trans_q = mysql_query("SELECT SUM(amount) AS amount FROM transfer_trans WHERE date LIKE '%".$date."%' AND branch_id = '".$_SESSION['login_branchid']."' AND (transfer_details LIKE '%SAVE%' OR transfer_details LIKE '%SAVING%')");
										$trans = mysql_fetch_assoc($trans_q);
										if($hqreturn == 0) 
										{
											$hqsave = $hqsave + $trans['amount'];
										}else
										{
											$hqsave =  $trans['amount'];
										}
										echo number_format($hqsave, '2');
										
									?>
								</strong>								
								</td>
							</tr>
						</table>
					</td>
				</tr>-->
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
								<td colspan="2">
								<?php
								$outstanding_month_q = mysql_query("SELECT * FROM balance_rec WHERE package_id = '1' AND branch_id = '".$_SESSION['login_branchid']."' AND bal_date <= '".$newM."' GROUP BY month_receipt DESC");
								$get_outstandingmonth = mysql_fetch_assoc($outstanding_month_q);
								$latestom = $get_outstandingmonth['month_receipt'];
								?>
								<div align="center"><strong>OUTSTANDING<BR />
									<?php echo strtoupper(date('F Y', strtotime($date))); ?></strong>
								</div>
								</td>
							</tr>
							<?php
								
								$b2out_q = mysql_query("SELECT * FROM bal2_cf WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '1' AND month <= '".$latestom."' ORDER BY month DESC");
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