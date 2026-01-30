<?php 
include('../include/page_header.php'); 
// error_reporting(E_ERROR | E_PARSE);

if(isset($_POST['search']))   // click search
{
	$cond = '';
		
		if($_POST['customer_name'] != '')
		{
			$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".$_POST['customer_name']."'");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= " and id = '".$cust['id']."'";	
		}
		
		
		
		if($_POST['customer_code'] != '')
		{
			
			$code_sql = mysql_query("SELECT * FROM customer_details 
									WHERE customercode2 = '".$_POST['customer_code']."'");
			$code = mysql_fetch_assoc($code_sql);
			$cond .= " and id = '".$code['id']."'";	

		}
		
		$statement = "`customer_details` WHERE customercode2!='' $cond
						GROUP BY customercode2
						";
						
}
else    // not click search and also no history result
{
	$statement = "`customer_details` WHERE customercode2!='' $cond
						GROUP BY customercode2";
}


$sql_q = ("SELECT * FROM {$statement}");
$sql = mysql_query($sql_q);

     
?>

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

#list_table
{
	border-collapse:collapse;
	border:none;	
}

#list_table tr th
{
	height:36px;
	background:#666;
	text-align:left;
	padding-left:10px;
	color:#FFF;
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
#search
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search:hover
{
	background:url(../img/enquiry/search-btn-roll-over.jpg);
}
#yearlyLedger {
  background: #fa900f;
  background-image: -webkit-linear-gradient(top, #fa900f, #fa900f);
  background-image: -moz-linear-gradient(top, #fa900f, #fa900f);
  background-image: -ms-linear-gradient(top, #fa900f, #fa900f);
  background-image: -o-linear-gradient(top, #fa900f, #fa900f);
  background-image: linear-gradient(to bottom, #fa900f, #fa900f);
  font-family: Arial;
  color: #ffffff;
  font-size: 14px;
  padding: 8px 20px 8px 20px;
  border: solid #ffbb0f 0px;
  text-decoration: none;
  cursor:pointer;
}

#yearlyLedger:hover {
  background: #f5a94c;
  background-image: -webkit-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -moz-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -ms-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -o-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: linear-gradient(to bottom, #f5a94c, #f5a94c);
  text-decoration: none;
}

.customBtn {
   border-top: 1px solid #96d1f8;
   background: #fa9c00;
   background: -webkit-gradient(linear, left top, left bottom, from(#cc7e00), to(#fa9c00));
   background: -webkit-linear-gradient(top, #cc7e00, #fa9c00);
   background: -moz-linear-gradient(top, #cc7e00, #fa9c00);
   background: -ms-linear-gradient(top, #cc7e00, #fa9c00);
   background: -o-linear-gradient(top, #cc7e00, #fa9c00);
   padding: 5px 10px;
   -webkit-border-radius: 8px;
   -moz-border-radius: 8px;
   border-radius: 8px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   }
.customBtn:hover {
   border-top-color: #fa9c00;
   background: #fa9c00;
   color: #ccc;
   }
.customBtn:active {
   border-top-color: #fa9c00;
   background: #fa9c00;
   }	
   
  .customBtn a {
   color: #FFFFFF;
   }
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Monthly Payment</td>
        <td align="right">
       	<form action="payment_monthly.php" method="post">
        	<table>
				<tr>
                    <td align="right" style="padding-right:10px">Customer ID</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px"/></td>
					<td align="right" style="padding-right:10px">Customer Name</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value=""/>
					</td>
                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr><td colspan="7" style="text-align:right;"><input type="submit" id="search_1" name="search" value="Show all list"/></td></tr>

            </table>
        </form>
        </td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php" >Ledger Listing</a>
			<a href="payment_monthly.php" id="active-menu">Monthly Listing</a>
			<a href="payment_instalment.php">Instalment Listing</a>
			<a href="lateIntPayment.php">Late Payment Listing</a>
			<a href="collection.php">Collection</a>
			<a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="close_listing.php">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
			<a href="return_book_monthly.php">Return Book (Monthly)</a>
            <a href="return_book_instalment.php">Return Book (Instalment)</a>
			<a href="account_book_monthly.php">Account Book (Monthly)</a>
            <a href="account_book_instalment.php">Account Book (Instalment)</a>
			<!-- <?php if($skimkutu != 0){ ?><a href="skimKutu.php">Skim Kutu </a><?php } ?>
			<?php if($kutuOffice != 0) {?><a href="kutuOffice.php">Kutu Office</a> --><?php } ?>
		</div>
<!-- 		<div style="float:right">
			<a href="index_show_all.php">Show All List</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="yearlyledger.php"><input type="button" value="Yearly Ledger" id="yearlyLedger" /></a>
		</div> -->
        </td>
    </tr>
	
    <tr>
    	<td colspan="3">
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
    	<th>No.</th>
    	<th>Customer ID</th>
    	<th>Customer Name</th>
        <th>Company</th>
        <th width="150"></th>
    </tr>
    <?php
    while($result_1 = mysql_fetch_assoc($sql))
	{ 
		$ctr++;
		$cust_q1 = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$result_1['id']."'");
		$get_cust1 = mysql_fetch_assoc($cust_q1);

		$monthly_payment_record_sql = "SELECT * FROM monthly_payment_record WHERE customer_id = '".$result_1['id']."' AND status = 'BAD DEBT'";
		$monthly_payment_record_query = mysql_query($monthly_payment_record_sql);
		$count_monthly_bd = mysql_num_rows($monthly_payment_record_query);

		$is_blacklist = $count_monthly_bd > 0 ? TRUE : FALSE;

	// if($result_1['blacklist'] == 'Yes')
	// {
	// 	$style = 'style="color:#F00"';
	// }else
	// {
	// 	$style='';
	// }

	$style = $is_blacklist ? 'style="color:#F00"' : '';
	?>
    <tr <?php echo $style; ?>>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo $result_1['customercode2']; ?></td>
    	<td><?php echo $result_1['name'];?></td>
        <td><?php echo $get_cust1['company']; ?></td>
        <td>
			<!-- <a href="add_monthly.php?id=<?php echo $result_1['id']; ?>"><img src="../img/apply-loan/add-btn.jpg" title="Add Monthly"></a> -->
        	<a href="view_monthly_list.php?id=<?php echo $result_1['id'];?>"><img src="../img/customers/view-icon.png" title="View Monthly List" width="20"></a>
		</td>
        
    </tr>
    <?php } ?>
        <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
       
    </tr>
</table>


</center>
<script>
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	 $("#loan_code").autocomplete("auto_loanCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
	
	  $("#customer_code").autocomplete("auto_custCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
  
});

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
	
$('#outdate').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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
</script>
<script>
function deleteConfirmation(id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this loan?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_loan',
							id: $id,
						},
						url: 'action.php',
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
</script>
