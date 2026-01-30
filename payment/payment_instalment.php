<?php 
include('../include/page_header.php'); 
error_reporting(E_ERROR | E_PARSE);

	$instalment_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
	$instalment_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('n');

if($instalment_month!=''){
    $year = $instalment_year;
	$month = $instalment_month;
	if($month=='1'){$month = '01';}
		else if($month=='2'){$month = '02';}
		else if($month=='3'){$month = '03';}
		else if($month=='4'){$month = '04';}
		else if($month=='5'){$month = '05';}
		else if($month=='6'){$month = '06';}
		else if($month=='7'){$month = '07';}
		else if($month=='8'){$month = '08';}
		else if($month=='9'){$month = '09';}
		else if($month=='10'){$month = '10';}
		else if($month=='11'){$month = '11';}
		else if($month=='12'){$month = '12';}
	$date_from = $year.'-'.$month.'-01';
	$date_to = $year.'-'.$month.'-31';
	$payout_month = $year.'-'.$month;
    }else{
$date_from = date("Y-m-01");
$date_to = date("Y-m-31");
$payout_month = date("Y-m");
}

if(isset($_POST['search']))   // click search
{
	$cond = '';
		
		if($_POST['customer_name'] != '')
		{
			$customer_sql = mysql_query("SELECT * FROM customer_details WHERE name = '".$_POST['customer_name']."'");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= " and customer_details.id = '".$cust['id']."'";	
		}
		
		if($_POST['loan_code'])
		{
			$cond .= " and customer_loanpackage.loan_code = '".$_POST['loan_code']."'";
		}
		
		
		// if($_POST['customer_code'] != '')
		// {
		// 	//echo 'Is it here?';
		// 	//$code_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$_POST['customer_code']."'");
		// 	//$code = mysql_fetch_assoc($code_sql);
		// 	//$cond .= " and lp.customer_id = '".$code['id']."'";	
			
		// 	$code_sql = mysql_query("SELECT * FROM customer_details 
		// 							WHERE customercode2 = '".$_POST['customer_code']."'");
		// 	while ($code = mysql_fetch_assoc($code_sql))
		// 	{//this is when we have many same customer code at a time
		// 		$idList[] = $code['id'];
		// 	}
			
		// 	$cond .= "and (";
		// 	for($i=0; $i<sizeof($idList); $i++)
		// 	{
		// 		if($i==0)
		// 		{//however we fall in this category lol
		// 			$cond .= " customer_id = '".$idList[$i]."'
		// 						";
					
		// 			/*$cond .= " lp.customer_id = '".$idList[$i]."'
		// 						AND (lp.loan_status = 'Paid' OR lp.loan_status = 'Finished')
		// 						AND pd.receipt_no = (SELECT pd.receipt_number 
		// 							WHERE pd.payment_date = (MAX(pd.payment_date)))";*/
					
		// 			/*$cond .= " lp.customer_id = '".$idList[$i]."'
		// 						AND (lp.loan_status = 'Paid' OR lp.loan_status = 'Finished')
		// 						AND pd.receipt_no = 03 046";*/
		// 		}
		// 		else{
		// 			$cond .= " or customer_id = '".$idList[$i]."'
		// 						 ";
		// 		}
		// 	}
			
		// 	$cond .= ")";
		// }

		if($_POST['customer_code'] != '')
		{
			$customer_sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$_POST['customer_code']."'");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= " and customer_details.id = '".$cust['id']."'";	
		}
		
		// $statement = "`customer_loanpackage` WHERE branch_id = '".$_SESSION['login_branchid']."' $cond and (loan_status = 'Paid' OR loan_status = 'Finished' OR loan_status = 'Bad Debt' OR loan_status = 'Deleted')  ORDER BY payout_date ASC";
		$statement = "`customer_loanpackage`
						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
						LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
					WHERE
						customer_loanpackage.branch_id = '".$_SESSION['login_branchid']."' $cond
					AND	temporary_payment_details.loan_month = '".$payout_month."'
					AND 
						temporary_payment_details.loan_code NOT IN(SELECT
					customer_loanpackage.loan_code 
					FROM
					customer_loanpackage
					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
					LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
					WHERE
					customer_loanpackage.loan_package = 'NEW PACKAGE'
					AND(
					loan_payment_details.month_receipt <'".$payout_month."'
					)

					AND loan_payment_details.loan_status='SETTLE' 
					GROUP BY customer_loanpackage.loan_code
					ORDER BY customer_loanpackage.loan_code ASC )
					AND 
						temporary_payment_details.loan_code NOT IN(SELECT
					customer_loanpackage.loan_code 
					FROM
					customer_loanpackage
					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
					LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
					WHERE
					customer_loanpackage.loan_package = 'NEW PACKAGE'
					AND(
					loan_payment_details.month_receipt <'".$payout_month."'
					)

					AND loan_payment_details.loan_status='BAD DEBT' 
					GROUP BY customer_loanpackage.loan_code
					ORDER BY customer_loanpackage.loan_code ASC )
					GROUP BY temporary_payment_details.loan_code
					ORDER BY
						customer_loanpackage.start_month = '".$payout_month."', customer_loanpackage.payout_date ASC";
						
}
else    // not click search and also no history result
{
	// $statement = "`customer_loanpackage` WHERE branch_id = '".$_SESSION['login_branchid']."' $cond and (loan_status = 'Paid' OR loan_status = 'Finished' )  ORDER BY payout_date ASC";
	$statement = "`customer_loanpackage`
					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
					LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
					customer_loanpackage.branch_id = '".$_SESSION['login_branchid']."' $cond
				AND	temporary_payment_details.loan_month = '".$payout_month."'
				AND 
					temporary_payment_details.loan_code NOT IN(SELECT
				customer_loanpackage.loan_code 
				FROM
				customer_loanpackage
				LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
				LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
				customer_loanpackage.loan_package = 'NEW PACKAGE'
				AND(
				loan_payment_details.month_receipt <'".$payout_month."'
				)

				AND loan_payment_details.loan_status='SETTLE' 
				GROUP BY customer_loanpackage.loan_code
				ORDER BY customer_loanpackage.loan_code ASC )
				AND 
					temporary_payment_details.loan_code NOT IN(SELECT
				customer_loanpackage.loan_code 
				FROM
				customer_loanpackage
				LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
				LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
				WHERE
				customer_loanpackage.loan_package = 'NEW PACKAGE'
				AND(
				loan_payment_details.month_receipt <'".$payout_month."'
				)

				AND loan_payment_details.loan_status='BAD DEBT' 
				GROUP BY customer_loanpackage.loan_code
				ORDER BY customer_loanpackage.loan_code ASC )
				GROUP BY temporary_payment_details.loan_code
				ORDER BY
					customer_loanpackage.start_month = '".$payout_month."', customer_loanpackage.payout_date ASC";
}


