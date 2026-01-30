<?php include('../include/page_header.php'); ?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
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

-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/package/package.png"></td>
        <td>Package</td>
        <td align="right">
        	
        </td>
    </tr>
	<tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" id="active-menu">Package Listing</a><a href="transfer.php">Money Transfer</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">
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
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
        <th>Package Name</th>
        <th>Interest (%)</th>
        <th>Period</th>
        <th>Type</th>
        <th>Receipt Type </th>
        <th width="100">&nbsp;</th>
    </tr>
    <?php 
	$ctr = 0;
	$loan_q = mysql_query("SELECT * FROM loan_scheme ORDER BY scheme ASC");
    while($get_loan = mysql_fetch_assoc($loan_q)){
	$ctr++;
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><?php echo $get_loan['scheme']; ?></td>
        <td><?php echo $get_loan['interest']; ?>%</td>
        <td><?php echo $get_loan['from_date']." - ".$get_loan['to_date']." months "; ?></td>
        <td><?php echo $get_loan['type']; ?></td>
        <td>
			<?php
				$rt = $get_loan['receipt_type'];
				
				$rtype = '';
				
				if($rt == 1)
				{
					$rtype = "Fixed";
				}
				if($rt == 2)
				{
					$rtype = 'Changing';
				}
				echo $rtype;
			?>
		</td>
      <td>
        <center>
        	<a href="edit_package.php?id=<?php echo $get_loan['id']; ?>"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>&nbsp;&nbsp;
        </center>        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="7" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="7">&nbsp;</td>
    </tr>
</table>
</center>
<script>
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this user: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_staff',
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
</body>
</html>