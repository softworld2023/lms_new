<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$statement_year = $_GET['year'];

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

set_time_limit(120);
ini_set('memory_limit', '5G');

$branch_name = '';
$branch_address = '';

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

function binary_search(Array $arr, $start, $end, $x){
	if ($end < $start)
		return false;
   
	$mid = floor(($end + $start)/2);
	if ($arr[$mid] == $x) 
		return true;
  
	elseif ($arr[$mid] > $x) {
  
		// call binary_search on [start, mid - 1]
		return binary_search($arr, $start, $mid - 1, $x);
	}
	else {
  
		// call binary_search on [mid + 1, end]
		return binary_search($arr, $mid + 1, $end, $x);
	}
}

$mpdf = new mPDF('', 'A4-L', '', '', 2, 2, 5, 5, 10, 10, 'P');
$header = '<div style="text-align:center;font-size:11px;">
			<b>' . $branch_name . '</b>
						<br>
			<span style="font-size:9px;">'. $branch_address . '</span>
			<br>
		</div>
		';

$mpdf->WriteHTML($header);
$html = '
		<div style="font-size:10px;">
			<table width="100%" align="center"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td rowspan="3" style = "text-align:center;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-bottom: 1px solid black;" >MONTH</td>
				<td colspan="6" style="text-align:center;border-top:1px solid black;border-right:1px solid black;">LOAN OUT</td>
				<td colspan="6" style="text-align:center;border-top:1px solid black;border-right:1px solid black;">INTEREST RECEIVED</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">CAPITAL IN</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">RETURN CAPITAL</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">PAY OUT INTEREST</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">EXPENSES </td>
								<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">EXPENSES 2</td>
				<td colspan="6" style="text-align:center;border-top:1px solid black;border-right:1px solid black;">LOAN RETURN</td>
				<td colspan="6" style="text-align:center;border-top:1px solid black;border-right:1px solid black;">BAD DEBTS</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;">BAD DEBTS COLLECTED</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">MONTHLY PROFIT</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">TOTAL PROFIT</td>
				<td rowspan="3"  style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">BALANCE IN HAND</td>
			</tr>
			<tr>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Monthly)</td>
				<td colspan="5" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Instalment)</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Monthly)</td>
				<td colspan="5" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Instalment)</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Monthly)</td>
				<td colspan="5" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Instalment)</td>
				<td style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Monthly)</td>
				<td colspan="5" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Instalment)</td>
				<td rowspan="2" style="text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">(Instalment & Monthly)</td>
			</tr>
			<tr>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#FF00FF;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#FF00FF;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#006400;">(8%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:blue;">(6.2%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#7F7F00;">(5.5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#004242;">(5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#FF00FF;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#FF00FF;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#006400;">(8%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:blue;">(6.2%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#7F7F00;">(5.5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#004242;">(5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#FF00FF;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#FF00FF;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#006400;">(8%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:blue;">(6.2%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#7F7F00;">(5.5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:#004242;">(5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:brown;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:brown;">(10%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:brown;">(8%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:brown;">(6.2%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:brown;">(5.5%)</td>
				<td style="text-align:center;border-bottom:1px solid black;border-right:1px solid black;color:brown;">(5%)</td>
			</tr>';


$previousYear = $statement_year - 1;
$bf_date_from = $previousYear . '-12-01';
$bf_date_to = $previousYear . '-12-31';
$bf_payout_month = $previousYear . '-12';


