<?php include('../include/page_header.php'); 
     
?>
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
#update
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#update:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
}
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Reports</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" >KPKT</a>
			<a href="monthly.php" id="active-menu">Monthly</a>
			<a href="instalment.php">Instalment</a>
			<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['taplogin_id'] != '201' && $_SESSION['login_branch'] == 'majusama')) { ?>
			<a href="statement_report.php">Statement</a>
			<?php } ?>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
    </tr>
</table>

<div id="message" style="width:1280px; text-align:left">
	<?php
    if($_SESSION['msg'] != '')
    {
        echo $_SESSION['msg'];
        $_SESSION['msg'] = '';
    }
    ?>						
</div>

<br />

<?php
	$selected_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
	$selected_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('m');
?>

<table width="1280" id="list_table">
		<tr>
    	<th>Report List</th>
        <th></th>
    </tr>
	<tr>
     	<td colspan="2">       
     		<form method="post">
            <table width="100%" border='0'>
                <tr>              
					<td style="font-size: 16px;"><br>YEAR
						<select 
							id="year"
						    name="year"
						    class="form-control"
						    style="width: 120px;height: 30px; font-size:16px;">
						<?php
							$current_year = idate('Y');
							$future_year = idate('Y', strtotime($current_year. ' + 5 years'));
							$past_year = idate('Y', strtotime($current_year. ' - 3 years'));

							for ($i = $past_year; $i <= $future_year; $i++) {
								$selected = $selected_year == $i ? 'selected' : '';
						?>
								<option value='<?php echo $i; ?>' <?php echo $selected; ?>><?php echo $i; ?></option>
								<?php } ?>
						</select>
						MONTH
						<select id="month" name="month" style="width: 120px;height: 30px; font-size:16px;">
						<?php

						    for ($i = 1; $i <= 12; $i++) { 
						        $selected = $selected_month == $i ? 'selected' : '';
								$month = str_pad($i, 2, '0', STR_PAD_LEFT);

								$date = DateTime::createFromFormat('!m', $month);

								// Get the full month name
								$full_month_name = $date->format('F');
						?>

						        <option value='<?php echo $month; ?>' <?php echo $selected; ?>><?php echo $full_month_name; ?></option>
						<?php
						    }
						?>

						</select>
                   		<input class="btn btn-blue" type="button" name="search" value="SEARCH" id="search">
                   	</td>


<!-- <a href="lampiran_b1.php" target="_blank">Lampiran B1</a> -->
                
 <td style="font-size: 16px;"></td>
                </tr>               
             	<?php
					$mth = $selected_month;
					if($mth=='01'){$mth = 'Jan';}
					else if($mth=='02'){$mth = 'Feb';}
					else if($mth=='03'){$mth = 'March';}
					else if($mth=='04'){$mth = 'April';}
					else if($mth=='05'){$mth = 'May';}
					else if($mth=='06'){$mth = 'June';}
					else if($mth=='07'){$mth = 'July';}
					else if($mth=='08'){$mth = 'Aug';}
					else if($mth=='09'){$mth = 'Sept';}
					else if($mth=='10'){$mth = 'Oct';}
					else if($mth=='11'){$mth = 'Nov';}
					else if($mth=='12'){$mth = 'Dec';}
				?> 
				<tr>
					<td style="font-size:16px;">
						<a href="monthly_pdf.php?year=<?php echo $selected_year; ?>&month=<?php echo $selected_month; ?>" target="_blank">
							MONTHLY (<?php echo $mth; ?> - <?php echo $selected_year; ?>)
						</a>
					</td>
				</tr>
            </table>
        </form>
        <br>
     	</td>
     </tr>
 </table>
 <table width="1280" id="list_table">
    		<tr style="background-color: #45b1e8;">
          <td colspan='9' style="color:black;font-size: 22px;"><b><?php echo 'Monthly ' . $mth . ' - ' . $selected_year; ?><b></td>
        </tr>
 </table>
