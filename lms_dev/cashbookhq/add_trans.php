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
#add_trans
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_trans:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<form method="post" action="action.php" onSubmit="return checkForm()">
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/cash-book/cash-book.png" style="height:47px"></td>
        <td>Add New Transaction </td>
    </tr>
    <tr>
    	<td colspan="2">
            <div id="message" style="width:100%;">
            <?php
            if($_SESSION['msg'] != '')
            {
                echo $_SESSION['msg'];
                $_SESSION['msg'] = '';
            }
            ?>
            </div>
		</td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<th width="15%">Transaction Details</th>
        <td><select name="description" id="description" style="height:30px;">
				<option value=""></option>
				<?php $type_q = mysql_query("SELECT * FROM cashbook_setting ORDER BY description ASC"); 
				while($type = mysql_fetch_assoc($type_q)){
				?>
				<option value="<?php echo $type['description']; ?>"><?php echo $type['description']; ?></option>
				<?php } ?>
			</select><input type="hidden" name="description1" id="description1" /></td>
    </tr>
	<tr>
	<th>
	</th>
	<td>
		<input type="text" name="description2" id="description2" style="width:250px" />
	</td>
	</tr>
    <tr>
    	<th>Date</th>
        <td><input type="text" name="date" id="date" value="<?php echo date('d-m-Y'); ?>" /></td>
    </tr>
    <tr>
        <th>Amount</th>
        <td><input type="text" name="amount" id="amount" class="currency" /></td>
    </tr>
    <tr>
        <th>Transaction Type</th>
        <td>
        <select name="type" id="type" style="height:30px;">
        	<option value=""></option>
        	<option value="in">In</option>
			<option value="out">Out</option>
        </select>
        </td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="add_trans" id="add_trans" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../cashbookhq/'" value="">
        </td>
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
	if((document.getElementById('expenses_details').value != '' && document.getElementById('date').value != '' && document.getElementById('amount').value != '' && document.getElementById('package_id').value != ''))
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
$('#date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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