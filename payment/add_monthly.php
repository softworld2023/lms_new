<?php include('../include/page_header.php'); 
$id = isset($_GET['id']) ? $_GET['id'] : null;

// if ($id) {
//     // Fetch the specific customer record based on the provided ID
//     $cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
//     $get_cust = mysql_fetch_assoc($cust_q);
// } else {
//     // Display an alert and redirect back
//     echo "<script>
//         alert('Please select loan code or customer ID before proceeding.');
//          window.location.href = '../../lms_pwa_collection';
//     </script>";
//     exit; // Stop further execution
// }


// Fetch the specific customer record based on the provided ID
$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
$get_cust = mysql_fetch_assoc($cust_q);

// Fetch all customer records for datalist
$cust_list_q = mysql_query("SELECT customercode2, id, name, nric FROM customer_details");
?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
<style type="text/css">
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

select
{
	height:32px;
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
#add_monthly
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_monthly:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
</style>

<center>
<form method="post" action="action_payout_monthly.php" onSubmit="return checkForm()" autocomplete="off"><input type="hidden" name="package_id" id="package_id" value="<?php echo "32"; ?>" />
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/apply-loan/apply-loan.png" width="56" height="47"></td>
        <td>Add Monthly </td>
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
    	<th width="15%">Customer ID</th>
		<td>
			<?php if ($id): ?>
                <!-- Show specific customer information if ID is passed -->
                <input style="color:blue;" type="text" name="customer_code" id="customer_code" 
                       value="<?php echo $get_cust['customercode2']; ?>"/>
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $get_cust['id']; ?>" />
            <?php else: ?>
                <!-- Show a datalist to select a customer if no ID is passed -->
                <input style="color:blue;" type="text" name="customer_code" id="customer_code" list="customer_list" />
                <datalist id="customer_list">
                    <?php 

						while ($row = mysql_fetch_assoc($cust_list_q)): ?>
                        <option value="<?php echo $row['customercode2']; ?>">
                    <?php endwhile; ?>
                </datalist>
				<input type="hidden" name="customer_id" id="customer_id">
            <?php endif; ?>
		</td>
    </tr>
	
    <tr>
      <th>Customer Name </th>
      <td><input style ="color:blue;" type="text" name="customer_name" id="customer_name" style="width:250px" value="<?php echo $get_cust['name']; ?>" required/></td>
    </tr>
    <tr>
    	<th>NRIC</th>
        <td><input style ="color:blue;" type="text" name="nric" id="nric" value="<?php echo $get_cust['nric']; ?>" required/></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;Please select the agreement no/ Assign a code for this</td>
    </tr>
    	<tr>
    	<th>Agreement No</th>
        <td><input type="text" name="loan_code" id="loan_code" list="loancode" required>
			<datalist id="loancode">
			<?php if ($id): ?>
			<?php 
				$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_cust['id']."'");
                    while ($get_q = mysql_fetch_assoc($sql)) {
				?>
                    <option value="<?php echo $get_q['loan_code']; ?>"></option>
                <?php } ?>
            <?php endif; ?>
							</datalist>
        <input type="hidden" name="loan_package" id="loan_package" value="32" /></td>
    </tr>
    <tr>
    	<th>Payout Date</th>
        <td><input type="text" name="monthly_date" id="monthly_date" required autocomplete="off"></td>
    </tr>
    <tr>
    	<th>Month</th>
        <td><input type="text" name="month" id="month" required></td>
    </tr>
    <tr>
      <th>Payout Amount (RM) </th>
      <td valign="top"><input type="text" name="payout_amount" id="payout_amount" class="currency" required></td>
    </tr>
    <tr>
		<th>SD</th>
		<td>
			<input style="vertical-align: middle;" type="radio" name="sd" value="Normal" required><span>Normal (RM5)</span><br>
			<input style="vertical-align: middle;" type="radio" name="sd" value="Listing" required><span>Stamping List</span>
		</td>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <td valign="top">&nbsp;</td>
    </tr>
    
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="add_monthly" id="add_monthly" value="">&nbsp;&nbsp;&nbsp;
        <!-- <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp; --><input type="button" name="back" id="back" onClick="window.location.href='../payment/payment_monthly.php'" value="">        </td>
    </tr>
    <tr>
    	<td height="16" colspan="2">&nbsp;</td> 
    </tr>
</table>
</form>
</center>

