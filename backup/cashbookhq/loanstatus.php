<?php 
include('../include/page_headercb.php'); 

$cond = '';
if(isset($_POST['search']))
{
	$_SESSION['sbranch'] = $_POST['sbranch'];
	if($_POST['sbranch'] != '')
	{
		
		$cond .= " and branch_id = '".$_POST['sbranch']."'";
	}
	
	$_SESSION['package'] = $_POST['lpackage'];
	if($_POST['lpackage'] != '')
	{
				$cond .= " and loan_package = '".$_POST['lpackage']."'";
	}
	
	if($_POST['year'] != '')
	{
		$_SESSION['lsdf'] = '';
		$_SESSION['lsdt']  = '';
		$year = substr($_POST['year'], 0, -1);
		$_SESSION['lsyear'] = $year;
		$cond .= " and payout_date LIKE '%".$year."%'";
	}else
	{
		$_SESSION['lsyear'] = '';
		if($_POST['datefrom'] != '')
		{
			$_SESSION['lsdf'] = $_POST['datefrom'];
			
			$cond .= " and payout_date >= '".date('Y-m-d', strtotime($_POST['datefrom']))."'";
		}else
		{
			$_SESSION['lsdf'] = '';
		}
		
		if($_POST['dateto'] != '')
		{
			$_SESSION['lsdt'] = $_POST['dateto'];
			$cond .= " and payout_date <= '".date('Y-m-d', strtotime($_POST['dateto']))."'";
		}else
		{
			$_SESSION['lsdt'] = '';
		}
	}
	
	
}


