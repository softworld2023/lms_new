<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$date = $_GET['date'];

$sql = mysql_query("SELECT * FROM expenses WHERE month_receipt = '".$date."' AND display_status = 'SHOW' ORDER BY date ASC, id ASC");

$branch_name = '';

switch ($_SESSION['login_branch']) {
	case 'MAJUSAMA':
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

		$positive_total = 0;
		$negative_total = 0;

		while($result_1 = mysql_fetch_assoc($sql)){
			$total += $result_1['amount'];
			$font_color = '';

			if ($result_1['amount']<=0) {
				if ($negative_total == 0) {	// check if the amount is the first negative amount
					$html .= '<tr '.$style.'>
								<td colspan="3" style="text-align: right; border-left: 1px solid; border-right: 1px solid; border-bottom: 1px solid;"><b>Total:&nbsp;&nbsp;&nbsp;</b></td>
								<td style="text-align: right; border-right: 1px solid; border-bottom: 1px solid;"><b>' . $positive_total . '</b></td>
							</tr>';
				}
				$style = "style='background-color:lightblue;'";
				$font_color = "color: red;";
				$negative_total += $result_1['amount'];
			}
			else
			{
				$style='';
				$positive_total += $result_1['amount'];
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
		<td style = "text-align:center;border-left:1px solid;border-right:1px solid;border-bottom:1px solid; ' . $font_color . '">'.date("d/m/Y", strtotime($result_1['date'])).'</td>
		<td style = "text-align:center;border-right:1px solid;border-bottom:1px solid;' . $font_color .'"></td>
		<td width="40%" style="text-align:left;border-right:1px solid;border-bottom:1px solid;' . $font_color . '">'.$description.'</td>
		<td style = "text-align:right;border-right:1px solid;border-bottom:1px solid;' . $font_color . '">'.$result_1['amount'].'</td>
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