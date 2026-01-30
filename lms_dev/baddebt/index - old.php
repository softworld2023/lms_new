<?php 
include('../include/page_headercb.php'); 

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
        <td width="401">Bad Debt (All Branch)</td>
        <td width="729" align="right">

	  </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	
	</table>
	
<table width="840" id="list_table" >

	<tr>
    	<th width="80" style="border:1px solid black;">No.</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
        <th width="240" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Bad Debt Amount</th>
    </tr>
    <?php 
	$ctr = 0;
	$bad_debt_amount= 0;
	$total_bd_amount= 0;

$branch_q = mysql_query("SELECT * FROM loansystem.branch WHERE branch_id != '13'");
while($branch = mysql_fetch_assoc($branch_q))
{
	$bd_name = $branch['db_name'];
	$bad_debt_q = mysql_query("SELECT sum(amount) AS bd_amount FROM ".$bd_name.".late_interest_record");
	$bad_debt = mysql_fetch_assoc($bad_debt_q);

$total_bd_amount +=$bad_debt['bd_amount'];

	$ctr++;
	
	?>

    <tr id="tr_list" onclick="view('<?php echo $branch['branch_name'];?>','<?php echo $branch['db_name'];?>');">
    	<td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;"><?php echo $ctr."."; ?></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo $branch['branch_name']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($bad_debt['bd_amount'],2);?> </b></td>
    </tr>
<?php }
?>
    <tr>
    	<td colspan="2" style="text-align: right;border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">Total</td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo "RM ".number_format($total_bd_amount,2);?> </b></td>
    </tr>
    
</table>
</center>
<script>
			function view(branch_name,bdname) {
		// console.log(e);
	     location.href = "bad_debt_list.php?bdname="+bdname+"&branch_name="+branch_name;
	    }
	
	</script>