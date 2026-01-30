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

#list_table1
{
	padding-left: 100px;
	padding-right: 100px;
	font-size: 12px;	
}

#list_table1 tr th
{
	text-align:center;
	color:#666;
	border: 1px solid;
}
#list_table1 tr td
{
	text-align:center;
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
			<a href="instalment.php">Instalment</a>
			<a href="statement.php" id="active-menu">Statement</a>
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
		
	   echo ' <tr><td style="font-size:16px;"><a href="statement_pdf.php?year='.$_POST['select'].'&month='.$month.'" target="_blank">STATEMENT ('.$mth1.' - '.$_POST['select'].')</a></td></tr>';
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
	  echo ' <tr><td style="font-size:16px;"><a href="statement_pdf.php?year='.$year_now.'&month='.$mth_now.'" target="_blank">STATEMENT ('.$mth2.' - '.$year_now.')</a></td></tr>';}
    ?> 
            </table>
        </form>
        <br>
     	</td>
     </tr>
 </table>
 <table width="1280" id="list_table1">
	<tr>
    	<th rowspan="3">MONTH</th>
		<th colspan="6">LOAN OUT</th>
    	<th colspan="6">INTEREST RECEIVED</th>
    	<th rowspan="3">EXPENSES 2</th>
    	<th rowspan="3">EXPENSES </th>
    	<th colspan="6">LOAN RETURN</th>
    	<th colspan="6">BAD DEBTS</th>
    	<th rowspan="3">BAD DEBTS COLLECTED <br> (Instalment & Monthly)</th>
    	<th rowspan="3">MONTHLY PROFIT</th>
    	<th rowspan="3">TOTAL PROFIT</th>
        <th rowspan="3">BALANCE IN HAND</th>
    </tr>
    	<tr>
    	<th >(Monthly)</th>
		<th colspan="5">(Instalment)</th>
		<th >(Monthly)</th>
		<th colspan="5">(Instalment)</th>
		<th >(Monthly)</th>
		<th colspan="5">(Instalment)</th>
		<th >(Monthly)</th>
		<th colspan="5">(Instalment)</th>

    	
    </tr>
    <tr>
    	<th>(10%)</th>
		<th >(10%)</th>
    	<th >(8%)</th>
    	<th >(6.2%))</th>
    	<th >(5.5%)</th>
    	<th >(5%)</th>
    	<th>(10%)</th>
		<th >(10%)</th>
    	<th >(8%)</th>
    	<th >(6.2%))</th>
    	<th >(5.5%)</th>
    	<th >(5%)</th>
    	    	<th>(10%)</th>
		<th >(10%)</th>
    	<th >(8%)</th>
    	<th >(6.2%))</th>
    	<th >(5.5%)</th>
    	<th >(5%)</th>
    	    	<th>(10%)</th>
		<th >(10%)</th>
    	<th >(8%)</th>
    	<th >(6.2%))</th>
    	<th >(5.5%)</th>
    	<th >(5%)</th>
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
    	$year = date('Y');
$date_from = date("Y-m-01");
$date_to = date("Y-m-31");
$payout_month = date("Y-m");
}