$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE loanpackagetype != '' AND (loan_status = 'Paid' OR loan_status = 'Finished') $cond");

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
        <td>Loan Status  Report</td>
        <td align="right">&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="report.php">HQ Cashbook Daily</a><a href="loanstatus.php" id="active-menu">Loan Status</a><a href="settle.php">Customer Settle Report</a>
		</div>	
        </td>
    </tr>
	<tr>
    	<td colspan="3"><div id="message" style="width:1280px; text-align:left">
	<?php
	if($_SESSION['msg'] != '')
	{
		echo $_SESSION['msg'];
		$_SESSION['msg'] = '';
	}
	?>
	</div>&nbsp;</td>
    </tr>
	<tr>
    
    	<td colspan="3">
		<form action="" method="post">
        	<table>
            	<tr>
            	  <td style="padding-right:30px">Branch<br />&nbsp;</td>
           	      <td colspan="9" style="padding-right:30px"><select name="sbranch" id="sbranch" style="height:30px">
                    <option value="">ALL</option>
                    <?php 
							$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'");
							while($branch = mysql_fetch_assoc($branch_q)){
							?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch['branch_id'] == $_SESSION['sbranch']) { echo 'selected'; } ?>><?php echo $branch['branch_name']; ?></option>
                    <?php } ?>
                  </select><br />&nbsp;</td>
       	      </tr>
            	<tr>
					<td align="right" style="padding-right:10px">Date From </td>
                    <td style="padding-right:30px"><input type="text" name="datefrom" id="datefrom" style="height:30px; width:100px" value="<?php echo $_SESSION['lsdf']; ?>" /></td>
					<td align="right" style="padding-right:10px">To</td>
                    <td style="padding-right:30px"><input type="text" name="dateto" id="dateto" style="height:30px; width:100px" value="<?php echo $_SESSION['lsdt']; ?>" /></td>
					<td align="right" style="padding-right:10px">Year</td>
                    <td style="padding-right:30px"><input type="text" name="year" id="year" style="height:30px; width:80px" maxlength="4" value="<?php echo $_SESSION['lsyear']; ?>" /></td>
                	<td align="right" style="padding-right:10px">Package</td>
                    <td style="padding-right:30px">
						<select name="lpackage" id="lpackage" style="height:30px;">
							<option value="">ALL</option>
							<?php $packageq = mysql_query("SELECT * FROM loan_scheme WHERE SCHEME != 'SKIM KUTU'"); 
							while($package = mysql_fetch_assoc($packageq)){
							?>
							<option value="<?php echo $package['scheme']; ?>" <?php if($_SESSION['package'] == $package['scheme']) { echo 'selected'; } ?>><?php echo $package['scheme']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
					<!--<td><button id="print_btn" onClick="window.print()"></button></td>-->
				</tr>
            </table>
        </form>
		</td>
    </tr>
</table>
<br />
<table width="1280" id="list_table">
	<tr id="grid">
    	<th>No.</th>
        <th>Application<br />Date</th>
        <th>Branch</th>
        <th>Cust<br />Code </th>
        <th>Customer Name </th>
        <th>Package</th>
        <th>Loan<br />Code</th>
        <th>New Loan<br />(RM)</th>
        <th>Reloan<br />(RM)</th>
        <th>Overlap<br />(RM)</th>
        <th>Account 2<br />(RM)</th>
        <th>Settlement Date for<br />Previous Loan</th>
        <th>Previous<br />Loan Code</th>
        <th>Total Loan<br />(RM)</th>
		<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) { ?>
        <th width="80">&nbsp;</th>
		<?php } ?>
    </tr>
	
    <?php 
	$ctr = 0;
	$tot = 0;
	$nloan = 0;
	$rloan = 0;
	$over = 0;
	$acc2 = 0;
	
	while($get_q = mysql_fetch_assoc($sql)){ 
	//branch 
	$branchq = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_q['branch_id']."'");	
	$gbranch = mysql_fetch_assoc($branchq);
	
	//customer
	$custq = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$cust = mysql_fetch_assoc($custq);
	
	$ctr++;
	//$lcode = $get_q['loan_code'];
	//if($lcode == '0')
	//{
		$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['id']."'");
		$lpd = mysql_fetch_assoc($lpd_q);
		
		$lcode = $lpd['receipt_no'];
	//}
	
	if($get_q['loanpackagetype'] == 'NEW LOAN') { $nloan += $get_q['actual_loanamt']; }
	if($get_q['loanpackagetype'] == 'RELOAN') { $rloan += $get_q['actual_loanamt']; }
	if($get_q['loanpackagetype'] == 'OVERLAP') { $over += $get_q['actual_loanamt']; }
	if($get_q['loanpackagetype'] == 'ACCOUNT 2') { $acc2 += $get_q['actual_loanamt']; }
	?>
    <tr id="grid">
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo date('d-m-Y', strtotime($get_q['payout_date'])); ?></td>
        <td><?php echo $gbranch['branch_name']; ?></td>
        <td><a href="../customers/view_customer2.php?id=<?php echo $get_q['customer_id']; ?>" rel="shadowbox"><?php echo strtoupper($cust['customercode2']); ?></a></td>
        <td><?php echo $cust['name']; ?></td>
        <td><?php echo $get_q['loan_package']; ?></td>
        <td><?php echo $lcode; ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'NEW LOAN') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'RELOAN') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'OVERLAP') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['loanpackagetype'] == 'ACCOUNT 2') { echo number_format($get_q['actual_loanamt']); } ?></td>
        <td><?php if($get_q['prev_settlementdate'] != '1970-01-01' && $get_q['prev_settlementdate'] != '0000-00-00')  { echo date('d-m-Y', strtotime($get_q['prev_settlementdate'])); } ?></td>
        <td><?php echo $get_q['prev_loancode']; ?></td>
        <td><?php echo number_format($get_q['actual_loanamt']); ?></td>
		<?php if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff')) { ?>
        <td>
       
        
			<center>
				<a href="edit_loanstatus.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;
			</center>        
		
		</td>
		<?php } ?>
    </tr>
    <?php } ?>
    
    <tr>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none"><strong>TOTAL</strong></td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none">&nbsp;</td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none"><strong><?php echo number_format($nloan); ?></strong></td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none"><strong><?php echo number_format($rloan); ?></strong></td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none"><strong><?php echo number_format($over); ?></strong></td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none"><strong><?php echo number_format($acc2); ?></strong></td>
      <td style="border:none">&nbsp;</td>
      <td style="border:none">&nbsp;</td>
      <td style="border-bottom:double #333; background:#CCC; border-left:none; border-right:none"><strong><?php $tot = $nloan + $rloan + $over + $acc2; echo number_format($tot); ?></strong></td>
    </tr>
    <tr>
    	<td colspan="14" style="border:none">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="14" align="right" style="border:none"><input type="button" name="back" id="back" onClick="window.location.href='../cashbookhq/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="14" style="border:none">&nbsp;</td>
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