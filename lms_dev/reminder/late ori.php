<?php include('../include/page_header.php'); 
$today = date('Y-m-d');
if(isset($_POST['search']))
{
	if($_POST['loan_package'] != '')
	{
		//$cond .= " and lp.loan_package = '".$_POST['loan_package']."'";	
		$cond .= " and lp.package_id = '".$_POST['loan_package']."'"; 
	}
	
	if($_POST['year'] != '')
	{
		//$cond .= " and pd.next_paymentdate LIKE '%".$_POST['year']."%'";	
		$cond .= " and lp.next_paymentdate LIKE '%".$_POST['year']."%'"; 
		$_SESSION['month_late'] = $_POST['year'];
	}
	
	if($_POST['fromdate'] != '')
	{
		$fromdate = date('Y-m-d', strtotime($_POST['fromdate']));
		
		if($_POST['todate'] != '')
		{
			$todate = date('Y-m-d', strtotime($_POST['todate']));
		}else
		{
			$todate = date('Y-m-d');
		}
		
		$cond .= " and lp.next_paymentdate BETWEEN '".$fromdate."' AND '".$todate."'"; 
	}
	
	//$statement = "`loan_payment_details` pd, `customer_loanpackage` lp, `customer_details` cd WHERE pd.payment_date = '0000-00-00' AND pd.balance != '0' AND lp.id = pd.customer_loanid AND cd.id = lp.customer_id ";
	
	$statement = "`customer_details` cd, `loan_payment_details` lp, `customer_loanpackage` cl WHERE lp.payment_date = '0000-00-00' AND lp.balance != '0' AND lp.next_paymentdate < '".$today."' AND lp.branch_id = '".$_SESSION['login_branchid']."' AND cl.id = lp.customer_loanid AND cl.customer_id = cd.id $cond ORDER BY cd.name ASC";

	$_SESSION['late_s'] = $statement;
}
else
if($_SESSION['late_s'] != '')
{
	$statement = $_SESSION['late_s'];
}
else
{
	//$statement = "`loan_payment_details` pd, `customer_loanpackage` lp, `customer_details` cd WHERE pd.payment_date = '0000-00-00' AND pd.balance != '0' AND lp.id = pd.customer_loanid AND cd.id = lp.customer_id";
	$statement = "`customer_details` cd, `loan_payment_details` lp, `customer_loanpackage` cl WHERE lp.payment_date = '0000-00-00' AND lp.balance != '0' AND lp.next_paymentdate < '".$today."' AND lp.branch_id = '".$_SESSION['login_branchid']."' AND cl.id = lp.customer_loanid AND cl.customer_id = cd.id ORDER BY cd.name ASC";
	$_SESSION['month_late'] = '';
}


$sql = mysql_query("SELECT * FROM {$statement}");


?>

