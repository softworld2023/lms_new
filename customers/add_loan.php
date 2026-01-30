<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];
$_SESSION['custid'] = $id;

if (!file_exists('appform/'.$id)) 
{
	mkdir('appform/'.$id, 0777, true);
}

if (!file_exists('custic/'.$id)) 
{
	mkdir('custic/'.$id, 0777, true);
}

if (!file_exists('bankstat/'.$id)) 
{
	mkdir('bankstat/'.$id, 0777, true);
}

if (!file_exists('salaryslip/'.$id)) 
{
	mkdir('salaryslip/'.$id, 0777, true);
}

if (!file_exists('atmcard/'.$id)) 
{
	mkdir('atmcard/'.$id, 0777, true);
}

if (!file_exists('mortgage/'.$id)) 
{
	mkdir('mortgage/'.$id, 0777, true);
}

if (!file_exists('guarantorform/'.$id)) 
{
	mkdir('guarantorform/'.$id, 0777, true);
}

if (!file_exists('others/'.$id)) 
{
	mkdir('others/'.$id, 0777, true);
}

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
#add_loan
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_loan:hover
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
.fileUpload1 {	position: relative;
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
<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $id; ?>">
<table width="1280" id="list_table">
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
                        <td width="79">
						<input type="hidden" name="idReload" id="idReload" value="<?php echo $id; ?>" />
						<input type="text" name="customercode2" id="customercode2" style="width:60px" onblur="checkcode(this.value)" value="<?php if($get_q['customercode2'] != '') { echo $get_q['customercode2']; } ?>" /></td>
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
							<td>
								<?php if($get_q['cust_pic'] != '') { 
								$image_link = "../files/customer/".$id."/".$get_q['cust_pic'];
								?>
									<a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox"><img src="<?php echo "../files/customer/".$id."/".$get_q['cust_pic']; ?>" name="cust_pic2" id="cust_pic2" style="height:115px; width:115px"></a>
								<?php } else { ?>
									<img name="cust_pic2" id="cust_pic2" style="height:115px; width:115px">
								<?php } ?>
							</td>
							<td>
								<?php if($get_q['cust_pic2'] != '') { 
								$image_link = "../files/customer/".$id."/".$get_q['cust_pic2'];
								?>
									<a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox">
									<img src="<?php echo "../files/customer/".$id."/".$get_q['cust_pic2']; ?>" name="cust_pic4" id="cust_pic4" style="height:115px; width:115px">
									</a>
								<?php } else { ?>
									<img name="cust_pic4" id="cust_pic4" style="height:115px; width:115px">
								<?php } ?>
							</td>
							<td>
								<?php if($get_q['cust_pic3'] != '') { 
								$image_link = "../files/customer/".$id."/".$get_q['cust_pic3'];
								?>
									<a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox">
									<img src="<?php echo "../files/customer/".$id."/".$get_q['cust_pic3']; ?>" name="cust_pic6" id="cust_pic6" style="height:115px; width:115px">
									</a>
								<?php } else { ?>
									<img name="cust_pic6" id="cust_pic6" style="height:115px; width:115px">
								<?php } ?>
							</td>
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
                  <td><input type="text" name="name" id="name" style="width:300px" tabindex="1" value="<?php echo $get_q['name']; ?>" /></td>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">New I/C</td>
                  <td><input type="text" name="nric" id="nric" tabindex="1" value="<?php echo $get_q['nric']; ?>" onblur="checkIC(this.value)" /></td>
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
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
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
   	              	  <select name="year_stay" id="year_stay" style="height:30px; width:85px">
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
                    <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Self Employed" <?php if($get_c['c_workingtype'] == 'SELF EMPLOYED') { echo 'checked'; } ?> onclick="wt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Self Employed</td>
                    <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Private" <?php if($get_c['c_workingtype'] == 'PRIVATE') { echo 'checked'; } ?> onclick="wt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Private</td>
                    <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Government" <?php if($get_c['c_workingtype'] == 'GOVERNMENT') { echo 'checked'; } ?> onclick="wt2()" /></td>
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
                      <td><input type="radio" name="c_workingtype" id="c_workingtype2" value="Retired" <?php if($get_c['c_workingtype'] == 'RETIRED') { echo 'checked'; } ?> onclick="wt2()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Retired</td>
                      <td><input type="radio" name="c_workingtype" id="c_workingtype2" value="others" <?php if($get_c['c_workingtype'] != 'GOVERNMENT' && $get_c['c_workingtype'] != 'RETIRED' && $get_c['c_workingtype'] != 'PRIVATE' && $get_c['c_workingtype'] != 'SELF EMPLOYED' && $get_c['c_workingtype'] != '') { echo 'checked'; } ?> onclick="wt()" /></td>
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
                    <td><input type="text" name="c_email" id="c_email" style="width:300px" tabindex="6" value="<?php echo $get_c['c_email']; ?>" class="email" /></td>
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
                	<td width="18%" style="padding-right:10px">Basic Salary</td>
                    <td width="27%"><input type="text" name="basic_salary" id="basic_salary" placeholder="RM" class="currency" tabindex="7" value="<?php echo $get_f['basic_salary']; ?>" /></td>
                	<td width="20%" style="padding-right:10px"></td>
                    <td width="35%"></td>
            	</tr>
                <tr>
                	<td width="18%" style="padding-right:10px">Monthly Nett Salary</td>
                    <td width="27%"><input type="text" name="net_salary" id="net_salary" placeholder="RM" class="currency" tabindex="7" value="<?php echo $get_f['net_salary']; ?>" /></td>
                	<td width="20%" style="padding-right:10px">House Installment Repayment</td>
                    <td width="35%"><input type="text" name="house_installment" id="house_installment" class="currency" placeholder="RM" tabindex="8" value="<?php echo $get_f['house_installment']; ?>"></td>
            	</tr>
                <tr>
                	<td style="padding-right:10px">Total Credit Card Repayment</td>
                    <td><input type="text" name="total_cc" id="total_cc" placeholder="RM" tabindex="7" class="currency" value="<?php echo $get_f['total_cc']; ?>" /></td>
                    <td style="padding-right:10px">Monthly Personal Loan Repayment</td>
                    <td><input type="text" name="personal_loan" id="personal_loan" placeholder="RM" tabindex="8" class="currency" value="<?php echo $get_f['personal_loan']; ?>"></td>
                </tr>
                <tr>
                	<td style="padding-right:10px">Car Installment Repayment</td>
                    <td><input type="text" name="car_installment" id="car_installment" placeholder="RM" tabindex="7" class="currency" value="<?php echo $get_f['car_installment']; ?>" /></td>
                    <td style="padding-right:10px">Bank Loan</td>
                    <td><input type="text" name="bank_loan" id="bank_loan" placeholder="RM" tabindex="8" class="currency" value="<?php echo $get_f['bank_loan']; ?>"></td>
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
                    <td><input type="radio" name="s_workingtype" id="s_workingtype" value="SELF EMPLOYED" <?php if($get_s['s_workingtype'] == 'SELF EMPLOYED') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Self Employed</td>
                    <td><input type="radio" name="s_workingtype" id="s_workingtype" value="PRIVATE" <?php if($get_s['s_workingtype'] == 'PRIVATE') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px">Private</td>
                    <td><input type="radio" name="s_workingtype" id="s_workingtype" value="GOVERNMENT" <?php if($get_s['s_workingtype'] == 'GOVERNMENT') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px">Government</td>
                  </tr>
                </table></td>
              </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Others<br/><a onClick=reset_others2()>Reset</a></td>
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
                      <td><input type="radio" name="s_workingtype" id="s_workingtype2" value="RETIRED" <?php if($get_s['s_workingtype'] == 'RETIRED') { echo 'checked'; } ?> onclick="swt2()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Retired</td>
                      <td><input type="radio" name="s_workingtype" id="s_workingtype2" value="others" <?php if($get_s['s_workingtype'] != 'GOVERNMENT' && $get_s['s_workingtype'] != 'RETIRED' && $get_s['s_workingtype'] != 'PRIVATE' && $get_s['s_workingtype'] != 'SELF EMPLOYED' && $get_s['s_workingtype'] != '') { echo 'checked'; } ?> onclick="swt()" /></td>
                      <td style="padding-left:5px; padding-right:15px">Others</td>
					  <td><input type="text" name="s_other_wt" id="s_other_wt" <?php if($get_s['s_workingtype'] != 'GOVERNMENT' && $get_s['s_workingtype'] != 'RETIRED' && $get_s['s_workingtype'] != 'PRIVATE' && $get_s['s_workingtype'] != 'SELF EMPLOYED' && $get_c['c_workingtype'] != '') { ?>value="<?php echo $get_s['s_workingtype']; ?>"<?php } else { echo 'disabled'; }?> /></td>
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
       	    		<td><input type="text" name="s_email" id="s_email" value="<?php echo $get_s['s_email']; ?>" style="width:300px" class="email" /></td>
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
                    <table>
						<?php $ctr_1=0; if($get_a['a_shoplotfile'] != '') { ?>
                    	<td><a href="<?php echo "../files/customer/".$id."/".$get_a['a_shoplotfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
						<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
								<br />
								<a href="javascript:deleteAF('<?php echo $get_a['a_shoplotfile']; ?>', '../files/customer/<?php echo $id; ?>/','<?php echo $id; ?>')" title="Delete">
									<img src="../img/delete-btn.png" width="15" />
								</a>
						<?php } ?>
                    	<?php } ?>
                        </td>
						<?php
						//callout uploaded files
						
						if ($handle = opendir('appform/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "appform/".$id."/".$entry;
									$ctr_1++;
						?>
                  <td><a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_1;?></a>
				  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
					<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'appform/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
					<?php } ?>
				  </td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
                    	<!--<input type="file" name="a_shoplotfile" id="a_shoplotfile">-->		
						<td><a href="multiimage/index.php" rel="shadowbox"><img src="../img/browse.JPG" />	</a>				</td>
            	</tr>
                </table>
                <tr>
                  <td width="18%" align="left" style="padding-right:10px">Branch</td>
                  <td width="32%"><input type="text" name="a_bankbranch" id="a_bankbranch" style="width:300px" tabindex="14" value="<?php echo $get_a['a_bankbranch']; ?>" /></td>
                	<td align="left" style="padding-right:10px">I/C</td>
                    <td>
                    <table>
						<?php $ctr_2=0; if($get_a['a_icfile'] != '') { ?>
                      <td><a href="<?php echo "../files/customer/".$id."/".$get_a['a_icfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
					   <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
										<br /><a href="javascript:deleteAF('<?php echo $get_a['a_icfile']; ?>', '../files/customer/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>	
                      <?php } ?>
                      </td>
						<?php
						//callout uploaded files
						
						if ($handle = opendir('custic/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
						
									$image_link = "custic/".$id."/".$entry;
									$ctr_2++;
						?>
                  <td><a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_2;?></a>
				  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
										<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'custic/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>
				  </td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
                      <td>	<a href="multiimage/ic.php" rel="shadowbox"><img src="../img/browse.JPG" /></a>					</td>
                </tr>
                </table>
                <tr>
                  <td align="left" style="padding-right:10px">Account Number</td>
                  <td><input type="text" name="a_bankaccno" id="a_bankaccno" style="width:300px" tabindex="14" value="<?php echo $get_a['a_bankaccno']; ?>" /></td>
                	<td align="left" style="padding-right:10px">Bank Statement</td>
                    <td>
                    <table>
						<td><?php $ctr_3=0; if($get_a['a_bankfile'] != '') { ?>
						  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_bankfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
						  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
									<br /><a href="javascript:deleteAF('<?php echo $get_a['a_bankfile']; ?>', '../files/customer/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>	
						  <?php } ?>
                          </td>
						<?php
						//callout uploaded files
						
						if ($handle = opendir('bankstat/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "bankstat/".$id."/".$entry;
									$ctr_3++;
						?>
                  <td><a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_3;?></a>
				  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
									<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'bankstat/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?></td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
					
                     <td> 	<a href="multiimage/bs.php" rel="shadowbox"><img src="../img/browse.JPG" /></a>					</td>
              </tr>
              </table>
              <tr>
                <td align="left" style="padding-right:10px">Account Holder Name</td>
                <td><input type="text" name="a_name" id="a_name" style="width:300px" tabindex="14" value="<?php echo $get_a['a_name']; ?>" /></td>
                <td align="left" style="padding-right:10px">Salary Slips </td>
                    <td>
                    <table>
						<td><?php $ctr_4=0; if($get_a['a_payslipfile'] != '') { ?>
                      <a href="<?php echo "../files/customer/".$id."/".$get_a['a_payslipfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
					  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
									<br /><a href="javascript:deleteAF('<?php echo $get_a['a_payslipfile']; ?>', '../files/customer/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>	
                      <?php } ?>
                      </td>
						<?php
						//callout uploaded files
						
						if ($handle = opendir('salaryslip/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "salaryslip/".$id."/".$entry;
									$ctr_4++;
						?>
                  <td><a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_4;?></a>
				  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong')){	?>
									<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'salaryslip/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>
				  </td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
                      	<td><a href="multiimage/salary.php" rel="shadowbox"><img src="../img/browse.JPG" /></a>					</td>
              </tr>
              </table>
              <tr>
                
                	<td><span style="padding-right:10px">ATM Card Number</span></td>
                    <td><input type="text" name="a_pinno" id="a_pinno" tabindex="15" style="width:250px" value="<?php echo $get_a['a_pinno']; ?>" /></td>
             <td align="left" style="padding-right:10px">ATM Card </td>
                <td>
					<table>
					<tr>
					<?php $ctr_5 = 0;if($get_a['a_atmfile'] != '') { ?>
                      <td>
					  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_atmfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>					  </td>
                      <?php } ?>
					<?php
					//callout uploaded files
					
					if ($handle = opendir('atmcard/'.$id.'/'))
					 {
						while (false !== ($entry = readdir($handle))) 
						{
							if ($entry != "." && $entry != "..") 
							{
								/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
								
								$filename = explode('.', $entry);
								$image_link = "atmcard/".$id."/".$entry;
								$ctr_5++;
						?>
                  
								<td>
								<a href="<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_5; ?></a>
								<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen')){	?>
								<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'atmcard/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
								<?php } ?>								</td>
					<?php
								 
						   }
						}
						closedir($handle);
					}
					
					?>
				
                    <td><a href="multiimage/atm.php" rel="shadowbox(width=900px)"><img src="../img/browse.JPG" /></a></td>
					</tr>
					</table>					</td> </tr>
              <tr>
                <td align="left" style="padding-right:10px">Expired (Month/Year) </td>
                <td><input type="text" name="internet_username" id="internet_username" value="<?php echo $get_a['internet_username']; ?>" class="email" /></td>
                <td align="left" style="padding-right:10px">Mortgage</td>
                    <td>
						<table>
						<tr>
						
						<?php $ctr_6 = 0;  if($get_a['a_housefile'] != '') { ?>
						<td>
                    	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_housefile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                    <?php } ?>
						</td>
						<?php
						//callout uploaded files
						
						if ($handle = opendir('mortgage/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "mortgage/".$id."/".$entry;
									$ctr_6++;
						?>
							<td>
                  			<a href="<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_6; ?></a>
							<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen')){	?>
					<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'mortgage/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a><?php } ?>
							</td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
					
						<td><a href="multiimage/mortgage.php" rel="shadowbox(width=900px)"><img src="../img/browse.JPG" /></a></td>
						</tr>
						</table>
					</td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px">Card Pin No</td>
                <td><input type="text" name="internet_password" id="internet_password" value="<?php echo $get_a['internet_password']; ?>" class="email" /></td>
                <td align="left" style="padding-right:10px">Guarantor Form </td>
                    <td>
					<table>
					<tr>
                    <?php $ctr_7 = 0;?>
					<?php
						//callout uploaded files
						
						if ($handle = opendir('guarantorform/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "guarantorform/".$id."/".$entry;
									$ctr_7++;
						?>
					  <td><a href="<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_7;?></a>
					  <?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen')){	?>
					<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'guarantorform/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a><?php } ?>
					  </td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
					
					
                    	<td><a href="multiimage/guarantorform.php" rel="shadowbox(width=900px)"><img src="../img/browse.JPG" /></a></td>
						</tr>
						</table>					
					</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">I.C Number</td>
                  	<td><input type="text" name="a_nric" id="a_nric" tabindex="14" value="<?php echo $get_a['a_nric']; ?>" /></td>
                     <td align="left" style="padding-right:10px">Others</td>
                <td>
				<table>
					<tr>
					<?php $ctr_8 = 0; if($get_a['a_landfile'] != '') { ?>
					<td>
                    	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_landfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>					</td>
                    <?php } ?>
					<?php
						//callout uploaded files
						
						if ($handle = opendir('others/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "others/".$id."/".$entry;
									$ctr_8++;
						?>
                  
					
							<td>
									<a href="<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_8;?></a>
									<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen')){	?>
									<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'others/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>							</td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
						</tr>
						<tr>
						<td colspan="5">
						
                    	<a href="multiimage/others.php" rel="shadowbox(width=900px)"><img src="../img/browse.JPG" /></a>						</td>
					</tr>
					</table>				</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">Pay date</td>
                	<td style="padding-right:10px"><input type="text" name="a_payday" id="a_payday" tabindex="14" value="<?php echo $get_a['a_payday']; ?>" /></td>
                	<td align="left" style="padding-right:10px">Reminder Letter</td>
                    <td>
						<table>
						<tr>
						<td>
						<?php
						//callout uploaded files
						
						if ($handle = opendir('reminder/'.$id.'/'))
						 {
							while (false !== ($entry = readdir($handle))) 
							{
								if ($entry != "." && $entry != "..") 
								{
									/**/$extension_importfile = substr(strrchr($entry, '.'), 1);
									
									$filename = explode('.', $entry);
									$image_link = "reminder/".$id."/".$entry;
									$ctr_9++;
						?>
                  
									<td align="center">
									<a href="<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /><br /><?php echo $ctr_9; ?>	</a>
										<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld'  || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'huizhen')){	?>
										<br /><a href="javascript:deleteAF('<?php echo addslashes($entry); ?>', 'reminder/<?php echo $id; ?>/')" title="Delete"><img src="../img/delete-btn.png" width="15" /></a>
									<?php } ?>									</td>
						<?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
						<td><a href="multiimage/reminder.php" rel="shadowbox(width=900px)"><img src="../img/browse.JPG" /></a></td>
						</tr>
						</table>					  </td>
              </tr>
              <tr>
                <td colspan="2" style="padding-right:10px"><strong>TRANSFER TO ACCOUNT 2 </strong></td>
                <td align="left" style="padding-right:10px">&nbsp;</td>
				<td rowspan="5">
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
                    </table>				</td>
              </tr>
              <tr>
                <td style="padding-right:10px">Bank</td>
                <td><input type="text" name="transfer_accountbank" id="transfer_accountbank" value="<?php echo $get_a['transfer_accountbank']; ?>" style="width:300px" /></td>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-right:10px">Account Number </td>
                <td><input type="text" name="transfer_accountno" id="transfer_accountno" value="<?php echo $get_a['transfer_accountno']; ?>" style="width:300px" /></td>
                <td style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-right:10px">Account Holder Name </td>
                <td><input type="text" name="transfer_accountholder" id="transfer_accountholder" value="<?php echo $get_a['transfer_accountholder']; ?>" style="width:300px" /></td>
                <td style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px"><input type="hidden" name="a_date" id="a_date" tabindex="14" value="<?php echo $get_a['a_date']; ?>" /></td>
                <td>&nbsp;</td>
                <td style="padding-right:10px">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" style="padding-right:10px">&nbsp;</td>
                <td>&nbsp;</td>
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
			  	<td style="padding-right:10px; padding-top:10px" valign="top">Notes</td>
				<td colspan="3"><textarea name="a_remarks" id="a_remarks" style="width:800px; height:50px"><?php echo $get_a['a_remarks']; ?></textarea></td>
			  </tr>
            </table>
        </center>
        </td>
    </tr>
   <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr>
    	<th>Loan Package</th>
    </tr>
    <tr>
    	<td>
        	<center>
        	  <table width="85%" id="table_form">
                <tr>
                  <td colspan="4"><div id="message3"></div></td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="NEW LOAN" checked="checked" /></td>
                        <td style="padding-left:5px; padding-right:20px">New Loan</td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">Settlement Date for Previous Loan </td>
                  <td><input type="text" name="previous_settlement_date" id="previous_settlement_date" tabindex="16" /></td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="RELOAN" /></td>
                        <td style="padding-left:5px; padding-right:20px">Reloan</td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">Previous Agreement No </td>
                  <td><input type="text" name="previous_loan_code" id="previous_loan_code" style="width:80px" tabindex="16" /></td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="OVERLAP" /></td>
                        <td style="padding-left:5px; padding-right:20px">Overlap</td>
                      </tr>
                  </table></td>
				  <td style="padding-right:10px"><!--Remark for New Customer --></td>
                  <td style="padding-right:10px">
					 <!--<select name="custRemark" id="custRemark" tabindex="16" style="height:30px" onChange="showRemarks()">
                        <option value="FLYER">FLYER</option>
						<option value="METRO">METRO</option>
						<option value="WeChat">WeChat</option>
						<option value="WALK IN">WALK IN</option>
						<option value="FACEBOOK">FACEBOOK</option>
						<option value="CALL LIST">CALL LIST</option>
						<option value="NAME CARD">NAME CARD</option>
						<option value="AGENT RECOMMEND">AGENT RECOMMEND</option>
						<option value="OTHER">OTHERS</option></select>-->
				  </td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="loanpackagetype" id="loanpackagetype" value="ACCOUNT 2" /></td>
                        <td style="padding-left:5px; padding-right:20px">Account 2</td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px">&nbsp;</td>
				  <td>
					<table>
					<tr style="display:none;" id="tr_remark">
						<td><input type="text" name="custRemark" id="other_remark" size="16" placeholder="Others" autocomplete="off"/></td>
					</tr>
					</table>
				  </td>
					
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px"><table id="radio_table">
                      <tr>
                        <!-- <td><input type="radio" name="loan_type" id="loan_type" value="Flexi Loan"  /></td>
                        <td style="padding-left:5px; padding-right:20px">Flexi Loan</td> -->
                        <td><input type="radio" name="loan_type" id="loan_type1" value="Fixed Loan" checked="checked" /></td>
                        <td style="padding-left:5px; padding-right:20px">Fixed Loan</td>
                      </tr>
                  </table></td>
                  <td style="padding-right:10px"></td>
                  <td></td>
                </tr>
                <tr>
                  <td width="18%" style="padding-right:10px">Package</td>
                  <td width="27%"><?php $scheme_q = mysql_query("SELECT * FROM loan_scheme"); ?>
                      <!--<select name="loan_package" id="loan_package" tabindex="16" style="height:30px" onchange="checkLoan(this.value);">-->
<!--                       <select name="loan_package" id="loan_package" tabindex="16" style="height:30px">
                        <option value=""></option>
                        <?php 
								while($scheme = mysql_fetch_assoc($scheme_q))
								{
							?>
                        <option value="<?php echo $scheme['scheme']; ?>"><?php echo $scheme['scheme']; ?></option>
                        <?php
								}
							?>
                      </select>       -->          <input type="text" name="loan_package" id="loan_package" value="NEW PACKAGE" readonly autocomplete="OFF" />   </td>

                      <td style="padding-right:10px"><b>Loan Amount</b></td>
                  <td><input type="text" name="loan_amount" id="loan_amount" placeholder="RM" class="currenciesOnly" tabindex="16" onkeyup="hideloanamt(this.value)" autocomplete="OFF" />
                      <input type="hidden" name="hide_loanamt" id="hide_loanamt" /></td>
                                       
                </tr>
                <tr>
                  <td style="padding-right:10px"><b>Agreement No<b></td>
                  <td><!--<input type="text" name="loan_code" id="loan_code" style="width:80px" onblur="checkLoanCode(this.value)" tabindex="16" />-->
                      <input type="text" name="loan_code" id="loan_code" style="width:80px" tabindex="16" autocomplete="OFF" onblur="checkLoanCode(this.value)" /></td>
 <!--                  <td width="20%"style="padding-right:10px" hidden="">Interest Rate </td>
                  <td width="35%" hidden=""><table width="100%" style="border-collapse:collapse">
                      <tr>
                        <td><input type="text" name="loan_interest" style="width:50px" id="loan_interest" tabindex="17" onkeyup="calculateInt()" />
                          % </td>
                        <td> Total of Interest </td>
                        <td><input type="text" name="loan_interesttotal" id="loan_interesttotal" class="currency" style="width:80px" tabindex="17" /></td>
                      </tr>
                  </table ></td> -->
                  <td style="padding-right:10px"><b>Loan Period (months) <b></td>
                  <td><input type="text" name="loan_period" id="loan_period" tabindex="16" onkeyup="calculateAmountMonth()" autocomplete="OFF" />
                      <input type="hidden" name="loantype" id="loantype" /></td>

                </tr>
                               <tr>

                  <td style="padding-right:10px">Loan Pokok (RM) </td>
                  <td><input type="text" name="loan_pokok" id="loan_pokok"  readonly="readonly" />
                  </td>
                                    <td style="padding-right:10px">Amount Monthly (RM) </td>
                  <td><input type="text" name="loan_amount_month" id="loan_amount_month" readonly="readonly" style="color:black;font-weight:bold;" />
                  </td>

                </tr>
                <!-- <tr>
                  <td style="padding-right:10px; padding-top:10px" valign="top">Remarks</td>
                  <td colspan="3"><textarea name="loan_remarks" id="loan_remarks" style="width:800px; height:50px"></textarea></td>
                </tr> -->
                <tr>
                  <td colspan="4" style="border-bottom:1px solid;"></td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-right:10px:">&nbsp;<b>Monthly</b></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="18%" style="padding-right:10px">Package</td>
                  <td width="27%"><?php $scheme_q = mysql_query("SELECT * FROM loan_scheme"); ?>
                      <!--<select name="loan_package" id="loan_package" tabindex="16" style="height:30px" onchange="checkLoan(this.value);">-->
<!--                       <select name="loan_package" id="loan_package" tabindex="16" style="height:30px">
                        <option value=""></option>
                        <?php 
								while($scheme = mysql_fetch_assoc($scheme_q))
								{
							?>
                        <option value="<?php echo $scheme['scheme']; ?>"><?php echo $scheme['scheme']; ?></option>
                        <?php
								}
							?>
                      </select>       -->          <input type="text" name="loan_package" id="loan_package" value="NEW PACKAGE" readonly autocomplete="OFF" />   </td>
                                        <td style="padding-right:10px"></td>
                  <td></td>

                </tr>
                <tr>
                  <td style="padding-right:10px"><b>Agreement No</b></td>
                  <td><!--<input type="text" name="loan_code" id="loan_code" style="width:80px" onblur="checkLoanCode(this.value)" tabindex="16" />-->
                      <input type="text" name="loan_code_monthly" id="loan_code_monthly" style="width:80px" tabindex="16" autocomplete="OFF" onblur="checkLoanCode(this.value)" /></td>
 <!--                  <td width="20%"style="padding-right:10px" hidden="">Interest Rate </td>
                  <td width="35%" hidden=""><table width="100%" style="border-collapse:collapse">
                      <tr>
                        <td><input type="text" name="loan_interest" style="width:50px" id="loan_interest" tabindex="17" onkeyup="calculateInt()" />
                          % </td>
                        <td> Total of Interest </td>
                        <td><input type="text" name="loan_interesttotal" id="loan_interesttotal" class="currency" style="width:80px" tabindex="17" /></td>
                      </tr>
                  </table ></td> -->

						<tr>
						    	<td><b>Payout Date</b></td>
						        <td><input type="date" name="monthly_date" id="monthly_date" autocomplete="off"></td>
						    </tr>
						    <tr>
						    	<td><b>Month</b></td>
						        <td><input type="text" name="month" id="month" autocomplete="off" ></td>
						    </tr>
						    <tr>
						      <td><b>Payout Amount (RM) </b></td>
						      <td valign="top"><input type="text" name="payout_amount" id="payout_amount" class="currency" autocomplete="off" ></td>
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
                	<td><input type="button" name="cancel_loan" id="cancel_loan" onClick="window.location.href='index.php'" value=""></td>
                    <td align="right"><input type="submit" name="add_loan" id="add_loan" value="">&nbsp;&nbsp;&nbsp;<input type="reset" id="reset" name="reset" value=""></td>
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
	document.getElementById('loan_package').focus();
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
$('#month').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
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
	if((document.getElementById('name').value != '' && document.getElementById('mobile_contact').value != ''&& document.getElementById('nric').value != '' && document.getElementById('loan_package').value != '' && document.getElementById('loan_amount').value != '' && document.getElementById('loan_interest').value != '' && document.getElementById('loan_interesttotal').value != '' && document.getElementById('loan_code').value!= ''))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		if((document.getElementById('name').value == '' || document.getElementById('nric').value == ''))
		{
			$('html, body').animate({scrollTop:150}, 'fast');
			var msg = "<div class='error'>Full Name & NRIC fields must be filled!</div>";
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
		
		// if((document.getElementById('loan_package').value == '' || document.getElementById('loan_amount').value == '' || document.getElementById('loan_interest').value == '' || document.getElementById('loan_interesttotal').value == '' || document.getElementById('loan_code').value == ''))
		// {
		// 	$('html, body').animate({scrollTop:2700}, 'fast');
		// 	var msg = "<div class='error'>Loan package section must be filled!</div>";
		// 	$('#message3').empty();
		// 	$('#message3').append(msg); 
		// 	$('#message3').html();
		// 	return false;
		// }
		// else
		// {
		// 	$('#message3').empty();
		// }
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
function apic()
{
	document.getElementById("others_ic2").disabled = false;
}
function apic2()
{
	document.getElementById("s_others_ic2").disabled = false;
}

function reset_others()
{

	document.getElementById('others_ic').checked = reset;
	document.getElementById('others_ic').checked = false;
	document.getElementById('others_ic2').value = "";
	document.getElementById('others_ic2').disabled = true;

}

function reset_others2()
{

	document.getElementById('s_others_ic').checked = reset;
	document.getElementById('s_others_ic').checked = false;
	document.getElementById('s_others_ic2').value = "";
	document.getElementById('s_others_ic2').disabled = true;

}

$(function() {

	$.mask.definitions['~'] = "[+-]";

	$("#nric").mask("999999-99-9999");
	$("#s_nric").mask("999999-99-9999");
	$("#a_nric").mask("999999-99-9999");
	//$("#a_pinno").mask("9999 9999 9999 9999 9999");

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
/*function autoPackage(str)
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
			
			document.getElementById('loan_interest').value = res[0];
			document.getElementById('loantype').value = res[1];
			$lt = document.getElementById('loantype').value;
			
			if($lt == 'Fixed Loan')
			{
				document.getElementById('loan_type1').checked = true;
			}else
			{
				document.getElementById('loan_type').checked = true;
			}
			
		}
	}
	xmlhttp.open("GET","get_package.php?q="+str,true);
	xmlhttp.send();
}*/
function hideloanamt(v)
{
	document.getElementById('hide_loanamt').value = v;
}
// function calculateInt()
// {
// 	$package = document.getElementById('loan_package').value;
// 	$loan = document.getElementById('hide_loanamt').value;
// 	$int = document.getElementById('loan_interest').value;
// 	$month = document.getElementById('loan_period').value;
	
// 	if($package != 'SKIM CEK')
// 	{
// 		if($int == '13')
// 		{
// 			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
// 		}else
// 		if($int == '18')
// 		{
// 			$loan_inttotal = ((($int/100) * $loan) * $month) - $loan;
// 		}else
// 		{
// 			$loan_inttotal = (($int/100) * $loan) * $month;
// 		}
// 	}else
// 	{	
// 		$loan_inttotal = (($int/100) * $loan);
// 	}
	
// 	if($loan_inttotal != 0)
// 	{
// 		document.getElementById('loan_interesttotal').value = $loan_inttotal;
// 	}
	
// }



	$("#loan_period,#loan_amount").on("blur",function(){

		var loan_package = $("#loan_package").val();
		var loan_amount = $("#loan_amount").val();
		var loan_period = $("#loan_period").val();
		var dataString = 'loan_amount='+loan_amount+'&loan_period='+loan_period;
		if(loan_package=='NEW PACKAGE'){
			
			$.ajax({
				url: 'autofill_newpackage.php',
				type: "post",
				data: dataString,
				cache: true,
				success: function (result){
					if(result != ""){
						var parsed = jQuery.parseJSON(result);
						$("#loan_amount_month").val(parsed[0]);
						$("#loan_pokok").val(parsed[1]);
						console.log(result);
					}
				}
			})
		}
	});

function deleteAF(filename, filelink)
{
	$filename = filename;
	$link = filelink;
	
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	:  'Are you sure want to delete this file: ' + $filename + '?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'deletefile',
							filename: $filename,
							filelink: $link,
						},
						url: 'action_deletefile.php',
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

function checkLoan(str)
{
	
	$code1 = document.getElementById('customer_id').value;
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
				$('#message3').empty();
				$('#message3').append(msg); 
				$('#message3').html();
				document.getElementById('loan_package').value = '';
				document.getElementById('loan_package').focus();
			}else
			{
				$('#message3').empty();
				document.getElementById('loan_interest').value = res[1];
			document.getElementById('loantype').value = res[2];
			$lt = document.getElementById('loantype').value;
			
				if($lt == 'Fixed Loan')
				{
					document.getElementById('loan_type1').checked = true;
					document.getElementById('loan_type1').disabled = false;
					document.getElementById('loan_type').disabled = true;
					
				}else
				{
					document.getElementById('loan_type').checked = true;
					document.getElementById('loan_type').disabled = false;
					document.getElementById('loan_type1').disabled = true;
				}
			}
		}
	  }
	  
	xmlhttp.open("GET","checkLoan.php?code1="+$code1+"&code2="+escape(str),true);
	xmlhttp.send();
}

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
Shadowbox.init({

	  onClose: SBClose     // Functions listed here do not include the traditional parenthesis or semicolon. Place a comma if you have another Shadowbox Option for the next line. There should be no comma on the last Shadowbox Option listed.
 });
	 
function SBClose() {
	  $idReload = document.getElementById('idReload').value;
	  window.open('add_loan.php?id='+$idReload, '_self');     // Reload parent window.

	  // Optional 2nd line for the next item Shadowbox onClose should execute. Example: Use   console.log('done');   to provide visual feedback in the browsers console window that it's done.
 }

function checkLoanCode(str)
{
	$loan_package = document.getElementById('loan_package').value;
	
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
				$('#message3').empty();
				$('#message3').append(msg); 
				$('#message3').html();
				document.getElementById('loan_code').value = '';
				document.getElementById('loan_code').focus();
			}else
			{
				$('#message3').empty();
			}
		}
	  }
	  
	xmlhttp.open("GET","checkLoanCode.php?loan_package="+$loan_package+"&code="+escape(str),true);
	xmlhttp.send();
}

function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 45
     || event.keyCode == 37 || event.keyCode == 39) {
        return true;
    }
    else if ( key < 48 || key > 57 || key == 116) {
        return false;
    }
    else return true;
};

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
function wt()
{
	document.getElementById("other_wt").disabled = false;
}
function wt2()
{
	document.getElementById("other_wt").disabled = true;
}
function swt()
{
	document.getElementById("s_other_wt").disabled = false;
}
function swt2()
{
	document.getElementById("s_other_wt").disabled = true;
}

$('#previous_settlement_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
	
function showRemarks()
{
	var x = document.getElementById('custRemark').value;
	
	if(x=="OTHER")
	{
		document.getElementById('tr_remark').style.display = 'table-row';
		x = document.getElementById('other_remark').value;
	}
	else
	{
		document.getElementById('tr_remark').style.display = 'none';
		document.getElementById('other_remark').value = x;
	}
}

</script>