if ($statement_year == '2022') {

	$ctr = 0;
	$sql_statement = mysql_query("SELECT * FROM statement");
	while ($result_statement = mysql_fetch_assoc($sql_statement)) {
		if ($bf_payout_month == $result_statement['month_year']) {

			$sales_statement = $result_statement['loan_out_monthly'] + $result_statement['loan_out_10percent'] + $result_statement['loan_out_8percent'] + $result_statement['loan_out_6_2percent'] + $result_statement['loan_out_5_5percent'] + $result_statement['loan_out_5percent'];

			$interest_statement = $result_statement['interest_received_monthly'] + $result_statement['interest_received_10percent'] + $result_statement['interest_received_8percent'] + $result_statement['interest_received_6_2percent'] + $result_statement['interest_received_5_5percent'] + $result_statement['interest_received_5percent'];

			$loan_out_person = $sales_statement / $result_statement['customer'];

			if ($result_statement['monthly_profit'] < 0) {
				$style_mp_bf = "color:red;";
			} else {
				$style_mp_bf = 'color:#3D91DD';
			}

			if ($result_statement['total_profit'] < 0) {
				$style_tp_bf = "color:red;";
			} else {
				$style_tp_bf = 'color:#3D91DD';
			}

			$html .= '		<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">BF Dec - ' . $previousYear . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_out_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_out_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['loan_out_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['loan_out_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['loan_out_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['loan_out_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['interest_received_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['interest_received_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['interest_received_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['interest_received_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['interest_received_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['interest_received_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['capital_in']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['return_capital']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['pay_out_interest']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($result_statement['expenses']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($result_statement['expenses_2']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_return_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_return_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['loan_return_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['loan_return_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['loan_return_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['loan_return_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['bad_debt_collected']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_mp_bf . '">' . number_format($result_statement['monthly_profit']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_tp_bf . '">' . number_format($result_statement['total_profit']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['balance_in_hand']) . '</td>
			</tr>
			<tr style="background-color:#b2ffff;">
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>SALES</b> = </td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($sales_statement) . '</td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>INTEREST</b> =</td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($interest_statement) . '</td>
				<td colspan="5" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"><b>CUSTOMER</b> = ' . number_format($result_statement['customer']) . '</td>
				<td colspan="4" style="text-align:center;border-bottom: 1px solid black;"><b>LOAN OUT/ PERSON</b> = </td>
				<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($loan_out_person) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan = "2" style="text-align:center;border-bottom: 1px solid black;"><b>CASH BALANCE</b> =</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['cash_balance']) . '</td>
			</tr>';
		} else {
		}
	}

	$ctr = 0;
	$sql_statement = mysql_query("SELECT * FROM statement_2022");
	while ($result_statement = mysql_fetch_assoc($sql_statement)) {

		$mth = $result_statement['month_year'];

		if ($mth == '2022-01') {
			$mth = 'Jan - 2022';
		} else if ($mth == '2022-02') {
			$mth = 'Feb - 2022';
		} else if ($mth == '2022-03') {
			$mth = 'March - 2022';
		} else if ($mth == '2022-04') {
			$mth = 'April - 2022';
		} else if ($mth == '2022-05') {
			$mth = 'May - 2022';
		} else if ($mth == '2022-06') {
			$mth = 'June - 2022';
		} else if ($mth == '2022-07') {
			$mth = 'July - 2022';
		} else if ($mth == '2022-08') {
			$mth = 'Aug - 2022';
		} else if ($mth == '2022-09') {
			$mth = 'Sept - 2022';
		} else if ($mth == '2022-10') {
			$mth = 'Oct - 2022';
		} else if ($mth == '2022-11') {
			$mth = 'Nov - 2022';
		} else if ($mth == '2022-12') {
			$mth = 'Dec - 2022';
		}

		$sales_statement = $result_statement['loan_out_monthly'] + $result_statement['loan_out_10percent'] + $result_statement['loan_out_8percent'] + $result_statement['loan_out_6_2percent'] + $result_statement['loan_out_5_5percent'] + $result_statement['loan_out_5percent'];

		$interest_statement = $result_statement['interest_received_monthly'] + $result_statement['interest_received_10percent'] + $result_statement['interest_received_8percent'] + $result_statement['interest_received_6_2percent'] + $result_statement['interest_received_5_5percent'] + $result_statement['interest_received_5percent'];

		$loan_out_person = $sales_statement / $result_statement['customer'];

		if ($result_statement['monthly_profit'] < 0) {
			$style_mp_bf = "color:red;";
		} else {
			$style_mp_bf = 'color:#3D91DD';
		}

		if ($result_statement['total_profit'] < 0) {
			$style_tp_bf = "color:red;";
		} else {
			$style_tp_bf = 'color:#3D91DD';
		}

		//total
		$capital_in_counter_mth += $result_statement['capital_in'];
		$return_capital_counter_mth += $result_statement['return_capital'];
		$pay_out_interest_counter_mth += $result_statement['pay_out_interest'];
		$expenses_counter_mth += $result_statement['expenses'];
		$expenses_2_counter_mth += $result_statement['expenses_2'];
		$baddebt_month_counter_mth += $result_statement['bad_debt_monthly'];
		$baddebt_amount1_counter_mth += $result_statement['bad_debt_10percent'];
		$baddebt_amount2_counter_mth += $result_statement['bad_debt_8percent'];
		$baddebt_amount3_counter_mth += $result_statement['bad_debt_6_2percent'];
		$baddebt_amount4_counter_mth += $result_statement['bad_debt_5_5percent'];
		$baddebt_amount5_counter_mth += $result_statement['bad_debt_5percent'];
		$total_bd_collected_counter_mth += $result_statement['bad_debt_collected'];
		$monthlyprofit_counter_mth += $result_statement['monthly_profit'];

		$html .= '		<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">' . $mth . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_out_monthly']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_out_10percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['loan_out_8percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['loan_out_6_2percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['loan_out_5_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['loan_out_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['interest_received_monthly']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['interest_received_10percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['interest_received_8percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['interest_received_6_2percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['interest_received_5_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['interest_received_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['capital_in']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['return_capital']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['pay_out_interest']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($result_statement['expenses']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($result_statement['expenses_2']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_return_monthly']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_return_10percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['loan_return_8percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['loan_return_6_2percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['loan_return_5_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['loan_return_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_monthly']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_10percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_8percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_6_2percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_5_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_5percent']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['bad_debt_collected']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_mp_bf . '">' . number_format($result_statement['monthly_profit']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_tp_bf . '">' . number_format($result_statement['total_profit']) . '</td>

				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['balance_in_hand']) . '</td>
			</tr>
			<tr style="background-color:#b2ffff;">
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>SALES</b> = </td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($sales_statement) . '</td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>INTEREST</b> =</td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($interest_statement) . '</td>
				<td colspan="5" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"><b>CUSTOMER</b> = ' . number_format($result_statement['customer']) . '</td>
				<td colspan="4" style="text-align:center;border-bottom: 1px solid black;"><b>LOAN OUT/ PERSON</b> = </td>
				<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($loan_out_person) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan = "2" style="text-align:center;border-bottom: 1px solid black;"><b>CASH BALANCE</b> =</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['cash_balance']) . '</td>
			</tr>';
	}




	// $mthlist = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	// for ($i = 11; $i <= 12; $i++) {

	// 	if ($i == '1') {
	// 		$i = '01';
	// 	} else if ($i == '2') {
	// 		$i = '02';
	// 	} else if ($i == '3') {
	// 		$i = '03';
	// 	} else if ($i == '4') {
	// 		$i = '04';
	// 	} else if ($i == '5') {
	// 		$i = '05';
	// 	} else if ($i == '6') {
	// 		$i = '06';
	// 	} else if ($i == '7') {
	// 		$i = '07';
	// 	} else if ($i == '8') {
	// 		$i = '08';
	// 	} else if ($i == '9') {
	// 		$i = '09';
	// 	} else if ($i == '10') {
	// 		$i = '10';
	// 	} else if ($i == '11') {
	// 		$i = '11';
	// 	} else if ($i == '12') {
	// 		$i = '12';
	// 	}

	// 	// $date_from = date("Y-".$i."-01");
	// 	// $date_to = date("Y-".$i."-31");
	// 	// $payout_month = date("Y-".$i."");

	// 	// $statement_year = date('Y');

	// 	$date_from = $statement_year . '-' . $i . '-01';
	// 	$date_to = $statement_year . '-' . $i . '-31';
	// 	$payout_month = $statement_year . '-' . $i;

	// 	$previousYear = $statement_year - 1;

	// 	$previousYearMonth = $previousYear . '-12';

	// 	$mth = date('m', strtotime($date_from));
	// 	if ($mth == '01') {
	// 		$mth = 'Jan';
	// 	} else if ($mth == '02') {
	// 		$mth = 'Feb';
	// 	} else if ($mth == '03') {
	// 		$mth = 'March';
	// 	} else if ($mth == '04') {
	// 		$mth = 'April';
	// 	} else if ($mth == '05') {
	// 		$mth = 'May';
	// 	} else if ($mth == '06') {
	// 		$mth = 'June';
	// 	} else if ($mth == '07') {
	// 		$mth = 'July';
	// 	} else if ($mth == '08') {
	// 		$mth = 'Aug';
	// 	} else if ($mth == '09') {
	// 		$mth = 'Sept';
	// 	} else if ($mth == '10') {
	// 		$mth = 'Oct';
	// 	} else if ($mth == '11') {
	// 		$mth = 'Nov';
	// 	} else if ($mth == '12') {
	// 		$mth = 'Dec';
	// 	}


	// 	$ctr = 0;


	// 	$total_loan_percent1 = 0;
	// 	$total_loan_percent2 = 0;
	// 	$total_loan_percent3 = 0;
	// 	$total_loan_percent4 = 0;
	// 	$total_loan_percent5 = 0;
	// 	$totalout = 0;
	// 	$totalcollected = 0;
	// 	$total_return_percent1 = 0;
	// 	$total_return_percent2 = 0;
	// 	$total_return_percent3 = 0;
	// 	$total_return_percent4 = 0;
	// 	$total_return_percent5 = 0;
	// 	$totalsettle = 0;
	// 	$totalbaddebt = 0;
	// 	$total_baddebt_amount1 = 0;
	// 	$total_baddebt_amount2 = 0;
	// 	$total_baddebt_amount3 = 0;
	// 	$total_baddebt_amount4 = 0;
	// 	$total_baddebt_amount5 = 0;

	// 	$sql_4 = mysql_query("SELECT
	// 							customer_loanpackage.loan_code,
	// 							customer_loanpackage.start_month,
	// 							customer_loanpackage.payout_date,
	// 							customer_loanpackage.loan_amount,
	// 							customer_loanpackage.loan_period,
	// 							customer_loanpackage.loan_total,
	// 							customer_loanpackage.loan_status,
	// 							customer_details.customercode2,
	// 							customer_details.name,
	// 							customer_details.nric,
	// 							customer_employment.company,
	// 							temporary_payment_details.monthly,
	// 							temporary_payment_details.loan_percent,
	// 							temporary_payment_details.loan_status,
	// 							temporary_payment_details.customer_loanid 
	// 						FROM
	// 							customer_loanpackage
	// 							LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 							LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 							LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
	// 						WHERE
	// 							temporary_payment_details.loan_month = '" . $payout_month . "'
	// 						AND 
	// 							temporary_payment_details.loan_code NOT IN(SELECT
	// 						customer_loanpackage.loan_code 
	// 					FROM
	// 						customer_loanpackage
	// 						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 						LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
	// 					WHERE
	// 						customer_loanpackage.loan_package = 'NEW PACKAGE'
	// 						AND(
	// 						loan_payment_details.month_receipt <'" . $payout_month . "'
	// 						)
					
	// 						AND loan_payment_details.loan_status='SETTLE' 
	// 						GROUP BY customer_loanpackage.loan_code
	// 						ORDER BY customer_loanpackage.loan_code ASC )
	// 						AND 
	// 							temporary_payment_details.loan_code NOT IN(SELECT
	// 						customer_loanpackage.loan_code 
	// 					FROM
	// 						customer_loanpackage
	// 						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 						LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
	// 					WHERE
	// 						customer_loanpackage.loan_package = 'NEW PACKAGE'
	// 						AND(
	// 						loan_payment_details.month_receipt <'" . $payout_month . "'
	// 						)
					
	// 						AND loan_payment_details.loan_status='BAD DEBT' 
	// 						GROUP BY customer_loanpackage.loan_code
	// 						ORDER BY customer_loanpackage.loan_code ASC )
	// 						GROUP BY temporary_payment_details.loan_code
	// 						ORDER BY
	// 							temporary_payment_details.loan_code ASC");


	// 	while ($result_4 = mysql_fetch_assoc($sql_4)) {

	// 		$ctr++;
	// 		if ($result_4['start_month'] == $payout_month) {
	// 			$out = $result_4['loan_amount'] - ($result_4['loan_amount'] * 0.1);
	// 		} else {
	// 			$out = '';
	// 		}

	// 		$collected = '';
	// 		$collected_remarks = '';
	// 		$settle = '';
	// 		$baddebt = '';
	// 		$baddebt_amount1 = '';
	// 		$baddebt_amount2 = '';
	// 		$baddebt_amount3 = '';
	// 		$baddebt_amount4 = '';
	// 		$baddebt_amount5 = '';
	// 		$return_percent1 = '';
	// 		$return_percent2 = '';
	// 		$return_percent3 = '';
	// 		$return_percent4 = '';
	// 		$return_percent5 = '';

	// 		$sql = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt ='" . $payout_month . "'");
	// 		while ($result = mysql_fetch_assoc($sql)) {

	// 			if ($result_4['loan_code'] == $result['receipt_no']) {

	// 				if ($result_4['loan_period'] >= 1 && $result_4['loan_period'] <= 12) {
	// 					if ($result['loan_status'] == 'SETTLE') {
	// 						$collected = '';
	// 						$collected_remarks = 'SETTLE';
	// 						$settle = $result_4['loan_percent'];
	// 						$baddebt = '';
	// 					} else if ($result['loan_status'] == 'BAD DEBT') {
	// 						$collected = '';
	// 						$collected_remarks = 'BD';
	// 						$settle = '';
	// 						$baddebt = '1';

	// 						$baddebt_amount1 = $result_4['loan_percent'];
	// 						$baddebt_amount2 = '';
	// 						$baddebt_amount3 = '';
	// 						$baddebt_amount4 = '';
	// 						$baddebt_amount5 = '';
	// 					} else {
	// 						$collected = $result_4['monthly'];
	// 						$collected_remarks = '';
	// 						$settle = '';
	// 						$baddebt = '';
	// 					}
	// 				} else if ($result_4['loan_period'] >= 13 && $result_4['loan_period'] <= 24) {
	// 					if ($result['loan_status'] == 'SETTLE') {
	// 						$collected = '';
	// 						$collected_remarks = 'SETTLE';
	// 						$settle = $result_4['loan_percent'];
	// 						$baddebt = '';
	// 					} else if ($result['loan_status'] == 'BAD DEBT') {
	// 						$collected = '';
	// 						$collected_remarks = 'BD';
	// 						$settle = '';
	// 						$baddebt = '1';

	// 						$baddebt_amount1 = '';
	// 						$baddebt_amount2 = $result_4['loan_percent'];
	// 						$baddebt_amount3 = '';
	// 						$baddebt_amount4 = '';
	// 						$baddebt_amount5 = '';
	// 					} else {
	// 						$collected = $result_4['monthly'];
	// 						$collected_remarks = '';
	// 						$settle = '';
	// 						$baddebt = '';
	// 					}
	// 				} else if ($result_4['loan_period'] >= 25 && $result_4['loan_period'] <= 36) {
	// 					if ($result['loan_status'] == 'SETTLE') {
	// 						$collected = '';
	// 						$collected_remarks = 'SETTLE';
	// 						$settle = $result_4['loan_percent'];
	// 						$baddebt = '';
	// 					} else if ($result['loan_status'] == 'BAD DEBT') {
	// 						$collected = '';
	// 						$collected_remarks = 'BD';
	// 						$settle = '';
	// 						$baddebt = '1';

	// 						$baddebt_amount1 = '';
	// 						$baddebt_amount2 = '';
	// 						$baddebt_amount3 = $result_4['loan_percent'];
	// 						$baddebt_amount4 = '';
	// 						$baddebt_amount5 = '';
	// 					} else {
	// 						$collected = $result_4['monthly'];
	// 						$collected_remarks = '';
	// 						$settle = '';
	// 						$baddebt = '';
	// 					}
	// 				} else if ($result_4['loan_period'] >= 37 && $result_4['loan_period'] <= 48) {
	// 					if ($result['loan_status'] == 'SETTLE') {
	// 						$collected = '';
	// 						$collected_remarks = 'SETTLE';
	// 						$settle = $result_4['loan_percent'];
	// 						$baddebt = '';
	// 					} else if ($result['loan_status'] == 'BAD DEBT') {
	// 						$collected = '';
	// 						$collected_remarks = 'BD';
	// 						$settle = '';
	// 						$baddebt = '1';

	// 						$baddebt_amount1 = '';
	// 						$baddebt_amount2 = '';
	// 						$baddebt_amount3 = '';
	// 						$baddebt_amount4 = $result_4['loan_percent'];
	// 						$baddebt_amount5 = '';
	// 					} else {
	// 						$collected = $result_4['monthly'];
	// 						$collected_remarks = '';
	// 						$settle = '';
	// 						$baddebt = '';
	// 					}
	// 				} else {
	// 					if ($result['loan_status'] == 'SETTLE') {
	// 						$collected = '';
	// 						$collected_remarks = 'SETTLE';
	// 						$settle = $result_4['loan_percent'];
	// 						$baddebt = '';
	// 					} else if ($result['loan_status'] == 'BAD DEBT') {
	// 						$collected = '';
	// 						$collected_remarks = 'BD';
	// 						$settle = '';
	// 						$baddebt = '1';

	// 						$baddebt_amount1 = '';
	// 						$baddebt_amount2 = '';
	// 						$baddebt_amount3 = '';
	// 						$baddebt_amount4 = '';
	// 						$baddebt_amount5 = $result_4['loan_percent'];
	// 					} else {
	// 						$collected = $result_4['monthly'];
	// 						$collected_remarks = '';
	// 						$settle = '';
	// 						$baddebt = '';
	// 					}
	// 				}
	// 			}
	// 		}

	// 		if ($result_4['start_month'] == $payout_month) {
	// 			$loan_percent1 = $result_4['loan_percent'];
	// 			$loan_percent2 = '';
	// 			$loan_percent3 = '';
	// 			$loan_percent4 = '';
	// 			$loan_percent5 = '';
	// 			$style = "color:black;";

	// 			$return_percent1 = $result_4['loan_percent'];
	// 			$return_percent2 = '';
	// 			$return_percent3 = '';
	// 			$return_percent4 = '';
	// 			$return_percent5 = '';
	// 		} else {
	// 			if ($result_4['loan_period'] >= 1 && $result_4['loan_period'] <= 12) {
	// 				$loan_percent1 = $result_4['loan_percent'];
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = '';
	// 				$style = "color:black;";

	// 				$return_percent1 = $result_4['loan_percent'];
	// 				$return_percent2 = '';
	// 				$return_percent3 = '';
	// 				$return_percent4 = '';
	// 				$return_percent5 = '';
	// 			} else if ($result_4['loan_period'] >= 13 && $result_4['loan_period'] <= 24) {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = $result_4['loan_percent'];
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = '';
	// 				$style = "color:green;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = $result_4['loan_percent'];
	// 				$return_percent3 = '';
	// 				$return_percent4 = '';
	// 				$return_percent5 = '';
	// 			} else if ($result_4['loan_period'] >= 25 && $result_4['loan_period'] <= 36) {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = $result_4['loan_percent'];
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = '';
	// 				$style = "color: #0066cc;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = '';
	// 				$return_percent3 = $result_4['loan_percent'];
	// 				$return_percent4 = '';
	// 				$return_percent5 = '';
	// 			} else if ($result_4['loan_period'] >= 37 && $result_4['loan_period'] <= 48) {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = $result_4['loan_percent'];
	// 				$loan_percent5 = '';
	// 				$style = "color:brown;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = '';
	// 				$return_percent3 = '';
	// 				$return_percent4 = $result_4['loan_percent'];
	// 				$return_percent5 = '';
	// 			} else {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = $result_4['loan_percent'];
	// 				$style = "color:#FF00FF;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = '';
	// 				$return_percent3 = '';
	// 				$return_percent4 = '';
	// 				$return_percent5 = $result_4['loan_percent'];
	// 			}
	// 		}




	// 		$total_loan_percent1 += $loan_percent1;
	// 		$total_loan_percent2 += $loan_percent2;
	// 		$total_loan_percent3 += $loan_percent3;
	// 		$total_loan_percent4 += $loan_percent4;
	// 		$total_loan_percent5 += $loan_percent5;
	// 		$totalout += $out;
	// 		$totalcollected += $collected;
	// 		$total_return_percent1 += $return_percent1;
	// 		$total_return_percent2 += $return_percent2;
	// 		$total_return_percent3 += $return_percent3;
	// 		$total_return_percent4 += $return_percent4;
	// 		$total_return_percent5 += $return_percent5;
	// 		$totalsettle += $settle;
	// 		$totalbaddebt += $baddebt;
	// 		$total_baddebt_amount1 += $baddebt_amount1;
	// 		$total_baddebt_amount2 += $baddebt_amount2;
	// 		$total_baddebt_amount3 += $baddebt_amount3;
	// 		$total_baddebt_amount4 += $baddebt_amount4;
	// 		$total_baddebt_amount5 += $baddebt_amount5;

	// 		$value1_loan += $loan_percent1;
	// 		$value2_loan += $loan_percent2;
	// 		$value3_loan += $loan_percent3;
	// 		$value4_loan += $loan_percent4;
	// 		$value5_loan += $loan_percent5;

	// 		$value7 += $baddebt_amount1;
	// 		$value8 += $baddebt_amount2;
	// 		$value9 += $baddebt_amount3;
	// 		$value10 += $baddebt_amount4;
	// 		$value11 += $baddebt_amount5;
	// 	}


	// 	$totalloan = $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5;

	// 	$after_total_loan_percent1 = $total_loan_percent1 * 0.1;
	// 	$after_total_loan_percent2 = $total_loan_percent2 * 0.08;
	// 	$after_total_loan_percent3 = $total_loan_percent3 * 0.062;
	// 	$after_total_loan_percent4 = $total_loan_percent4 * 0.055;
	// 	$after_total_loan_percent5 = $total_loan_percent5 * 0.05;

	// 	$value1 = $value1_loan * 0.1;
	// 	$value2 = $value2_loan * 0.08;
	// 	$value3 = $value3_loan * 0.062;
	// 	$value4 = $value4_loan * 0.055;
	// 	$value5 = $value5_loan * 0.05;

	// 	$totalint = $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5;

	// 	$totalamount_collected_desc = 0;
	// 	$sql_bd_collected = mysql_query("SELECT
	// 									late_interest_record.loan_code,
	// 									customer_details.customercode2,
	// 									customer_details.name,
	// 									late_interest_record.bd_date,
	// 									late_interest_record.amount as amount,
	// 									SUM(late_interest_payment_details.amount) as collected,
	// 									late_interest_payment_details.payment_date,
	// 									late_interest_record.balance as balance
	// 								FROM
	// 									late_interest_record
	// 									LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
	// 									LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
	// 								WHERE late_interest_payment_details.month_receipt ='" . $payout_month . "'
	// 								GROUP BY late_interest_record.loan_code
	// 								ORDER BY late_interest_record.bd_date ASC");

	// 	while ($result_bd_collected = mysql_fetch_assoc($sql_bd_collected)) {
	// 		$totalamount_collected_desc += $result_bd_collected['collected'];
	// 		$value12 += $result_bd_collected['collected'];
	// 	}

	// 	$sql_monthly = mysql_query("SELECT
	// 						payout_amount AS PA,
	// 						loan_code,
	// 						customercode2,
	// 						name,
	// 						company,
	// 						payout_amount,
	// 						balance AS balance,
	// 						monthly_payment_record.customer_id,
	// 						monthly_payment_record.status
	// 					FROM
	// 						monthly_payment_record
	// 						LEFT JOIN customer_details ON monthly_payment_record.customer_id = customer_details.id 
	// 						LEFT JOIN customer_employment ON customer_details.id = customer_employment.customer_id
	// 						WHERE monthly_payment_record.month ='" . $payout_month . "' and monthly_payment_record.status!='DELETED'
						
	// 						ORDER BY
	// 						monthly_payment_record.id ASC ");

	// 	$totalpayout = 0;
	// 	$totalreturn = 0;
	// 	$totalbaddebt = 0;
	// 	$payout = 0;

	// 	while ($result_monthly = mysql_fetch_assoc($sql_monthly)) {

	// 		if ($result_monthly['status'] == 'FINISHED') {
	// 			$return = $result_monthly['PA'] - $result_monthly['balance'];
	// 			// $baddebt=$result_monthly['balance'];
	// 			$baddebt = '0.00';
	// 			$cash_balance += (($result_monthly['PA'] - $result_monthly['balance']) * 0.1);
	// 		} else if ($result_monthly['status'] == 'PAID') {
	// 			$return = '0.00';
	// 			$baddebt = '0.00';
	// 			$cash_balance += 0;
	// 		} else if ($result_monthly['status'] == 'BAD DEBT') {
	// 			$return = '0.00';
	// 			//$baddebt=$result_monthly['PA'];
	// 			$baddebt = $result_monthly['balance'];
	// 			$cash_balance -= ($result_monthly['PA'] - ($result_monthly['PA'] * 0.1));
	// 		}

	// 		if ($result_monthly['status'] == 'BAD DEBT') {
	// 			$style = "style='color:#FF0000'";
	// 		} else {
	// 			$style = " ";
	// 		}

	// 		$totalpayout += $result_monthly['PA'];
	// 		$totalreturn += $return;
	// 		$totalbaddebt += $baddebt;
	// 		$payout += ($result_monthly['PA'] - ($result_monthly['PA'] * 0.1));
	// 	}
	// 	$totalexpenses = 0;
	// 	$sql_expenses = mysql_query("SELECT * FROM expenses WHERE date BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
	// 	while ($result_expenses = mysql_fetch_assoc($sql_expenses)) {
	// 		$totalexpenses += $result_expenses['amount'];
	// 		$value6 += $result_expenses['amount'];
	// 	}

	// 	$sql_thismonth_bd = mysql_query("SELECT
	// 									late_interest_record.loan_code,
	// 									customer_details.customercode2,
	// 									customer_details.name,
	// 									customer_details.nric,
	// 									customer_loanpackage.payout_date,
	// 									customer_employment.company,
	// 									late_interest_record.bd_date,
	// 									SUM(late_interest_record.amount) as amount
	// 								FROM
	// 									late_interest_record
	// 									LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
	// 									LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = late_interest_record.loan_code
	// 									LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 								WHERE late_interest_record.bd_date BETWEEN '" . $date_from . "'	AND '" . $date_to . "'
	// 								GROUP BY late_interest_record.loan_code
	// 								ORDER BY late_interest_record.bd_date ASC");

	// 	while ($result_thismonth_bd = mysql_fetch_assoc($sql_thismonth_bd)) {
	// 		$totalamount_instalment_desc += $result_thismonth_bd['amount'];
	// 	}


	// 	$closing_balance = $totalcollected + $totalsettle + $totalamount_collected_desc + $cash_balance - $totalout - $totalexpenses;


	// 	//$monthly_profit = $value1+$value2+$value3+$value4+$value5-$value6-$value7-$value8-$value9-$value10-$value11+$value12;

	// 	$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '" . $payout_month . "'");
	// 	$result_balance = mysql_fetch_assoc($sql_balance);
	// 	$interest_paid_out = $result_balance['interest_paid_out'];
	// 	$return_capital = $result_balance['return_capital'];
	// 	$capital_in = $result_balance['capital_in'];
	// 	$opening_balance_k = $result_balance['opening_balance'];
	// 	$collected_k = $result_balance['collected'];
	// 	$settle_k = $result_balance['settle'];
	// 	$baddebt_k = $result_balance['baddebt'];
	// 	$monthly_k = $result_balance['monthly'];
	// 	$payout_k = $result_balance['payout'];
	// 	$expenses_k = $result_balance['expenses'];
	// 	$expenses_k2 = $result_balance['expenses_2'];

	// 	$total_profit_q += (($totalpayout * 0.1) + $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5 - $interest_paid_out - $expenses_k - $expenses_k2 - $totalbaddebt - $total_baddebt_amount1 - $total_baddebt_amount2 - $total_baddebt_amount3 - $total_baddebt_amount4 - $total_baddebt_amount5 + $totalamount_collected_desc);

	// 	$sql_statement = mysql_query("SELECT * FROM statement_2022 WHERE month_year = '2022-08'");
	// 	$result_statement = mysql_fetch_assoc($sql_statement);
	// 	$total_profit = $result_statement['total_profit'] + $total_profit_q;

	// 	$capital_in1 += $capital_in;
	// 	$capital_in2 = $result_statement['capital_in'] + $capital_in1;


	// 	$balance_in_hand = $total_profit + $capital_in2;


	// 	if ($i == '01') {
	// 		$payout_month_cb = $statement_year . '-02';
	// 	} else if ($i == '02') {
	// 		$payout_month_cb = $statement_year . '-03';
	// 	} else if ($i == '03') {
	// 		$payout_month_cb = $statement_year . '-04';
	// 	} else if ($i == '04') {
	// 		$payout_month_cb = $statement_year . '-05';
	// 	} else if ($i == '05') {
	// 		$payout_month_cb = $statement_year . '-06';
	// 	} else if ($i == '06') {
	// 		$payout_month_cb = $statement_year . '-07';
	// 	} else if ($i == '07') {
	// 		$payout_month_cb = $statement_year . '-08';
	// 	} else if ($i == '08') {
	// 		$payout_month_cb = $statement_year . '-09';
	// 	} else if ($i == '09') {
	// 		$payout_month_cb = $statement_year . '-10';
	// 	} else if ($i == '10') {
	// 		$payout_month_cb = $statement_year . '-11';
	// 	} else if ($i == '11') {
	// 		$payout_month_cb = $statement_year . '-12';
	// 	} else if ($i == '12') {
	// 		$payout_month_cb = $statement_year . '-12';
	// 	}


	// 	$total_loan_percent1_cb = 0;
	// 	$total_loan_percent2_cb = 0;
	// 	$total_loan_percent3_cb = 0;
	// 	$total_loan_percent4_cb = 0;
	// 	$total_loan_percent5_cb = 0;

	// 	$sql_4 = mysql_query("SELECT
	// 							customer_loanpackage.loan_code,
	// 							customer_loanpackage.start_month,
	// 							customer_loanpackage.payout_date,
	// 							customer_loanpackage.loan_amount,
	// 							customer_loanpackage.loan_period,
	// 							customer_loanpackage.loan_total,
	// 							customer_loanpackage.loan_status,
	// 							customer_details.customercode2,
	// 							customer_details.name,
	// 							customer_details.nric,
	// 							customer_employment.company,
	// 							temporary_payment_details.monthly,
	// 							temporary_payment_details.loan_percent,
	// 							temporary_payment_details.loan_status,
	// 							temporary_payment_details.customer_loanid 
	// 						FROM
	// 							customer_loanpackage
	// 							LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 							LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 							LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
	// 						WHERE
	// 							temporary_payment_details.loan_month = '" . $payout_month_cb . "'
	// 						AND 
	// 							temporary_payment_details.loan_code NOT IN(SELECT
	// 						customer_loanpackage.loan_code 
	// 					FROM
	// 						customer_loanpackage
	// 						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 						LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
	// 					WHERE
	// 						customer_loanpackage.loan_package = 'NEW PACKAGE'
	// 						AND(
	// 						loan_payment_details.month_receipt <'" . $payout_month_cb . "'
	// 						)
					
	// 						AND loan_payment_details.loan_status='SETTLE' 
	// 						GROUP BY customer_loanpackage.loan_code
	// 						ORDER BY customer_loanpackage.loan_code ASC )
	// 						AND 
	// 							temporary_payment_details.loan_code NOT IN(SELECT
	// 						customer_loanpackage.loan_code 
	// 					FROM
	// 						customer_loanpackage
	// 						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
	// 						LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
	// 					WHERE
	// 						customer_loanpackage.loan_package = 'NEW PACKAGE'
	// 						AND(
	// 						loan_payment_details.month_receipt <'" . $payout_month_cb . "'
	// 						)
					
	// 						AND loan_payment_details.loan_status='BAD DEBT' 
	// 						GROUP BY customer_loanpackage.loan_code
	// 						ORDER BY customer_loanpackage.loan_code ASC )
	// 						GROUP BY temporary_payment_details.loan_code
	// 						ORDER BY
	// 							temporary_payment_details.loan_code ASC");


	// 	while ($result_4 = mysql_fetch_assoc($sql_4)) {


	// 		if ($result_4['start_month'] == $payout_month_cb) {
	// 			$out = $result_4['loan_amount'] - ($result_4['loan_amount'] * 0.1);
	// 		} else {
	// 			$out = '';
	// 		}

	// 		$collected = '';
	// 		$collected_remarks = '';
	// 		$settle = '';
	// 		$baddebt = '';
	// 		$baddebt_amount1 = '';
	// 		$baddebt_amount2 = '';
	// 		$baddebt_amount3 = '';
	// 		$baddebt_amount4 = '';
	// 		$baddebt_amount5 = '';
	// 		$return_percent1 = '';
	// 		$return_percent2 = '';
	// 		$return_percent3 = '';
	// 		$return_percent4 = '';
	// 		$return_percent5 = '';


	// 		if ($result_4['start_month'] == $payout_month_cb) {
	// 			$loan_percent1 = $result_4['loan_percent'];
	// 			$loan_percent2 = '';
	// 			$loan_percent3 = '';
	// 			$loan_percent4 = '';
	// 			$loan_percent5 = '';
	// 			$style = "color:black;";

	// 			$return_percent1 = $result_4['loan_percent'];
	// 			$return_percent2 = '';
	// 			$return_percent3 = '';
	// 			$return_percent4 = '';
	// 			$return_percent5 = '';
	// 		} else {
	// 			if ($result_4['loan_period'] >= 1 && $result_4['loan_period'] <= 12) {
	// 				$loan_percent1 = $result_4['loan_percent'];
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = '';
	// 				$style = "color:black;";

	// 				$return_percent1 = $result_4['loan_percent'];
	// 				$return_percent2 = '';
	// 				$return_percent3 = '';
	// 				$return_percent4 = '';
	// 				$return_percent5 = '';
	// 			} else if ($result_4['loan_period'] >= 13 && $result_4['loan_period'] <= 24) {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = $result_4['loan_percent'];
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = '';
	// 				$style = "color:green;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = $result_4['loan_percent'];
	// 				$return_percent3 = '';
	// 				$return_percent4 = '';
	// 				$return_percent5 = '';
	// 			} else if ($result_4['loan_period'] >= 25 && $result_4['loan_period'] <= 36) {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = $result_4['loan_percent'];
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = '';
	// 				$style = "color: #0066cc;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = '';
	// 				$return_percent3 = $result_4['loan_percent'];
	// 				$return_percent4 = '';
	// 				$return_percent5 = '';
	// 			} else if ($result_4['loan_period'] >= 37 && $result_4['loan_period'] <= 48) {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = $result_4['loan_percent'];
	// 				$loan_percent5 = '';
	// 				$style = "color:brown;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = '';
	// 				$return_percent3 = '';
	// 				$return_percent4 = $result_4['loan_percent'];
	// 				$return_percent5 = '';
	// 			} else {
	// 				$loan_percent1 = '';
	// 				$loan_percent2 = '';
	// 				$loan_percent3 = '';
	// 				$loan_percent4 = '';
	// 				$loan_percent5 = $result_4['loan_percent'];
	// 				$style = "color:#FF00FF;";

	// 				$return_percent1 = '';
	// 				$return_percent2 = '';
	// 				$return_percent3 = '';
	// 				$return_percent4 = '';
	// 				$return_percent5 = $result_4['loan_percent'];
	// 			}
	// 		}




	// 		$total_loan_percent1_cb += $loan_percent1;
	// 		$total_loan_percent2_cb += $loan_percent2;
	// 		$total_loan_percent3_cb += $loan_percent3;
	// 		$total_loan_percent4_cb += $loan_percent4;
	// 		$total_loan_percent5_cb += $loan_percent5;
	// 	}


	// 	$totalloan = $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5;

	// 	$after_total_loan_percent1_cb = $total_loan_percent1_cb * 0.1;
	// 	$after_total_loan_percent2_cb = $total_loan_percent2_cb * 0.08;
	// 	$after_total_loan_percent3_cb = $total_loan_percent3_cb * 0.062;
	// 	$after_total_loan_percent4_cb = $total_loan_percent4_cb * 0.055;
	// 	$after_total_loan_percent5_cb = $total_loan_percent5_cb * 0.05;

	// 	$sql_monthly = mysql_query("SELECT
	// 						payout_amount AS PA,
	// 						loan_code,
	// 						customercode2,
	// 						name,
	// 						company,
	// 						payout_amount,
	// 						balance AS balance,
	// 						monthly_payment_record.customer_id,
	// 						monthly_payment_record.status
	// 					FROM
	// 						monthly_payment_record
	// 						LEFT JOIN customer_details ON monthly_payment_record.customer_id = customer_details.id 
	// 						LEFT JOIN customer_employment ON customer_details.id = customer_employment.customer_id
	// 						WHERE monthly_payment_record.month ='" . $payout_month_cb . "' and monthly_payment_record.status!='DELETED'
						
	// 						ORDER BY
	// 						monthly_payment_record.id ASC ");

	// 	$totalpayout_cb = 0;


	// 	while ($result_monthly = mysql_fetch_assoc($sql_monthly)) {

	// 		$totalpayout_cb += $result_monthly['PA'];
	// 	}

	// 	$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '" . $payout_month_cb . "'");
	// 	$result_balance = mysql_fetch_assoc($sql_balance);

	// 	$capital_in_cb = $result_balance['capital_in'];



	// 	$cash_balance_a1 = $totalpayout_cb + $total_loan_percent1_cb + $total_loan_percent2_cb + $total_loan_percent3_cb + $total_loan_percent4_cb + $total_loan_percent5_cb;
	// 	$cash_balance_b1 = ($totalpayout_cb * 0.1) + $after_total_loan_percent1_cb + $after_total_loan_percent2_cb + $after_total_loan_percent3_cb + $after_total_loan_percent4_cb + $after_total_loan_percent5_cb;
	// 	$cash_balance_c1 = $capital_in_cb;

	// 	if (date("m", strtotime('-1 months')) == $i) {
	// 		$cash_balance_mth = $balance_in_hand;
	// 		$ch = $cash_balance_mth;
	// 	} else if ($i >= date("m")) {
	// 		$cash_balance_mth = $ch;
	// 		$balance_in_hand = $ch;
	// 		$total_profit = $ch;
	// 		$totalpayout = '0';
	// 		$total_loan_percent1 = '0';
	// 		$total_loan_percent2 = '0';
	// 		$total_loan_percent3 = '0';
	// 		$total_loan_percent4 = '0';
	// 		$total_loan_percent5 = '0';
	// 		$after_total_loan_percent1 = '0';
	// 		$after_total_loan_percent2 = '0';
	// 		$after_total_loan_percent3 = '0';
	// 		$after_total_loan_percent4 = '0';
	// 		$after_total_loan_percent5 = '0';
	// 		$capital_in = '0';
	// 		$interest_paid_out = '0';
	// 		$expenses_k = '0';
	// 		$expenses_k2 = '0';
	// 		$totalreturn = '0';
	// 		$total_return_percent1 = '0';
	// 		$total_return_percent2 = '0';
	// 		$total_return_percent3 = '0';
	// 		$total_return_percent4 = '0';
	// 		$total_return_percent5 = '0';
	// 		$totalbaddebt = '0';
	// 		$total_baddebt_amount1 = '0';
	// 		$total_baddebt_amount2 = '0';
	// 		$total_baddebt_amount3 = '0';
	// 		$total_baddebt_amount4 = '0';
	// 		$total_baddebt_amount5 = '0';
	// 		$totalamount_collected_desc = '0';
	// 		$ctr = '0';
	// 	} else {
	// 		$cash_balance_mth = $balance_in_hand - $cash_balance_a1 + $cash_balance_b1 + $cash_balance_c1;
	// 	}


	// 	$closing_balance = $opening_balance_k + $collected_k + $settle_k + $capital_in + $baddebt_k + $monthly_k - $payout_k - $expenses_k - $interest_paid_out - $return_capital;


	// 	$monthlyprofit = ($totalpayout * 0.1) + $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5 - $interest_paid_out - $expenses_k - $expenses_k2 - $totalbaddebt - $total_baddebt_amount1 - $total_baddebt_amount2 - $total_baddebt_amount3 - $total_baddebt_amount4 - $total_baddebt_amount5 + $totalamount_collected_desc;

	// 	if ($monthlyprofit <= '-1') {
	// 		$style_mp = "color:red;";
	// 	} else {
	// 		$style_mp = 'color:#3D91DD';
	// 	}


	// 	$html .= '<tr>
	// 			<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">' . $mth . ' - ' . $statement_year . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($totalpayout) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($total_loan_percent1) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($total_loan_percent2) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($total_loan_percent3) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($total_loan_percent4) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($total_loan_percent5) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($totalpayout * 0.1) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($after_total_loan_percent1) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($after_total_loan_percent2) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($after_total_loan_percent3) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($after_total_loan_percent4) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($after_total_loan_percent5) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($capital_in) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($interest_paid_out) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($expenses_k) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($expenses_k2) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($totalreturn) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($total_return_percent1 - $total_baddebt_amount1) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($total_return_percent2 - $total_baddebt_amount2) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($total_return_percent3 - $total_baddebt_amount3) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($total_return_percent4 - $total_baddebt_amount4) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($total_return_percent5 - $total_baddebt_amount5) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($totalbaddebt) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount1) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount2) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount3) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount4) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount5) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($totalamount_collected_desc) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_mp . '">' . number_format($monthlyprofit) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($total_profit) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($balance_in_hand) . '</td>
	// 		</tr>
	// 		<tr style="background-color:#b2ffff;">
	// 			<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>SALES</b> = </td>
	// 			<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($totalpayout + $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5) . '</td>
	// 			<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>INTEREST</b> =</td>
	// 			<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format(($totalpayout * 0.1) + $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5) . '</td>
	// 			<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"><b>CUSTOMER</b> = ' . $ctr . '</td>
	// 			<td colspan="4" style="text-align:center;border-bottom: 1px solid black;"><b>LOAN OUT/ PERSON</b> = </td>
	// 			<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format(($totalpayout + $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5) / $ctr) . '</td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
	// 			<td colspan = "2" style="text-align:center;border-bottom: 1px solid black;"><b>CASH BALANCE</b> = </td>
	// 			<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($cash_balance_mth) . '</td>
	// 		</tr>';

	// 	$total_capital += $capital_in;
	// 	$total_paid_out_interest += $interest_paid_out;
	// 	$total_expenses += $expenses_k;
	// 	$total_expenses2 += $expenses_k2;
	// 	$total_bd_collected += $totalamount_collected_desc;
	// 	$total_monthly_profit += $monthlyprofit;
	// 	$total_totalbaddebt += $totalbaddebt;
	// 	$total_total_baddebt_amount1 += $total_baddebt_amount1;
	// 	$total_total_baddebt_amount2 += $total_baddebt_amount2;
	// 	$total_total_baddebt_amount3 += $total_baddebt_amount3;
	// 	$total_total_baddebt_amount4 += $total_baddebt_amount4;
	// 	$total_total_baddebt_amount5 += $total_baddebt_amount5;
	// }
	$html .= '
		<tr>
		<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">&nbsp;</td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="5" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		</tr>
		<tr>
		<td colspan="13" style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Total Capital In:</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['capital_in'] + $capital_in_counter_mth) . '</td></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['return_capital'] + $return_capital_counter_mth) . '</td></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($pay_out_interest_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red">' . number_format($expenses_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red">' . number_format($expenses_2_counter_mth) . '</td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($baddebt_month_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($baddebt_amount1_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($baddebt_amount2_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($baddebt_amount3_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($baddebt_amount4_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($baddebt_amount5_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($total_bd_collected_counter_mth) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($monthlyprofit_counter_mth) . '</td>
		<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		</tr>
		</table>
	
		

				';
} else {
	//normal year
	$ctr = 0;
	$sql_statement = mysql_query("SELECT * FROM statement WHERE month_year = '$bf_payout_month'");
	while ($result_statement = mysql_fetch_assoc($sql_statement)) {
		if ($bf_payout_month == $result_statement['month_year']) {
			$sales_statement = $result_statement['loan_out_monthly'] + $result_statement['loan_out_10percent'] + $result_statement['loan_out_8percent'] + $result_statement['loan_out_6_2percent'] + $result_statement['loan_out_5_5percent'] + $result_statement['loan_out_5percent'];

			$interest_statement = $result_statement['interest_received_monthly'] + $result_statement['interest_received_10percent'] + $result_statement['interest_received_8percent'] + $result_statement['interest_received_6_2percent'] + $result_statement['interest_received_5_5percent'] + $result_statement['interest_received_5percent'];

			$loan_out_person = $sales_statement / $result_statement['customer'];

			if ($result_statement['monthly_profit'] < 0) {
				$style_mp_bf = "color:red;";
			} else {
				$style_mp_bf = 'color:#3D91DD';
			}
	
			if ($result_statement['total_profit'] < 0) {
				$style_tp_bf = "color:red;";
			} else {
				$style_tp_bf = 'color:#3D91DD';
			}

			$bf_balance_in_hand = $result_statement['balance_in_hand'];
			$bf_capital_in = $result_statement['capital_in'];

			$html .= '		<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">BF Dec - ' . $previousYear . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_out_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_out_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['loan_out_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['loan_out_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['loan_out_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['loan_out_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['interest_received_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['interest_received_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['interest_received_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['interest_received_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['interest_received_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['interest_received_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['capital_in']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['return_capital']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['pay_out_interest']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($result_statement['expenses']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($result_statement['expenses_2']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_return_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($result_statement['loan_return_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($result_statement['loan_return_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($result_statement['loan_return_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($result_statement['loan_return_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($result_statement['loan_return_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_monthly']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_10percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_8percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_6_2percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_5_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($result_statement['bad_debt_5percent']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['bad_debt_collected']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_mp_bf . '">' . number_format($result_statement['monthly_profit']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_tp_bf . '">' . number_format($result_statement['total_profit']) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['balance_in_hand']) . '</td>
			</tr>
			<tr style="background-color:#b2ffff;">
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>SALES</b> = </td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($sales_statement) . '</td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>INTEREST</b> =</td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($interest_statement) . '</td>
				<td colspan="5" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"><b>CUSTOMER</b> = ' . number_format($result_statement['customer']) . '</td>
				<td colspan="4" style="text-align:center;border-bottom: 1px solid black;"><b>LOAN OUT/ PERSON</b> = </td>
				<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($loan_out_person) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan = "2" style="text-align:center;border-bottom: 1px solid black;"><b>CASH BALANCE</b> =</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['cash_balance']) . '</td>
			</tr>';
		} else {
		}
	}

	$mthlist = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	for ($i = 1; $i <= count($mthlist); $i++) {

		if ($i == '1') {
			$i = '01';
		} else if ($i == '2') {
			$i = '02';
		} else if ($i == '3') {
			$i = '03';
		} else if ($i == '4') {
			$i = '04';
		} else if ($i == '5') {
			$i = '05';
		} else if ($i == '6') {
			$i = '06';
		} else if ($i == '7') {
			$i = '07';
		} else if ($i == '8') {
			$i = '08';
		} else if ($i == '9') {
			$i = '09';
		} else if ($i == '10') {
			$i = '10';
		} else if ($i == '11') {
			$i = '11';
		} else if ($i == '12') {
			$i = '12';
		}

		// $date_from = date("Y-".$i."-01");
		// $date_to = date("Y-".$i."-31");
		// $payout_month = date("Y-".$i."");

		// $statement_year = date('Y');

		$date_from = $statement_year . '-' . $i . '-01';
		$date_to = $statement_year . '-' . $i . '-31';
		$payout_month = $statement_year . '-' . $i;
		if($i + 1 < 12) {
			$next_month = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
			$next_payout_month = $statement_year . '-' . $next_month;
		}else{
			$next_payout_month = $statement_year . '-' . $i;
		}
		$previousYear = $statement_year - 1;

		$previousYearMonth = $previousYear . '-12';

		$mth = date('m', strtotime($date_from));
		if ($mth == '01') {
			$mth = 'Jan';
		} else if ($mth == '02') {
			$mth = 'Feb';
		} else if ($mth == '03') {
			$mth = 'March';
		} else if ($mth == '04') {
			$mth = 'April';
		} else if ($mth == '05') {
			$mth = 'May';
		} else if ($mth == '06') {
			$mth = 'June';
		} else if ($mth == '07') {
			$mth = 'July';
		} else if ($mth == '08') {
			$mth = 'Aug';
		} else if ($mth == '09') {
			$mth = 'Sept';
		} else if ($mth == '10') {
			$mth = 'Oct';
		} else if ($mth == '11') {
			$mth = 'Nov';
		} else if ($mth == '12') {
			$mth = 'Dec';
		}


		$ctr = 0;


		$total_loan_percent1 = 0;
		$total_loan_percent2 = 0;
		$total_loan_percent3 = 0;
		$total_loan_percent4 = 0;
		$total_loan_percent5 = 0;
		$totalout = 0;
		$totalcollected = 0;
		$total_return_percent1 = 0;
		$total_return_percent2 = 0;
		$total_return_percent3 = 0;
		$total_return_percent4 = 0;
		$total_return_percent5 = 0;
		$totalsettle = 0;
		$totalbaddebt = 0;
		$total_baddebt_amount1 = 0;
		$total_baddebt_amount2 = 0;
		$total_baddebt_amount3 = 0;
		$total_baddebt_amount4 = 0;
		$total_baddebt_amount5 = 0;

		// $sql_4 = mysql_query("SELECT
		// 						customer_loanpackage.loan_code,
		// 						customer_loanpackage.start_month,
		// 						customer_loanpackage.payout_date,
		// 						customer_loanpackage.loan_amount,
		// 						customer_loanpackage.loan_period,
		// 						customer_loanpackage.loan_total,
		// 						customer_loanpackage.loan_status,
		// 						customer_details.customercode2,
		// 						customer_details.name,
		// 						customer_details.nric,
		// 						customer_employment.company,
		// 						temporary_payment_details.monthly,
		// 						temporary_payment_details.loan_percent,
		// 						temporary_payment_details.loan_status,
		// 						temporary_payment_details.customer_loanid 
		// 					FROM
		// 						customer_loanpackage
		// 						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		// 						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		// 						LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
		// 					WHERE
		// 						temporary_payment_details.loan_month = '" . $payout_month . "'
		// 					AND 
		// 						temporary_payment_details.loan_code NOT IN(SELECT
		// 					customer_loanpackage.loan_code 
		// 				FROM
		// 					customer_loanpackage
		// 					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		// 					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		// 					LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
		// 				WHERE
		// 					customer_loanpackage.loan_package = 'NEW PACKAGE'
		// 					AND(
		// 					loan_payment_details.month_receipt <'" . $payout_month . "'
		// 					)
					
		// 					AND loan_payment_details.loan_status='SETTLE' 
		// 					GROUP BY customer_loanpackage.loan_code
		// 					ORDER BY customer_loanpackage.loan_code ASC )
		// 					AND 
		// 						temporary_payment_details.loan_code NOT IN(SELECT
		// 					customer_loanpackage.loan_code 
		// 				FROM
		// 					customer_loanpackage
		// 					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		// 					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		// 					LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
		// 				WHERE
		// 					customer_loanpackage.loan_package = 'NEW PACKAGE'
		// 					AND(
		// 					loan_payment_details.month_receipt <'" . $payout_month . "'
		// 					)
					
		// 					AND loan_payment_details.loan_status='BAD DEBT' 
		// 					GROUP BY customer_loanpackage.loan_code
		// 					ORDER BY customer_loanpackage.loan_code ASC )
		// 					GROUP BY temporary_payment_details.loan_code
		// 					ORDER BY
		// 						temporary_payment_details.loan_code ASC");

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
									temporary_payment_details.customer_loanid
								FROM
									customer_loanpackage
									LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
									LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
									LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
								WHERE
									temporary_payment_details.loan_month = '" . $payout_month . "'
								GROUP BY temporary_payment_details.loan_code
								ORDER BY
									temporary_payment_details.loan_code ASC");

		while ($result_4 = mysql_fetch_assoc($sql_4)) {
			$sql_loan_code = "SELECT
								customer_loanpackage.loan_code 
							FROM
								customer_loanpackage
								LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
								LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
								LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
							WHERE
								customer_loanpackage.loan_package = 'NEW PACKAGE'
							AND(
								loan_payment_details.month_receipt <'" . $payout_month . "'
							)
							AND (loan_payment_details.loan_status = 'SETTLE' OR loan_payment_details.loan_status = 'BAD DEBT')
							GROUP BY customer_loanpackage.loan_code
							ORDER BY customer_loanpackage.loan_code ASC";
			$query_loan_code = mysql_query($sql_loan_code);

			$arr = array();

			while ($row = mysql_fetch_assoc($query_loan_code)) {
				$arr[] = $row['loan_code'];
			}
			
			if (binary_search($arr, 0, count($arr) - 1, $result_4['loan_code']) == TRUE) {
				continue;
			}

			$ctr++;
			if ($result_4['start_month'] == $payout_month) {
				$out = $result_4['loan_amount'] - ($result_4['loan_amount'] * 0.1);
			} else {
				$out = '';
			}

			$collected = '';
			$collected_remarks = '';
			$settle = '';
			$baddebt = '';
			$baddebt_amount1 = '';
			$baddebt_amount2 = '';
			$baddebt_amount3 = '';
			$baddebt_amount4 = '';
			$baddebt_amount5 = '';
			$return_percent1 = '';
			$return_percent2 = '';
			$return_percent3 = '';
			$return_percent4 = '';
			$return_percent5 = '';

			$sql = mysql_query("SELECT * FROM loan_payment_details WHERE month_receipt ='" . $payout_month . "'");
			while ($result = mysql_fetch_assoc($sql)) {

				if ($result_4['loan_code'] == $result['receipt_no']) {

					if ($result_4['loan_period'] >= 1 && $result_4['loan_period'] <= 12) {
						if ($result['loan_status'] == 'SETTLE') {
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_4['loan_percent'];
							$baddebt = '';
						} else if ($result['loan_status'] == 'BAD DEBT') {
							$collected = '';
							$collected_remarks = 'BD';
							$settle = '';
							$baddebt = '1';

							$baddebt_amount1 = $result_4['loan_percent'];
							$baddebt_amount2 = '';
							$baddebt_amount3 = '';
							$baddebt_amount4 = '';
							$baddebt_amount5 = '';
						} else {
							$collected = $result_4['monthly'];
							$collected_remarks = '';
							$settle = '';
							$baddebt = '';
						}
					} else if ($result_4['loan_period'] >= 13 && $result_4['loan_period'] <= 24) {
						if ($result['loan_status'] == 'SETTLE') {
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_4['loan_percent'];
							$baddebt = '';
						} else if ($result['loan_status'] == 'BAD DEBT') {
							$collected = '';
							$collected_remarks = 'BD';
							$settle = '';
							$baddebt = '1';

							$baddebt_amount1 = '';
							$baddebt_amount2 = $result_4['loan_percent'];
							$baddebt_amount3 = '';
							$baddebt_amount4 = '';
							$baddebt_amount5 = '';
						} else {
							$collected = $result_4['monthly'];
							$collected_remarks = '';
							$settle = '';
							$baddebt = '';
						}
					} else if ($result_4['loan_period'] >= 25 && $result_4['loan_period'] <= 36) {
						if ($result['loan_status'] == 'SETTLE') {
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_4['loan_percent'];
							$baddebt = '';
						} else if ($result['loan_status'] == 'BAD DEBT') {
							$collected = '';
							$collected_remarks = 'BD';
							$settle = '';
							$baddebt = '1';

							$baddebt_amount1 = '';
							$baddebt_amount2 = '';
							$baddebt_amount3 = $result_4['loan_percent'];
							$baddebt_amount4 = '';
							$baddebt_amount5 = '';
						} else {
							$collected = $result_4['monthly'];
							$collected_remarks = '';
							$settle = '';
							$baddebt = '';
						}
					} else if ($result_4['loan_period'] >= 37 && $result_4['loan_period'] <= 48) {
						if ($result['loan_status'] == 'SETTLE') {
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_4['loan_percent'];
							$baddebt = '';
						} else if ($result['loan_status'] == 'BAD DEBT') {
							$collected = '';
							$collected_remarks = 'BD';
							$settle = '';
							$baddebt = '1';

							$baddebt_amount1 = '';
							$baddebt_amount2 = '';
							$baddebt_amount3 = '';
							$baddebt_amount4 = $result_4['loan_percent'];
							$baddebt_amount5 = '';
						} else {
							$collected = $result_4['monthly'];
							$collected_remarks = '';
							$settle = '';
							$baddebt = '';
						}
					} else {
						if ($result['loan_status'] == 'SETTLE') {
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_4['loan_percent'];
							$baddebt = '';
						} else if ($result['loan_status'] == 'BAD DEBT') {
							$collected = '';
							$collected_remarks = 'BD';
							$settle = '';
							$baddebt = '1';

							$baddebt_amount1 = '';
							$baddebt_amount2 = '';
							$baddebt_amount3 = '';
							$baddebt_amount4 = '';
							$baddebt_amount5 = $result_4['loan_percent'];
						} else {
							$collected = $result_4['monthly'];
							$collected_remarks = '';
							$settle = '';
							$baddebt = '';
						}
					}
				}
			}

			if ($result_4['start_month'] == $payout_month) {
				$loan_percent1 = $result_4['loan_percent'];
				$loan_percent2 = '';
				$loan_percent3 = '';
				$loan_percent4 = '';
				$loan_percent5 = '';
				$style = "color:black;";

				$return_percent1 = $result_4['loan_percent'];
				$return_percent2 = '';
				$return_percent3 = '';
				$return_percent4 = '';
				$return_percent5 = '';
			} else {
				if ($result_4['loan_period'] >= 1 && $result_4['loan_period'] <= 12) {
					$loan_percent1 = $result_4['loan_percent'];
					$loan_percent2 = '';
					$loan_percent3 = '';
					$loan_percent4 = '';
					$loan_percent5 = '';
					$style = "color:black;";

					$return_percent1 = $result_4['loan_percent'];
					$return_percent2 = '';
					$return_percent3 = '';
					$return_percent4 = '';
					$return_percent5 = '';
				} else if ($result_4['loan_period'] >= 13 && $result_4['loan_period'] <= 24) {
					$loan_percent1 = '';
					$loan_percent2 = $result_4['loan_percent'];
					$loan_percent3 = '';
					$loan_percent4 = '';
					$loan_percent5 = '';
					$style = "color:green;";

					$return_percent1 = '';
					$return_percent2 = $result_4['loan_percent'];
					$return_percent3 = '';
					$return_percent4 = '';
					$return_percent5 = '';
				} else if ($result_4['loan_period'] >= 25 && $result_4['loan_period'] <= 36) {
					$loan_percent1 = '';
					$loan_percent2 = '';
					$loan_percent3 = $result_4['loan_percent'];
					$loan_percent4 = '';
					$loan_percent5 = '';
					$style = "color: #0066cc;";

					$return_percent1 = '';
					$return_percent2 = '';
					$return_percent3 = $result_4['loan_percent'];
					$return_percent4 = '';
					$return_percent5 = '';
				} else if ($result_4['loan_period'] >= 37 && $result_4['loan_period'] <= 48) {
					$loan_percent1 = '';
					$loan_percent2 = '';
					$loan_percent3 = '';
					$loan_percent4 = $result_4['loan_percent'];
					$loan_percent5 = '';
					$style = "color:brown;";

					$return_percent1 = '';
					$return_percent2 = '';
					$return_percent3 = '';
					$return_percent4 = $result_4['loan_percent'];
					$return_percent5 = '';
				} else {
					$loan_percent1 = '';
					$loan_percent2 = '';
					$loan_percent3 = '';
					$loan_percent4 = '';
					$loan_percent5 = $result_4['loan_percent'];
					$style = "color:#FF00FF;";

					$return_percent1 = '';
					$return_percent2 = '';
					$return_percent3 = '';
					$return_percent4 = '';
					$return_percent5 = $result_4['loan_percent'];
				}
			}




			$total_loan_percent1 += $loan_percent1;
			$total_loan_percent2 += $loan_percent2;
			$total_loan_percent3 += $loan_percent3;
			$total_loan_percent4 += $loan_percent4;
			$total_loan_percent5 += $loan_percent5;
			$totalout += $out;
			$totalcollected += $collected;
			$total_return_percent1 += $return_percent1;
			$total_return_percent2 += $return_percent2;
			$total_return_percent3 += $return_percent3;
			$total_return_percent4 += $return_percent4;
			$total_return_percent5 += $return_percent5;
			$totalsettle += $settle;
			$totalbaddebt += $baddebt;
			$total_baddebt_amount1 += $baddebt_amount1;
			$total_baddebt_amount2 += $baddebt_amount2;
			$total_baddebt_amount3 += $baddebt_amount3;
			$total_baddebt_amount4 += $baddebt_amount4;
			$total_baddebt_amount5 += $baddebt_amount5;

			$value1_loan += $loan_percent1;
			$value2_loan += $loan_percent2;
			$value3_loan += $loan_percent3;
			$value4_loan += $loan_percent4;
			$value5_loan += $loan_percent5;

			$value7 += $baddebt_amount1;
			$value8 += $baddebt_amount2;
			$value9 += $baddebt_amount3;
			$value10 += $baddebt_amount4;
			$value11 += $baddebt_amount5;
		}


		$totalloan = $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5;

		$after_total_loan_percent1 = $total_loan_percent1 * 0.1;
		$after_total_loan_percent2 = $total_loan_percent2 * 0.08;
		$after_total_loan_percent3 = $total_loan_percent3 * 0.062;
		$after_total_loan_percent4 = $total_loan_percent4 * 0.055;
		$after_total_loan_percent5 = $total_loan_percent5 * 0.05;

		$value1 = $value1_loan * 0.1;
		$value2 = $value2_loan * 0.08;
		$value3 = $value3_loan * 0.062;
		$value4 = $value4_loan * 0.055;
		$value5 = $value5_loan * 0.05;

		$totalint = $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5;

		$totalamount_collected_desc = 0;
		// $sql_bd_collected = mysql_query("SELECT
		// 								late_interest_record.loan_code,
		// 								customer_details.customercode2,
		// 								customer_details.name,
		// 								late_interest_record.bd_date,
		// 								late_interest_record.amount as amount,
		// 								SUM(late_interest_payment_details.amount) as collected,
		// 								late_interest_payment_details.payment_date,
		// 								late_interest_record.balance as balance
		// 							FROM
		// 								late_interest_record
		// 								LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
		// 								LEFT JOIN late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
		// 							WHERE late_interest_payment_details.month_receipt ='" . $payout_month . "'
		// 							GROUP BY late_interest_record.loan_code
		// 							ORDER BY late_interest_record.bd_date ASC");

		// while ($result_bd_collected = mysql_fetch_assoc($sql_bd_collected)) {
		// 	$totalamount_collected_desc += $result_bd_collected['collected'];
		// 	$value12 += $result_bd_collected['collected'];
		// }

		$login_branch = $_SESSION["login_branch"];

		// if ($login_branch == 'ANSENG') {
		// 	$sql_get_all_bd = "SELECT
		// 						late_interest_record.id,
		// 						late_interest_record.loan_code,
		// 						customer_details.customercode2,
		// 						customer_details.name,
		// 						late_interest_record.bd_date,
		// 						SUM(late_interest_record.amount) as amount,
		// 						SUM(late_interest_record.balance) as balance,
		// 						late_interest_record.bd_from
		// 					FROM
		// 						late_interest_record
		// 					LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
		// 					WHERE late_interest_record.month_receipt < '".$payout_month."' AND status != 'HIDDEN'
		// 					GROUP BY late_interest_record.loan_code
		// 					ORDER BY late_interest_record.bd_date ASC";
		// 	$query = mysql_query($sql_get_all_bd);

		// 	$totalamount_collected_desc = 0;

		// 	while ($bd = mysql_fetch_assoc($query)) {
		// 		$sql_bd_collected = "SELECT SUM(amount) AS collected, month_receipt FROM late_interest_payment_details WHERE lid = '" . $bd['id'] . "' AND month_receipt = '" . $payout_month . "'";
		// 		$query = mysql_query($sql_bd_collected);
		// 		while ($row = mysql_fetch_assoc($query)) {
		// 			$totalamount_collected_desc += round($row['collected'], 2);
		// 			$value12 += $row['collected'];
		// 		}
		// 	}
		// } else {
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
								WHERE late_interest_payment_details.month_receipt='" . $payout_month . "'
								GROUP BY late_interest_record.loan_code
								ORDER BY late_interest_record.bd_date ASC");

			while ($result_bd_collected = mysql_fetch_assoc($sql_bd_collected)) {
				if ($login_branch == 'ANSENG' && $payout_month == '2023-04') {
					$totalamount_collected_desc += 0;
					$value12 += 0;
				} else {
				$totalamount_collected_desc += $result_bd_collected['collected'];
				$value12 += $result_bd_collected['collected'];
			}
		}
		// }

		$sql_monthly = mysql_query("SELECT
							payout_amount AS PA,
							loan_code,
							customercode2,
							name,
							company,
							payout_amount,
							balance AS balance,
							monthly_payment_record.customer_id,
							monthly_payment_record.status
						FROM
							monthly_payment_record
							LEFT JOIN customer_details ON monthly_payment_record.customer_id = customer_details.id 
							LEFT JOIN customer_employment ON customer_details.id = customer_employment.customer_id
							WHERE monthly_payment_record.month ='" . $payout_month . "' and monthly_payment_record.status!='DELETED'
						
							ORDER BY
							monthly_payment_record.id ASC ");

		$totalpayout = 0;
		$totalreturn = 0;
		$totalbaddebt = 0;
		$payout = 0;

		while ($result_monthly = mysql_fetch_assoc($sql_monthly)) {

			if ($result_monthly['status'] == 'FINISHED') {
				$return = $result_monthly['PA'] - $result_monthly['balance'];
				// $baddebt=$result_monthly['balance'];
				$baddebt = '0.00';
				$cash_balance += (($result_monthly['PA'] - $result_monthly['balance']) * 0.1);
			} else if ($result_monthly['status'] == 'PAID') {
				$return = '0.00';
				$baddebt = '0.00';
				$cash_balance += 0;
			} else if ($result_monthly['status'] == 'BAD DEBT') {
				$return = '0.00';
				//$baddebt=$result_monthly['PA'];
				$baddebt = $result_monthly['balance'];
				$cash_balance -= ($result_monthly['PA'] - ($result_monthly['PA'] * 0.1));
			}

			if ($result_monthly['status'] == 'BAD DEBT') {
				$style = "style='color:#FF0000'";
			} else {
				$style = " ";
			}

			$totalpayout += $result_monthly['PA'];
			$totalreturn += $return;
			$totalbaddebt += $baddebt;
			$payout += ($result_monthly['PA'] - ($result_monthly['PA'] * 0.1));
		}
		$totalexpenses = 0;
		$sql_expenses = mysql_query("SELECT * FROM expenses WHERE date BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
		while ($result_expenses = mysql_fetch_assoc($sql_expenses)) {
			$totalexpenses += $result_expenses['amount'];
			$value6 += $result_expenses['amount'];
		}

		$sql_thismonth_bd = mysql_query("SELECT
										late_interest_record.loan_code,
										customer_details.customercode2,
										customer_details.name,
										customer_details.nric,
										customer_loanpackage.payout_date,
										customer_employment.company,
										late_interest_record.bd_date,
										SUM(late_interest_record.amount) as amount
									FROM
										late_interest_record
										LEFT JOIN customer_details ON late_interest_record.customer_id = customer_details.id
										LEFT JOIN customer_loanpackage ON customer_loanpackage.loan_code = late_interest_record.loan_code
										LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
									WHERE late_interest_record.bd_date BETWEEN '" . $date_from . "'	AND '" . $date_to . "'
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");

		while ($result_thismonth_bd = mysql_fetch_assoc($sql_thismonth_bd)) {
			$totalamount_instalment_desc += $result_thismonth_bd['amount'];
		}


		$closing_balance = $totalcollected + $totalsettle + $totalamount_collected_desc + $cash_balance - $totalout - $totalexpenses;


		//$monthly_profit = $value1+$value2+$value3+$value4+$value5-$value6-$value7-$value8-$value9-$value10-$value11+$value12;

		$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '" . $payout_month . "'");
		$result_balance = mysql_fetch_assoc($sql_balance);
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

		$total_profit_q += (($totalpayout * 0.1) + $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5 - $interest_paid_out - $expenses_k - $expenses_k2 - $totalbaddebt - $total_baddebt_amount1 - $total_baddebt_amount2 - $total_baddebt_amount3 - $total_baddebt_amount4 - $total_baddebt_amount5 + $totalamount_collected_desc);

		$sql_statement = mysql_query("SELECT * FROM statement WHERE month_year = '" . $previousYearMonth . "'");
		$result_statement = mysql_fetch_assoc($sql_statement);
		$total_profit = $result_statement['total_profit'] + $total_profit_q;

		$capital_in1 += $capital_in;
		$capital_in2 = $result_statement['capital_in'] + $capital_in1;



		$balance_in_hand = $total_profit + $capital_in2;


		if ($i == '01') {
			$payout_month_cb = $statement_year . '-02';
		} else if ($i == '02') {
			$payout_month_cb = $statement_year . '-03';
		} else if ($i == '03') {
			$payout_month_cb = $statement_year . '-04';
		} else if ($i == '04') {
			$payout_month_cb = $statement_year . '-05';
		} else if ($i == '05') {
			$payout_month_cb = $statement_year . '-06';
		} else if ($i == '06') {
			$payout_month_cb = $statement_year . '-07';
		} else if ($i == '07') {
			$payout_month_cb = $statement_year . '-08';
		} else if ($i == '08') {
			$payout_month_cb = $statement_year . '-09';
		} else if ($i == '09') {
			$payout_month_cb = $statement_year . '-10';
		} else if ($i == '10') {
			$payout_month_cb = $statement_year . '-11';
		} else if ($i == '11') {
			$payout_month_cb = $statement_year . '-12';
		} else if ($i == '12') {
			$payout_month_cb = $statement_year . '-12';
		}


		$total_loan_percent1_cb = 0;
		$total_loan_percent2_cb = 0;
		$total_loan_percent3_cb = 0;
		$total_loan_percent4_cb = 0;
		$total_loan_percent5_cb = 0;

		// $sql_4 = mysql_query("SELECT
		// 						customer_loanpackage.loan_code,
		// 						customer_loanpackage.start_month,
		// 						customer_loanpackage.payout_date,
		// 						customer_loanpackage.loan_amount,
		// 						customer_loanpackage.loan_period,
		// 						customer_loanpackage.loan_total,
		// 						customer_loanpackage.loan_status,
		// 						customer_details.customercode2,
		// 						customer_details.name,
		// 						customer_details.nric,
		// 						customer_employment.company,
		// 						temporary_payment_details.monthly,
		// 						temporary_payment_details.loan_percent,
		// 						temporary_payment_details.loan_status,
		// 						temporary_payment_details.customer_loanid 
		// 					FROM
		// 						customer_loanpackage
		// 						LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		// 						LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		// 						LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
		// 					WHERE
		// 						temporary_payment_details.loan_month = '" . $payout_month_cb . "'
		// 					AND 
		// 						temporary_payment_details.loan_code NOT IN(SELECT
		// 					customer_loanpackage.loan_code 
		// 				FROM
		// 					customer_loanpackage
		// 					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		// 					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		// 					LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
		// 				WHERE
		// 					customer_loanpackage.loan_package = 'NEW PACKAGE'
		// 					AND(
		// 					loan_payment_details.month_receipt <'" . $payout_month_cb . "'
		// 					)
					
		// 					AND loan_payment_details.loan_status='SETTLE' 
		// 					GROUP BY customer_loanpackage.loan_code
		// 					ORDER BY customer_loanpackage.loan_code ASC )
		// 					AND 
		// 						temporary_payment_details.loan_code NOT IN(SELECT
		// 					customer_loanpackage.loan_code 
		// 				FROM
		// 					customer_loanpackage
		// 					LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
		// 					LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
		// 					LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
		// 				WHERE
		// 					customer_loanpackage.loan_package = 'NEW PACKAGE'
		// 					AND(
		// 					loan_payment_details.month_receipt <'" . $payout_month_cb . "'
		// 					)
					
		// 					AND loan_payment_details.loan_status='BAD DEBT' 
		// 					GROUP BY customer_loanpackage.loan_code
		// 					ORDER BY customer_loanpackage.loan_code ASC )
		// 					GROUP BY temporary_payment_details.loan_code
		// 					ORDER BY
		// 						temporary_payment_details.loan_code ASC");

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
									temporary_payment_details.customer_loanid
								FROM
									customer_loanpackage
									LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
									LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
									LEFT JOIN temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
								WHERE
									temporary_payment_details.loan_month = '" . $payout_month_cb . "'
								GROUP BY temporary_payment_details.loan_code
								ORDER BY
									temporary_payment_details.loan_code ASC");

		while ($result_4 = mysql_fetch_assoc($sql_4)) {
			$sql_loan_code = "SELECT
								customer_loanpackage.loan_code 
							FROM
								customer_loanpackage
								LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
								LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
								LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
							WHERE
								customer_loanpackage.loan_package = 'NEW PACKAGE'
							AND(
								loan_payment_details.month_receipt <'" . $payout_month_cb . "'
							)
							AND (loan_payment_details.loan_status = 'SETTLE' OR loan_payment_details.loan_status = 'BAD DEBT')
							GROUP BY customer_loanpackage.loan_code
							ORDER BY customer_loanpackage.loan_code ASC";
			$query_loan_code = mysql_query($sql_loan_code);

			$arr = array();

			while ($row = mysql_fetch_assoc($query_loan_code)) {
				$arr[] = $row['loan_code'];
			}
			
			if (binary_search($arr, 0, count($arr) - 1, $result_4['loan_code']) == TRUE) {
				continue;
			}

			if ($result_4['start_month'] == $payout_month_cb) {
				$out = $result_4['loan_amount'] - ($result_4['loan_amount'] * 0.1);
			} else {
				$out = '';
			}

			$collected = '';
			$collected_remarks = '';
			$settle = '';
			$baddebt = '';
			$baddebt_amount1 = '';
			$baddebt_amount2 = '';
			$baddebt_amount3 = '';
			$baddebt_amount4 = '';
			$baddebt_amount5 = '';
			$return_percent1 = '';
			$return_percent2 = '';
			$return_percent3 = '';
			$return_percent4 = '';
			$return_percent5 = '';


			if ($result_4['start_month'] == $payout_month_cb) {
				$loan_percent1 = $result_4['loan_percent'];
				$loan_percent2 = '';
				$loan_percent3 = '';
				$loan_percent4 = '';
				$loan_percent5 = '';
				$style = "color:black;";

				$return_percent1 = $result_4['loan_percent'];
				$return_percent2 = '';
				$return_percent3 = '';
				$return_percent4 = '';
				$return_percent5 = '';
			} else {
				if ($result_4['loan_period'] >= 1 && $result_4['loan_period'] <= 12) {
					$loan_percent1 = $result_4['loan_percent'];
					$loan_percent2 = '';
					$loan_percent3 = '';
					$loan_percent4 = '';
					$loan_percent5 = '';
					$style = "color:black;";

					$return_percent1 = $result_4['loan_percent'];
					$return_percent2 = '';
					$return_percent3 = '';
					$return_percent4 = '';
					$return_percent5 = '';
				} else if ($result_4['loan_period'] >= 13 && $result_4['loan_period'] <= 24) {
					$loan_percent1 = '';
					$loan_percent2 = $result_4['loan_percent'];
					$loan_percent3 = '';
					$loan_percent4 = '';
					$loan_percent5 = '';
					$style = "color:green;";

					$return_percent1 = '';
					$return_percent2 = $result_4['loan_percent'];
					$return_percent3 = '';
					$return_percent4 = '';
					$return_percent5 = '';
				} else if ($result_4['loan_period'] >= 25 && $result_4['loan_period'] <= 36) {
					$loan_percent1 = '';
					$loan_percent2 = '';
					$loan_percent3 = $result_4['loan_percent'];
					$loan_percent4 = '';
					$loan_percent5 = '';
					$style = "color: #0066cc;";

					$return_percent1 = '';
					$return_percent2 = '';
					$return_percent3 = $result_4['loan_percent'];
					$return_percent4 = '';
					$return_percent5 = '';
				} else if ($result_4['loan_period'] >= 37 && $result_4['loan_period'] <= 48) {
					$loan_percent1 = '';
					$loan_percent2 = '';
					$loan_percent3 = '';
					$loan_percent4 = $result_4['loan_percent'];
					$loan_percent5 = '';
					$style = "color:brown;";

					$return_percent1 = '';
					$return_percent2 = '';
					$return_percent3 = '';
					$return_percent4 = $result_4['loan_percent'];
					$return_percent5 = '';
				} else {
					$loan_percent1 = '';
					$loan_percent2 = '';
					$loan_percent3 = '';
					$loan_percent4 = '';
					$loan_percent5 = $result_4['loan_percent'];
					$style = "color:#FF00FF;";

					$return_percent1 = '';
					$return_percent2 = '';
					$return_percent3 = '';
					$return_percent4 = '';
					$return_percent5 = $result_4['loan_percent'];
				}
			}




			$total_loan_percent1_cb += $loan_percent1;
			$total_loan_percent2_cb += $loan_percent2;
			$total_loan_percent3_cb += $loan_percent3;
			$total_loan_percent4_cb += $loan_percent4;
			$total_loan_percent5_cb += $loan_percent5;
		}


		$totalloan = $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5;

		$after_total_loan_percent1_cb = $total_loan_percent1_cb * 0.1;
		$after_total_loan_percent2_cb = $total_loan_percent2_cb * 0.08;
		$after_total_loan_percent3_cb = $total_loan_percent3_cb * 0.062;
		$after_total_loan_percent4_cb = $total_loan_percent4_cb * 0.055;
		$after_total_loan_percent5_cb = $total_loan_percent5_cb * 0.05;

		$sql_monthly = mysql_query("SELECT
							payout_amount AS PA,
							loan_code,
							customercode2,
							name,
							company,
							payout_amount,
							balance AS balance,
							monthly_payment_record.customer_id,
							monthly_payment_record.status
						FROM
							monthly_payment_record
							LEFT JOIN customer_details ON monthly_payment_record.customer_id = customer_details.id 
							LEFT JOIN customer_employment ON customer_details.id = customer_employment.customer_id
							WHERE monthly_payment_record.month ='" . $payout_month_cb . "' and monthly_payment_record.status!='DELETED'
						
							ORDER BY
							monthly_payment_record.id ASC ");

		$totalpayout_cb = 0;


		while ($result_monthly = mysql_fetch_assoc($sql_monthly)) {

			$totalpayout_cb += $result_monthly['PA'];
		}

		$sql_balance = mysql_query("SELECT * FROM instalment_balance WHERE pay_month = '" . $payout_month_cb . "'");
		$result_balance = mysql_fetch_assoc($sql_balance);

		$capital_in_cb = $result_balance['capital_in'];



		$cash_balance_a1 = $totalpayout_cb + $total_loan_percent1_cb + $total_loan_percent2_cb + $total_loan_percent3_cb + $total_loan_percent4_cb + $total_loan_percent5_cb;
		$cash_balance_b1 = ($totalpayout_cb * 0.1) + $after_total_loan_percent1_cb + $after_total_loan_percent2_cb + $after_total_loan_percent3_cb + $after_total_loan_percent4_cb + $after_total_loan_percent5_cb;
		$cash_balance_c1 = $capital_in_cb;

		if (date("Y-m", strtotime('-1 months')) == $statement_year . '-' . $i) {
			$cash_balance_mth = $balance_in_hand;
			$ch = $cash_balance_mth;
		} else if ($statement_year . '-' . $i >= date("Y-m")) {
			$cash_balance_mth = $ch;
			$balance_in_hand = $ch;
			$total_profit = $ch;
			$totalpayout = '0';
			$total_loan_percent1 = '0';
			$total_loan_percent2 = '0';
			$total_loan_percent3 = '0';
			$total_loan_percent4 = '0';
			$total_loan_percent5 = '0';
			$after_total_loan_percent1 = '0';
			$after_total_loan_percent2 = '0';
			$after_total_loan_percent3 = '0';
			$after_total_loan_percent4 = '0';
			$after_total_loan_percent5 = '0';
			$capital_in = '0';
			$interest_paid_out = '0';
			$expenses_k = '0';
			$expenses_k2 = '0';
			$totalreturn = '0';
			$total_return_percent1 = '0';
			$total_return_percent2 = '0';
			$total_return_percent3 = '0';
			$total_return_percent4 = '0';
			$total_return_percent5 = '0';
			$totalbaddebt = '0';
			$total_baddebt_amount1 = '0';
			$total_baddebt_amount2 = '0';
			$total_baddebt_amount3 = '0';
			$total_baddebt_amount4 = '0';
			$total_baddebt_amount5 = '0';
			$totalamount_collected_desc = '0';
			$ctr = '0';
		} else {
			$cash_balance_mth = $balance_in_hand - $cash_balance_a1 + $cash_balance_b1 + $cash_balance_c1;
		}

		if($i + 1 <= 12) {
			$next_month = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
			$next_payout_month_capital = $statement_year . '-' . $next_month;
		}
		$next_month_return = mysql_query("SELECT return_capital FROM instalment_balance WHERE pay_month = '" . $next_payout_month_capital . "'");
		$result_next_month_return = mysql_fetch_assoc($next_month_return);
		
		if ($result_next_month_return['return_capital'] == null){
			$next_month_return_capital = 0;
		}else {
			$next_month_return_capital = $result_next_month_return['return_capital'];
		}

		$closing_balance = $opening_balance_k + $collected_k + $settle_k + $capital_in + $baddebt_k + $monthly_k - $payout_k - $expenses_k - $interest_paid_out - $return_capital;


		$monthlyprofit = ($totalpayout * 0.1) + $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5 - $interest_paid_out - $expenses_k - $expenses_k2 - $totalbaddebt - $total_baddebt_amount1 - $total_baddebt_amount2 - $total_baddebt_amount3 - $total_baddebt_amount4 - $total_baddebt_amount5 + $totalamount_collected_desc;

		if ($monthlyprofit < 0) {
			$style_mp = "color:red;";
		} else {
			$style_mp = 'color:#3D91DD';
		}

		if ($total_profit < 0) {
			$style_tp = "color:red;";
		} else {
			$style_tp = 'color:#3D91DD';
		}

		$total_return_capital += $return_capital;

		if ($i == 12) {
			$cash_balance_display = number_format($cash_balance_mth - $total_return_capital);
		} else {
			$cash_balance_display = number_format($cash_balance_mth - $total_return_capital - $next_month_return_capital);
		}
		$html .= '<tr>
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">' . $mth . ' - ' . $statement_year . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($totalpayout) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($total_loan_percent1) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($total_loan_percent2) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($total_loan_percent3) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($total_loan_percent4) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($total_loan_percent5) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($totalpayout * 0.1) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($after_total_loan_percent1) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($after_total_loan_percent2) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($after_total_loan_percent3) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($after_total_loan_percent4) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($after_total_loan_percent5) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($capital_in) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($return_capital) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($interest_paid_out) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($expenses_k) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red;">' . number_format($expenses_k2) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($totalreturn) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#FF00FF;">' . number_format($total_return_percent1 - $total_baddebt_amount1) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#006400;">' . number_format($total_return_percent2 - $total_baddebt_amount2) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:blue;">' . number_format($total_return_percent3 - $total_baddebt_amount3) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#7F7F00;">' . number_format($total_return_percent4 - $total_baddebt_amount4) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:#004242;">' . number_format($total_return_percent5 - $total_baddebt_amount5) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($totalbaddebt) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount1) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount2) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount3) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount4) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_baddebt_amount5) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($totalamount_collected_desc) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_mp . '">' . number_format($monthlyprofit) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;' . $style_tp . '">' . number_format($total_profit) . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($balance_in_hand - $total_return_capital) . '</td>
			</tr>
			<tr style="background-color:#b2ffff;">
				<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>SALES</b> = </td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($totalpayout + $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5) . '</td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid black;"><b>INTEREST</b> =</td>
				<td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format(($totalpayout * 0.1) + $after_total_loan_percent1 + $after_total_loan_percent2 + $after_total_loan_percent3 + $after_total_loan_percent4 + $after_total_loan_percent5) . '</td>
				<td colspan="5" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"><b>CUSTOMER</b> = ' . $ctr . '</td>
				<td colspan="4" style="text-align:center;border-bottom: 1px solid black;"><b>LOAN OUT/ PERSON</b> = </td>
				<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . ($ctr > 0 ? number_format(($totalpayout + $total_loan_percent1 + $total_loan_percent2 + $total_loan_percent3 + $total_loan_percent4 + $total_loan_percent5) / $ctr) : '0') . '</td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
				<td colspan = "2" style="text-align:center;border-bottom: 1px solid black;"><b>CASH BALANCE</b> = </td>
				<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">'
					. $cash_balance_display .
				'</td>
			</tr>';

		$total_capital += $capital_in;
		// $total_return_capital += $return_capital;
		$total_paid_out_interest += $interest_paid_out;
		$total_expenses += $expenses_k;
		$total_expenses2 += $expenses_k2;
		$total_bd_collected += $totalamount_collected_desc;
		$total_monthly_profit += $monthlyprofit;
		$total_totalbaddebt += $totalbaddebt;
		$total_total_baddebt_amount1 += $total_baddebt_amount1;
		$total_total_baddebt_amount2 += $total_baddebt_amount2;
		$total_total_baddebt_amount3 += $total_baddebt_amount3;
		$total_total_baddebt_amount4 += $total_baddebt_amount4;
		$total_total_baddebt_amount5 += $total_baddebt_amount5;
	}
	$html .= '
		<tr>
		<td style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">&nbsp;</td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="5" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		</tr>
		<tr>
		<td colspan="13" style="text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom: 1px solid black;">Total Capital In:</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($result_statement['capital_in'] + $total_capital) . '</td></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($result_statement['return_capital'] + $total_return_capital) . '</td></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black; color: red;">' . number_format($total_paid_out_interest) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red">' . number_format($total_expenses) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:red">' . number_format($total_expenses2) . '</td>
		<td colspan="6" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_totalbaddebt) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_total_baddebt_amount1) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_total_baddebt_amount2) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_total_baddebt_amount3) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_total_baddebt_amount4) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;color:brown;">' . number_format($total_total_baddebt_amount5) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($total_bd_collected) . '</td>
		<td style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;">' . number_format($total_monthly_profit) . '</td>
		<td colspan="2" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;"></td>
		</tr>
		</table>
	
		

				';
}

$mpdf->WriteHTML($html);
$mpdf->WriteHTML($footer);
$mpdf->Output();
