<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
$loan_code = $_GET['id'];

// Set the memory limit to 256 megabytes
ini_set('memory_limit', '512M');

$branch_name = '';

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

$sql_1 = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$loan_code."'");
$result_1 = mysql_fetch_assoc($sql_1);

$sql_2 = mysql_query("SELECT * FROM customer_details WHERE id = '".$result_1['customer_id']."'");
$result_2 = mysql_fetch_assoc($sql_2);

$loan_period = $result_1['loan_period'];

$loan_amount = $result_1['loan_amount'];

$loan_period_month = $loan_period.'months';

// $sql_6 = mysql_query("SELECT $loan_period_month FROM new_package WHERE loan_amount = '$loan_amount'");
$sql_6 = mysql_query("SELECT $loan_period_month, cash_in_hand FROM new_package WHERE loan_amount = '$loan_amount'");
$result_6 = mysql_fetch_row($sql_6);
$mth_payment= $result_6[0];
$cash_in_hand= $result_6[1];

$jumlah_besar = $cash_in_hand + ($cash_in_hand * $loan_period * 0.015);
$jumlah_faedah = $jumlah_besar - $cash_in_hand;
$payment = number_format(floor($jumlah_besar / $loan_period), 2);

// $result_6 = mysql_fetch_row($sql_6);

// $mth_payment= $result_6[0];

$payment_str = strtoupper(convert_number($payment));


$month = $result_1['month'];
$settlement = $result_1['loan_settle'];

if($settlement =='yes'){
	$description = 'Full Settlement';
}else
{
	$description = addOrdinalNumberSuffix($month).'  Instalment';
}

$mpdf = new mPDF('','A4','','',4,4,5,3,3,3,'P');

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$loan_code."'");
						while($result = mysql_fetch_assoc($sql))
						{

							$maxrow = $result['loan_period'];
						}
$sql2 = mysql_query("SELECT no_resit FROM loan_lejar_details WHERE receipt_no = '".$loan_code."' ORDER BY id ASC LIMIT 1");
$result2 = mysql_fetch_assoc($sql2);

$html.= '<table width="100%" style="border: 1px dotted black; border-collapse: collapse;font-size:8px;">';
for ($i = 1; $i <= $maxrow; $i += 2)
{
	if($result2['no_resit']!=''){
	$no_resit = $result2['no_resit']+$i;
	}
	else
	{
		$no_resit = $i;
	}

    $html.= '<tr>';
    $html.= "<td  width='50%' style='border: 1px dotted black; border-collapse: collapse;padding: 5px;position:fixed'>
    <table width='100%' style='border-collapse: collapse;position:fixed;'>
    <tr >
		    <td width='22%'><u><b>Official Receipt</b></u></td>
		    <td width='53%'><b>" . $branch_name . "</b></td>
		    <td width='25%'><b>No: ".str_pad($no_resit-1, 4, '0', STR_PAD_LEFT)."</b></td>
    </tr>
    <tr>
    		<td colspan='3'>Date:</td>
    </tr>
    <tr>
    		<td >Received from</td>
    		<td>: {$result_2['name']}</td>
    	
    </tr>
    <tr>
    		<td  height='12px;' >the sum of Ringgit</td>
    		<td>: {$payment_str} ONLY</td>
   
    </tr>
    <tr >
    		<td>in payment of</td>
    		<td>: {$loan_code}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".addOrdinalNumberSuffix($i)." INSTALMENT</td>

    </tr>
    <tr>
    		<td colspan='2'>&nbsp;</td>

    </tr>
    <tr>
    		<td colspan='2' height='15' style='border-left:1px solid black; border-right:1px solid black;border-top:1px solid black;text-align:center;font-size:10px'><b>RM ".$payment."</b></td>
    	
    </tr>
    <tr>
    		<td colspan='2' height='15' style='border-left:1px solid black; border-right:1px solid black; text-align:center; border-top:1px solid black;border-bottom:1px solid black; white-space:nowrap;'><b>Cash / Bank Transfer</b></td>
			
    </tr>
	<tr>
    		<td>&nbsp;</td>
    </tr>
	<tr>
    		<td colspan='3' style='font-style: italic;'>This is a computer-generated receipt. No signature is required.</td>
    </tr>
    </tr>

    </table>
    </td>
    ";
    $html.= "<td  width='50%' style='border: 1px dotted black; border-collapse: collapse;padding: 5px;position:fixed'>
    <table width='100%' style='border-collapse: collapse;position:fixed;'>
    <tr >
		    <td width='22%'><u><b>Official Receipt</b></u></td>
		    <td width='53%'><b>" . $branch_name . "</b></td>
		    <td width='25%'><b>No: ".str_pad($no_resit, 4, '0', STR_PAD_LEFT)."</b></td>
    </tr>
    <tr>
    		<td colspan='3'>Date:</td>
    </tr>
    <tr>
    		<td >Received from</td>
    		<td>: {$result_2['name']}</td>
    </tr>
    <tr>
    		<td  height='12px;' >the sum of Ringgit</td>
    		<td>: {$payment_str} ONLY</td>
    	
    </tr>
    <tr >
    		<td>in payment of</td>
    		<td>: {$loan_code}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".addOrdinalNumberSuffix($i+1)." INSTALMENT</td>
    		
    <tr>
    		<td colspan='2'>&nbsp;</td>
    		
    </tr>
    <tr>
    		<td colspan='2' height='15' style='border-left:1px solid black; border-right:1px solid black; border-top:1px solid black;text-align:center;font-size:10px'><b>RM ".$payment."</b></td>	
    </tr>
    <tr>
    		<td colspan='2' height='15' style='border-left:1px solid black; border-right:1px solid black; text-align:center; border-top:1px solid black;border-bottom:1px solid black;  white-space:nowrap;'><b>Cash / Bank Transfer</b></td>
    </tr>
	<tr>
    		<td>&nbsp;</td>
    </tr>
	<tr>
    		<td colspan='3' style='font-style: italic;'>This is a computer-generated receipt. No signature is required.</td>
    </tr>
    </tr>

    </table>
    </td>
    ";
    $html.= '</tr>';
}
$html.= '</table>';



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
					$res .= " and " ;
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
				$res .= " and " ;
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