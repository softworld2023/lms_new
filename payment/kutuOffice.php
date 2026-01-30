<?php 
include('../include/page_header.php'); 

$sql = mysql_query("SELECT * FROM kutu_office WHERE branch_id = '".$_SESSION['login_branchid']."'");

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
        <td>Payment Kutu Office</td>
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
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" /></td>
                </tr>
            </table>
        </form>        </td>
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
			<a href="index.php">Payment Listing</a><a href="lateIntPayment.php">Late Interest Payment Listing</a><?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?><a href="kutuOffice.php" id="active-menu">Kutu Office</a>		
		</div>	
		<div style="float:right">
        	<a href="add_kutuOffice.php" title="New Kutu Office">
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
            </div>		</td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
    	<th>Title</th>
    	<th>Stock Amount </th>			
        <th>In</th>
        <th>Balance</th>	
        <th>Period</th>
    </tr>
    <?php 
	$tbal = 0;
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$stock_amt_q = mysql_query("SELECT amount FROM kutuOffice_payment WHERE skim_id = '".$get_q['id']."' ORDER BY month DESC");
	$stock_amt = mysql_fetch_assoc($stock_amt_q);
	
	$stock_left = $stock_amt['amount'];

	$tbal += $stock_left;
	
	$payment_q = mysql_query("SELECT SUM(inamt) AS tot_inamt, SUM(outamt) AS tot_outamt FROM kutuoffice_payment WHERE skim_id = '".$get_q['id']."'");
	$payment = mysql_fetch_assoc($payment_q);
	$tot_inamt = $payment['tot_inamt'];
	$tot_outamt = $payment['tot_outamt'];
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
    	<td><a href="payment_kutuOffice.php?id=<?php echo $get_q['id']; ?>"><?php echo $get_q['title']; ?></a></td>
        <td><?php echo "RM ".number_format($get_q['stock'], '2'); ?></td>		
        <td><?php echo "RM ".number_format($tot_inamt, '2'); ?></td>
    	<td><?php echo "RM ".number_format($stock_left, '2'); ?></td>		
        <td><?php echo $get_q['period']; ?></td>
    </tr>
    <?php } ?>
    
    <tr>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>	  
      <td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($tbal, '2'); ?></strong></td>
      <td align="right">&nbsp;</td>		  
    </tr>
    <tr>
    	<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
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
