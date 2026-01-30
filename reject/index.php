<?php 
session_start();
include('../include/page_headercb.php');

$login_branch = $_SESSION['login_branch']; 

if(isset($_POST['search']))   // click search
{

	$cond = '';

	// if($_POST['branch_name'] != '')
	// {
		
	// 	$code_sql = mysql_query("SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA') AND status = 'ACTIVE'");
	// 	$code = mysql_fetch_assoc($code_sql);
	// 	$cond .= "where branch_name = '".$code['branch_name']."'";	

	// }
	
	if($_POST['customer_name'] != '')
	{
		$customer_name = trim($_POST['customer_name']);

		// $customer_sql = mysql_query("SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA') AND customer_name = '".$_POST['customer_name']."' AND status = 'ACTIVE'");
		// $cust = mysql_fetch_assoc($customer_sql);
		// $cond .= "where customer_name = '".$cust['customer_name']."'";
		$cond .= " AND customer_name = '$customer_name'";
	}
	
	if($_POST['customer_ic'] != '')
	{
		$customer_ic = trim($_POST['customer_ic']);

		// $code_sql = mysql_query("SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA') AND customer_ic = '".$_POST['customer_ic']."' AND status = 'ACTIVE'");
		// $code = mysql_fetch_assoc($code_sql);
		// $cond .= "where customer_ic = '".$code['customer_ic']."'";	
		$cond .= " AND customer_ic = '$customer_ic'";
	}
	
	// $statement = "SELECT * FROM loansystem.reject_loan ".$cond." ORDER BY reject_date ASC";

	if ($login_branch == "ANSENG" || $login_branch == "MAJUSAMA" || $login_branch == "MAJUSAMA2" || $login_branch == "YUWANG" || $login_branch == "DK") {

		//If $login_branch is "ANSENG" or "MAJUSAMA" or "YUWANG", set $statement for ANSENG, MAJUSAMA, YUWANG, DK branches
		$statement = "SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA' OR branch_name = 'MAJUSAMA2' OR branch_name = 'YUWANG' OR branch_name = 'DK')" . $cond . " AND status = 'ACTIVE' ORDER BY reject_date ASC";
	} elseif ($login_branch == "KTL" || $login_branch == "TSY"|| $login_branch == "TSY2") {
		$statement = "SELECT * FROM loansystem.reject_loan WHERE 1=1 " . $cond . " AND status = 'ACTIVE' ORDER BY reject_date ASC";
	}		
}
else    // not click search and also no history result
{
		// $statement = "SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA') AND status = 'ACTIVE' ORDER BY reject_date ASC";

		// Check the value of $login_branch
		if ($login_branch == "ANSENG" || $login_branch == "MAJUSAMA" || $login_branch == "MAJUSAMA2" || $login_branch == "YUWANG" || $login_branch == "DK") {

			//If $login_branch is "ANSENG" or "MAJUSAMA" or "YUWANG", set $statement for ANSENG, MAJUSAMA, YUWANG branches
			$statement = "SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA' OR branch_name = 'MAJUSAMA2' OR branch_name = 'YUWANG' OR branch_name = 'DK') AND status = 'ACTIVE' ORDER BY reject_date ASC";
		} elseif ($login_branch == "KTL" || $login_branch == "TSY" || $login_branch == "TSY2") {
			$statement = "SELECT * FROM loansystem.reject_loan WHERE status = 'ACTIVE' ORDER BY reject_date ASC";
		}
}


$sql_q = $statement;
// var_dump($sql_q);
$bad_debt_q = mysql_query($sql_q);

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
#tr_list:hover {background-color: lightgrey;}

