<?php include('../include/page_header.php'); 
$id = $_GET['id'];
$branch = $_GET['branch'];
$database = $_GET['database'];

$get_database = mysql_query("SELECT db_name FROM branch WHERE branch_id= '".$branch. "' AND branch_name = '" .$database."'");
$get_database_fetch = mysql_fetch_assoc($get_database);

$database_name = $get_database_fetch['db_name'];

$sql = mysql_query("SELECT * FROM ".$database_name.".customer_loanpackage WHERE id = '".$id."'");
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
#edit_status
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_status:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<form method="post" action="action.php" onSubmit="return checkForm()">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="database_name" id="database_name" value="<?php echo $database_name; ?>">

<table width="1280">
	<tr>
    	<td width="65"><img src="../img/expenses.png" width="56"></td>
        <td>Edit Loan Status</td>
    </tr>
     <tr>
    	<td colspan="3">&nbsp;</td>
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
    	<th width="20%"><span style=" float:left">Loan Package Type <?php echo $zz; ?></span></th>
        
        <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="NEW LOAN" <?php if($get_q['loanpackagetype'] == 'NEW LOAN') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">New Loan</td>
                      </tr>
                  </table></td>
    </tr>
    
    <tr>
    <th width="15%"></th>
                  <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="RELOAN" <?php if($get_q['loanpackagetype'] == 'RELOAN') { echo 'checked'; } ?>/></td>
                        <td style="padding-left:5px; padding-right:20px">Reloan</td>
                      </tr>
                  </table></td>
    <tr>
    <tr>
    <th width="15%"></th>
    <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="OVERLAP" <?php if($get_q['loanpackagetype'] == 'OVERLAP') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Overlap</td>
                      </tr>
                  </table></td>
      </tr>
                 <tr>
    <th width="15%"></th>
    <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="ACCOUNT 2" <?php if($get_q['loanpackagetype'] == 'ACCOUNT 2') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Account 2</td>
                      </tr>
                  </table></td>
                 </tr>
    	<th><span style=" float:left">Settlement Date for Previous Loan</span></th>
        <td><input type="text" name="prev_settlementdate" id="prev_settlementdate" value="<?php if($get_q['prev_settlementdate'] != '1970-01-01' && $get_q['prev_settlementdate'] != '0000-00-00')  { echo date('d-m-Y', strtotime($get_q['prev_settlementdate'])); }?>" /></td>
    </tr>
    <tr>
        <th><span style=" float:left">Previous Loan Code</span></th>
        <td><input type="text" name="prev_loancode" id="prev_loancode" value="<?php echo $get_q['prev_loancode']; ?>" /> </td>
    </tr>
    
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="edit_status" id="edit_status" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../cashbookhq/loanstatus.php'" value="">        </td>
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

$('#prev_settlementdate').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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

/*$('#from_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
    } );*/
</script>
</body>
</html>