<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
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
#rel_list
{
	padding-left:15px;
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
    	<td width="65"><?php echo $to; ?><img src="../img/collection-reminder/collection-reminder.png"></td>
        <td>Collection Reminder</td>
        <td align="right">
		<form action="" method="post">
        	<table>
            	<tr>
					<td align="right" style="padding-right:10px">Date From </td>
                    <td style="padding-right:30px"><input type="text" name="fromdate" id="fromdate" style="width:100px; height:30px" /></td>
					<td align="right" style="padding-right:10px">To</td>
                    <td style="padding-right:30px"><input type="text" name="todate" id="todate" style="width:100px; height:30px" /></td>
                	<td align="right" style="padding-right:10px">Package</td>
                    <td style="padding-right:30px">
						<select name="loan_package" id="loan_package" style="height:30px">
                        	<option value="">All</option>
                            <?php
							$package_q = mysql_query("SELECT * FROM loan_scheme");
							while($get_p = mysql_fetch_assoc($package_q)){
							?>
                            <option value="<?php echo $get_p['id']; ?>"><?php echo $get_p['scheme']; ?></option>
                            <?php } ?>
                        </select> 
					</td>
					<td align="right" style="padding-right:10px">Year </td>
                    <td style="padding-right:30px"><input type="text" name="year" id="year" style="height:30px; width:70px" /></td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
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
				<a href="index.php">To Be Collected</a><a href="late.php" id="active-menu">Late Form Scheduled</a>
            </div>
        </td>
    </tr>
</table>
<br />
<table width="1280" id="list_table">
	<!--<tr>
    	<td>
        <center>
           	<iframe src="http://cm1.cmctos.com.my/creditfile/Customer.do?submit=List" width="1280" height="650" style="border:none"></iframe>
        </center>
        </td>
    </tr>-->
    <tr>
    	<th width="50">No.</th>
        <th width="300">Customer Name</th>
        <th>Package</th>
        <th>Scheduled Payment Date</th>
        <th width="150">Total Loan </th>
        <th width="150">Total Late Payment </th>
    </tr>
    <?php
	$ctr = 0;
	$tot_bal = 0;
    while($get_q = mysql_fetch_assoc($sql))
	{
		
		/*
		if(date('m') == '02' && $get_q['a_payday'] > 28)
		{ 
			$payday = '28'; 
		}else
		{ 
			$payday = $get_q['a_payday']; 
		} 
		
		if($payday < 10)
		{
			$payday = '0'.$payday;
		*/
		
		$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
		$loan = mysql_fetch_assoc($loan_q);
		
		$scheme_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$loan['loan_package']."'");
		$scheme = mysql_fetch_assoc($scheme_q);
		
		$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
		$cust = mysql_fetch_assoc($cust_q);
		$npd = $get_q['next_paymentdate']; 
		//echo $npd;
		
		$nm = $get_q['month'] + 1;
		//next payment 
		/*$np_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND month = '".$nm."'");
		$getnp = mysql_fetch_assoc($np_q);
		if(!$getnp)
		{*/
			/*if(strtotime($npd) < strtotime($today) )
			{*/ 
			$ctr++;
			
			/*$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' ORDER BY id ASC");
			$get_bal = mysql_fetch_assoc($bal_q);
			
			$tot_bal += $get_bal['balance'];*/
	?>
    <tr>
    	<td valign="top" style="padding-top:10px"><?php echo $ctr; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $cust['name']; ?></td>
        <td valign="top" style="padding-top:10px"><a href="history.php?id=<?php echo $cust['id']; ?>" rel="shadowbox"><?php echo $loan['loan_package']." (".$get_q['receipt_no'].")"; ?></a></td>
        <td valign="top" style="padding-top:10px"><?php echo date('d-m-Y', strtotime($get_q['next_paymentdate'])); ?></td>
        <td valign="top" style="padding-top:10px"><?php echo "RM ".$loan['loan_total']; ?></td>
        <td valign="top" style="padding-top:10px">
		<?php 
				if($loan['loan_type'] == 'Flexi Loan') 
				{ 
					if($scheme['receipt_type'] == '2')
					{
						echo "RM ".$get_q['balance'];
					}else
					{
						/*if($_SESSION['month_late'] != '')
						{	
							$m = date('m', strtotime($_SESSION['month_late'])) + 1;
							$Y = date('Y', strtotime($_SESSION['month_late']));
							
							if($m <= '12')
							{
								if($m <= '9')
								{
									$nm = $Y.'-0'.$m;
									
								}else
								{
									$nm = $Y.'-'.$m;
								}
							}else
							{
								$Y++;
								
								$nm = $Y.'-01';
							}
							$sumlate_q = mysql_query("SELECT SUM(balance) AS sumLate FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND payment_date = '0000-00-00' AND next_paymentdate < '".$nm."'");
							$sumlate = mysql_fetch_assoc($sumlate_q);
						}else
						{
							$sumlate_q = mysql_query("SELECT SUM(balance) AS sumLate FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND payment_date = '0000-00-00' AND next_paymentdate < '".$today."'");
							$sumlate = mysql_fetch_assoc($sumlate_q);
						}
						echo "RM ".number_format($sumlate['sumLate'], '2', '.', '');
						*/
						
						echo "RM ".$get_q['balance'];
					}
				} else
				{
					//get sum total loan should have been paid (late payment)
					if($_SESSION['month_late'] != '')
					{	
						$m = date('m', strtotime($_SESSION['month_late'])) + 1;
						$Y = date('Y', strtotime($_SESSION['month_late']));
						
						if($m <= '12')
						{
							if($m <= '9')
							{
								$nm = $Y.'-0'.$m;
								
							}else
							{
								$nm = $Y.'-'.$m;
							}
						}else
						{
							$Y++;
							
							$nm = $Y.'-01';
						}
						$sumlate_q = mysql_query("SELECT SUM(monthly) AS sumLate FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND payment_date = '0000-00-00' AND next_paymentdate < '".$nm."'");
						$sumlate = mysql_fetch_assoc($sumlate_q);
					}else
					{
						$sumlate_q = mysql_query("SELECT SUM(monthly) AS sumLate FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND payment_date = '0000-00-00' AND next_paymentdate < '".$today."'");
						$sumlate = mysql_fetch_assoc($sumlate_q);
					}
					echo "RM ".number_format($sumlate['sumLate'], '2', '.', '');
				}
		?>		</td>
    </tr>
    <?php 
			//}
		//}
	} ?>
	<tr>
    	<td colspan="4">&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
    <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
    <tr>
    	<td align="right" colspan="7"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
</table>
</center>
<script>
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
  
});
$(document).ready(function() {   
   $("#icno").autocomplete("auto_Nric.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
  
});
$('#year').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
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

$('#fromdate').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select From Date"}).focus(); } ).
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
$('#todate').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select To Date"}).focus(); } ).
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
</body>
</html>