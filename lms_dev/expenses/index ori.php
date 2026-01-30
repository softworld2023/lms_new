<?php 
include('../include/page_header.php'); 


if(isset($_POST['search']))
{
	if($_POST['date'] != '')
	{
		$cond .= " and date = '".$_POST['date']."'";	
	}
	
	$statement = "`expenses` WHERE branch_id = '".$_SESSION['login_branchid']."' $cond ORDER BY date DESC";
	$_SESSION['exp_s'] = $statement;
}
else
if($_SESSION['exp_s'] != '')
{
	$statement = $_SESSION['exp_s'];
}
else
{
	$statement = "`expenses` WHERE branch_id = '".$_SESSION['login_branchid']."' ORDER BY date DESC";
}


$sql = mysql_query("SELECT * FROM {$statement}");

//$sql = mysql_query("SELECT * FROM expenses ORDER BY date DESC");
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
#approve_loan
{
	background:url(../img/approval/approve-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#approve_loan:hover
{
	background:url(../img/approval/approve-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
}
#reject_loan
{
	background:url(../img/approval/reject-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#reject_loan:hover
{
	background:url(../img/approval/reject-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
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
    	<td width="65"><img src="../img/expenses.png" width="56"></td>
        <td>Expenses</td>
        <td align="right">
		<form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Date</td>
                    <td style="padding-right:30px"><input type="text" name="date" id="date" style="height:30px" /></td>
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
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" id="active-menu">Expenses Listing</a><a href="setting.php">Expenses Type Setting</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">
        	<a href="add_expenses.php" title="New Expenses">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Add Expenses</td>
                	</tr>
                </table>
            </a>
        </td>
    </tr>
</table>
<div id="message" style="width:1280px; text-align:left">
	<?php
    if($_SESSION['msg'] != '')
    {
        echo $_SESSION['msg'];
        $_SESSION['msg'] = '';
    }
    ?>						
</div>
<br />
<table width="1280" id="list_table">
	<tr>
    	<th width="80">No.</th>
        <th>Date</th>
        <th>Details</th>
        <th>Amount</th>
        <th>Package</th>
        <th>Transaction Type </th>
    </tr>
    <?php 
	$ctr = 0;
	$total = 0;
	$total2 = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['package_id']."'");
	$get_p = mysql_fetch_assoc($package_q);
	if($get_q['ttype'] == 'EXPENSES')
	{
		$total += $get_q['amount'];
	}
	if($get_q['ttype'] == 'RECEIVED')
	{
		$total2 += $get_q['amount'];
	}
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_q['date']; ?></td>
        <td><?php echo $get_q['expenses_details']; ?></td>
        <td><?php echo "RM ".$get_q['amount']; ?></td>
        <td><?php echo $get_p['scheme']; ?></td>
        <td><?php echo ucwords($get_q['ttype']); ?></td>
    </tr>
    <?php } ?>
	<?php if($total2 != 0) { ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"><strong>RECEIVED TOTAL </strong></div></td>
      <td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total2, '2'); ?></strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<?php } 
	if($total != 0)
	{
	?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"><strong>EXPENSES TOTAL </strong></div></td>
      <td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total, '2'); ?></strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<?php } ?>
    <tr>
    	<td colspan="6">&nbsp;</td>
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
function deleteConfirmation(date, name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this expenses: ' + name + ' (' + date + ')?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_exp',
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

$('#date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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