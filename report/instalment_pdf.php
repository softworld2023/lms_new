<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');

// Set the memory limit to 256 megabytes
ini_set('memory_limit', '256M');

 $year = $_GET['year'];
$month = $_GET['month'];
	 // $year = '2020';
	 // $month = '04';
	 $date= $year.'-'.$month;
	 $payout_month = $year.'-'.$month;
$date_from = $year.'-'.$month.'-01';
$date_to = $year.'-'.$month.'-31';

$mth1 = $month;
if ($mth1=='01'){$mth1 = 'Jan';}
else if($mth1=='02'){$mth1 = 'Feb';}
else if($mth1=='03'){$mth1 = 'March';}
else if($mth1=='04'){$mth1 = 'April';}
else if($mth1=='05'){$mth1 = 'May';}
else if($mth1=='06'){$mth1 = 'June';}
else if($mth1=='07'){$mth1 = 'July';}
else if($mth1=='08'){$mth1 = 'Aug';}
else if($mth1=='09'){$mth1 = 'Sept';}
else if($mth1=='10'){$mth1 = 'Oct';}
else if($mth1=='11'){$mth1 = 'Nov';}
else if($mth1=='12'){$mth1 = 'Dec';}

$mpdf = new mPDF('', 'A4-L', '', '', 10, 10, 12, 12, 5, 5);

switch ($_SESSION['login_branch']) {
	case 'MAJUSAMA':
		$branch_name = 'MJ MAJUSAMA SDN BHD';
		break;
	case 'MAJUSAMA2':
		$branch_name = 'MJ2 MAJUSAMA SDN BHD';
		break;
	case 'ANSENG':
		$branch_name = 'ANSENG CREDIT SDN BHD';
		break;
	case 'YUWANG':
		$branch_name = 'YUWANG';
		break;
	case 'DK':
		$branch_name = 'DESA KOMERSIAL SDN BHD';
		break;
	case 'KTL':
		$branch_name = 'KTL SETIA REALTY SDN BHD';
		break;
	case 'TSY':
		$branch_name = 'TSY AGENCY';
		break;
	case 'TSY2':
		$branch_name = 'TSY2 AGENCY';
		break;
}

$header='<div style="text-align:left;font-size:11px;color:blue;">
			<b>INSTALMENT</b>
		</div>
		<table width="40%" align="left"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
		<tr><td>' . $branch_name . '</td><td>
		<div style="text-align:center;font-size:10px;">
			<b>Statement for: <span style="color:red;">'.$mth1.' - '.$year.'</span>
		</div></td></tr></table>
		';

$mpdf->WriteHTML($header);

$html='
		<div style="font-size:8px;">
			<table width="100%" align="center"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:8px;">
			<tr>
				<td rowspan="3" style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;" >Bil</td>
				<td rowspan="3" width="6%" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Agreement No.</td>
				<td rowspan="3" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">ID</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Name</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">I/C NO.</td>
				<td rowspan="3" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Date Issue</td>
				<td rowspan="3" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Company</td>
				<td colspan="3" style="text-align:center;border-top:1px solid black;border-right:1px solid black;color:brown"><b>Loan</b></td>
				<td colspan="14"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;color:red;"><b><i>'.$mth1.' '.$year.'</i></b></td>
			</tr>
			<tr>
				<td rowspan="2" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Applied</td>
				<td rowspan="2" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Monthly</td>
				<td rowspan="2" width="3%" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">X</td>
				<td colspan="5"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;background-color:	#b2ffff;"><b>Loan</b></td>
				<td rowspan="2"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:red"><b>OUT</b></td>
				<td rowspan="2"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:purple;"><b>Collected</b></td>
				<td colspan="5"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;background-color:	#b2ffff;"><b>Return</b></td>
				<td rowspan="2"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:purple;"><b>Settle</b></td>
				<td rowspan="2"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:brown;"><b>Bad Debt</b></td>
			</tr>
			<tr>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;">10%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:green;">8%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:blue;">6.2%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:brown;">5.5%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:#FF00FF;">5%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;">10%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:green;">8%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:blue;">6.2%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:brown;">5.5%</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:#FF00FF;">5%</td>
			</tr>';

$loan_code_arr = array();

