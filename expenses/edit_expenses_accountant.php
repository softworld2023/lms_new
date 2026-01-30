<?php include('../include/page_header.php'); 
$id = $_GET['id'];

$sql = mysql_query("SELECT * FROM expenses_accountant WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);
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
#edit_expenses
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_expenses:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<form method="post" action="action_accountant.php" onSubmit="return checkForm()"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/expenses.png" width="56"></td>
        <td>Edit Expenses</td>
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
    	<th width="15%">Expenses Type</th>
        <td>
			<!--<select name="expenses_details" id="expenses_details" style="height:30px;" onchange="eType(this.value)">-->
			<select name="expenses_details" id="expenses_details" style="height:30px;" onchange="eType(this.value)">
				<option value=""></option>
				<?php $type_q = mysql_query("SELECT * FROM expenses_setting ORDER BY description ASC"); 
				while($type = mysql_fetch_assoc($type_q)){
				?>
				<option value="<?php echo $type['description']; ?>" <?php if($type['description'] == $get_q['expenses_type']) { echo 'selected'; } ?>><?php echo $type['description']; ?></option>
				<?php } ?>
			</select><input type="hidden" name="details1" id="details1" />
		</td>
    </tr>
	<tr>
    	<th width="15%">Expenses Details</th>
        <td>
			<!--<div style="visibility:hidden" id="show"><input type="text" name="details2" id="details2" style="width:250px" /></div>-->
			<input type="text" name="details2" id="details2" style="width:250px" value="<?php echo $get_q['expenses_details']; ?>" />
		</td>
    </tr>
	
    <tr>
    	<th>Date</th>
        <td><input type="text" name="date" id="date" value="<?php echo $get_q['date']; ?>" /></td>
    </tr>

    <tr>
    	<th>Pay Month</th>
        <td><input type="text" name="month_receipt" id="month_receipt" value="<?php echo $get_q['month_receipt']; ?>" /></td>
    </tr>
    <tr>
        <th>Amount</th>
        <td><input type="text" name="amount" id="amount" class="currency" value="<?php echo $get_q['amount']; ?>" /></td>
    </tr>
    <!-- <tr>
      <th>Package</th>
      <td>
	  <select name="package_id" id="package_id" style="height:30px;">
        <option value=""></option>
        <?php 
			$package_q = mysql_query("SELECT * FROM loan_scheme");
			while($get_p = mysql_fetch_assoc($package_q))
			{
		?>
        <option value="<?php echo $get_p['id']; ?>" <?php if($get_p['id'] == $get_q['package_id']) { echo 'selected'; } ?>><?php echo $get_p['scheme']; ?></option>
        <?php 
			}
		?>
      </select>	  </td>
    </tr> -->
<!--     <tr>
        <th>Transaction Type </th>
        <td>
			<select name="ttype" id="ttype" style="height:30px;">
				<option value=""></option>
				<option value="RECEIVED" <?php if($get_q['ttype'] == 'RECEIVED') { echo 'selected'; } ?>>RECEIVED</option>
				<option value="EXPENSES" <?php if($get_q['ttype'] == 'EXPENSES') { echo 'selected'; } ?>>EXPENSES</option>
			</select>		</td>
    </tr> -->
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="edit_expenses" id="edit_expenses" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../expenses/'" value="">        </td>
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
	if((document.getElementById('expenses_details').value != '' && document.getElementById('date').value != '' && document.getElementById('amount').value != '' && document.getElementById('package_id').value != ''&& document.getElementById('ttype').value))
	{
		$ed = document.getElementById('details1').value;
		if($ed == 'SALARY' || $ed == 'PETROL' || $ed == 'REPAIR' || $ed == 'PARKING' || $ed == 'KOMISEN' || $ed == 'AGENT KOMISEN' || $ed == 'TOUCH & GO' || $ed == 'CAR INSTALLMENT')
		{
			if(document.getElementById('details2').value != '')
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
		}else
		{
			$('#message').empty();
			return true;	
		}
	}else
	{
		var msg = "<div class='error'>Please fill in all the text fields!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
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

$('#month_receipt').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
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

function eType(t)
{
	$type = t;
	document.getElementById('details1').value = $type;
	
	/*if($type == 'SALARY' || $type == 'PETROL' || $type == 'REPAIR' || $type == 'PARKING' || $type == 'KOMISEN' || $type == 'AGENT KOMISEN' || $type == 'TOUCH & GO' || $type == 'CAR INSTALLMENT' || $type == 'OTHER')
	{
		document.getElementById('show').style.visibility = 'visible';	
	}else
	{
		document.getElementById('show').style.visibility = 'hidden';	
		document.getElementById('details2').value = '';
	}*/
}
</script>
</body>
</html>