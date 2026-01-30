<?php
session_start();
include('../include/page_headercb3.php'); 


//declare something I don't know..
$cond = '';

if(isset($_POST['search']))
{
	if($_POST['sbranch'] != '')//if user selected a branch (not empty)
	{
		$_SESSION['csbranch'] = $_POST['sbranch'];
		
		//First we define sql to selecting database!
		$db = mysql_query("SELECT db_name,branch_id FROM branch WHERE branch_id = '".$_POST['sbranch']."'");
		//Then we run the query
		while($db_q = mysql_fetch_assoc($db)){
			//Then we got the results
			$db_q_dbid = $db_q['branch_id'];
			$db_q_dbname = $db_q['db_name'];
		}	
		
		$conn_database = $db_q_dbname;
		$_SESSION['login_database_specific'] = $conn_database;
		$_SESSION['login_branchid'] = $db_q_dbid;
	}
	
	if($_SESSION['csbranch'] == '')
	{
		$_SESSION['login_database_specific'] = "loansystem";
		$_SESSION['login_branchid'] = 13;
		echo '<p align="center">-------------EMPTY BRANCH!-------------</p></br>';
		$_SESSION['csbranch'] = '';
		echo '<p align="center">';
		//print_r($_SESSION);
		echo '</p>';
		//break;
	}
	
	if($_POST['datefrom'] != '')// if user have add any date...
	{
		$_SESSION['dosf'] = $_POST['datefrom'];
	}
	else//if user didn't add any date, the dosf save NOTHING!
	{
		$_SESSION['dosf'] = '';
	}
	
	if($_POST['dateto'] != '') // if user have add any date...
	{
		$_SESSION['dost'] = $_POST['dateto'];
	}
	else//else the dost save NOTHING!
	{
		$_SESSION['dost'] = '';
	}
}

/*else if(isset($_POST['search']))
{
	if($_SESSION['csbranch'] == '')
	{
		//$cond .= " and branch_id = '".$_SESSION['csbranch']."'";
		//$cond .= " and branch_id = '1'";
		//this database's table have 2 records currently
		//WHERE 1 = ALWAYS TRUE!
		//$_SESSION['csbranch'] = ''; from page_headercb.php
		//$sql = mysql_query("SELECT * FROM customer_details WHERE 1 $cond ORDER BY LPAD(customercode2, 20, '0')");
		echo '---------EMPTY BRANCH!!';
	}
}*/

// ****** paging UNKNOWN FUNCTION****** 
// page is the current page, if there's nothing set, default is page 1
if(isset($_POST['currentPage']))
{
	$page = isset($_GET['page']) ? $_GET['page'] : $_POST['currentPage'];
	//$page = isset($_GET['page']) ? $_GET['page'] : 1;
}
else
{
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
}
// ****** paging UNKNOWN FUNCTION******

$recordsPerPage = 100;
 
// calculate for the query LIMIT clause
$fromRecordNum = ($recordsPerPage * $page) - $recordsPerPage;
$startno = $fromRecordNum + 1; 

?>

<style>
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
	
	
}

#list_table tr th
{
	height:36px;
	background:#666;
	text-align:left;
	padding-left:10px;
	color:#FFF;
	border:thin solid #333;
	
}
#list_table tr td
{
	height:35px;
	padding-left:10px;
	padding-right:10px;
	border:thin solid #333;
	
}

