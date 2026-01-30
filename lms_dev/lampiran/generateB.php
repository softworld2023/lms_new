<?php 
session_start();
include("../include/dbconnection.php");
include("../include/dbconnection2.php");

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
   
<table width="1280" id="list_table" align="center">
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
	$ctr=0;
	
	$exp = mysql_query("SELECT * FROM customer_details WHERE branch_id = '".$_GET['branch_id']."'", $db1);
	while($exp_d = mysql_fetch_assoc($exp)){
	
	$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id = '".$exp_d['branch_id']."'", $db1);
	$branch = mysql_fetch_assoc($branch_q);
	
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$exp_d['id']."'", $db1);
	$loan = mysql_fetch_assoc($loan_q);
	
	$checkic = mysql_query("SELECT * FROM customer_details WHERE nric = '".$exp_d['nric']."'", $db2);
	$chkic = mysql_fetch_assoc($checkic);
	
	
	
	$ctr++;
	?> 
		<tr align="center">
			<td><?php echo $ctr; ?></td>
			<td></td>
			<td><?php echo $exp_d['name']; ?></td>
			<td><?php echo $exp_d['nric']; ?></td>
			<td><?php echo $branch['branch_name']; ?></td>
			<td><?php echo $loan['loan_code']; ?></td>
			<td><?php echo $loan['loan_total']; ?></td>
			<td><a href="letter/print_lampiranB.php?id=<?php echo $chkic['id']; ?>">
					<img src="../img/view_source.png"/></a>&nbsp;
				<a href="view_details.php?id=<?php echo $chkic['id']; ?>">
					<img src="../img/view.png"/>&nbsp;
				<a href="letter/editB.php?cus_id=<?php echo $exp_d['id']; ?>">
					<img src="../img/enquiry/edit-btn.png"/></a>&nbsp;
				<a href="action2.php?id=<?php echo $chkic['id']; ?>" onclick="return confirm('Are you sure to delete?')">
					<img src="../img/delete-btn.png" title="Delete Record" id="del" ></a>
		    </td>
    </tr>
   <?php 
   }
   ?>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../'"></td>
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

$(function() {
  	$('.nm3').nyroModal({});
});

$("#select_all").click(function() {
	$counter = $("#ctr").val();
	
	if($('#select_all').is(':checked'))
	{
		for($i = 1; $i <= $counter; $i++)
		{
			$("#id_" + $i).attr('checked', true);
		}
		$("#selected_item").html($counter + " selected" + '&nbsp;&nbsp;<input type="submit" name="sub-del" id="sub-del" class="submit_style" value="sub-del" title="Delete All" style="color:#eee;">');
	}else
	{
		for($i = 1; $i <= $counter; $i++)
		{
			$("#id_" + $i).attr('checked', false);	
		}
		$('#selected_item').text('');
	}
});

function check(exp)
{
	$counter = $("#ctr").val();
	$ctr = 0;
		
	for($i = 1; $i <= $counter; $i--)
	{
		if($('#exp_d'+$i).is(':checked'))
		{
			$ctr++;
		}
	}
	
	if($ctr > 0)
	{
		$("#selected_item").html($ctr + " selected" + '&nbsp;&nbsp;<input type="submit" name="sub-del" id="sub-del" class="submit_style" value="sub-del" title="Delete All" style="color:#eee;">');
	}else
	{
		$("#selected_item").text("");	
	}
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
