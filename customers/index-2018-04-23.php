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
	
	$statement = "`customer_details` WHERE 
					branch_id = '".$_SESSION['login_branchid']."' 
					$cond
					ORDER BY LPAD(customercode2, 20, '0')";
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

$sql = mysql_query("SELECT * FROM {$statement} LIMIT {$fromRecordNum}, {$recordsPerPage}");
//$sqlPaging = mysql_query("SELECT COUNT(*) as total_rows FROM {$statement}");
$sqlPaging = mysql_query("SELECT * FROM {$statement}");


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
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php" id="active-menu">Own Branch Customer Listing</a>
			<a href="other_listing.php">Other Branch Listing</a>
		</div>	
        </td>
		<td align="right">
			<a href="index_show_all.php"><br/>Show All List&nbsp;&nbsp;</a>
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
	
	$startno = $fromRecordNum + 1; 
	$r = mysql_num_rows($sql);
	
if($r>0){
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
				$company_q = mysql_query("SELECT * FROM customer_employment WHERE 
											customer_id = '".$get_q['id']."'");
				$company = mysql_fetch_assoc($company_q);
				echo $company['company'];
			?>
		</td>
        <td>
			<?php
				$lcode_q = mysql_query("SELECT * FROM customer_loanpackage WHERE 
										customer_id = '".$get_q['id']."' 
										and loan_status = 'Paid'");
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
				$loan_q = mysql_query("SELECT SUM(loan_amount) 
										AS loan_amount 
										FROM customer_loanpackage WHERE 
										customer_id = '".$get_q['id']."' 
										AND loan_status = 'Paid'");
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
            	
				<a href="view_customer.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/view-icon.png" alt="" title="View Particular" /></a>&nbsp;&nbsp;&nbsp;
				
				<a href="history.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/history-icon.png" title="History"></a>&nbsp;&nbsp;&nbsp;
				
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
    <?php $startno++;} ?>
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

<br><?php
	 
    // *************** <PAGING_SECTION> ***************
		echo "<div id='paging'>";
		//if($sort=='ASC')
		//{
			//$sort='DESC';
		//}
		//else
		//{
			//$sort='ASC';
		//}		
		
		// ********** show the number paging
		
		// find out total pages
        //$query = "SELECT COUNT(*) as total_rows FROM addmember where status = 'Active' AND 1 $cond ORDER BY $field $sort";
        
        //$stmt=mysql_query($query);
 
        //$get_list = mysql_fetch_assoc($sqlPaging);
        //$total_rows = $get_list['total_rows'];
        $total_rows = mysql_num_rows($sqlPaging);
 
        $total_pages = ceil($total_rows / $recordsPerPage);
 
        // range of num links to show
        $range = 4;
		
		
        // display links to 'range of pages' around 'current page'
        //$initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
		?>&nbsp;<?php
		
		//$currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 2;
		//$currPage = $_POST['currentPage'];
echo "<center><table border='0' width='100%' >";
echo "<tr>
		<td width='30%'></td>
		<td width='40%'>";
		
		echo "<center><table border='0'  class='customBtn'>";
		echo "<tr>";
		
		if($currPage = isset($_POST['currentPage']))
		{	
			$page=$_POST['currentPage'];
			$initial_num = $page - $range;
		}
		else
		{
			$initial_num = $page - $range;
		}
		
		echo "&nbsp;";
		 // ***** for 'previous' pages
        if($page>1){
            // ********** show the previous page
            $prev_page = $page - 1;
            echo "<td><a href='" . $_SERVER['PHP_SELF'] 
                    . "?page={$prev_page}' title='Previous page is {$prev_page}.'>";
                echo "<span style='margin:0 .5em;'> <  </span>";
            echo "</a></td>";
        }
		else
		{
			$prev_page = $page;
            echo "<td></td>";
		}
		echo "&nbsp;";
		for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $total_pages)) {
             
                // current page
                if ($x == $page) {
                    echo "<td>&nbsp;&nbsp;<span style='font-size:16px;color:red;font-weight:bold'>$x</span>&nbsp;&nbsp;</td>";
                } 
                 
                // not current page
                else {
                    echo " <td>&nbsp;&nbsp;<a href='{$_SERVER['PHP_SELF']}?page=$x' >$x</a> &nbsp;&nbsp;</td>";
                }
            }
        }
		
		echo "&nbsp;";
		// ***** for 'next' 
		if($page<$total_pages){
			// ********** show the next page
			$next_page = $page + 1;
			echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?page={$next_page}' title='Next page is {$next_page}.'";
			echo "<span style='margin:0 .5em;'> > </span>";
			echo "</a></td>";
		}
		else
		{
			$next_page = $page;
			echo "<td></td>";
		}
		echo "&nbsp;";
        for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
			
            if (($x > 0) && ($x <= $total_pages)) {
				// ***** allow user to enter page number
                // current page
                if ($x == $page) {
					echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
					echo "<td><input type='text' size='1' name='currentPage' value='$x' autocomplete='off' style='text-align:center'/> of $total_pages ";
					
					?>&nbsp;&nbsp;<?php	
					echo "<input type='hidden' name='submit' value='Go to'></td>";
					?><?php
					echo "</form>";
					
                } 
				
            }
        }		
		
		echo "</tr></table>";
		echo "</div>";
		
echo "
	</td>
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >&nbsp;</td></tr>
		</table>
	</td >
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >&nbsp;</td></tr>
		</table>
	</td>
	<td width='10%'>
		<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >
				<input type='button' name='back' id='back' onClick=window.location.href='../home/' value=''/></td></tr>
		</table>
	</td>
	</tr></table><br/><br/>";	
   
		// *************** </PAGING_SECTION> ***************

	}
 
// tell the user if no records were found
else{
    echo "<div style='position:absolute; padding-top:70px;' class='noneFound'>
	<table  border='0' align='center' class=''>
			<tr>&nbsp;<td >
				No records found. 
			</td></tr>
		</table>
	</div>";
}
?>
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
