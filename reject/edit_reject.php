<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

$id = $_GET['id'];

// $query = "SELECT * FROM loansystem.reject_loan WHERE (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA')  AND status = 'ACTIVE' AND id = '".$id."' ORDER BY reject_date ASC";
$query = "SELECT * FROM loansystem.reject_loan WHERE status = 'ACTIVE' AND id = '".$id."' ORDER BY reject_date ASC";

$statement = mysql_query($query);
$reject = mysql_fetch_assoc($statement);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Golden One Entity</title>
<script type="text/javascript" src="../include/js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../include/js/jquery-1.8.3.min.js"></script>
<script src="../include/js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="../include/css/style.css" />
<link rel="stylesheet" type="text/css" href="../include/css/media-queries.css" />
<script type="text/javascript" src="../include/js/jquery.confirm/jquery.confirm.js"></script>
<link rel="stylesheet" type="text/css" href="../include/js/jquery.confirm/jquery.confirm.css" />
<link rel="stylesheet" type="text/css" href="../include/js/anytimer/anytime.css" />
<script type="text/javascript" src="../include/js/anytimer/anytime.js"></script>
<link rel="stylesheet" href="../include/js/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../include/js/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>
<script type='text/javascript' src="../include/plugin/autocomplete/jquery.autocomplete.js"></script> 
<link rel="stylesheet" type="text/css" href="../include/plugin/autocomplete/jquery.autocomplete.css" /> 
<script type="text/javascript" src="../include/js/tokeninput/src/jquery.tokeninput.js"></script>
<link rel="stylesheet" type="text/css" href="../include/jquery/ui/themes/smoothness/jquery-ui-1.7.2.custom.css">
<link rel="stylesheet" type="text/css" href="../include/js/tokeninput/styles/token-input.css"/>
<link rel="stylesheet" type="text/css" href="../include/js/tokeninput/styles/token-input-facebook.css"/>
<script src="../include/js/jquery.maskedinput.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../include/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript" src="../include/shadowbox/shadowbox.js"></script>
<link rel="stylesheet" type="text/css" href="../include/shadowbox/shadowbox.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon"></head>

<body>  

<script language=JavaScript>
<!--
var message="You are not allowed to copy content from this website!";
function clickIE4(){
if (event.button==2){
<!--alert(message);-->
return false;
}
}
function clickNS4(e){
if
(document.layers||document.getElementById&&!document.all){
if
(e.which==2||e.which==3){
<!--alert(message);-->
return false;
}
}
}
if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}
document.oncontextmenu=new Function("return false")
// -->
</script>
<style>
#list_table
{
    border-collapse:collapse;
    border:none;
}

#list_table tr th
{
    height:30px;
    background:#F0F0F0;
    text-align:left;
    padding-left:10px;
}
#list_table tr td
{
    height:30px;
    padding-top:3px;
}
#hid
{
    border-collapse:collapse;
    border:none;
}

#hid tr th
{
    height:20px;
    background:#F0F0F0;
    text-align:left;
    padding-left:10px;
}
#hid tr td
{
    height:20px;
    padding-top:3px;
}
input
{
    height:30px;
}
#reset
{
    background:url(../img/add-enquiry/clear-btn.jpg);
    width:109px;
    height:30px;
    border:none;
    cursor:pointer;
}
#reset:hover
{
    background:url(../img/add-enquiry/clear-btn-roll-over.jpg);
}
#edit_reject
{
    background:url(../img/add-enquiry/submit-btn.jpg);
    width:109px;
    height:30px;
    border:none;
    cursor:pointer;
}
#edit_reject:hover
{
    background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
    text-transform:uppercase;
}
</style>
<center>
<form method="post" action="reject_action.php" enctype="multipart/form-data" onSubmit="return checkForm()">
<table width="100%" id="list_table" border="0">
    <tr>
        <th colspan="2" style="text-align:center;">Edit Reject</th>
        <input type="hidden" name="reject_id" id="reject_id" value="<?php echo $id?>" style="width:230px"  autocomplete="off">
    </tr>
    
    <tr>
        <td width="40%" style="padding-right:10px;" align="right"><b>Date</b></td>
        <td align="left"><input type="text" name="reject_date" id="reject_date" value="<?php echo $reject['reject_date']?>" style="width:230px"  autocomplete="off"></td>
    </tr>
    <tr>
        <td style="padding-right:10px;" align="right">Customer Name</td>
        <td align="left"><input type="text" name="customer_name" id="customer_name" value="<?php echo $reject['customer_name']?>" style="width:230px"  autocomplete="off"/></td>
    </tr>
    <tr>
        <td style="padding-right:10px;" align="right">I.C. Number</td>
        <td align="left"><input type="text" name="customer_ic" id="customer_ic" value="<?php echo $reject['customer_ic']?>" style="width:230px"  autocomplete="off"/></td>
    </tr>
      <tr>
        <td style="padding-right:10px;" align="right"><b>Company Name</b></td>
        <td align="left"><input type="text" name="customer_company" id="customer_company" value="<?php echo $reject['customer_company']?>" style="width:230px"  autocomplete="off"/></td>
    </tr>
          <tr>
        <td style="padding-right:10px;" align="right"><b>Contact Number</b></td>
        <td align="left"><input type="text" name="customer_contact_number" id="customer_contact_number" value="<?php echo $reject['customer_contact_number']?>"  style="width:230px"  autocomplete="off"/></td>
    </tr>
     <tr>
        <td style="padding-right:10px;" align="right"><b>Customer From</b></td>
        <td align="left"><input type="text" name="customer_from" id="customer_from" value="<?php echo $reject['customer_from']?>"  style="width:230px"  autocomplete="off"/></td>
    </tr>
          <tr>
        <td style="padding-right:10px;" align="right"><b>Reject Reason</b></td>
        <td align="left"><textarea rows="10"  name="reject_reason" id="reject_reason"  style="width:230px"  autocomplete="off"/><?php echo $reject['reject_reason']?></textarea></td>
    </tr>
    <tr> <td style="padding-right:10px;" align="right"><b>Document</b></td>
        <td><input type="file" name="reject_doc" id="reject_doc" class="upload" />
            <?php if($reject['document'] != '') { ?>
            <a target="_blank" href="../reject/document/<?php echo strtolower($reject['branch_name']); ?>/<?php echo $reject['id']; ?>/<?php echo $reject['document']; ?>"><img src="../img/customers/view-icon.png" alt="" title="View Reject Document" /></a><?php }?>
            <input type="hidden" name="reject_doc1" id="reject_doc1" value="<?php echo $reject['document']?>"  style="width:230px"  autocomplete="off"/>
</td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td colspan="2" align="center"><input type="submit" name="edit_reject" id="edit_reject" value="" tabindex="4"></td></tr>
    
</table>
</form>
</center>
<script>
$('#reject_date').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m-%d", labelTitle: "Select Date"}).focus(); } ).
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


$(function() {

    $.mask.definitions['~'] = "[+-]";

    $("#customer_ic").mask("999999-99-9999");

})
</script>
</body>
</html>