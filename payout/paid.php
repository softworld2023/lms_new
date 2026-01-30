<?php 
include('../include/page_header.php'); 

$sql = mysql_query("SELECT * FROM customer_details cd, customer_loanpackage lp WHERE lp.branch_id = '".$_SESSION['login_branchid']."' AND (lp.loan_status = 'Paid' OR lp.loan_status = 'Finished' OR lp.loan_status = 'Deleted') AND lp.customer_id = cd.id ORDER BY cd.name ASC");
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
</style>
<center>
<form action="action.php" method="post" onsubmit="return checkForm()">
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payout/payout.png"></td>
        <td>Payout</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Approved</a><a href="paid.php" id="active-menu">Paid</a>
		</div>	
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
    	<th>No.</th>
        <th>Customer Name</th>
        <th>I.C Number</th>
        <th>Agreement No</th>
        <th>Apply Date</th>
        <th>Approved Date</th>
        <th>Payout Date</th>
        <th>Amount</th>
		<?php
		if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'))
		{
		?>
		<th>Cancel Payout</th>
		<?php } ?>
      </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_c = mysql_fetch_assoc($cust_q);
	
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_c['name']; ?></td>
        <td><?php echo $get_c['nric']; ?></td>
        <td><?php echo $get_q['loan_code']; ?></td>
        <td><?php echo date("d-m-Y",strtotime($get_q['apply_date'])); ?></td>
        <td><?php echo date("d-m-Y",strtotime($get_q['approval_date'])); ?></td>
        <td><?php echo date("d-m-Y",strtotime($get_q['payout_date'])); ?></td>
        <td><?php echo "RM ".$get_q['loan_amount']; ?></td>
		<?php
		if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'))
		{
		?>
		<td>
		<center>
			<?php if($get_q['payout_date'] != '0000-00-00') { ?>
			<?php //let user edit
			if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'))
			{
			?>
			<a href="editdateissue.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox; width=400px; height=400px"><img src="../img/customers/edit-icon.png" /></a>
			<a href="javascript:reversedP('<?php echo $get_q['id']; ?>', '<?php echo $get_q['loan_code']; ?>')" title="Reversed Payout"><img src="../img/delete-btn.png" /></a>
			<?php } ?>
			<?php } ?>
		</center>
		</td>
		<?php } ?>
      </tr>
    <?php } ?>
    <tr>
    	<td colspan="9">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="9" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="9">&nbsp;</td>
    </tr>
</table>
<input type="hidden" name="check_app" id="check_app" value="0" /><input type="hidden" name="ctr" id="ctr" value="<?php echo $ctr; ?>" />
</form>
</center>
<script>
function check()
{
	$counter = $("#ctr").val();
	$total_amount = 0;
	
	for($i = 1; $i <= $counter; $i++)
	{
		if($('#approved_'+$i).is(':checked'))
		{
			$no = 1;
			$total_amount+= $no;
		}else
		{
			$total_amount;
		}
	}
	document.getElementById('check_app').value = $total_amount;
}

function checkForm()
{
	if((document.getElementById('check_app').value != '0'))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		$('html, body').animate({scrollTop:150}, 'fast');
		var msg = "<div class='error'>You must select atleast ONE (1) loan from the list!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}

function reversedP(id, lcode)
{
	$id = id;
	$lcode = lcode;
	$.confirm({
		'title'		: 'Reversed Payout Confirmation',
		'message'	:  'Are You sure want to reversed the payout for this loan: '+$lcode+'?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'reversed_loan',
							id: $id,
						},
						url: 'action_reversed.php',
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