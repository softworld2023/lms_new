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
#add_scheme
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_scheme:hover
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
        <td>Add New Package</td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="2">
		<div class="subnav">
			<a href="index.php" id="active-menu">Package Listing</a><a href="transfer.php">Money Transfer</a>
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
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<th width="15%">Package Name</th>
        <td><input type="text" name="scheme" id="scheme" style="width:350px" /></td>
    </tr>
    <tr>
    	<th>Formula</th>
        <td><input type="text" name="formula" id="formula" style="width:350px" /></td>
    </tr>
    <tr>
        <th>Interest</th>
        <td><input type="text" name="interest" id="interest" style="width:50px" /> %</td>
    </tr>
    <tr>
        <th>Period</th>
        <td><input type="text" name="from_date" id="from_date" style="width:100px" />&nbsp;&nbsp;to&nbsp;&nbsp;<input type="text" name="to_date" id="to_date" style="width:100px"> months</td>
    </tr>
    <tr>
      <th>Type</th>
		<td>
		<select name="type" id="type" style="height:30px">
			<option value="">-Select-</option>
			<option value="Flexi Loan">Flexi Loan</option>
        	<option value="Fixed Loan">Fixed Loan</option>
      	</select>
		</td>
    </tr>
    <tr>
    	<th>Receipt Type</th>
        <td>
		<select name="receipt_type" id="receipt_type" style="height:30px">
			<option value="">-Select-</option>
			<option value="1">Fixed</option>
        	<option value="2">Changing</option>
      	</select>
		</td>
	</tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="add_scheme" id="add_scheme" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../package/'" value="">        </td>
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
	if((document.getElementById('scheme').value != '' && document.getElementById('interest').value != '' && document.getElementById('from_date').value != '' && document.getElementById('to_date').value != '' && document.getElementById('type').value != ''))
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
/*
$('#from_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
</script>
</body>
</html>