$sql_q = ("SELECT *,YEAR(payout_date) AS date_year FROM {$statement}");
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
#search1
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search1:hover
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
        <td>Instalment Payment</td>
        <td align="right">
       	<form action="payment_instalment.php" method="post">
        	<table>
				<tr>
                    <td align="right" style="padding-right:10px">Customer ID</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px"/></td>
					<td align="right" style="padding-right:10px">Customer Name</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Agreement No</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
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
			<a href="payment_monthly.php" >Monthly Listing</a>
			<a href="payment_instalment.php" id="active-menu">Instalment Listing</a>
			<a href="lateIntPayment.php">Late Payment Listing</a>
			<a href="collection.php">Collection</a>
			<a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="close_listing.php">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
			<a href="return_book_monthly.php">Monthly</a>
            <a href="return_book_instalment.php">Return Book</a>
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
     	<td colspan="2">       
     		<form method="post">
            <table width="100%" border='0'>
                <tr>              
                    <td style="font-size: 16px;"><br>YEAR
						<select 
							id="instalment_year"
						    name="instalment_year"
						    class="form-control"
						    style="width: 120px;height: 30px; font-size:16px;">
						<?php
							$current_year = idate('Y');
							$future_year = idate('Y', strtotime($current_year. ' + 5 years'));
							$past_year = idate('Y', strtotime($current_year. ' - 3 years'));

							for ($i = $past_year; $i <= $future_year; $i++) {
								$selected = $instalment_year == $i ? 'selected' : '';
						?>
								<option value='<?php echo $i; ?>' <?php echo $selected; ?>><?php echo $i; ?></option>
						<?php
							}
						?>
						</select>
						MONTH
						<select id="instalment_month" name="instalment_month" style="width: 120px;height: 30px; font-size:16px;">
						<?php

						    for ($i = 1; $i <= 12; $i++) { 
						        $selected = $instalment_month == $i ? 'selected' : '';
						?>

						        <option value='<?php echo $i; ?>' <?php echo $selected; ?>><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
						<?php
						    }
						?>

						</select>
                   		<input class="btn btn-blue" type="button" name="search1" value="" id="search1">
                   	</td>
        </td>


