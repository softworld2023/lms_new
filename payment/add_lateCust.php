<?php include('../include/page_header.php'); 

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
#add_lateCust
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_lateCust:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<form method="post" action="action_lateCust.php" onSubmit="return checkForm()" autocomplete="off"><input type="hidden" name="package_id" id="package_id" value="<?php echo $id; ?>" />
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/apply-loan/apply-loan.png" width="56" height="47"></td>
        <td>Add New Bad Debt Customer </td>
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
    	<th>BD Date</th>
        <td><input type="date" name="bd_date" id="bd_date"/></td>
    </tr>
	<tr>
    	<th>Loan Code </th>
        <td><input type="text" name="loan_code" id="loan_code" onblur="showLoanDetails(this.value)" />
        
    <input type="hidden" name="loan_package" id="loan_package" value="32" /></td>
    </tr>
    
	<tr>
    	<th width="15%">Customer Code </th>
        <!--<td><input type="text" name="customer_code" id="customer_code" onblur="showCustDetails(this.value)" /><input type="hidden" name="customer_id" id="customer_id" /></td>-->
        <!-- 01 (13012017) - call function showDetails() when user key in customer code -->
		<td><input type="text" name="customer_code" id="customer_code" onblur="showDetails()" /><input type="hidden" name="customer_id" id="customer_id" /></td>
    </tr>
	
    <tr>
      <th>Customer Name </th>
      <td><input type="text" name="customer_name" id="customer_name" style="width:250px" onblur="showCustDetails3(this.value)" /></td>
    </tr>
    <tr>
    	<th>NRIC</th>
        <td><input type="text" name="nric" id="nric" onblur="showCustDetails2(this.value)" /></td>
    </tr>
    <tr>
      <th>Bad Debt Amount </th>
      <td valign="top"><input type="text" name="int_amount" id="int_amount" class="currency" /></td>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <td valign="top">&nbsp;</td>
    </tr>
    
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="add_lateCust" id="add_lateCust" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../payment/lateIntPayment.php'" value="">        </td>
    </tr>
    <tr>
    	<td height="16" colspan="2">&nbsp;</td> 
    </tr>
</table>
</form>
</center>

<script>
<!-- 02 (13012017) - call required php -->
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




function checkForm()
{
	if((document.getElementById('loan_code').value != '' && document.getElementById('loan_package').value != '' && document.getElementById('customer_id').value != '' && document.getElementById('int_amount').value != ''))
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