<script>

function showDetails()
{
	$customer_code = document.getElementById('customer_code').value;
	
	$.ajax({
            url: 'getCustomerName.php',
            type: 'POST',
            data: {customer_code: $customer_code},
            success: function(data) {			
				document.getElementById('customer_name').value = data;
            }
        });
		
	$.ajax({
            url: 'getCustomerNric.php',
            type: 'POST',
            data: {customer_code: $customer_code},
            success: function(data) {			
				document.getElementById('nric').value = data;
            }
        });
}
</script>

<script>
$('#monthly_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 250,
		matchContains: true,
		selectFirst: false
	});
	
	$("#customer_code").autocomplete("auto_custCode.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	$("#nric").autocomplete("auto_custNric.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	 $("#loan_code").autocomplete("auto_loanCode.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
  
});


document.addEventListener("DOMContentLoaded", function() {
    const customerCodeField = document.getElementById('customer_code');
    const customerIdField = document.getElementById('customer_id');
    const customerNameField = document.getElementById('customer_name');
    const nricField = document.getElementById('nric');
	const loanCodeField = document.getElementById('loan_code');
    const loancodeDatalist = document.getElementById('loancode');

    // Listen for changes in the customer code field
    customerCodeField.addEventListener('change', function() {
        const selectedCode = customerCodeField.value;
		customerNameField.value = '';
		customerNameField.readOnly = false;
		customerIdField.value = '';
        nricField.value = '';
		nricField.readOnly = false;
        // Make an AJAX call to fetch customer details based on the selected customercode2
        if (selectedCode) {
            fetch(`get_customer_details.php?customercode=${selectedCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        // Populate the fields with the returned data
                        customerNameField.value = data.name;
						customerNameField.readOnly = true;
						customerIdField.value = data.id;
                        nricField.value = data.nric;
						nricField.readOnly = true;
                    } 
					// else {
                    //     alert("No customer found with the selected code.");
					// 	customerNameField.value = '';
					// 	customerIdField.value = '';
					// 	nricField.value = '';
					// 	customerCodeField.value = '';
                    // }
                })
                .catch(error => console.error('Error fetching customer details:', error));
        
				fetch(`get_loan_codes.php?customercode=${selectedCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        // Clear the existing options
                        loancodeDatalist.innerHTML = '';
                        
                        // Populate the loan codes datalist
                        data.forEach(loan => {
                            const option = document.createElement('option');
                            option.value = loan.loan_code;
                            loancodeDatalist.appendChild(option);
                        });
                    } else {
                        alert("No loan codes found for the selected customer.");
                    }
                })
                .catch(error => console.error('Error fetching loan codes:', error));
		}
    });
});


function checkForm()
{
	if((document.getElementById('loan_code').value != '' && document.getElementById('loan_package').value != '' && document.getElementById('payout_amount').value != ''))
	{
		$('#message').empty();
		return true;
	}else
	{
		var msg = "<div class='error'>Please fill in the form!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}
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


function showLoanDetails(str)
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
			if(res != '')
			{
				document.getElementById('loan_package').value = res[0];
				document.getElementById('customer_code').value = res[1];
				document.getElementById('customer_name').value = res[2];
				document.getElementById('nric').value = res[3];
				document.getElementById('customer_id').value = res[4];
			}
		}
	}
	xmlhttp.open("GET","get_loanDetails.php?q="+str,true);
	xmlhttp.send();
}

function showCustDetails(str)
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
			
			document.getElementById('customer_name').value = res[0];
			document.getElementById('nric').value = res[1];
			document.getElementById('customer_id').value = res[2];
		}
	}
	xmlhttp.open("GET","get_custDetails.php?q="+str,true);
	xmlhttp.send();
}

function showCustDetails2(str)
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
			
			document.getElementById('customer_name').value = res[0];
			document.getElementById('customer_code').value = res[1];
			document.getElementById('customer_id').value = res[2];
		}
	}
	xmlhttp.open("GET","get_custDetails2.php?q="+str,true);
	xmlhttp.send();
}

function showCustDetails3(str)
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
			
			document.getElementById('nric').value = res[0];
			document.getElementById('customer_code').value = res[1];
			document.getElementById('customer_id').value = res[2];
		}
	}
	xmlhttp.open("GET","get_custDetails3.php?q="+str,true);
	xmlhttp.send();
}

</script>
</body>
</html>