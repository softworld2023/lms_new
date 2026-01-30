<?php 
include('../include/page_headercb.php'); 

	


	if(isset($_POST['search']))   // click search
{

	$cond = '';
		
		if($_POST['customer_name'] != '')
		{
			$customer_sql = mysql_query("(SELECT * FROM majusama.customer_details AS table2 WHERE name = '".$_POST['customer_name']."') UNION (SELECT * FROM anseng.customer_details AS table2 WHERE name = '".$_POST['customer_name']."')");
			$cust = mysql_fetch_assoc($customer_sql);
			$cond .= "where table2.customercode2 = '".$cust['customercode2']."' AND table2.name = '".$cust['name']."'";	
		}
		
		
		
		if($_POST['customer_ic'] != '')
		{
			
			$code_sql = mysql_query("(SELECT * FROM majusama.customer_details WHERE nric = '".$_POST['customer_ic']."') UNION (SELECT * FROM anseng.customer_details WHERE nric = '".$_POST['customer_ic']."')");
			$code = mysql_fetch_assoc($code_sql);
			$cond .= "where table2.customercode2 = '".$cust['customercode2']."' AND table2.name = '".$cust['name']."'";	

		}

		$statement = "(
					SELECT
						*,'MAJUSAMA' AS company_branch
					FROM
						majusama.late_interest_record AS table1
					LEFT JOIN majusama.customer_details AS table2 ON table1.customer_id = table2.id
					LEFT JOIN majusama.customer_employment AS table3 ON table1.customer_id = table3.customer_id
					LEFT JOIN majusama.customer_address AS table4 ON table1.customer_id = table4.customer_id
					$cond)
				UNION
					(
						SELECT
							*,'ANSENG' AS company_branch
						FROM
							anseng.late_interest_record AS table1
						LEFT JOIN anseng.customer_details AS table2 ON table1.customer_id = table2.id
						LEFT JOIN anseng.customer_employment AS table3 ON table1.customer_id = table3.customer_id
						LEFT JOIN anseng.customer_address AS table4 ON table1.customer_id = table4.customer_id
						$cond)";
			}
		else    // not click search and also no history result
		{
		$statement = "(
					SELECT
						*,'MAJUSAMA' AS company_branch
					FROM
						majusama.late_interest_record AS table1
					LEFT JOIN majusama.customer_details AS table2 ON table1.customer_id = table2.id
					LEFT JOIN majusama.customer_employment AS table3 ON table1.customer_id = table3.customer_id
					LEFT JOIN majusama.customer_address AS table4 ON table1.customer_id = table4.customer_id
				)
				UNION
					(
						SELECT
							*,'ANSENG' AS company_branch
						FROM
							anseng.late_interest_record AS table1
						LEFT JOIN anseng.customer_details AS table2 ON table1.customer_id = table2.id
						LEFT JOIN anseng.customer_employment AS table3 ON table1.customer_id = table3.customer_id
						LEFT JOIN anseng.customer_address AS table4 ON table1.customer_id = table4.customer_id)";
			}


$sql_q = $statement;
$bad_debt_q = mysql_query($sql_q);
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
#tr_list:hover {background-color: lightgrey;}

</style>
<center>
<table width="1280">
	<tr>
    	<td width="134"><img src="../img/cash-register-icon.png" width="56"></td>
        <td width="401"><b>Bad Debt</b></td>
 
<td align="right">
       	<form action="bad_debt_list_anseng_mj.php" method="post">
        	<table>
				<tr>
					<td align="right" style="padding-right:10px">Customer Name</td>
					<td>
					<?php
$sql="(SELECT * FROM majusama.customer_details) UNION (SELECT * FROM anseng.customer_details)"; 
$result=mysql_query($sql);
echo '<input id="customer_name" name="customer_name" list="names" style="height:30px;" ><datalist id="names">';
while($rows=mysql_fetch_assoc($result)){
?>
<option value="<?php echo $rows["name"]; ?>">
<?php
}
?>
				</datalist></td>
                    <td align="right" style="padding-right:10px">Customer IC</td>
                    <td><?php
$sql="(SELECT * FROM majusama.customer_details) UNION (SELECT * FROM anseng.customer_details)"; 
$result=mysql_query($sql);
echo '<input id="customer_ic" name="customer_ic" list="nrics" style="height:30px;" ><datalist id="nrics">';
while($rows=mysql_fetch_assoc($result)){
?>
<option value="<?php echo $rows["nric"]; ?>">
<?php
}
?>
				</datalist></td>
                    <td style="padding-right:8px">
                    	<input type="hidden" id="branch_name" name="branch_name" value="<?php echo $branch_name?>"/>
                    	<input type="hidden" id="bd_name" name="bd_name" value="<?php echo $bd_name?>"/>
                    	<input type="submit" id="search" name="search" value=""/>
					</td>
                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr><td colspan="7" style="text-align:right;"><input type="submit" id="search_1" name="search" value="Show all list"/></td></tr>

            </table>
        </form>
        </td>
	  
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	
	</table>
	
<table width="1240" id="list_table" >

	<tr>
    	<th width="80" style="border:1px solid black;">No.</th>
    	<th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Name</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">IC</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Company Name</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Contact Number</th>
        <th width="240" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Bad Debt Amount</th>
    </tr>
    <?php 
	$ctr = 0;
	$bad_debt_amount= 0;
	$total_bd_amount= 0;

$numberofrow = mysql_num_rows($bad_debt_q);
if($numberofrow >0){
while($bad_debt = mysql_fetch_assoc($bad_debt_q))
{
	$total_bd_amount +=$bad_debt['balance'];

	$ctr++;
	
	?>

    <tr id="tr_list" >
    	<td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;"><?php echo $ctr."."; ?></td>
    	<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['company_branch']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['name']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['nric']; ?></b></td>
     	<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['company']; ?></b></td>
     	<td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $bad_debt['mobile_contact']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($bad_debt['balance'],2);?> </b></td>
    </tr>
<?php } }else{
?> <tr id="tr_list" >
    	<td colspan="7" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
    </tr>
<?php }?>
    <tr>
    	<td colspan="6" style="text-align: right;border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">Total</td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($total_bd_amount,2);?> </b></td>
    </tr>
    <tr>
    	<td colspan="7" align="right">&nbsp;</td>
    </tr>
     <tr>
    	<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../branch/'" value=""></td>
    </tr>
</table>
</center>
<script>
