<?php include('../include/page_header.php');

$id = $_GET['id'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$id."'");
$get_p = mysql_fetch_assoc($package_q);

$sql = mysql_query("SELECT * FROM expenses e, loan_payment_details pd WHERE e.branch_id = '".$_SESSION['login_branchid']."' AND pd.payment_date != '0000-00-00' AND pd.package_id = '".$id."' GROUP BY CONCAT(YEAR(pd.payment_date), '-', MONTH(pd.payment_date))");
?>
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
#update
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#update:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
}
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Reports: <strong><?php echo $get_p['scheme']; ?> Profit (Summary)</strong></td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
		
			<a href="index.php">Borrow Out</a>
			<a href="payout.php">Actual Payout</a>
			<a href="collection.php">Total Collection</a>
			<a href="profit.php" id="active-menu">Profit & Loss</a>
			<a href="expenses.php">Expenses</a>
			<a href="interest.php">Interest Earn</a>
			<a href="latepayment.php">Late Payment Collections</a>
			<?php
			if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' 
														|| $_SESSION['login_username'] == 'fong' 
														|| $_SESSION['login_username'] == 'staff'
														|| $_SESSION['login_username'] == 'staff'
														|| $_SESSION['login_username'] == 'waynechua'
														|| $_SESSION['login_username'] == 'ming'
														|| $_SESSION['login_username'] == 'fong'
														|| $_SESSION['login_username'] == 'wanpin'))
			{
		?>
			<a href="daily.php">Daily Collections</a>
			<a href="statement.php">Statement</a>
		<?php
			}
		?>
		
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
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
    	<th width="50">No.</th>
        <th>Month</th>
        <th width="150">Collection</th>
        <th width="150">Expenses</th>
        <th width="150">Profit</th>
    </tr>
    <?php 
	$ctr = 0;
    while($get_q = mysql_fetch_assoc($sql)){
		
		$month = date('Y-m-', strtotime($get_q['payment_date']));
		
		$sume_q = mysql_query("SELECT SUM(amount) as expamt FROM expenses WHERE branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$id."' AND date LIKE '%".$month."%'");
		$get_esum = mysql_fetch_assoc($sume_q);
			
		$package1 = $get_p['scheme'];
		$package = mysql_real_escape_string($package1);
				
		$coll_q = mysql_query("SELECT SUM(payment) AS payment, SUM(payment_int) AS payment_int FROM loan_payment_details WHERE package_id = '".$id."' AND payment_date LIKE '%".$month."%'");
		$get_coll = mysql_fetch_assoc($coll_q);
		
		$collection = number_format(($get_coll['payment'] + $get_coll['payment_int']), '2', '.', '');
		$ctr++;
		$profit = number_format($collection - $get_esum['expamt'], '2', '.', '');
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date('M Y', strtotime($get_q['payment_date'])); ?></a></td>
        <td><?php echo $collection; ?></td>
        <td><?php echo $get_esum['expamt']; ?></td>
        <td><?php echo $profit; ?></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../report/profit.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
<script>
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this user: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_staff',
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

function showEdit(no)
{
	if(document.getElementById('edit_' + no).style.visibility == 'hidden')
	{
		document.getElementById('edit_' + no).style.visibility = 'visible';	
	}else
	if(document.getElementById('edit_' + no).style.visibility == 'visible')
	{
		document.getElementById('edit_' + no).style.visibility = 'hidden';
	}
}

function updateAmount(no, id)
{
	$amount = $('#amount_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
}

// mini jQuery plugin that formats to two decimal places
(function($) {
	$.fn.currencyFormat = function() {
    	this.each( function( i ) {
        $(this).change( function( e ){
        	if( isNaN( parseFloat( this.value ) ) ) return;
        	this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
    }
})( jQuery );

// apply the currencyFormat behaviour to elements with 'currency' as their class
$( function() {
    $('.currency').currencyFormat();
});
</script>
</body>
</html>