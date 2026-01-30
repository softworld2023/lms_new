<?php 
session_start();
include('../include/page_headercb.php'); 
include("../include/dbconnection.php");
include("../include/dbconnection2.php");
$branch_id = $GET_['branch_id'];
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
	
	color:#FFF;
}
#list_table tr td
{
	height:35px;
	padding-top:15px;
	padding-bottom:15px;
	padding-left:15px;
	padding-right:15px;
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
   
<table width="280" id="list_table" align="center">
	<tr>
    	
        <th width="363"><div id="rl"></div></th>
    </tr>

		<tr align="center">
			
			<td><a href="generateB.php?branch_id=<?php echo $_GET['branch_id']; ?>">
					<img src="../img/view_source.png"/></a>&emsp;&emsp;&emsp;&emsp;
				<a href="view_details.php?branch_id=<?php echo $_GET['branch_id']; ?>">
					<img src="../img/view.png"/></a>&emsp;&emsp;&emsp;&emsp;
				<a href="editB.php?branch_id=<?php echo $_GET['branch_id']; ?>">
					<img src="../img/enquiry/edit-btn.png"/></a>
				
		    </td>
    </tr>

    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../'"></td>
    </tr>
    <tr>
    	<td colspan="8">&nbsp;</td>
    </tr>
</table>
</center>

