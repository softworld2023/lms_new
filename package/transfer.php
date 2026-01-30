<?php include('../include/page_header.php'); ?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
<style type="text/css">
<!--
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

-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/package/package.png"></td>
        <td>Package (Money Transfer) </td>
        <td align="right">
        	
        </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php">Package Listing</a><a href="transfer.php" id="active-menu">Money Transfer</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">
        	<a href="add_newtransfer.php" title="Transfer Money">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>New Transfer </td>
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
<table width="1280" id="list_table">
	<tr>
    	<th width="50">No.</th>
        <th>Date</th>
        <th>Description</th>
        <th>From</th>
        <th>To</th>
        <th width="100">Amount</th>
		<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<th width="100">&nbsp;</th>
        <?php } ?>
    </tr>
    <?php 
	$ctr = 0;
	$sql = mysql_query("SELECT * FROM transfer_trans WHERE branch_id = '".$_SESSION['login_branchid']."' ORDER BY date DESC");
    while($get_q = mysql_fetch_assoc($sql)){
	$ctr++;
	
	//from package
	if($get_q['from_package'] != 0)
	{
		$from_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['from_package']."'");
		$get_f = mysql_fetch_assoc($from_q);
		$fpack = $get_f['scheme'];
	}else
	{
		$fpack = 'BOSS';
	}	
	
	//to package
	if($get_q['to_package'] != 0)
	{
		$to_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$get_q['to_package']."'");
		$get_t = mysql_fetch_assoc($to_q);
		$tpack = $get_t['scheme'];
	}else
	{
		$tpack = 'BOSS';
	}
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date('d/m/Y', strtotime($get_q['date'])); ?></td>
        <td><?php echo $get_q['transfer_details']; ?></td>
        <td><?php echo $fpack; ?></td>
        <td><?php echo $tpack; ?></td>
        <td><?php echo "RM ".$get_q['amount']; ?></td>
		<?php if($_SESSION['login_username'] != 'softworld' && $_SESSION['login_username'] != 'fong' && $_SESSION['login_username'] != 'huizhen' && $_SESSION['login_username'] != 'staff') {
		}else{
		?>
		<td><center><a href="edit_transfer.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" /></a>&nbsp;&nbsp;<a href="javascript:deleteConfirmation('<?php echo $get_q['transfer_details']; ?>', '<?php echo $get_q['id']; ?>', '<?php echo $get_q['date']; ?>', '<?php echo $get_q['amount']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete" /></a>
		</center></td>
        <?php } ?>
    </tr>
    <?php } ?>
    <tr>
		<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<td colspan="7">&nbsp;</td>
		<?php } else { ?>
    	<?php } ?>
    </tr>
    <tr>
		<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
		<?php } else { ?>
    	<?php } ?>
    </tr>
    <tr>
    	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' && $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<td colspan="7">&nbsp;</td>
		<?php } else { ?>
    	<?php } ?>
    </tr>
</table>
</center>
<script>
function deleteConfirmation(transfer_details, id, date, amount){
	$id = id;
    $date = date;
	$amount = amount;
	
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this transfer: ' + transfer_details + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_transfer',
							id: $id,
							date: $date,
							amount: $amount,
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
</body>
</html>