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
			<a href="monthly.php" >Monthly</a>
			<a href="instalment.php" id="active-menu">Instalment</a>
			<?php
            if($_SESSION['taplogin_id'] != '201' && $_SESSION['taplogin_id'] != '204')
            {
        ?>
			<a href="statement_report.php">Statement</a>
							 <?php
            }
        ?>
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
	$instalment_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
	$instalment_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('n');
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

						        <option value='<?php echo $i; ?>' <?php echo $selected; ?>><?php echo date('F', mktime(0,0,0,$i)); ?></option>
						<?php
						    }
						?>

						</select>
                   		<input class="btn btn-blue" type="button" name="search" value="SEARCH" id="search">
                   	</td>
        </td>


<!-- <a href="lampiran_b1.php" target="_blank">Lampiran B1</a> -->
                
 					<td style="font-size: 16px;"></td>
                </tr>               
             	<?php 
    		$mth1 = $instalment_month;

		if($mth1=='1'){$mth1 = 'Jan';}
		else if($mth1=='2'){$mth1 = 'Feb';}
		else if($mth1=='3'){$mth1 = 'March';}
		else if($mth1=='4'){$mth1 = 'April';}
		else if($mth1=='5'){$mth1 = 'May';}
		else if($mth1=='6'){$mth1 = 'June';}
		else if($mth1=='7'){$mth1 = 'July';}
		else if($mth1=='8'){$mth1 = 'Aug';}
		else if($mth1=='9'){$mth1 = 'Sept';}
		else if($mth1=='10'){$mth1 = 'Oct';}
		else if($mth1=='11'){$mth1 = 'Nov';}
		else if($mth1=='12'){$mth1 = 'Dec';}

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
		
	   echo ' <tr><td style="font-size:16px;"><a href="instalment_pdf.php?year='.$instalment_year.'&month='.$month.'" target="_blank">Instalment ('.$mth1.' - '.$instalment_year.')</a></td></tr>';

	  if($instalment_month==''){
	  $year_now = date("Y");
	  $mth_now = date("m");
	  $mth2 = $mth_now;
	  		if($mth2=='01'){$mth2 = 'Jan';}
		else if($mth2=='02'){$mth2 = 'Feb';}
		else if($mth2=='03'){$mth2 = 'March';}
		else if($mth2=='04'){$mth2 = 'April';}
		else if($mth2=='05'){$mth2 = 'May';}
		else if($mth2=='06'){$mth2 = 'June';}
		else if($mth2=='07'){$mth2 = 'July';}
		else if($mth2=='08'){$mth2 = 'Aug';}
		else if($mth2=='09'){$mth2 = 'Sept';}
		else if($mth2=='10'){$mth2 = 'Oct';}
		else if($mth2=='11'){$mth2 = 'Nov';}
		else if($mth2=='12'){$mth2 = 'Dec';}
	  echo ' <tr><td style="font-size:16px;"><a href="instalment_pdf.php?year='.$year_now.'&month='.$mth_now.'" target="_blank">INSTALMENT ('.$mth2.' - '.$year_now.')</a></td></tr>';}
    ?> 
            </table>
        </form>
        <br>
     	</td>
     </tr>
 </table>
 <table width="1280" id="list_table">
    		<tr style="background-color: #45b1e8;">
          <td colspan='9' style="color:black;font-size: 22px;"><b><?php if($instalment_month!=''){echo 'Instalment '.$mth1.' - '.$instalment_year;}else{echo 'Instalment '.date("M - Y"); }?><b></td>
        </tr>
 </table>
