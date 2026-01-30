<?php 
include('../include/page_header.php'); 


if(isset($_POST['search']))
{	

	$Value1 = $_POST['amount_monthly'] -'100';
	if($Value1 <=1){
		$Value1 = '0';
	}else{
		$Value1 = $Value1;
	}
	$Value2 = $_POST['amount_monthly'] +'100';



$sql = mysql_query("	(SELECT loan_amount,cash_in_hand, 48months AS amount_monthly ,'48 months' AS loan_month FROM new_package WHERE (48months >= '".$Value1."' AND 48months <= '".$Value2."')ORDER BY loan_amount)

									UNION	(SELECT loan_amount,cash_in_hand, 60months AS amount_monthly ,'60 months' AS loan_month FROM new_package WHERE (60months >= '".$Value1."' AND 60months <= '".$Value2."')ORDER BY loan_amount)
");
}

//$sql = mysql_query("SELECT * FROM expenses ORDER BY date DESC");
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
#tr_list:hover {background-color: yellow;}

</style>
<center>
<table width="1280">
	<tr>
    	<td width="134"><img src="../img/cash-book-icon.png" width="56"></td>
        <td width="401">Search Loan Amount</td>
        <td width="729" align="right">
		<form action="" method="post">
        	<table>
            	<tr>
					<td align="left" style="padding-right:10px">Loan Amount (RM)</td>
                    <td style="padding-right:30px"><input type="text" name="amount_monthly" id="amount_monthly" value="<?php echo $_POST['amount_monthly'];?>"style="height:30px; width:100px"/></td>
                    <td style="padding-right:8px"><input type="submit" id="search" name="search" value="" /></td>
                </tr>
            </table>
        </form>
	  </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr><td colspan="3"> <div class="subnav"><a href="index.php" >List 1</a> <a href="index_list2.php" id="active-menu"> List 2</a>
	
		</div></td>
	
		</div></td></tr>
	
	</table>
	<h2><?php echo 'Amount Monthly Between';?><span style="color:red;"><?php echo ' RM '.$Value1;?></span> - <span style="color:red;"><?php echo 'RM '.$Value2;?></h2>
	<br>
<table width="1280" id="list_table">

	<tr>
    	<th width="80" style="border:1px solid black;">No.</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Loan Amount</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Cash in Hand</th>
        <th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Month</th>
        <th width="200" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Amount Monthly</th>
    </tr>
    <?php 
	$ctr = 0;
	$amount_monthly = 0;
	$row = mysql_num_rows($sql);
	if($row>0){
	while($get_q = mysql_fetch_assoc($sql)){ 

	$ctr++;
	
	?>

    <tr id="tr_list">
    	<td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;"><?php echo $ctr."."; ?></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b><?php echo "RM ".$get_q['loan_amount']; ?></b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: orangered;"><b><?php echo "RM ".$get_q['cash_in_hand'];?> </b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: blue;"><b><?php echo $get_q['loan_month'];?> </b></td>
        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: green;"><b><?php echo "RM ".$get_q['amount_monthly'];?> </b></td>
    </tr>
<?php }}
else 
{?>

    <tr>

        <td colspan="5" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Record - </td>
        
    </tr>
<?php }?>
    <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
</table>
</center>