</style>
<center>
<table width="1280">
	<tr>
    	<td width="134"><img src="../img/cash-register-icon.png" width="56"></td>
        <td width="401">Reject List (All Branch)</td>
        <td align="right">
       	<form action="index.php" method="post">
        	<table>
				<!-- <tr> <td align="right" style="padding-right:10px">Branch</td>
						<td>
							<input id="branch_name" name="branch_name" list="branch" style="height:30px;" >
							<datalist id="branch">
							<option value="KTL">
							<option value="ANSENG">
							<option value="MAJUSAMA">
						</datalist></td> -->
					<td align="right" style="padding-right:10px">Customer Name</td>
					<td>
						<?php
							$sql="SELECT * FROM loansystem.reject_loan WHERE branch_name = '$login_branch' AND status = 'ACTIVE' "; 
							$result=mysql_query($sql);

							echo '<input id="customer_name" name="customer_name" list="names" style="height:30px;" ><datalist id="names">';
							while($rows=mysql_fetch_assoc($result)){
						?>
							<option value="<?php echo $rows["customer_name"]; ?>">
						<?php
							}
						?>
						</datalist>
					</td>
                    <td align="right" style="padding-right:10px">Customer IC</td>
                    <td>
						<?php
							$sql="SELECT * FROM loansystem.reject_loan WHERE branch_name = '$login_branch' AND status = 'ACTIVE' ";
							$result=mysql_query($sql);
							
							echo '<input id="customer_ic" name="customer_ic" list="nrics" style="height:30px;" ><datalist id="nrics">';
							while($rows=mysql_fetch_assoc($result)){
						?>
							<option value="<?php echo $rows["customer_ic"]; ?>">
						<?php
							}
						?>
						</datalist>
					</td>

                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value=""/>
					</td>

                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr><td colspan="7" style="text-align:right;"><input type="submit" id="search_1" name="search" value="Show all list"/>
            </tr>

            </table>
        </form>
        </td>
	  
    	</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	
	</table>
	<table>
		<tr>
			<td width="1400" align="right" style="padding-right:10px">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td><a href="reject.php" title="Reject" rel="shadowbox; width=600px; height=550px">Add Reject</a></td>
                	</tr>
                </table>
            </a>
        	</td>
		</tr>
	</table>
        	
	
<table width="1500" id="list_table" >

	<tr>
		<th width="30" style="border:1px solid black;">No.</th>
		<th width="100" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
		<th width="150" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Name</th>
		<th width="110" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer IC</th>
		<th width="130" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Company</th>
		<th width="350" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Reject Reason</th>
		<th width="80" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Date</th>
		<th width="80" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Contact Number</th>
		<th width="80" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer From</th>
		<th width="100" style="background-color: white;"></th>
	</tr>
	<?php 
		$ctr = 0;

		$numberofrow = mysql_num_rows($bad_debt_q);
		if($numberofrow >0){
		while($branch = mysql_fetch_assoc($bad_debt_q))
		{

		$ctr++;
		
	?>
    <tr id="tr_list">
    	<td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;"><?php echo $ctr."."; ?></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['branch_name']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['customer_name']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['customer_ic']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['customer_company']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['reject_reason']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php if($branch['reject_date']!=''){echo date('d/m/Y',strtotime($branch['reject_date']));}else{echo '';} ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['customer_contact_number']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['customer_from']; ?></b></td>
        <td>
        	<?php
        		if ($branch['document']!='') {
        	?>
        			<a target="_blank" href="document/<?php echo strtolower($branch['branch_name']); ?>/<?php echo $branch['id']; ?>/<?php echo $branch['document']; ?>">
						<img src="../reject/view_doc.png" alt="" style="height:25px;" title="View Reject Document" /></a>
        	<?php
        		}
        	?>
        	<a href="edit_reject.php?id=<?php echo $branch['id']; ?>" title="Edit" rel="shadowbox; width=600px; height=550px"><img src="../img/customers/edit-icon.png" /></a>
			<?php
        		if ($branch['customer_id']!='') {
        	?>
			<a hidden href="view_customer_loan.php?id=<?php echo $branch['customer_id']; ?>"><img src="../../img/customers/search.png" title="View" style=""></a>
        	<?php
        		}
        	?>
        	<a href="javascript:deleteConfirmation('<?php echo $branch['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
			
        </td>
    </tr>

		<?php } }else{

	?> 
	<tr id="tr_list">
			<td colspan="9" style="text-align:center ;border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">- No Records - </td>
		<?php }?>
	</tr>
    <tr>
		<td colspan="10" height="50"></td>
	</tr>

</table>
</center>

<script type="text/javascript">
	Shadowbox.init();
</script>
<script>
function deleteConfirmation(id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_reject',
							id: $id,
						},
						url: 'reject_delete_action.php',
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