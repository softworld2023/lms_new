<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
	$year = $_GET['year'];
	$month = $_GET['month'];
$payout_month = $year.'-'.$month;

$mth1 = $month;
if($mth1=='01'){$mth1 = 'Jan';}
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

$mpdf = new mPDF('','A4-L','','',2,2,5,5,10,10,'P');
$header='<div style="text-align:left;">
			<span style="font-size:16px;"><b>Monthly (10%)</b></span>
			<br>
			<span style="font-size:12px;"><b>' . $branch_name . '</b></span>
		</div>
		<div style="text-align:center;font-size:16px;">
			<b>'.$mth1.' - '.$year.'
		</div>';

				$mpdf->WriteHTML($header);
$html='
		<div style="font-size:10px;">
			<table width="100%" align="center"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td rowspan="2" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;" width ="2%" >Bil</td>
				<td rowspan="2" width ="6%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Agreement No.</td>
				<td rowspan="2" width ="3%" style="text-align:center;border-top:1px solid;border-right:1px solid;">ID</td>
				<td rowspan="2" width ="19%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Name</td>
				<td rowspan="2" width ="19%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Company</td>
				<td rowspan="2" width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Total</td>
				<td rowspan="2" width ="6%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Return</td>
				<td colspan="1" width ="7%" style="text-align:center;border-top:1px solid;border-right:1px solid;color:brown;">Bad Debts</td>
				<td rowspan="2" width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;color:blue;">Cash Balance</td>
			</tr>
			<tr>
				<td width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;">(Outstanding)</td>
			</tr>';
						$sql_1 = mysql_query("SELECT
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
							monthly_date ASC,monthly_payment_record.id ASC");
						$ctr=0;
						$numberoflist = mysql_num_rows($sql_1);
						if($numberoflist <=25){
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
								$style = "background-color:orange;";
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

							// Stamping (black colour code) 报账
							if(substr($result_1['loan_code'],0,2) !='AS' 
							&& substr($result_1['loan_code'],0,2) !='SB'
							 && substr($result_1['loan_code'],0,2) !='BP' 
							 && substr($result_1['loan_code'],0,2) !='KT' 
							 && substr($result_1['loan_code'],0,2) !='MS' 
							 && substr($result_1['loan_code'],0,2) !='MJ' 
							&& substr($result_1['loan_code'],0,2) !='PP'
							&& substr($result_1['loan_code'],0,2) !='NG'
							 && substr($result_1['loan_code'],0,2) !='TS')
							{
								$style1 = "color:#FF0000;";
							}
							else
							{
								$style1 = " ";	
							}

							


	$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;'.$style.''.$style1.'">'.$ctr.'</td>
				';
					if(substr($result_1['loan_code'],0,2) =='SB' 
					|| substr($result_1['loan_code'],0,2) =='MS' 
					|| substr($result_1['loan_code'],0,2) =='MJ'
					|| substr($result_1['loan_code'],0,2) =='PP'
					)
							{
								$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_1['loan_code']).'</td>
				';
			}else
			{
				$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1['loan_code'].'</td>
				';

			}
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1['customercode2'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1['name'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1['company'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;'.$style.'">'.number_format($result_1['PA'],2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.'">'.number_format($return,2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:brown;'.$style.'">'.number_format($baddebt,2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:#ff00ff;'.$style.'">'.number_format($cash_balance,2).'</td>


				</tr>';}

				for ($i=$ctr+1; $i <=25; $i++) {

					$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;">'.$i.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;">0.00</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:brown;">0.00</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				</tr>';}	
				while($result_1 = mysql_fetch_assoc($sql_1))
						{ 	
							$totalpayout+=$result_1['PA'];
							$totalreturn+=$return;
							$totalbaddebt+=$baddebt;
							$payout +=($result_1['PA']-($result_1['PA']*0.1));
						}
							

$html.='<tr>
				<td style="text-align:center;border-left: 1px solid;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;color:red;">'.number_format($totalpayout,2).'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;">'.number_format($totalreturn,2).'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;color:brown;">'.number_format($totalbaddebt,2).'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				</tr>
			</table>
		</div>
		<br><div>
			<table width="55%" border="0" cellspacing="0" cellpadding="1" align="left" style="font-family:Time New Roman;font-size:10px;">	
			<tr><td colspan="6">BAD DEBTS FOR THIS MONTH</td></tr>';
			$sql_2 = mysql_query("SELECT
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
							WHERE monthly_payment_record.month ='".$payout_month."' and monthly_payment_record.status='BAD DEBT'
						GROUP BY
							loan_code ASC");
						$ctr1=0;
						while($result_2 = mysql_fetch_assoc($sql_2))
						{ 
							$ctr1++;
							// $total_baddebt+=$result_2['PA'];
							$total_baddebt+=$result_2['balance'];

							if($ctr1>5){
								break;
							}
								// Stamping (black colour code) 报账
								if(substr($result_2['loan_code'],0,2) !='AS' 
								&& substr($result_2['loan_code'],0,2) !='SB' 
								&& substr($result_2['loan_code'],0,2) !='BP' 
								&& substr($result_2['loan_code'],0,2) !='KT' 
								&& substr($result_2['loan_code'],0,2) !='MS' 
								&& substr($result_2['loan_code'],0,2) !='MJ'
								&& substr($result_2['loan_code'],0,2) !='PP'
								&& substr($result_2['loan_code'],0,2) !='NG' 
								&& substr($result_2['loan_code'],0,2) !='TS')
							{
								$style2 = "color:#FF0000";
							}
							else
							{
								$style2 = " ";	
							}


$html.='
			<tr><td>'.$ctr1.'</td>
				';
			if(substr($result_2['loan_code'],0,2) =='SB' 
			|| substr($result_2['loan_code'],0,2) =='MS' 
			|| substr($result_2['loan_code'],0,2) =='MJ'
			|| substr($result_2['loan_code'],0,2) =='PP')
			{
				$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-left: 1px solid;background-color:orange;'.$style2.'">'.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_2['loan_code']).'</td>';
			}
			else
			{
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-left: 1px solid;background-color:orange;'.$style2.'">'.$result_2['loan_code'].'</td>';
			}
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;background-color:orange;">'.$result_2['customercode2'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;background-color:orange;">'.$result_2['name'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;background-color:orange;">'.$result_2['company'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;">'.number_format($result_2['balance'],2).'</td>
				</tr>';}


				for ($k=$ctr1+1; $k <=5; $k++) {
					$html.='
			<tr><td>'.$k.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-left: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				</tr>';}
				while($result_2 = mysql_fetch_assoc($sql_2))
						{ 
							
							// $total_baddebt+=$result_2['PA'];
							$total_baddebt+=$result_2['balance'];
						}

$html.='		<tr><td></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;border-left: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;color:red;">'.number_format($total_baddebt,2).'</td>
				</tr>
				</table>
				<table width="30%" border="0" cellspacing="0" cellpadding="2" align="right" style="font-family:Time New Roman;padding-top:-90px;padding-right:100px;font-size:12px;">	
				<tr><td style="text-align:right;">OPENING BALANCE:</td><td style="text-align:center;">0</td></tr>
				<tr><td style="text-align:right;">MONTHLY RETURN:</td><td style="text-align:center;">'.number_format($totalreturn,2).'</td></tr>
				<tr><td style="color:red;text-align:right;">MONTHLY PAYOUT:</td><td style="color:red;text-align:center;">'.number_format($payout,2).'</td></tr>
				<tr><td></td><td style="border-bottom:1px solid black;"></td></tr>
				<tr><td style="text-align:right;">BALANCE CLOSED A/C:</td><td style="text-align:center;">'.number_format($totalreturn - $payout ,2).'</td></tr>
				<tr><td></td><td style="border-bottom:1px solid black;"></td></tr>
				<tr><td style="color:brown;text-align:right;">BAD DEBT THIS MONTH:</td><td style="color:brown;text-align:center;">'.number_format($total_baddebt,2).'</td></tr>
			
		</table>
	
		

				';
			}
			else
			{
				$sql_1_list = mysql_query("SELECT
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
							monthly_date ASC,monthly_payment_record.id ASC");
						$ctr_list=0;
						$numberoflist = mysql_num_rows($sql_1_list);
				
						while($result_1_list = mysql_fetch_assoc($sql_1_list))
						{ 
							$ctr_list++;


							if($result_1_list['status']=='FINISHED')
							{
								$return_list=$result_1_list['PA']-$result_1_list['balance'];
								$baddebt_list=$result_1_list['balance'];
								$cash_balance_list+=(($result_1_list['PA']-$result_1_list['balance'])*0.1);

							}
							else if($result_1_list['status']=='PAID')
							{
								$return_list=$result_1_list['PA']-$result_1_list['balance'];
								$baddebt_list=$result_1_list['balance'];
								$cash_balance_list+=(($result_1_list['PA']-$result_1_list['balance'])*0.1);
							
							}else if($result_1_list['status']=='BAD DEBT')
							{
								// $return_list='0.00';
								// $baddebt_list=$result_1_list['PA'];
								// $cash_balance_list-=($result_1_list['PA']-($result_1_list['PA']*0.1));
								$return_list=$result_1_list['PA']-$result_1_list['balance'];
								$baddebt_list=$result_1_list['balance'];
								$cash_balance_list-=($result_1_list['PA']-($result_1_list['balance']*0.1));
							}

								if($result_1_list['status']=='BAD DEBT')
							{
								$style = "background-color:orange;";
							}else
							{
								$style = " ";	
							}

							$totalpayout_list+=$result_1_list['PA'];
							$totalreturn_list+=$return_list;
							$totalbaddebt_list+=$baddebt_list;
							$payout_list +=($result_1_list['PA']-($result_1_list['PA']*0.1));

							$select = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$result_1_list['loan_code']."'");
							$get_select = mysql_fetch_assoc($select);
							$num_select = mysql_num_rows($select);

							if(substr($result_1_list['loan_code'],0,2) !='AS' 
							&& substr($result_1_list['loan_code'],0,2) !='SB' 
							&& substr($result_1_list['loan_code'],0,2) !='BP' 
							&& substr($result_1_list['loan_code'],0,2) !='KT' 
							&& substr($result_1_list['loan_code'],0,2) !='MS' 
							&& substr($result_1_list['loan_code'],0,2) !='MJ' 
							&& substr($result_1_list['loan_code'],0,2) !='PP' 
							&& substr($result_1_list['loan_code'],0,2) !='NG' 
							&& substr($result_1_list['loan_code'],0,2) !='TS')
							{
								$style1 = "color:#FF0000;";
							}
							else
							{
								$style1 = " ";	
							}

							
							if($ctr_list > 12)
						{
							break;
						}
					  


	$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;'.$style.''.$style1.'">'.$ctr_list.'</td>
				';
					if(substr($result_1_list['loan_code'],0,2) =='SB' 
					|| substr($result_1_list['loan_code'],0,2) =='MS' 
					|| substr($result_1_list['loan_code'],0,2) =='MJ'
					|| substr($result_1_list['loan_code'],0,2) =='PP')
							{
								$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_1_list['loan_code']).'</td>
				';
			}else
			{
				$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list['loan_code'].'</td>
				';

			}
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list['customercode2'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list['name'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list['company'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;'.$style.'">'.number_format($result_1_list['PA'],2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.'">'.number_format($return_list,2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:brown;'.$style.'">'.number_format($baddebt_list,2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:#ff00ff;'.$style.'">'.number_format($cash_balance_list,2).'</td>


				</tr>';}

				$sql_1_list2 = mysql_query("SELECT
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
							monthly_date ASC,monthly_payment_record.id ASC");
						$ctr_list2=0;
						$numberoflist2 = mysql_num_rows($sql_1_list2);
				
						while($result_1_list2 = mysql_fetch_assoc($sql_1_list2))
						{ 
							$ctr_list2++;


							if($result_1_list2['status']=='FINISHED')
							{
								$return_list2=$result_1_list2['PA']-$result_1_list2['balance'];
								$baddebt_list2=$result_1_list2['balance'];
								$cash_balance_list2+=(($result_1_list2['PA']-$result_1_list2['balance'])*0.1);

							}
							else if($result_1_list2['status']=='PAID')
							{
								$return_list2=$result_1_list2['PA']-$result_1_list2['balance'];
								$baddebt_list2=$result_1_list2['balance'];
								$cash_balance_list2+=(($result_1_list2['PA']-$result_1_list2['balance'])*0.1);
							}else if($result_1_list2['status']=='BAD DEBT')
							{
								// $return_list2='0.00';
								// $baddebt_list2=$result_1_list2['PA'];
								// $cash_balance_list2-=($result_1_list2['PA']-($result_1_list2['PA']*0.1));
								$return_list2=$result_1_list2['PA']-$result_1_list2['balance'];
								$baddebt_list2=$result_1_list2['balance'];
								$cash_balance_list2-=($result_1_list2['PA']-($result_1_list2['balance']*0.1));
							}

								if($result_1_list2['status']=='BAD DEBT')
							{
								$style = "background-color:orange;";
							}else
							{
								$style = " ";	
							}

							$totalpayout_list2+=$result_1_list2['PA'];
							$totalreturn_list2+=$return_list2;
							$totalbaddebt_list2+=$baddebt_list2;
							$payout_list2 +=($result_1_list2['PA']-($result_1_list2['PA']*0.1));

							$select = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$result_1_list2['loan_code']."'");
							$get_select = mysql_fetch_assoc($select);
							$num_select = mysql_num_rows($select);

							// Stamping (black colour code) 报账
							if(substr($result_1_list2['loan_code'],0,2) !='AS' 
							&& substr($result_1_list2['loan_code'],0,2) !='SB'
							&& substr($result_1_list2['loan_code'],0,2) !='BP' 
							&& substr($result_1_list2['loan_code'],0,2) !='KT' 
							&& substr($result_1_list2['loan_code'],0,2) !='MS' 
							&& substr($result_1_list2['loan_code'],0,2) !='MJ'
							&& substr($result_1_list2['loan_code'],0,2) !='PP'
							&& substr($result_1_list2['loan_code'],0,2) !='NG'
							&& substr($result_1_list2['loan_code'],0,2) !='TS')
							{
								$style1 = "color:#FF0000;";
							}
							else
							{
								$style1 = " ";	
							}

							if($ctr_list2 < $numberoflist2-12)
						{
							continue;
						}
					  
							


	$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;'.$style.''.$style1.'">'.$ctr_list2.'</td>
				';
					if(substr($result_1_list2['loan_code'],0,2) =='SB' 
					|| substr($result_1_list2['loan_code'],0,2) =='MS' 
					|| substr($result_1_list2['loan_code'],0,2) =='MJ'
					|| substr($result_1_list2['loan_code'],0,2) =='PP')
							{
								$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_1_list2['loan_code']).'</td>
				';
			}else
			{
				$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list2['loan_code'].'</td>
				';

			}
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list2['customercode2'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list2['name'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;'.$style.''.$style1.'">'.$result_1_list2['company'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;'.$style.'">'.number_format($result_1_list2['PA'],2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;'.$style.'">'.number_format($return_list2,2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:brown;'.$style.'">'.number_format($baddebt_list2,2).'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:#ff00ff;'.$style.'">'.number_format($cash_balance_list2,2).'</td>


				</tr>';}

			

					$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;">'.$i.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;">0.00</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:brown;">0.00</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				</tr>';
					$sql_1 = mysql_query("SELECT
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
							monthly_date ASC,monthly_payment_record.id ASC");
						$ctr=0;
						$numberoflist = mysql_num_rows($sql_1);
				
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
								$style = "background-color:orange;";
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

							if($num_select==0)
							{
								$style1 = "color:#FF0000;";
							}else
							{
								$style1 = " ";	
							}
						}
				while($result_1 = mysql_fetch_assoc($sql_1))
						{ 	
							$totalpayout+=$result_1['PA'];
							$totalreturn+=$return;
							$totalbaddebt+=$baddebt;
							$payout +=($result_1['PA']-($result_1['PA']*0.1));
						}
							

$html.='<tr>
				<td style="text-align:center;border-left: 1px solid;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;color:red;">'.number_format($totalpayout,2).'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;">'.number_format($totalreturn,2).'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;color:brown;">'.number_format($totalbaddebt,2).'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				</tr>
			</table>
		</div>
		<br><div>
			<table width="55%" border="0" cellspacing="0" cellpadding="1" align="left" style="font-family:Time New Roman;font-size:10px;">	
			<tr><td colspan="6">BAD DEBTS FOR THIS MONTH</td></tr>';
			$sql_2 = mysql_query("SELECT
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
							WHERE monthly_payment_record.month ='".$payout_month."' and monthly_payment_record.status='BAD DEBT'
						GROUP BY
							loan_code ASC");
						$ctr1=0;
						while($result_2 = mysql_fetch_assoc($sql_2))
						{ 
							$ctr1++;
							// $total_baddebt+=$result_2['PA'];
							$total_baddebt+=$result_2['balance'];
							if($ctr1>5){
								break;
							}

							// Stamping (black colour code) 报账
							if(substr($result_2['loan_code'],0,2) !='AS' 
							&& substr($result_2['loan_code'],0,2) !='SB' 
							&& substr($result_2['loan_code'],0,2) !='BP' 
							&& substr($result_2['loan_code'],0,2) !='KT' 
							&& substr($result_2['loan_code'],0,2) !='MS' 
							&& substr($result_2['loan_code'],0,2) !='MJ' 
							&& substr($result_2['loan_code'],0,2) !='PP' 
							&& substr($result_2['loan_code'],0,2) !='NG' 
							&& substr($result_2['loan_code'],0,2) !='TS')
							{
								$style2 = "color:#FF0000";
							}
							else
							{
								$style2 = " ";	
							}


$html.='
			<tr><td>'.$ctr1.'</td>
			';
			if(substr($result_2['loan_code'],0,2) =='SB' 
			|| substr($result_2['loan_code'],0,2) =='MS' 
			|| substr($result_2['loan_code'],0,2) =='MJ'
			|| substr($result_2['loan_code'],0,2) == 'PP' )
			{
				$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-left: 1px solid;background-color:orange;'.$style2.'">'.preg_replace('/^([a-z]{2})/i','<span style="color:red;">\1</span>',$result_2['loan_code']).'</td>';
			}
			else
			{
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-left: 1px solid;background-color:orange;'.$style2.'">'.$result_2['loan_code'].'</td>';
			}
			$html.='
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;background-color:orange;'.$style2.'">'.$result_2['customercode2'].'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;background-color:orange;'.$style2.'">'.$result_2['name'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;background-color:orange;'.$style2.'">'.$result_2['company'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;color:red;">'.number_format($result_2['PA'],2).'</td>
				</tr>';}


				for ($k=$ctr1+1; $k <=5; $k++) {
					$html.='
			<tr><td>'.$k.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-left: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				</tr>';}
				while($result_2 = mysql_fetch_assoc($sql_2))
						{ 
							
							// $total_baddebt+=$result_2['PA'];
							$total_baddebt+=$result_2['balance'];
						}

$html.='		<tr><td></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;border-left: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;border-bottom: 1px solid;color:red;">'.number_format($total_baddebt,2).'</td>
				</tr>
				</table>
				<table width="30%" border="0" cellspacing="0" cellpadding="2" align="right" style="font-family:Time New Roman;padding-top:-90px;padding-right:100px;font-size:12px;">	
				<tr><td style="text-align:right;">OPENING BALANCE:</td><td style="text-align:center;">0</td></tr>
				<tr><td style="text-align:right;">MONTHLY RETURN:</td><td style="text-align:center;">'.number_format($totalreturn,2).'</td></tr>
				<tr><td style="color:red;text-align:right;">MONTHLY PAYOUT:</td><td style="color:red;text-align:center;">'.number_format($payout,2).'</td></tr>
				<tr><td></td><td style="border-bottom:1px solid black;"></td></tr>
				<tr><td style="text-align:right;">BALANCE CLOSED A/C:</td><td style="text-align:center;">'.number_format($totalreturn - $payout ,2).'</td></tr>
				<tr><td></td><td style="border-bottom:1px solid black;"></td></tr>
				<tr><td style="color:brown;text-align:right;">BAD DEBT THIS MONTH:</td><td style="color:brown;text-align:center;">'.number_format($total_baddebt,2).'</td></tr>
			
		</table>
	
		

				';
			
			}


				
$title="Monthly ( ".$mth1." - ".$year." )";
$mpdf->SetTitle($title);
$mpdf->WriteHTML($html);
$mpdf->WriteHTML($footer);
$mpdf->Output($title.'.pdf', 'I');

?>