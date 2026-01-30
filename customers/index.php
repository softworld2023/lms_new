<?php 
error_reporting(0);
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
		$lcodeq = mysql_query("SELECT * FROM customer_loanpackage WHERE 
								loan_code = '".$_POST['lcode']."' 
								and branch_id = '".$_SESSION['login_branchid']."'");
		$lcode = mysql_fetch_assoc($lcodeq);
		$cond .= " and id = '".$lcode['customer_id']."'";

	}
	$statement = "`customer_details` WHERE (customer_status NOT IN ('PENDING APPROVAL','REJECTED') OR customer_status IS NULL) 
					AND branch_id = '".$_SESSION['login_branchid']."' 
					$cond
					ORDER BY LPAD(customercode2, 20, '0')";
	$_SESSION['cust_s'] = $statement;
}
else
{
	$statement = "`customer_details` WHERE (customer_status NOT IN ('PENDING APPROVAL','REJECTED') OR customer_status IS NULL) 
					AND branch_id = '".$_SESSION['login_branchid']."'
					ORDER BY LPAD(customercode2, 20, '0')";

}


// page is the current page, if there's nothing set, default is page 1
if(isset($_POST['currentPage']))
{
	$page = isset($_POST['currentPage']) ? $_POST['currentPage'] : $_POST['currentPage'];
	//$page = isset($_GET['page']) ? $_GET['page'] : 1;
}
else
{
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
}

// set records or rows of data per page
$recordsPerPage = 30;
 
// calculate for the query LIMIT clause
$fromRecordNum = ($recordsPerPage * $page) - $recordsPerPage;

// var_dump("SELECT * FROM {$statement} LIMIT {$fromRecordNum}, {$recordsPerPage}");
$sql = mysql_query("SELECT * FROM {$statement} LIMIT {$fromRecordNum}, {$recordsPerPage}");
//$sqlPaging = mysql_query("SELECT COUNT(*) as total_rows FROM {$statement}");
$sqlPaging = mysql_query("SELECT * FROM {$statement}");

/*echo "<h3> PHP List All Session Variables</h3>";
    foreach ($_SESSION as $key=>$val)
    echo $key." ".$val."<br/>"; */
	
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

