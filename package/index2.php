<?php 
include('../include/page_header.php'); 

$sql = mysql_query("SELECT * FROM loan_scheme");
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
#add_scheme
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_scheme:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
	width:230px
}
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/package/package.png"></td>
        <td>Package</td>
        <td align="right">
        	<a href="add_new.php" title="Add New Package">
            	<table>
                	<tr>
                    	<td style="padding-right:5px"><img src="../img/enquiry/add-button.png"></td>
                        <td>New Package</td>
                	</tr>
                </table>
            </a>
        </td>
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
    	<th width="50">No.</th>
        <th>Package Name</th>
        <th>&nbsp;</th>
        <th width="100">&nbsp;</th>
    </tr>
    <?php 
	$ctr = 0;
	while($get_q = mysql_fetch_assoc($sql)){ 
	$ctr++;
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_q['scheme']; ?></td>
        <td>
        	<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Package Name</td>
                    <td style="padding:0px">
                    	<input type="text" name="scheme_<?php echo $ctr; ?>" id="scheme_<?php echo $ctr; ?>" value="<?php echo $get_q['scheme']; ?>">
                    </td>
                    <td>
                    	<input type="button" name="update" id="update" onclick="updateScheme('<?php echo $ctr; ?>', '<?php echo $get_q['id']; ?>')" value="">
                    </td>
                </tr>
            </table>
        	</div>
        </td>
        <td>
        	<center>
        	   <a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;&nbsp;
                <a href="javascript:deleteConfirmation('<?php echo $get_q['id']; ?>')"><img src="../img/customers/delete-icon.png" title="Delete"></a>
            </center>
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
</table>
</center>
<script>
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

function updateScheme(no, id)
{
	$scheme = $('#scheme_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_scheme',
			id: $id,
			scheme: $scheme,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
}
function deleteConfirmation(id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete the package?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_scheme',
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
</script>