#rl
{
	width:318px;
	height:36px;
	background:url(../img/customers/right-left.jpg);
}
#approve_loan
{
	background:url(../img/approval/approve-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#approve_loan:hover
{
	background:url(../img/approval/approve-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
}
#reject_loan
{
	background:url(../img/approval/reject-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#reject_loan:hover
{
	background:url(../img/approval/reject-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
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
#print_btn
{
	background:url(../img/print-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#print_btn:hover
{
	background:url(../img/print-btn-roll.jpg);
}
#search
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search:hover
{
	background:url(../img/enquiry/search-btn-roll-over.jpg);
}
@media print {	
	.subnav { display:none; }
	#hideprint { display:none; }
	#back { display:none; }
	#message { display:none; }
	#list_table
	{
		width:1000px;
	}
}
#grid td
{
	border-left:thin solid #333;
	border-right:thin solid #333;
	border-bottom:thin solid #333;
	border-top:thin solid #333;
}
#grid th
{
	border-left:thin solid #333;
	border-right:thin solid #333;
	border-bottom:thin solid #333;
	border-top:thin solid #333;
}
#grid2
{
	background:#CCCCCC;
	border-bottom:double #333;
}
</style>
<center>
<table width="1280" id="hideprint">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Customer Settle Report</td>
        <td align="right">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
		<!--This is the little menu above! -->
			<a href="report.php" >HQ Cashbook Daily</a>
			<a href="customerChannnel.php">Customer Channel</a>
			<a href="loanstatus.php">Loan Status</a>
			<a href="settle.php" id="active-menu">Customer Settle Report</a>
		</div>
        </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="3">
		<form action="" method="post">
        	<table>
            	<tr>
            	  <td style="padding-right:30px">Branch<br /></td>
           	      <td style="padding-right:30px"><select name="sbranch" id="sbranch" style="height:30px">
                    <option value="">ALL</option>
						<?php
							//DROP DOWN options
							$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'");
							
							while($branch = mysql_fetch_assoc($branch_q))
							{
						?>
								<option value="<?php echo $branch['branch_id']; ?>" 
												<?php if($branch['branch_id'] == $_SESSION['csbranch'])
														{
															echo 'selected'; 
														} ?>>
									<?php echo $branch['branch_name']; ?>
								</option>
						<?php
							}
						?>
						
                  </select>
				  </td>
				  <td align="right" style="padding-right:10px">Date From </td>
                  <td style="padding-right:30px"><input type="text" name="datefrom" id="datefrom" style="height:30px; width:100px" value="<?php echo $_SESSION['dosf']; ?>" /></td>
				  <td align="right" style="padding-right:10px">To</td>
                  <td style="padding-right:30px"><input type="text" name="dateto" id="dateto" style="height:30px; width:100px" value="<?php echo $_SESSION['dost']; ?>" /></td>
       	          <td style="padding-right:30px"><span style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></span></td>
				</tr>
            </table>
        </form>
		</td>
    </tr>
</table>
<br />
<table width="1280" id="list_table">
	<tr id="grid">
    	<th width="50">No.</th>
        <th> Date of Settlement </th>
        <th>Branch</th>
        <th width="150">Cust Code</th>
        <th>Customer Name</th>
        <th>Loan Code </th>
    </tr>
	
    <?php 
		//Declare No. here
		$ctr=0;
		
		//if user selected a branch from the drop down menu...
		if($_SESSION['csbranch'] != '')
		{			
			$sql = mysql_query("SELECT * FROM customer_details WHERE 
								branch_id = '".$_POST['sbranch']."' 
									ORDER BY id");
									
			while($get_q = mysql_fetch_assoc($sql))
			{//apahal tak dapat run here for first time??????????
				/*echo $get_q['name'];
				echo '<br/>';*/
				$loanp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' 
										AND loan_status = 'Finished'");
				$loanrow = mysql_num_rows($loanp_q);
				$loanp = mysql_fetch_assoc($loanp_q);				
			
				//Illegal IF
				if(/*$loanrow == '1'&& */$loanp['loan_status'] == 'Finished')
				{
					$branchq = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_q['branch_id']."'");
					$gbranch = mysql_fetch_assoc($branchq);
						
					$lpd_q2 = mysql_query("SELECT * FROM customer_loanpackage clp, loan_payment_details lpd 
											WHERE clp.customer_id = '".$get_q['id']."' 
											AND lpd.customer_loanid = clp.id 
											AND lpd.balance != 0 
											AND lpd.payment_date != '0000-00-00' 
											ORDER BY lpd.payment_date DESC");

					$lpd2 = mysql_fetch_assoc($lpd_q2);
					
					//The second IF
					/*if($_SESSION['dosf'] != '' && $_SESSION['dost'] == '')
					{
						$datecheck = date('Y-m-d', strtotime($_SESSION['dosf']));
						
						//The third If
						if($lpd2['payment_date'] >= $datecheck)
						{
							//$ctr++;
	?>
	<tr id="grid">
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo 'test_1';//date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
        <td><?php echo $get_q['branch_name']; ?></td>
        <td><!--<a href="../report/history.php?id=<?php //echo $get_q['id']; ?>" rel="shadowbox">--><?php echo $get_q['customercode2']; ?></a></td>
        <td><?php echo $get_q['name']; ?></td>
        <td><!--<a href="../report /history.php?id=<?php echo 'test';//$get_q['id']; ?>" rel="shadowbox">-->
			
			
	<?php
		//Kosong punya
		$loan_code_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."'
									AND (loan_status = 'Finished' OR loan_status = 'Paid')");
		$lcrow = mysql_num_rows($loan_code_q);
		$count = 0;
		
		while($loan_code = mysql_fetch_assoc($loan_code_q))
		{
			$count++;
			$loancode = $loan_code['loan_code'];
				
			if($loancode == '0')
			{
				$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_code['id']."' AND receipt_no != '0' ORDER BY id DESC");
				$lpd = mysql_fetch_assoc($lpd_q);
				$loancode = $lpd['receipt_no'];
			}
			
			echo $loancode;
			
			if($count < $lcrow)
			{
				echo ", ";
			}				
		}		
	?>
			<!--</a>-->
		</td>
    </tr>
	
	<?php
		}
	}*/
//}//Ends of Illegal IF(1)
	
	
	
	//echo "Hello";
	if($_SESSION['dosf'] == '' && $_SESSION['dost'] != '')
	{
		$datecheck = date('Y-m-d', strtotime($_SESSION['dost']));
		
		if($lpd2['payment_date'] <= $datecheck)
		{
			$ctr++;
	?>
	
	<tr id="grid">
		<?php
			/*<td><?php echo $ctr."."; ?></td>
			<td><?php echo 'test_1';//date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
			<td><?php echo $get_q['branch_name']; ?></td>
			<td><!--<a href="../report/history.php?id=<?php //echo $get_q['id']; ?>" rel="shadowbox">-->
				<?php echo $get_q['customercode2']; ?></a></td>
			<td><?php echo $get_q['name']; ?></td>
			<td><!--<a href="../report /history.php?id=<?php echo 'test';//$get_q['id']; ?>" rel="shadowbox">-->*/
		?>
		
    	<td><?php echo $ctr."."; ?></td>		
        <td><?php echo 'Test 2';//date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
        <td><?php echo $gbranch['branch_name']; ?></td>
        <td><a href="../report/history.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox"><?php echo strtoupper($get_q['customercode2']); ?></a></td>
        <td><?php echo $get_q['name']; ?></td>
        <td><a href="../report /history.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox">
			<?php $loan_code_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' AND (loan_status = 'Finished' OR loan_status = 'Paid')");
				$lcrow = mysql_num_rows($loan_code_q);
				$count = 0;
				while($loan_code = mysql_fetch_assoc($loan_code_q))
				{
					$count++;
					$loancode = $loan_code['loan_code'];
						
					if($loancode == '0')
					{
						$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_code['id']."' AND receipt_no != '0' ORDER BY id DESC");
						$lpd = mysql_fetch_assoc($lpd_q);
						$loancode = $lpd['receipt_no'];
					}
					
					echo $loancode;
					if($count < $lcrow)
					{
						echo ", ";
					}
				}		
			?></a>
		</td>
    </tr>
	<?php
		}
	}
	
	if($_SESSION['dosf'] != '' && $_SESSION['dost'] != '')
	{
		$datecheck = date('Y-m-d', strtotime($_SESSION['dosf']));
		$datecheck2 = date('Y-m-d', strtotime($_SESSION['dost']));
		
		if($lpd2['payment_date'] >= $datecheck && $lpd2['payment_date'] <= $datecheck2)
		{
		$ctr++;
	?>
	<tr id="grid">
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo 'Test 3';//date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
        <td><?php echo $gbranch['branch_name']; ?></td>
        <td><a href="../report/history.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox"><?php echo strtoupper($get_q['customercode2']); ?></a></td>
        <td><?php echo $get_q['name']; ?></td>
        <td><a href="../report /history.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox">
			<?php $loan_code_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' AND (loan_status = 'Finished' OR loan_status = 'Paid')");
				$lcrow = mysql_num_rows($loan_code_q);
				$count = 0;
				while($loan_code = mysql_fetch_assoc($loan_code_q))
				{
					$count++;
					$loancode = $loan_code['loan_code'];
						
					if($loancode == '0')
					{
						$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_code['id']."' AND receipt_no != '0' ORDER BY id DESC");
						$lpd = mysql_fetch_assoc($lpd_q);
						$loancode = $lpd['receipt_no'];
					}
					
					echo $loancode;
					if($count < $lcrow)
					{
						echo ", ";
					}
				}
			?></a>
		</td>
    </tr>
	
	<?php
		}
	}
	//dosf = date 1
	//dost = date 2
	//if date 1 and date 2 isn't filled...
	//it will go here!!!
	if($_SESSION['dosf'] == '' && $_SESSION['dost'] == '')
	{
		$ctr++;
	?>
		<tr id="grid">
			<td><?php echo $ctr."."; ?></td>
			<td><?php echo date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
			<td><?php echo $gbranch['branch_name']; ?></td>
			<td><a href="../report/history.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox">
					<?php echo strtoupper($get_q['customercode2']); ?></a></td>
			<td><?php echo $get_q['name']; ?></td>
			<td><a href="../report/history.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox">
				<?php
					//this code shows the LOAN CODE 
					$loan_code_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."'
												AND (loan_status = 'Finished' OR loan_status = 'Paid')");

					$lcrow = mysql_num_rows($loan_code_q);
					$count = 0;
					while($loan_code = mysql_fetch_assoc($loan_code_q))
					{
						$count++;
						$loancode = $loan_code['loan_code'];
							
						if($loancode == '0')
						{
							$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_code['id']."'
													AND receipt_no != '0' ORDER BY id DESC");

							$lpd = mysql_fetch_assoc($lpd_q);
							$loancode = $lpd['receipt_no'];
						}
						
						echo $loancode;
						if($count < $lcrow)
						{
							echo ", ";
						}					
					}			
				?></a>
			</td>
		</tr>
    <?php 
		}
				}//end of illegal If
			}//end of big while loop
		}
		
		else if($_SESSION['csbranch'] == ''){
		echo 'hello';
		$sql_all = mysql_query("
					SELECT * FROM ktl.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_tsh.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_grandtip.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_setplan.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_shah_alam.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_jiningo.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_wellstart.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_success.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_maju_jaya.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_barcelona.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_libyee.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_dasia.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_maju_kajang.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_city.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_bayu.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_jaya_kaya.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_bengen.customer_loanpackage WHERE loan_status = 'Finished' UNION
					SELECT * FROM lskl_cap_phillo.customer_loanpackage WHERE loan_status = 'Finished'
					LIMIT {$fromRecordNum}, {$recordsPerPage}
					");
					
		while($get_sql_all = mysql_fetch_assoc($sql_all)){
			$ctr++;
			//Just wants to make sure only the branch available in customer_loanpackage
			//is selected.
			$branchq = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_sql_all['branch_id']."'");	
			$gbranch = mysql_fetch_assoc($branchq);
			// ++++++++++ //
			$cust_q = mysql_query(
						"SELECT * FROM ktl.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_tsh.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_grandtip.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_setplan.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_shah_alam.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_jiningo.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_wellstart.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_success.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_maju_jaya.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_barcelona.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_libyee.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_dasia.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_maju_kajang.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_city.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_bayu.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_jaya_kaya.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_bengen.customer_details WHERE id = '".$get_sql_all['customer_id']."' UNION
						SELECT * FROM lskl_cap_phillo.customer_details WHERE id = '".$get_sql_all['customer_id']."'
						");
			$get_cust_q = mysql_fetch_assoc($cust_q);
			
			$lpd_q = mysql_query("SELECT * FROM  ktl.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_tsh.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_grandtip.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_setplan.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_shah_alam.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_jiningo.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_wellstart.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_success.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_maju_jaya.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_barcelona.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_libyee.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_dasia.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_hq.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_maju_kajang.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_city.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_bayu.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_jaya_kaya.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_bengen.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' UNION
						SELECT * FROM  lskl_cap_phillo.loan_payment_details WHERE customer_loanid = '".$get_sql_all['id']."' 
						");
						$lpd = mysql_fetch_assoc($lpd_q);
						
						$lcode = $lpd['receipt_no'];
		
		
		
		
	?>
		<tr id="grid">
    	<td><?php echo $startno."."; ?></td>
        <td><?php echo date ('d-m-Y', strtotime($lpd['payment_date']));?></td>
        <td><?php echo $gbranch['branch_name']; ?></td>
        <td><?php echo $get_cust_q['customercode2']; ?></td>
        <td><?php echo $get_cust_q['name']; ?></td>
        <td><a href="../report /history.php?id=<?php echo $get_sql_all['id']; ?>" rel="shadowbox">
			<?php $loan_code_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_sql_all['id']."' AND (loan_status = 'Finished')");
				$lcrow = mysql_num_rows($loan_code_q);
				$count = 0;
				while($loan_code = mysql_fetch_assoc($loan_code_q))
				{
					$count++;
					$loancode = $loan_code['loan_code'];
						
					if($loancode == '0')
					{
						$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan_code['id']."' AND receipt_no != '0' ORDER BY id DESC");
						$lpd = mysql_fetch_assoc($lpd_q);
						$loancode = $lpd['receipt_no'];
					}
					
					echo $loancode;
					if($count < $lcrow)
					{
						echo ", ";
					}					
				}			
			?></a>
		</td>
    </tr>
	
	<?php
		$startno++;
		
		}
	?>
    
    <tr>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" style="border:none">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right" style="border:none"><input type="button" name="back" id="back" onClick="window.location.href='../cashbookhq/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6" style="border:none">&nbsp;</td>
    </tr>
	
<?php
	 
    // *************** <PAGING_SECTION> ***************
		
		// ********** show the number paging
		
		// find out total pages
        //$sql = "SELECT COUNT(*) as total_rows FROM loan_goldenone.customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond";
		
		$sql_num = mysql_query("
						  SELECT * FROM ktl.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_tsh.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_grandtip.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_setplan.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_shah_alam.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_jiningo.customer_loanpackage  WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_wellstart.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_success.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_maju_jaya.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_barcelona.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_libyee.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_dasia.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_hq.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_maju_kajang.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_city.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_bayu.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_jaya_kaya.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_bengen.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond
					UNION SELECT * FROM lskl_cap_phillo.customer_loanpackage WHERE loanpackagetype != '' AND loan_status = 'Finished' $cond");
		
		//get the number of the row
		$total_rows = mysql_num_rows($sql_num);
		
        //$get_list = mysql_fetch_assoc($rs);
		
        //$total_rows = $get_list['total_rows'];
 
		//this round up the number!
        $total_pages = ceil($total_rows / $recordsPerPage);
 
        // range of num links to show
        $range = 4;
		
        // display links to 'range of pages' around 'current page'
        //$initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
		?>
			&nbsp;
		
		<?php
		
		//$currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 2;
		//$currPage = $_POST['currentPage'];
		
		echo "<center><table border='0'  class='customBtn'>";
		echo "<tr>";
		
		if($currPage = isset($_POST['currentPage']))
		{
			$page=$_POST['currentPage'];
			$initial_num = $page - $range;
		}
		else
		{
			$initial_num = $page - $range;
		}
		
		//make a straight blanks space
		echo "&nbsp;";
		 // ***** for 'previous' pages
        if($page>1){
            // ********** show the previous page
            $prev_page = $page - 1;
            echo "<td><a href='" . $_SERVER['PHP_SELF'] 
                    . "?page={$prev_page}' title='Previous page is {$prev_page}.'>";
                echo "<span style='margin:0 .5em;'> <  </span>";
            echo "</a></td>";
        }
		else
		{
			$prev_page = $page;
            echo "<td><a href='" . $_SERVER['PHP_SELF'] 
                    . "?page={$prev_page}' title='Previous page is {$prev_page}.'>";
                //echo "<span style='margin:0 .5em;'> < </span>";
            echo "</a></td>";
		}
		echo "&nbsp;";
		for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $total_pages)) {
             
                // current page
                if ($x == $page) {
                    echo "<td>&nbsp;&nbsp;<span style='font-size:16px;color:red;font-weight:bold'>$x</span>&nbsp;&nbsp;</td>";
                } 
                 
                // not current page
                else {
                    echo " <td>&nbsp;&nbsp;<a href='{$_SERVER['PHP_SELF']}?page=$x' >$x</a> &nbsp;&nbsp;</td>";
                }
            }
        }
		
		echo "&nbsp;";
		// ***** for 'next' 
		if($page<$total_pages){
			// ********** show the next page
			$next_page = $page + 1;
			echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?page={$next_page}' title='Next page is {$next_page}.'";
			echo "<span style='margin:0 .5em;'> > </span>";
			echo "</a></td>";
		}
		else
		{
			$next_page = $page;
			echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?page={$next_page}' title='Next page is {$next_page}.' >";
			//echo "<span style='margin:0 .5em;'> >  </span>";
			echo "</a></td>";
		}
		echo "&nbsp;";
        for ($x=$initial_num; $x<$condition_limit_num; $x++) {
             
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
			
            if (($x > 0) && ($x <= $total_pages)) {
				// ***** allow user to enter page number
                // current page
                if ($x == $page) {
					echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
					echo "<td><input type='text' size='1' name='currentPage' value='$x' autocomplete='off' style='text-align:center'/> of $total_pages ";
					
					?>&nbsp;&nbsp;<?php	
					echo "<input type='hidden' name='submit' value='Go to'></td>";
					?><?php
					echo "</form>";
					
                } 
				
            }
        }		
		echo "</tr></table>";
		echo "</div>";
   
		// *************** </PAGING_SECTION> 
}else{
    echo "<tr><td colspan='8' style='border:none;' align='center'>No records found.</td></tr>";
}?>

</table>
</center>
<script>
function deleteConfirmation(date, name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this transaction: ' + name + ' (' + date + ')?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_trans',
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
$('#year').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y ", labelTitle: "Select Date"}).focus(); } ).
keydown(
	function(e)
	{
		var key = e.keyCode || e.which;
		if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
		{
			$v = this;
			$($v).off('keydown').AnyTime_picker().focus();
			e.preventDefault();
		}
	} );
$('#datefrom').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).					
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
$('#dateto').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", labelTitle: "Select Date"}).focus(); } ).
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

Shadowbox.init();
</script>
</script>