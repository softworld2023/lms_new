<?php 
include('../include/page_header.php'); 

$branch_name = '';

switch ($_SESSION['login_branch']) {
	case 'MAJUSAMA':
		$branch_name = 'MJ MAJUSAMA SDN BHD';
		break;
	case 'ANSENG':
		$branch_name = 'ANSENG CREDIT SDN BHD';
		break;
	case 'YUWANG':
		$branch_name = 'YUWANG';
		break;
	case 'DK':
		$branch_name = 'DESA KOMERSIAL SDN BHD';
		break;
	case 'KTL':
		$branch_name = 'KTL SETIA REALTY SDN BHD';
		break;
	case 'TSY':
		$branch_name = 'TSY AGENCY';
		break;
	case 'TSY2':
		$branch_name = 'TSY2 AGENCY';
		break;
}

if(isset($_POST['search']))
{
	if($_POST['month'] != '')
	{
		$cond .= " and month_receipt = '".$_POST['month']."'";	
		$mth1 = date("M",strtotime($_POST['month']));

	}
	
	if($_POST['date'] != '')
	{
		$cond .= " and date = '".$_POST['date']."'";	
	}
	
	if($_POST['expenses_details'] != '')
	{
		
		$cond .= " and expenses_details LIKE '%".$_POST['expenses_details']."%'";
	}
	
	$statement = "`expenses` WHERE branch_id = '".$_SESSION['login_branchid']."' $cond AND display_status = 'SHOW' ORDER BY date ASC";
	
}

