<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$date = $_GET['date'];

$branch_name = '';

switch ($_SESSION['login_branch']) {
	case 'MAJUSAMA':
		$branch_name = 'MJ MAJUSAMA SDN BHD';
		break;
	case 'MAJUSAMA2':
		$branch_name = 'MJ MAJUSAMA SDN BHD';
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

// $sql = mysql_query("SELECT * FROM expenses_accountant WHERE month_receipt = '".$date."' ORDER BY date ASC");
$sql = mysql_query("SELECT
						expenses.*, expenses_accountant.id
					FROM
						expenses_accountant
					JOIN expenses ON TRIM(expenses.expenses_type) LIKE CONCAT(TRIM(expenses_accountant.expenses_type),'%') 
					AND TRIM(expenses.expenses_details) LIKE CONCAT(TRIM(expenses_accountant.expenses_details),'%')
					AND expenses.created_date = expenses_accountant.created_date
					AND expenses.month_receipt = expenses_accountant.month_receipt
					WHERE expenses.display_status = 'SHOW' AND expenses_accountant.display_status = 'SHOW' 
					AND expenses_accountant.branch_id = '" . $_SESSION['login_branchid'] . "'
					AND expenses.month_receipt = '".$date."'
					GROUP BY expenses.id
					ORDER BY expenses.date, expenses.id ASC
					");

$mpdf = new mPDF('','A4','','',20,20,10,20,20,20,'P');
$html.='<div style="text-align:left;font-size:10px;">
			<b><span style="font-size:22px;">' . $branch_name . '</span></b>
			<br><br>
			<span style="font-size:16px;">Month : '.date("M Y", strtotime($date)).'</span>
		</div>
		<br><br>

		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
		<tr>
		<td width="15%" style = "text-align:center;border:1px solid;"><b>Date</b></td>
		<td width="10%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"><b>Voucher No.</b></td>
		<td width="50%" style="text-align:center;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"><b>Description</b></td>
		<td width="15%" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"><b>Amount (RM) </b></td>
		</tr>';

		while($result_1 = mysql_fetch_assoc($sql)){
			$total += $result_1['amount'];

			if ($result_1['amount']<=0) {
			$style = "style='background-color:lightblue'";
			
				}
				else
				{
					$style='';
				}


			$expenses_details = $result_1['expenses_type']." ".$result_1['expenses_details'];
			$no_details1 = strlen($result_1['expenses_type']);
			$no_details2 = strlen($result_1['expenses_details']);
			$no_details3 = strlen($expenses_details);


			if($no_details1 >=15)
			{
				$description = $result_1['expenses_type'].'<br>'.$result_1['expenses_details'];
			}
			else if($no_details1 >=20 && $no_details2 >=20)
			{
				$description = $result_1['expenses_type'].'<br>'.$result_1['expenses_details'];
			}
			else
			{
				$description = $expenses_details;
			}
$html.='
		<tr '.$style.'>
		<td style = "text-align:center;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;">'.date("d/m/Y", strtotime($result_1['date'])).'</td>
		<td style = "text-align:center;border-right:1px solid;border-bottom:1px solid;"></td>
		<td width="40%" style="text-align:left;border-right:1px solid;border-bottom:1px solid;">'.$description.'</td>
		<td style = "text-align:right;border-right:1px solid;border-bottom:1px solid;">'.$result_1['amount'].'</td>
		</tr>';

		}

$html.='<tr>
		<td style = "border-left:1px solid;border-right:1px solid;border-bottom:1px solid;"></td>
		<td style = "border-right:1px solid;border-bottom:1px solid;"></td>
		<td width="40%" style="text-align:right;border-right:1px solid;border-bottom:1px solid;"><b>Total:</b>&nbsp;&nbsp;&nbsp;</td>
		<td style = "text-align:right;border-right:1px solid;border-bottom:1px solid;">'.number_format($total,2).'</td>
		</tr></table>
		</div>

		<br>

		';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>