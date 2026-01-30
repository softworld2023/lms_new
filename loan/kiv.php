<?php 
include('../include/page_header.php'); 

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_status = 'KIV' AND branch_id = '".$_SESSION['login_branchid']."'");
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
#approve_loan2
{
	background:url(../img/approval/approve-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#approve_loan2:hover
{
	background:url(../img/approval/approve-btn-roll-over.jpg);
	height:30px;
	width:109px;
	border:none;
}
#reject_loan2
{
	background:url(../img/approval/reject-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#reject_loan2:hover
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
</style>
<center>
<form action="action.php" method="post" onsubmit="return checkForm()">
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/approval/approval.png"></td>
        <td>Approval</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Waiting</a><a href="approved.php">Approved</a><a href="rejected.php">Rejected</a><a href="kiv.php" id="active-menu">KIV</a>
		</div>	
        </td>
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
    	<th>No.</th>
        <th>Name</th>
        <th>I.C Number</th>
        <th>Income</th>
        <th>Loan Amount</th>
        <th>Approval Amount</th>
        <th style="padding:0px"><center>Approval</center></th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$get_q['customer_id']."'");
	$get_c = mysql_fetch_assoc($cust_q);
	
	$financial_q = mysql_query("SELECT * FROM customer_financial WHERE customer_id = '".$get_q['customer_id']."'");
	$get_f = mysql_fetch_assoc($financial_q);
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_c['name']; ?></td>
        <td><?php echo $get_c['nric']; ?></td>
        <td><?php echo "RM ".$get_f['net_salary']; ?></td>
        <td><?php echo "RM ".$get_q['loan_amount']; ?></td>
        <td><?php echo "RM ".$get_q['loan_amount']; ?></td>
        <td>
        	<center>
            <input type="hidden" name="loan_id_<?php echo $ctr; ?>" id="loan_id_<?php echo $ctr; ?>" value="<?php echo $get_q['id']; ?>" />
            <?php
				$approval_q = mysql_query("SELECT * FROM approval_level WHERE approved_by = '".$_SESSION['login_level']."'");
				$get_app = mysql_fetch_assoc($approval_q);
				
				$approval2_q = mysql_query("SELECT * FROM approval_level WHERE amount < '".$get_app['amount']."'");
				$get_app2 = mysql_fetch_assoc($approval2_q);
				
				if($get_app)
				{
					if($_SESSION['login_level'] != 'Boss')
					{
						if($get_app2)
						{
							if(($get_q['loan_amount'] < $get_app['amount']) && ($get_q['loan_amount'] >= $get_app2['amount']))
							{
				?>
								<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" onclick="check()" value="Approved" />
				<?php
							}
							else
							{
				?>
								<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" disabled="disabled" />
				<?php
							}
						}else
						{
							if($get_q['loan_amount'] < $get_app['amount'])
							{
			?>
								<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" onclick="check()" value="Approved" />
			<?php
							}else
							{
			?>
								<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" disabled="disabled" />
			<?php
							}
						}
					}else
					{
						if($get_q['loan_amount'] >= $get_app['amount'])
						{
			?>
							<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" onclick="check()" value="Approved" />
            <?php
						}
						else
						{
			?>
            				<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" disabled="disabled" />
            <?php
						}
					}
				}else
				{
			?>
            	<input type="checkbox" id="approved_<?php echo $ctr; ?>" name="approved_<?php echo $ctr; ?>" disabled="disabled" />
            <?php
				}
			?>
            </center>
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="7" align="right"><input type="submit" id="approve_loan2" name="approve_loan2" value="">&nbsp;&nbsp;&nbsp;<input type="submit" name="reject_loan2" id="reject_loan2" value="">
        &nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../home/'" value="">
        </td>
    </tr>
</table>
<input type="hidden" name="check_app" id="check_app" value="0" /><input type="hidden" name="ctr" id="ctr" value="<?php echo $ctr; ?>" />
</form>
</center>
<script>
function check()
{
	$counter = $("#ctr").val();
	$total_amount = 0;
	
	for($i = 1; $i <= $counter; $i++)
	{
		if($('#approved_'+$i).is(':checked'))
		{
			$no = 1;
			$total_amount+= $no;
		}else
		{
			$total_amount;
		}
	}
	document.getElementById('check_app').value = $total_amount;
}

function checkForm()
{
	if((document.getElementById('check_app').value != '0'))
	{
		$('#message').empty();
		return true;	
	}
	else
	{
		$('html, body').animate({scrollTop:150}, 'fast');
		var msg = "<div class='error'>You must select atleast ONE (1) loan from the list!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}
</script>