.customBtn {
   border-top: 1px solid #96d1f8;
   background: #fa9c00;
   background: -webkit-gradient(linear, left top, left bottom, from(#cc7e00), to(#fa9c00));
   background: -webkit-linear-gradient(top, #cc7e00, #fa9c00);
   background: -moz-linear-gradient(top, #cc7e00, #fa9c00);
   background: -ms-linear-gradient(top, #cc7e00, #fa9c00);
   background: -o-linear-gradient(top, #cc7e00, #fa9c00);
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
   border-top-color: #fa9c00;
   background: #fa9c00;
   color: #ccc;
   }
.customBtn:active {
   border-top-color: #fa9c00;
   background: #fa9c00;
   }	
   
  .customBtn a {
   color: #FFFFFF;
   }
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/customers/customers.png"></td>
        <td>Customers</td>
        <td align="right">
		<form action="index.php" method="post">
        	<table>
            	<tr>
            	  <td align="right" style="padding-right:10px">I/C No. </td>
                	<td align="right" style="padding-right:10px"><input type="text" name="nric" id="nric" style="height:30px" /></td>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:10px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
					<td align="right" style="padding-right:10px">Customer ID </td>
					<td align="right" style="padding-right:10px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px" /></td>
                    <td style="padding-right:10px">Agreement No </td>
                    <td style="padding-right:10px"><input type="text" name="lcode" id="lcode" style="height:30px; width:70px" /></td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
                </tr>


            </table>
     
		</td>
        <td align="right" width="120">
        	<a href="apply_loan.php" title="Apply Loan (New Customer)">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Apply Loan</td>
                	</tr>
                </table>
            </a>
		</td>
    </tr>
	<tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php" id="active-menu">Customer Listing</a>
			<a href="pending_approval.php" >Pending Approval Listing</a>
			<!-- <a href="other_listing.php">Other Branch Listing</a> -->
		</div>	
        </td>
		<td align="right">
			<input type="submit" id="search_1" name="search" value="Show all list"/>
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
    </tr>   </form>
</table>
<table width="1280" id="list_table">
	<tr>
    	<th width="39">No.</th>
        <th width="94">Customer ID </th>
         <th width="102">Agreement No</th>
        <th width="192">Name</th>
        <th width="124">I.C Number</th>
        <th width="223">Company</th>
        <th width="107">Total Loan</th>
        <th width="363"><div id="rl"></div></th>
    </tr>
    <?php 
	$ctr = 0;
	
	$startno = $fromRecordNum + 1; 
	$r = mysql_num_rows($sqlPaging);
	
if($r>0){
	while($get_q = mysql_fetch_assoc($sqlPaging)){ 
	$ctr++;
	$style = '';
	if($get_q['blacklist'] == 'Yes')
	{
		//$style = 'style="color:#F00"';
	}
	?>
    <tr <?php echo $style; ?>>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo strtoupper($get_q['customercode2']); ?></td>
        <td>
			<?php
				$lcode_q = mysql_query("SELECT * FROM customer_loanpackage WHERE 
										customer_id = '".$get_q['id']."' 
										");
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
        <td><?php echo $get_q['name']; ?></td>
        <td><?php echo $get_q['nric']; ?></td>
        <td>
        	<?php 
				$company_q = mysql_query("SELECT * FROM customer_employment WHERE 
											customer_id = '".$get_q['id']."'");
				$company = mysql_fetch_assoc($company_q);
				echo $company['company'];
			?>
		</td>
        <td>
        	<?php
				$loan_q = mysql_query("SELECT SUM(loan_amount) 
										AS loan_amount 
										FROM customer_loanpackage WHERE 
										customer_id = '".$get_q['id']."' 
										");
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
<!-- 			<a href="../lampiran/print_borangJ.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/lampiran.png" alt="" title="Borang J" /></a>
			<a href="../lampiran/print_lampiranA.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/lampiran.png" alt="" title="Lampiran A" /></a> -->
            	
				<a href="view_customer.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/view-icon.png" alt="" title="View Particular" /></a>&nbsp;&nbsp;&nbsp;
				
				<a href="history.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/history-icon.png" title="History"></a>&nbsp;&nbsp;&nbsp;
				
                <!--<a href="javascript:blackList('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/follow-up-icon.png" title="Status"></a>-->
				<!-- <?php if($get_q['blacklist'] != 'Yes')
				{
				?>				
				<a href="sendtoblacklist.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox; width=800px; height=400px"><img src="../img/customers/follow-up-icon.png" title="Status"></a>
				<?php } else { //clear the blacklist status 
				if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')){
				?>
				<a href="javascript:blacklistClear('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/follow-up-icon.png" title="Status"></a>
				<?php }else
				{
				?>
					<a href="javascript:blacklistStatus('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/follow-up-icon.png" title="Status"></a>
				<?php } } ?> -->
			
                <a href="add_loan.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/apply-loan.png" title="Apply Loan"></a>&nbsp;&nbsp;&nbsp;
				<a href="../payment/add_monthly.php?id=<?php echo $get_q['id']; ?>" style="margin-right: 10px;">
					<img src="../img/apply-loan/add-btn.jpg" title="Add Monthly">
				</a>
                <a href="edit_cust.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')){ ?>
                <a href="javascript:deleteConfirmation('<?php echo $get_q['name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
				<?php } else {?>
			<!-- <?php 
					$level_query = mysql_query("SELECT *FROM user where username ='".$_SESSION['login_username']."'");
					$get_level = mysql_fetch_assoc($level_query);
					$level = $get_level['level'];
					if($level!= 'Staff'){?>
				<a href="edit_cust2.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
			<?php }?>  -->
				<?php } ?>
				</center></td>
    </tr>
    <?php } }?>
    <!--<tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>-->
</table>
</center>

<br><br><br><br><br>
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
