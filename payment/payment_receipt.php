<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$loan_lejar_id = $_GET['id'];

$sql_1 = mysql_query("SELECT * FROM loan_lejar_details WHERE id = '".$loan_lejar_id."'");
$result_1 = mysql_fetch_assoc($sql_1);

$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$result_1['customer_loanid']."'");
$result_2 = mysql_fetch_assoc($sql_2);

$sql_3 = mysql_query("SELECT * FROM customer_details WHERE id = '".$result_2['customer_id']."'");
$result_3 = mysql_fetch_assoc($sql_3);

$payment = strtoupper(convert_number($result_1['payment']));


$month = $result_1['month'];
$settlement = $result_1['loan_settle'];

if($settlement =='yes'){
	$description = 'Full Settlement';
}else
{
	$description = addOrdinalNumberSuffix($month).'  Instalment';
}

$branch_name = '';
$branch_address = '';

switch ($_SESSION['login_branch']) {
	case 'MAJUSAMA':
		$branch_name = 'MJ MAJUSAMA SDN BHD';
		$branch_address = 'Bukit Mertajam, Penang';
		break;
	case 'MAJUSAMA2':
		$branch_name = 'MJ2 MAJUSAMA SDN BHD';
		$branch_address = 'Bukit Mertajam, Penang';
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
		$branch_address = '43-1,Jalan Borealis 3, Pusat Komersial Borealis, 14110 Bandar Cassia, Pulau Pinang';
		break;
	case 'TSY':
		$branch_name = 'TSY AGENCY';
		break;
	case 'TSY2':
		$branch_name = 'TSY2 AGENCY';
		break;
}

