<?php 
include('../include/page_header.php'); 

if(isset($_POST['search']))
{
	if($_POST['customer_name'] != '')
	{
		$cond .= " and cust_name = '".$_POST['customer_name']."'";	
	}
	
	if($_POST['icno'] != '')
	{
		$cond .= " and icno = '".$_POST['icno']."'";
	}
	
	if($_POST['enq_date'] != '')
	{
		$cond .= " and enq_date = '".$_POST['enq_date']."'";
	}
	
	$statement = "`enquiry` WHERE 1 $cond ";
	$_SESSION['enq'] = $statement;
}
else
if($_SESSION['enq'] != '')
{
	$statement = $_SESSION['enq'];
}
else
{
	$statement = "`enquiry` WHERE 1 $cond ";
}


$sql = mysql_query("SELECT * FROM {$statement}");

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
    	<td width="65"><img src="../img/enquiry/small-enquiry-icon.png"></td>
        <td>Enquiry</td>
        <td align="right" style="padding-left:20px">
            <a href="enquiry.php">
                <table>
                    <tr>
                        <td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>Add Enquiry</td>
                    </tr>
                </table>
            </a> 
        </td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3"><center>
        <form action="" method="post">
        <table>
            <tr>
                <td align="right" style="padding-right:10px">Name</td>
                <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px; width:250px" /></td>
                <td align="right" style="padding-right:10px">I.C. Number</td>
                <td style="padding-right:30px"><input type="text" name="icno" id="icno" style="height:30px" /></td>
                <td align="right" style="padding-right:10px">Date</td>
                <td  style="padding-right:30px"><input type="text" name="enq_date" id="enq_date" style="height:30px" /></td>
                <td style="padding-right:8px">
                    <input type="submit" id="search" name="search" value="" />
                </td>
           	</tr>
        </table>
        </form>
        </center>
        </td>
        </td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
            <div id="message" style="width:100%;">
            <?php
            if($_SESSION['msg'] != '')
            {
                echo $_SESSION['msg'];
                $_SESSION['msg'] = '';
            }
            ?>
            </div>
		</td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
    	<th width="100">Date</th>
        <th width="250">Name</th>
        <th width="120">I.C. Number</th>
      	<th>Mobile No. </th>
		<th>Branch</th>
      	<th width="280">Remarks</th>
        <?php //let user edit
		//if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong'))
if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'))
		{
		?>	
		<th colspan="2" width="140"></th>
		<?php } else
		{ ?>
		<th colspan="2" width="50"></th>
		<?php } ?>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;

	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
    	<td><?php echo date('d M Y', strtotime($get_q['enq_date'])); //date('d M Y', strtotime($get_q['enq_date']))." - ".date('h:i:A', strtotime($get_q['time'])); ?></td>
        <td><?php echo $get_q['cust_name']; ?></td>
        <td><?php echo $get_q['icno']; ?></td>
        <td><?php if($get_q['branch'] == $_SESSION['login_branch']) { echo $get_q['mobile']; } ?></td>
		<td><?php echo $get_q['branch']; ?></td>
        <td><?php if($get_q['branch'] == $_SESSION['login_branch']) { echo $get_q['remarks']; } ?></td>
        <?php //let user edit
		//if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong'))
if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff'))
		{
		?>
		<td width="30">
			<a href="view_enq.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox"><img src="../img/customers/view-icon.png" alt="" title="View Particular" /></a>
		</td>
		<td>
			
            <a href="edit_enquiry.php?id=<?php echo $get_q['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit"/></a>&nbsp;
            <a href="javascript:deleteConfirmation('<?php echo $get_q['cust_name']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
		</td>
        <?php } //end of edit function
		else { ?>
		<td colspan="2">
			<?php if($get_q['branch'] == $_SESSION['login_branch']) { ?><a href="view_enq.php?id=<?php echo $get_q['id']; ?>" rel="shadowbox"><img src="../img/customers/view-icon.png" alt="" title="View Particular" /></a><?php } ?>
		</td>
		<?php } ?>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="9">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="9" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="9">&nbsp;</td>
    </tr>
</table>
</center>
<script>
function deleteConfirmation(cust_name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this enquiry: ' + cust_name + '?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_enquiry',
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

Shadowbox.init();
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 250,
		matchContains: true,
		selectFirst: false
	});
   
   $("#icno").autocomplete("auto_ic.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
  
});

$('#enq_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
</script>
