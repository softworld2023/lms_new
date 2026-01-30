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
    	<td width="65"><img src="../img/cash-book/cash-book.png" style="height:47px"></td>
        <td>Cash Book</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" id="active-menu">Cash Book</a><a href="balance_1.php">Balance 1</a><a href="balance.php">Balance 2</a><a href="../cashbookhq/">CASHBOOK HQ</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
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
    	<th width="50">No.</th>
        <th>Package</th>
        <th>Opening Balance</th>
        <th>Opening Date</th>
        <th></th>
        <th width="100"></th>
    </tr>
    <?php 
	$ctr = 0;
	$package_q = mysql_query("SELECT * FROM loan_scheme ORDER BY scheme ASC");
    while($get_p = mysql_fetch_assoc($package_q)){
	$ctr++;
	?>
	<?php if($get_p['scheme'] != 'SKIM KUTU') { ?>
    <tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><a href="cashbook.php?id=<?php echo $get_p['id']; ?>"><?php echo $get_p['scheme']; ?></a></td>
        <td><?php echo "RM ".number_format($get_p['initial_amount'], '2'); ?></td>
        <td><?php if($get_p['opening_date'] != '0000-00-00') { echo $get_p['opening_date']; } else { echo "-"; } ?></td>
        <td>
        	<div style="padding-left:10px; visibility:hidden" id="edit_<?php echo $ctr; ?>">
            <table>
            	<tr>
            		<td>Edit Amount</td>
                    <td style="padding:0px">
                   	  <input type="text" name="amount_<?php echo $ctr; ?>" id="amount_<?php echo $ctr; ?>" value="<?php echo $get_p['initial_amount']; ?>" class="currency">
                    </td>
                    <td>Edit Date</td>
                    <td style="padding:0px">
                   	  <input type="text" name="opening_<?php echo $ctr; ?>" id="opening_<?php echo $ctr; ?>" value="<?php if($get_p['opening_date'] != '0000-00-00') { echo $get_p['opening_date']; }?>">
                    </td>
                    <td>
                   	  <input type="button" name="update" id="update" onclick="updateAmount('<?php echo $ctr; ?>', '<?php echo $get_p['id']; ?>')" value="">
                    </td>
                </tr>
            </table>
        	</div>
        </td>
        <td><a href="javascript:showEdit('<?php echo $ctr; ?>')"><img src="../img/customers/edit-icon.png" title="Edit" style="margin-bottom:2px"></a></td>
    </tr>
	<?php } else { ?>
	<tr>
    	<td><?php echo $ctr."."; ?></td>
        <td><a href="cashbookKutu.php"><?php echo $get_p['scheme']; ?></a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td></td>
    </tr>
	<?php } ?>
    <?php } ?>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6" align="right"><input type="hidden" name="ctr" id="ctr" value="<?php echo $ctr; ?>" /><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
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
	$date = $('#opening_' + no).val();
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
			opening: $date,
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

$ctr = document.getElementById('ctr').value;
for($i=1; $i<=$ctr; $i++)
{
	$('#opening_' + $i).click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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
}
</script>
</body>
</html>