<table width="1280" id="list_table">
	<tr>
    	<th style="text-align: center;">No.</th>
		<th style="text-align: center;">Agreement No</th>
    	<th >Customer ID</th>
    	<th>Customer Name</th>
        <th>Company</th>
        <th>First Loan Date</th>
        <th>Total Loan</th>
        <th>Return</th>
        <th>Bad Debts /<br>Outstanding</th>
        <th>Cash Balance</th>
        <th colspan="2" 
        width="50"></th>
    </tr>

    <?php
	$payout_month = $selected_year . '-' . $selected_month;

	$sql_1 = mysql_query("SELECT
							SUM(mpr.payout_amount) AS PA,
							mpr.loan_code,
							cd.customercode2,
							cd.name,
							ce.company,
							SUM(mpr.balance) AS balance,
							mpr.monthly_date,
							mpr.customer_id,
							mpr.status
						FROM monthly_payment_record mpr
						LEFT JOIN customer_details cd ON mpr.customer_id = cd.id
						LEFT JOIN (
									SELECT 
										customer_id,
										MAX(company) AS company
									FROM customer_employment
									GROUP BY customer_id
								) ce ON cd.id = ce.customer_id
						WHERE mpr.month = '".$payout_month."' AND mpr.status != 'DELETED'
						GROUP BY
							mpr.loan_code
						ORDER BY
							mpr.monthly_date ASC,
							mpr.id ASC;");

						$ctr=0;
						while($result_1 = mysql_fetch_assoc($sql_1))
						{ 
							$ctr++;


							if($result_1['status']=='FINISHED')
							{
								$return=$result_1['PA']-$result_1['balance'];
								$baddebt=$result_1['balance'];
								$cash_balance+=(($result_1['PA']-$result_1['balance'])*0.1);

							}
							else if($result_1['status']=='PAID')
							{
								$return=$result_1['PA']-$result_1['balance'];
								$baddebt=$result_1['balance'];
								$cash_balance+=(($result_1['PA']-$result_1['balance'])*0.1);
							}else if($result_1['status']=='BAD DEBT')
							{
								// $return='0.00';
								// $baddebt=$result_1['PA'];
								// $cash_balance-=($result_1['PA']-($result_1['PA']*0.1));
								$return=$result_1['PA']-$result_1['balance'];
								$baddebt=$result_1['balance'];
								$cash_balance-=($result_1['PA']-($result_1['balance']*0.1));

							}

								if($result_1['status']=='BAD DEBT')
							{
								$style = "style='color:#FF0000'";
							}else
							{
								$style = " ";	
							}

							$totalpayout+=$result_1['PA'];
							$totalreturn+=$return;
							$totalbaddebt+=$baddebt;
							$payout +=($result_1['PA']-($result_1['PA']*0.1));

							$select = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$result_1['loan_code']."'");
							$get_select = mysql_fetch_assoc($select);
							$num_select = mysql_num_rows($select);

							// if($num_select==0)
							// {
							// 	$style1 = "style='color:#FF0000'";

							// }else 
							// Stamping (black colour code) 报账
							if (substr($result_1['loan_code'],0,2) == 'SD') {
								$style1 = " ";
							} else if (substr($result_1['loan_code'],0,2) !='AS' 
							&& substr($result_1['loan_code'],0,2) !='SB' 
							&& substr($result_1['loan_code'],0,2) !='BP' 
							&& substr($result_1['loan_code'],0,2) !='KT' 
							&& substr($result_1['loan_code'],0,2) !='MS' 
							&& substr($result_1['loan_code'],0,2) !='MJ' 
							&& substr($result_1['loan_code'],0,2) !='PP' 
							&& substr($result_1['loan_code'],0,2) !='NG' 
							&& substr($result_1['loan_code'],0,2) !='TS' 
							&& substr($result_1['loan_code'],0,2) != 'LT'
							&& substr($result_1['loan_code'],0,2) !='BT' 
							&& substr($result_1['loan_code'],0,2) !='DK') {
								$style1 = "style='color:#FF0000'";
							} else {
								$style1 = " ";	
							}

	?>
    <tr <?php echo $style; ?> >
    	<td><?php echo $ctr."."; ?></td>
		<td align="center" <?php echo $style1; ?>>
			<?php 
				// Branch Code (Instalment)
				if (substr($result_1['loan_code'],0,2) =='SB' 
				|| substr($result_1['loan_code'],0,2) == 'MS' 
				|| substr($result_1['loan_code'],0,2) == 'MJ' 
				|| substr($result_1['loan_code'],0,2) == 'PP' 
				|| substr($result_1['loan_code'],0,2) == 'CL' 
				|| substr($result_1['loan_code'],0,2) == 'BT') {
					echo preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_1['loan_code']);
				} else {
					 echo $result_1['loan_code'];
				} 
			?>
		</td>
    	<td <?php echo $style1; ?>><?php echo $result_1['customercode2']; ?></td>
    	<td <?php echo $style1; ?>><?php echo $result_1['name'];?></td>
        <td ><?php echo $result_1['company']; ?></td>
         <td style="color: blue;"><?php echo $result_1['monthly_date']; ?></td>
        <td><?php echo number_format($result_1['PA'], '2'); ?></td>
        <td><?php echo $return; ?></td>
        <td><?php echo $baddebt; ?></td>
        <td><?php echo $cash_balance; ?></td>
        <td><a href="view_monthly_list.php?loan_code=<?php echo $result_1['loan_code']; ?>&id=<?php echo $result_1['customer_id'];?>"><img src="../img/customers/view-icon.png" title="View Monthly List" width="20"></a></a></td>
        
    </tr>
    <?php } ?>
    <tr>
    	<td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;"><b><?php echo number_format($totalpayout, '2'); ?></b></td>
		<td style="border-top:1px solid;"><b><?php echo number_format($totalreturn, '2'); ?></b></td>
        <td style="border-top:1px solid;"><b><?php echo number_format($totalbaddebt, '2'); ?></b></td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid;">&nbsp;</td>


    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
    <table width="7%" align="left"><tr><td>&nbsp;</td></tr></table>
    <table width="50%" align="left" id="list_table" cellspacing="1" cellpadding="1" style="font-family:Time New Roman;padding-right:150px;padding-left:50px;padding-bottom:-230px;font-size:11px;color:black ;">
			<tr><td colspan="6"></td><td>BAD DEBTS FOR THIS MONTH</td></tr>

