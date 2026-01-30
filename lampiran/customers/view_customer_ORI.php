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
       	  <table width="85%" id="table_form">
            	<tr>
                	<td colspan="4">
                    	<div id="message"></div>
					</td>
                </tr>
				<tr>
                  <td align="left" style="padding-right:10px">Customer Code</td>
                    <td bgcolor="#CCCCCC">
                       <?php if($get_q['customercode2'] != '') { echo $get_q['customercode2']; } ?>
                    </td>
                    <td align="right" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC">&nbsp;</td>
                </tr>
                <tr>
               	  <td width="18%" align="left" style="padding-right:10px">BIS / CTOS / CCRIS</td>
                    <td width="27%" bgcolor="#CCCCCC"><?php echo $get_q['bis']; ?></td>
                	<td width="20%" align="left" style="padding-right:10px">Sex</td>
                    <td width="35%" bgcolor="#CCCCCC">
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="gender" id="gender" value="Male" <?php if($get_q['gender'] == 'Male') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Male</td>
                            	<td><input type="radio" name="gender" id="gender" value="Female" <?php if($get_q['gender'] == 'Female') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Female</td>
                            </tr>
                        </table>
                    </td>
            	</tr>
                <tr>
               	  <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC">
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="title" id="title" value="Mr." <?php if($get_q['title'] == 'Mr.') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Mr.</td>
                            	<td><input type="radio" name="title" id="title" value="Miss" <?php if($get_q['title'] == 'Miss') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Miss</td>
                                <td><input type="radio" name="title" id="title" value="Madam" <?php if($get_q['title'] == 'Madam') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Madam</td>
                            	<td><input type="radio" name="title" id="title" value="Dr." <?php if($get_q['title'] == 'Dr') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Dr.</td>
                                <td><input type="radio" name="title" id="title" value="Others" <?php if($get_q['title'] == 'Others') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Others</td>
                            </tr>
                        </table>
                    </td>
                    <td align="left" style="padding-right:10px">Race</td>
                    <td bgcolor="#CCCCCC">
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="race" id="race" value="Malay" onClick="raceOther2();" <?php if($get_q['race'] == 'Malay') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Malay</td>
                            	<td><input type="radio" name="race" id="race" value="Chinese" onClick="raceOther2();" <?php if($get_q['race'] == 'Chinese') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Chinese</td>
                                <td><input type="radio" name="race" id="race" value="Indian" onClick="raceOther2();" <?php if($get_q['race'] == 'Indian') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Indian</td>
                            </tr>
                        </table>
                	</td>
                </tr>
                <tr>
               	  <td align="left" style="padding-right:10px">Full Name As In NRIC</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['name']; ?></td>
                    <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC">
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="race" id="race" value="Others" onClick="raceOther();" <?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Others</td>
                            	<td><?php if($get_q['race'] == 'Malay' || $get_q['race'] == 'Chinese' || $get_q['race'] == 'Indian') { echo 'disabled'; } if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo $get_q['race']; } ?><input type="hidden" name="p_race_other" id="p_race_other" value="<?php if($get_q['race'] != 'Malay' && $get_q['race'] != 'Chinese' && $get_q['race'] != 'Indian') { echo $get_q['race']; } ?>" ></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
               	  <td align="left" style="padding-right:10px">Date of Birth</td>
                    <td bgcolor="#CCCCCC"><?php if($get_q['dob'] != '0000-00-00') { echo $get_q['dob']; } ?></td>
                    <td align="left" style="padding-right:10px">Marital Status</td>
                    <td bgcolor="#CCCCCC">
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="marital_status" id="marital_status" value="Single" <?php if($get_q['marital_status'] == 'Single') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Single</td>
                            	<td><input type="radio" name="marital_status" id="marital_status" value="Married" <?php if($get_q['marital_status'] == 'Married') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Maried</td>
                                <td><input type="radio" name="marital_status" id="marital_status" value="Widowed / Divorced" <?php if($get_q['marital_status'] == 'Widowed / Divorced') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:20px">Widowed / Divorced</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
               	  <td align="left" style="padding-right:10px">Nationality</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['nationality']; ?></td>
                    <td align="left" style="padding-right:10px">No. of Dependents</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['no_dependents']; ?></td>
                </tr>
                <tr>
               	  <td align="left" style="padding-right:10px">Old I.C</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['old_ic']; ?></td>
                    <td align="left" style="padding-right:10px">Academic Qualification</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['academic_qualification']; ?></td>
                </tr>
                <tr>
               	  <td align="left" style="padding-right:10px">New I.C</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['nric']; ?></td>
                    <td align="left" style="padding-right:10px">Mother's Name</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_q['mother_name']; ?></td>
                </tr>
                <tr>
               	  <td align="right" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC">&nbsp;</td>
                    <td align="right" style="padding-right:10px">&nbsp;</td>
                    <td valign="top" bgcolor="#CCCCCC"><span style="color:#999; font-size:12px">(This is compulsary for security purposes)</span></td>
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
       	  	<table width="85%" id="table_form">
            	<tr>
                	<td colspan="4">
                    	<div id="message2"></div>
                    </td>
                </tr>
                <tr>
                	<td width="15%" align="left" style="padding-right:10px">Residence Address</td>
                    <td width="39%" bgcolor="#CCCCCC"><?php echo $get_add['address1']; ?></td>
                	<td width="16%" align="left" style="padding-right:10px">Mobile Contact</td>
                    <td width="30%" bgcolor="#CCCCCC"><?php echo $get_add['mobile_contact']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['address2']; ?></td>
                    <td align="left" style="padding-right:10px">I/C Address</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['m_address1']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['address3']; ?></td>
                    <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['m_address2']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                    <td valign="top" bgcolor="#CCCCCC"><span style="color:#999; font-size:12px">(As Per I.C)</span></td>
                    <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['m_address3']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Postcode</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['postcode']; ?></td>
                    <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td valign="top" bgcolor="#CCCCCC"><span style="color:#999; font-size:12px">(As Per I.C)</span></td>
                </tr>
                <tr>
                  <td align="left" style="padding-right:10px">City</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_add['city']; ?></td>
                  <td align="left" style="padding-right:10px">Postcode</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_add['m_postcode']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">State</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_a['state']; ?></td>
                    <td align="left" style="padding-right:10px">City</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['m_city']; ?></td>
                </tr>
                <tr>
               	  <td rowspan="2" align="left" valign="top" style="padding-right:10px; padding-top:10px">Residence is</td>
              <td rowspan="2" bgcolor="#CCCCCC">
                    	<table width="100%" id="radio_table">
                        	<tr>
                            	<td width="2%"><input type="radio" name="residence" id="residence" value="Owned-No-Loan" <?php if($get_add['residence'] == 'Owned-No-Loan') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Owned-No-Loan</td>
                            	<td width="2%"><input type="radio" name="residence" id="residence" value="Rented" <?php if($get_add['residence'] == 'Rented') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Rented</td>
                            </tr>
                            <tr>
                            	<td width="2%"><input type="radio" name="residence" id="residence" value="Owned-With-Loan" <?php if($get_add['residence'] == 'Owned-With-Loan') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Owned-With-Loan</td>
                            	<td width="2%"><input type="radio" name="residence" id="residence" value="Employers" <?php if($get_add['residence'] == 'Employers') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Employer's</td>
                            </tr>
                            <tr>
                            	<td width="2%"><input type="radio" name="residence" id="residence" value="Parents / Relatives" <?php if($get_add['residence'] == 'Parents / Relatives') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Parent's / Relative's</td>
                            	<td width="2%"></td>
                                <td width="48%" style="padding-left:5px"></td>
                            </tr>
                        </table>
                    </td>
                    <td align="left" style="padding-right:10px">State</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_a['m_state']; ?></td>
                </tr>
                 <tr>
                	<td rowspan="2" align="left" valign="top" style="padding-right:10px; padding-top:10px">Residence is</td>
                    <td rowspan="2" bgcolor="#CCCCCC">
                    	<table width="100%" id="radio_table">
                        	<tr>
                            	<td width="2%"><input type="radio" name="m_residence" id="m_residence" value="Owned-No-Loan" <?php if($get_add['m_residence'] == 'Owned-No-Loan') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Owned-No-Loan</td>
                            	<td width="2%"><input type="radio" name="m_residence" id="m_residence" value="Rented" <?php if($get_add['m_residence'] == 'Rented') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Rented</td>
                            </tr>
                            <tr>
                            	<td width="2%"><input type="radio" name="m_residence" id="m_residence" value="Owned-With-Loan" <?php if($get_add['m_residence'] == 'POwned-With-Loan') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Owned-With-Loan</td>
                            	<td width="2%"><input type="radio" name="m_residence" id="m_residence" value="Employers" <?php if($get_add['m_residence'] == 'Employers') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Employer's</td>
                            </tr>
                            <tr>
                            	<td width="2%"><input type="radio" name="m_residence" id="m_residence" value="Parents / Relatives" <?php if($get_add['m_residence'] == 'Parents / Relatives') { echo 'checked'; } ?>></td>
                                <td width="48%" style="padding-left:5px">Parent's / Relative's</td>
                            	<td width="2%"></td>
                                <td width="48%" style="padding-left:5px"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                 <tr>
                	<td align="left" style="padding-right:10px">Year Stay</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['year_stay']; ?> years &nbsp;&nbsp;<?php echo $get_add['month_stay']; ?> months</td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Home Contact</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['home_contact']; ?></td>
                    <td align="left" style="padding-right:10px">Year Stay</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_add['m_year_stay']; ?> years &nbsp;&nbsp; <?php echo $get_add['m_year_stay']; ?> months</td>
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
                	<td width="15%" align="left" style="padding-right:10px">Name of Company</td>
                    <td width="39%" bgcolor="#CCCCCC"><?php echo $get_c['company']; ?></td>
               	  <td width="16%" align="left" style="padding-right:10px">Postcode</td>
                    <td width="30%" bgcolor="#CCCCCC"><?php echo $get_c['c_postcode']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Department</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['department']; ?></td>
               	  <td align="left" style="padding-right:10px">City</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_city']; ?></td>
            	</tr>
                <tr>
                  <td align="left" style="padding-right:10px">Position</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_c['position']; ?></td>
                  <td align="left" style="padding-right:10px">State</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_c['c_state']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Nature Business</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['nature_business']; ?></td>
               	  <td align="left" style="padding-right:10px">Office Contact</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_contactno']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Address</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_address1']; ?></td>
               	  <td align="left" style="padding-right:10px">- Ext.</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_ext']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_address2']; ?></td>
               	  <td align="left" style="padding-right:10px">Year Working</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_yearworking']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_address3']; ?></td>
               	  <td align="left" style="padding-right:10px">&nbsp;</td>
                    <td bgcolor="#CCCCCC">
                    	<table id="radio_table">
                        	<tr>
                            	<td><input type="radio" name="c_workingtype" id="c_workingtype" value="Self Employed" <?php if($get_c['c_workingtype'] == 'Self Employed') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:15px">Self Employed</td>
                            	<td><input type="radio" name="c_workingtype" id="c_workingtype" value="Salaried Employed" <?php if($get_c['c_workingtype'] == 'Salaried Employed') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px; padding-right:15px">Salaried Employed</td>
                                <td><input type="radio" name="c_workingtype" id="c_workingtype" value="Contract Staff" <?php if($get_c['c_workingtype'] == 'Contract Staff') { echo 'checked'; } ?>></td>
                                <td style="padding-left:5px">Contract Staff</td>
                            </tr>
                        </table>
                    </td>
            	</tr>
                <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
                	<td align="left" style="padding-right:10px">Email Address</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_c['c_email']; ?></td>
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
                	<td width="15%" align="left" style="padding-right:10px">Monthly Nett Salary</td>
                    <td width="39%" bgcolor="#CCCCCC"><?php echo $get_f['net_salary']; ?></td>
               	  <td width="16%" align="left" style="padding-right:10px">House Installment Repayment</td>
                    <td width="30%" bgcolor="#CCCCCC"><?php echo $get_f['house_installment']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Total Credit Card Repayment</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['total_cc']; ?></td>
                  <td align="left" style="padding-right:10px">Monthly Personal Loan Repayment</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['personal_loan']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Car Installment Repayment</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['car_installment']; ?></td>
                  <td align="left" style="padding-right:10px">Bank Loan</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['bank_loan']; ?></td>
                </tr>
                <tr>
                	<td align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="left" style="padding-right:10px">Remarks</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_f['remarks']; ?></td>
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
                	<td width="15%" align="left" style="padding-right:10px">Contact Person</td>
                    <td width="39%" bgcolor="#CCCCCC"><?php echo $get_e['e_contactperson']; ?></td>
               	  <td width="16%" align="left" style="padding-right:10px">Company Name</td>
                    <td width="30%" bgcolor="#CCCCCC" style="color: #CCCCCC"><?php echo $get_e['e_company']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Relationship</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_e['e_relationship']; ?></td>
                  <td align="left" style="padding-right:10px">Work As</td>
                    <td bgcolor="#CCCCCC" style="color: #CCCCCC"><?php echo $get_e['e_workas']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Contact Number</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_e['e_contactno']; ?></td>
                  <td align="left" style="padding-right:10px">Office Contact</td>
                    <td bgcolor="#CCCCCC" style="color: #CCCCCC"><?php echo $get_e['e_officeno']; ?></td>
              </tr>
              <tr>
                	<td align="left" style="padding-right:10px">Residence Address</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_e['e_address1']; ?></td>
                <td align="left" style="padding-right:10px">- Ext.</td>
                    <td bgcolor="#CCCCCC" style="color: #CCCCCC"><?php echo $get_e['e_ext']; ?></td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_e['e_address2']; ?></td>
                <td align="right" style="padding-right:10px">&nbsp;</td>
                    <td>&nbsp;</td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                  <td bgcolor="#CCCCCC"><?php echo $get_e['e_address3']; ?></td>
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
                	<td width="15%" align="left" style="padding-right:10px">Full Name As In NRIC</td>
                    <td width="40%" bgcolor="#CCCCCC"><?php echo $get_s['s_name']; ?></td>
               	  <td width="15%" align="left" style="padding-right:10px">Work As</td>
                    <td width="30%" bgcolor="#CCCCCC"><?php echo $get_s['s_workas']; ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Old I.C</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_s['s_oldic']; ?></td>
                    <td align="left" style="padding-right:10px">Relationship</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_s['s_relationship']; ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">New I.C</td>
                  	<td bgcolor="#CCCCCC"><?php echo $get_s['s_nric']; ?></td>
                  <td align="left" style="padding-right:10px">Office Contact</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_s['s_officeno']; ?></td>
              </tr>
              <tr>
                	<td align="left" style="padding-right:10px">Company Name</td>
               	  <td bgcolor="#CCCCCC"><?php echo $get_s['s_company']; ?></td>
                <td align="left" style="padding-right:10px">Mobile Contact</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_s['s_mobile']; ?></td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                  	<td valign="top"><span style="color:#999; font-size:12px">(If Any)</span></td>
                    <td align="right" style="padding-right:10px">&nbsp;</td>
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
                    	<td><input type="text" name="r_name_<?php echo $ctr_r; ?>" id="r_name_<?php echo $ctr_r; ?>" style="width:300px;" tabindex="13" value="<?php echo $get_r['r_name']; ?>" readonly="readonly"></td>
                        <td><input type="text" name="r_relationship_<?php echo $ctr_r; ?>" id="r_relationship_<?php echo $ctr_r; ?>" tabindex="13" value="<?php echo $get_r['r_relationship']; ?>" readonly="readonly"></td>
                        <td><input type="text" name="r_workas_<?php echo $ctr_r; ?>" id="r_workas_<?php echo $ctr_r; ?>" tabindex="13" value="<?php echo $get_r['r_workas']; ?>" readonly="readonly"></td>
                        <td><input type="text" name="r_contact_<?php echo $ctr_r; ?>" id="r_contact_<?php echo $ctr_r; ?>" tabindex="13" value="<?php echo $get_r['r_contact']; ?>" readonly="readonly"></td>
                        <td><input type="text" name="r_address_<?php echo $ctr_r; ?>" id="r_address_<?php echo $ctr_r; ?>" style="width:300px;" tabindex="13" value="<?php echo $get_r['r_address']; ?>" readonly="readonly"></td>
                        <td><img src="../img/customers/delete-icon.png" width="20" id="del_<?php echo $ctr_r; ?>" name="del_<?php echo $ctr_r; ?>" onclick="deleteRow('<?php echo $ctr_r; ?>');" style="cursor:pointer;"></td>
                    </tr>
                <?php 
					} 
				?>
				</tbody>
                    <tr>
                    	<td>&nbsp;</td>
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
                	<td width="14%" align="left" style="padding-right:10px">Branch Bank Account Open</td>
                    <td width="41%" bgcolor="#CCCCCC"><?php echo $get_a['a_bankbranch']; ?></td>
               	  <td width="14%" align="left" style="padding-right:10px">I.C</td>
                  <td width="31%" bgcolor="#CCCCCC">
					<?php if($get_a['a_icfile'] != '') { ?>
                   	  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_icfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_icfile" id="prev_icfile" value="<?php echo $get_a['a_icfile']; ?>" />&nbsp;	
					<?php } ?></td>
            	</tr>
                <tr>
                	<td align="left" style="padding-right:10px">Payday</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_a['a_payday']; ?></td>
                  <td align="left" style="padding-right:10px">Bank Statement</td>
                    <td bgcolor="#CCCCCC">
                    <?php if($get_a['a_bankfile'] != '') { ?>
                    	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_bankfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <?php echo $get_a['a_bankfile']; ?>&nbsp;
                    <?php } ?></td>
                </tr>
                <tr>
                	<td align="left" style="padding-right:10px">Name</td>
                  	<td bgcolor="#CCCCCC"><?php echo $get_a['a_name']; ?></td>
                  <td align="left" style="padding-right:10px">Payslips</td>
                  <td bgcolor="#CCCCCC">
                    <?php if($get_a['a_payslipfile'] != '') { ?>
                   	  <a href="<?php echo "../files/customer/".$id."/".$get_a['a_payslipfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_payslipfile" id="prev_payslipfile" value="<?php echo $get_a['a_payslipfile']; ?>" />&nbsp;
                    <?php } ?></td>
              </tr>
              <tr>
                	<td align="left" style="padding-right:10px">I.C Number</td>
                  	<td bgcolor="#CCCCCC"><?php echo $get_a['a_nric']; ?></td>
                <td align="left" style="padding-right:10px">Upload ATM</td>
                <td bgcolor="#CCCCCC">
                    <?php if($get_a['a_atmfile'] != '') { ?>
                   	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_atmfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_atmfile" id="prev_atmfile" value="<?php echo $get_a['a_atmfile']; ?>" />&nbsp;
                  <?php } ?></td>
              </tr>
              <tr>
                	<td align="left" style="padding-right:10px">Date</td>
                  	<td bgcolor="#CCCCCC"><?php echo $get_a['a_date']; ?></td>
                <td align="left" style="padding-right:10px">Pin Number</td>
                    <td bgcolor="#CCCCCC"><?php echo $get_a['a_pinno']; ?></td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
               	<td>&nbsp;</td>
                    <td colspan="2" align="right" style="padding-right:10px; text-align: center; font-weight: bold; color: #003; font-size: 18px;">Mortgage</td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                  	<td rowspan="3">
                    	<table width="100%">
                        	<tr>
                            	<td width="50%">&nbsp;</td>
                                <td width="50%">&nbsp;</td>
                            </tr>
                        </table>                    </td>
                    <td align="left" style="padding-right:10px">House Sales &amp; Purchase</td>
                <td bgcolor="#CCCCCC"><?php if($get_a['a_housefile'] != '') { ?>
               	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_housefile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_housefile" id="prev_housefile" value="<?php echo $get_a['a_housefile']; ?>" />&nbsp;
                  <?php } ?></td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
                  	<td align="left" style="padding-right:10px">Land Title</td>
                <td bgcolor="#CCCCCC">
                    <?php if($get_a['a_landfile'] != '') { ?>
                   	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_landfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_landfile" id="prev_landfile" value="<?php echo $get_a['a_landfile']; ?>" />&nbsp;
                  <?php } ?></td>
              </tr>
              <tr>
                	<td align="right" style="padding-right:10px">&nbsp;</td>
               	<td align="left" style="padding-right:10px">Shop Lot Sales &amp; Purchase</td>
                <td bgcolor="#CCCCCC">
                    <?php if($get_a['a_shoplotfile'] != '') { ?>
                   	<a href="<?php echo "../files/customer/".$id."/".$get_a['a_shoplotfile']; ?>" rel="shadowbox"><img src="../img/view_source.png" width="20" /></a>
                        <input type="hidden" name="prev_shoplotfile" id="prev_shoplotfile" value="<?php echo $get_a['a_shoplotfile']; ?>" />&nbsp;
                  <?php } ?></td>
              </tr>
			  <tr>
                	<td width="14%" align="right" style="padding-right:10px">&nbsp;</td>
                    <td width="41%">&nbsp;</td>
                	<td width="14%" align="left" style="padding-right:10px">Remarks</td>
                    <td width="31%"><textarea name="a_remarks" id="a_remarks" style="width:300px; height:50px"><?php echo $get_a['a_remarks']; ?></textarea></td>
           	  </tr>
            </table>
        </center>
        </td>
    </tr>
    <tr height="35">
    	<td><input type="button" name="back" id="back" onclick="window.location.href='index.php'" value="" /></td>
    </tr>
    <tr height="35">
    	<td>&nbsp;</td>
    </tr>
    <tr height="35">
    	<td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>
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

$('#dob').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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

$('#a_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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

/*$('#a_payday').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
	$("#a_pinno").mask("999999");

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
Shadowbox.init();
</script>