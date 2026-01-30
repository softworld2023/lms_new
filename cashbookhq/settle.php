<?php 
include('../include/page_headercb.php'); 


$cond = '';


if(isset($_POST['search']))
{
	$_SESSION['csbranch'] = $_POST['sbranch'];
	if($_POST['sbranch'] != '')
	{
		
		$cond .= " and branch_id = '".$_POST['sbranch']."'";
		$sql = mysql_query("SELECT * FROM customer_details WHERE 1 $cond ORDER BY LPAD(customercode2, 20, '0')");
	}	
	
	if($_POST['datefrom'] != '')
	{
		$_SESSION['dosf'] = $_POST['datefrom'];
	}else
	{
		$_SESSION['dosf'] = '';
	}
	
	if($_POST['dateto'] != '')
	{
		$_SESSION['dost'] = $_POST['dateto'];
	}else
	{
		$_SESSION['dost'] = '';
	}
}else
if($_SESSION['csbranch'] != '')
{
	$cond .= " and branch_id = '".$_SESSION['csbranch']."'";
	$sql = mysql_query("SELECT * FROM customer_details WHERE 1 $cond ORDER BY LPAD(customercode2, 20, '0')");
}




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
        <td>Customer Settle   Report</td>
        <td align="right">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
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
                    <option value=""></option>
                    <?php 
							$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'");
							while($branch = mysql_fetch_assoc($branch_q)){
							?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch['branch_id'] == $_SESSION['csbranch']) { echo 'selected'; } ?>><?php echo $branch['branch_name']; ?></option>
                    <?php } ?>
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
	$ctr=0;
		
	if($_SESSION['csbranch'] != '')
	{
	while($get_q = mysql_fetch_assoc($sql)){ 
	//loan
	$loanp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' AND (loan_status = 'Finished' OR loan_status = 'Paid') GROUP BY loan_status");
	$loanrow = mysql_num_rows($loanp_q);
	$loanp = mysql_fetch_assoc($loanp_q);
	
	if($loanrow == '1' && $loanp['loan_status'] == 'Finished')
	{
	//branch 
	$branchq = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_q['branch_id']."'");	
	$gbranch = mysql_fetch_assoc($branchq);
	
	$lpd_q2 = mysql_query("SELECT * FROM customer_loanpackage clp, loan_payment_details lpd WHERE clp.customer_id = '".$get_q['id']."' AND lpd.customer_loanid = clp.id AND lpd.balance != 0 AND lpd.payment_date != '0000-00-00' ORDER BY lpd.payment_date DESC");
	$lpd2 = mysql_fetch_assoc($lpd_q2);	
	
	if($_SESSION['dosf'] != '' && $_SESSION['dost'] == '')
	{
		$datecheck = date('Y-m-d', strtotime($_SESSION['dosf']));
		
		if($lpd2['payment_date'] >= $datecheck)
		{
		$ctr++;
	?>
	<tr id="grid">
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
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
	
	if($_SESSION['dosf'] == '' && $_SESSION['dost'] != '')
	{
		$datecheck = date('Y-m-d', strtotime($_SESSION['dost']));
		
		if($lpd2['payment_date'] <= $datecheck)
		{
		$ctr++;
	?>
	<tr id="grid">
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
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
        <td><?php echo date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
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
	
	if($_SESSION['dosf'] == '' && $_SESSION['dost'] == '')
	{
	$ctr++;
	?>
    <tr id="grid">
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date ('d-m-Y', strtotime($lpd2['payment_date']));?></td>
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
    <?php }
	} 
	}
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