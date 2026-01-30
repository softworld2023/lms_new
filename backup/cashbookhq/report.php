<?php 
include('../include/page_headercb.php'); 

if(isset($_POST['search']))
{
	if($_POST['date'] != '')
	{
		$_SESSION['date'] = $_POST['date'];
	}
	else
	{
		$_SESSION['date'] = date('Y-m-d');
	}
	
	$date = $_SESSION['date'];
}else if($_SESSION['date'] != '')
{
	$date = $_SESSION['date'];
}
else
{
	$date = date('Y-m-d');
}
$month = date('Y-m', strtotime($date));

$sql = mysql_query("SELECT * FROM hq_cashbook WHERE date = '".$date."'");
$cf = 0;

$odate = $date;
$cf_q = mysql_query("SELECT SUM(amount) AS sumIn FROM hq_cashbook WHERE date < '".$odate."' AND type = 'in'");
$get_cf = mysql_fetch_assoc($cf_q);

$cf_q1 = mysql_query("SELECT SUM(amount) AS sumOut FROM hq_cashbook WHERE date < '".$odate."' AND type = 'out'");
$get_cf1 = mysql_fetch_assoc($cf_q1);

$cf = $cf + $get_cf['sumIn'] - $get_cf1['sumOut'];
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
#print_btn
{
	background:url(../img/print-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#print_btn:hover
{
	background:url(../img/print-btn-roll.jpg);
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
@media print {	
	.subnav { display:none; }
	#hideprint { display:none; }
	#back { display:none; }
	#message { display:none; }
	#list_table
	{
		width:1000px;
	}
}
</style>
<center>
<table width="1280" id="hideprint">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>HQ Cashbook Daily Report</td>
        <td align="right">
        	<form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Date</td>
                    <td style="padding-right:30px"><input type="text" name="date" id="date" style="height:30px" value="<?php echo $date; ?>" /></td>
					<td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
					<td><button id="print_btn" onClick="window.print()"></button></td>
				</tr>
            </table>
        </form>
        </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="report.php" id="active-menu">HQ Cashbook Daily</a><a href="loanstatus.php">Loan Status</a><a href="settle.php">Customer Settle Report</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
    </tr>
</table>
<br />
<table width="1280" id="list_table">
	<tr>
    	<th width="80">No.</th>
        <th>Date</th>
        <th>Description</th>
        <th>Out</th>
        <th>In</th>
        <th width="100">Total</th>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>CF</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>RM <?php echo number_format($cf, '2'); ?></td>
    </tr>
    <?php 
	$ctr = 0;
	$tot = $cf;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	if($get_q['type'] == 'out')
	{ 
		$tot -= $get_q['amount']; 
	}
	else
	{
		$tot += $get_q['amount']; 
	}
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date('d-m-Y', strtotime($get_q['date'])); ?></td>
        <td><?php echo $get_q['description']; ?></td>
        <td><?php if($get_q['type'] == 'out') { echo "RM ".$get_q['amount']; } ?></td>
        <td><?php if($get_q['type'] == 'in') { echo "RM ".$get_q['amount']; } ?></td>
        <td><?php echo "RM ".number_format($tot, '2'); ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td style="border-bottom:thin solid #000; border-top:thin solid #000"><strong><?php echo "RM ".number_format($tot, '2'); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../cashbookhq/'" value=""></td>
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
		'message'	: 'Are you sure want to delete this transaction: ' + name + ' (' + date + ')?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_trans',
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