<?php
include('../include/page_header.php'); 

if(!($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'avi')))
{
	echo "<script type='text/javascript'>alert('You do not have the permission to access');</script>";
	echo "<script>window.location='../'</script>";
} else {
	
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
	
	if($_POST['cust_code'] != '')
	{
		$cond .= " and customercode2 = '".$_POST['cust_code']."'";
	}
	
	/* 01(20122016) - add sql to search customer for selected branch */
	if($_POST['branch'] != '')
	{
		$cond .= " and branch_id = '".$_POST['branch']."'";
	}
	/* End of 01(20122016) */
	
	$statement = "`customer_details` WHERE blacklist = 'Yes' $cond ";
	$_SESSION['blacklist1'] = $statement;
}
else
if($_SESSION['blacklist1'] != '')
{
	$statement = $_SESSION['blacklist1'];
}
else
{
	$statement = "`customer_details` WHERE blacklist = 'Yes' ";
}

$sql = mysql_query("SELECT * FROM {$statement} ORDER BY blacklistdate DESC")
?>

<script type="text/javascript" src="../include/js/tinybox.js"></script>
<link href="../include/css/tinystyle.css" rel="stylesheet" type="text/css" />
<style>
iframe {
   overflow: hidden;
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
</style>

<center>

<table width="1280">
	<tr>
    	<td width="65"><img src="../img/check-blacklist/check-blacklist.png"></td>
        <td>Black List Report</td>
        <td align="right">
       <form action="" method="post">
        	<table>
            	<tr>
                	<td align="right" style="padding-right:10px">Cust Code</td>
                	<td align="right" style="padding-right:10px"><input type="text" name="cust_code" id="cust_code" style="height:30px;width:100px;" /></td>
                	<td align="right" style="padding-right:10px">Customer</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">I.C. Number</td>
                    <td style="padding-right:30px"><input type="text" name="icno" id="icno" style="height:30px" /></td>
					<!-- 02(20122016) - add search option for branch -->
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
					<!-- End of 02(20122016) -->
                    <td style="padding-right:8px">
                    	<input type="submit" id="search" name="search" value="" />                    </td>
                </tr>
            </table>
        </form>
      </td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
    	<th width="50">No.</th>
        <th>Cust Code</th>
        <th width="300">Customer Name</th>
        <th>NRIC</th>
        <th width="150">Loan Code</th>
        <th>Amount (RM)</th>
        <th>Date </th>
        <th>Blacklist by</th>
        <th width="50"></th>
    </tr>

	<?php
	$ctr = 0;
    while($blreport = mysql_fetch_assoc($sql))
	{
		$ctr++;
	?>
	<tr>
    	<td valign="top" style="padding-top:10px"><?php echo $ctr; ?></td>
		<td valign="top" style="padding-top:10px"><?php echo strtoupper($blreport['customercode2']); ?></td>
		<td valign="top" style="padding-top:10px"><?php echo $blreport['name']; ?></td>
        <td valign="top" style="padding-top:10px"><?php echo $blreport['nric'] ?></td>
		<td valign="top" style="padding-top:10px"><?php
			$lcode_q = mysql_query("SELECT * FROM customer_loanpackage WHERE customer_id = '".$blreport['id']."' and loan_status = 'Paid'");
			$lcr = mysql_num_rows($lcode_q);
			$lctr = 0;
			while($lcode = mysql_fetch_assoc($lcode_q))
			{
				$lctr++;
				
				if($lctr != $lcr)
				{
					echo $lcode['loan_code'].", ";
				}else
				{
					echo $lcode['loan_code'];
				}
			}
		?></td>
		<td valign="top" style="padding-top:10px"><?php echo $blreport['blacklistamt']; ?></td>
		<td valign="top" style="padding-top:10px"><?php echo $blreport['blacklistdate']; ?></td>
		<td valign="top" style="padding-top:10px"><?php echo $blreport['blacklistby']; ?></td>
		<td valign="top" style="padding-top:10px"><img src="../img/customers/view-icon.png" title="View Record" style="cursor:pointer" onclick="TINY.box.show({iframe:'view_record.php?id=<?php echo $blreport['id']; ?>&name=<?php echo $blreport['name']; ?>', width:1000,height:700,close:false,opacity:70});"></td>
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
<?php
}
?>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
