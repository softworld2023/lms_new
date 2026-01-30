<?php 
include('../include/page_header.php'); 

if(isset($_POST['search']))
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
		$cond .= " and package_id = '".$_POST['loan_package']."'";
	}
	
	$statement = "`late_interest_record` WHERE 1 $cond AND branch_id = '".$_SESSION['login_branchid']."'";
	$_SESSION['payment_ls'] = $statement;
}
else
if($_SESSION['payment_ls'] != '')
{
	$statement = $_SESSION['payment_ls'];
}
else
{
	$statement = "`late_interest_record` WHERE status = '' AND branch_id = '".$_SESSION['login_branchid']."'";
}


$sql = mysql_query("SELECT * FROM {$statement} ORDER BY loan_code");

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
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Late Interest Payment</td>
        <td align="right">
       	<form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Loan Code</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
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
			<a href="index.php">Payment Listing</a><a href="lateIntPayment.php" id="active-menu">Late Interest Payment Listing</a><?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?>
		</div>	
		<div style="float:right">
			<a href="add_lateCust.php" title="New Late Payment Customer">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Add New </td>
                	</tr>
                </table>
            </a>
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
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
    	<th>Customer Name</th>
    	<th>Loan Code</th>
    	<th>Package</th>
        <th width="150">Late Interest Amount</th>
        <th width="50"></th>
    </tr>
    <?php 
	$ctr = 0;
	$totout = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_cust = mysql_fetch_assoc($cust_q);
	
	//package
	$scheme_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['package_id']."'");
	$get_scheme = mysql_fetch_assoc($scheme_q);
	
	$totout += $get_q['balance'];
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo $get_cust['name']; ?></td>
    	<td><?php echo $get_q['loan_code']; ?></td>
    	<td><?php echo $get_scheme['scheme']; ?></td>
        <td><?php echo "RM ".$get_q['balance']; ?></td>
        <td><a href="payLateInt.php?id=<?php echo $get_q['id']; ?>" title="View Payment Record"><img src="../img/report-icon.png" /></a></td>
    </tr>
    <?php } ?>
    <tr>
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
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
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
