<?php include('../include/page_header.php'); 
$id = $_GET['id'];

$transq = mysql_query("SELECT * FROM transfer_trans WHERE id = '".$id."'");
$trans = mysql_fetch_assoc($transq);

?>
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
input
{
	height:30px;
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
	height:35px;
	text-align:right;
	padding-left:20px;
	padding-right:10px;
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
#reset
{
	background:url(../img/add-enquiry/clear-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#reset:hover
{
	background:url(../img/add-enquiry/clear-btn-roll-over.jpg);
}
#edit_transfer
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_transfer:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<form method="post" action="action.php" onSubmit="return checkForm()">
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/package/package.png" style="height:47px"></td>
        <td>Add New Money Transfer </td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="2">
		<div class="subnav">
			<a href="index.php">Package Listing</a><a href="transfer.php" id="active-menu">Money Transfer</a>
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

<table width="1280" id="list_table">
	<tr>
    	<td colspan="2"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" /></td>
    </tr>
	<tr>
    	<th width="15%">Description</th>
        <td><input type="text" name="transfer_details" id="transfer_details" style="width:350px" value="<?php echo $trans['transfer_details']; ?>" /></td>
    </tr>
    <tr>
    	<th>Date</th>
        <td><input type="text" name="transfer_date" id="transfer_date" value="<?php echo date('d-m-Y', strtotime($trans['date'])); ?>" /></td>
    </tr>
    <tr>
        <th>Transfer From </th>
        <td>
			<select name="from_package" id="from_package" style="height:30px">
				<option value="">-Select-</option>
				<option value="0" <?php if($trans['from_package'] == 0) { echo 'selected'; } ?>>BOSS</option>
				<?php 
				$pack_q = mysql_query("SELECT * FROM loan_scheme ORDER BY scheme ASC");
				while($get_p = mysql_fetch_assoc($pack_q))
				{
				?>
				<option value="<?php echo $get_p['id'] ?>" <?php if($trans['from_package'] == $get_p['id']) { echo 'selected'; } ?>><?php echo $get_p['scheme']; ?></option>
				<?php
				} 
				?>
			</select>
		</td>
    </tr>
    <tr>
        <th>Transfer To </th>
        <td>
			<select name="to_package" id="to_package" style="height:30px">
				<option value="">-Select-</option>
				<option value="0" <?php if($trans['to_package'] == 0) { echo 'selected'; } ?>>BOSS</option>
				<?php 
				$pack_q2 = mysql_query("SELECT * FROM loan_scheme ORDER BY scheme ASC");
				while($get_p2 = mysql_fetch_assoc($pack_q2))
				{
				?>
				<option value="<?php echo $get_p2['id'] ?>" <?php if($trans['to_package'] == $get_p2['id']) { echo 'selected'; } ?>><?php echo $get_p2['scheme']; ?></option>
				<?php
				} 
				?>
			</select>
		</td>
	</tr>
    <tr>
    	<th>Amount</th>
        <td><input type="text" name="transfer_amount" id="transfer_amount" class="currency" value="<?php echo $trans['amount']; ?>" /></td>
	</tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="edit_transfer" id="edit_transfer" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../package/transfer.php'" value="">        </td>
    </tr>
    <tr>
    	<td height="16" colspan="2">&nbsp;</td> 
    </tr>
</table>
</form>
</center>
<script>

$("#password").passStrength({
	userid:	"#username"
});

function checkForm()
{
	if((document.getElementById('transfer_details').value != '' && document.getElementById('transfer_date').value != '' && document.getElementById('from_package').value != '' && document.getElementById('to_package').value != '' && document.getElementById('transfer_amount').value != ''))
	{
		$('#message').empty();
		return true;	
	}else
	{
		var msg = "<div class='error'>Please fill in all the text fields!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}

$('#transfer_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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

/*
$('#to_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
keydown(
	function(e)
    {
    	var key = e.keyCode || e.which;
        if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
        {
        	$(this).off('keydown').AnyTime_picker().focus();
            e.preventDefault();
        }
    } ); */
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