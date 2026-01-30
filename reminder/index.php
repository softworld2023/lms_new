<?php include('../include/page_header.php'); 
$_SESSION['payday'] = 0;

if(isset($_POST['search']))
{
	if($_POST['year'] != '')
	{	
		$cond .= " and pd.next_paymentdate = '".$_POST['year']."'";
		$_SESSION['payday'] = 1;
		if($_POST['loan_package'] != '')
		{
			$cond .= " and lp.loan_package = '".$_POST['loan_package']."'";	
		}
		
		$statement = "`loan_payment_details` pd, `customer_loanpackage` lp, `customer_details` cd WHERE pd.payment_date = '0000-00-00' AND pd.balance != '0' AND lp.id = pd.customer_loanid AND cd.id = lp.customer_id AND lp.branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY lp.loan_code ASC";
		$_SESSION['remind_s'] = $statement;

	}else
	{
		$_SESSION['payday'] = 0;
		if($_POST['loan_package'] != '')
		{
			$cond .= " and lp.loan_package = '".$_POST['loan_package']."'";	
		}
		
		$statement = "`loan_payment_details` pd, `customer_loanpackage` lp, `customer_details` cd WHERE pd.payment_date = '0000-00-00' AND pd.balance != '0' AND lp.id = pd.customer_loanid AND cd.id = lp.customer_id AND lp.branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY lp.loan_code ASC";
		$_SESSION['remind_s'] = $statement;
	}
}
else
if($_SESSION['remind_s'] != '')
{
	$statement = $_SESSION['remind_s'];
}
else
{
	$statement = "`loan_payment_details` pd, `customer_loanpackage` lp, `customer_details` cd WHERE pd.payment_date = '0000-00-00' AND pd.balance != '0' AND lp.id = pd.customer_loanid AND cd.id = lp.customer_id AND lp.branch_id = '".$_SESSION['login_branchid']."' ORDER BY lp.loan_code ASC";
}

$sql = mysql_query("SELECT * FROM {$statement}");


?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
<script type="text/javascript" src="../include/js/jquery-latest.js"></script> 
<script type="text/javascript" src="../include/js/jquery.tablesorter.js"></script> 
<link href="../include/css/tablesorter.css" rel="stylesheet" type="text/css" />
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
<script>
$.tablesorter.addWidget({
    id: "numbering",
    format: function(table) {
        var c = table.config;
        $("tr:visible", table.tBodies[0]).each(function(i) {
            $(this).find('td').eq(0).text(i + 1);
        });
    }
});
$(document).ready(function() 
    { 
        $(".tablesorter").tablesorter({
			headers: {
				0: {
					sorter: false
				},
				4: {
					sorter: false
				},
				5: {
					sorter: false
				},
				6: {
					sorter: false
				}
			},
			widgets: ['numbering']
		}); 
    } 
); 
    
</script>
<center>
<table width="1280">
	<tr>
    	<td width="65"><?php echo $to; ?><img src="../img/collection-reminder/collection-reminder.png"></td>
        <td>Collection Reminder</td>
        <td align="right">
		<form action="" method="post">
        	<table>
            	<tr>
					<td align="right" style="padding-right:10px">Package</td>
                    <td style="padding-right:30px">
						<select name="loan_package" id="loan_package" style="height:30px">
                        	<option value="">All</option>
                            <?php
							$package_q = mysql_query("SELECT * FROM loan_scheme");
							while($get_p = mysql_fetch_assoc($package_q)){
							?>
                            <option value="<?php echo $get_p['scheme']; ?>"><?php echo $get_p['scheme']; ?></option>
                            <?php } ?>
                        </select>					</td>
					<td align="right" style="padding-right:10px">Pay day  </td>
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
				<a href="index.php" id="active-menu">To Be Collected</a><a href="late.php">Late From Scheduled</a>
            </div>
        </td>
    </tr>
</table>
<br />

<?php if($_SESSION['payday'] == 0)
{
?>
<table width="1280" id="tt" class="tablesorter">
	<!--<tr>
    	<td>
        <center>
           	<iframe src="http://cm1.cmctos.com.my/creditfile/Customer.do?submit=List" width="1280" height="650" style="border:none"></iframe>
        </center>
        </td>
    </tr>-->
	<thead>
    <tr>
    	<th width="50">No.</th>
        <th width="120">Customer Code</th>
        <th width="300">Customer Name</th>
        <th>Package</th>
        <th>Scheduled Payment Date</th>
        <th width="150">Total Loan</th>
        <th width="150">Balance</th>
    </tr>
	</thead>
	<tbody>
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
		
		$npd = $get_q['next_paymentdate']; 
		
		$today = date('Y-m-d');
		
		$due = strtotime(date($npd));
		$duedate = date('Y-m-d',strtotime($npd));
		$noti = date('Y-m-d',strtotime('-3 days',$due));
		if(strtotime($noti)<= strtotime($today) && strtotime($today) <= strtotime($npd) ){ 
		$ctr++;
		$tot_bal += $get_q['balance'];
	?>
    <tr>
   
    	<td valign="top" style="padding-top:10px"><?php echo $ctr; ?></td>
        <td valign="top" style="padding-top:10px"><a href="history.php?id=<?php echo $get_q['customer_id']; ?>" rel="shadowbox"><?php echo strtoupper($get_q['customercode2']); ?></a></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['name']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['loan_package']." (".$get_q['receipt_no'].")";  ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['next_paymentdate']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo "RM ".$get_q['loan_total']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['balance']; ?></td>
    </tr>
    <?php 
		}
	} ?>
	</tbody>
    <tr>
    	<td colspan="5">&nbsp;</td>
		<td>&nbsp;</td>
		<td style="background:#CCCCCC"><?php echo number_format($tot_bal, '2'); ?></td>
    </tr>
    <tr>
      <td align="right" colspan="7">&nbsp;</td>
    </tr>
    <tr>
    	<td align="right" colspan="7"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
</table>
<?php } else { ?>
<table width="1280" id="tt" class="tablesorter">
	<!--<tr>
    	<td>
        <center>
           	<iframe src="http://cm1.cmctos.com.my/creditfile/Customer.do?submit=List" width="1280" height="650" style="border:none"></iframe>
        </center>
        </td>
    </tr>-->
	<thead>
    <tr>
    	<th width="50">No.</th>
        <th width="120">Customer Code</th>
        <th width="300">Customer Name</th>
        <th>Package</th>
        <th>Scheduled Payment Date</th>
        <th width="150">Total Loan</th>
        <th width="150">Balance</th>
    </tr>
	</thead>
	</tbody>
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
		
		
		$npd = $get_q['next_paymentdate']; */
		
		$today = date('Y-m-d');
		$ctr++;
		$tot_bal += $get_q['balance'];
	?>
    <tr>
    	<td valign="top" style="padding-top:10px"><?php echo $ctr; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['customercode2']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['name']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['loan_package']." (".$get_q['receipt_no'].")";  ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['next_paymentdate']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo "RM ".$get_q['loan_total']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['balance']; ?></td>
    </tr>
	<?php } ?>
	</tbody>
    <tr>
    	<td colspan="5">&nbsp;</td>
		<td>&nbsp;</td>
		<td style="background:#CCCCCC"><?php echo number_format($tot_bal, '2'); ?></td>
    </tr>
    <tr>
      <td align="right" colspan="7">&nbsp;</td>
    </tr>
    <tr>
    	<td align="right" colspan="7"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
</table>
<?php } ?>
</center>
<script>
$('#year').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Month"}).focus(); } ).
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
Shadowbox.init();
</script>
</body>
</html>