<?php include('../include/page_header.php'); 
$id = $_GET['id'];

$package_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$id."'");
$get_p = mysql_fetch_assoc($package_q);

$scheme1 = $get_p['scheme'];
$scheme = mysql_real_escape_string($scheme1);

$date = date('Y-m-d');


if(isset($_POST['search']))
{
	if($_POST['date'] != '')
	{
		$date = $_POST['date'];
	}
	else
	{
		$date = date('Y-m-d');
	}
	
	$statement = "`loan_payment_details` pd, `customer_loanpackage` lp WHERE lp.loan_package = '".$scheme."' AND pd.customer_loanid = lp.id AND pd.payment_date = '".$date."'";
	$_SESSION['collections'] = $statement;
}
else
if($_SESSION['collections'] != '')
{
	$statement = $_SESSION['collections'];
}
else
{
	$statement = "`loan_payment_details` pd, `customer_loanpackage` lp WHERE lp.loan_package = '".$scheme."' AND pd.customer_loanid = lp.id AND pd.payment_date = '".$date."'";
}


$sql = mysql_query("SELECT * FROM {$statement}");
//$sql = mysql_query("SELECT * FROM loan_payment_details pd, customer_loanpackage lp WHERE lp.loan_package = '".$scheme."' AND pd.customer_loanid = lp.id AND pd.payment_date = '".$date."'");
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
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Reports: <strong><?php echo $get_p['scheme']; ?> Daily Collection</strong></td>
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
        </form>		</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Borrow Out</a><a href="payout.php">Actual Payout</a><a href="collection.php">Total Collection</a><a href="profit.php">Profit & Loss</a><a href="expenses.php">Expenses</a><a href="interest.php">Interest Earn</a><a href="latepayment.php">Late Payment Collections</a><a href="daily.php" id="active-menu">Daily Collections</a><a href="dailyloan.php">Daily Loan</a>		</div>        </td>
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
        <th>Date</th>
        <th>Customer's Name</th>
        <th>Loan Code </th>
        <th>Amount</th>
    </tr>
    <?php 
	$ctr = 0;
	$total;
	while($get_q = mysql_fetch_assoc($sql))
	{
		$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
		$get_c = mysql_fetch_assoc($cust_q);
		
		$payment = number_format(($get_q['payment'] + $get_q['payment_int']), '2', '.', '');
		$total += $payment;
		$ctr++;
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_q['payment_date']; ?></td>
        <td><?php echo $get_c['name']; ?></td>
        <td><?php echo $get_q['receipt_no']; ?></td>
        <td><?php echo "RM ".$payment; ?></td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4" align="right"><strong>Total</strong></td>
        <td style="background:#CCCCCC"><strong><?php echo "RM ".number_format($total, '2', '.', ''); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../report/daily.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
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
</body>
</html>