$sql_list = mysql_query("SELECT
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
					
$numberofrow = mysql_num_rows($sql_list);
while ($result_list = mysql_fetch_assoc($sql_list)) {
	array_push($loan_code_arr, $result_list['loan_code']);

	$ctr_list++;
	if($result_list['start_month'] == $payout_month)
	{
		$out_list = $result_list['loan_amount']-($result_list['loan_amount']*0.1);
	}
	else
	{
		$out_list = '';
	}

	$collected_list='';
	$collected_remarks='';
	$settle_list='';
	$baddebt_list= '';
	$return_percent1_list='';
	$return_percent2_list='';
	$return_percent3_list='';
	$return_percent4_list='';
	$return_percent5_list='';

	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt ='".$payout_month."'");
	while($result = mysql_fetch_assoc($sql)){
		if ($result_list['loan_code']==$result['receipt_no']) {
			if ($result_list['loan_period']>=1 && $result_list['loan_period']<=12) {
				if($result['loan_status']=='SETTLE')
				{
					$collected_list='';
					$collected_remarks = 'SETTLE';
					$settle_list=$result_list['loan_percent'];
					$baddebt_list= '';

				}else if($result['loan_status']=='BAD DEBT')
				{
					$collected_list='';
					$collected_remarks = 'BD';
					$settle_list='';
					$baddebt_list= '1';
				}else
				{
					$collected_list=$result_list['monthly'];
					$collected_remarks = '';
					$settle_list='';
					$baddebt_list= '';
				}
			} else if ($result_list['loan_period']>=13 && $result_list['loan_period']<=24) {
				if($result['loan_status']=='SETTLE') {
					$collected_list='';
					$collected_remarks = 'SETTLE';
					$settle_list=$result_list['loan_percent'];
					$baddebt_list= '';

				}else if($result['loan_status']=='BAD DEBT') {
					$collected_list='';
					$collected_remarks = 'BD';
					$settle_list='';
					$baddebt_list= '1';
				}else
				{
					$collected_list=$result_list['monthly'];
					$collected_remarks = '';
					$settle_list='';
					$baddebt_list= '';
				}
			} else if ($result_list['loan_period']>=25 && $result_list['loan_period']<=36) {
				if ($result['loan_status']=='SETTLE') {
					$collected_list='';
					$collected_remarks = 'SETTLE';
					$settle_list=$result_list['loan_percent'];
					$baddebt_list= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected_list='';
					$collected_remarks = 'BD';
					$settle_list='';
					$baddebt_list= '1';
				} else {
					$collected_list=$result_list['monthly'];
					$collected_remarks = '';
					$settle_list='';
					$baddebt_list= '';
				}
			} else if ($result_list['loan_period']>=37 && $result_list['loan_period']<=48) {
				if ($result['loan_status']=='SETTLE') {
					$collected_list='';
					$collected_remarks = 'SETTLE';
					$settle_list=$result_list['loan_percent'];
					$baddebt_list= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected_list='';
					$collected_remarks = 'BD';
					$settle_list='';
					$baddebt_list= '1';
				} else {
					$collected_list=$result_list['monthly'];
					$collected_remarks = '';
					$settle_list='';
					$baddebt_list= '';
				}
			} else {
				if($result['loan_status']=='SETTLE') {
					$collected_list='';
					$collected_remarks = 'SETTLE';
					$settle_list=$result_list['loan_percent'];
					$baddebt_list= '';
				} else if ($result['loan_status']=='BAD DEBT') {
					$collected_list='';
					$collected_remarks = 'BD';
					$settle_list='';
					$baddebt_list= '1';
				} else {
					$collected_list=$result_list['monthly'];
					$collected_remarks = '';
					$settle_list='';
					$baddebt_list= '';
				}

			}
		}
	}

	// if ($result_list['start_month'] == $payout_month) {
	// 	$loan_percent1_list=$result_list['loan_percent'];
	// 	$loan_percent2_list='';
	// 	$loan_percent3_list='';
	// 	$loan_percent4_list='';
	// 	$loan_percent5_list='';
	// 	$style_font = "color:black;";
	// 	$return_percent1_list=$result_list['loan_percent'];
	// 	$return_percent2_list='';
	// 	$return_percent3_list='';
	// 	$return_percent4_list='';
	// 	$return_percent5_list='';
	// } else {
		if ($result_list['loan_period']>=1 && $result_list['loan_period']<=12) {
			$loan_percent1_list=$result_list['loan_percent'];
			$loan_percent2_list='';
			$loan_percent3_list='';
			$loan_percent4_list='';
			$loan_percent5_list='';
			$style_font = "color:black;";
			$return_percent1_list=$result_list['loan_percent'];
			$return_percent2_list='';
			$return_percent3_list='';
			$return_percent4_list='';
			$return_percent5_list='';
		} else if ($result_list['loan_period']>=13 && $result_list['loan_period']<=24) {
			$loan_percent1_list='';
			$loan_percent2_list=$result_list['loan_percent'];
			$loan_percent3_list='';
			$loan_percent4_list='';
			$loan_percent5_list='';
			$style_font = "color:green;";
			$return_percent1_list='';
			$return_percent2_list=$result_list['loan_percent'];
			$return_percent3_list='';
			$return_percent4_list='';
			$return_percent5_list='';
		} else if ($result_list['loan_period']>=25 && $result_list['loan_period']<=36) {
			$loan_percent1_list='';
			$loan_percent2_list='';
			$loan_percent3_list=$result_list['loan_percent'];
			$loan_percent4_list='';
			$loan_percent5_list='';
			$style_font = "color: #0066cc;";
			$return_percent1_list='';
			$return_percent2_list='';
			$return_percent3_list=$result_list['loan_percent'];
			$return_percent4_list='';
			$return_percent5_list='';
		} else if ($result_list['loan_period']>=37 && $result_list['loan_period']<=48) {
			$loan_percent1_list='';
			$loan_percent2_list='';
			$loan_percent3_list='';
			$loan_percent4_list=$result_list['loan_percent'];
			$loan_percent5_list='';
			$style_font = "color:brown;";
			$return_percent1_list='';
			$return_percent2_list='';
			$return_percent3_list='';
			$return_percent4_list=$result_list['loan_percent'];
			$return_percent5_list='';
		} else {
			$loan_percent1_list='';
			$loan_percent2_list='';
			$loan_percent3_list='';
			$loan_percent4_list='';
			$loan_percent5_list=$result_list['loan_percent'];
			$style_font = "color:#FF00FF;";
			$return_percent1_list='';
			$return_percent2_list='';
			$return_percent3_list='';
			$return_percent4_list='';
			$return_percent5_list=$result_list['loan_percent'];
		} 					
	// }


	if ($collected_remarks=='SETTLE') {
		$style_remark ='background-color: yellow;';
	} elseif($collected_remarks=='BD') {
		$style_remark = 'background-color: darkorange;'; 
	} else {
		$style_remark='';
	}
	
	// show first 5 rows and last 5 rows only
	// if ($ctr_list <= 5 || $ctr_list > $numberofrow - 5) {
	// }
	$html.='<tr style="'.$style.';">
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$ctr_list.'</td>
				';
	if(substr($result_list['loan_code'],0,2) == 'MS' 
	|| substr($result_list['loan_code'],0,2) == 'MJ' 
	|| substr($result_list['loan_code'],0,2) == 'PP' 
	|| substr($result_list['loan_code'],0,2) == 'SB' 
	|| substr($result_list['loan_code'],0,2) == 'TL' 
	|| substr($result_list['loan_code'],0,2) == 'YW' 
	|| substr($result_list['loan_code'],0,2) == 'CL' 
	|| substr($result_list['loan_code'],0,2) == 'BT'
	|| substr($result_list['loan_code'],0,2) == 'CG')
	{
		$html.='
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_list['loan_code']).'</td>';
	}else
	{
		$html.='
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$result_list['loan_code'].'</td>';

	}

	$html.='
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$result_list['customercode2'].'</td>
		<td style="text-align:left;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$result_list['name'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$result_list['nric'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.date("d-m-Y",strtotime($result_list['payout_date'])).'</td>
		<td style="text-align:left;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$result_list['company'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($result_list['loan_amount']).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($result_list['monthly']).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$result_list['loan_period'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($loan_percent1_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($loan_percent2_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($loan_percent3_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($loan_percent4_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($loan_percent5_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">'.number_format($out_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.''.$style_remark.'">'.number_format($collected_list).' '.$collected_remarks.'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($return_percent1_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($return_percent2_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($return_percent3_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($return_percent4_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($return_percent5_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.number_format($settle_list).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;'.$style_font.'">'.$baddebt_list.'</td>
	</tr>'; 
}

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
				temporary_payment_details.loan_code ASC");
					
					
