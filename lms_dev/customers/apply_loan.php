<?php 
include('../include/page_header.php'); 

$branchid = mysql_query("select branch_name from user where id = '".$_SESSION['taplogin_id']."'");
$branchid_q = mysql_fetch_assoc($branchid);

/*echo "<h3> PHP List All Session Variables</h3>";
    foreach ($_SESSION as $key=>$val)
    echo $key." ".$val."<br/>";
	*/
?>
<script src="../include/js/jquery.maskedinput.min.js" type="text/javascript"></script>

<script language="javascript">
function getGender(nirc){
	if(nirc.charAt(13) != '_'){
		gender = nirc.charAt(13) % 2;
		if(gender == 1){
			document.getElementById("Male").checked = true;
		}
		if(gender == 0){
			document.getElementById("Female").checked = true;
		}
	}
}

function inputPass(pass){
	if(pass != ''){
		$("#nirc").removeAttr("required");
	}
	else{
		$("#nirc").attr("required","required");
	}
}

</script>
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
#apply_loan
{
	background:url(../img/apply-loan/apply-loan-btn.jpg);
	width:132px;
	height:30px;
	border:none;
	cursor:pointer;
}
#apply_loan:hover
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
#apply_loan
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#apply_loan:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
.fileUpload {	position: relative;
	overflow: hidden;
	width:73px;
	height:22px;
	background:url(../img/browse.JPG) no-repeat;
}
.email
{
	text-transform:none;
}
</style>
<center>
<form action="action.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
<table width="1280" id="list_table">
	<tr>
    	<td>
        	<table>
            	<tr>
                	<td style="padding-left:15px"><img src="../img/apply-loan/apply-loan.png"></td>
                    <td style="padding-left:15px; font-weight: bold;">Apply Loan</td>
                </tr>
            </table>
      </td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
	<tr>
    	<th height="23">My Personal Details</th>
  	</tr>
    <tr>
    	<td>
        	<center>
        	  <table width="85%" id="table_form2">
                <tr>
                  <td colspan="5"><div id="message"></div></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Customer ID</td>
                  <td><table id="radio_table3">
                      <tr>
                        <td width="79"><input type="text" name="customercode2" id="customercode2" style="width:60px" onblur="checkcode(this.value)" value="<?php if($get_q['customercode2'] != '') { echo $get_q['customercode2']; } ?>" /></td>
                        <td width="30" style="padding-left:5px; padding-right:20px"><span style="padding-right:10px">Recruiter</span></td>
                        <td width="196" style="padding-left:5px; padding-right:20px">
                          
                          <input type="text" name="recruitor" id="recruitor" style="width:150px" value="<?php if($get_q['recruitor'] != '') { echo $get_q['recruitor']; } ?>" />
                       </td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Customer Picture</td>
                  <td width="31%" rowspan="4">
				  	<table>
						<tr>
							<td><img name="cust_pic2" id="cust_pic2" style="height:115px; width:115px" /></td>
							<td><img name="cust_pic4" id="cust_pic4" style="height:115px; width:115px" /></td>
							<td><img name="cust_pic6" id="cust_pic6" style="height:115px; width:115px" /></td>
						</tr>
					</table>                      
                  </td>
                </tr>
                <tr>
                  <td width="15%" align="left" style="padding-right:10px">BIS / CTOS / CCRIS</td>
                  <td width="38%"><input type="text" name="bis" id="bis" style="width:300px" tabindex="1" value="<?php echo $get_q['bis']; ?>" /></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td><table id="radio_table2">
                      <tr>
                        <td><input type="radio" name="title" id="title2" value="MR." <?php if($get_q['title'] == 'MR.') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">MR.</td>
                        <td><input type="radio" name="title" id="title2" value="MISS" <?php if($get_q['title'] == 'MISS') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">MISS</td>
                        <td><input type="radio" name="title" id="title2" value="MADAM" <?php if($get_q['title'] == 'MADAM') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">MADAM</td>
                        <td><input type="radio" name="title" id="title2" value="DR." <?php if($get_q['title'] == 'DR.') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">DR.</td>
                        <td><input type="radio" name="title" id="title2" value="Others" <?php if($get_q['title'] == 'OTHERS') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Others</td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Full Name (as per I/C)</td>
                  <td><input type="text" name="name" id="name" style="width:300px" tabindex="1" value="<?php echo $get_q['name']; ?>" required /></td>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">New I/C</td>
                  <td><input type="text" name="nric" id="nric" tabindex="1" value="<?php echo $get_q['nric']; ?>" onblur="checkIC(this.value)" required /></td>
                  <td width="2%" align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="14%" align="left" style="padding-right:10px">&nbsp;</td>
                  <td>
				  	<table>
						<tr>
							<td style="width:115px">
							<center><div class="fileUpload">
                    			<input type="file" name="cust_pic" id="cust_pic" class="upload" onchange="readURL(this, 'cust_pic2');" />
                  			</div></center>
				  			</td>
							<td style="width:115px">
							<center><div class="fileUpload">
                    			<input type="file" name="cust_pic3" id="cust_pic3" class="upload" onchange="readURL(this, 'cust_pic4');" />
                  			</div></center>
							</td>
							<td style="width:115px">
							<center><div class="fileUpload">
                    			<input type="file" name="cust_pic5" id="cust_pic5" class="upload" onchange="readURL(this, 'cust_pic6');" />
                  			</div></center>
							</td>
						</tr>
					</table>  
				  </td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Old I/C</td>
                  <td><input type="text" name="oldic" id="oldic" tabindex="1" value="<?php echo $get_q['old_ic']; ?>" /></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Date of Birth</td>
                  <td><input type="text" name="dob" id="dob" tabindex="1" value="<?php if($get_q['dob'] != '0000-00-00') { echo $get_q['dob']; } ?>" /></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Others<br/><a onClick=reset_others()>Reset</a></td>
                  <td><table id="radio_table4">
                      <tr>
                        <td><input type="radio" name="others_ic" id="others_ic" value="police" <?php if($get_q['others_ic'] == 'police') { echo 'checked'; } ?> onclick="apic();" /></td>
                        <td style="padding-left:5px; padding-right:20px">Police</td>
                        <td><input type="radio" name="others_ic" id="others_ic" value="army" <?php if($get_q['others_ic'] == 'army') { echo 'checked'; } ?> onclick="apic();" /></td>
                        <td style="padding-left:5px; padding-right:20px">Army</td>
                        <td><input type="text" name="others_ic2" id="others_ic2" <?php if($get_q['others_ic2'] != '') { ?>value="<?php echo $get_q['others_ic2']; ?>"<?php } else { echo 'disabled'; }?> /></td>
                      </tr>
                  </table></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Sex</td>
                  <td><table id="radio_table2">
                      <tr>
                        <td><input type="radio" name="gender" id="gender2" value="Male" <?php if($get_q['gender'] == 'Male') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Male</td>
                        <td><input type="radio" name="gender" id="gender2" value="Female" <?php if($get_q['gender'] == 'Female') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Female</td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Mobile Phone </td>
                  <td><input type="text" name="mobile_contact" id="mobile_contact" tabindex="4" value="<?php echo $get_add['mobile_contact']; ?>" required /></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Race</td>
                  <td><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="race" id="race" value="Malay" onclick="raceOther2();" <?php if($get_q['race'] == 'Malay') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Malay</td>
                        <td><input type="radio" name="race" id="race" value="Chinese" onclick="raceOther2();" <?php if($get_q['race'] == 'Chinese') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Chinese</td>
                        <td><input type="radio" name="race" id="race" value="Indian" onclick="raceOther2();" <?php if($get_q['race'] == 'Indian') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Indian</td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Home Phone </td>
                  <td><input type="text" name="home_contact" id="home_contact" tabindex="3" value="<?php echo $get_add['home_contact']; ?>" /></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="race" id="radio" value="Others" onclick="raceOther();" <?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Others</td>
                        <td><input type="text" name="race_other" id="race_other" <?php if($get_q['race'] == 'Malay' || $get_q['race'] == 'Chinese' || $get_q['race'] == 'Indian') { echo 'disabled'; } ?> value="<?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo $get_q['race']; } ?>" />
                            <input type="hidden" name="p_race_other" id="p_race_other" value="<?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo $get_q['race']; } ?>" /></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Email Address </td>
                  <td><input type="text" name="nationality" id="nationality" tabindex="1" style="width:300px" value="<?php echo $get_q['nationality']; ?>" class="email" /></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Marital Status</td>
                  <td><table id="radio_table2">
                      <tr>
                        <td><input type="radio" name="marital_status" id="marital_status2" value="Single" <?php if($get_q['marital_status'] == 'Single') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Single</td>
                        <td><input type="radio" name="marital_status" id="marital_status2" value="Married" <?php if($get_q['marital_status'] == 'Married') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Maried</td>
                        <td><input type="radio" name="marital_status" id="marital_status2" value="Widowed / Divorced" <?php if($get_q['marital_status'] == 'Widowed / Divorced') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Widowed / Divorced</td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Customer From</td>
                  <td align="left" style="padding-right:10px">
					<select name="source" id="source" style="width:150px; padding:3px;">
						<option value=""></option>
						<option value="WALK IN">WALK IN</option>
						<option value="FACEBOOK">FACEBOOK</option>
						<option value="GOOGLE">GOOGLE</option>
						<option value="TIKTOK">TIKTOK</option>
					</select>
				  </td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">No. of Dependents</td>
                  <td><input type="text" name="no_dependents" id="no_dependents" tabindex="2" value="<?php echo $get_q['no_dependents']; ?>" /></td>
                </tr>
                 <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Dependents</td>
                  <td><table id="radio_table2">
                      <tr>
                        <td><input type="checkbox" name="dependent1" id="dependent1" value="Father" <?php if($get_q['dependent1'] == 'Father') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Father &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><input type="checkbox" name="dependent2" id="dependent2" value="Mother" <?php if($get_q['dependent2'] == 'Mother') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Mother</td>
                      </tr></table></td></tr>
                       <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px"></td>
                  <td><table id="radio_table2">
                      <tr>
                        <td><input type="checkbox" name="dependent3" id="dependent3" value="FatherIL" <?php if($get_q['dependent3'] == 'FatherIL') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Father in Law</td>
                        <td><input type="checkbox" name="dependent4" id="dependent4" value="MotherIL" <?php if($get_q['dependent4'] == 'MotherIL') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Mother in Law</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" name="dependent5" id="dependent5" value="Child" <?php if($get_q['dependent5'] == 'Child') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Child</td>
                        <td><input type="checkbox" name="dependent6" id="dependent6" value="HorW" <?php if($get_q['dependent6'] == 'HorW') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Husband/Wife</td>
                      </tr>
                  </table></td>
                </tr>
