<?php 
include('../include/page_header.php'); 

if(isset($_POST['search']))
{
	if($_POST['month'] != '')
	{
		$cond .= " and pd.month_receipt = '".$_POST['month']."' and pd.payment_date = '0000-00-00'";
		
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
		}
		
		if($_POST['loan_type'] != '')
		{
			$cond .= " and lp.loan_type = '".$_POST['loan_type']."'";
		}
		
		$statement = "`loan_payment_details` pd, `customer_loanpackage` lp WHERE lp.loan_status = 'Paid' AND lp.id = pd.customer_loanid AND lp.branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY lp.loan_code ASC";
		$_SESSION['payment_s'] = $statement;
	}else
	{
		if($_POST['customer_name'] != '')
		{
			$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".$_POST['customer_name']."'");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= " and customer_id = '".$cust['id']."'";	
		}
		
		if($_POST['loan_code'])
		{
			$cond .= " and loan_code = '".$_POST['loan_code']."'";
		}
		
		if($_POST['loan_package'] != '')
		{
			$cond .= " and loan_package = '".$_POST['loan_package']."'";
		}
		
		if($_POST['loan_type'] != '')
		{
			$cond .= " and loan_type = '".$_POST['loan_type']."'";
		}
		
		$statement = "`customer_loanpackage` WHERE loan_status = 'Paid' AND branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY loan_code ASC";
		$_SESSION['payment_s'] = $statement;
	}
}
else
if($_SESSION['payment_s'] != '')
{
	$statement = $_SESSION['payment_s'];
}
else
{
	$statement = "`customer_loanpackage` WHERE loan_status = 'Paid' AND branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY loan_code ASC";
}


$sql = mysql_query("SELECT * FROM {$statement}");

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
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Payment</td>
        <td align="right">
       	<form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Loan Code</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
					<td align="right" style="padding-right:10px">Month</td>
                    <td style="padding-right:30px"><input type="text" name="month" id="month" style="height:30px; width:70px" /></td>
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
                        </select>                    </td>
                    <td align="right" style="padding-right:10px">Loan Type</td>
                    <td  style="padding-right:30px">
                    	<select name="loan_type" id="loan_type" style="height:30px">
                        	<option value="">All</option>
                            <option value="Flexi Loan">Flexi Loan</option>
                            <option value="Fixed Loan">Fixed Loan</option>
                        </select>                    </td>
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
	<?php 
	//check skim kutu exist or not
	$skimkutu_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = 'SKIM KUTU'");
	$skimkutu = mysql_num_rows($skimkutu_q);
	
	?>
	<tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php" id="active-menu">Payment Listing</a><a href="lateIntPayment.php">Late Interest Payment Listing</a><?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?>
		</div>
		<div style="float:right"><a href="yearlyledger.php"><input type="button" value="Yearly Ledger" id="yearlyLedger" /></a></div>
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
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
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
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_cust = mysql_fetch_assoc($cust_q);
	
	$style = '';
	
	if($get_cust['blacklist'] == 'Yes')
	{
		$style = "style='color:#FF0000'";
	}
	
	//loan outstanding
	$outst_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."' ORDER BY month DESC");
	$gouts = mysql_fetch_assoc($outst_q);
	
	$totout += $gouts['balance'];
	?>
    <tr <?php echo $style; ?>>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo $get_cust['name']; ?></td>
    	<td><?php echo $get_q['loan_code']; ?></td>
        <td><?php echo "RM ".number_format($get_q['loan_amount'], '2'); ?></td>
        <td><?php echo "RM ".number_format($gouts['balance'], '2'); ?></td>
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
    <?php } ?>
    <tr>
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
    </tr>
    <tr>
    	<td colspan="10" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="10">&nbsp;</td>
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
