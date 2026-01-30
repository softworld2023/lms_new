<?php 
include('../include/page_headercb.php'); 

$sql = mysql_query("SELECT * FROM cashbook_setting ORDER BY description ASC");
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
#add_type
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	height:30px;
	width:109px;
	border:none;
	cursor:pointer;
}
#add_type:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
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
#update
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#update:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/expenses.png" width="56"></td>
        <td>Cashbook Setting </td>
        <td align="right">
       		<form method="post" action="action_setting.php" onsubmit="return checkForm()">
            	<table>
                	<tr>
						<td style="padding-right:5px">Add New Type:</td>
                    	<td style="padding-right:5px"><input type="text" name="description" id="description" style="height:25px; width:200px" /></td>
                        <td><input type="submit" name="add_type" id="add_type" value="" /></td>
                	</tr>
                </table>
			</form>
		</td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    
</table>
<div id="message" style="width:1280px; text-align:left">
	<?php
    if($_SESSION['msg1'] != '')
    {
        echo $_SESSION['msg1'];
        $_SESSION['msg1'] = '';
    }
    ?>						
</div>
<br />
<table width="1280" id="list_table">
	<tr>
    	<th width="80">No.</th>
        <th colspan="2">Type</th>
		<th width="120"></th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_q['description']; ?></td>
		<td>
			<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit</td>
                    <td style="padding:0px">
                   	  <input type="text" name="desc_<?php echo $ctr; ?>" id="desc_<?php echo $ctr; ?>" value="<?php echo $get_q['description']; ?>" style="height:25px; width:200px">
                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateDesc('<?php echo $ctr; ?>', '<?php echo $get_q['id']; ?>')" value="">
                    </td>
                </tr>
            </table>
        	</div>
		</td>
		<td>
        <center>
        	<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;
        	<a href="javascript:deleteConfirmation('<?php echo $get_q['description']; ?>', '<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete" id="del"></a>
        </center>	
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='index.php'" value=""></td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
</table>
</center>
<script>
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this expenses type: ' + name + '?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_type',
							id: $id,
						},
						url: 'action_setting.php',
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

function checkForm()
{
	if((document.getElementById('description').value != ''))
	{
		$('#message').empty();
		return true;	
	}else
	{
		var msg = "<div class='error'>Please fill in the type text field!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}

function showEdit(no)
{
	if(document.getElementById('edit_' + no).style.visibility == 'hidden')
	{
		document.getElementById('edit_' + no).style.visibility = 'visible';	
	}else
	if(document.getElementById('edit_' + no).style.visibility == 'visible')
	{
		document.getElementById('edit_' + no).style.visibility = 'hidden';
	}
}

function updateDesc(no, id)
{
	$description = $('#desc_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_type',
			id: $id,
			description: $description,
		},
		url: 'action_setting.php',
			success: function(){
			location.reload();
		}
	})
}
</script>