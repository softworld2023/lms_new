<?php

include('../include/page_header.php');
$id = $_GET['id'];

$cust_q = mysql_query("SELECT customer_details.*, customer_address.mobile_contact FROM customer_details 
						LEFT JOIN customer_address ON  customer_details.id = customer_address.customer_id
						WHERE customer_details.id = '" . $id . "'");
$get_cust = mysql_fetch_assoc($cust_q);
?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
<style type="text/css">
	.submit_style {
		color: #eee;
		padding: 4px;
		border: none;
		background: transparent url("<?php echo IMAGE_PATH . 'remove.png'; ?>") no-repeat;
		cursor: pointer;
		background-size: 21px 21px;
		text-indent: -1000em;
		width: 25px;
	}

	input {
		height: 30px;

	}

	select {
		height: 32px;
	}

	.app_style {
		color: #eee;
		padding: 4px;
		border: none;
		background: transparent url("<?php echo IMAGE_PATH . 'sent.png'; ?>") no-repeat;
		cursor: pointer;
		background-size: 21px 21px;
		text-indent: -1000em;
		width: 25px;
	}

	.reject_style {
		color: #eee;
		padding: 4px;
		border: none;
		background: transparent url("<?php echo IMAGE_PATH . 'cancel-icon.png'; ?>") no-repeat;
		cursor: pointer;
		background-size: 21px 21px;
		text-indent: -1000em;
		width: 25px;
	}

	#list_table {
		border-collapse: collapse;
		border: none;
	}

	#list_table tr th {
		height: 35px;
		text-align: right;
		padding-left: 20px;
		padding-right: 10px;
	}

	#list_table tr td {
		height: 35px;
		padding-left: 10px;
		padding-right: 10px;
	}

	#rl {
		width: 318px;
		height: 36px;
		background: url(../img/customers/right-left.jpg);
	}

	#back {
		background: url(../img/back-btn.jpg);
		width: 109px;
		height: 30px;
		border: none;
		cursor: pointer;
	}

	#back:hover {
		background: url(../img/back-btn-roll-over.jpg);
	}

	#reset {
		background: url(../img/add-enquiry/clear-btn.jpg);
		width: 109px;
		height: 30px;
		border: none;
		cursor: pointer;
	}

	#reset:hover {
		background: url(../img/add-enquiry/clear-btn-roll-over.jpg);
	}

	#add_instalment {
		background: url(../img/add-enquiry/submit-btn.jpg);
		width: 109px;
		height: 30px;
		border: none;
		cursor: pointer;
	}

	#add_instalment:hover {
		background: url(../img/add-enquiry/submit-roll-over.jpg);
	}
</style>

