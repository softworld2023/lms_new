<?php
include('../include/dbconnection.php');	

$cust_id = $_GET['id'];
$name = $_GET['name'];
?>

<style>

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
<body>
<span style="font-weight:bold">View Record: <?php echo $name; ?></span><br><hr><br>
<?php 
$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '$cust_id'");
$cust= mysql_fetch_assoc($cust_q);
?>
<span><b>Blacklist Reason: </b><?php echo $cust['reason'];?></span><br><br>
<?php
for($i=0; $i<3; $i++)
{
	$cond = "";
	if($i == 0){
		$level = "OFFICE";
		$cond .= "AND level = '$level'";
	}else if($i == 1){
		$level = "AUDITOR";
		$cond .= "AND level = '$level'";
	}else if($i == 2){
		$level = "BLACKLIST IN CHARGE";
		$cond .= "AND level = '$level'";
	}else{
		break;
	}
?>
<table width="980px" id="list_table">
	<tr>
		<td colspan=6 align="center"><span style="font-weight:bold"><?php echo $level; ?></span></td>
	<tr>
	<tr>
    	<th width="40px">No.</th>
        <th width="110px">Date</th>
        <th width="180px">Person in Charge</th>
        <th width="440px">Reason</th>
        <th width="100px">Save by</th>
        <th width="110px">Save Date </th>
    </tr>
	<?php
	$ctr = 0;
	$sql = mysql_query("SELECT * FROM blacklist_reason WHERE customer_id = '$cust_id' $cond ORDER BY date ");
	if(mysql_num_rows($sql) != 0){
		while($get_detail = mysql_fetch_assoc($sql))
		{
			$ctr++;
		?>
		<tr>
			<td valign="top" style="padding-top:10px"><?php echo $ctr; ?></td>
			<td valign="top" style="padding-top:10px"><?php echo $get_detail['date']; ?></td>
			<td valign="top" style="padding-top:10px"><?php echo strtoupper($get_detail['handleby']); ?></td>
			<td valign="top" style="padding-top:10px"><?php echo strtoupper($get_detail['reason']); ?></td>
			<td valign="top" style="padding-top:10px"><?php echo $get_detail['saveby']; ?></td>
			<td valign="top" style="padding-top:10px"><?php echo $get_detail['save_date']; ?></td>
		</tr>											
	<?php }
	} else{ ?>
		<tr><td colspan="6" align="center">No record</td></tr>
	<?php }  ?>
	<tr><td>&nbsp; </td></tr>
	<tr><td>&nbsp; </td></tr>
<?php } ?>
</table>