<!--                 <tr>
                  <td height="25" align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="31%">&nbsp;</td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td style="padding-right:10px">Academic Qualification</td>
                  <td valign="top"><input type="text" name="academic_qualification" id="academic_qualification" tabindex="2" value="<?php echo $get_q['academic_qualification']; ?>" style="width:300px" /></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="31%">&nbsp;</td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td style="padding-right:10px">Mother's Name</td>
                  <td valign="top"><input type="text" name="mother_name" id="mother_name" style="width:300px" tabindex="2" value="<?php echo $get_q['mother_name']; ?>" /></td>
                </tr> -->
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
              </table>
        	</center>
        </td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<th height="23">My Home Address</th>
    </tr>
    <tr>
    	<td>
        	<center>
   	  	  <table width="85%" id="table_form3">
        	    <tr>
        	      <td colspan="4"><div id="message2"></div></td>
      	      </tr>
        	    <tr>
        	      <td width="15%" align="left" style="padding-right:10px">&nbsp;</td>
        	      <td width="40%">&nbsp;</td>
        	      <td colspan="2" align="left" style="padding-right:10px"><input type="checkbox" onclick="sameR()" name="sameaddress" value="samer" id="sameaddress" />
        	        please tick if same as residence address </td>
       	        </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">Residence Address</td>
        	      <td><input type="text" name="address1" id="address1" style="width:300px" tabindex="3" value="<?php echo $get_add['address1']; ?>" /></td>
        	      <td width="14%" align="left" style="padding-right:10px">Mailing Address </td>
        	      <td width="31%"><input type="text" name="m_address1" id="m_address1" style="width:300px" tabindex="4" value="<?php echo $get_add['m_address1']; ?>" /></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td><input type="text" name="address2" id="address2" style="width:300px" tabindex="3" value="<?php echo $get_add['address2']; ?>" /></td>
        	      <td align="left" valign="top" style="padding-right:10px"><p>
        	        <label></label>
        	        <br />
      	        </p></td>
        	      <td><input type="text" name="m_address2" id="m_address2" style="width:300px" tabindex="4" value="<?php echo $get_add['m_address2']; ?>" /></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td valign="top"><input type="text" name="address3" id="address3" style="width:300px" tabindex="3" value="<?php echo $get_add['address3']; ?>" /></td>
        	      <td align="left" valign="top" style="padding-right:10px"><label></label></td>
        	      <td><input type="text" name="m_address3" id="m_address3" style="width:300px" tabindex="4" value="<?php echo $get_add['m_address3']; ?>" /></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>&nbsp;</td>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>(as per I/C) </td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">Postcode</td>
        	      <td><input type="text" name="postcode" id="postcode" tabindex="3" value="<?php echo $get_add['postcode']; ?>" /></td>
        	      <td align="left" style="padding-right:10px">Postcode</td>
        	      <td><input type="text" name="m_postcode" id="m_postcode" tabindex="4" value="<?php echo $get_add['m_postcode']; ?>" /></td>
       	        </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">City</td>
        	      <td><input type="text" name="city" id="city" tabindex="3" value="<?php echo $get_add['city']; ?>" /></td>
        	      <td align="left" style="padding-right:10px">City</td>
        	      <td><input type="text" name="m_city" id="m_city" tabindex="4" value="<?php echo $get_add['m_city']; ?>" /></td>
       	        </tr>
        	    <tr>
        	      <td rowspan="2" align="left" valign="top" style="padding-right:10px; padding-top:10px"><span style="padding-right:10px">State</span></td>
        	      <td rowspan="2" align="left">
				  <select name="state" id="state" style="height:30px; width:85px">
				  <option value=""></option>
                  <option value="JOHOR" <?php if($get_add['state'] == 'JOHOR') { echo 'selected'; } ?>>JOHOR</option>
                  <option value="KEDAH" <?php if($get_add['state'] == 'KEDAH') { echo 'selected'; } ?>>KEDAH</option>
                  <option value="KELANTAN" <?php if($get_add['state'] == 'KELANTAN') { echo 'selected'; } ?>>KELANTAN</option>
                  <option value="MELAKA" <?php if($get_add['state'] == 'MELAKA') { echo 'selected'; } ?>>MELAKA</option>
                  <option value="NEGERI SEMBILAN" <?php if($get_add['state'] == 'NEGERI SEMBILAN') { echo 'selected'; } ?>>NEGERI SEMBILAN</option>
                  <option value="PAHANG" <?php if($get_add['state'] == 'PAHANG') { echo 'selected'; } ?>>PAHANG</option>
                  <option value="PERAK" <?php if($get_add['state'] == 'PERAK') { echo 'selected'; } ?>>PERAK</option>
                  <option value="PERLIS" <?php if($get_add['state'] == 'PERLIS') { echo 'selected'; } ?>>PERLIS</option>
                  <option value="PENANG" <?php if($get_add['state'] == 'PENANG') { echo 'selected'; } ?>>PENANG</option>
                  <option value="SABAH" <?php if($get_add['state'] == 'SABAH') { echo 'selected'; } ?>>SABAH</option>
                  <option value="SARAWAK" <?php if($get_add['state'] == 'SARAWAK') { echo 'selected'; } ?>>SARAWAK</option>
                  <option value="SELANGOR" <?php if($get_add['state'] == 'SELANGOR') { echo 'selected'; } ?>>SELANGOR</option>
                  <option value="TERENGGANU" <?php if($get_add['state'] == 'TERENGGANU') { echo 'selected'; } ?>>TERENGGANU</option>
				  <option value="WILAYAH PERSEKUTUAN KUALA LUMPUR" <?php if($get_add['state'] == 'WILAYAH PERSEKUTUAN KUALA LUMPUR') { echo 'selected'; } ?>>WILAYAH PERSEKUTUAN KUALA LUMPUR</option>
				  <option value="WILAYAH PERSEKUTUAN LABUAN" <?php if($get_add['state'] == 'WILAYAH PERSEKUTUAN LABUAN') { echo 'selected'; } ?>>WILAYAH PERSEKUTUAN LABUAN</option>
				  <option value="WILAYAH PERSEKUTUAN PUTRAJAYA" <?php if($get_add['state'] == 'WILAYAH PERSEKUTUAN PUTRAJAYA') { echo 'selected'; } ?>>WILAYAH PERSEKUTUAN PUTRAJAYA</option>
                </select>				  </td>
        	      <td align="left" style="padding-right:10px">State</td>
        	      <td>
				  <select name="m_state" id="m_state" style="height:30px; width:85px">
				  <option value=""></option>
                  <option value="JOHOR" <?php if($get_add['m_state'] == 'JOHOR') { echo 'selected'; } ?>>JOHOR</option>
                  <option value="KEDAH" <?php if($get_add['m_state'] == 'KEDAH') { echo 'selected'; } ?>>KEDAH</option>
                  <option value="KELANTAN" <?php if($get_add['m_state'] == 'KELANTAN') { echo 'selected'; } ?>>KELANTAN</option>
                  <option value="MELAKA" <?php if($get_add['m_state'] == 'MELAKA') { echo 'selected'; } ?>>MELAKA</option>
                  <option value="NEGERI SEMBILAN" <?php if($get_add['m_state'] == 'NEGERI SEMBILAN') { echo 'selected'; } ?>>NEGERI SEMBILAN</option>
                  <option value="PAHANG" <?php if($get_add['m_state'] == 'PAHANG') { echo 'selected'; } ?>>PAHANG</option>
                  <option value="PERAK" <?php if($get_add['m_state'] == 'PERAK') { echo 'selected'; } ?>>PERAK</option>
                  <option value="PERLIS" <?php if($get_add['m_state'] == 'PERLIS') { echo 'selected'; } ?>>PERLIS</option>
                  <option value="PENANG" <?php if($get_add['m_state'] == 'PENANG') { echo 'selected'; } ?>>PENANG</option>
                  <option value="SABAH" <?php if($get_add['m_state'] == 'SABAH') { echo 'selected'; } ?>>SABAH</option>
                  <option value="SARAWAK" <?php if($get_add['m_state'] == 'SARAWAK') { echo 'selected'; } ?>>SARAWAK</option>
                  <option value="SELANGOR" <?php if($get_add['m_state'] == 'SELANGOR') { echo 'selected'; } ?>>SELANGOR</option>
                  <option value="TERENGGANU" <?php if($get_add['m_state'] == 'TERENGGANU') { echo 'selected'; } ?>>TERENGGANU</option>
				  <option value="WILAYAH PERSEKUTUAN KUALA LUMPUR" <?php if($get_add['m_state'] == 'WILAYAH PERSEKUTUAN KUALA LUMPUR') { echo 'selected'; } ?>>WILAYAH PERSEKUTUAN KUALA LUMPUR</option>
				  <option value="WILAYAH PERSEKUTUAN LABUAN" <?php if($get_add['m_state'] == 'WILAYAH PERSEKUTUAN LABUAN') { echo 'selected'; } ?>>WILAYAH PERSEKUTUAN LABUAN</option>
				  <option value="WILAYAH PERSEKUTUAN PUTRAJAYA" <?php if($get_add['m_state'] == 'WILAYAH PERSEKUTUAN PUTRAJAYA') { echo 'selected'; } ?>>WILAYAH PERSEKUTUAN PUTRAJAYA</option>
                </select>				  </td>
      	      </tr>
        	    <tr>
        	      <td rowspan="2" align="left" valign="top" style="padding-right:10px; padding-top:10px">Home Ownership</td>
        	      <td rowspan="2"><table width="100%" id="radio_table5">
        	        <tr>
        	          <td width="10%"><input type="radio" name="m_residence" id="m_residence1" value="Owned-No-Loan" <?php if($get_add['m_residence'] == 'Owned-No-Loan') { echo 'checked'; } ?> /></td>
        	          <td width="44%" style="padding-left:5px">Owned-No-Loan</td>
        	          <td width="8%"><input type="radio" name="m_residence" id="m_residence2" value="Rented" <?php if($get_add['m_residence'] == 'Rented') { echo 'checked'; } ?> /></td>
        	          <td width="38%" style="padding-left:5px">Rented</td>
      	          </tr>
        	        <tr>
        	          <td><input type="radio" name="m_residence" id="m_residence3" value="Owned-With-Loan" <?php if($get_add['m_residence'] == 'Owned-With-Loan') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px">Owned-With-Loan</td>
        	          <td><input type="radio" name="m_residence" id="m_residence4" value="Employers" <?php if($get_add['m_residence'] == 'Employers') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px">Employer's</td>
      	          </tr>
        	        <tr>
        	          <td><input type="radio" name="m_residence" id="m_residence5" value="Parents / Relatives" <?php if($get_add['m_residence'] == 'Parents / Relatives') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px">Parent's / Relative's</td>
        	          <td></td>
        	          <td style="padding-left:5px"></td>
      	          </tr>
       	          </table></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px" valign="top"><span style="padding-right:10px; padding-top:10px padding-top:10px">Home Ownership</span></td>
        	      <td><table width="100%" id="radio_table6">
        	        <tr>
        	          <td width="9%"><input type="radio" name="residence" id="residence1" value="Owned-No-Loan" <?php if($get_add['residence'] == 'Owned-No-Loan') { echo 'checked'; } ?> /></td>
        	          <td width="37%" style="padding-left:5px">Owned-No-Loan</td>
        	          <td width="7%"><input type="radio" name="residence" id="residence2" value="Rented" <?php if($get_add['residence'] == 'Rented') { echo 'checked'; } ?> /></td>
        	          <td width="47%" style="padding-left:5px">Rented</td>
      	          </tr>
        	        <tr>
        	          <td><input type="radio" name="residence" id="residence3" value="Owned-With-Loan" <?php if($get_add['residence'] == 'Owned-With-Loan') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px">Owned-With-Loan</td>
        	          <td><input type="radio" name="residence" id="residence4" value="Employers" <?php if($get_add['residence'] == 'Employers') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px">Employer's</td>
      	          </tr>
        	        <tr>
        	          <td><input type="radio" name="residence" id="residence5" value="Parents / Relatives" <?php if($get_add['residence'] == 'Parents / Relatives') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px">Parent's / Relative's</td>
        	          <td></td>
        	          <td style="padding-left:5px"></td>
      	          </tr>
       	          </table></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">Years of Stay</td>
        	      <td><table id="radio_table9">
        	        <tr>
        	          <td width="87"><span style="padding-left:5px; padding-right:20px">
        	            <select name="month_stay" id="month_stay" style="height:30px; width:85px">
                          <?php for($x = 0; $x<= 12; $x++){ ?>
                          <option value="<?php echo $x; ?>" <?php if($get_add['month_stay'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
                          <?php } ?>
                        </select>
        	          </span></td>
        	          <td width="93" style="padding-left:5px; padding-right:20px">Months</td>
        	          <td width="92"><span style="padding-left:5px; padding-right:20px"><span style="padding-right:20px">
   	              	  <select name="year_stay" id="year_stay" style="height:30px; width:85px" value="<?php echo $get_add['year_stay']; ?>">
                        <?php for($x = 0; $x<= 80; $x++){ ?>
							<option value="<?php echo $x; ?>" <?php if($get_add['year_stay'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
						<?php } ?>  
                      </select>
       	              </span>      	            </span></td>
        	          <td width="135" style="padding-left:5px; padding-right:20px">Years</td>
      	          </tr>
      	        </table></td>
        	      <td align="left" style="padding-right:10px">Year of Stay</td>
        	      <td><table id="radio_table10">
        	        <tr>
        	          <td width="85"><span style="padding-right:20px">
        	           <span style="padding-left:5px; padding-right:20px">
        	           <select name="m_month_stay" id="m_month_stay" style="height:30px; width:85px" value="<?php echo $get_add['m_month_stay']; ?>">
                       <?php for($x = 0; $x<= 12; $x++){ ?>
							<option value="<?php echo $x; ?>" <?php if($get_add['m_month_stay'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
						<?php } ?> 
                       </select>
        	           </span></span></td>
        	          <td width="59" style="padding-left:5px; padding-right:20px">Months</td>
        	          <td width="95"><span style="padding-right:20px">
        	            <select name="m_year_stay" id="m_year_stay" style="height:30px; width:85px" value="<?php echo $get_add['m_year_stay']; ?>">
						<?php for($x = 0; $x<= 80; $x++){ ?>
							<option value="<?php echo $x; ?>" <?php if($get_add['m_year_stay'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
						<?php } ?>                       
                        </select>
        	          </span></td>
        	          <td width="71" style="padding-left:5px; padding-right:20px">Years</td>
      	          </tr>
      	        </table></td>
       	        </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>&nbsp;</td>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>&nbsp;</td>
       	        </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>&nbsp;</td>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
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
    	<td>&nbsp;</td>
    </tr>
    <tr height="35">
    	<td>
        	<table width="100%">
            	<tr>
                	<td><!--<input type="submit" name="apply_loan" id="apply_loan" value="">--></td>
                    <td align="right"><input type="submit" name="apply_loan" id="apply_loan" value="">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
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

$('#dob').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
	if((document.getElementById('name').value != '' && document.getElementById('mobile_contact').value != '' && document.getElementById('nric').value != ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		if((document.getElementById('name').value == '' || document.getElementById('nric').value != ''))
		{
			$('html, body').animate({scrollTop:150}, 'fast');
			var msg = "<div class='error'>Full Name & NRIC must be filled!</div>";
			$('#message').empty();
			$('#message').append(msg); 
			$('#message').html();
			return false;
		}
		else
		{
			$('#message').empty();
		}
		
		if((document.getElementById('mobile_contact').value == ''))
		{
			$('html, body').animate({scrollTop:150}, 'fast');
			var msg = "<div class='error'>Mobile Contact must be filled!</div>";
			$('#message').empty();
			$('#message').append(msg2); 
			$('#message').html();
			return false;
		}
		else
		{
			$('#message').empty();
		}
	}
}
function apic()
{
	document.getElementById("others_ic2").disabled = false;
}

function reset_others()
{

  document.getElementById('others_ic').checked = reset;
  document.getElementById('others_ic').checked = false;
  document.getElementById('others_ic2').value = "";
  document.getElementById('others_ic2').disabled = true;

}

function checkIC(str)
{
	if (str.length==0)
	  { 
	  return;
	  }
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var ajaxDisplay = xmlhttp.responseText;
			var res=ajaxDisplay.split("#");
			var err = res[0];
			
			if(err != '')
			{
				var msg = "<div class='error'>" + res[0] + "</div>";
				$('#message').empty();
				$('#message').append(msg); 
				$('#message').html();
				document.getElementById('nric').value = '';
				document.getElementById('nric').focus();
			}else
			{
				$('#message').empty();
				document.getElementById('dob').value = res[1];
			}
		}
	  }
	  
	xmlhttp.open("GET","checkIC.php?ic="+escape(str),true);
	xmlhttp.send();
}

$(function() {

	$.mask.definitions['~'] = "[+-]";

	$("#nric").mask("999999-99-9999");

});
function checkcode(str)
{
	
	$code2 = str;
	if (str.length==0)
	  { 
	  return;
	  }
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var ajaxDisplay = xmlhttp.responseText;
			var res=ajaxDisplay.split("#");
			var err = res[0];
			
			if(err != '')
			{
				var msg = "<div class='error'>" + res[0] + "</div>";
				$('#message').empty();
				$('#message').append(msg); 
				$('#message').html();
				document.getElementById('customercode2').value = '';
				document.getElementById('customercode2').focus();
			}else
			{
				$('#message').empty();
			}
		}
	  }
	  
	xmlhttp.open("GET","checkCode.php?code2="+escape(str),true);
	xmlhttp.send();
}

function isNumberKey(evt)

{

var charCode = (evt.which) ? evt.which : evt.keyCode;

if(charCode==8 || charCode==13|| charCode==99|| charCode==118 || charCode==46)
 {    return true;  }
if (charCode > 31 && (charCode < 48 || charCode > 57))
{  return false; }
return true;
}
function sameR(v)
{
	if(document.getElementById("sameaddress").checked)
	{
		$add1 = document.getElementById('address1').value;
		$add2 = document.getElementById('address2').value;
		$add3 = document.getElementById('address3').value;
		$postcode = document.getElementById('postcode').value;
		$city = document.getElementById('city').value;
		$state = document.getElementById('state').value;
		$month_s = document.getElementById('month_stay').value;
		$year_s = document.getElementById('year_stay').value;
		if(document.getElementById('residence1').checked == true)
		{
			document.getElementById('m_residence1').checked = true;
		}else
		if(document.getElementById('residence2').checked == true)
		{
			document.getElementById('m_residence2').checked = true;
		}else
		if(document.getElementById('residence3').checked == true)
		{
			document.getElementById('m_residence3').checked = true;
		}else
		if(document.getElementById('residence4').checked == true)
		{
			document.getElementById('m_residence4').checked = true;
		}else
		if(document.getElementById('residence5').checked == true)
		{
			document.getElementById('m_residence5').checked = true;
		}
		else
		{
		
		}
		
		document.getElementById('m_address1').value = $add1;
		document.getElementById('m_address2').value = $add2;
		document.getElementById('m_address3').value = $add3;
		document.getElementById('m_postcode').value = $postcode;
		document.getElementById('m_city').value = $city;
		document.getElementById('m_state').value = $state;
		document.getElementById('m_month_stay').value = $month_s;
		document.getElementById('m_year_stay').value = $year_s;
		
		
	}else
	{
		document.getElementById('m_address1').value = '';
		document.getElementById('m_address2').value = '';
		document.getElementById('m_address3').value = '';
		document.getElementById('m_postcode').value = '';
		document.getElementById('m_city').value = '';
		document.getElementById('m_state').value = '';
		document.getElementById('m_month_stay').value = '';
		document.getElementById('m_year_stay').value = '';
		document.getElementById('m_residence1').checked = false;
		document.getElementById('m_residence2').checked = false;
		document.getElementById('m_residence3').checked = false;
		document.getElementById('m_residence4').checked = false;
		document.getElementById('m_residence5').checked = false;
	}
}

function readURL(input, location) {
	if (input.files && input.files[0]) {
    	var reader = new FileReader();

        reader.onload = function (e) {
        	$('#' + location)
            	.attr('src', e.target.result)
            };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>