$ctr=0;
$sql=mysql_query("SELECT
					*
						FROM
					loan_payment_details
					WHERE month_receipt <='".$payout_month."' and month_receipt >='".$year."'
					GROUP BY month_receipt");
$r = mysql_num_rows($sql);
while($result = mysql_fetch_assoc($sql))
	{ 

		$month_receipt = $result['month_receipt'];

		$date_from = $month_receipt.'-01';
		$date_to = $month_receipt.'-31';
		$payout_month = $month_receipt;
		$i=0;
		if($r>0 && $result['month_receipt']!='0-0'){	
		$currentDate = false;
		$i++;
                    if ($result['month_receipt'] != $currentDate ){     
                    	 $i=1;
        ?>
        <!-- <tr style="background-color: #45b1e8;">
          <td colspan='11' style="color:black;font-size: 22px;"><b><?php echo 'Tahun '.$result['month_receipt']; ?><b></td>
        </tr> -->
        <?php 
        //$currentDate = $result['month_receipt'];

            }

            			$loan_percent1=0;
						$loan_percent2=0;
						$loan_percent3=0;
						$loan_percent4=0;
						$loan_percent5=0;
						$return_percent1=0;
						$return_percent2=0;
						$return_percent3=0;
						$return_percent4=0;
						$return_percent5=0;
						$baddebt_amount1=0;
						$baddebt_amount2=0;
						$baddebt_amount3=0;
						$baddebt_amount4=0;
						$baddebt_amount5=0;

						$total_loan_percent1=0;
						$total_loan_percent2=0;
						$total_loan_percent3=0;
						$total_loan_percent4=0;
						$total_loan_percent5=0;
						$totalout=0;
						$totalcollected=0;
						$total_return_percent1=0;
						$total_return_percent2=0;
						$total_return_percent3=0;
						$total_return_percent4=0;
						$total_return_percent5=0;
						$totalsettle=0;
						$totalbaddebt=0;
						$total_baddebt_amount1=0;
						$total_baddebt_amount2=0;
						$total_baddebt_amount3=0;
						$total_baddebt_amount4=0;
						$total_baddebt_amount5=0;
$sql_1 = mysql_query("SELECT
							customer_loanpackage.loan_code,
							customer_loanpackage.payout_date,
							customer_loanpackage.loan_amount,
							customer_loanpackage.loan_period,
							customer_loanpackage.loan_total,
							
							customer_details.customercode2,
							customer_details.name,
							customer_details.nric,
							customer_employment.company,
							loan_payment_details.monthly,
							loan_payment_details.loan_percent,
							loan_payment_details.return_percent,
							loan_payment_details.loan_status 
						FROM
							customer_loanpackage
							LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
							LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
							LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
						WHERE
							customer_loanpackage.loan_package = 'NEW PACKAGE'
							AND(
							loan_payment_details.month_receipt ='".$payout_month."'
							AND
							customer_loanpackage.payout_date NOT BETWEEN '".$date_from."' and '".$date_to."')
							AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished' OR customer_loanpackage.loan_status = 'BAD DEBT')
							GROUP BY customer_loanpackage.loan_code, loan_payment_details.month_receipt
							ORDER BY customer_loanpackage.loan_code ASC ");
						$ctr=0;
						while($result_1 = mysql_fetch_assoc($sql_1))
						{ 
							$ctr++;

						if($result_1['loan_period']>=1 && $result_1['loan_period']<=12)
						{
						$loan_percent1=$result_1['loan_percent'];
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1=$result_1['return_percent'];
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						if ($result_1['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1=$result_1['return_percent'];
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}

						}else if($result_1['loan_period']>=13 && $result_1['loan_period']<=24)
						{
						$loan_percent1='';
						$loan_percent2=$result_1['loan_percent'];
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2=$result_1['return_percent'];
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						if ($result_1['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2=$result_1['return_percent'];
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}

						}else if($result_1['loan_period']>=25 && $result_1['loan_period']<=36)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3=$result_1['loan_percent'];
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3=$result_1['return_percent'];
						$return_percent4='';
						$return_percent5='';

						if ($result_1['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3=$result_1['return_percent'];
						$baddebt_amount4='';
						$baddebt_amount5='';
						}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}

						}
						else
						if($result_1['loan_period']>=37 && $result_1['loan_period']<=48)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4=$result_1['loan_percent'];
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4=$result_1['return_percent'];
						$return_percent5='';

						if ($result_1['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4=$result_1['return_percent'];
						$baddebt_amount5='';
						}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}
						
						}else
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5=$result_1['loan_percent'];
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5=$result_1['return_percent'];

						if ($result_1['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5=$result_1['return_percent'];
						}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}
					}

						if($result_1['loan_status']=='SETTLE')
						{
							$out = '';
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_1['return_percent'];
							$baddebt = '';
						}else if ($result_1['loan_status']=='BAD DEBT')
						{
							$out = '';
							$collected = '';
							$collected_remarks = 'BD';
							$settle ='';
							$baddebt ='1';
						}else if ($result_1['loan_status']=='COLLECTED')
						{	
							$out='';
							$collected=$result_1['monthly'];
							$collected_remarks = '';
							$settle='';
							$baddebt='';
							
						}
						else
						{
							$out=$result_1['loan_amount']-($result_1['loan_amount']*0.1);
							$collected='';
							$collected_remarks = '';
							$settle='';
							$baddebt='';

						}

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
						$total_baddebt_amount1+=$baddebt_amount1;
						$total_baddebt_amount2+=$baddebt_amount2;
						$total_baddebt_amount3+=$baddebt_amount3;
						$total_baddebt_amount4+=$baddebt_amount4;
						$total_baddebt_amount5+=$baddebt_amount5;
					}

$sql_2 = mysql_query("SELECT
							customer_loanpackage.loan_code,
							customer_loanpackage.payout_date,
							customer_loanpackage.loan_amount,
							customer_loanpackage.loan_period,
							customer_loanpackage.loan_total,
							
							customer_details.customercode2,
							customer_details.name,
							customer_details.nric,
							customer_employment.company,
							loan_payment_details.monthly,
							loan_payment_details.loan_percent,
							loan_payment_details.return_percent,
							loan_payment_details.loan_status 
						FROM
							customer_loanpackage
							LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
							LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
							LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
						WHERE
							customer_loanpackage.loan_package = 'NEW PACKAGE'
							AND(
							loan_payment_details.month_receipt ='".$payout_month."'
							AND
							customer_loanpackage.payout_date  BETWEEN '".$date_from."' and '".$date_to."')
							AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished' OR customer_loanpackage.loan_status = 'BAD DEBT')
							AND loan_payment_details.loan_status!=''
							GROUP BY customer_loanpackage.loan_code
							ORDER BY customer_loanpackage.loan_code ASC ");
					
						while($result_2 = mysql_fetch_assoc($sql_2))
						{ 
							$ctr++;

						if($result_2['loan_period']>=1 && $result_2['loan_period']<=12)
						{
						$loan_percent1=$result_2['loan_percent'];
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1=$result_2['return_percent'];
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						if ($result_2['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1=$result_2['return_percent'];
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}

						}else if($result_2['loan_period']>=13 && $result_2['loan_period']<=24)
						{
						$loan_percent1='';
						$loan_percent2=$result_2['loan_percent'];
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2=$result_2['return_percent'];
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						if ($result_2['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2=$result_2['return_percent'];
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';

					}

						}else if($result_2['loan_period']>=25 && $result_2['loan_period']<=36)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3=$result_2['loan_percent'];
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3=$result_2['return_percent'];
						$return_percent4='';
						$return_percent5='';

						if ($result_2['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3=$result_2['return_percent'];
						$baddebt_amount4='';
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}

						}else if($result_2['loan_period']>=37 && $result_2['loan_period']<=48)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4=$result_2['loan_percent'];
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4=$result_2['return_percent'];
						$return_percent5='';

						if ($result_2['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4=$result_2['return_percent'];
						$baddebt_amount5='';
					}else
					{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}

						
						}else
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5=$result_2['loan_percent'];
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5=$result_2['return_percent'];

						if ($result_2['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5=$result_2['return_percent'];
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}
						}

						if($result_2['loan_status']=='SETTLE')
						{
							$out = '';
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_2['return_percent'];
							$baddebt = '';
						}else if ($result_2['loan_status']=='BAD DEBT')
						{
							$out = '';
							$collected = '';
							$collected_remarks = 'BD';
							$settle ='';
							$baddebt ='1';
						}else if ($result_2['loan_status']=='COLLECTED')
						{	
							$out=$result_2['loan_amount']-($result_2['loan_amount']*0.1);
							$collected=$result_2['monthly'];
							$collected_remarks = '';
							$settle='';
							$baddebt='';
						}
						else
						{
							$out=$result_2['loan_amount']-($result_2['loan_amount']*0.1);
							$collected='';
							$collected_remarks = '';
							$settle='';
							$baddebt='';

						}

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
						$total_baddebt_amount1+=$baddebt_amount1;
						$total_baddebt_amount2+=$baddebt_amount2;
						$total_baddebt_amount3+=$baddebt_amount3;
						$total_baddebt_amount4+=$baddebt_amount4;
						$total_baddebt_amount5+=$baddebt_amount5;
					}

					$sql_3 = mysql_query("SELECT
							customer_loanpackage.loan_code,
							customer_loanpackage.payout_date,
							customer_loanpackage.loan_amount,
							customer_loanpackage.loan_period,
							customer_loanpackage.loan_total,
							
							customer_details.customercode2,
							customer_details.name,
							customer_details.nric,
							customer_employment.company,
							loan_payment_details.monthly,
							loan_payment_details.loan_percent,
							loan_payment_details.return_percent,
							loan_payment_details.loan_status,
							loan_payment_details.customer_loanid  
						FROM
							customer_loanpackage
							LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
							LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
							LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
						WHERE
							customer_loanpackage.loan_package = 'NEW PACKAGE'
							AND(
							loan_payment_details.month_receipt !='".$payout_month."'
							AND
							customer_loanpackage.payout_date  BETWEEN '".$date_from."' and '".$date_to."')
							AND (customer_loanpackage.loan_status = 'Paid' OR customer_loanpackage.loan_status = 'Finished' OR customer_loanpackage.loan_status = 'BAD DEBT')
							AND loan_payment_details.loan_status!=''
							GROUP BY customer_loanpackage.loan_code
							ORDER BY customer_loanpackage.loan_code ASC ");
					
					
						while($result_3 = mysql_fetch_assoc($sql_3))
						{ 
							$ctr++;

						if($result_3['loan_period']>=1 && $result_3['loan_period']<=12)
						{
						$loan_percent1=$result_3['loan_percent'];
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1=$result_3['return_percent'];
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						if ($result_3['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1=$result_3['return_percent'];
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';}

						}else if($result_3['loan_period']>=13 && $result_3['loan_period']<=24)
						{
						$loan_percent1='';
						$loan_percent2=$result_3['loan_percent'];
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2=$result_3['return_percent'];
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';

						if ($result_3['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2=$result_3['return_percent'];
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}

						}else if($result_3['loan_period']>=25 && $result_3['loan_period']<=36)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3=$result_3['loan_percent'];
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3=$result_3['return_percent'];
						$return_percent4='';
						$return_percent5='';
						if ($result_3['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3=$result_3['return_percent'];
						$baddebt_amount4='';
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}

						}else if($result_3['loan_period']>=37 && $result_3['loan_period']<=48)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4=$result_3['loan_percent'];
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4=$result_3['return_percent'];
						$return_percent5='';
						if ($result_3['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4=$result_3['return_percent'];
						$baddebt_amount5='';
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}
						
						}else
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5=$result_3['loan_percent'];
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5=$result_3['return_percent'];
						if ($result_3['loan_status']=='BAD DEBT')
						{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5=$result_3['return_percent'];
					}else{
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
					}
						}

						if($result_3['loan_status']=='SETTLE')
						{
							$out = '';
							$collected = '';
							$collected_remarks = 'SETTLE';
							$settle = $result_3['return_percent'];
							$baddebt = '';
						}else if ($result_3['loan_status']=='BAD DEBT')
						{
							$out = '';
							$collected = '';
							$collected_remarks = 'BD';
							$settle ='';
							$baddebt = '1';
						}else if ($result_3['loan_status']=='COLLECTED')
						{	
							$out=$result_3['loan_amount']-($result_3['loan_amount']*0.1);
							$collected='';
							$collected_remarks = '';
							$settle='';
							$baddebt= '';
						}
						else
						{
							$out=$result_3['loan_amount']-($result_3['loan_amount']*0.1);
							$collected='';
							$collected_remarks = '';
							$settle='';
							$baddebt= '';

						}

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
						$total_baddebt_amount1+=$baddebt_amount1;
						$total_baddebt_amount2+=$baddebt_amount2;
						$total_baddebt_amount3+=$baddebt_amount3;
						$total_baddebt_amount4+=$baddebt_amount4;
						$total_baddebt_amount5+=$baddebt_amount5;
						}
	 $sql_4 = mysql_query("SELECT
								customer_loanpackage.loan_code,
								customer_loanpackage.payout_date,
								customer_loanpackage.loan_amount,
								customer_loanpackage.loan_period,
								customer_loanpackage.loan_total,
								customer_loanpackage.loan_status,
								customer_details.customercode2,
								customer_details.name,
								customer_details.nric,
								customer_employment.company,
								loan_payment_details.monthly,
								loan_payment_details.loan_percent,
								loan_payment_details.return_percent,
								loan_payment_details.loan_status,
								loan_payment_details.customer_loanid 
							FROM
								customer_loanpackage
								LEFT JOIN customer_details ON customer_loanpackage.customer_id = customer_details.id
								LEFT JOIN customer_employment ON customer_employment.customer_id = customer_details.id
								LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
							WHERE
								loan_code NOT IN (
								SELECT
									customer_loanpackage.loan_code 
								FROM
									customer_loanpackage
									LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
								WHERE
									customer_loanpackage.loan_package = 'NEW PACKAGE' 
									AND ( loan_payment_details.month_receipt = '".$payout_month."' AND customer_loanpackage.payout_date NOT BETWEEN '".$date_from."' AND '".$date_to."' ) 
									AND ( customer_loanpackage.loan_status = 'Paid') 
								GROUP BY
									customer_loanpackage.loan_code 
								ORDER BY
									customer_loanpackage.loan_code ASC 
								) 
								AND loan_code NOT IN (
								SELECT
									customer_loanpackage.loan_code 
								FROM
									customer_loanpackage
									LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
								WHERE
									customer_loanpackage.loan_package = 'NEW PACKAGE' 
									AND ( loan_payment_details.month_receipt = '".$payout_month."' AND customer_loanpackage.payout_date BETWEEN '".$date_from."' AND '".$date_to."' ) 
									AND ( customer_loanpackage.loan_status = 'Paid' ) 
									AND loan_payment_details.loan_status != '' 
								GROUP BY
									customer_loanpackage.loan_code 
								ORDER BY
									customer_loanpackage.loan_code ASC 
								) 
								AND loan_code NOT IN (
								SELECT
									customer_loanpackage.loan_code 
								FROM
									customer_loanpackage
									LEFT JOIN loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
								WHERE
									customer_loanpackage.loan_package = 'NEW PACKAGE' 
									AND ( loan_payment_details.month_receipt != '".$payout_month."' AND customer_loanpackage.payout_date BETWEEN '".$date_from."' AND '".$date_to."' ) 
									AND ( customer_loanpackage.loan_status = 'Paid' ) 
									AND loan_payment_details.loan_status != '' 
								) 
								AND customer_loanpackage.loan_status = 'Paid' 
								GROUP BY loan_payment_details.receipt_no
							ORDER BY
								loan_payment_details.receipt_no ASC");
					
					
						while($result_4 = mysql_fetch_assoc($sql_4))
						{ 
							$ctr++;

							$sql_a = mysql_query("SELECT
													customer_loanid,
													receipt_no,
												loan_percent	
												FROM
													loan_payment_details 
												WHERE
													payment_date = '0000-00-00' 
												AND receipt_no = '".$result_4['loan_code']."'
												GROUP BY
													customer_loanid 
												ORDER BY
													customer_loanid ASC,
													id DESC
												");
							$result_a = mysql_fetch_assoc($sql_a);

						if($result_4['loan_period']>=1 && $result_4['loan_period']<=12)
						{
						$loan_percent1=$result_a['loan_percent'];
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';

						}else if($result_4['loan_period']>=13 && $result_4['loan_period']<=24)
						{
						$loan_percent1='';
						$loan_percent2=$result_a['loan_percent'];
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';

						}else if($result_4['loan_period']>=25 && $result_4['loan_period']<=36)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3=$result_a['loan_percent'];
						$loan_percent4='';
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';

						}else if($result_4['loan_period']>=37 && $result_4['loan_period']<=48)
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4=$result_a['loan_percent'];
						$loan_percent5='';
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						
						}else
						{
						$loan_percent1='';
						$loan_percent2='';
						$loan_percent3='';
						$loan_percent4='';
						$loan_percent5=$result_a['loan_percent'];
						$return_percent1='';
						$return_percent2='';
						$return_percent3='';
						$return_percent4='';
						$return_percent5='';
						$baddebt_amount1='';
						$baddebt_amount2='';
						$baddebt_amount3='';
						$baddebt_amount4='';
						$baddebt_amount5='';
						}


							$out='';
							$collected='';
							$collected_remarks = '';
							$settle='';
							$baddebt= '';

					

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
					}

    $totalloan=$total_loan_percent1+$total_loan_percent2+$total_loan_percent3+$total_loan_percent4+$total_loan_percent5;

    $after_total_loan_percent1 = $total_loan_percent1*0.1;
    $after_total_loan_percent2 = $total_loan_percent2*0.08;
    $after_total_loan_percent3 = $total_loan_percent3*0.062;
    $after_total_loan_percent4 = $total_loan_percent4*0.055;
    $after_total_loan_percent5 = $total_loan_percent5*0.05;
    $totalint = $after_total_loan_percent1 +$after_total_loan_percent2+$after_total_loan_percent3+$after_total_loan_percent4+$after_total_loan_percent5;


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
									WHERE late_interest_record.bd_date <'".$date_from."'
									GROUP BY late_interest_record.loan_code
									ORDER BY late_interest_record.bd_date ASC");

    					$totalamount_collected_desc=0;
														
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

							$totalpayout=0;
							$totalreturn=0;
							$totalbaddebt=0;
							$payout =0;
						
						while($result_monthly = mysql_fetch_assoc($sql_monthly))
						{ 

							if($result_monthly['status']=='FINISHED')
							{
								$return=$result_monthly['PA']-$result_monthly['balance'];
								$baddebt=$result_monthly['balance'];
								$cash_balance+=(($result_monthly['PA']-$result_monthly['balance'])*0.1);

							}
							else if($result_monthly['status']=='PAID')
							{
								$return='0.00';
								$baddebt='0.00';
								$cash_balance+=0;
							}else if($result_monthly['status']=='BAD DEBT')
							{
								$return='0.00';
								$baddebt=$result_monthly['PA'];
								$cash_balance-=($result_monthly['PA']-($result_monthly['PA']*0.1));

							}

								if($result_monthly['status']=='BAD DEBT')
							{
								$style = "style='color:#FF0000'";
							}else
							{
								$style = " ";	
							}

							$totalpayout+=$result_monthly['PA'];
							$totalreturn+=$return;
							$totalbaddebt+=$baddebt;
							$payout +=($result_monthly['PA']-($result_monthly['PA']*0.1));

						}
						$sql_expenses = mysql_query("SELECT * FROM expenses WHERE date BETWEEN '".$date_from."' AND '".$date_to."'");
						$totalexpenses=0;
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
						$totalamount_instalment_desc=0;
														
						while($result_thismonth_bd = mysql_fetch_assoc($sql_thismonth_bd))
						{ 
							$totalamount_instalment_desc += $result_thismonth_bd['amount'];
						}


						$closing_balance = $totalcollected + $totalsettle + $totalamount_collected_desc + $cash_balance - $totalout - $totalexpenses;

						?>
						<tr>
				<td style="text-align:center;border-left:1px solid;border-right:1px solid;border-bottom: 1px solid;"><?php echo date('M-Y',strtotime($month_receipt));?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalpayout);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_loan_percent1);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_loan_percent2);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_loan_percent3);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_loan_percent4);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_loan_percent5);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalpayout*0.1);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent1);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent2);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent3);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent4);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent5);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;">0</td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalexpenses);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalreturn);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_return_percent1-$total_baddebt_amount1);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_return_percent2-$total_baddebt_amount2);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_return_percent3-$total_baddebt_amount3);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_return_percent4-$total_baddebt_amount4);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_return_percent5-$total_baddebt_amount5);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalbaddebt);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_baddebt_amount1);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_baddebt_amount2);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_baddebt_amount3);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_baddebt_amount4);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_baddebt_amount5);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($totalamount_collected_desc);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent1+$after_total_loan_percent2+$after_total_loan_percent3+$after_total_loan_percent4+$after_total_loan_percent5-$totalexpenses-$total_baddebt_amount1-$total_baddebt_amount2-$total_baddebt_amount3-$total_baddebt_amount4-$total_baddebt_amount5+$totalamount_collected_desc);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
			</tr>
			<tr style="background-color:#b2ffff;">
				<td style="text-align:center;border-left:1px solid;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid;"><b>SALES </b>= </td>
				<td colspan="4" style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($total_loan_percent1+$total_loan_percent2+$total_loan_percent3+$total_loan_percent4+$total_loan_percent5);?></td>
				<td colspan="2" style="text-align:center;border-bottom: 1px solid;"><b>INTEREST</b> =</td>
				<td colspan="4" style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format($after_total_loan_percent1+$after_total_loan_percent2+$after_total_loan_percent3+$after_total_loan_percent4+$after_total_loan_percent5);?></td>
				<td colspan="2" style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><b>CUSTOMER </b>= <?php echo $ctr;?></td>
				<td colspan="4" style="text-align:center;border-bottom: 1px solid;"><b>LOAN OUT/ PERSON</b> = </td>
				<td colspan="2" style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"><?php echo number_format(($total_loan_percent1+$total_loan_percent2+$total_loan_percent3+$total_loan_percent4+$total_loan_percent5)/$ctr);?></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
				<td colspan = "2" style="text-align:center;border-bottom: 1px solid;"><b>CASH BALANCE</b> =</td>
				<td style="text-align:center;border-right:1px solid;border-bottom: 1px solid;"></td>
			</tr><?php }}?>
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