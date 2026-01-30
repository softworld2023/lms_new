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
	
	if($_POST['branch'] != '')
	{
		$cond .= " and branch_id = '".$_POST['branch']."'";
	}
	
	if($_POST['customer_name'] != '' || $_POST['nric'] != '' || $_POST['branch'] != '')
	{
		$statement = "`customer_details` WHERE branch_id != '".$_SESSION['login_branchid']."' $cond ORDER BY name ASC";
		$_SESSION['query'] = $statement;
		$sql = mysql_query("SELECT * FROM {$statement}");
	}
}

if($_SESSION['query'] != '')
{
	$statement = $_SESSION['query'];
	$sql = mysql_query("SELECT * FROM {$statement}");
}

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
        <td>Other Branch Customers</td>
        <td align="right" colspan="2">
		<form action="" method="post">
        	<table>
            	<tr>
            	  <td align="right" style="padding-right:10px">I/C No. </td>
                	<td align="right" style="padding-right:10px"><input type="text" name="nric" id="nric" style="height:30px" /></td>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:10px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
					<td align="right" style="padding-right:10px">Branch </td>
					<td align="right" style="padding-right:10px">
						<select name="branch" id="branch" style="height:35px">
							<option value=""></option>
						<?php 
							$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13' AND branch_id != '".$_SESSION['login_branchid']."'");
							while($branch = mysql_fetch_assoc($branch_q))
							{
						?>
							<option value="<?php echo $branch['branch_id']; ?>"><?php echo $branch['branch_name']; ?></option>
						<?php } ?>
						</select>
					</td>
					<td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
                </tr>
            </table>
        </form>
		</td>
    </tr>
	<tr>
    	<td colspan="4">
        <div class="subnav">
			<a href="index.php">Own Branch Customer Listing</a><a href="other_listing.php" id="active-menu">Other Branch Listing</a>
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
        <th width="192">Name</th>
        <th width="124">I.C Number</th>
        <th width="223">Branch</th>
    </tr>
	
    <?php 
	if($_SESSION['query'] != '')
	{
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
        <td><?php echo $get_q['name']; ?></td>
        <td><?php echo $get_q['nric']; ?></td>
        <td>
			<?php 
			if($get_q['branch_id'] != 'OTH')
			{
				$cust_branch_q = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_q['branch_id']."'"); 
				$cust_branch = mysql_fetch_assoc($cust_branch_q);
				echo $cust_branch['branch_name'];
			}else
			{
				echo $get_q['branch_name'];
			}
			?>
		</td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
<?php } ?>
</table>
</center>
<script>
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName_other.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	$("#customer_code").autocomplete("auto_custCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
	
	$("#nric").autocomplete("auto_nric_other.php", {
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
