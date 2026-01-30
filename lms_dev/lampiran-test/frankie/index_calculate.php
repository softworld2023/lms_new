<?php 
include('../include/page_header.php'); 
?>
<style>
#tblmain
{
	font-size:11.5px;
}
</style>
<center>
	<table width="1149" height="486" background="../img/home/box.png" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><table id="tblmain" width="1149" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td height="45">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><center><a href="../payment/"><img src="../img/home/payment-received.png" alt=""/></a></center></td>
            <td>&nbsp;</td>
            <td><center><a href="../cashbook/"><img src="../img/home/cash-book.png" alt=""/></a></center></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="30">&nbsp;</td>
            <td width="99">&nbsp;</td>
            <td width="132">&nbsp;</td>
            <td width="99"><center>Payment Received</center></td>
            <td width="132">&nbsp;</td>
            <td width="102"><center>Cash Book</center></td>
            <td width="132">&nbsp;</td>
            <td width="102">&nbsp;</td>
            <td width="132">&nbsp;</td>
            <td width="98">&nbsp;</td>
          </tr>
          <tr height="110">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><center><img src="../img/home/down-arrow.png" alt="" style="height:90px" /></center></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><center><a href="../enquiry/"><img src="../img/home/enquiry.png"/></a></center></td>
            <td><center><img src="../img/home/to-right-arrow.png" alt="" width="132" height="22" /></center></td>
            <td><center><a href="../customers/"><img src="../img/home/customers.png"/></a></center></td>
            <td><center><img src="../img/home/to-right-arrow.png" alt="" width="132" height="22" /></center></td>
            <td><center><a href="../customers/apply_loan.php"><img src="../img/home/apply-loan.png"/></a></center></td>
            <td><center><img src="../img/home/to-right-arrow.png" alt="" width="132" height="22" /></center></td>
            <td><center><a href="../loan/"><img src="../img/home/approval.png"/></a></center></td>
            <td><center><img src="../img/home/to-right-arrow.png" alt="" width="132" height="22" /></center></td>
            <td><center><a href="../payout/"><img src="../img/home/payout.png"/></a></center></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><center>Enquiry</center></td>
            <td>&nbsp;</td>
            <td><center>Customers</center></td>
            <td>&nbsp;</td>
            <td><center>Apply Loan</center></td>
            <td>&nbsp;</td>
            <td><center>Approval</center></td>
            <td>&nbsp;</td>
            <td><center>Payout</center></td>
          </tr>
          <tr height="100">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><center><img src="../img/home/up-arrow.png" alt="" style="height:90px" /></center></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><center><a href="../reminder/"><img src="../img/home/collection-reminder.png" alt=""/></a></center></td>
            <td>&nbsp;</td>
            <td><center><a href="../blacklist/"><img src="../img/home/check-blacklist.png" alt=""/></a></center></td>
            <td>&nbsp;</td>
            <td><center><a href="../report/"><img src="../img/home/report.png" alt=""/></a></center></td>
            <td>&nbsp;</td>
            <td><center><a href="../package/"><img src="../img/home/package.png" alt=""/></a></center></td>
            <td>&nbsp;</td>
            <td><center><a href="../expenses/"><img src="../img/expenses.png" width="63" height="53"/></a></center></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><center>Collection Reminder</center></td>
            <td>&nbsp;</td>
            <td><center>Check Blacklist</center></td>
            <td>&nbsp;</td>
            <td><center>Reports</center></td>
            <td>&nbsp;</td>
            <td><center>Package</center></td>
            <td>&nbsp;</td>
            <td><center>Expenses</center></td>
          </tr>
        </table></td>
      </tr>
    </table>
