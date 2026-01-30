<?php 
include('../include/page_header.php'); 
$id = $_GET['id'];

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

#table_form2 tr td
{
	height:35px;
	padding-left:5px;
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
					<td>Last Update By: <?php echo $get_q['update_byname']; ?></td>
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
                  <td bgcolor="#CCCCCC"><table style="border-collapse:collapse">
                      <tr>
                        <td width="79" bgcolor="#CCCCCC"><?php echo $get_q['customercode2']; ?></td>
                        <td width="117" style="padding-left:5px; padding-right:20px; border:#FFFFFF thin solid" bgcolor="#FFFFFF"><span style="padding-right:10px">Recruiter</span></td>
                        <td width="196" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC"><label><?php echo $get_q['recruitor']; ?></label></td>
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
                  <td width="38%" bgcolor="#CCCCCC"><?php echo $get_q['bis']; ?></td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td bgcolor="#CCCCCC"><table id="radio_table2">
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
                  <td bgcolor="#CCCCCC"><?php echo $get_q['name']; ?></td>
                  <td colspan="2" style="padding-right:10px">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">New I/C</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_q['nric']; ?></td>
                  <td width="2%" align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="14%" align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Old I/C</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_q['old_ic']; ?></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Date of Birth</td>
                  <td bgcolor="#CCCCCC"><?php if($get_q['dob'] != '0000-00-00') { echo $get_q['dob']; } ?></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Others</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_q['others_ic']; ?> <?php echo $get_q['others_ic2']; ?></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Sex</td>
                  <td bgcolor="#CCCCCC"><table id="radio_table2">
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
                  <td bgcolor="#CCCCCC"><?php echo $get_add['mobile_contact']; ?></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Race</td>
                  <td bgcolor="#CCCCCC"><table id="radio_table">
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
                  <td  bgcolor="#CCCCCC"><?php echo $get_add['home_contact']; ?></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td bgcolor="#CCCCCC"><table id="radio_table">
                      <tr>
                        <td><input type="radio" name="race" id="radio" value="Others" onclick="raceOther();" <?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian' && $get_q['race'] != '') { echo 'checked'; } ?> /></td>
                        <td style="padding-left:5px; padding-right:20px">Others</td>
                        <td><?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo $get_q['race']; } ?></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Email Address </td>
                  <td  bgcolor="#CCCCCC"><?php echo $get_q['nationality']; ?></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td align="left" style="padding-right:10px">Marital Status</td>
                  <td bgcolor="#CCCCCC"><table id="radio_table2">
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
                  <td bgcolor="#CCCCCC"><?php echo $get_q['no_dependents']; ?></td>
                </tr>
                <tr>
                  <td height="25" align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="31%">&nbsp;</td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td style="padding-right:10px">Academic Qualification</td>
                  <td valign="top" bgcolor="#CCCCCC"><?php echo $get_q['academic_qualification']; ?></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td width="31%">&nbsp;</td>
                  <td style="padding-right:10px">&nbsp;</td>
                  <td style="padding-right:10px">Mother's Name</td>
                  <td valign="top" bgcolor="#CCCCCC"><?php echo $get_q['mother_name']; ?></td>
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
       	  	<table width="85%" id="table_form2">
        	    <tr>
        	      <td colspan="4"><div id="message2"></div></td>
      	      </tr>
        	    <tr>
        	      <td width="15%" align="left" style="padding-right:10px">&nbsp;</td>
        	      <td width="40%">&nbsp;</td>
        	      <td colspan="2" align="left" style="padding-right:10px">&nbsp;</td>
       	        </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">Residence Address</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['address1']; ?></td>
        	      <td width="14%" align="left" style="padding-right:10px">Mailing Address </td>
        	      <td width="31%" bgcolor="#CCCCCC"><?php echo $get_add['m_address1']; ?></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['address2']; ?></td>
        	      <td align="left" valign="top" style="padding-right:10px"><p>
        	        <label></label>
        	        <br />
      	        </p></td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['m_address2']; ?></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td valign="top" bgcolor="#CCCCCC"><?php echo $get_add['address3']; ?></td>
        	      <td align="left" valign="top" style="padding-right:10px"><label></label></td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['m_address3']; ?></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>&nbsp;</td>
        	      <td align="left" style="padding-right:10px">&nbsp;</td>
        	      <td>(as per I/C) </td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">Postcode</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['postcode']; ?></td>
        	      <td align="left" style="padding-right:10px">Postcode</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['m_postcode']; ?></td>
       	        </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">City</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['city']; ?></td>
        	      <td align="left" style="padding-right:10px">City</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['m_city']; ?></td>
       	        </tr>
        	    <tr>
        	      <td rowspan="2" align="left" valign="top" style="padding-right:10px; padding-top:10px"><span style="padding-right:10px">State</span></td>
        	      <td rowspan="2" align="left" bgcolor="#CCCCCC"><?php echo $get_add['state']; ?></td>
        	      <td align="left" style="padding-right:10px">State</td>
        	      <td bgcolor="#CCCCCC"><?php echo $get_add['m_state']; ?></td>
      	      </tr>
        	    <tr>
        	      <td rowspan="2" align="left" valign="top" style="padding-right:10px; padding-top:10px">Home Ownership</td>
        	      <td rowspan="2" bgcolor="#CCCCCC"><table width="100%" id="radio_table5">
        	        <tr>
        	          <td width="10%" bgcolor="#CCCCCC"><input type="radio" name="m_residence" id="m_residence1" value="Owned-No-Loan" <?php if($get_add['m_residence'] == 'Owned-No-Loan') { echo 'checked'; } ?> /></td>
        	          <td width="44%" style="padding-left:5px" bgcolor="#CCCCCC">Owned-No-Loan</td>
        	          <td width="8%" bgcolor="#CCCCCC"><input type="radio" name="m_residence" id="m_residence2" value="Rented" <?php if($get_add['m_residence'] == 'Rented') { echo 'checked'; } ?> /></td>
        	          <td width="38%" style="padding-left:5px" bgcolor="#CCCCCC">Rented</td>
      	          </tr>
        	        <tr>
        	          <td bgcolor="#CCCCCC"><input type="radio" name="m_residence" id="m_residence3" value="Owned-With-Loan" <?php if($get_add['m_residence'] == 'Owned-With-Loan') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px" bgcolor="#CCCCCC">Owned-With-Loan</td>
        	          <td bgcolor="#CCCCCC"><input type="radio" name="m_residence" id="m_residence4" value="Employers" <?php if($get_add['m_residence'] == 'Employers') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px" bgcolor="#CCCCCC">Employer's</td>
      	          </tr>
        	        <tr>
        	          <td bgcolor="#CCCCCC"><input type="radio" name="m_residence" id="m_residence5" value="Parents / Relatives" <?php if($get_add['m_residence'] == 'Parents / Relatives') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px" bgcolor="#CCCCCC">Parent's / Relative's</td>
        	          <td></td>
        	          <td style="padding-left:5px"></td>
      	          </tr>
       	          </table></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px" valign="top"><span style="padding-right:10px; padding-top:10px padding-top:10px">Home Ownership</span></td>
        	      <td bgcolor="#CCCCCC"><table width="100%" id="radio_table6">
        	        <tr>
        	          <td width="9%" bgcolor="#CCCCCC"><input type="radio" name="residence" id="residence1" value="Owned-No-Loan" <?php if($get_add['residence'] == 'Owned-No-Loan') { echo 'checked'; } ?> /></td>
        	          <td width="37%" style="padding-left:5px" bgcolor="#CCCCCC">Owned-No-Loan</td>
        	          <td width="7%" bgcolor="#CCCCCC"><input type="radio" name="residence" id="residence2" value="Rented" <?php if($get_add['residence'] == 'Rented') { echo 'checked'; } ?> /></td>
        	          <td width="47%" style="padding-left:5px" bgcolor="#CCCCCC">Rented</td>
      	          </tr>
        	        <tr>
        	          <td bgcolor="#CCCCCC"><input type="radio" name="residence" id="residence3" value="Owned-With-Loan" <?php if($get_add['residence'] == 'Owned-With-Loan') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px" bgcolor="#CCCCCC">Owned-With-Loan</td>
        	          <td bgcolor="#CCCCCC"><input type="radio" name="residence" id="residence4" value="Employers" <?php if($get_add['residence'] == 'Employers') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px" bgcolor="#CCCCCC">Employer's</td>
      	          </tr>
        	        <tr>
        	          <td bgcolor="#CCCCCC"><input type="radio" name="residence" id="residence5" value="Parents / Relatives" <?php if($get_add['residence'] == 'Parents / Relatives') { echo 'checked'; } ?> /></td>
        	          <td style="padding-left:5px" bgcolor="#CCCCCC">Parent's / Relative's</td>
        	          <td></td>
        	          <td style="padding-left:5px"></td>
      	          </tr>
       	          </table></td>
      	      </tr>
        	    <tr>
        	      <td align="left" style="padding-right:10px">Years of Stay</td>
        	      <td bgcolor="#CCCCCC"><table id="radio_table9">
        	        <tr>
        	          <td width="87" bgcolor="#CCCCCC"><span style="padding-left:5px; padding-right:20px"><?php echo $get_add['month_stay']; ?></span></td>
        	          <td width="93" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC">Months</td>
        	          <td width="92"><span style="padding-left:5px; padding-right:20px"><?php echo $get_add['year_stay']; ?></span></td>
        	          <td width="135" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC">Years</td>
      	          </tr>
      	        </table></td>
        	      <td align="left" style="padding-right:10px">Year of Stay</td>
        	      <td bgcolor="#CCCCCC"><table id="radio_table10">
        	        <tr>
        	          <td width="85" bgcolor="#CCCCCC"><span style="padding-left:5px; padding-right:20px"><?php echo $get_add['m_month_stay']; ?></span></td>
        	          <td width="59" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC">Months</td>
        	          <td width="95" bgcolor="#CCCCCC"><span style="padding-right:20px"><?php echo $get_add['m_year_stay']; ?>
        	          </span></td>
        	          <td width="71" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC">Years</td>
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
                <td width="40%" bgcolor="#CCCCCC"><?php echo $get_c['company']; ?></td>
                	<td width="14%" align="left" style="padding-right:10px">Company Address</td>
                	<td bgcolor="#CCCCCC"><?php echo $get_c['c_address1']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Nature of Business</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['nature_business']; ?></td>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                	<td bgcolor="#CCCCCC"><?php echo $get_c['c_address2']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Department</td>
                	<td bgcolor="#CCCCCC"><?php echo $get_c['department']; ?></td>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                	<td bgcolor="#CCCCCC"><?php echo $get_c['c_address3']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Position</td>
					<td bgcolor="#CCCCCC"><?php echo $get_c['position']; ?></td>
					<td width="14%" align="left" style="padding-right:10px">Postcode</td>
					<td width="31%" bgcolor="#CCCCCC">
					<table style="border-collapse:collapse">
						<tr>
							<td bgcolor="#CCCCCC">
								<?php echo $get_c['c_postcode']; ?></td>
							<td style="padding-right:15px; padding-left:15px">City</td>
							<td bgcolor="#CCCCCC"><?php echo $get_c['c_city']; ?></td>
						</tr>
					</table>				
					</td>
               	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Employment</td>
                	<td bgcolor="#CCCCCC">
					<table id="radio_table7">
					  <tr>
						<td bgcolor="#CCCCCC"><input type="radio" name="c_workingtype" id="c_workingtype" value="Self Employed" <?php if($get_c['c_workingtype'] == 'Self Employed') { echo 'checked'; } ?> onclick="wt2()" /></td>
						<td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Self Employed</td>
						<td bgcolor="#CCCCCC"><input type="radio" name="c_workingtype" id="c_workingtype" value="Private" <?php if($get_c['c_workingtype'] == 'Private') { echo 'checked'; } ?> onclick="wt2()" /></td>
						<td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Private</td>
						<td bgcolor="#CCCCCC"><input type="radio" name="c_workingtype" id="c_workingtype" value="Government" <?php if($get_c['c_workingtype'] == 'Government') { echo 'checked'; } ?> onclick="wt2()" /></td>
						<td style="padding-left:5px" bgcolor="#CCCCCC">Government</td>
					  </tr>
                	</table>
				</td>
                <td align="left" style="padding-right:10px">State</td>
                <td bgcolor="#CCCCCC"><?php echo $get_c['c_state']; ?></td>
                </tr>
				
                <tr>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td bgcolor="#CCCCCC">
					  <table id="radio_table8">
						<tr>
						  <td bgcolor="#CCCCCC"><input type="radio" name="c_workingtype" id="c_workingtype2" value="Retired" <?php if($get_c['c_workingtype'] == 'Retired') { echo 'checked'; } ?> onclick="wt2()" /></td>
						  <td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Retired</td>
						  <td bgcolor="#CCCCCC"><input type="radio" name="c_workingtype" id="c_workingtype2" value="others" <?php if($get_c['c_workingtype'] != 'Government' && $get_c['c_workingtype'] != 'Retired' && $get_c['c_workingtype'] != 'Private' && $get_c['c_workingtype'] != 'Self Employed' && $get_c['c_workingtype'] != '') { echo 'checked'; } ?> onclick="wt()" /></td>
						  <td style="padding-left:5px; padding-right:15px"bgcolor="#CCCCCC">Others</td>
						  <td bgcolor="#CCCCCC"><?php echo $get_c['c_workingtype']; ?></td>
						</tr>
					  </table>
				  </td>
                	<td align="left" style="padding-right:10px">Company Phone</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_contactno']; ?>
                      <span style="padding-right:10px">- Ext.
                      <?php echo $get_c['c_ext']; ?>
                      </span></td>
               	</tr>
                <tr>
                  <td align="left" style="padding-right:10px">Years of Working</td>
                  <td bgcolor="#CCCCCC">
				  	<table id="radio_table9">
        	        <tr>
        	          <td width="87" bgcolor="#CCCCCC"><span style="padding-left:5px; padding-right:20px">
        	            <?php echo $get_c['c_monthworking']; ?>
                       
        	          </span></td>
        	          <td width="93" style="padding-left:5px; padding-right:20px">Months</td>
        	          <td width="92"><span style="padding-left:5px; padding-right:20px"><?php echo $get_c['c_yearworking']; ?> </span></td>
        	          <td width="135" style="padding-left:5px; padding-right:20px">Years</td>
      	          </tr>
      	        </table>
				</td>
				</tr>
                	<td align="left" style="padding-right:10px">Email Address</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_email']; ?></td>
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
                    <td width="27%" bgcolor="#CCCCCC"><?php echo $get_f['net_salary']; ?></td>
                	<td width="20%" style="padding-right:10px">House Installment Repayment</td>
                    <td width="35%" bgcolor="#CCCCCC"><?php echo $get_f['house_installment']; ?></td>
            	</tr>
                <tr>
                	<td style="padding-right:10px">Total Credit Card Repayment</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['total_cc']; ?></td>
                    <td style="padding-right:10px">Monthly Personal Loan Repayment</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['personal_loan']; ?></td>
                </tr>
                <tr>
                	<td style="padding-right:10px">Car Installment Repayment</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['car_installment']; ?></td>
                    <td style="padding-right:10px">Bank Loan</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['bank_loan']; ?></td>
                </tr>
                <tr>
                	<td><span style="padding-right:10px">Remarks</span></td>
                    <td colspan="3" bgcolor="#CCCCCC"><?php echo $get_f['remarks']; ?></td>
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
                    <td width="27%" bgcolor="#CCCCCC"><?php echo $get_e['e_contactperson']; ?></td>
                	<td width="20%" style="padding-right:10px">&nbsp;</td>
                    <td width="35%">&nbsp;</td>
            	</tr>
                <tr>
                	<td style="padding-right:10px">Mobile Number</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_e['e_contactno']; ?></td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td style="padding-right:10px">Home Phone </td>
                    <td bgcolor="#CCCCCC"><?php echo $get_e['e_officeno']; ?></td>
                    <td style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">Relationship</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_e['e_relationship']; ?></td>
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
                <td width="32%" bgcolor="#CCCCCC"><?php echo $get_s['s_name']; ?></td>
                	<td width="19%" align="left" style="padding-right:10px">Company Name</td>
                    <td width="31%" bgcolor="#CCCCCC"><?php echo $get_s['s_company']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">New I/C</td>
                <td bgcolor="#CCCCCC"><?php echo $get_s['s_nric']; ?></td>
                    <td align="left" style="padding-right:10px">Position</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_s['s_workas']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Old I/C</td>
           	        <td bgcolor="#CCCCCC"><?php echo $get_s['s_oldic']; ?></td>
           	        <td align="left" style="padding-right:10px">Employment</td>
                <td><table id="radio_table7">
                  <tr>
                    <td bgcolor="#CCCCCC"><input type="radio" name="s_workingtype" id="s_workingtype" value="Self Employed" <?php if($get_s['s_workingtype'] == 'Self Employed') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Self Employed</td>
                    <td bgcolor="#CCCCCC"><input type="radio" name="s_workingtype" id="s_workingtype" value="Private" <?php if($get_s['s_workingtype'] == 'Private') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Private</td>
                    <td bgcolor="#CCCCCC"><input type="radio" name="s_workingtype" id="s_workingtype" value="Government" <?php if($get_s['s_workingtype'] == 'Government') { echo 'checked'; } ?> onclick="swt2()" /></td>
                    <td style="padding-left:5px" bgcolor="#CCCCCC">Government</td>
                  </tr>
                </table></td>
              </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Others</td>
        	      <td><table id="radio_table4">
                    <tr>
                      <td bgcolor="#CCCCCC"><input type="radio" name="s_others_ic" id="s_others_ic" value="police" <?php if($get_s['s_others_ic'] == 'police') { echo 'checked'; } ?> onclick="apic2();" /></td>
                      <td style="padding-left:5px; padding-right:20px"bgcolor="#CCCCCC">Police</td>
                      <td bgcolor="#CCCCCC"><input type="radio" name="s_others_ic" id="s_others_ic" value="army" <?php if($get_s['s_others_ic'] == 'army') { echo 'checked'; } ?> onclick="apic2();" /></td>
                      <td style="padding-left:5px; padding-right:20px"bgcolor="#CCCCCC">Army</td>
                      <td bgcolor="#CCCCCC"><?php echo $get_s['s_others_ic2']; ?></td>
                    </tr>
                  </table></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>
				  <table id="radio_table8">
                    <tr>
                      <td bgcolor="#CCCCCC"><input type="radio" name="s_workingtype" id="s_workingtype2" value="Retired" <?php if($get_s['s_workingtype'] == 'Retired') { echo 'checked'; } ?> onclick="swt2()" /></td>
                      <td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Retired</td>
                      <td bgcolor="#CCCCCC"><input type="radio" name="s_workingtype" id="s_workingtype2" value="others" <?php if($get_s['s_workingtype'] != 'Government' && $get_s['s_workingtype'] != 'Retired' && $get_c['s_workingtype'] != 'Private' && $get_s['s_workingtype'] != 'Self Employed' && $get_s['s_workingtype'] != '') { echo 'checked'; } ?> onclick="swt()" /></td>
                      <td style="padding-left:5px; padding-right:15px" bgcolor="#CCCCCC">Others</td>
					  <td bgcolor="#CCCCCC"><?php if($get_s['s_workingtype'] != 'Government' && $get_s['s_workingtype'] != 'Retired' && $get_s['s_workingtype'] != 'Private' && $get_s['s_workingtype'] != 'Self Employed' && $get_c['c_workingtype'] != '') { echo $get_s['s_workingtype']; } ?></td>
                    </tr>
                  </table>
				  </td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Mobile Phone </td>
                  <td bgcolor="#CCCCCC"><?php echo $get_s['s_mobile']; ?></td>
                  <td align="left" style="padding-right:10px">Years of Working </td>
                  <td>
				  	<table id="radio_table9">
        	        <tr>
        	          <td width="87" bgcolor="#CCCCCC"><span style="padding-left:5px; padding-right:20px"><?php echo $get_s['s_monthworking'];  ?></span></td>
        	          <td width="93" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC">Months</td>
        	          <td width="92" bgcolor="#CCCCCC"><span style="padding-left:5px; padding-right:20px"><?php echo $get_s['s_yearworking']; ?></span></td>
        	          <td width="135" style="padding-left:5px; padding-right:20px" bgcolor="#CCCCCC">Years</td>
      	          </tr>
      	        </table>
				  </td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">Home Phone </td>
                  <td bgcolor="#CCCCCC"><?php echo $get_s['s_officeno']; ?></td>
                  <td align="left" style="padding-right:10px">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              <tr>
                	<td align="left" style="padding-right:10px">Email Address </td>
       	    		<td bgcolor="#CCCCCC"><?php echo $get_s['s_email']; ?></td>
                    <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
              <tr>
                	<td style="padding-right:10px">Relationship</td>
                  	<td valign="top" bgcolor="#CCCCCC"><?php echo $get_s['s_relationship']; ?></td>
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
                    </tr>
                	<tr>
                    	<th style="background:#333; color:#FFF">Name</th>
                        <th style="background:#333; color:#FFF">Relationship</th>
                        <th style="background:#333; color:#FFF">Work As</th>
                        <th style="background:#333; color:#FFF">Contact Number</th>
                        <th style="background:#333; color:#FFF">Address</th>
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
                    	<td bgcolor="#CCCCCC"><?php echo $get_r['r_name']; ?></td>
                        <td bgcolor="#CCCCCC"><?php echo $get_r['r_relationship']; ?></td>
                        <td bgcolor="#CCCCCC"><?php echo $get_r['r_workas']; ?></td>
                        <td bgcolor="#CCCCCC"><?php echo $get_r['r_contact']; ?></td>
                        <td bgcolor="#CCCCCC"><?php echo $get_r['r_address']; ?></td>
                    </tr>
                <?php 
					} 
				?>
				</tbody>
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
              <td width="32%" bgcolor="#CCCCCC"><?php echo $get_a['a_bankname']; ?></td>
              <td width="16%" align="left" style="padding-right:10px">Application Form </td>
              <td width="34%" bgcolor="#CCCCCC"><?php if($get_a['a_shoplotfile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_shoplotfile']; ?>" target="_blank"><img src="../img/view_source.png" width="20" /></a>
                  <?php } ?>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td width="18%" align="left" style="padding-right:10px">Branch</td>
              <td width="32%" bgcolor="#CCCCCC"><?php echo $get_a['a_bankbranch']; ?></td>
              <td align="left" style="padding-right:10px">I/C</td>
              <td bgcolor="#CCCCCC"><?php if($get_a['a_icfile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_icfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                  <?php } ?>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td align="left" style="padding-right:10px">Account Number</td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['a_bankaccno']; ?></td>
              <td align="left" style="padding-right:10px">Bank Statement</td>
              <td bgcolor="#CCCCCC"><?php if($get_a['a_bankfile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_bankfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                  <?php } ?>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td align="left" style="padding-right:10px">Account Holder Name</td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['a_name']; ?></td>
              <td align="left" style="padding-right:10px">Salary Slips </td>
              <td bgcolor="#CCCCCC"><?php if($get_a['a_payslipfile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_payslipfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                  <?php } ?>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td style="padding-right:10px">Internet Username </td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['internet_username']; ?></td>
              <td align="left" style="padding-right:10px">ATM Card </td>
              <td bgcolor="#CCCCCC"><?php if($get_a['a_atmfile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_atmfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
								 
						   }
						}
						closedir($handle);
					}
					
					?>
              </td>
            </tr>
            <tr>
              <td style="padding-right:10px">Internet Password </td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['internet_password']; ?></td>
              <td><span style="padding-right:10px">ATM Card Number</span></td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['a_pinno']; ?></td>
            </tr>
            <tr>
              <td align="left" style="padding-right:10px">I.C Number</td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['a_nric']; ?></td>
              <td align="left" style="padding-right:10px">Mortgage</td>
              <td bgcolor="#CCCCCC"><?php if($get_a['a_housefile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_housefile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                  <?php } ?>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td align="left" style="padding-right:10px">Pay date</td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['a_payday']; ?></td>
              <td align="left" style="padding-right:10px">Guarantor Form </td>
              <td bgcolor="#CCCCCC"><?php
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td style="padding-right:10px">Payment Date </td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['a_paymentdate']; ?> </td>
              <td align="left" style="padding-right:10px">Others</td>
              <td bgcolor="#CCCCCC"><?php if($get_a['a_landfile'] != '') { ?>
                  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_landfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
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
						?>
                  <a href="image.php?link=<?php echo $image_link; ?>" rel="shadowbox()"><img src="../img/view_source.png" width="20" /></a>
                  <?php
									 
							   }
							}
							closedir($handle);
						}
						
						?>
              </td>
            </tr>
            <tr>
              <td colspan="2" style="padding-right:10px"><strong>TRANSFER TO ACCOUNT 2 </strong></td>
              <td style="padding-right:10px">&nbsp;</td>
              <td rowspan="5"><table width="100%">
                  <tr>
                    <td>Left Thumb</td>
                    <td>Right Thumb</td>
                  </tr>
                  <tr>
                    <td width="50%" bgcolor="#CCCCCC"><?php if($get_a['a_lefthand'] != '') { ?>
                        <img src="<?php echo "../files/customer/".$id."/".$get_a['a_lefthand']; ?>" name="lh_img" id="lh_img" style="height:115px; width:115px" />
                        <?php } else { ?>
                        <img name="lh_img" id="lh_img" style="height:115px; width:115px" />
                        <?php } ?>
                        <input type="hidden" name="prev_lefthand" id="prev_lefthand" value="<?php echo $get_a['a_lefthand']; ?>" />
                    </td>
                    <td width="50%" bgcolor="#CCCCCC"><?php if($get_a['a_righthand'] != '') { ?>
                        <img src="<?php echo "../files/customer/".$id."/".$get_a['a_righthand']; ?>" name="rh_img" id="rh_img" style="height:115px; width:115px" />
                        <?php } else { ?>
                        <img name="rh_img" id="rh_img2" style="height:115px; width:115px" />
                        <?php } ?>
                        <input type="hidden" name="prev_righthand" id="prev_righthand" value="<?php echo $get_a['a_righthand']; ?>" />
                    </td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td style="padding-right:10px">Bank</td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['transfer_accountbank']; ?></td>
              <td style="padding-right:10px">&nbsp;</td>
            </tr>
            <tr>
              <td style="padding-right:10px">Account Number </td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['transfer_accountno']; ?></td>
              <td style="padding-right:10px">&nbsp;</td>
            </tr>
            <tr>
              <td style="padding-right:10px">Account Holder Name </td>
              <td bgcolor="#CCCCCC"><?php echo $get_a['transfer_accountholder']; ?></td>
              <td style="padding-right:10px">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" style="padding-right:10px"><input type="hidden" name="a_date" id="a_date" tabindex="14" value="<?php echo $get_a['a_date']; ?>" /></td>
              <td></td>
              <td style="padding-right:10px">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" style="padding-right:10px">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="padding-right:10px">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td style="padding-right:10px; padding-top:10px" valign="top">Notes</td>
              <td colspan="3" bgcolor="#CCCCCC"><?php echo $get_a['a_remarks']; ?></td>
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
    	<td><center>
    	    &nbsp;&nbsp;&nbsp;
    	    <input type="button" name="back" id="back" onClick="window.location.href='index.php'" value=""></center>
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