<table width="1280" id="list_table">
	<tr>
    	<th width="10">No.</th>
		<th width="80">Agreement No</th>
    	<th width="70">Customer ID</th>
    	<th width="360">Name</th>
    	<th width="55">Applied</th>
    	<th width="55">Monthly</th>
    	<th width="55">X</th>
    	<th width="55">Loan (10%)</th>
    	<th width="55">Loan (8%)</th>
    	<th width="55">Loan (6.2%)</th>
    	<th width="55">Loan (5.5%)</th>
    	<th width="55">Loan (5%)</th>
    	<th width="55">Out</th>
        <th width="55">Collected</th>
        <th width="55">Return (10%)</th>
    	<th width="55">Return (8%)</th>
    	<th width="55">Return (6.2%)</th>
    	<th width="55">Return (5.5%)</th>
    	<th width="55">Return (5%)</th>
        <th width="55">Settle</th>
        <th width="55">Bad Debts</th>
    </tr>

    <?php 
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

     ?>
 <?php 
    $sql_4 = mysql_query("SELECT
								customer_loanpackage.loan_code,
								customer_loanpackage.start_month,
								customer_loanpackage.payout_date,
								customer_loanpackage.loan_amount,
								customer_loanpackage.loan_period,
								customer_loanpackage.loan_total,
								customer_loanpackage.loan_status,
								customer_details.customercode2,
								customer_details.name,
								customer_details.nric,
								customer_employment.company,
								temporary_payment_details.monthly,
								temporary_payment_details.loan_percent,
								temporary_payment_details.loan_status,
								temporary_payment_details.customer_loanid 
							FROM
								customer_loanpackage
								LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
								LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
								LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
							WHERE
								temporary_payment_details.loan_month = '".$payout_month."'
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
								customer_loanpackage.start_month = '".$payout_month."', customer_loanpackage.payout_date ASC");
					
					
						while($result_4 = mysql_fetch_assoc($sql_4))
						{ 

							$ctr++;
						if($result_4['start_month'] == $payout_month)
						{
							$out = $result_4['loan_amount']-($result_4['loan_amount']*0.1);
						}
						else
						{
							$out = '';
						}

						$collected='';
						$collected_remarks='';
						$settle='';
						$baddebt= '';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						$sql = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt ='".$payout_month."'");
							while($result = mysql_fetch_assoc($sql)){

							if($result_4['loan_code']==$result['receipt_no']){

							if($result_4['loan_period']>=1 && $result_4['loan_period']<=12)
						{
							if($result['loan_status']=='SETTLE')
							{
								$collected='';
								$collected_remarks = 'SETTLE';
								$settle=$result_4['loan_percent'];
								$baddebt= '';

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected='';
								$collected_remarks = 'BD';
								$settle='';
								$baddebt= '1';
							}else
							{
								$collected=$result_4['monthly'];
								$collected_remarks = '';
								$settle='';
								$baddebt= '';
							}
							
						

						}else if($result_4['loan_period']>=13 && $result_4['loan_period']<=24)
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected='';
								$collected_remarks = 'SETTLE';
								$settle=$result_4['loan_percent'];
								$baddebt= '';

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected='';
								$collected_remarks = 'BD';
								$settle='';
								$baddebt= '1';
							}else
							{
								$collected=$result_4['monthly'];
								$collected_remarks = '';
								$settle='';
								$baddebt= '';
							}
						

						}else if($result_4['loan_period']>=25 && $result_4['loan_period']<=36)
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected='';
								$collected_remarks = 'SETTLE';
								$settle=$result_4['loan_percent'];
								$baddebt= '';

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected='';
								$collected_remarks = 'BD';
								$settle='';
								$baddebt= '1';
							}else
							{
								$collected=$result_4['monthly'];
								$collected_remarks = '';
								$settle='';
								$baddebt= '';
							}
						

						}else if($result_4['loan_period']>=37 && $result_4['loan_period']<=48)
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected='';
								$collected_remarks = 'SETTLE';
								$settle=$result_4['loan_percent'];
								$baddebt= '';

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected='';
								$collected_remarks = 'BD';
								$settle='';
								$baddebt= '1';
							}else
							{
								$collected=$result_4['monthly'];
								$collected_remarks = '';
								$settle='';
								$baddebt= '';
							}

						
						}else
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected='';
								$collected_remarks = 'SETTLE';
								$settle=$result_4['loan_percent'];
								$baddebt= '';

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected='';
								$collected_remarks = 'BD';
								$settle='';
								$baddebt= '1';
							}else
							{
								$collected=$result_4['monthly'];
								$collected_remarks = '';
								$settle='';
								$baddebt= '';
							}

						}
							}
						}

						$last_loanremarks = '';

						// $sqlT = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no ='".$result_4['loan_code']."'  ORDER BY id DESC LIMIT 1");
						// while($resultT = mysql_fetch_assoc($sqlT)){
						// 	if($collected!='' && $resultT['month_receipt'] == $payout_month){
						// 		if($result_4['monthly']==$resultT['balance']){
						// 			$last_loanremarks = 'LAST';
						// 		}
						// 		else
						// 		{
						// 			$last_loanremarks = '';
						// 		}
						// 	}

						// 	if($collected=='' ){
						// 		if($result_4['monthly']==$resultT['balance']){
						// 			$last_loanremarks = 'LAST';
						// 		}
						// 		else
						// 		{
						// 			$last_loanremarks = '';
						// 		}
						// 	}

						// }

						// Assume this is the last payment year and month
						$current_instalment_year_month = $instalment_year . '-' . $instalment_month;

						// subtract the loan period from current selected year and month, and compare the start year and month to identify the last payment year and month
						$loan_period = $result_4['loan_period'] - 1;	// subtract 1 from loan period because current_instalment_year_month is counted as one month already
						
						$start_year_month = '' . date('Y-m', strtotime($current_instalment_year_month . ' - ' . $loan_period . ' months'));
						if ($start_year_month == $result_4['start_month']) {
							$last_loanremarks = 'LAST';
						}


						if($result_4['start_month'] == $payout_month)
						{
						$loan_percent1=$result_4['loan_percent'];
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						if($result_4['loan_period']>=1 && $result_4['loan_period']<=12)
						{
							$style = "color:black;";
						}
						else if($result_4['loan_period']>=13 && $result_4['loan_period']<=24)
						{
							$style = "color:green;";

						}else if($result_4['loan_period']>=25 && $result_4['loan_period']<=36)
						{
							$style = "color: #0066cc;";

						}else if($result_4['loan_period']>=37 && $result_4['loan_period']<=48)
						{
							$style = "color:brown;";
						}
						else
						{
							$style = "color:#FF00FF;";
						}
						$style1 = "color:black;";

						$return_percent1=$result_4['loan_percent'];
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
						}
						else
						{
						if($result_4['loan_period']>=1 && $result_4['loan_period']<=12)
						{
						$loan_percent1=$result_4['loan_percent'];
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$style = "color:black;";

						$return_percent1=$result_4['loan_percent'];
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';


						}else if($result_4['loan_period']>=13 && $result_4['loan_period']<=24)
						{
						$loan_percent1='';
						$loan_percent2=$result_4['loan_percent'];
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$style = "color:green;";

						$return_percent1='';
						$return_percent2=$result_4['loan_percent'];
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
					

						}else if($result_4['loan_period']>=25 && $result_4['loan_period']<=36)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3=$result_4['loan_percent'];
						$loan_percent4='';
						$loan_percent5='';
						$style = "color: #0066cc;";

						$return_percent1='';
						$return_percent2='';
						$return_percent3=$result_4['loan_percent'];
						$return_percent4='';
						$return_percent5='';


						}else if($result_4['loan_period']>=37 && $result_4['loan_period']<=48)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4=$result_4['loan_percent'];
						$loan_percent5='';
						$style = "color:brown;";

						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4=$result_4['loan_percent'];
						$return_percent5='';
						
						}else
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5=$result_4['loan_percent'];
						$style = "color:#FF00FF;";

						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5=$result_4['loan_percent'];
						} 
						}

						$total_applied+=$result_4['loan_amount'];
						$total_month+=$result_4['monthly'];
						
							

						$total_loan_percent1+=$loan_percent1;
						$total_loan_percent2+=$loan_percent2;
						$total_loan_percent3+=$loan_percent3;
						$total_loan_percent4+=$loan_percent4;
						$total_loan_percent5+=$loan_percent5;
						$totalout+=$out;
						$totalcollected+=$collected;
						$total_return_percent1+=$return_percent1;
						$total_return_percent2+=$return_percent2;
						$total_return_percent3+=$return_percent3;
						$total_return_percent4+=$return_percent4;
						$total_return_percent5+=$return_percent5;
						$totalsettle+=$settle;
						$totalbaddebt+=$baddebt;

	?>
    <tr style="<?php echo $style.";"; ?>">
    	<td style="border-left: 1px solid black;border-bottom: 1px dashed black;"><?php echo $ctr."."; ?></td>
		<td align="center" style="border-bottom: 1px dashed black;"><a style="<?php echo $style.";"; ?>" href="payloan_a.php?id=<?php echo $result_4['customer_loanid']; ?>&year=<?php echo $instalment_year; ?>&month=<?php echo $instalment_month; ?>" title="Make Payment"><?php if(substr($result_4['loan_code'],0,2) == 'MS'){echo preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_4['loan_code']);}else { echo $result_4['loan_code'];} ?></a></td>
    	<td style="border-bottom: 1px dashed black;"><?php echo $result_4['customercode2']; ?></td>
    	<td style="border-bottom: 1px dashed black;"><?php echo $result_4['name'];?></td>
    	<td style="text-align:center;border-bottom: 1px dashed black;"><?php echo number_format($result_4['loan_amount']);?></td>
		<td style="text-align:center;border-bottom: 1px dashed black;"><?php echo number_format($result_4['monthly']);?></td>
		<td style="text-align:center;border-right: 1px solid black;border-bottom: 1px dashed black;"><?php echo $result_4['loan_period'];?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;<?php echo $style1.";"; ?>"><?php echo number_format($loan_percent1); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($loan_percent2); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($loan_percent3); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($loan_percent4); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($loan_percent5); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;<?php if($out!=''){ echo 'color:red;'; }else{};?>"><?php echo number_format($out); ?></td>
        <td style="border-right: 1px solid black;border-bottom: 1px dashed black;text-align: center;<?php if($collected_remarks=='SETTLE'){echo 'background-color: yellow;';}elseif($collected_remarks=='BD'){ echo 'background-color: darkorange;'; }elseif($last_loanremarks=='LAST'){ echo 'background-color: yellow;'; }else{} ?>"><?php echo number_format($collected).''.$collected_remarks; ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;<?php echo $style1.";"; ?>"><?php echo number_format($return_percent1); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($return_percent2); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($return_percent3); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($return_percent4); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($return_percent5); ?></td>
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;"><?php echo number_format($settle); ?></td>
        <td style="border-right: 1px solid black;border-bottom: 1px dashed black;text-align: center;"><?php echo $baddebt; ?></td>
    </tr>
    <?php } ?>

    <?php  
    $totalloan=$total_loan_percent1+$total_loan_percent2+$total_loan_percent3+$total_loan_percent4+$total_loan_percent5;
    $after_total_loan_percent1 = $total_loan_percent1*0.1;
    $after_total_loan_percent2 = $total_loan_percent2*0.08;
    $after_total_loan_percent3 = $total_loan_percent3*0.062;
    $after_total_loan_percent4 = $total_loan_percent4*0.055;
    $after_total_loan_percent5 = $total_loan_percent5*0.05;
    $totalint = $after_total_loan_percent1 +$after_total_loan_percent2+$after_total_loan_percent3+$after_total_loan_percent4+$after_total_loan_percent5;

    ?>
        <tr>
    	<td style="border-top:1px solid black;border-left:1px solid;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;border-right: 1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px solid black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
        <td style="border-top:1px solid black;border-right: 1px solid black;text-align: center;"></td>
    </tr>
    <tr>
    	<td style="border-top:1px solid black;border-left:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;border-right: 1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: black;"><?php echo number_format($total_loan_percent1); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: green;"><?php echo number_format($total_loan_percent2); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: #0066cc;"><?php echo number_format($total_loan_percent3); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: brown;"><?php echo number_format($total_loan_percent4); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: #FF00FF;"><?php echo number_format($total_loan_percent5); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: red;"><?php echo number_format($totalout); ?></td>
        <td style="border-top:1px solid black;border-right: 1px solid black;text-align: center;color: black;"><?php echo number_format($totalcollected); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: black;"><?php echo number_format($total_return_percent1); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: green;"><?php echo number_format($total_return_percent2); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: #0066cc;"><?php echo number_format($total_return_percent3); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: brown;"><?php echo number_format($total_return_percent4); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color:#ff00ff"><?php echo number_format($total_return_percent5); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: black"><?php echo number_format($totalsettle); ?></td>
        <td style="border-top:1px solid black;border-right: 1px solid black;text-align: center;color: black"><?php echo $totalbaddebt; ?></td>
    </tr>
        <tr>
    	<td style="border-top:1px solid black;border-left:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
         <td style="border-top:1px solid black;"><?php echo number_format($total_applied); ?></td>
        <td style="border-top:1px solid black;"><?php echo number_format($total_month); ?></td>
        <td style="border-top:1px solid black;border-right: 1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color:black;"><?php echo number_format($after_total_loan_percent1); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color:green;"><?php echo number_format($after_total_loan_percent2); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color:#0066cc;"><?php echo number_format($after_total_loan_percent3); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: brown;"><?php echo number_format($after_total_loan_percent4); ?></td>
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color:#ff00ff"><?php echo number_format($after_total_loan_percent5); ?></td>
        <td colspan="2" style="border-top:1px solid black;border-right: 1px solid black;color: black;"><?php echo "Total Int= ".number_format($totalint); ?></td>
        <td colspan="3" style="border-top:1px solid black; color: black;"><?php echo "Total Loan= "; ?></td>
        <td colspan="4" style="border-top:1px solid black;border-right: 1px solid black; color: black;"><?php echo number_format($totalloan); ?></td>
    </tr>
    <tr>
    	<td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
        <td style="border-top:1px solid black;"></td>
    </tr>
