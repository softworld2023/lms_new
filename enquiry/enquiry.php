<?php 
include('../include/page_header.php'); 
?>
<script src="../include/js/jquery.maskedinput.min.js" type="text/javascript"></script>
<style>
.upload {
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	font-size: 20px;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity=0);
}
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
input
{
	height:25px;
}

#email
{
	text-transform:none;
}
#list_table
{
	border-collapse:collapse;
	border:none;
}

#list_table tr th
{
	height:30px;
	background:#F0F0F0;
	text-align:left;
	padding-left:10px;
}

#table_form tr td
{
	height:35px;
}
#radio_table tr td
{
	height:25px;
}
/*
#add_enquiry
{
	background:url(../img/apply-loan/apply-loan-btn.jpg);
	width:132px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_enquiry:hover
{
	background:url(../img/apply-loan/apply-loan-btn-roll-over.jpg);
}*/
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
#add_enquiry
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_enquiry:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
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
<form action="action.php" method="post" id="en" name="en" enctype="multipart/form-data" onSubmit="return checkForm()">
<table width="1280" id="list_table">
	<tr>
    	<td>
        	<table>
            	<tr>
                	<td style="padding-left:15px"><img src="../img/enquiry/small-enquiry-icon.png"></td>
                    <td style="padding-left:15px">Add Enquiry</td>
                </tr>
            </table>
      </td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
	<tr>
    	<td>
        	<center>
       	  <table width="85%" id="table_form">
            	<tr>
                	<td colspan="2">
                    	<div id="message">
                        	<?php
							if($_SESSION['msg'] != '')
							{
								echo $_SESSION['msg'];
								$_SESSION['msg'] = '';
							}
							?>
                        </div>					</td>
                </tr>
                <tr>
                	<td width="40%" align="right" style="padding-right:10px">Date</td>
                    <td width="60%"><input type="text" name="enq_date" id="enq_date" tabindex="1" value="<?php echo date('d-m-Y'); ?>" /></td>
            	</tr>
                <tr>
                	<td align="right" style="padding-right:10px">Full Name (as per I/C)</td>
                    <td><input type="text" name="cust_name" id="cust_name" tabindex="1" style="width:300px" /></td>
            	</tr>
                <tr>
               	  <td align="right" style="padding-right:10px">New I.C. Number</td>
                    <td><span style="padding-right:10px">
                      <input type="text" name="nric" id="nric" tabindex="1" />
                    </span></td>
                </tr>
                 <tr>
                	<td align="right" style="padding-right:10px">Old I.C. Number</td>
                    <td><input type="text" name="oldicno" id="oldicno" tabindex="1" /></td>
            	</tr>
                 <tr >
                	<td align="right" style="padding-right:10px">Others&nbsp;<input type="radio" id="oth" name="otherno" value="other" style="margin-top: -1px;
  vertical-align: middle;"/>&nbsp;
                    Police&nbsp;<input type="radio" name="otherno" id="police" value="police" style="margin-top: -1px;
  vertical-align: middle;" />&nbsp;Army<input type="radio" name="otherno" id="army" value="army" style="margin-top: -1px;
  vertical-align: middle;" /></td>
                    <td><input type="text" name="no_oth" id="no_oth" tabindex="1" /></td>
            	</tr>
                 <tr>
                	<td align="right" style="padding-right:10px">Mobile Phone</td>
                    <td><input type="text" name="mobile" id="mobile" tabindex="1" /></td>
            	</tr>
                 <tr>
                	<td align="right" style="padding-right:10px">Home Phone</td>
                    <td><input type="text" name="homephone" id="homephone" tabindex="1" /></td>
            	</tr>
                 <tr>
                	<td align="right" style="padding-right:10px">Email Address</td>
                    <td><input type="text" name="email" id="email" tabindex="1" /></td>
            	</tr>
                 <tr>
                	<td align="right" style="padding-right:10px">Race</td>
                   <td>
					<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" id="malay" name="race" value="malay" onClick="show()" style="margin-top: -1px;
  vertical-align: middle;"/></td>
                                <td style="padding-left:5px; padding-right:20px">Malay</td>
                            	<td><input type="radio" name="race" id="chinese" value="chinese" onclick="show()" style="margin-top: -1px;
  vertical-align: middle;" /></td>
                            	<td style="padding-left:5px; padding-right:20px">Chinese</td>
								<td><input type="radio" name="race" value="indian" onclick="show()" id="indian" style="margin-top: -1px;
  vertical-align: middle;" /></td>
								<td style="padding-left:5px; padding-right:20px">Indian</td>
								<td><input type="radio" name="race" value="others" onclick="show()" id="others_race" style="margin-top: -1px; vertical-align: middle;" /></td>
                            	<td style="padding-left:5px; padding-right:20px">Others</td>
                            </tr>
                      </table>
					
					</td>
                 </tr>
                 <tr>
                	<td align="right" style="padding-right:10px"></td>
                    <td><input type="text" name="other_race_name" id="other_race_name" style="display:none"/></td>
            	</tr>
            	
                <tr>
                	<td align="right" style="padding-right:10px">Sex</td>
                    <td>
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="gender" id="gender" value="Male" checked ></td>
                                <td style="padding-left:5px; padding-right:20px">Male</td>
                            	<td><input type="radio" name="gender" id="gender" value="Female"></td>
                                <td style="padding-left:5px; padding-right:20px">Female</td>
                            </tr>
                        </table>                    </td>
            	</tr>
               
                <tr>
                	<td align="right" style="padding-right:10px">Branch</td>
                    <td>
						<select name="branch" id="branch" style="height:30px">
						<option value=""></option>
						<?php
						$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'");
						while($branch = mysql_fetch_assoc($branch_q))
						{
						?>
							<option value="<?php echo $branch['branch_name']; ?>"><?php echo $branch['branch_name']; ?></option>
						<?php } ?>
							<option value="OTHERS">OTHERS</option>
						</select>
					</td>
            	</tr>
                <tr>
                	<td align="right" style="padding-right:10px; padding-top:10px" valign="top">Remarks</td>
                    <td style="padding-top:4px"><textarea name="remarks" id="remarks" style="width:300px; height:80px" tabindex="1"></textarea></td>
            	</tr>
                <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            </center>
        </td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr height="35">
    	<td>
        	<table width="100%">
            	<tr>
                	<td><!--<input type="submit" name="apply_loan" id="apply_loan" value="">--></td>
                    <td align="right"><input type="submit" name="add_enquiry" id="add_enquiry" value="" tabindex="1">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../enquiry/'" value=""></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
</table>
</form>
<script>
$(function() {

	$.mask.definitions['~'] = "[+-]";

	$("#nric").mask("999999-99-9999");
	

})
function show(){
if(others_race.checked == true){
other_race_name.style.display = "block";
other_race_name.focus();
}
else{other_race_name.style.display = "none";}
}

function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function validate2(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /^[a-zA-Z@]/;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function autotab(current,to){
    if (current.getAttribute && 
      current.value.length==current.getAttribute("maxlength")) {
        to.focus() 
        }
}
</script>
<script>
$(document).ready(function() {
	document.getElementById('bis').focus();
});
function raceOther()
{
	document.getElementById("race_other").disabled = false;
	document.getElementById('race_other').focus();
}
function raceOther2()
{
	document.getElementById("race_other").disabled = true;
}

$('#enq_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

function checkForm()
{
	if((document.getElementById('cust_name').value != ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
			$('html, body').animate({scrollTop:150}, 'fast');
			var msg = "<div class='error'>Please fill in the form!</div>";
			$('#message').empty();
			$('#message').append(msg); 
			$('#message').html();
			return false;
	}
}
</script>