$mpdf = new mPDF('','A4','','',10,10,10,5,10,10,'P');
$html.='<div style="text-align:left;font-size:16px;">
			<b><u>' . $branch_name . '</u></b>
			<br>
			<span style="font-size:9px;">' . $branch_address . '</span>
			<br>
			<br>
		</div>
		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
			<tr>
				<td width="55%" style="text-align:right;"><b>OFFICIAL RECEIPT</b></td>
				<td  style="text-align:right;"><b>Customer Copy</b></td>
			</tr>
			</table>
		</div>
		<div>
			<table width="100%" align="left" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td width ="20%" style="text-align:right;padding-bottom:12px;"><b>RECEIVED FROM</b></td>
				<td width ="45%" ></td>
				<td width = "15%" style="text-align:left;padding-bottom:12px;"></td>
				<td style="padding-bottom:12px;"></td>
			</tr>
			<tr>
				<td style="text-align:right;padding-bottom:12px;"><b>Name</b></td>
				<td style="padding-bottom:12px;padding-bottom:12px;">: '.$result_3['name'].' </td>
				<td style="text-align:left;"><b>Receipt No.</b></td>
				<td style="padding-bottom:12px;padding-bottom:12px;">: '.$result_1['no_resit'].'</td>
			</tr>
			<tr>
				<td style="text-align:right;padding-bottom:12px;"><b>MyKad / Passport / ID</b></td>
				<td style="padding-bottom:12px;">: '.$result_3['nric'].'</td>
				<td style="text-align:left;padding-bottom:12px;"><b>Date</b></td>
				<td style="padding-bottom:12px;">: '.date("d/m/Y", strtotime($result_1['payment_date'])).'</td>
			</tr>
			<tr>
				<td style="text-align:right;padding-bottom:12px;"><b>Agreement No.</b></td>
				<td style="padding-bottom:12px;">: '.$result_2['loan_code'].'</td>
				<td style="text-align:left;padding-bottom:12px;"><b>Customer ID</b></td>
				<td style="padding-bottom:12px;">: '.$result_3['customercode2'].'</td>
			</tr>
			</table>
		</div>
		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td colspan="4" style="text-align:left;border-bottom: 1px solid;"></td>
			</tr>
			<tr>
				<td width="21%" ></td>
				<td width="49%" style="text-align:left;"><b>BEING PAYMENT OF:</b></td>
				<td width="15%" ></td>
				<td width="15%" style="text-align:center;"><b>RM</b></td>
			</tr>
			<tr>
				<td width="21%" ></td>
				<td width="49%" style="text-align:left;padding-bottom:50px;">'.$description.'</td>
				<td width="15%" ></td>
				<td width="15%" style="text-align:center;padding-bottom:50px;">'.number_format($result_1['payment'],'2').'</td>
			</tr>
			<tr>
				<td width="21%" ></td>
				<td width="49%" style="text-align:left;"><b>Ringgit Malaysia:</b></td>
				<td width="15%" ></td>
				<td width="15%" style="text-align:center;"></td>
			</tr>
			<tr>
				<td width="21%" style="border-bottom:1px solid;"></td>
				<td width="49%" style="text-align:left;border-bottom:1px solid;">'.$payment.' ONLY</td>
				<td width="15%" style="border-bottom:1px solid;"></td>
				<td width="15%" style="text-align:center;border-bottom:1px solid;"></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:right;border-right:1px solid;border-bottom: 1px solid;padding:5px;"><b>GIRO/CASH &nbsp;&nbsp;</b></td>
				<td width="15%" style="text-align:center;border-bottom:1px solid;border-right:1px solid;"><b>TOTAL</b></td>
				<td width="15%" style="text-align:center;border-bottom:1px solid;border-right:1px solid;"><b>'.number_format($result_1['payment'],'2').'</b></td>
			</tr>
			<tr>
				<td colspan="4" style="text-align:left;font-size:9px;">This is computer generated invoice. No signature required.</td>
			</tr>
			</table>
		</div>

		<br>
		<div>
			<table width="20%" align="left"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:10px;">
				<tr>
					<td colspan="2" style="text-align:left;"><b>Acknowledgement Receipt</b></td>
				</tr>
				<tr><td><br><br>&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td  style="text-align:left;border-top:1px solid;padding-bottom:20px;"><b>Name</b></td>
					<td width="10%" style="text-align:left;border-top:1px solid;padding-bottom:20px;">:</td>
				</tr>
				<tr>
					<td style="text-align:left;"><b>MyKad </b></td>
					<td style="text-align:left;">:</td>
				</tr>
			</table>
		</div>

		<br><br><hr style="color:black;"><br><br><br>

		<div style="text-align:left;font-size:16px;">
			<b><u>MJ MAJUSAMA SDN BHD</u></b>
			<br>
			<span style="font-size:9px;">Bukit Mertajam, Penang/span>
			<br>
			<br>
		</div>
		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:14px;">
			<tr>
				<td width="55%" style="text-align:right;"><b>OFFICIAL RECEIPT</b></td>
				<td  style="text-align:right;"><b>Office Copy</b></td>
			</tr>
			</table>
		</div>
		<div>
			<table width="100%" align="left" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td width ="20%" style="text-align:right;padding-bottom:12px;"><b>RECEIVED FROM</b></td>
				<td width ="45%" ></td>
				<td width = "15%" style="text-align:left;padding-bottom:12px;"></td>
				<td style="padding-bottom:12px;"></td>
			</tr>
			<tr>
				<td style="text-align:right;padding-bottom:12px;"><b>Name</b></td>
				<td style="padding-bottom:12px;padding-bottom:12px;">: '.$result_3['name'].' </td>
				<td style="text-align:left;"><b>Receipt No.</b></td>
				<td style="padding-bottom:12px;padding-bottom:12px;">: '.$result_1['no_resit'].'</td>
			</tr>
			<tr>
				<td style="text-align:right;padding-bottom:12px;"><b>MyKad / Passport / ID</b></td>
				<td style="padding-bottom:12px;">: '.$result_3['nric'].'</td>
				<td style="text-align:left;padding-bottom:12px;"><b>Date</b></td>
				<td style="padding-bottom:12px;">: '.date("d/m/Y", strtotime($result_1['payment_date'])).'</td>
			</tr>
			<tr>
				<td style="text-align:right;padding-bottom:12px;"><b>Agreement No.</b></td>
				<td style="padding-bottom:12px;">: '.$result_2['loan_code'].'</td>
				<td style="text-align:left;padding-bottom:12px;"><b>Customer ID</b></td>
				<td style="padding-bottom:12px;">: '.$result_3['customercode2'].'</td>
			</tr>
			</table>
		</div>

		<div>
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td colspan="4" style="text-align:left;border-bottom: 1px solid;"></td>
			</tr>
			<tr>
				<td width="21%" ></td>
				<td width="49%" style="text-align:left;"><b>BEING PAYMENT OF:</b></td>
				<td width="15%" ></td>
				<td width="15%" style="text-align:center;"><b>RM</b></td>
			</tr>
			<tr>
				<td width="21%" ></td>
				<td width="49%" style="text-align:left;padding-bottom:50px;">'.$description.'</td>
				<td width="15%" ></td>
				<td width="15%" style="text-align:center;padding-bottom:50px;">'.number_format($result_1['payment'],'2').'</td>
			</tr>
			<tr>
				<td width="21%" ></td>
				<td width="49%" style="text-align:left;"><b>Ringgit Malaysia:</b></td>
				<td width="15%" ></td>
				<td width="15%" style="text-align:center;"></td>
			</tr>
			<tr>
				<td width="21%" style="border-bottom:1px solid;"></td>
				<td width="49%" style="text-align:left;border-bottom:1px solid;">'.$payment.' ONLY</td>
				<td width="15%" style="border-bottom:1px solid;"></td>
				<td width="15%" style="text-align:center;border-bottom:1px solid;"></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:right;border-right:1px solid;border-bottom: 1px solid;padding:5px;"><b>GIRO/CASH &nbsp;&nbsp;</b></td>
				<td width="15%" style="text-align:center;border-bottom:1px solid;border-right:1px solid;"><b>TOTAL</b></td>
				<td width="15%" style="text-align:center;border-bottom:1px solid;border-right:1px solid;"><b>'.number_format($result_1['payment'],'2').'</b></td>
			</tr>
			<tr>
				<td colspan="4" style="text-align:left;font-size:9px;">This is computer generated invoice. No signature required.</td>
			</tr>
			</table>
		</div>

		<br>
		<div>
			<table width="20%" align="left"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:10px;">
				<tr>
					<td colspan="2" style="text-align:left;"><b>Acknowledgement Receipt</b></td>
				</tr>
				<tr><td><br><br>&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td  style="text-align:left;border-top:1px solid;padding-bottom:20px;"><b>Name</b></td>
					<td width="10%" style="text-align:left;border-top:1px solid;padding-bottom:20px;">:</td>
				</tr>
				<tr>
					<td style="text-align:left;"><b>MyKad<b></td>
					<td style="text-align:left;">:</td>
				</tr>
			</table>
		</div>';
		

