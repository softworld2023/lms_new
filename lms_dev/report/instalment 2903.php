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
			<a href="statement_report.php">Statement</a>
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
                    <td style="font-size: 16px;"><br>SELECT YEAR
               
<select 
         name="select"
         class="form-control"
         id="dropdownYear"
         style="width: 120px;height: 30px; font-size:16px;"
         onchange="getProjectReportFunc()">
</select>
MONTH
<?php
    $selected_month = date('m'); //current month

    echo '<select id="month" name="month" style="width: 120px;height: 30px; font-size:16px;">'."\n";
    for ($i_month = 1; $i_month <= 12; $i_month++) { 
        $selected = ($selected_month == $i_month ? ' selected' : '');
        echo '<option value="'.$i_month.'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
    }
    echo '</select>'."\n";
?>
                   <input class="btn btn-blue" type="submit" name="search" value="SEARCH" id="search" ></td></td>


<!-- <a href="lampiran_b1.php" target="_blank">Lampiran B1</a> -->
                
 <td style="font-size: 16px;"></td>
                </tr>               
             	<?php 
    	if(isset($_POST['search'])){
    		$mth1 = $_POST['month'];

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

		$month = $_POST['month'];
		if($month=='01'){$month = '01';}
		else if($month=='02'){$month = '02';}
		else if($month=='03'){$month = '03';}
		else if($month=='04'){$month = '04';}
		else if($month=='05'){$month = '05';}
		else if($month=='06'){$month = '06';}
		else if($month=='07'){$month = '07';}
		else if($month=='08'){$month = '08';}
		else if($month=='09'){$month = '09';}
		else if($month=='10'){$month = '10';}
		else if($month=='11'){$month = '11';}
		else if($month=='12'){$month = '12';}
		
	   echo ' <tr><td style="font-size:16px;"><a href="instalment_pdf.php?year='.$_POST['select'].'&month='.$month.'" target="_blank">Instalment ('.$mth1.' - '.$_POST['select'].')</a></td></tr>';
	  }
	  if($_POST['month']==''){
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
          <td colspan='9' style="color:black;font-size: 22px;"><b><?php if($_POST['month']!=''){echo 'Instalment '.$mth1.' - '.$_POST['select'];}else{echo 'Instalment '.date("M - Y"); }?><b></td>
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
    if($_POST['month']!=''){
    $year = $_POST['select'];
	$month = $_POST['month'];
	if($month=='01'){$month = '01';}
		else if($month=='02'){$month = '02';}
		else if($month=='03'){$month = '03';}
		else if($month=='04'){$month = '04';}
		else if($month=='05'){$month = '05';}
		else if($month=='06'){$month = '06';}
		else if($month=='07'){$month = '07';}
		else if($month=='08'){$month = '08';}
		else if($month=='09'){$month = '09';}
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
								temporary_payment_details.loan_code ASC");
					
					
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
		<td align="center" style="border-bottom: 1px dashed black;"><a style="<?php echo $style.";"; ?>" href="payloan_a.php?id=<?php echo $result_4['customer_loanid']; ?>" title="Make Payment"><?php echo $result_4['loan_code']; ?></a></td>
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
        <td style="border-right: 1px dashed black;border-bottom: 1px dashed black;text-align: center;<?php if($collected_remarks=='SETTLE'){echo 'background-color: yellow;';}elseif($collected_remarks=='BD'){ echo 'background-color: darkorange;'; }else{} ?>"><?php echo number_format($collected).''.$collected_remarks; ?></td>
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
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;"></td>
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
        <td style="border-top:1px solid black;border-right: 1px dashed black;text-align: center;color: black;"><?php echo number_format($totalcollected); ?></td>
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
        <td colspan="2" style="border-top:1px solid black;border-right: 1px dashed black;color: black;"><?php echo "Total Int= ".number_format($totalint); ?></td>
        <td colspan="3" style="border-top:1px solid black; color: black;"><?php echo "Total Loan= "; ?></td>
        <td colspan="4" style="border-top:1px solid black;border-right: 1px solid black; color: black;"><?php echo number_format($totalloan); ?></td>
    </tr>
    <tr>
    	<td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
        <td style="border-top:1px solid black;">&nbsp;</td>
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr><?php
        $sql_bd_collected = mysql_query("SELECT
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
									WHERE late_interest_payment_details.payment_date BETWEEN '".$date_from."'	AND '".$date_to."'
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
									WHERE late_interest_record.bd_date BETWEEN '".$date_from."'	AND '".$date_to."'
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");
														
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

						$closing_balance = $opening_balance_k + $totalcollected + $totalsettle + $capital_in + $baddebt_k + $monthly_k - $totalout - $expenses_k - $interest_paid_out - $return_capital;
						// $closing_balance = $opening_balance_k + $collected_k + $settle_k + $capital_in + $baddebt_k + $monthly_k - $payout_k - $expenses_k - $interest_paid_out - $return_capital;
						// $closing_balance = $opening_balance + $totalcollected + $totalsettle + $capital_in + $totalamount_collected_desc + $cash_balance - $totalout - $totalexpenses - $interest_paid_out - $return_capital;


						?>
    <tr><td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">Opening Balance =</td>
        <!-- <td style="color:#FF00FF;"><?php echo number_format($opening_balance, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="opening_balance_k" id="opening_balance_k" value=" <?php echo number_format($opening_balance_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_opening_balance('<?php echo $payout_month; ?>')" title="save opening balance"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
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
        <tr>
        <td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">Instalment Collected =</td>
        <td style="color:#FF00FF;"><?php echo number_format($totalcollected, '2'); ?></td>
        <td>&nbsp;</td>
        <!-- <td colspan="3" style="color:#FF00FF;"><input type="text" name="collected_k" id="collected_k" value=" <?php echo number_format($collected_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_collected('<?php echo $payout_month; ?>')" title="save collected"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->
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
      <tr >
      	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">Settle =</td>
        <td style="color:#FF00FF;"><?php echo number_format($totalsettle, '2'); ?></td>
        <td>&nbsp;</td>
        <!-- <td colspan="3" style="color:#FF00FF;"><input type="text" name="settle_k" id="settle_k" value=" <?php echo number_format($settle_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_settle('<?php echo $payout_month; ?>')" title="save settle"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->
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
     <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">Capital In =</td>
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="capital_in" id="capital_in" value=" <?php echo number_format($capital_in,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_capital_in('<?php echo $payout_month; ?>')" title="save capital in"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
      
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
          <tr>
        <td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">BD Collected = </td>
        <!-- <td style="color:#FF00FF;"><?php echo number_format($totalamount_collected_desc, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="baddebt_k" id="baddebt_k" value=" <?php echo number_format($baddebt_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_bad_debt('<?php echo $payout_month; ?>')" title="save bad debt"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
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
       <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">Monthly =</td>
        <!-- <td style="color:#FF00FF;"><?php echo number_format($cash_balance, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="monthly_k" id="monthly_k" value=" <?php echo number_format($monthly_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_monthly('<?php echo $payout_month; ?>')" title="save monthly"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
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
        <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;color:red;">Instalment Payout =</td>
        <td style="color:red;"><?php echo number_format($totalout, '2'); ?></td>
        <td>&nbsp;</td>
        <!-- <td colspan="3" style="color:#FF00FF;"><input type="text" name="payout_k" id="payout_k" value=" <?php echo number_format($payout_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_payout('<?php echo $payout_month; ?>')" title="save payout"><img src="../img/document_save.png" /></a>
		<?php } ?></td> -->
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
        <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;color:red;">Expenses =</td>
        <!-- <td style="color:red;"><?php echo number_format($totalexpenses, '2'); ?></td>
        <td>&nbsp;</td> -->
        <td colspan="3" style="color:#FF00FF;"><input type="text" name="expenses_k" id="expenses_k" value=" <?php echo number_format($expenses_k,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_expenses('<?php echo $payout_month; ?>')" title="save expenses"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
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
     <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;color:red;">Interest Paid Out =</td>
        <td colspan="3" style="color:red;"><input type="text" name="interest_paid_out" id="interest_paid_out" value=" <?php echo number_format($interest_paid_out,2) ; ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_interest('<?php echo $payout_month; ?>')" title="save interest paid out"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
      
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
         <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;color:red;">Return Capital =</td>
        <td colspan="3" style="color:red;"><input type="text" name="return_capital" id="return_capital" value=" <?php echo number_format($return_capital,2); ?>" />        	<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) { ?>
		<a href="javascript:save_capital('<?php echo $payout_month; ?>')" title="save return capital"><img src="../img/document_save.png" /></a>
		<?php } ?></td>
        
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
         <tr>
       	<td>&nbsp;</td>
    	<td colspan="2" style="text-align: right;">Closing Balance =</td>
        <td style="color:#FF00FF;"><?php echo number_format($closing_balance, '2'); ?></td>
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
		function save_interest(pay_month)
{
	$interest_paid_out = document.getElementById('interest_paid_out').value;
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
}	function save_collected(pay_month)
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
	let select = document.getElementById('dropdownYear');
let currYear = new Date().getFullYear();
let futureYear = currYear+5;
let pastYear = currYear-3;
for(let year = pastYear; year <= futureYear; year++){
  let option = document.createElement('option');
  option.setAttribute('value', year);
  if(year === currYear){
    option.setAttribute('selected', true);
  }
  option.innerHTML = year;
  select.appendChild(option);
}
</script>
</body>
</html>