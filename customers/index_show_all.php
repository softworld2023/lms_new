<?php 
include('../include/page_header.php'); 
$_SESSION['custid'] = '';
if(isset($_POST['search']))
{
	if($_POST['customer_name'] != '')
	{
		$cond .= " and name = '".$_POST['customer_name']."'";	
	}
	
	if($_POST['nric'] != '')
	{
		$cond .= " and nric = '".$_POST['nric']."'";	
	}
	
	if($_POST['customer_code'] != '')
	{
		$cond .= " and customercode2 = '".$_POST['customer_code']."'";	
	}
	
	if($_POST['lcode'] != '')
	{
	
		$lcodeq = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$_POST['lcode']."'");
		$lcode = mysql_fetch_assoc($lcodeq);
		$cond .= " and id = '".$lcode['customer_id']."'";	
	}
	
	$statement = "`customer_details` WHERE branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY LPAD(customercode2, 20, '0')";
	$_SESSION['cust_s'] = $statement;
}
else
if($_SESSION['cust_s'] != '')
{
	$statement = $_SESSION['cust_s'];
}
else
{
	$statement = "`customer_details` WHERE branch_id = '".$_SESSION['login_branchid']."' ORDER BY LPAD(customercode2, 20, '0')";
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
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/customers/customers.png"></td>
        <td>Customers</td>
        <td align="right">
		<form action="" method="post">
        	<table>
            	<tr>
            	  <td align="right" style="padding-right:10px">I/C No. </td>
                	<td align="right" style="padding-right:10px"><input type="text" name="nric" id="nric" style="height:30px" /></td>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:10px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
					<td align="right" style="padding-right:10px">Customer Code </td>
					<td align="right" style="padding-right:10px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px" /></td>
                    <td style="padding-right:10px">Loan Code </td>
                    <td style="padding-right:10px"><input type="text" name="lcode" id="lcode" style="height:30px; width:70px" /></td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
                </tr>
            </table>
        </form>
		</td>
        <td align="right" width="120">
        	<a href="apply_loan.php" title="Apply Loan (New Customer)">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Apply Loan</td>
                	</tr>
                </table>
            </a>        </td>
    </tr>
	<tr>
    	<td colspan="4">
        <div class="subnav">
			<a href="index.php" id="active-menu">Own Branch Customer Listing</a><a href="other_listing.php">Other Branch Listing</a>
		</div>	
        </td>
    </tr>
    <tr>
    	<td colspan="4">
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
    	<th width="39">No.</th>
        <th width="94">Cust Code </th>
        <th width="192">Name</th>
        <th width="124">I.C Number</th>
        <th width="223">Company</th>
        <th width="102">Loan Code </th>
        <th width="107">Total Loan</th>
        <th width="363"><div id="rl"></div></th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	$style = '';
	if($get_q['blacklist'] == 'Yes')
	{
		$style = 'style="color:#F00"';
	}
	?>
    <tr <?php echo $style; ?>>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo strtoupper($get_q['customercode2']); ?></td>
        <td><?php echo $get_q['name']; ?></td>
        <td><?php echo $get_q['nric']; ?></td>
        <td>
        	<?php 
				$company_q = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$get_q['id']."'");
				$company = mysql_fetch_assoc($company_q);
				echo $company['company'];
			?>        </td>
        <td>
			<?php
			$lcode_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' and loan_status = 'Paid'");
			$lcr = mysql_num_rows($lcode_q);
			$lctr = 0;
			while($lcode = mysql_fetch_assoc($lcode_q))
			{
				$lctr++;
				
				if($lctr != $lcr)
				{
					echo $lcode['loan_code'].", ";
				}else
				{
					echo $lcode['loan_code'];
				}
			}
			?>
		</td>
        <td>
        	<?php
				$loan_q = mysql_query("SELECT SUM(loan_amount) AS loan_amount FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' AND loan_status = 'Paid'");
				$loan = mysql_fetch_assoc($loan_q);
				if($loan['loan_amount'] != '')
				{
					echo "RM ".$loan['loan_amount'];
				}else
				{
					echo "RM 0.00";
				}
			?>        </td>
        <td>
        	<center>
			<a href="../lampiran/print_borangJ.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/lampiran.png" alt="" title="Borang J" /></a>
			<a href="../lampiran/print_lampiranA.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/lampiran.png" alt="" title="Lampiran A" /></a>
            	<a href="view_customer.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/view-icon.png" alt="" title="View Particular" /></a>&nbsp;&nbsp;&nbsp;<a href="history.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/history-icon.png" title="History"></a>&nbsp;&nbsp;&nbsp;
                <!--<a href="javascript:blackList('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/follow-up-icon.png" title="Status"></a>-->
				<?php if($get_q['blacklist'] != 'Yes')
				{
				?>				
				<a href="sendtoblacklist.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox; width=800px; height=400px"><img src="../img/customers/follow-up-icon.png" title="Status"></a>
				<?php } else { //clear the blacklist status 
				if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')){
				?>
				<a href="javascript:blacklistClear('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/follow-up-icon.png" title="Status"></a>
				<?php }else
				{
				?>
					<a href="javascript:blacklistStatus('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/follow-up-icon.png" title="Status"></a>
				<?php } } ?>
				&nbsp;&nbsp;&nbsp;
                <a href="add_loan.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/apply-loan.png" title="Apply Loan"></a>&nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')){ ?>
                <a href="edit_cust.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;&nbsp;
                <a href="javascript:deleteConfirmation('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
				<?php } else {?>
				<a href="edit_cust2.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
				<?php } ?>
				</center></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
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
	
	$("#customer_code").autocomplete("auto_custCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
	
	$("#nric").autocomplete("auto_nric.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	$("#lcode").autocomplete("auto_loanCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
  
});

function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this customer: ' + name + ' ?<br><br>All of the records for this customer will be deteted from the database.',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_customer',
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

function blacklistStatus(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Status Confirmation',
		'message'	: name + ' has been blacklisted!',
		'buttons'	: {
			'OK'	: {
				'class'	: 'gray',
				'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
			}
		}
	});
}

function blacklistClear(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Blacklist Confirmation',
		'message'	: 'Are you sure want to remove the blacklist status for this customer: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'remove_blacklist',
							id: $id,
						},
						url: 'action_blacklist.php',
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

Shadowbox.init();
</script>