$mpdf->WriteHTML($html);
$mpdf->Output();

?>
<?php

function convert_number($number){
    
    $res = "";
	
	if($number<0){
		$neg = ($number*-1);
		$res .= " Negative";
		$number = $neg;
	}
	$number = number_format((float)$number, 2, '.', '');
	$num = (explode(".",$number));
	$num_front = $num[0];
	$num_back = $num[1];
	
	if($num_front>0){
		//number_format((float)$number, 2, '.', '')
		$Gn = floor($num_front / 100000);  /* Millions (giga) */
		$num_front -= $Gn * 100000;
		$kn = floor($num_front / 1000);     /* Thousands (kilo) */
		$num_front -= $kn * 1000;
		$Hn = floor($num_front / 100);      /* Hundreds (hecto) */
		$num_front -= $Hn * 100;
		$Dn = floor($num_front / 10);       /* Tens (deca) */
		$num_front -= $Dn * 10;
		$n = floor($num_front / 1);               /* Ones */
		$num_front -= $n * 1;
		
		if ($Gn)
		{
			$res .= convert_number(number_format((float)$Gn, 2, '.', '')) . " Hundred";
		}
		 
		if ($kn)
		{
			$res .= (empty($res) ? "" : " ").convert_number(number_format((float)$kn, 2, '.', '')) . " Thousand";
		}
		 
		if ($Hn)
		{
			$res .= (empty($res) ? "" : " ").convert_number(number_format((float)$Hn, 2, '.', '')) . " Hundred";
		}
		
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
				"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
				"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
				"Nineteen");
		$tens = array("", "", " Twenty", " Thirty", " Fourty", " Fifty", " Sixty",
				" Seventy", " Eigthy", " Ninety");
		
		if ($Dn || $n)
		{
			if (!empty($res))
			{
				if($num_back>0){
					$res .= " " ;
				}else{
					$res .= " and" ;
				}
			}
			
			if ($Dn < 2)
			{
				$res .= $ones[$Dn * 10 + $n];
			}
			else
			{
				$res .= $tens[$Dn];
			 
				if ($n)
				{
				$res .= "-" . $ones[$n];
				}
			}
		}
	}
	
	
	if($num_back>0){
		$cent1 = floor($num_back/10);
		$num_back -= $cent1 * 10;
			
		$cent2 = floor($num_back / 1);
		$num_back -= $cent2 * 1;
		
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
				"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
				"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
				"Nineteen");
		$tens = array("", "", " Twenty", " Thirty", " Fourty", " Fifty", " Sixty",
				" Seventy", " Eigthy", " Ninety");
				
		if ($cent1 || $cent2)
		{
			if (!empty($res)){
				$res .= " and" ;
			}
			if ($cent1 < 2)
			{
				$res .= $ones[$cent1 * 10 + $cent2];
			}
			else
			{
				$res .= " Cents". $tens[$cent1];
			 
				if ($cent2)
				{
				$res .= "-" . $ones[$cent2];
				}
			}
		}
	}
	
	
	return $res;
}

  function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
?>