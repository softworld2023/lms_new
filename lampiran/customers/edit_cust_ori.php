<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];

$sql = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
$get_q = mysql_fetch_assoc($sql);

$sql2 = mysql_query("SELECT * FROM customer_address WHERE customer_id = '".$id."'");
$get_add = mysql_fetch_assoc($sql2);

$sql3 = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$id."'");
$get_c = mysql_fetch_assoc($sql3);

$sql4 = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$id."'");
$get_f = mysql_fetch_assoc($sql4);

$sql5 = mysql_query("SELECT * FROM customer_emergency WHERE customer_id = '".$id."'");
$get_e = mysql_fetch_assoc($sql5);

$sql6 = mysql_query("SELECT * FROM customer_spouse WHERE customer_id = '".$id."'");
$get_s = mysql_fetch_assoc($sql6);

$sql7 = mysql_query("SELECT * FROM customer_relative WHERE customer_id = '".$id."'");

$sql8 = mysql_query("SELECT * FROM customer_account WHERE customer_id = '".$id."'");
$get_a = mysql_fetch_assoc($sql8);
?>
<script src="../include/js/jquery.maskedinput.min.js" type="text/javascript"></script>
<style>

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

.table_form tr td
{
	height:35px;
}

#radio_table tr td
{
	height:25px;
}
#cancel_loan
{
	background:url(../img/apply-loan/cancel-loan-btn.jpg);
	width:145px;
	height:30px;
	border:none;
	cursor:pointer;
}
#cancel_loan:hover
{
	background:url(../img/apply-loan/cancel-loan-btn-roll-over.jpg);
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
#edit_cust
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#edit_cust:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}

.fileUpload {
	position: relative;
	overflow: hidden;
	width:73px;
	height:22px;
	background:url(../img/browse.JPG) no-repeat;
}
.fileUpload input.upload {
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
</style>
<center>
<form action="action.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $id; ?>">
<table width="1280" id="list_table">
    <tr>
    	<td>
			<table>
				<tr>
					<td>Created By: <?php if($get_q['staff_name'] != '') { echo $get_q['staff_name']; } else { echo 'System Admin'; } ?></td>
					<td><?php if($get_q['created_date'] != '0000-00-00') { echo "On: ".date('d-m-Y', strtotime($get_q['created_date'])); } ?></td>
				</tr>
				<?php if($get_q['update_date'] != '0000-00-00') { ?>
				<tr>
					<td>Last Update By: <?php echo $get_q['staff_name']; ?></td>
					<td><?php if($get_q['update_date'] != '0000-00-00') { echo "On: ".date('d-m-Y', strtotime($get_q['update_date'])); } ?></td>
				</tr>
				<?php } ?>
			</table>
		</td>
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
                  <td align="left" style="padding-right:10px">Customer Code</td>
                  <td><table id="radio_table3">
                      <tr>
                        <td width="79"><input type="text" name="customercode2" id="customercode2" style="width:60px" onblur="checkcode(this.value)" value="<?php if($get_q['customercode2'] != '') { echo $get_q['customercode2']; } ?>" /></td>
                        <td width="117" style="padding-left:5px; padding-right:20px"><span style="padding-right:10px">Recruiter</span></td>
                        <td width="196" style="padding-left:5px; padding-right:20px"><label>
                          <select name="recruitor" id="recruitor" style="height:30px; width:85px">
                            <option value=""></option>
                            <?php 
								  	$recruitor_q = mysql_query("SELECT * FROM user WHERE branch_id = '".$_SESSION['login_branchid']."'");
									while($recruitor = mysql_fetch_assoc($recruitor_q))
									{
								  ?>
                            <option value="<?php echo $recruitor['fullname']; ?>" <?php if($get_q['recruitor'] == $recruitor['fullname']) { echo 'selected'; } ?>><?php echo $recruitor['fullname']; ?></option>
                            <?php
								  	}
								  ?>
                          </select>
                        </label></td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Customer Picture</td>
                  <td width="31%" rowspan="4"><?php if($get_q['cust_pic'] != '') { ?>
                      <img src="<?php echo "../files/customer/".$id."/".$get_q['cust_pic']; ?>" name="cust_pic2" id="cust_pic2" style="height:115px; width:115px" />
                      <?php } else { ?>
                      <img name="cust_pic2" id="cust_pic2" style="height:115px; width:115px" />
                      <?php } ?>
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
                        <td><input type="radio" name="title" id="title2" value="Mr." <?php if($get_q['title'] == 'Mr.') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Mr.</td>
                        <td><input type="radio" name="title" id="title2" value="Miss" <?php if($get_q['title'] == 'Miss') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Miss</td>
                        <td><input type="radio" name="title" id="title2" value="Madam" <?php if($get_q['title'] == 'Madam') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Madam</td>
                        <td><input type="radio" name="title" id="title2" value="Dr." <?php if($get_q['title'] == 'Dr') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Dr.</td>
                        <td><input type="radio" name="title" id="title2" value="Others" <?php if($get_q['title'] == 'Others') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Others</td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Full Name (as per I/C)</td>
                  <td><input type="text" name="name" id="name" style="width:300px" tabindex="1" value="<?php echo $get_q['name']; ?>" /></td>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">New I/C</td>
                  <td><input type="text" name="nric" id="nric" tabindex="1" value="<?php echo $get_q['nric']; ?>" onblur="checkIC(this.value)" /></td>
                  <td width="2%" align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="14%" align="left" style="padding-right:10px">&nbsp;</td>
                  <td><div class="fileUpload">
                    <input type="file" name="cust_pic" id="cust_pic" class="upload" onchange="readURL(this, 'cust_pic2');" />
                  </div></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Old I/C</td>
                  <td><input type="text" name="oldic" id="oldic" tabindex="1" value="<?php echo $get_q['old_ic']; ?>" /></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Date of Birth</td>
                  <td><input type="text" name="dob" id="dob" tabindex="1" value="<?php if($get_q['dob'] != '0000-00-00') { echo $get_q['dob']; } ?>" /></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Others</td>
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
                  <td><input type="text" name="mobile_contact" id="mobile_contact" tabindex="4" value="<?php echo $get_add['mobile_contact']; ?>" /></td>
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
                  <td><input type="text" name="nationality" id="nationality" tabindex="1" style="width:300px" value="<?php echo $get_q['nationality']; ?>" /></td>
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
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">No. of Dependents</td>
                  <td><input type="text" name="no_dependents" id="no_dependents" tabindex="2" value="<?php echo $get_q['no_dependents']; ?>" /></td>
                </tr>
                <tr>
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
                </tr>
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
                  <option value="Johor" <?php if($get_add['state'] == 'Johor') { echo 'selected'; } ?>>Johor</option>
                  <option value="Kedah" <?php if($get_add['state'] == 'Kedah') { echo 'selected'; } ?>>Kedah</option>
                  <option value="Kelantan" <?php if($get_add['state'] == 'Kelantan') { echo 'selected'; } ?>>Kelantan</option>
                  <option value="Malacca" <?php if($get_add['state'] == 'Malacca') { echo 'selected'; } ?>>Melaka</option>
                  <option value="Negeri Sembilan" <?php if($get_add['state'] == 'Negeri Sembilan') { echo 'selected'; } ?>>Negeri Sembilan</option>
                  <option value="Pahang" <?php if($get_add['state'] == 'Pahang') { echo 'selected'; } ?>>Pahang</option>
                  <option value="Perak" <?php if($get_add['state'] == 'Perak') { echo 'selected'; } ?>>Perak</option>
                  <option value="Perlis" <?php if($get_add['state'] == 'Perlis') { echo 'selected'; } ?>>Perlis</option>
                  <option value="Penang" <?php if($get_add['state'] == 'Penang') { echo 'selected'; } ?>>Penang</option>
                  <option value="Sabah" <?php if($get_add['state'] == 'Sabah') { echo 'selected'; } ?>>Sabah</option>
                  <option value="Sarawak" <?php if($get_add['state'] == 'Sarawak') { echo 'selected'; } ?>>Sarawak</option>
                  <option value="Selangor" <?php if($get_add['state'] == 'Selangor') { echo 'selected'; } ?>>Selangor</option>
                  <option value="Terengganu" <?php if($get_add['state'] == 'Terengganu') { echo 'selected'; } ?>>Terengganu</option>
				  <option value="Wilayah Persekutuan Kuala Lumpur" <?php if($get_add['state'] == 'Wilayah Persekutuan Kuala Lumpur') { echo 'selected'; } ?>>Wilayah Persekutuan Kuala Lumpur</option>
				  <option value="Wilayah Persekutuan Labuan" <?php if($get_add['state'] == 'Wilayah Persekutuan Labuan') { echo 'selected'; } ?>>Wilayah Persekutuan Labuan</option>
				  <option value="Wilayah Persekutuan Putrajaya" <?php if($get_add['state'] == 'Wilayah Persekutuan Putrajaya') { echo 'selected'; } ?>>Wilayah Persekutuan Putrajaya</option>
                </select>				  </td>
        	      <td align="left" style="padding-right:10px">State</td>
        	      <td>
				  <select name="m_state" id="m_state" style="height:30px; width:85px">
                  <option value="Johor" <?php if($get_add['m_state'] == 'Johor') { echo 'selected'; } ?>>Johor</option>
                  <option value="Kedah" <?php if($get_add['m_state'] == 'Kedah') { echo 'selected'; } ?>>Kedah</option>
                  <option value="Kelantan" <?php if($get_add['m_state'] == 'Kelantan') { echo 'selected'; } ?>>Kelantan</option>
                  <option value="Malacca" <?php if($get_add['m_state'] == 'Malacca') { echo 'selected'; } ?>>Melaka</option>
                  <option value="Negeri Sembilan" <?php if($get_add['m_state'] == 'Negeri Sembilan') { echo 'selected'; } ?>>Negeri Sembilan</option>
                  <option value="Pahang" <?php if($get_add['m_state'] == 'Pahang') { echo 'selected'; } ?>>Pahang</option>
                  <option value="Perak" <?php if($get_add['m_state'] == 'Perak') { echo 'selected'; } ?>>Perak</option>
                  <option value="Perlis" <?php if($get_add['m_state'] == 'Perlis') { echo 'selected'; } ?>>Perlis</option>
                  <option value="Penang" <?php if($get_add['m_state'] == 'Penang') { echo 'selected'; } ?>>Penang</option>
                  <option value="Sabah" <?php if($get_add['m_state'] == 'Sabah') { echo 'selected'; } ?>>Sabah</option>
                  <option value="Sarawak" <?php if($get_add['m_state'] == 'Sarawak') { echo 'selected'; } ?>>Sarawak</option>
                  <option value="Selangor" <?php if($get_add['m_state'] == 'Selangor') { echo 'selected'; } ?>>Selangor</option>
                  <option value="Terengganu" <?php if($get_add['m_state'] == 'Terengganu') { echo 'selected'; } ?>>Terengganu</option>
				  <option value="Wilayah Persekutuan Kuala Lumpur" <?php if($get_add['m_state'] == 'Wilayah Persekutuan Kuala Lumpur') { echo 'selected'; } ?>>Wilayah Persekutuan Kuala Lumpur</option>
				  <option value="Wilayah Persekutuan Labuan" <?php if($get_add['m_state'] == 'Wilayah Persekutuan Labuan') { echo 'selected'; } ?>>Wilayah Persekutuan Labuan</option>
				  <option value="Wilayah Persekutuan Putrajaya" <?php if($get_add['m_state'] == 'Wilayah Persekutuan Putrajaya') { echo 'selected'; } ?>>Wilayah Persekutuan Putrajaya</option>
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
   	              	  <select name="year_stay" id="year_stay" style="height:30px; width:85px">
                        <?php for($x = 0; $x<= 35; $x++){ ?>
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
        	           <select name="m_month_stay" id="m_month_stay" style="height:30px; width:85px">
                       <?php for($x = 0; $x<= 12; $x++){ ?>
							<option value="<?php echo $x; ?>" <?php if($get_add['m_month_stay'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
						<?php } ?> 
                       </select>
        	           </span></span></td>
        	          <td width="59" style="padding-left:5px; padding-right:20px">Months</td>
        	          <td width="95"><span style="padding-right:20px">
        	            <select name="m_year_stay" id="m_year_stay" style="height:30px; width:85px">
						<?php for($x = 0; $x<= 35; $x++){ ?>
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
    <tr>
    	<th height="23">My Employment Details</th>
    </tr>
    <tr>
    	<td>
        <center>
        	<table width="85%" id="table_form">
            	<tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            	</tr>
                <tr>
                	<td width="15%" align="left" style="padding-right:10px">Company Name</td>
                <td width="40%"><input type="text" name="company" id="company" style="width:300px" tabindex="5" value="<?php echo $get_c['company']; ?>" /></td>
                	<td width="14%" align="left" style="padding-right:10px">Company Address</td>
                	<td><input type="text" name="c_address1" id="c_address1" style="width:300px" tabindex="5" value="<?php echo $get_c['c_address1']; ?>" /></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Nature of Business</td>
                    <td><input type="text" name="nature_business" id="nature_business" tabindex="5" value="<?php echo $get_c['nature_business']; ?>" /></td>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                	<td><input type="text" name="c_address2" id="c_address2" style="width:300px" tabindex="5" value="<?php echo $get_c['c_address2']; ?>" /></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Department</td>
                <td><input type="text" name="department" id="department" tabindex="5" value="<?php echo $get_c['department']; ?>" /></td>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                	<td><input type="text" name="c_address3" id="c_address3" style="width:300px" tabindex="5" value="<?php echo $get_c['c_address3']; ?>" /></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Position</td>
                <td><input type="text" name="position" id="position" tabindex="5" value="<?php echo $get_c['position']; ?>" /></td>
                <td width="14%" align="left" style="padding-right:10px">Postcode</td>
                <td width="31%">
					<table style="border-collapse:collapse">
						<tr>
							<td>
								<input type="text" name="c_postcode" id="c_postcode" tabindex="6" style="width:70px" value="<?php echo $get_c['c_postcode']; ?>" />							</td>
							<td style="padding-right:15px; padding-left:15px">City</td>
							<td><input type="text" name="c_city" id="c_city" tabindex="6" value="<?php echo $get_c['c_city']; ?>" /></td>
						</tr>
					</table>				</td>
               	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Employment</td>
                <td><table id="radio_table7">
                  <tr>
                    <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Self Employed" <?php if($get_c['c_workingtype'] == 'Self Employed') { echo 'checked'; } ?> onclick="wt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Self Employed</td>
                    <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Private" <?php if($get_c['c_workingtype'] == 'Private') { echo 'checked'; } ?> onclick="wt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Private</td>
                    <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Government" <?php if($get_c['c_workingtype'] == 'Government') { echo 'checked'; } ?> onclick="wt2()" /></td>
                    <td style="padding-left:5px">Government</td>
                  </tr>
                </table></td>
                <td align="left" style="padding-right:10px">State</td>
                <td><input type="text" name="c_state" id="c_state" tabindex="6" value="<?php echo $get_c['c_state']; ?>" /></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td><table id="radio_table8">
                    <tr>
                      <td><input type="radio" name="c_workingtype" id="c_workingtype2" value="Retired" <?php if($get_c['c_workingtype'] == 'Retired') { echo 'checked'; } ?> onclick="wt2()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Retired</td>
                      <td><input type="radio" name="c_workingtype" id="c_workingtype2" value="others" <?php if($get_c['c_workingtype'] != 'Government' && $get_c['c_workingtype'] != 'Retired' && $get_c['c_workingtype'] != 'Private' && $get_c['c_workingtype'] != 'Self Employed' && $get_c['c_workingtype'] != '') { echo 'checked'; } ?> onclick="wt()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Others</td>
					  <td><input type="text" name="other_wt" id="other_wt" <?php if($get_c['c_workingtype'] != 'Government' && $get_c['c_workingtype'] != 'Retired' && $get_c['c_workingtype'] != 'Private' && $get_c['c_workingtype'] != 'Self Employed' && $get_c['c_workingtype'] != '') { ?>value="<?php echo $get_c['c_workingtype']; ?>"<?php } else { echo 'disabled'; }?> /></td>
                    </tr>
                  </table></td>
                	<td align="left" style="padding-right:10px">Company Phone</td>
                    <td><input type="text" name="c_contactno" id="c_contactno" tabindex="6" value="<?php echo $get_c['c_contactno']; ?>" />
                      <span style="padding-right:10px">- Ext.
                      <input type="text" name="c_ext" id="c_ext" tabindex="6" style="width:40px" value="<?php echo $get_c['c_ext']; ?>" />
                      </span></td>
               	</tr>
                <tr>
                  <td align="left" style="padding-right:10px">Years of Working</td>
                  <td>
				  	<table id="radio_table9">
        	        <tr>
        	          <td width="87"><span style="padding-left:5px; padding-right:20px">
        	            <select name="c_monthworking" id="c_monthworking" style="height:30px; width:85px">
                          <?php for($x = 0; $x<= 12; $x++){ ?>
                          <option value="<?php echo $x; ?>" <?php if($get_c['c_monthworking'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
                          <?php } ?>
                        </select>
        	          </span></td>
        	          <td width="93" style="padding-left:5px; padding-right:20px">Months</td>
        	          <td width="92"><span style="padding-left:5px; padding-right:20px"><span style="padding-right:20px">
   	              	  <select name="c_yearworking" id="c_yearworking" style="height:30px; width:85px">
                        <?php for($x = 0; $x<= 60; $x++){ ?>
							<option value="<?php echo $x; ?>" <?php if($get_c['c_yearworking'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
						<?php } ?>  
                      </select>
       	              </span>      	            </span></td>
        	          <td width="135" style="padding-left:5px; padding-right:20px">Years</td>
      	          </tr>
      	        </table>
                	<td align="left" style="padding-right:10px">Email Address</td>
                    <td><input type="text" name="c_email" id="c_email" style="width:300px" tabindex="6" value="<?php echo $get_c['c_email']; ?>" /></td>
               	</tr>
                <tr>
                	<td>&nbsp;</td>
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
    <tr>
    	<th height="23">My Financial Particulars</th>
  	</tr>
    <tr>
    	<td>
        <center>
        	<table width="85%" id="table_form">
            	<tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            	</tr>
                <tr>
                	<td width="18%" style="padding-right:10px">Monthly Nett Salary</td>
                    <td width="27%"><input type="text" name="net_salary" id="net_salary" placeholder="RM" class="currency"  tabindex="7" value="<?php echo $get_f['net_salary']; ?>" /></td>
                	<td width="20%" style="padding-right:10px">House Installment Repayment</td>
                    <td width="35%"><input type="text" name="house_installment" id="house_installment" placeholder="RM" class="currency"  tabindex="8" value="<?php echo $get_f['house_installment']; ?>"></td>
            	</tr>
                <tr>
                	<td style="padding-right:10px">Total Credit Card Repayment</td>
                    <td><input type="text" name="total_cc" id="total_cc" placeholder="RM" class="currency"  tabindex="7" value="<?php echo $get_f['total_cc']; ?>" /></td>
                    <td style="padding-right:10px">Monthly Personal Loan Repayment</td>
                    <td><input type="text" name="personal_loan" id="personal_loan" placeholder="RM" class="currency"  tabindex="8" value="<?php echo $get_f['personal_loan']; ?>"></td>
                </tr>
                <tr>
                	<td style="padding-right:10px">Car Installment Repayment</td>
                    <td><input type="text" name="car_installment" id="car_installment" placeholder="RM" class="currency"  tabindex="7" value="<?php echo $get_f['car_installment']; ?>" /></td>
                    <td style="padding-right:10px">Bank Loan</td>
                    <td><input type="text" name="bank_loan" id="bank_loan" placeholder="RM" class="currency"  tabindex="8" value="<?php echo $get_f['bank_loan']; ?>"></td>
                </tr>
                <tr>
                	<td><span style="padding-right:10px">Remarks</span></td>
                    <td colspan="3"><textarea name="remarks" id="remarks" style="width:800px; height:50px" tabindex="8" ><?php echo $get_f['remarks']; ?></textarea></td>
                </tr>
            </table>
        </center>
        </td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<th height="23">Emergency Contact Person</th>
  	</tr>
    <tr>
    	<td>
        <center>
        	<table width="85%" id="table_form">
            	<tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            	</tr>
                <tr>
                	<td width="18%" style="padding-right:10px">Full Name </td>
                    <td width="27%"><input type="text" name="e_contactperson" id="e_contactperson" style="width:300px" tabindex="9" value="<?php echo $get_e['e_contactperson']; ?>" /></td>
                	<td width="20%" style="padding-right:10px">&nbsp;</td>
                    <td width="35%">&nbsp;</td>
            	</tr>
                <tr>
                	<td style="padding-right:10px">Mobile Number</td>
                    <td><input type="text" name="e_contactno" id="e_contactno" tabindex="9" value="<?php echo $get_e['e_contactno']; ?>" /></td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td style="padding-right:10px">Home Phone </td>
                    <td><input type="text" name="e_officeno" id="e_officeno" tabindex="10" value="<?php echo $get_e['e_officeno']; ?>" /></td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">Relationship</td>
                  <td><input type="text" name="e_relationship" id="e_relationship" tabindex="9" value="<?php echo $get_e['e_relationship']; ?>" /></td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
            </table>
        </center>
        </td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<th height="23">My Spouse's Detail</th>
  	</tr>
    <tr>
    	<td>
        <center>
        	<table width="85%" id="table_form">
            	<tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            	</tr>
                <tr>
                	<td width="18%" align="left" style="padding-right:10px">Full Name (as per I/C) </td>
                <td width="32%"><input type="text" name="s_name" id="s_name" style="width:300px" tabindex="11" value="<?php echo $get_s['s_name']; ?>" /></td>
                	<td width="19%" align="left" style="padding-right:10px">Company Name</td>
                    <td width="31%"><input type="text" name="s_company" id="s_company" style="width:300px" tabindex="12" value="<?php echo $get_s['s_company']; ?>" /></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">New I/C</td>
                <td><input type="text" name="s_nric" id="s_nric" tabindex="11" value="<?php echo $get_s['s_nric']; ?>" /></td>
                    <td align="left" style="padding-right:10px">Position</td>
                    <td><input type="text" name="s_workas" id="s_workas" tabindex="12" value="<?php echo $get_s['s_workas']; ?>" /></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Old I/C</td>
           	        <td><input type="text" name="s_oldic" id="s_oldic" tabindex="11" value="<?php echo $get_s['s_oldic']; ?>" /></td>
           	        <td align="left" style="padding-right:10px">Employment</td>
                <td><table id="radio_table7">
                  <tr>
                    <td><input type="radio" name="s_workingtype" id="s_workingtype" value="Self Employed" <?php if($get_s['s_workingtype'] == 'Self Employed') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Self Employed</td>
                    <td><input type="radio" name="s_workingtype" id="s_workingtype" value="Private" <?php if($get_s['s_workingtype'] == 'Private') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Private</td>
                    <td><input type="radio" name="s_workingtype" id="s_workingtype" value="Government" <?php if($get_s['s_workingtype'] == 'Government') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px">Government</td>
                  </tr>
                </table></td>
              </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Others</td>
        	      <td><table id="radio_table4">
                    <tr>
                      <td><input type="radio" name="s_others_ic" id="s_others_ic" value="police" <?php if($get_s['s_others_ic'] == 'police') { echo 'checked'; } ?> onclick="apic2();" /></td>
                      <td style="padding-left:5px; padding-right:20px">Police</td>
                      <td><input type="radio" name="s_others_ic" id="s_others_ic" value="army" <?php if($get_s['s_others_ic'] == 'army') { echo 'checked'; } ?> onclick="apic2();" /></td>
                      <td style="padding-left:5px; padding-right:20px">Army</td>
                      <td><input type="text" name="s_others_ic2" id="s_others_ic2" <?php if($get_s['s_others_ic2'] != '') { ?>value="<?php echo $get_s['s_others_ic2']; ?>"<?php } else { echo 'disabled'; }?> /></td>
                    </tr>
                  </table></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>
				  <table id="radio_table8">
                    <tr>
                      <td><input type="radio" name="s_workingtype" id="s_workingtype2" value="Retired" <?php if($get_s['s_workingtype'] == 'Retired') { echo 'checked'; } ?> onclick="swt2()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Retired</td>
                      <td><input type="radio" name="s_workingtype" id="s_workingtype2" value="others" <?php if($get_s['s_workingtype'] != 'Government' && $get_s['s_workingtype'] != 'Retired' && $get_c['s_workingtype'] != 'Private' && $get_s['s_workingtype'] != 'Self Employed' && $get_s['s_workingtype'] != '') { echo 'checked'; } ?> onclick="swt()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Others</td>
					  <td><input type="text" name="s_other_wt" id="s_other_wt" <?php if($get_s['s_workingtype'] != 'Government' && $get_s['s_workingtype'] != 'Retired' && $get_s['s_workingtype'] != 'Private' && $get_s['s_workingtype'] != 'Self Employed' && $get_c['c_workingtype'] != '') { ?>value="<?php echo $get_s['s_workingtype']; ?>"<?php } else { echo 'disabled'; }?> /></td>
                    </tr>
                  </table>
				  </td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Mobile Phone </td>
                  <td><input type="text" name="s_mobile" id="s_mobile" tabindex="12" value="<?php echo $get_s['s_mobile']; ?>" /></td>
                  <td align="left" style="padding-right:10px">Years of Working </td>
                  <td>
				  	<table id="radio_table9">
        	        <tr>
        	          <td width="87"><span style="padding-left:5px; padding-right:20px">
        	            <select name="s_monthworking" id="s_monthworking" style="height:30px; width:85px">
                          <?php for($x = 0; $x<= 12; $x++){ ?>
                          <option value="<?php echo $x; ?>" <?php if($get_s['s_monthworking'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
                          <?php } ?>
                        </select>
        	          </span></td>
        	          <td width="93" style="padding-left:5px; padding-right:20px">Months</td>
        	          <td width="92"><span style="padding-left:5px; padding-right:20px"><span style="padding-right:20px">
   	              	  <select name="s_yearworking" id="s_yearworking" style="height:30px; width:85px">
                        <?php for($x = 0; $x<= 60; $x++){ ?>
							<option value="<?php echo $x; ?>" <?php if($get_s['s_yearworking'] == $x) { echo 'selected'; } ?>><?php echo $x; ?></option>
						<?php } ?>  
                      </select>
       	              </span>      	            </span></td>
        	          <td width="135" style="padding-left:5px; padding-right:20px">Years</td>
      	          </tr>
      	        </table>
				  </td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Home Phone </td>
                  <td><input type="text" name="s_officeno" id="s_officeno" tabindex="11" value="<?php echo $get_s['s_officeno']; ?>" /></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              <tr>
                	<td align="left" style="padding-right:10px">Email Address </td>
       	    		<td><input type="text" name="s_email" id="s_email" value="<?php echo $get_s['s_email']; ?>" style="width:300px" /></td>
                    <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">Relationship</td>
                  	<td valign="top"><input type="text" name="s_relationship" id="s_relationship" style="width:300px" tabindex="11" value="<?php echo $get_s['s_relationship']; ?>" /></td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
            </table>
        </center>
        </td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<th height="23">My Personal Reference (Brother/Sister/Relative)</th>
    </tr>
    <tr>
    	<td>
        	<center>
            	<table width="95%" id="relativetbl" class="table_form" style="border:none; border-collapse:collapse">
                <thead>
                	<tr>
                    	<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td width="5%">&nbsp;</td>
                    </tr>
                	<tr>
                    	<th style="background:#333; color:#FFF">Name</th>
                        <th style="background:#333; color:#FFF">Relationship</th>
                        <th style="background:#333; color:#FFF">Work As</th>
                        <th style="background:#333; color:#FFF">Contact Number</th>
                        <th style="background:#333; color:#FFF">Address</th>
                        <th style="background:#333; color:#FFF">&nbsp;</th>
                    </tr>
               	</thead>
                <tbody id="add">
                <?php
					$ctr_r = 0;
					while($get_r = mysql_fetch_assoc($sql7))
					{
						$ctr_r++;
				?>
                    <tr id="row_<?php echo $ctr_r; ?>">
                    	<td><input type="text" name="r_name_<?php echo $ctr_r; ?>" id="r_name_<?php echo $ctr_r; ?>" style="width:300px;" tabindex="13" value="<?php echo $get_r['r_name']; ?>"></td>
                        <td><input type="text" name="r_relationship_<?php echo $ctr_r; ?>" id="r_relationship_<?php echo $ctr_r; ?>" tabindex="13" value="<?php echo $get_r['r_relationship']; ?>"></td>
                        <td><input type="text" name="r_workas_<?php echo $ctr_r; ?>" id="r_workas_<?php echo $ctr_r; ?>" tabindex="13" value="<?php echo $get_r['r_workas']; ?>"></td>
                        <td><input type="text" name="r_contact_<?php echo $ctr_r; ?>" id="r_contact_<?php echo $ctr_r; ?>" tabindex="13" value="<?php echo $get_r['r_contact']; ?>"></td>
                        <td><input type="text" name="r_address_<?php echo $ctr_r; ?>" id="r_address_<?php echo $ctr_r; ?>" style="width:300px;" tabindex="13" value="<?php echo $get_r['r_address']; ?>"></td>
                        <td><img src="../img/customers/delete-icon.png" width="20" id="del_<?php echo $ctr_r; ?>" name="del_<?php echo $ctr_r; ?>" onclick="deleteRow('<?php echo $ctr_r; ?>');" style="cursor:pointer;"></td>
                    </tr>
                <?php 
					} 
				?>
				</tbody>
                    <tr>
                    	<td>
                        	<table>
                            	<tr>
                                	<td><input type="button" id="add" name="add" value="" onclick="addRow()" style="background:url(../img/apply-loan/add-btn.jpg); width:20px; height:20px; border:none; cursor:pointer" />&nbsp;</td>
                                    <td>Add</td>
                                </tr>
                            </table>
                        </td>
                        <td><input type="hidden" name="ctr" id="ctr" value="<?php echo $ctr_r; ?>" /></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<th height="23">Account Particular</th>
    </tr>
    <tr>
    	<td>
        <center>
        	<table width="85%" id="table_form">
            	<tr>
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            	</tr>
                <tr>
                	<td width="18%" align="left" style="padding-right:10px">Bank</td>
                    <td width="32%"><input type="text" name="a_bankname" id="a_bankname" style="width:300px" tabindex="14" value="<?php echo $get_a['a_bankname']; ?>" /></td>
                	<td width="16%" align="left" style="padding-right:10px">Application Form </td>
                    <td width="34%">
					<?php if($get_a['a_shoplotfile'] != '') { ?>
                    	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_shoplotfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_shoplotfile" id="prev_shoplotfile" value="<?php echo $get_a['a_shoplotfile']; ?>" />&nbsp;
                    <?php } ?>
                    <input type="file" name="a_shoplotfile" id="a_shoplotfile">					</td>
            	</tr>
                <tr>
                  <td width="18%" align="left" style="padding-right:10px">Branch</td>
                  <td width="32%"><input type="text" name="a_bankbranch" id="a_bankbranch" style="width:300px" tabindex="14" value="<?php echo $get_a['a_bankbranch']; ?>" /></td>
                	<td align="left" style="padding-right:10px">I/C</td>
                    <td><?php if($get_a['a_icfile'] != '') { ?>
                      <a href="<?php echo "../files/customer/".$id."/".$get_a['a_icfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                      <input type="hidden" name="prev_icfile" id="prev_icfile" value="<?php echo $get_a['a_icfile']; ?>" />
                      &nbsp;
                      <?php } ?>
                      <input type="file" name="a_icfile" id="a_icfile" /></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Account Number</td>
                  <td><input type="text" name="a_bankaccno" id="a_bankaccno" style="width:300px" tabindex="14" value="<?php echo $get_a['a_bankaccno']; ?>" /></td>
                	<td align="left" style="padding-right:10px">Bank Statement</td>
                    <td><?php if($get_a['a_bankfile'] != '') { ?>
                      <a href="<?php echo "../files/customer/".$id."/".$get_a['a_bankfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                      <input type="hidden" name="prev_bankfile" id="prev_bankfile" value="<?php echo $get_a['a_bankfile']; ?>" />
                      &nbsp;
                      <?php } ?>
                      <input type="file" name="a_bankfile" id="a_bankfile" /></td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px">Account Holder Name</td>
                <td><input type="text" name="a_name" id="a_name" style="width:300px" tabindex="14" value="<?php echo $get_a['a_name']; ?>" /></td>
                <td align="left" style="padding-right:10px">Salary Slips </td>
                    <td><?php if($get_a['a_payslipfile'] != '') { ?>
                      <a href="<?php echo "../files/customer/".$id."/".$get_a['a_payslipfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                      <input type="hidden" name="prev_payslipfile" id="prev_payslipfile" value="<?php echo $get_a['a_payslipfile']; ?>" />
                      &nbsp;
                      <?php } ?>
                      <input type="file" name="a_payslipfile" id="a_payslipfile" /></td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px">I.C Number</td>
                <td><input type="text" name="a_nric" id="a_nric" tabindex="14" value="<?php echo $get_a['a_nric']; ?>" /></td>
                <td align="left" style="padding-right:10px">ATM Card </td>
                    <td><?php if($get_a['a_atmfile'] != '') { ?>
                      <a href="<?php echo "../files/customer/".$id."/".$get_a['a_atmfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                      <input type="hidden" name="prev_atmfile" id="prev_atmfile" value="<?php echo $get_a['a_atmfile']; ?>" />
                      &nbsp;
                      <?php } ?>
                      <input type="file" name="a_atmfile" id="a_atmfile" /></td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px">Pay date</td>
                <td><input type="text" name="a_payday" id="a_payday" tabindex="14" value="<?php echo $get_a['a_payday']; ?>" /></td>
                	<td><span style="padding-right:10px">ATM Card Number</span></td>
                    <td><input type="text" name="a_pinno" id="a_pinno" tabindex="15" style="width:200" value="<?php echo $get_a['a_pinno']; ?>" /></td>
              </tr>
              <tr>
                	<td style="padding-right:10px">&nbsp;</td>
                  	<td>					  </td>
                    <td align="left" style="padding-right:10px">Mortgage</td>
                    <td><?php if($get_a['a_housefile'] != '') { ?>
                    	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_housefile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_housefile" id="prev_housefile" value="<?php echo $get_a['a_housefile']; ?>" />&nbsp;
                    <?php } ?>
                    <input type="file" name="a_housefile" id="a_housefile">                    </td>
              </tr>
              <tr>
                	<td colspan="2" style="padding-right:10px"><strong>TRANSFER TO ACCOUNT 2 </strong></td>
                	<td align="left" style="padding-right:10px">Others</td>
                    <td>
                    <?php if($get_a['a_landfile'] != '') { ?>
                    	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_landfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_landfile" id="prev_landfile" value="<?php echo $get_a['a_landfile']; ?>" />&nbsp;
                    <?php } ?>
                    <input type="file" name="a_landfile" id="a_landfile">                    </td>
              </tr>
              <tr>
                <td style="padding-right:10px">Bank</td>
                <td><input type="text" name="transfer_accountbank" id="transfer_accountbank" value="<?php echo $get_a['transfer_accountbank']; ?>" style="width:300px" /></td>
                <td align="left" style="padding-right:10px">&nbsp;</td>
                <td rowspan="4">
                  <table width="100%">
                    <tr>
                      <td>Left Thumb</td>
					    <td>Right Thumb</td>
					  </tr>
                    <tr>
                      <td width="50%">
                        <?php if($get_a['a_lefthand'] != '') { ?>
                        <img src="<?php echo "../files/customer/".$id."/".$get_a['a_lefthand']; ?>" name="lh_img" id="lh_img" style="height:115px; width:115px">
                        <?php } else { ?>
                        <img name="lh_img" id="lh_img" style="height:115px; width:115px">
                        <?php } ?>
                        <input type="hidden" name="prev_lefthand" id="prev_lefthand" value="<?php echo $get_a['a_lefthand']; ?>" />                                </td>
					    <td width="50%">
					      <?php if($get_a['a_righthand'] != '') { ?>
						    <img src="<?php echo "../files/customer/".$id."/".$get_a['a_righthand']; ?>" name="rh_img" id="rh_img" style="height:115px; width:115px">
					      <?php } else { ?>
						    <img name="rh_img" id="rh_img" style="height:115px; width:115px">
					      <?php } ?>
				        <input type="hidden" name="prev_righthand" id="prev_righthand" value="<?php echo $get_a['a_righthand']; ?>" />                                </td>
					  </tr>
                  </table>					</td>
              </tr>
              <tr>
                <td style="padding-right:10px">Account Number </td>
                <td><input type="text" name="transfer_accountno" id="transfer_accountno" value="<?php echo $get_a['transfer_accountno']; ?>" style="width:300px" /></td>
                <td align="left" style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-right:10px">Account Holder Name </td>
                <td><input type="text" name="transfer_accountholder" id="transfer_accountholder" value="<?php echo $get_a['transfer_accountholder']; ?>" style="width:300px" /></td>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px"><input type="hidden" name="a_date" id="a_date" tabindex="14" value="<?php echo $get_a['a_date']; ?>" /></td>
                <td></td>
                <td style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px">&nbsp;</td>
                <td></td>
                <td style="padding-right:10px">&nbsp;</td>
                <td>
					<table width="100%">
						<tr>
							<td width="50%"><div class="fileUpload"><input type="file" name="a_lefthand" id="a_lefthand" class="upload" onchange="readURL(this, 'lh_img');"></div></td>
							<td width="50%"><div class="fileUpload"><input type="file" name="a_righthand" id="a_righthand" class="upload" onchange="readURL(this, 'rh_img');"></div></td>
						</tr>
					</table>				</td>
              </tr>
			  <tr>
			  	<td style="padding-right:10px; padding-top:10px" valign="top">Remarks</td>
				<td colspan="3"><textarea name="a_remarks" id="a_remarks" style="width:800px; height:50px"><?php echo $get_a['a_remarks']; ?></textarea></td>
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
    	<td><center><input type="submit" name="edit_cust" id="edit_cust" value="">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='index.php'" value=""></center>
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
	$("input[type=text].currenciesOnly").live('keydown', currenciesOnly).live('blur', function () { $(this).formatCurrency(); });
});
function raceOther()
{
	var race = document.getElementById("p_race_other").value;
	document.getElementById("race_other").disabled = false;
	document.getElementById('race_other').focus();
	document.getElementById("race_other").value = race;
}
function raceOther2()
{
	document.getElementById("race_other").disabled = true;
	document.getElementById("race_other").value = '';
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

$('#a_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

/*$('#a_payday').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
*/
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

function checkForm()
{
	if((document.getElementById('name').value != '' && document.getElementById('nric').value != '' && document.getElementById('mobile_contact').value != ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		if((document.getElementById('name').value == '' || document.getElementById('nric').value == ''))
		{
			$('html, body').animate({scrollTop:150}, 'fast');
			var msg = "<div class='error'>Full Name and NRIC fields must be filled!</div>";
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
			$('html, body').animate({scrollTop:500}, 'fast');
			var msg2 = "<div class='error'>Mobile Contact must be filled!</div>";
			$('#message2').empty();
			$('#message2').append(msg2); 
			$('#message2').html();
			return false;
		}
		else
		{
			$('#message').empty();
		}
	}
}

function addRow()
{
	$ctr = $('#ctr').val() * 1 + 1;
	 
	var table = document.getElementById("add");
	var elem = document.createElement('tr');
	elem.id = "row_" + $ctr;
	table.appendChild(elem);	
	
	$('#' + elem.id).load("addRow.php", { inputs: $ctr} );
	
	$('#ctr').val($ctr);
}

function deleteRow(num)
{		
	var row = document.getElementById('row_'+num);
	document.getElementById('relativetbl').deleteRow(row.rowIndex);
	var ctr = document.getElementById('ctr').value;
	var ctrnew = ctr - 1;
	document.getElementById('ctr').value = ctrnew;
}

$(function() {

	$.mask.definitions['~'] = "[+-]";

	$("#nric").mask("999999-99-9999");
	$("#s_nric").mask("999999-99-9999");
	$("#a_nric").mask("999999-99-9999");
	$("#a_pinno").mask("9999 9999 9999 9999");

})
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
function apic()
{
	document.getElementById("others_ic2").disabled = false;
}
function apic2()
{
	document.getElementById("s_others_ic2").disabled = false;
}
Shadowbox.init();
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
</script>