<?php $sql_2 = mysql_query("SELECT
							sum(payout_amount) AS PA,
							loan_code,
							customercode2,
							name,
							company,
							payout_amount,
							sum(balance) AS balance,
							monthly_payment_record.customer_id,
							monthly_payment_record.status
						FROM
							monthly_payment_record
							LEFT JOIN customer_details ON monthly_payment_record.customer_id = customer_details.id 
							LEFT JOIN customer_employment ON customer_details.id = customer_employment.customer_id
							WHERE monthly_payment_record.month ='".$payout_month."' AND monthly_payment_record.status='BAD DEBT'
						GROUP BY
							loan_code ASC");
						$ctr1=0;
						while($result_2 = mysql_fetch_assoc($sql_2))
						{ 

							$ctr1++;
							// $total_baddebt+=$result_2['PA'];
							$total_baddebt+=$result_2['balance'];

							if(substr($result_2['loan_code'],0,2) !='AS')
							{
								$style2 = "color:#FF0000";
							}
							else
							{
								$style2 = " ";	
							}


					?>
				<tr><td colspan="5"></td><td width="3%" style = "text-align:center;"><?php echo $ctr1;?></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;border-left: 1px solid black;background-color:orange;<?php echo $style2; ?>"><?php echo $result_2['loan_code']?></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;background-color:orange;<?php echo $style2; ?>"><?php echo $result_2['customercode2']?></td>
				<td style="text-align:left;border-top: 1px solid black; border-right: 1px solid black;background-color:orange;<?php echo $style2; ?>"><?php echo $result_2['name']?></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;background-color:orange;"><?php echo $result_2['company']?></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;color:red;"><?php echo number_format($result_2['balance'])?></td>
				</tr> <?php }?>

				<tr><td colspan="5"></td><td></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;"></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-top: 1px solid black; border-right: 1px solid black;border-bottom: 1px solid black;color:red;"><?php echo number_format($total_baddebt);?></td>
				</tr>
				<tr><td colspan="11">&nbsp;</td></tr>
		</table>



    <table width="580"  id="list_table" cellspacing="0" cellpadding="1" align="right" style="padding-right:150px;padding-left:50px;padding-bottom:-230px;font-size:12px; color:black ;">	

    <tr><td>&nbsp;</td>
    	<td>OPENING BALANCE:</td>
        <td>0</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
        <tr>
        <td>&nbsp;</td>
    	<td>MONTHLY RETURN:</td>
        <td><?php echo number_format($totalreturn, '2'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
      <tr style="color:red;">
      	<td>&nbsp;</td>
    	<td>MONTHLY PAYOUT:</td>
        <td style="border-bottom:1px solid black;"><?php echo number_format($payout, '2'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
          <tr>
        <td>&nbsp;</td>
    	<td>BALANCE CLOSED A/C:</td>
        <td style="border-bottom:1px solid black;"><?php echo number_format($totalreturn - $payout, '2'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
       <tr style="color:brown;">
       	<td>&nbsp;</td>
    	<td>BAD DEBT THIS MONTH:</td>
        <td><?php echo number_format($total_baddebt, '2'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
        <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>

</center>
<script>
	$('#tamat_tempoh').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#tarikh_mula').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#tarikh_tamat').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
	$('#cl_tarikh1').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#cl_tarikh2').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#cl_tarikh3').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this user: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_staff',
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

function showEdit(no)
{
	if(document.getElementById('edit_' + no).style.visibility == 'hidden')
	{
		document.getElementById('edit_' + no).style.visibility = 'visible';	
	}else
	if(document.getElementById('edit_' + no).style.visibility == 'visible')
	{
		document.getElementById('edit_' + no).style.visibility = 'hidden';
	}
}

function updateAmount(no, id)
{
	$amount = $('#amount_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
}

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

</script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#search').on('click', function() {
			let year = $('#year').val();
			let month = $('#month').val();
			window.location.href = 'monthly.php?year=' + year + '&month=' + month;
		});
	});
</script>
</body>
</html>