</table><?php
        $sql_bd_collected = mysql_query("SELECT
										late_interest_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										late_interest_record.bd_date,
										late_interest_record.amount as amount,
										SUM(late_interest_payment_details.amount) as collected,
										late_interest_payment_details.payment_date,
										late_interest_record.balance as balance
									FROM
										late_interest_record
										LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
										LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
									WHERE late_interest_payment_details.month_receipt ='".$payout_month."'
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");
														
						while($result_bd_collected = mysql_fetch_assoc($sql_bd_collected))
						{ 
							$totalamount_collected_desc += $result_bd_collected['collected'];
						}

						$sql_monthly = mysql_query("SELECT
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
							WHERE monthly_payment_record.month ='".$payout_month."' and monthly_payment_record.status!='DELETED'
						GROUP BY
							loan_code ASC
							ORDER BY
							monthly_payment_record.id ASC ");
						while($result_monthly = mysql_fetch_assoc($sql_monthly))
						{ 
							if($result_monthly['status']=='FINISHED')
							{
								$cash_balance+=(($result_monthly['PA']-$result_monthly['balance'])*0.1);
							}
							else if($result_monthly['status']=='PAID')
							{
								$cash_balance+=0;
							}else if($result_monthly['status']=='BAD DEBT')
							{
								$cash_balance-=($result_monthly['PA']-($result_monthly['PA']*0.1));
							}

						}

						$sql_expenses = mysql_query("SELECT * FROM expenses WHERE date BETWEEN '".$date_from."' AND '".$date_to."'");
						while($result_expenses = mysql_fetch_assoc($sql_expenses))
						{ 
							$totalexpenses += $result_expenses['amount'];
						}

						$sql_thismonth_bd = mysql_query("SELECT
										baddebt_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										customer_details.nric,
										customer_loanpackage.payout_date,
										customer_employment.company,
										baddebt_record.bd_date,
										baddebt_record.amount as amount
									FROM
										baddebt_record
										LEFT JOIN customer_details ON baddebt_record.customer_id = customer_details.id
										LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = baddebt_record.loan_code
										LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
									WHERE baddebt_record.month_receipt = '".$payout_month."' and
									baddebt_record.bd_from ='Instalment'
									GROUP BY baddebt_record.loan_code
									ORDER BY baddebt_record.bd_date ASC");
														
						while($result_thismonth_bd = mysql_fetch_assoc($sql_thismonth_bd))
						{ 
							$totalamount_instalment_desc += $result_thismonth_bd['amount'];
						}
//OPENING BALANCE

						$sql1_ob = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt > '2021-12' AND month_receipt <'".$payout_month."' AND payment_date >'2022-01-01' AND payment_date <'".$date_from."'");
						while($result1_ob = mysql_fetch_assoc($sql1_ob))
						{
							$totalcollected_ob +=$result1_ob['payment'];
						}

						$sql2_ob = mysql_query("SELECT * FROM customer_loanpackage WHERE payout_date >'2022-01-01' AND payout_date<'".$date_from."'");
						while($result2_ob = mysql_fetch_assoc($sql2_ob))
						{
							$totalout_ob +=($result2_ob['loan_amount']-($result2_ob['loan_amount']*0.1));
						}

						$sql3_ob= mysql_query("SELECT
										late_interest_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										late_interest_record.bd_date,
										SUM(late_interest_record.amount) as amount,
										late_interest_payment_details.amount as collected,
										late_interest_payment_details.payment_date,
										SUM(late_interest_record.balance) as balance
									FROM
										late_interest_record
										LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
										LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
									WHERE late_interest_record.bd_date > '2022-01-01'
									AND late_interest_record.bd_date <'".$date_from."'
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");
														
						while($result3_ob = mysql_fetch_assoc($sql3_ob))
						{ 
							$totalamount_collected_desc_ob += $result3_ob['collected'];
						}

						$sql4_ob = mysql_query("SELECT
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
							WHERE monthly_payment_record.month >'2022-01' AND monthly_payment_record.month <'".$payout_month."' and monthly_payment_record.status!='DELETED'
						GROUP BY
							loan_code ASC
							ORDER BY
							monthly_payment_record.id ASC ");
						while($result4_ob = mysql_fetch_assoc($sql4_ob))
						{ 
							if($result4_ob['status']=='FINISHED')
							{
								$cash_balance_ob+=(($result4_ob['PA']-$result4_ob['balance'])*0.1);
							}
							else if($result4_ob['status']=='PAID')
							{
								$cash_balance_ob+=0;
							}else if($result4_ob['status']=='BAD DEBT')
							{
								$cash_balance_ob-=($result4_ob['PA']-($result4_ob['PA']*0.1));
							}

						}

						$sql5_ob = mysql_query("SELECT * FROM expenses WHERE date >'2022-01-01'  AND date < '".$date_from."'");
						while($result5_ob = mysql_fetch_assoc($sql5_ob))
						{ 
							$totalexpenses_ob += $result5_ob['amount'];
						}
						
						$ob = mysql_query("SELECT * FROM opening_balance");
						$result_ob = mysql_fetch_assoc($ob);
//$opening_balance = $result_ob['amount'] + $totalcollected_ob + $totalamount_collected_desc_ob + $cash_balance_ob - $totalout_ob - $totalexpenses_ob;

						$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$payout_month."'");
						$result_balance = mysql_fetch_assoc($sql_balance);
						$result_balance_row  = mysql_num_rows($sql_balance);
						$interest_paid_out = $result_balance['interest_paid_out'];
						$return_capital = $result_balance['return_capital'];
						$capital_in = $result_balance['capital_in'];
						$opening_balance_k = $result_balance['opening_balance'];
						$collected_k = $result_balance['collected'];
						$settle_k = $result_balance['settle'];
						$baddebt_k = $result_balance['baddebt'];
						$monthly_k = $result_balance['monthly'];
						$payout_k = $result_balance['payout'];
						$expenses_k = $result_balance['expenses'];
						$expenses_k2 = $result_balance['expenses_2'];

						if($result_balance_row =='0')
						{

							$payout_month_bf = date('Y-m', strtotime('-1 month', strtotime($payout_month)));
							  $sql_4 = mysql_query("SELECT
								customer_loanpackage.loan_code,
								customer_loanpackage.start_month,
								customer_loanpackage.payout_date,
								customer_loanpackage.loan_amount,
								customer_loanpackage.loan_period,
								customer_loanpackage.loan_total,
								customer_loanpackage.loan_status,
								customer_details.customercode2,
								customer_details.name,
								customer_details.nric,
								customer_employment.company,
								temporary_payment_details.monthly,
								temporary_payment_details.loan_percent,
								temporary_payment_details.loan_status,
								temporary_payment_details.customer_loanid 
							FROM
								customer_loanpackage
								LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
								LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
								LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
							WHERE
								temporary_payment_details.loan_month = '".$payout_month_bf."'
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
							loan_payment_details.month_receipt <'".$payout_month_bf."'
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
							loan_payment_details.month_receipt <'".$payout_month_bf."'
							)
					
							AND loan_payment_details.loan_status='BAD DEBT' 
							GROUP BY customer_loanpackage.loan_code
							ORDER BY customer_loanpackage.loan_code ASC )
							GROUP BY temporary_payment_details.loan_code
							ORDER BY
								temporary_payment_details.loan_code ASC");
					
					
						while($result_4 = mysql_fetch_assoc($sql_4))
						{ 
						if($result_4['start_month'] == $payout_month_bf)
						{
							$out_bf = $result_4['loan_amount']-($result_4['loan_amount']*0.1);
						}
						else
						{
							$out_bf = '';
						}

						$collected_bf='';
						$settle_bf='';
					

							$sql = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt ='".$payout_month_bf."'");
							while($result = mysql_fetch_assoc($sql)){

							if($result_4['loan_code']==$result['receipt_no']){

							if($result_4['loan_period']>=1 && $result_4['loan_period']<=12)
						{
							if($result['loan_status']=='SETTLE')
							{
								$collected_bf='';
								
								$settle_bf=$result_4['loan_percent'];
						

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected_bf='';

								$settle_bf='';
							
							}else
							{
								$collected_bf=$result_4['monthly'];
								
								$settle_bf='';
							
							}
							
						

						}else if($result_4['loan_period']>=13 && $result_4['loan_period']<=24)
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected_bf='';
								
								$settle_bf=$result_4['loan_percent'];
						

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected_bf='';

								$settle_bf='';
							
							}else
							{
								$collected_bf=$result_4['monthly'];
								
								$settle_bf='';
							
							}
						

						}else if($result_4['loan_period']>=25 && $result_4['loan_period']<=36)
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected_bf='';
								
								$settle_bf=$result_4['loan_percent'];
						

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected_bf='';

								$settle_bf='';
							
							}else
							{
								$collected_bf=$result_4['monthly'];
								
								$settle_bf='';
							
							}
						

						}else if($result_4['loan_period']>=37 && $result_4['loan_period']<=48)
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected_bf='';
								
								$settle_bf=$result_4['loan_percent'];
						

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected_bf='';

								$settle_bf='';
							
							}else
							{
								$collected_bf=$result_4['monthly'];
								
								$settle_bf='';
							
							}

						
						}else
						{
						if($result['loan_status']=='SETTLE')
							{
								$collected_bf='';
								
								$settle_bf=$result_4['loan_percent'];
						

							}else if($result['loan_status']=='BAD DEBT')
							{
								$collected_bf='';

								$settle_bf='';
							
							}else
							{
								$collected_bf=$result_4['monthly'];
								
								$settle_bf='';
							
							}

						}
							}
						}

						$totalout_bf+=$out_bf;
						$totalcollected_bf+=$collected_bf;
						$totalsettle_bf+=$settle_bf;

						$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$payout_month_bf."'");
						$result_balance = mysql_fetch_assoc($sql_balance);
						$interest_paid_out_bf = $result_balance['interest_paid_out'];
						$return_capital_bf = $result_balance['return_capital'];
						$capital_in_bf = $result_balance['capital_in'];
						$opening_balance_k = $result_balance['opening_balance'];
						
						$baddebt_bf = $result_balance['baddebt'];
						$monthly_bf = $result_balance['monthly'];
		
						$expenses_bf = $result_balance['expenses'];
						$expenses_bf2 = $result_balance['expenses_2'];

						$closing_balance_t = $opening_balance_k + $totalcollected_bf + $totalsettle_bf + $capital_in_bf + $baddebt_bf + $monthly_bf - $totalout_bf - $expenses_bf - $expenses_bf2 - $interest_paid_out_bf - $return_capital_bf;

						$opening_balance_k = $closing_balance_t;

}
}else{$opening_balance_k = $opening_balance_k;}
						

						$closing_balance = $opening_balance_k + $totalcollected + $totalsettle + $capital_in + $totalamount_collected_desc + $monthly_k - $totalout - $expenses_k - $expenses_k2 - $interest_paid_out - $return_capital;
					
						// $closing_balance = $opening_balance_k + $collected_k + $settle_k + $capital_in + $baddebt_k + $monthly_k - $payout_k - $expenses_k - $interest_paid_out - $return_capital;
						// $closing_balance = $opening_balance + $totalcollected + $totalsettle + $capital_in + $totalamount_collected_desc + $cash_balance - $totalout - $totalexpenses - $interest_paid_out - $return_capital;


						?>
					
			<table width="70%" align="left"  cellspacing="3" cellpadding="2" style="font-family:Time New Roman;font-size:12px;height:35px;color: black;">
			<tr style="height:20px;">
				<td colspan="4" style = "text-align:right;"></td>
				<td style = "text-align:right;"></td>
				<td colspan="2" style = "text-align:left;"></td>
				<td width="7%" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;"></td>
				
				<td> Instalment</td>
			</tr>
			<tr style="height:20px;">
				<td colspan="4" style = "text-align:right; " >Bad Debts This Month</td>
				<td style = "text-align:right;">= RM</td>
				<td width="15%" colspan="2" style = "text-align:left;"><?php echo number_format($totalamount_instalment_desc);?></td>
				
				<td  style = "background-color:yellow;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;"></td>
				<td> Monthly + Instalment</td>
			</tr>
			<tr style="height:20px;">
				<td colspan="4" style = "text-align:right;" >Bad Debts Collected This Month</td>
				<td style = "text-align:right;">= RM</td>
				<td colspan="2" style = "text-align:left;"><?php echo number_format($totalamount_collected_desc);?></td>
			
				<td  style = "background-color:green;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;"></td>
				<td> Monthly</td>
			</tr>
			</table></div><div style="font-size:10px;">
				<table width="65%" align="left" id="list_table" cellspacing="1" cellpadding="2" style="font-family:Time New Roman;padding-right:150px;padding-left:50px;padding-bottom:-230px;font-size:12px;color:black ;">
			<tr>
				<td colspan="5"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">BD DATE</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">Amount</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">Collected</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">Date</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">Balance</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">Monthly</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">Envelope</td>
			</tr>
			<?php 
		$ctr1=0;

		$date_from = $payout_month.'-01';
		$date_to = $payout_month.'-31';
				$sql_4 = mysql_query("SELECT
										late_interest_record.id,
										late_interest_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										late_interest_record.bd_date,
										SUM(late_interest_record.amount) as amount,
										SUM(late_interest_record.balance) as balance,
										late_interest_record.bd_from
									FROM
										late_interest_record
									LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
									WHERE late_interest_record.month_receipt < '".$payout_month."' AND status != 'HIDDEN'
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");

		$total_bd_collected = 0;
		$totalbalance = 0;
		$total_previous_month_bd = 0;
		$total_envelope = 0;
														
						while($result_4 = mysql_fetch_assoc($sql_4))
						{ 
$payment_date='';

if($result_4['bd_from']=='Instalment')
{
	$style_bd = '';
}
else if($result_4['bd_from']=='Monthly')
{
	$style_bd = 'background-color:green;';
}
else
{
	$style_bd = 'background-color:yellow;';
}

							$sql_4a = mysql_query("SELECT
										late_interest_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										late_interest_record.bd_date,
										late_interest_record.amount as amount,
										SUM(late_interest_payment_details.amount) as collected,
										MAX(late_interest_payment_details.payment_date) AS payment_date,
										late_interest_record.balance as balance
									FROM
										late_interest_record
										LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
										LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
									WHERE 	late_interest_record.month_receipt < '".$payout_month."'
									AND (late_interest_payment_details.month_receipt = '".$payout_month."')
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");
														
							while($result_4a = mysql_fetch_assoc($sql_4a))
							{
								if($result_4['loan_code']==$result_4a['loan_code']) 
								{
									$payment_date=date("d/M/Y",strtotime($result_4a['payment_date']));
								}


							}
							$amount_collected=0;

							$previous_year_month = $instalment_year . '-' . date('m', mktime(0,0,0,$instalment_month - 1));

							$balance = 0;
							$previous_monthly_bd = 0;
							$envelope = 0;
							$deductible = 0;
							$current_month_bd_collected = 0;
							$previous_months_bd_collected = 0;
														
							$query = mysql_query("SELECT SUM(amount) AS collected FROM late_interest_payment_details WHERE lid = '" . $result_4['id'] . "'");
							while ($row = mysql_fetch_assoc($query)) {
								$previous_months_bd_collected += round($row['collected'],2);
							}

							$query = mysql_query("SELECT SUM(amount) AS collected FROM late_interest_payment_details WHERE lid = '" . $result_4['id'] . "' AND month_receipt = '" . $payout_month . "'");
							while ($row = mysql_fetch_assoc($query)) {
								$current_month_bd_collected += round($row['collected'],2);
							}

							$query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_bd FROM monthly_payment_record WHERE loan_code = '" . $result_4['loan_code'] . "' AND month = '" . $previous_year_month . "'");
							$result_monthly_payment_record = mysql_fetch_assoc($query);

							$previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
							
							$amount = $result_4['amount'] - $previous_months_bd_collected;

							$query = mysql_query("SELECT MIN(balance) AS min_balance FROM loan_payment_details WHERE receipt_no = '" . $result_4['loan_code'] . "'");
							$result_loan_payment_details = mysql_fetch_assoc($query);

							$loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];

							$amount_without_collected = $result_4['amount'] - $previous_monthly_bd;

							$envelope = $loan_payment_details_min_balance - $amount_without_collected;

							$query = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '" . $result_4['loan_code'] . "'");
							$result_late_interest_record = mysql_fetch_assoc($query);

							$bd_date = new DateTime($result_late_interest_record['bd_date']);

							if ($bd_date->format('Y-m') != $result_late_interest_record['month_receipt']) {
								$amount += $bd_collected;
							} else {
								$amount -= $previous_monthly_bd;
							}

							$difference = $amount - $bd_collected;
							if ($difference < 0) {
								$amount = 0;
								$deductible = abs($difference);
							} else {
								$amount = $difference;
								if ($amount == $difference) {
									$deductible = 0;
								} else {
									$deductible = abs($difference);
								}
							}

							$difference = $previous_monthly_bd - $deductible;
							if ($difference < 0) {
								$previous_monthly_bd = 0;
								$deductible = abs($difference);
							} else {
								$previous_monthly_bd = $difference;
								if ($previous_monthly_bd == $difference) {
									$deductible = 0;
								} else {
									$deductible = abs($difference);
								}
							}

							if ($previous_monthly_bd == 0) {
								$envelope -= $deductible;
							}

							if ($envelope < 0) {
								$envelope = 0;
							} 

							$balance = $amount + $previous_monthly_bd + $envelope;

							if ($balance == 0) {
								continue;	// not showing this row if balance is zero
							}

							$total_bd_collected += $current_month_bd_collected;
							$totalbalance += $balance;
							$total_previous_month_bd += $previous_monthly_bd;
							$total_envelope += $envelope;

							$ctr1++;							
?>			
							

			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

				<td width="3%" style = "text-align:center;"><?php echo $ctr1;?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><a href="paybaddebt.php?id=<?php echo $result_4['id'];?>" title="Make Payment"><?php if(substr($result_4['loan_code'],0,2) == 'MS' || substr($result_4['loan_code'],0,2) == 'MJ'){echo preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_4['loan_code']);}else { echo $result_4['loan_code'];} ?></a></a></td>
				<td width="7%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo $result_4['customercode2'];?></td>
				<td width="30%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo $result_4['name'];?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo date("d/m/Y",strtotime($result_4['bd_date']));?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo number_format($amount);?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo $current_month_bd_collected;?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo $payment_date;?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo number_format($balance);?></td>
				<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo number_format($previous_monthly_bd);?></td>
				<td width="15%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;<?php echo $style_bd;?>"><?php echo number_format($envelope); ?></td>
			</tr> <?php } ?>
				<tr>
				<td style = "text-align:center;">&nbsp;</td>
				<td style = "text-align:center;">&nbsp;</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			</tr>
			<tr>
				<td style = "text-align:center;"></td>
				<td style = "text-align:center;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">TOTAL</td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_bd_collected);?></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalbalance);?></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_previous_month_bd);?></td>
				<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_envelope);?></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td style = "text-align:center;"></td><td style = "text-align:center;"></td><td style = "text-align:center;"></td><td colspan="8" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"><b>Bad Debts This Month (Instalment)</b></td></tr>
			<tr>
				<tr><td width="3%"  style = "text-align:center;"></td><td width="3%"  style = "text-align:center;"></td><td width="3%"  style = "text-align:center;"></td>
			<td width="7%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">AGG</td>
			<td width="7%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">ID</td>
			<td width="20%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">NAME</td>
			<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">IC NO</td>
			<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">DATE ISSUE</td>
			<td width="20%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">COMPANY</td>
			<td colspan="2" width="5%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">AMOUNT(INSTALMENT)</td></tr>
			<?php 
			$ctr2=0;
				$sql_5 = mysql_query("SELECT
										baddebt_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										customer_details.nric,
										customer_loanpackage.payout_date,
										customer_employment.company,
										baddebt_record.bd_date,
										SUM(baddebt_record.amount) as amount
									FROM
										baddebt_record
										LEFT JOIN customer_details ON baddebt_record.customer_id = customer_details.id
										LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = baddebt_record.loan_code
										LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
									WHERE baddebt_record.month_receipt = '".$payout_month."' AND baddebt_record.bd_from='Instalment'
									GROUP BY baddebt_record.loan_code
									ORDER BY baddebt_record.bd_date ASC");
														
						while($result_5 = mysql_fetch_assoc($sql_5))
						{ 
							$ctr2++;
							$totalamount_instalment += $result_5['amount'];

							?>

	<tr ><td style = "text-align:center;">&nbsp;</td><td style = "text-align:center;">&nbsp;</td><td style = "text-align:center;"><?php echo $ctr2;?></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php if(substr($result_5['loan_code'],0,2) == 'MS'){echo preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_5['loan_code']);}else { echo $result_5['loan_code'];} ?></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php echo $result_5['customercode2'];?></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php echo $result_5['name'];?></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php echo $result_5['nric'];?></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php echo date("d/m/Y",strtotime($result_5['payout_date']));?></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php echo $result_5['company'];?></td>
			<td colspan="2" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;background-color:orange;"><?php echo $result_5['amount'];?></td></tr><?php } ?>

	<tr><td style = "text-align:center;"></td><td style = "text-align:center;"></td><td style = "text-align:center;"></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			<td style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td>
			<td colspan="2" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"></td></tr>
	<tr><td style = "text-align:center;"></td><td style = "text-align:center;"></td><td style = "text-align:center;"></td><td colspan="6" style = "text-align:right;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;">TOTAL</td>
			<td colspan="2" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalamount_instalment);?></td></tr>
			<tr><td>&nbsp;</td></tr>

		</table>
    <table width="500"  id="list_table" cellspacing="0" cellpadding="1" align="right" style="font-family:Time New Roman;padding-right:150px;padding-left:50px;padding-bottom:-230px;font-size:12px; color:black ;">	

    	<td colspan="2" style="text-align: right;">Opening Balance =</td>
        <!-- <td style="color:#FF00FF;"><?php echo number_format($opening_balance, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="opening_balance_k" id="opening_balance_k" value=" <?php echo number_format($opening_balance_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_opening_balance('<?php echo $payout_month; ?>')" title="save opening balance"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
    </tr>
        <tr>

    	<td colspan="2" style="text-align: right;">Instalment Collected =</td>
        <td style="color:#FF00FF;"><?php echo number_format($totalcollected, '2'); ?></td>
        <td>&nbsp;</td>
        <!-- <td colspan="3" style="color:#FF00FF;"><input type="text" name="collected_k" id="collected_k" value=" <?php echo number_format($collected_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_collected('<?php echo $payout_month; ?>')" title="save collected"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->

    </tr>
      <tr >
 
    	<td colspan="2" style="text-align: right;">Settle =</td>
        <td style="color:#FF00FF;"><?php echo number_format($totalsettle, '2'); ?></td>
        <td>&nbsp;</td>
        <!-- <td colspan="3" style="color:#FF00FF;"><input type="text" name="settle_k" id="settle_k" value=" <?php echo number_format($settle_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_settle('<?php echo $payout_month; ?>')" title="save settle"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->

    </tr>
     <tr>

    	<td colspan="2" style="text-align: right;">Capital In =</td>
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="capital_in" id="capital_in" value=" <?php echo number_format($capital_in,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_capital_in('<?php echo $payout_month; ?>')" title="save capital in"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
 
    </tr>
          <tr>

    	<td colspan="2" style="text-align: right;">BD Collected = </td>
        <td style="color:#FF00FF;"><?php echo number_format($totalamount_collected_desc, '2'); ?></td>
        <td>&nbsp;</td>
<!--         <td colspan="3" style="color:#FF00FF;"><input type="text" name="baddebt_k" id="baddebt_k" value=" <?php echo number_format($baddebt_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_bad_debt('<?php echo $payout_month; ?>')" title="save bad debt"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->

    </tr>
       <tr>

    	<td colspan="2" style="text-align: right;">Monthly =</td>
        <!-- <td style="color:#FF00FF;"><?php echo number_format($cash_balance, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="monthly_k" id="monthly_k" value=" <?php echo number_format($monthly_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_monthly('<?php echo $payout_month; ?>')" title="save monthly"><img src="../img/document_save.png" /></a>
		<?php } ?></td>

    </tr>
        <tr>

    	<td colspan="2" style="text-align: right;color:red;">Instalment Payout =</td>
        <td style="color:red;"><?php echo number_format($totalout, '2'); ?></td>
        <td>&nbsp;</td>
        <!-- <td colspan="3" style="color:#FF00FF;"><input type="text" name="payout_k" id="payout_k" value=" <?php echo number_format($payout_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_payout('<?php echo $payout_month; ?>')" title="save payout"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->

    </tr>
        <tr>
    
    	<td colspan="2" style="text-align: right;color:red;">Expenses =</td>
        <!-- <td style="color:red;"><?php echo number_format($totalexpenses, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="expenses_k" id="expenses_k" value=" <?php echo number_format($expenses_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_expenses('<?php echo $payout_month; ?>')" title="save expenses"><img src="../img/document_save.png" /></a>
		<?php } ?></td>

    </tr>
    <tr>
    	<td colspan="2" style="text-align: right;color:red;">Expenses 2 =</td>
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="expenses_k2" id="expenses_k2" value=" <?php echo number_format($expenses_k2,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_expenses2('<?php echo $payout_month; ?>')" title="save expenses 2"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
    </tr>

     <tr>
 
    	<td colspan="2" style="text-align: right;color:red;">Interest Paid Out =</td>
        <td colspan="3" style="color:red;"><input type="text" name="interest_paid_out" id="interest_paid_out" value=" <?php echo number_format($interest_paid_out,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_interest('<?php echo $payout_month; ?>')" title="save interest paid out"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
      
    </tr>
         <tr>
 
    	<td colspan="2" style="text-align: right;color:red;">Return Capital =</td>
        <td colspan="3" style="color:red;"><input type="text" name="return_capital" id="return_capital" value=" <?php echo number_format($return_capital,2); ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_capital('<?php echo $payout_month; ?>')" title="save return capital"><img src="../img/document_save.png" /></a>
		<?php } ?></td>

    </tr>
         <tr>

    	<td colspan="2" style="text-align: right;">Closing Balance =</td>
        <td style="color:#FF00FF;"><?php echo number_format($closing_balance, '2'); ?><input type="hidden" name="closing_balance" id="closing_balance" value=" <?php echo number_format($closing_balance,2); ?>"></td>

    </tr>
         <tr>
    	<td>&nbsp;</td>
    </tr>
    </tr>
         <tr>
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
		function save_interest(pay_month)
{
	$interest_paid_out = document.getElementById('interest_paid_out').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Interest Paid Out',
		'message'	:  'Are You sure want to update the interest paid out?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_interest',
							interest_paid_out: $interest_paid_out,
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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
	function save_capital(pay_month)
{
	$return_capital = document.getElementById('return_capital').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Return Capital',
		'message'	:  'Are You sure want to update the return capital?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_capital',
							opening_balance_k: $opening_balance_k,
							return_capital: $return_capital,
							pay_month: $pay_month,
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

	function save_capital_in(pay_month)
{
	$capital_in = document.getElementById('capital_in').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;

	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Capital In',
		'message'	:  'Are You sure want to update the capital in?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_capital_in',
							opening_balance_k: $opening_balance_k,
							capital_in: $capital_in,
							pay_month: $pay_month,
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
//11032022
// 	function save_xingfeng(loan_code,ctr1)
// {
// 	$loan_code = loan_code;
// 	$ctr1 = ctr1;
// 	$xingfeng = document.getElementById('xingfeng_'+$ctr1).value;
	
// 	$.confirm({
// 		'title'		: 'Update Xing Feng',
// 		'message'	:  'Are You sure want to update the xing feng?',
// 		'buttons'	: {
// 			'Yes'	: {
// 			'class'	: 'blue',
// 			'action': function(){
// 				$.ajax({
// 						type: 'POST',
// 						data: {
// 							action: 'update_xingfeng',
// 							ctr: $ctr,
// 							loan_code: $loan_code,
// 							xingfeng: $xingfeng,
							
// 						},
// 						url: 'action.php',
// 						success: function(){
// 							location.reload();
// 						}
// 					})
// 				}
// 			},
// 			'No'	: {
// 				'class'	: 'gray',
// 				'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
// 			}
// 		}
// 	});
// }
function save_xingfeng(loan_code, ctr,pay_month)
{
	$loan_code = loan_code;
	$pay_month = pay_month;
	$ctr = ctr;
	$xingfeng = document.getElementById('xingfeng_'+$ctr).value;
	
	$.confirm({
		'title'		: 'Update Xing Feng',
		'message'	:  'Are You sure want to update the xing feng?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
	$.ajax({
			type: 'POST',
			data: {
				action: 'update_xingfeng',
				xingfeng: $xingfeng,
				pay_month: $pay_month,
				loan_code: $loan_code
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


	function save_opening_balance(pay_month)
{
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Opening Balance',
		'message'	:  'Are You sure want to update the opening balance?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_opening_balance',
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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
	function save_expenses(pay_month)
{
	$expenses_k = document.getElementById('expenses_k').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Expense',
		'message'	:  'Are You sure want to update the expenses?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_expenses',
							expenses_k: $expenses_k,
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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
	function save_expenses2(pay_month)
{
	$expenses_k2 = document.getElementById('expenses_k2').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Expense',
		'message'	:  'Are You sure want to update the expenses 2?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_expenses2',
							expenses_k2: $expenses_k2,
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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

function save_collected(pay_month)
{
	$collected_k = document.getElementById('collected_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Collected',
		'message'	:  'Are You sure want to update the collected?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_collected',
							collected_k: $collected_k,
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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
}	function save_settle(pay_month)
{
	$settle_k = document.getElementById('settle_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Settle',
		'message'	:  'Are You sure want to update the settle?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_settle',
							settle_k: $settle_k,
							pay_month: $pay_month,
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
}	function save_bad_debt(pay_month)
{
	$baddebt_k = document.getElementById('baddebt_k').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Bad Debt',
		'message'	:  'Are You sure want to update the bad debt?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_bad_debt',
							baddebt_k: $baddebt_k,
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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
}	function save_monthly(pay_month)
{
	$monthly_k = document.getElementById('monthly_k').value;
	$opening_balance_k = document.getElementById('opening_balance_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Monthly',
		'message'	:  'Are You sure want to update the monthly?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_monthly',
							monthly_k: $monthly_k,
							opening_balance_k: $opening_balance_k,
							pay_month: $pay_month,
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
}	function save_payout(pay_month)
{
	$payout_k = document.getElementById('payout_k').value;
	$pay_month = pay_month;
	$.confirm({
		'title'		: 'Update Payout',
		'message'	:  'Are You sure want to update the Payout?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'update_payout',
							payout_k: $payout_k,
							pay_month: $pay_month,
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
//11032022
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
			let year = $('#instalment_year').val();
			let month = $('#instalment_month').val();
			window.location.href = 'instalment.php?year=' + year + '&month=' + month;
		});
	});
</script>
</body>
</html>