<center>
	<form method="post" action="./save_instalment_action.php" onSubmit="return checkForm()"  enctype="multipart/form-data" autocomplete="off">
		<table width="1280">
			<tr>
				<td width="65"><img src="../img/apply-loan/apply-loan.png" width="56" height="47"></td>
				<td>Add Instalment </td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="message" style="width:100%;">
						<?php
						if ($_SESSION['msg'] != '') {
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
				<td colspan="2">
					<div id="message3"></div>
				</td>
			</tr>
			<input type="hidden" name="loanpackagetype" id="loanpackagetype" value="NEW LOAN" />
			<input type="hidden" name="previous_settlement_date" id="previous_settlement_date" tabindex="16" />
			<tr style="display:none;" id="tr_remark">
				<td><input type="text" name="custRemark" id="other_remark" size="16" placeholder="Others" autocomplete="off" /><input type="hidden" name="loan_type" id="loan_type1" value="Fixed Loan" /></td>
			</tr>
			<tr>
				<td width="15%"><b>Customer ID </b></td>
				<td>
					<!-- <input style="color:blue;" type="text" name="customer_code" id="customer_code" value="<?php echo $get_cust['customercode2']; ?>" onblur="checkcode(this.value)"/> -->
					<input style="color:blue;" type="text" name="customer_code" id="customer_code" value="<?php echo $get_cust['customercode2']; ?>" <?php echo !empty($get_cust['customercode2']) ? 'readonly' : ''; ?>/>
					<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $get_cust['id']; ?>" />
				</td>
			</tr>

            <td><input type="text" name="name" id="name" style="width:300px" tabindex="1" hidden value="<?php echo isset($get_cust['name']) ? htmlspecialchars($get_cust['name'], ENT_QUOTES, 'UTF-8') : null; ?>" /></td>
            <td><input type="text" name="nric" id="nric" style="width:300px"  tabindex="1" onblur="checkIC(this.value)" hidden value="<?php echo $get_cust['nric']; ?>"/></td>
            <td><input type="text" name="mobile_contact" style="width:300px"  id="mobile_contact" tabindex="4" hidden value="<?php echo $get_cust['mobile_contact']; ?>"/></td> 

			<tr>
				<td><b>Package</b></td>
				<td><?php $scheme_q = mysql_query("SELECT * FROM loan_scheme"); ?>
					<input type="text" name="loan_package" id="loan_package" value="NEW PACKAGE" readonly autocomplete="OFF" />
				</td>
			</tr>
			<tr>
				<td><b>Loan Amount</b></td>
				<td>
					<input type="text" name="loan_amount" id="loan_amount" placeholder="RM" class="currenciesOnly" tabindex="16" onkeyup="hideloanamt(this.value)" autocomplete="OFF" />
					<input type="hidden" name="hide_loanamt" id="hide_loanamt" />
				</td>
			</tr>
			<tr>
				<td><b>Agreement No<b></td>
				<td><input type="text" name="loan_code" id="loan_code" tabindex="16" autocomplete="OFF" onblur="checkLoanCode(this.value)" /></td>
			</tr>
			<tr>
				<td><b>Loan Period (months) <b></td>
				<td><input type="text" name="loan_period" id="loan_period" tabindex="16" onkeyup="calculateAmountMonth()" autocomplete="OFF" /></td>
			</tr>
			<tr>
				<td><b>Start Date</b></td>
				<td><input type="month" name="start_date" id="start_date" tabindex="16" autocomplete="OFF" /></td>
			</tr>
			<tr>
				<!-- <td>Loan Pokok (RM) </td> -->
				<td><input type="hidden" name="loan_pokok" id="loan_pokok" readonly="readonly" /></td>
			</tr>
			<tr>
				<!-- <td>Amount Monthly (RM) </td> -->
				<td><input type="hidden" name="loan_amount_month" id="loan_amount_month" readonly="readonly" style="color:black;font-weight:bold;" /></td>
			</tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" name="apply_loan" id="add_instalment" value="">&nbsp;&nbsp;&nbsp;
					<input type="button" name="back" id="back" onClick="window.location.href='../customers/index.php'" value=""></td>
				</tr>
		</table>
	</form>
</center>

<script>
	function showDetails() {
		$customer_code = document.getElementById('customer_code').value;
		$.ajax({
			url: 'getCustomerName.php',
			type: 'POST',
			data: {
				customer_code: $customer_code
			},
			success: function(data) {
				document.getElementById('customer_name').value =
					data;
			}
		});
		$.ajax({
			url: 'getCustomerNric.php',
			type: 'POST',
			data: {
				customer_code: $customer_code
			},
			success: function(data) {
				document.getElementById('nric').value =
					data;
			}
		});
	}

	function hideloanamt(v) {
		document.getElementById('hide_loanamt').value = v;
	}

	$("#loan_period,#loan_amount").on("blur", function() {

		var loan_package = $("#loan_package").val();
		var loan_amount = $("#loan_amount").val();
		var loan_period = $("#loan_period").val();
		var dataString = 'loan_amount=' + loan_amount + '&loan_period=' + loan_period;
		if (loan_package == 'NEW PACKAGE') {

			$.ajax({
				url: '../customers/autofill_newpackage.php',
				type: "post",
				data: dataString,
				cache: true,
				success: function(result) {
					if (result != "") {
						var parsed = jQuery.parseJSON(result);
						$("#loan_amount_month").val(parsed[0]);
						$("#loan_pokok").val(parsed[1]);
						console.log(result);
					}
				}
			})
		}
	});


	function checkForm() {
		if ((document.getElementById('loan_code').value != '' && document.getElementById('loan_package').value != '' )) {
			$('#message').empty();
			return true;
		} else {
			var msg = "<div class='error'>Please fill in the form!</div>";
			$('#message').empty();
			$('#message').append(msg);
			$('#message').html();
			return false;
		}
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
	
	// mini jQuery plugin that formats to two decimal places
	(function($) {
		$.fn.currencyFormat = function() {
			this.each(function(i) {
				$(this).change(function(e) {
					if (isNaN(parseFloat(this.value))) return;
					this.value = parseFloat(this.value).toFixed(2);
				});
			});
			return this; //for chaining
		}
	})(jQuery);

	// apply the currencyFormat behaviour to elements with 'currency' as their class
	$(function() {
		$('.currency').currencyFormat();
	});

</script>
</body>

</html>