while ($result_4 = mysql_fetch_assoc($sql_4)) { 
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
	while ($result = mysql_fetch_assoc($sql)){
		if ($result_4['loan_code']==$result['receipt_no']){
			if ($result_4['loan_period']>=1 && $result_4['loan_period']<=12) {
				if ($result['loan_status']=='SETTLE') {
					$collected='';
					$collected_remarks = 'SETTLE';
					$settle=$result_4['loan_percent'];
					$baddebt= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected='';
					$collected_remarks = 'BD';
					$settle='';
					$baddebt= '1';
				} else {
					$collected=$result_4['monthly'];
					$collected_remarks = '';
					$settle='';
					$baddebt= '';
				}
			} else if ($result_4['loan_period']>=13 && $result_4['loan_period']<=24) {
				if($result['loan_status']=='SETTLE') {
					$collected='';
					$collected_remarks = 'SETTLE';
					$settle=$result_4['loan_percent'];
					$baddebt= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected='';
					$collected_remarks = 'BD';
					$settle='';
					$baddebt= '1';
				} else {
					$collected=$result_4['monthly'];
					$collected_remarks = '';
					$settle='';
					$baddebt= '';
				}
			} else if ($result_4['loan_period']>=25 && $result_4['loan_period']<=36) {
				if ($result['loan_status']=='SETTLE') {
					$collected='';
					$collected_remarks = 'SETTLE';
					$settle=$result_4['loan_percent'];
					$baddebt= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected='';
					$collected_remarks = 'BD';
					$settle='';
					$baddebt= '1';
				} else {
					$collected=$result_4['monthly'];
					$collected_remarks = '';
					$settle='';
					$baddebt= '';
				}
			} else if ($result_4['loan_period']>=37 && $result_4['loan_period']<=48) {
				if ($result['loan_status']=='SETTLE') {
					$collected='';
					$collected_remarks = 'SETTLE';
					$settle=$result_4['loan_percent'];
					$baddebt= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected='';
					$collected_remarks = 'BD';
					$settle='';
					$baddebt= '1';
				} else {
					$collected=$result_4['monthly'];
					$collected_remarks = '';
					$settle='';
					$baddebt= '';
				}
			} else {
				if ($result['loan_status']=='SETTLE') {
					$collected='';
					$collected_remarks = 'SETTLE';
					$settle=$result_4['loan_percent'];
					$baddebt= '';
				} else if($result['loan_status']=='BAD DEBT') {
					$collected='';
					$collected_remarks = 'BD';
					$settle='';
					$baddebt= '1';
				} else {
					$collected=$result_4['monthly'];
					$collected_remarks = '';
					$settle='';
					$baddebt= '';
				}
			}
		}
	}

	if ($result_4['start_month'] == $payout_month) {
		$loan_percent1=$result_4['loan_percent'];
		$loan_percent2='';
		$loan_percent3='';
		$loan_percent4='';
		$loan_percent5='';
		$style_font = "color:black;";
		$return_percent1=$result_4['loan_percent'];
		$return_percent2='';
		$return_percent3='';
		$return_percent4='';
		$return_percent5='';
	} else {
		if ($result_4['loan_period']>=1 && $result_4['loan_period']<=12) {
			$loan_percent1=$result_4['loan_percent'];
			$loan_percent2='';
			$loan_percent3='';
			$loan_percent4='';
			$loan_percent5='';
			$style_font = "color:black;";
			$return_percent1=$result_4['loan_percent'];
			$return_percent2='';
			$return_percent3='';
			$return_percent4='';
			$return_percent5='';
		} else if ($result_4['loan_period']>=13 && $result_4['loan_period']<=24) {
			$loan_percent1='';
			$loan_percent2=$result_4['loan_percent'];
			$loan_percent3='';
			$loan_percent4='';
			$loan_percent5='';
			$style_font = "color:green;";
			$return_percent1='';
			$return_percent2=$result_4['loan_percent'];
			$return_percent3='';
			$return_percent4='';
			$return_percent5='';
		} else if ($result_4['loan_period']>=25 && $result_4['loan_period']<=36) {
			$loan_percent1='';
			$loan_percent2='';
			$loan_percent3=$result_4['loan_percent'];
			$loan_percent4='';
			$loan_percent5='';
			$style_font = "color: #0066cc;";
			$return_percent1='';
			$return_percent2='';
			$return_percent3=$result_4['loan_percent'];
			$return_percent4='';
			$return_percent5='';
		} else if ($result_4['loan_period']>=37 && $result_4['loan_period']<=48) {
			$loan_percent1='';
			$loan_percent2='';
			$loan_percent3='';
			$loan_percent4=$result_4['loan_percent'];
			$loan_percent5='';
			$style_font = "color:brown;";
			$return_percent1='';
			$return_percent2='';
			$return_percent3='';
			$return_percent4=$result_4['loan_percent'];
			$return_percent5='';
		} else {
			$loan_percent1='';
			$loan_percent2='';
			$loan_percent3='';
			$loan_percent4='';
			$loan_percent5=$result_4['loan_percent'];
			$style_font = "color:#FF00FF;";
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

	$totalloan=$total_loan_percent1+$total_loan_percent2+$total_loan_percent3+$total_loan_percent4+$total_loan_percent5;
	$after_total_loan_percent1 = $total_loan_percent1*0.1;
	$after_total_loan_percent2 = $total_loan_percent2*0.08;
	$after_total_loan_percent3 = $total_loan_percent3*0.062;
	$after_total_loan_percent4 = $total_loan_percent4*0.055;
	$after_total_loan_percent5 = $total_loan_percent5*0.05;
	$totalint = $after_total_loan_percent1 +$after_total_loan_percent2+$after_total_loan_percent3+$after_total_loan_percent4+$after_total_loan_percent5;
}

for ($i=1; $i <=4; $i++) {
	$html.='
			<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">&nbsp;</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
			</tr>';
}

$html.='
			<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($total_loan_percent1,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:green;">'.number_format($total_loan_percent2,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">'.number_format($total_loan_percent3,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">'.number_format($total_loan_percent4,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">'.number_format($total_loan_percent5,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">'.number_format($totalout).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($totalcollected).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;">'.number_format($total_return_percent1,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:green;">'.number_format($total_return_percent2,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:blue;">'.number_format($total_return_percent3,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:brown;">'.number_format($total_return_percent4,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;color:#FF00FF;">'.number_format($total_return_percent5,0).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;">'.number_format($totalsettle).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;">'.$totalbaddebt.'</td>
			</tr>
			<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($total_applied).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($total_month).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($after_total_loan_percent1).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($after_total_loan_percent2).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($after_total_loan_percent3).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($after_total_loan_percent4).'</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'.number_format($after_total_loan_percent5).'</td>
				<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">Total Int = '.number_format($totalint).'</td>
				<td colspan="3" style="text-align:center;border-bottom: 1px solid black;background-color:#b2ffff;">Total loan</td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;background-color:#b2ffff;"><b>'.number_format($totalloan).'</b></td>
			</tr>
		</table>';
					
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

while ($result_monthly = mysql_fetch_assoc($sql_monthly)) { 
	if($result_monthly['status']=='FINISHED') {
		$cash_balance+=(($result_monthly['PA']-$result_monthly['balance'])*0.1);
	} else if($result_monthly['status']=='PAID') {
		$cash_balance+=0;
	} else if($result_monthly['status']=='BAD DEBT') {
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
				SUM(baddebt_record.amount) as amount
			FROM
				baddebt_record
				LEFT JOIN customer_details ON baddebt_record.customer_id = customer_details.id
				LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = baddebt_record.loan_code
				LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
			WHERE baddebt_record.month_receipt = '".$payout_month."' AND
			baddebt_record.bd_from ='Instalment'
			GROUP BY baddebt_record.loan_code
			ORDER BY baddebt_record.bd_date ASC");
														
while($result_thismonth_bd = mysql_fetch_assoc($sql_thismonth_bd))
{ 
	$totalamount_instalment_desc += $result_thismonth_bd['amount'];
}

$sql1_ob = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt > '2021-12' AND month_receipt <'".$payout_month."' AND payment_date >'2022-01-01' AND payment_date <'".$date_from."'");
while($result1_ob = mysql_fetch_assoc($sql1_ob))
{
	$totalcollected_ob +=$result1_ob['payment'];
}

/* =========================
   REMAINING CODE (from $sql1_ob onwards)
   ========================= */

/* Ensure these are defined (they are used later in BD section) */
$instalment_year  = $year;
$instalment_month = (int)$month;

/* Initialize accumulators to avoid undefined warnings */
$totalcollected_ob = 0;
$totalout_ob = 0;
$totalamount_collected_desc_ob = 0;
$cash_balance_ob = 0;
$totalexpenses_ob = 0;

$totalexpenses = isset($totalexpenses) ? $totalexpenses : 0;
$totalamount_instalment_desc = isset($totalamount_instalment_desc) ? $totalamount_instalment_desc : 0;
$totalamount_collected_desc = isset($totalamount_collected_desc) ? $totalamount_collected_desc : 0;

/* ---------- OB / Previous-period calculations ---------- */
$sql1_ob = mysql_query("SELECT * FROM loan_payment_details 
    WHERE month_receipt > '2021-12' 
      AND month_receipt < '".$payout_month."' 
      AND payment_date > '2022-01-01' 
      AND payment_date < '".$date_from."'");

while($result1_ob = mysql_fetch_assoc($sql1_ob)) {
	$totalcollected_ob += $result1_ob['payment'];
}

$sql2_ob = mysql_query("SELECT * FROM customer_loanpackage 
    WHERE payout_date > '2022-01-01' 
      AND payout_date < '".$date_from."'");

while($result2_ob = mysql_fetch_assoc($sql2_ob)) {
	$totalout_ob += ($result2_ob['loan_amount'] - ($result2_ob['loan_amount'] * 0.1));
}

$sql3_ob = mysql_query("SELECT
	late_interest_record.loan_code,
	customer_details.customercode2,
	customer_details.name,
	late_interest_record.bd_date,
	SUM(late_interest_record.amount) as amount,
	late_interest_payment_details.amount as collected,
	late_interest_payment_details.payment_date,
	SUM(late_interest_record.balance) as balance
FROM late_interest_record
LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
WHERE late_interest_record.bd_date > '2022-01-01'
  AND late_interest_record.bd_date < '".$date_from."'
GROUP BY late_interest_record.loan_code
ORDER BY late_interest_record.bd_date ASC");

while($result3_ob = mysql_fetch_assoc($sql3_ob)) {
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
FROM monthly_payment_record
LEFT JOIN customer_details ON monthly_payment_record.customer_id = customer_details.id
LEFT JOIN customer_employment ON customer_details.id = customer_employment.customer_id
WHERE monthly_payment_record.month > '2022-01'
  AND monthly_payment_record.month < '".$payout_month."'
  AND monthly_payment_record.status != 'DELETED'
GROUP BY loan_code
ORDER BY loan_code ASC");

while ($result4_ob = mysql_fetch_assoc($sql4_ob)) {
	if ($result4_ob['status'] == 'FINISHED') {
		$cash_balance_ob += (($result4_ob['PA'] - $result4_ob['balance']) * 0.1);
	} else if($result4_ob['status'] == 'PAID') {
		$cash_balance_ob += 0;
	} else if($result4_ob['status'] == 'BAD DEBT') {
		$cash_balance_ob -= ($result4_ob['PA'] - ($result4_ob['PA'] * 0.1));
	}
}

$sql5_ob = mysql_query("SELECT * FROM expenses 
    WHERE date > '2022-01-01' 
      AND date < '".$date_from."'");

while($result5_ob = mysql_fetch_assoc($sql5_ob)) {
	$totalexpenses_ob += $result5_ob['amount'];
}

/* ---------- Opening balance / instalment balance ---------- */
$ob = mysql_query("SELECT * FROM opening_balance");
$result_ob = mysql_fetch_assoc($ob);

$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$payout_month."'");
$result_balance = mysql_fetch_assoc($sql_balance);
$result_balance_row  = mysql_num_rows($sql_balance);

$interest_paid_out = $result_balance['interest_paid_out'];
$return_capital    = $result_balance['return_capital'];
$capital_in        = $result_balance['capital_in'];
$opening_balance_k = $result_balance['opening_balance'];
$collected_k       = $result_balance['collected'];
$settle_k          = $result_balance['settle'];
$baddebt_k         = $result_balance['baddebt'];
$monthly_k         = $result_balance['monthly'];
$payout_k          = $result_balance['payout'];
$expenses_k        = $result_balance['expenses'];
$expenses_k2       = $result_balance['expenses_2'];

if ($result_balance_row == '0') {
	$payout_month_bf = date('Y-m', strtotime('-1 month', strtotime($payout_month)));

	$sql_balance_bf = mysql_query("SELECT
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
		FROM customer_loanpackage
		LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id
		WHERE temporary_payment_details.loan_month = '".$payout_month_bf."'
		  AND customer_loanpackage.loan_package = 'NEW PACKAGE'
		  AND NOT EXISTS (
				SELECT 1 FROM loan_payment_details lpd
				WHERE lpd.customer_loanid = customer_loanpackage.id
				  AND lpd.loan_status = 'SETTLE'
				  AND lpd.month_receipt < '".$payout_month_bf."'
		  )
		  AND NOT EXISTS (
				SELECT 1 FROM loan_payment_details lpd
				WHERE lpd.customer_loanid = customer_loanpackage.id
				  AND lpd.loan_status = 'BAD DEBT'
				  AND lpd.month_receipt < '".$payout_month_bf."'
		  )
		GROUP BY customer_loanpackage.id
		ORDER BY temporary_payment_details.loan_code ASC");

	$totalout_bf = 0;
	$totalcollected_bf = 0;
	$totalsettle_bf = 0;

	while ($result_bf = mysql_fetch_assoc($sql_balance_bf)) {
		if ($result_bf['start_month'] == $payout_month_bf) {
			$out_bf = $result_bf['loan_amount'] - ($result_bf['loan_amount'] * 0.1);
		} else {
			$out_bf = '';
		}

		$collected_bf = '';
		$settle_bf = '';

		$sql = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt ='".$payout_month_bf."'");
		while ($row_bf = mysql_fetch_assoc($sql)) {
			if ($result_bf['loan_code'] == $row_bf['receipt_no']) {
				if ($row_bf['loan_status'] == 'SETTLE') {
					$collected_bf = '';
					$settle_bf = $result_bf['loan_percent'];
				} else if ($row_bf['loan_status'] == 'BAD DEBT') {
					$collected_bf = '';
					$settle_bf = '';
				} else {
					$collected_bf = $result_bf['monthly'];
					$settle_bf = '';
				}
			}
		}

		$totalout_bf += $out_bf;
		$totalcollected_bf += $collected_bf;
		$totalsettle_bf += $settle_bf;

		$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '".$payout_month_bf."'");
		$result_balance = mysql_fetch_assoc($sql_balance);

		$interest_paid_out_bf = $result_balance['interest_paid_out'];
		$return_capital_bf    = $result_balance['return_capital'];
		$capital_in_bf        = $result_balance['capital_in'];
		$opening_balance_k    = $result_balance['opening_balance'];

		$baddebt_bf = $result_balance['baddebt'];
		$monthly_bf = $result_balance['monthly'];

		$expenses_bf  = $result_balance['expenses'];
		$expenses_bf2 = $result_balance['expenses_2'];

		$closing_balance = $opening_balance_k
			+ $totalcollected_bf
			+ $totalsettle_bf
			+ $capital_in_bf
			+ $baddebt_bf
			+ $monthly_bf
			- $totalout_bf
			- $expenses_bf
			- $expenses_bf2
			- $interest_paid_out_bf
			- $return_capital_bf;

		$opening_balance_k = $closing_balance;
	}
}else{$opening_balance_k = $opening_balance_k;}
	$closing_balance = $opening_balance_k
		+ $totalcollected
		+ $totalsettle
		+ $capital_in
		+ $totalamount_collected_desc
		+ $monthly_k
		- $totalout
		- $expenses_k
		- $expenses_k2
		- $interest_paid_out
		- $return_capital;


/* ---------- Summary box + legends ---------- */
$html.='<table width="25%" border="0" cellspacing="0" cellpadding="2" align="right" style="font-family:Time New Roman;padding-right:20px;padding-left:50px;padding-bottom:-230px;padding-top:64px;font-size:10px;">
		<tr><td colspan="2" style="text-align:center;border-bottom:1px solid black;"><b>'.$mth1.' '.$year.'</b></td></tr>
		<tr><td style="border-bottom:1px solid black black;"></td><td style="border-bottom:1px solid black black;"></td></tr>
		<tr><td style="text-align:right;">Opening Balance=</td><td style="color:#FF00FF;text-align:center;">'.number_format($opening_balance_k, '2').'</td></tr>
		<tr><td style="text-align:right;">Instalment Collected=</td><td style="color:#FF00FF;text-align:center;">'.number_format($totalcollected,2).'</td></tr>
		<tr><td style="text-align:right;">Settle=</td><td style="color:#FF00FF;text-align:center;">'.number_format($totalsettle,2).'</td></tr>
		<tr><td style="text-align:right;">Capital In=</td><td style="color:#FF00FF;text-align:center;">'.number_format($capital_in,2).'</td></tr>
		<tr><td style="text-align:right;">BD Collected=</td><td style="color:#FF00FF;text-align:center;">'.number_format($totalamount_collected_desc,2).'</td></tr>
		<tr><td style="text-align:right;">Monthly=</td><td style="color:#FF00FF;text-align:center;">'.number_format($monthly_k,2).'</td></tr>
		<tr><td style="text-align:right;">Instalment Payout=</td><td style="color:red;text-align:center;">'.number_format($totalout,2).'</td></tr>
		<tr><td style="text-align:right;">Expenses=</td><td style="color:red;text-align:center;">'.number_format($expenses_k,2).'</td></tr>
		<tr><td style="text-align:right;">Expenses 2=</td><td style="color:red;text-align:center;">'.number_format($expenses_k2,2).'</td></tr>
		<tr><td style="text-align:right;">Interest Paid Out=</td><td style="color:red;text-align:center;">'.number_format($interest_paid_out,2).'</td></tr>
		<tr><td style="text-align:right;">Return Capital=</td><td style="color:red;text-align:center;">'.number_format($return_capital,2).'</td></tr>
		<tr><td style="text-align:right;">Closing Balance=</td><td style="color:#FF00FF;text-align:center;">'.number_format($closing_balance,2).'</td></tr>
	</table></div><div style="font-size:8px;">
	<table width="70%" align="left"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:8px;">
	<tr>
		<td colspan="4" style = "text-align:right;"></td>
		<td style = "text-align:right;"></td>
		<td colspan="2" style = "text-align:left;"></td>
		<td width="7%" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;"></td>
		<td> Instalment</td>
	</tr>
	<tr>
		<td colspan="4" style = "text-align:right;" >Bad Debts This Month</td>
		<td style = "text-align:right;">= RM</td>
		<td colspan="2" style = "text-align:left;">'.number_format($totalamount_instalment_desc).'</td>
		<td  style = "background-color:yellow;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;"></td>
		<td> Monthly + Instalment</td>
	</tr>
	<tr>
		<td colspan="4" style = "text-align:right;" >Bad Debts Collected This Month</td>
		<td style = "text-align:right;">= RM</td>
		<td colspan="2" style = "text-align:left;">'.number_format($totalamount_collected_desc).'</td>
		<td  style = "background-color:green;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;"></td>
		<td> Monthly</td>
	</tr>
	</table></div><div style="font-size:8px;">
	<table width="70%" align="left"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:8px; margin-top: 10px;">
	<tr><td></td>
		<td colspan="3" style="border-bottom: 1px solid black;border-right: 1px solid black;"></td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">BD DATE</td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Amount</td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Collected</td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Date</td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Balance</td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Monthly</td>
		<td style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Envelope</td>
	</tr>';

/* ---------- Late interest / BD listing ---------- */
$ctr1 = 0;

$date_from_bd = $payout_month.'-01';
$date_to_bd   = date('Y-m-t', strtotime($date_from_bd));

$sql_4 = mysql_query("SELECT
		late_interest_record.id,
		late_interest_record.loan_code,
		customer_details.customercode2,
		customer_details.name,
		late_interest_record.bd_date,
		late_interest_record.amount,
		late_interest_record.balance,
		late_interest_record.bd_from
	FROM late_interest_record
	LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
	LEFT JOIN monthly_payment_record ON monthly_payment_record.loan_code = late_interest_record.loan_code
	WHERE late_interest_record.month_receipt < '".$payout_month."'
	  AND (late_interest_record.status != 'HIDDEN' OR late_interest_record.status IS NULL)
	  AND (monthly_payment_record.status != 'PAID' OR monthly_payment_record.status IS NULL)
	GROUP BY late_interest_record.loan_code
	ORDER BY late_interest_record.id");

$total_bd_collected = 0;
$totalbalance = 0;
$total_previous_month_bd = 0;
$total_envelope = 0;

$num_of_bd_rows = mysql_num_rows($sql_4);

while ($result_4 = mysql_fetch_assoc($sql_4)) {
	$payment_date = '';
	$latest_payment_date = '';

	if($result_4['bd_from']=='Instalment') {
		$style_bd = '';
	} else if($result_4['bd_from']=='Monthly') {
		$style_bd = 'background-color:green;';
	} else {
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
		FROM late_interest_record
		LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
		LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
		WHERE late_interest_record.month_receipt < '".$payout_month."'
		  AND (late_interest_payment_details.month_receipt = '".$payout_month."')
		GROUP BY late_interest_record.loan_code
		ORDER BY late_interest_record.bd_date ASC");

	while($result_4a = mysql_fetch_assoc($sql_4a)) {
		if($result_4['loan_code']==$result_4a['loan_code']) {
			if (!empty($result_4a['payment_date'])) {
				$payment_date = date("d/M/Y",strtotime($result_4a['payment_date']));
				$latest_payment_date = date('Y-m', strtotime($result_4a['payment_date']));
			}
		}
	}

	$amount_collected = 0;
	$balance = 0;
	$previous_monthly_bd = 0;
	$envelope = 0;
	$deductible = 0;
	$current_month_bd_collected = 0;
	$previous_months_bd_collected = 0;

	$query = mysql_query("SELECT SUM(payout_amount) AS previous_monthly_amount, SUM(balance) AS previous_monthly_bd
		FROM monthly_payment_record
		WHERE loan_code = '" . $result_4['loan_code'] . "'
		  AND status = 'BAD DEBT'");
	$result_monthly_payment_record = mysql_fetch_assoc($query);

	$previous_monthly_bd = $result_monthly_payment_record['previous_monthly_bd'];
	$previous_monthly_amount = $result_monthly_payment_record['previous_monthly_amount'];

	$amount = $result_4['amount'];
	if ($result_4['bd_from'] == 'Monthly') {
		$amount = $previous_monthly_amount - $previous_monthly_bd;
	}

	$query = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '" . $result_4['loan_code'] . "'");
	$result_late_interest_record = mysql_fetch_assoc($query);

	$late_interest_record_id = $result_late_interest_record['id'];
	$bd_from = $result_late_interest_record['bd_from'];
	$branch_name_lower = strtolower($result_late_interest_record['branch_name']);

	if ($result_4['loan_code'] == 'KT 20032') {
		$previous_monthly_bd = 680;
	}
	if ($result_4['loan_code'] == 'KT 22003') {
		$style_bd = 'background-color: yellow;';
	}

	// special handling for mixed old records
	if ($branch_name_lower == 'majusama' && $bd_from == 'Instalment + Monthly' && $late_interest_record_id > 46) {
		$amount -= $previous_monthly_bd;
	} else if ($branch_name_lower != 'majusama' && ($bd_from == 'Instalment + Monthly' || $bd_from == 'Monthly')) {
		$amount -= $previous_monthly_bd;
	}

	$query = mysql_query("SELECT MIN(balance) AS min_balance FROM loan_payment_details WHERE receipt_no = '" . $result_4['loan_code'] . "'");
	$result_loan_payment_details = mysql_fetch_assoc($query);
	$loan_payment_details_min_balance = $result_loan_payment_details['min_balance'];

	$envelope = $loan_payment_details_min_balance - $amount;

	if ($result_4['loan_code'] == 'KT 20034') {
		$envelope += 9157;
	}

	$query = mysql_query("SELECT SUM(amount) AS collected FROM late_interest_payment_details WHERE lid = '" . $result_4['id'] . "' AND month_receipt < '" . $payout_month . "'");
	while ($row = mysql_fetch_assoc($query)) {
		$previous_months_bd_collected += round($row['collected'],2);
	}

	$current_month_receipt = '';
	$query = mysql_query("SELECT SUM(amount) AS collected, month_receipt FROM late_interest_payment_details WHERE lid = '" . $result_4['id'] . "' AND month_receipt = '" . $payout_month . "'");
	while ($row = mysql_fetch_assoc($query)) {
		$current_month_bd_collected += round($row['collected'],2);
		$current_month_receipt = $row['month_receipt'];
	}

	if ($result_4['bd_from'] != 'Monthly') {
		$amount -= $previous_months_bd_collected;
		$amount -= $current_month_bd_collected;
	}

	if ($amount < 0) {
		$deductible = abs($amount);
		$amount = 0;
	}

	if ($amount == 0) {
		$difference = $previous_monthly_bd - $deductible;
		if ($difference <= 0) {
			$previous_monthly_bd = 0;
			$deductible = abs($difference);
		} else {
			$previous_monthly_bd = $difference;
		}
	}

	if ($previous_monthly_bd == 0) {
		$envelope -= $deductible;
	}

	if ($envelope < 0) {
		$envelope = 0;
	}

	if ($branch_name_lower == 'majusama' && $late_interest_record_id <= 46) {
		$previous_monthly_bd = 0;
		$envelope = 0;
	}

	$balance = $amount + $previous_monthly_bd + $envelope;

	$selected_year_month = $instalment_year . '-' . date('m', mktime(0,0,0,$instalment_month));

	if ($result_4['loan_code'] != 'YW1210') {
		if (($balance == 0 || ($bd_from == 'Monthly' && $previous_monthly_bd == 0))
			&& !empty($current_month_receipt)
			&& $selected_year_month > $current_month_receipt) {
			continue;
		}
	} else {
		if (($balance == 0 || ($bd_from == 'Monthly' && $previous_monthly_bd == 0))
			&& !empty($current_month_receipt)
			&& $selected_year_month > $current_month_receipt) {
			continue;
		}
	}

	if ($branch_name_lower == 'yuwang') {
		switch ($result_4['loan_code']) {
			case 'SD0063':
			case 'SD0086':
			case 'SD19186':
			case 'SD19159':
			case '2306-021':
				continue;
			default:
				break;
		}
	}

	$total_bd_collected += $current_month_bd_collected;
	$totalbalance += $balance;
	$total_previous_month_bd += $previous_monthly_bd;
	$total_envelope += $envelope;

	$ctr1++;

	// show first 5 rows and last 5 rows only
	// if ($ctr1 <= 5 || $ctr1 > $num_of_bd_rows - 5) {
	// }
	$html.='
	<tr>
		<td style="text-align:center;">'.$ctr1.'</td>
		<td style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.$result_4['loan_code'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.$result_4['customercode2'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.$result_4['name'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.date("d/m/Y",strtotime($result_4['bd_date'])).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.number_format($amount).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.$current_month_bd_collected.'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.$payment_date.'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.number_format($balance).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.number_format($previous_monthly_bd).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;'.$style_bd.'">'.number_format($envelope).'</td>
	</tr>';
}

$html.='
	<tr>
		<td style="text-align:center;">&nbsp;</td>
		<td style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
	</tr>
	<tr>
		<td style="text-align:center;"></td>
		<td style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">TOTAL</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">'.number_format($total_bd_collected).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">'.number_format($totalbalance).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">'.number_format($total_previous_month_bd).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">'.number_format($total_envelope).'</td>
	</tr>
	</table></div><div>
	<div style="font-size:8px;">
	<table width="70%" align="left" cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:8px;padding-top:25px;">
	<tr><td style="text-align:center;"></td><td colspan="7" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;"><b>Bad Debts This Month (Instalment)</b></td></tr>
	<tr><td width="3%" style="text-align:center;"></td>
		<td width="7%" style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;">AGG</td>
		<td width="7%" style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">ID</td>
		<td width="20%" style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">NAME</td>
		<td width="10%" style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">IC NO</td>
		<td width="10%" style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">DATE ISSUE</td>
		<td width="20%" style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">COMPANY</td>
		<td width="5%" style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">AMOUNT(INSTALMENT)</td>
	</tr>';

/* ---------- BD This Month (Instalment) - show first 5 rows ---------- */
$ctr2_list = 0;
$sql_5_list = mysql_query("SELECT
		baddebt_record.loan_code,
		customer_details.customercode2,
		customer_details.name,
		customer_details.nric,
		customer_loanpackage.payout_date,
		customer_employment.company,
		baddebt_record.bd_date,
		SUM(baddebt_record.amount) as amount
	FROM baddebt_record
	LEFT JOIN customer_details ON baddebt_record.customer_id = customer_details.id
	LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = baddebt_record.loan_code
	LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	WHERE baddebt_record.month_receipt = '".$payout_month."'
	  AND baddebt_record.bd_from='Instalment'
	GROUP BY baddebt_record.loan_code
	ORDER BY baddebt_record.bd_date ASC");

while($result_5_list = mysql_fetch_assoc($sql_5_list)) {
	$ctr2_list++;
	// if($ctr2_list > 5) { break; }

	$html.='<tr><td style="text-align:center;">'.$ctr2_list.'</td>';

	if(substr($result_5_list['loan_code'],0,2) =='MS'
	|| substr($result_5_list['loan_code'],0,2) =='MJ'
	|| substr($result_5_list['loan_code'],0,2) == 'PP'
	|| substr($result_5_list['loan_code'],0,2) =='SB'
	|| substr($result_5_list['loan_code'],0,2) =='YW')
	{
		$html.='<td style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;background-color:orange;">'
			.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_5_list['loan_code'])
			.'</td>';
	} else {
		$html.='<td style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;background-color:orange;">'
			.$result_5_list['loan_code']
			.'</td>';
	}

	$html.='
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;background-color:orange;">'.$result_5_list['customercode2'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;background-color:orange;">'.$result_5_list['name'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;background-color:orange;">'.$result_5_list['nric'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;background-color:orange;">'.date("d-m-Y",strtotime($result_5_list['payout_date'])).'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;background-color:orange;">'.$result_5_list['company'].'</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;background-color:orange;">'.$result_5_list['amount'].'</td>
	</tr>';
}

/* ---------- BD This Month (Instalment) - total amount ---------- */
$ctr2 = 0;
$totalamount_instalment = 0;

$sql_5 = mysql_query("SELECT
		baddebt_record.loan_code,
		customer_details.customercode2,
		customer_details.name,
		customer_details.nric,
		customer_loanpackage.payout_date,
		customer_employment.company,
		baddebt_record.bd_date,
		SUM(baddebt_record.amount) as amount
	FROM baddebt_record
	LEFT JOIN customer_details ON baddebt_record.customer_id = customer_details.id
	LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = baddebt_record.loan_code
	LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	WHERE baddebt_record.month_receipt = '".$payout_month."'
	  AND baddebt_record.bd_from='Instalment'
	GROUP BY baddebt_record.loan_code
	ORDER BY baddebt_record.bd_date ASC");

while($result_5 = mysql_fetch_assoc($sql_5)) {
	$ctr2++;
	$totalamount_instalment += $result_5['amount'];
}

for ($j=1; $j <=2; $j++) {
	$html.='<tr>
		<td style="text-align:center;">&nbsp;</td>
		<td style="text-align:center;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;"></td>
	</tr>';
}

$html.='<tr>
	<td style="text-align:center;"></td>
	<td colspan="6" style="text-align:right;border-right:1px solid black;border-left:1px solid black;border-bottom:1px solid black;">TOTAL</td>
	<td style="text-align:center;border-right:1px solid black;border-bottom:1px solid black;">'.number_format($totalamount_instalment).'</td>
</tr>
</table></div>';

/* ---------- Output PDF ---------- */
$title = "Instalment ( ".$mth1." - ".$year." )";
$mpdf->SetTitle($title);
$mpdf->WriteHTML($html);
$mpdf->Output($title.'.pdf', 'I');

?>
