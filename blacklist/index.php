<?php include('../include/page_header.php'); 

if(isset($_POST['search']))
{
	if($_POST['customer_name'] != '')
	{
		$cond .= " and name = '".$_POST['customer_name']."'";	
	}
	
	if($_POST['icno'] != '')
	{
		$cond .= " and nric = '".$_POST['icno']."'";
	}
	
	if($_POST['type'] != '')
	{
		$cond .= " and blacklisttype = '".$_POST['type']."'";
	}
	
	if($_POST['branch'] != '')
	{
		$cond .= " and branch_id = '".$_POST['branch']."'";
	}
	
	$statement = "`customer_details` WHERE blacklist = 'Yes' $cond ";
	$_SESSION['blacklist'] = $statement;
}
else
if($_SESSION['blacklist'] != '')
{
	$statement = $_SESSION['blacklist'];
}
else
{
	$statement = "`customer_details` WHERE blacklist = 'Yes' ";
}


$sql = mysql_query("SELECT * FROM {$statement}");
?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
<script type="text/javascript" src="../include/js/tinybox.js"></script>
<link href="../include/css/tinystyle.css" rel="stylesheet" type="text/css" />
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
#rel_list
{
	padding-left:15px;
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

-->
</style>

<center>

<table width="1280">
	<tr>
    	<td width="65"><img src="../img/check-blacklist/check-blacklist.png"></td>
        <td>Black List</td>
        <td align="right">
       <form action="" method="post">
        	<table>
            	<tr>
            	  <td align="right" style="padding-right:10px">Type</td>
                	<td align="right" style="padding-right:10px">
						<select name="type" id="type" style="height:30px">
							<option value=""></option>
							<option value="CTOS">CTOS</option>
							<option value="CCRIS">CCRIS</option>
						</select>					</td>
                	<td align="right" style="padding-right:10px">Branch</td>
                	<td align="right" style="padding-right:10px">
						<select name="branch" id="branch" style="height:30px">
							<option value=""></option>
							<?php $brq = mysql_query("SELECT * FROM branch WHERE branch_id != '13'");
								while($br = mysql_fetch_assoc($brq))
								{
							?>
							<option value="<?php echo $br['branch_id']; ?>"><?php echo $br['branch_name']; ?></option>
							<?php } ?>
						</select>
					</td>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">I.C. Number</td>
                    <td style="padding-right:30px"><input type="text" name="icno" id="icno" style="height:30px" /></td>
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
                </tr>
            </table>
        </form>
      </td>
    </tr>
	<tr>
    	<td colspan="4">
            <div id="message" style="width:100%;">
            <?php
            if($_SESSION['msg'] != '')
            {
                echo $_SESSION['msg'];
                $_SESSION['msg'] = '';
            }
            ?>
            </div>		</td>
    </tr>
</table>
<table width="1280" id="list_table">
	<!--<tr>
    	<td>
        <center>
           	<iframe src="http://cm1.cmctos.com.my/creditfile/Customer.do?submit=List" width="1280" height="650" style="border:none"></iframe>
        </center>
        </td>
    </tr>-->
    <tr>
    	<th width="50">No.</th>
        <th>Branch</th>
        <th width="200">Customer Name</th>
        <th>NRIC</th>
        <th>Package</th>
        <th>Total Loan</th>
        <th>Balance</th>
        <th>CTOS / CCRIS </th>
        <th>AMOUNT</th>
        <th width="200">Relatives</th>
		<th width="50"><th>
    </tr> 
    <?php
	$ctr = 0;
    while($get_q = mysql_fetch_assoc($sql))
	{
		$ctr++;
	?>
    <tr>
    	<td valign="top" style="padding-top:10px"><?php echo $ctr; ?></td>
        <td valign="top" style="padding-top:10px">
		<?php $branch_q = mysql_query("SELECT * FROM branch WHERE branch_id = '".$get_q['branch_id']."'");
		$branch = mysql_fetch_assoc($branch_q);
		echo $branch['branch_name'];
		?>
		</td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['name']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['nric'] ?></td>
        <td valign="top" style="padding-top:10px">
			<?php
				$lq = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' AND loan_status = 'Paid'");
				while($gl = mysql_fetch_assoc($lq))
				{
					echo $gl['loan_package']."<br>";
				}
			?>		</td>
        <td valign="top" style="padding-top:10px">
        	<?php 
				$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$get_q['id']."' AND loan_status = 'Paid'");
				while($get_l = mysql_fetch_assoc($loan_q))
				{
					echo "RM ".number_format($get_l['loan_total'], '2')."<br>";
				}
			?>			</td>
        <td valign="top" style="padding-top:10px">
        	<?php
				$bal_q = mysql_query("SELECT * FROM loan_payment_details pay, customer_loanpackage lp WHERE lp.customer_id = '".$get_q['id']."' AND lp.loan_status = 'Paid' AND pay.customer_loanid = lp.id AND pay.payment = 0 AND pay.payment_int = 0 GROUP BY pay.customer_loanid");
				while($get_b = mysql_fetch_assoc($bal_q))
				{
					$bal = $get_b['balance'] + $get_b['int_balance'];
					echo "RM ".number_format($bal, '2')."<br>";
				}
			?>		</td>
        <td valign="top" style="padding-top:10px"><?php echo $get_q['blacklisttype']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo "RM ".number_format($get_q['blacklistamt'], '2'); ?></td>
        <td valign="top" style="padding-top:10px">
        <?php
		$rel_q = mysql_query("SELECT * FROM customer_relative WHERE customer_id = '".$get_q['id']."'");
		$r = mysql_num_rows($rel_q);
		if($r != 0)
		{
		?>
        	<ul id="rel_list">
			<?php
				while($get_r = mysql_fetch_assoc($rel_q))
				{
				?>
				<li><?php echo $get_r['r_name']; ?></li>
            <?php 
				}
			?>
            </ul>
        <?php } else { echo '-'; } ?>        </td>
		<td><img src="../img/customers/follow-up-icon.png" title="Add Record" style="cursor:pointer" onclick="TINY.box.show({iframe:'add_record.php?id=<?php echo $get_q['id']; ?>', width:700,height:500,close:false,opacity:70});"></td>
    </tr>
    <?php } ?>
    <tr>
    	<td align="right" colspan="11"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="12">&nbsp;</td>
    </tr>
</table>
</center>
<script>
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
  
});
$(document).ready(function() {   
   $("#icno").autocomplete("auto_Nric.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
  
});

</script>
</body>
</html>