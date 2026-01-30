<?php
include('../include/dbconnection.php');	

$cust_id = $_GET['id'];
?>

<script type="text/javascript" src="../include/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="../include/js/anytimer/anytime.css" />
<script type="text/javascript" src="../include/js/anytimer/anytime.js"></script>

<style>
table tr th
{
	text-align:left;
	padding-left:10px;
	padding-bottom:10px;
}
table tr td
{
	padding-left:10px;
	padding-bottom:10px;
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
#save_record
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#save_record:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
</style>

<form method="post" action="save_record.php" onSubmit="return checkForm()">
<input type="hidden" name="custid" id="custid" value="<?php echo $cust_id; ?>">
<span style="font-weight:bold">Add Record</span><br><hr>
<div id="display" style="color:red; margin-left:10px">&nbsp; </div><br>
<table>
<tr>
<th>Date</th>
<td><input type="text" name="date" id="date" style="height:30px" ></td>
</tr>
<tr>
<th>Person in Charge</th>
<td><input type="text" name="personic" id="personic" style="height:30px" ></td>
</tr>
<tr>
<th>Unit</th>
<td>
	<select name="level" id="level" style="height:30px">
		<option></option>
		<option value="OFFICE">OFFICE</option>
		<option value="AUDITOR">AUDITOR</option>
		<option value="BLACKLIST IN CHARGE">BLACKLIST IN CHARGE</option>
	</select>
</td>
</tr>
<tr>
	<th>Reason</th>
	<td><textarea name="reason" id="reason" rows="4" cols="50" ></textarea></td>
</tr>
</table>
<br><br>
<div align="right">
		<input type="submit" name="save_record" id="save_record" value="" tabindex="4">&nbsp;&nbsp;&nbsp;
		<input type="reset" id="reset" name="reset" value="">
</div>
</form>

<script>
function checkForm() 
{
	if((document.getElementById('date').value != '' && document.getElementById('personic').value != '' && document.getElementById('level').value != '' && document.getElementById('reason').value != ''))
	{
		return true;
	}
	else
	{
		document.getElementById('display').innerHTML = "* Please fill in all fields";
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
</script>