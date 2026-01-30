<?php include('../include/page_header.php'); ?>
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
input
{
	height:30px;
}
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/settings.png" style="height:47px"></td>
        <td>Setting</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">System User</a><a href="approval.php" id="active-menu">Loan Approval Level</a>
		</div>	
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
<br />
<table width="1280" id="list_table">
	<tr>
    	<th>No.</th>
        <th>Amount</th>
        <th>Approved By</th>
        <th>&nbsp;</th>
        <th width="100">&nbsp;</th>
    </tr>
    <?php 
	$ctr = 0;
	$sql = mysql_query("SELECT * FROM approval_level");
    while($get_q = mysql_fetch_assoc($sql)){
	$ctr++;
	?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td>
		<?php 
			if($get_q['approved_by'] != 'Boss')
			{
		?>
        		<img src="../img/less-sign.png" style="height:15px">
		<?php
			}else{
		?>
        		<img src="../img/more-equal-sign.jpg" style="height:15px">
		<?php
			}
			
			echo "RM ".$get_q['amount']; 
		?>
        </td>
        <td><?php echo $get_q['approved_by']; ?></td>
        <td>
        <?php 
			if($get_q['approved_by'] != 'Boss')
			{
		?>
        	<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $get_q['amount']; ?>" class="currency">
                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '<?php echo $get_q['id']; ?>')" value="">
                    </td>
                </tr>
            </table>
        	</div>
      <?php } ?>
      </td>
      <td>
        <center>
        <?php 
			if($get_q['approved_by'] != 'Boss')
			{
		?>
        	<a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a>
        <?php } ?>
        </center>	
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td height="16" colspan="5">&nbsp;</td>
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

function updateAmount(no, id)
{
	$amount = $('#amount_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
}
// mini jQuery plugin that formats to two decimal places
(function($) {
	$.fn.currencyFormat = function() {
    	this.each( function( i ) {
        $(this).change( function( e ){
        	if( isNaN( parseFloat( this.value ) ) ) return;
        	this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
    }
})( jQuery );

// apply the currencyFormat behaviour to elements with 'currency' as their class
$( function() {
    $('.currency').currencyFormat();
});
</script>
</body>
</html>