<br><br>
</center>
<?php /*
$todayd = date('Y-m-d');

//echo $todayd."<br>";

$scheme_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM CEK'");
$shceme = mysql_fetch_assoc($scheme_q);


$latep_q = mysql_query("SELECT * FROM loan_payment_details WHERE next_paymentdate < '".$todayd."' AND balance > 0 AND payment_date = '0000-00-00' AND package_id != '".$shceme['id']."' AND branch_id = '".$_SESSION['login_branchid']."' ORDER BY customer_loanid ASC, month ASC");
while($latep = mysql_fetch_assoc($latep_q))
{
	$cyr = date('Y', strtotime($latep['next_paymentdate']));
	$cmt = date('m', strtotime($latep['next_paymentdate']));
	$cdt = date('d', strtotime($latep['next_paymentdate']));
		
	//echo $cyr." ".$cmt." ".$cdt."<br>";

	
	//check next payment record
	$nx = $latep['month'] + 1;
	$nextp_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$latep['customer_loanid']."' AND month = '".$nx."'");
	$nextp = mysql_fetch_assoc($nextp_q);
	if(!$nextp)
	{
		//echo $latep['customer_loanid'];
		//check balance
		
		
		
		$d1 = new DateTime($latep['next_paymentdate']);
		$d2 = new DateTime();
		$d3 = $d1->diff($d2);
		$d4 = ($d3->y*12)+$d3->m;
		
		$loanp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$latep['customer_loanid']."'");
		$loanp = mysql_fetch_assoc($loanp_q);
		
		$cm = $latep['month'];
		if($loanp['loan_type'] == 'Fixed Loan')
		{
			$balance_month = ($latep['balance'] * 1) / ($latep['monthly'] * 1);
			
			//check sum balance
			$sum_b_q = mysql_query("SELECT SUM(monthly) AS sumMly FROM loan_payment_details WHERE next_paymentdate < '".$todayd."' AND balance > 0 AND payment_date = '0000-00-00' AND customer_loanid = '".$latep['customer_loanid']."'");
			$sumb = mysql_fetch_assoc($sum_b_q);
			if($sumb['sumMly'] < $latep['balance'])
			{
			//echo $sumb['sumMly']." ".$latep['balance']."<br>";
			
				for($i = 1; $i < $balance_month; $i++)
				{
					$cm++;
					$cmt++;
					
					if($cmt <= 12)
					{
						if($cmt == 1)
						{
							$nextpd = $cyr."-01-".$cdt;
						}
						if($cmt == 2)
						{
							if($cdt > 28)
							{
								$nextpd = $cyr."-02-28";
							}else
							{
								$nextpd = $cyr."-02-".$cdt;
							}
						}
						if($cmt == 3)
						{
							$nextpd = $cyr."-03-".$cdt;
						}
						if($cmt == 4)
						{
							if($cdt == 31)
							{
								$nextpd = $cyr."-04-30";
							}else
							{
								$nextpd = $cyr."-04-".$cdt;
							}
						}
						if($cmt == 5)
						{
							$nextpd = $cyr."-05-".$cdt;
						}
						if($cmt == 6)
						{
							if($cdt == 31)
							{
								$nextpd = $cyr."-06-30";
							}else
							{
								$nextpd = $cyr."-06-".$cdt;
							}
						}
						if($cmt == 7)
						{
							$nextpd = $cyr."-07-".$cdt;
						}
						if($cmt == 8)
						{
							$nextpd = $cyr."-08-".$cdt;
						}
						if($cmt == 9)
						{
							if($cdt == 31)
							{
								$nextpd = $cyr."-09-30";
							}else
							{
								$nextpd = $cyr."-09-".$cdt;
							}
						}
						if($cmt == 10)
						{
							$nextpd = $cyr."-10-".$cdt;
						}
						if($cmt == 11)
						{
							if($cdt == 31)
							{
								$nextpd = $cyr."-11-30";
							}else
							{
								$nextpd = $cyr."-11-".$cdt;
							}
						}
						if($cmt == 12)
						{
							$nextpd = $cyr."-12-".$cdt;
						}
					}
					else
					{
						$cyr++;
						$nextpd = $cyr."-01-".$cdt;
						$cmt = '01';
					}
					$addnew = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$latep['customer_loanid']."', month = '".$cm."', int_percent = '".$latep['int_percent']."', monthly = '".$latep['monthly']."', balance = '".$latep['balance']."', int_balance = '".$latep['int_balance']."', next_paymentdate = '".$nextpd."', package_id = '".$latep['package_id']."', receipt_no = '".$latep['receipt_no']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}
			}
		}
		
		if($loanp['loan_type'] == 'Flexi Loan')
		{
			for($i = 1; $i <= $d4; $i++)
			{
				$cm++;
				$cmt++;
				
				if($cmt <= 12)
				{
					if($cmt == 1)
					{
						$nextpd = $cyr."-01-".$cdt;
						$mtrcp = $cyr."-01";
					}
					if($cmt == 2)
					{
						if($cdt > 28)
						{
							$nextpd = $cyr."-02-28";
						}else
						{
							$nextpd = $cyr."-02-".$cdt;
						}
						$mtrcp = $cyr."-02";
					}
					if($cmt == 3)
					{
						$nextpd = $cyr."-03-".$cdt;
						$mtrcp = $cyr."-03";
					}
					if($cmt == 4)
					{
						if($cdt == 31)
						{
							$nextpd = $cyr."-04-30";
						}else
						{
							$nextpd = $cyr."-04-".$cdt;
						}
						$mtrcp = $cyr."-04";
					}
					if($cmt == 5)
					{
						$nextpd = $cyr."-05-".$cdt;
						$mtrcp = $cyr."-05";
					}
					if($cmt == 6)
					{
						if($cdt == 31)
						{
							$nextpd = $cyr."-06-30";
						}else
						{
							$nextpd = $cyr."-06-".$cdt;
						}
						$mtrcp = $cyr."-06";
					}
					if($cmt == 7)
					{
						$nextpd = $cyr."-07-".$cdt;
						$mtrcp = $cyr."-07";
					}
					if($cmt == 8)
					{
						$nextpd = $cyr."-08-".$cdt;
						$mtrcp = $cyr."-08";
					}
					if($cmt == 9)
					{
						if($cdt == 31)
						{
							$nextpd = $cyr."-09-30";
						}else
						{
							$nextpd = $cyr."-09-".$cdt;
						}
						$mtrcp = $cyr."-09";
					}
					if($cmt == 10)
					{
						$nextpd = $cyr."-10-".$cdt;
						$mtrcp = $cyr."-10";
					}
					if($cmt == 11)
					{
						if($cdt == 31)
						{
							$nextpd = $cyr."-11-30";
						}else
						{
							$nextpd = $cyr."-11-".$cdt;
						}
						
						$mtrcp = $cyr."-11";
					}
					if($cmt == 12)
					{
						$nextpd = $cyr."-12-".$cdt;
						$mtrcp = $cyr."-12";
					}
				}
				else
				{
					$cyr++;
					$nextpd = $cyr."-01-".$cdt;
					$cmt = '01';
					$mtrcp = $cyr."-01";
				}
				
				$addnew = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$latep['customer_loanid']."', month = '".$cm."', int_percent = '".$latep['int_percent']."', monthly = '".$latep['monthly']."', balance = '".$latep['balance']."', int_balance = '".$latep['int_balance']."', next_paymentdate = '".$nextpd."', month_receipt = '".$mtrcp."', package_id = '".$latep['package_id']."', receipt_no = '".$latep['receipt_no']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			}
		}
		
	}
}

$fpy_q = mysql_query("SELECT * FROM loan_payment_details WHERE month = '1' AND payment_date = '0000-00-00'");
while($fpy = mysql_fetch_assoc($fpy_q))
{
	//echo $fpy['id'];
	$spy_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$fpy['customer_loanid']."' AND month != '1' AND payment_date != '0000-00-00'");
	$spy = mysql_fetch_assoc($spy_q);
	
	if($spy)
	{
		$update = mysql_query("UPDATE loan_payment_details SET payment_date = '".$spy['payment_date']."' WHERE id = '".$fpy['id']."'");
	}
}
*/
?>
</body>
</html>