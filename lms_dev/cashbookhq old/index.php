<?php 
include('../include/page_header.php'); 

$sql = mysql_query("SELECT * FROM hq_cashbook GROUP BY CONCAT(YEAR(date), '-', MONTH(date)) ORDER BY date ASC");
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
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/cash-book/cash-book.png" style="height:47px"></td>
        <td>HQ Cashbook</td>
        <td align="right">
        	<a href="add_trans.php" title="New Transaction">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Add Transaction </td>
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
        <th>Month</th>
        <th>Out</th>
        <th>In</th>
        <th>Total</th>
    </tr>
    <?php 
	$ctr = 0;
	$in = '0.00';
	$out = '0.00';
	$tot = '0.00';
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$date = date('Y-m', strtotime($get_q['date']));
	
	
	$sum_q = mysql_query("SELECT SUM(amount) as sumIn FROM hq_cashbook WHERE date LIKE '%".$date."%' AND type = 'in'");
	$get_sum = mysql_fetch_assoc($sum_q);
	if($get_sum['sumIn'] != '')
	{
		$in = $get_sum['sumIn'];
		$tot += $in;
	}
	
	$sum_q2 = mysql_query("SELECT SUM(amount) as sumOut FROM hq_cashbook WHERE date LIKE '%".$date."%' AND type = 'out'");
	$get_sum2 = mysql_fetch_assoc($sum_q2);
	if($get_sum2['sumOut'] != '')
	{
		$out = $get_sum2['sumOut'];
		$tot -= $out;
	}
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><a href="details.php?m=<?php echo $date; ?>"><?php echo date('M Y', strtotime($date)); ?></a></td>
        <td><?php echo "RM ".$out; ?></td>
        <td><?php echo "RM ".$in; ?></td>
        <td><?php echo "RM ".$tot; ?></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
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
</script>