else
{
	$statement = "`expenses` WHERE branch_id = '".$_SESSION['login_branchid']."' AND month_receipt = '".date("Y-m")."' AND display_status = 'SHOW' ORDER BY date ASC";
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
    	<td width="134"><img src="../img/expenses.png" width="56"></td>
        <td width="401">Expenses</td>
        <td width="729" ></td>
        <td width="729" align="right">
		<form action="" method="post">
        	<table>
            	<tr>
					<td align="right" style="padding-right:10px">Month</td>
                    <td style="padding-right:30px"><input type="text" name="month" id="month" style="height:30px; width:100px" value="<?php if(isset($_POST['search']))
{ echo $_POST['month'];}else { echo date('Y-m');}?>"/></td>
                    <td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
                </tr>
            </table>
        </form>
	  </td>
    </tr>
	<tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
	
	
    <tr>
	
    <td colspan="3">
        <div class="subnav">
			<a href="index.php" id="active-menu">Expenses Listing</a>
			
	<?php
		if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' 
														|| $_SESSION['login_username'] == 'fong' 
														|| $_SESSION['login_username'] == 'bida'
														|| $_SESSION['login_username'] == 'waynechua'
														|| $_SESSION['login_username'] == 'ming'
														|| $_SESSION['login_username'] == 'yvonne'
														|| $_SESSION['login_username'] == 'JESS'
														|| $_SESSION['login_username'] == 'joey'
														|| $_SESSION['login_username'] == 'karen'
														|| $_SESSION['login_username'] == 'zoe'
														|| $_SESSION['login_username'] == 'may'
														|| $_SESSION['login_username'] == 'huizhen'
														|| $_SESSION['login_username'] == 'weiqi'
														|| $_SESSION['login_username'] == 'alice'
														|| $_SESSION['login_username'] == 'siangsiang'
														|| $_SESSION['login_username'] == 'wanpin')){
														
		//if($result['branch_name'] == 'HQ'){
	?>
			<a href="setting.php">Expenses Type Setting</a>
			<a href="expenses_accountant.php">Expenses Listing (Accountant)</a>
	<?php
	}
	?>		
		</div>
    </td>
		
	<?php
		/*if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' 
														|| $_SESSION['login_username'] == 'fong' 
														|| $_SESSION['login_username'] == 'bida'
														|| $_SESSION['login_username'] == 'waynechua'
														|| $_SESSION['login_username'] == 'ming'
														|| $_SESSION['login_username'] == 'yvonne'
														|| $_SESSION['login_username'] == 'JESS'
														|| $_SESSION['login_username'] == 'joey'
														|| $_SESSION['login_username'] == 'karen'
														|| $_SESSION['login_username'] == 'zoe'
														|| $_SESSION['login_username'] == 'may'
														|| $_SESSION['login_username'] == 'huizhen'
														|| $_SESSION['login_username'] == 'weiqi'
														|| $_SESSION['login_username'] == 'alice'
														|| $_SESSION['login_username'] == 'siangsiang'
														|| $_SESSION['login_username'] == 'wanpin')){*/
	?>
	
        <td width="729" align="right" style="padding-right:10px">
        	
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td><a href="add_expenses.php" title="New Expenses">Add Expenses</a></td>
                        <td>&nbsp;</td>
                        <td style="padding-right:5px"><img src="../img/enquiry/minus-button.png" style="height:17px;width: 17px;"></td>
                        <td><a href="minus_expenses.php" title="New Expenses">Add Expenses</a></td>
                	</tr>
                </table>
            </a>
        </td>
		
	<?php
	//}
	?>
	
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
        <th>Amount (RM)</th>
       <!--  <th>Package</th> -->
		<?php //let user edit
		//if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen'))
		if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || 
													$_SESSION['login_username'] == 'fong' || 
	$_SESSION['login_username'] == 'huizhen' ||				$_SESSION['login_username'] == 'bida'))
		{
		?>	
		<th width="80"></th>
		<?php } ?>
    </tr>
    <tr><th colspan="6" style="background-color:white;color: black;"><?php if($_POST['month']!=''){echo '<a href="expenses_pdf.php?date='.$_POST['month'].'" target="_blank">Expenses '.$mth1. ' ' . $branch_name . '</a>';}else{ echo '<a href="expenses_pdf.php?date='.date("Y-m").'" target="_blank">Expenses '.date("M"). ' ' . $branch_name . '</a>'; }?> </th></tr>
    <?php 
	$ctr = 0;
	$total = 0;
	$total2 = 0;
	$positive_total = 0;
	$negative_total = 0;

	while($get_q = mysql_fetch_assoc($sql)){ 

		if ($get_q['amount']<=0) {
			if ($negative_total == 0) {	// check if the amount is the first negative amount
	?>
				<tr>
					<td colspan="3" style="text-align: right;"><b>Total:</b></td>
					<td><b><?= $positive_total; ?></b></td>
				</tr>
	<?php
			}
			$style = "style='background-color:lightblue; color: red;'";
			$negative_total += $get_q['amount'];
		}
		else
		{
			$style='';
			$positive_total += $get_q['amount'];
		}

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

     <tr <?php echo $style; ?>>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date("d-m-Y",strtotime($get_q['date'])); ?></td>
        <td><?php echo $get_q['expenses_type']." ".$get_q['expenses_details']; ?></td>
        <td><?php echo number_format($get_q['amount'],2); ?></td>
       <!--  <td><?php echo $get_p['scheme']; ?></td> -->
		<?php //let user edit
		//if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong'))
if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'bida' || $_SESSION['login_username'] == 'huizhen'))
		{
		?>
		<td>
			<a href="edit_expenses.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" /></a>&nbsp;&nbsp;<a href="javascript:deleteConfirmation('<?php echo addslashes($get_q['expenses_details']); ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
		</td>
		<?php } //end of edit function ?>
    </tr>
    <?php } ?>
	<?php if($total2 != 0) { ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"><strong>RECEIVED TOTAL (RM)</strong></div></td>
      <td style="background:#CCCCCC"><strong><?php echo number_format($total2, '2'); ?></strong></td>
      <td>&nbsp;</td>
     <!--  <td>&nbsp;</td> -->
	  <?php //let user edit
		//if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen'))
if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'bida' || $_SESSION['login_username'] == 'huizhen'))
		{
		?><td>&nbsp;</td><?php } ?>
    </tr>
	<?php } 
	if($total != 0)
	{
	?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><div align="right"><strong>EXPENSES TOTAL (RM)</strong></div></td>
      <td style="background:#CCCCCC"><strong><?php echo number_format($total, '2'); ?></strong></td>
     <!--  <td>&nbsp;</td> -->

	  <?php //let user edit
		//if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen'))
if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'bida' || $_SESSION['login_username'] == 'huizhen'))
		{
		?><td>&nbsp;</td><?php } ?>
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
	
	function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this : ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_expenses',
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
</script>