<!-- <a href="lampiran_b1.php" target="_blank">Lampiran B1</a> -->
                
 					<td style="font-size: 16px;"></td>
                </tr>
				
            </table>
        </form>
        <br>
     	</td>
     </tr>
 </table>
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
    	<th>Agreement No.</th>
    	<th>Customer ID</th>
    	<th>Customer Name</th>
        <th>Company</th>
        <th>Date Issue</th>
        <th>Loan Applied (RM)</th>
        <th>Monthly (RM)</th>
		<th>Loan Period</th>
        <th width="100"></th>
    </tr>
    <?php
    $ctr=0;
    	$r = mysql_num_rows($sql);
		$i=0;
		if($r>0){	
		// $currentDate = false;
    while($result_1 = mysql_fetch_assoc($sql))
	{  $i++;
                    // if ($result_1['date_year'] != $currentDate){     
                    // 	 $i=1;
        ?>
        <!-- <tr style="background-color: #45b1e8;">
          <td colspan='11' style="color:black;font-size: 22px;"><b><?php echo 'Tahun '.$result_1['date_year']; ?><b></td>
        </tr> -->
        <?php 
		// $currentDate = $result_1['date_year'];

            // }

		$ctr++;
		$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$result_1['customer_id']."'");
		$get_cust = mysql_fetch_assoc($cust_q);

		$cust_q1 = mysql_query("SELECT * FROM customer_employment WHERE customer_id = '".$result_1['customer_id']."'");
		$get_cust1 = mysql_fetch_assoc($cust_q1);

		$cust_q2 = mysql_query("SELECT customer_loanpackage.id FROM customer_loanpackage LEFT JOIN customer_details ON customer_loanpackage.id = customer_details.id WHERE customer_id = '".$result_1['customer_id']."'");
		$get_cust2 = mysql_fetch_assoc($cust_q2);

		$sql_payment = mysql_query("SELECT

										customer_loanid,
										receipt_no 
									FROM
										loan_payment_details 
									WHERE
										payment_date = '0000-00-00' 
										AND loan_status != 'Finished'  
									GROUP BY
										customer_loanid 
									ORDER BY
										customer_loanid DESC,
										id ASC");
		while($get_payment =  mysql_fetch_assoc($sql_payment)){

			 if($result_1['loan_code'] == $get_payment['receipt_no'])
	 {
	 	$style = 'style="color:blue;"';
	 }else
	 {
	 	$style='';
	 }
	

	// if($get_cust['blacklist'] == 'Yes')
	// {
	// 	$style = 'style="color:#F00"';
	// }else
	// {
	// 	$style='';
	// }
	

	$loan_monthly = number_format(($result_1['loan_total']/$result_1['loan_period']),2);

	?>
    <tr <?php echo $style;} ?>>
    	<td><?php echo $i."."; ?></td>
    	<td><?php echo $result_1['loan_code']; ?></td>
    	<td><?php echo $get_cust['customercode2']; ?></td>
    	<td><?php echo $get_cust['name'];?></td>
        <td><?php echo $get_cust1['company']; ?></td>
        <td><?php echo date('d/m/Y',strtotime($result_1['payout_date'])); ?></td>
        <td><?php echo number_format($result_1['loan_amount'],2); ?></td>
        <td><?php echo $loan_monthly; ?></td>
        <td><?php echo $result_1['loan_period']; ?></td>
        <td><a href="payloan_a.php?id=<?php echo $result_1['customer_loanid']; ?>" title="Make Payment"><img src="../img/report-icon.png" /></a>
		<!-- <a href="javascript:deleteConfirmation('<?php echo $result_1['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a> --> 
		</td>
        
    </tr>
    <?php }} ?>
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
<script type="text/javascript">
	// date filter 
	$(document).ready(function () {
		$('#search1').on('click', function() {
			let year = $('#instalment_year').val();
			let month = $('#instalment_month').val();
			window.location.href = 'payment_instalment.php?year=' + year + '&month=' + month;
		});
	});
</script>
