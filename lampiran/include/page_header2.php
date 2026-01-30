<?php
session_start();
//if != settle.php, clear session for settlement page
$basenamecheck = basename($_SERVER['PHP_SELF']);
if($basenamecheck != 'settle.php')
{
	$_SESSION['dosf'] = '';
	$_SESSION['dost'] = '';
	$_SESSION['csbranch'] = '';
}

if ($_SESSION['login_branchid'] == '') {
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php
}
include("../include/dbconnection.php");
include("../config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Softworld - Loan System</title>
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

<script type="text/javascript" src="../include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../include/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript" src="../include/shadowbox/shadowbox.js"></script>
<link rel="stylesheet" type="text/css" href="../include/shadowbox/shadowbox.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon">
</head>

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

// JavaScript I wrote to limit what types of input are allowed to be keyed into a textbox 
var allowedSpecialCharKeyCodes = [46,8,37,39,35,36,9];
var numberKeyCodes = [44, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105];
var commaKeyCode = [188];
var decimalKeyCode = [190,110];

function numbersOnly(event) {
    var legalKeyCode =
        (!event.shiftKey && !event.ctrlKey && !event.altKey)
            &&
        (jQuery.inArray(event.keyCode, allowedSpecialCharKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, numberKeyCodes) >= 0);

    if (legalKeyCode === false)
        event.preventDefault();
}

function numbersAndCommasOnly(event) {
    var legalKeyCode =
        (!event.shiftKey && !event.ctrlKey && !event.altKey)
            &&
        (jQuery.inArray(event.keyCode, allowedSpecialCharKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, numberKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, commaKeyCode) >= 0);

    if (legalKeyCode === false)
        event.preventDefault();
}

function decimalsOnly(event) {
    var legalKeyCode =
        (!event.shiftKey && !event.ctrlKey && !event.altKey)
            &&
        (jQuery.inArray(event.keyCode, allowedSpecialCharKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, numberKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, commaKeyCode) >= 0
            ||
        jQuery.inArray(event.keyCode, decimalKeyCode) >= 0);

    if (legalKeyCode === false)
        event.preventDefault();
}

function currenciesOnly(event) {
    var legalKeyCode =
        (!event.shiftKey && !event.ctrlKey && !event.altKey)
            &&
        (jQuery.inArray(event.keyCode, allowedSpecialCharKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, numberKeyCodes) >= 0
            ||
        jQuery.inArray(event.keyCode, commaKeyCode) >= 0
            ||
        jQuery.inArray(event.keyCode, decimalKeyCode) >= 0);

    // Allow for $
    if (!legalKeyCode && event.shiftKey)
        legalKeyCode = true;

    if (legalKeyCode === false)
        event.preventDefault();
}


// jQuery formatCurrency plugin... see http://code.google.com/p/jquery-formatcurrency/
(function($){$.formatCurrency={};$.formatCurrency.regions=[];$.formatCurrency.regions[""]={symbol:"",positiveFormat:"%s%n",negativeFormat:"(%s%n)",decimalSymbol:".",digitGroupSymbol:",",groupDigits:true};
$.fn.formatCurrency=function(destination,settings){if(arguments.length==1&&typeof destination!=="string"){settings=destination;destination=false
}var defaults={name:"formatCurrency",colorize:false,region:"",global:true,roundToDecimalPlace:2,eventOnDecimalsEntered:false};defaults=$.extend(defaults,$.formatCurrency.regions[""]);
settings=$.extend(defaults,settings);if(settings.region.length>0){settings=$.extend(settings,getRegionOrCulture(settings.region))}settings.regex=generateRegex(settings);
return this.each(function(){$this=$(this);var num="0";num=$this[$this.is("input, select, textarea")?"val":"html"]();if(num.search("\\(")>=0){num="-"+num
}if(num===""||(num==="-"&&settings.roundToDecimalPlace===-1)){return}if(isNaN(num)){num=num.replace(settings.regex,"");if(num===""||(num==="-"&&settings.roundToDecimalPlace===-1)){return
}if(settings.decimalSymbol!="."){num=num.replace(settings.decimalSymbol,".")}if(isNaN(num)){num="0"}}var numParts=String(num).split(".");var isPositive=(num==Math.abs(num));
var hasDecimals=(numParts.length>1);var decimals=(hasDecimals?numParts[1].toString():"0");var originalDecimals=decimals;num=Math.abs(numParts[0]);
num=isNaN(num)?0:num;if(settings.roundToDecimalPlace>=0){decimals=parseFloat("1."+decimals);decimals=decimals.toFixed(settings.roundToDecimalPlace);
if(decimals.substring(0,1)=="2"){num=Number(num)+1}decimals=decimals.substring(2)}num=String(num);if(settings.groupDigits){for(var i=0;i<Math.floor((num.length-(1+i))/3);
i++){num=num.substring(0,num.length-(4*i+3))+settings.digitGroupSymbol+num.substring(num.length-(4*i+3))}}if((hasDecimals&&settings.roundToDecimalPlace==-1)||settings.roundToDecimalPlace>0){num+=settings.decimalSymbol+decimals
}var format=isPositive?settings.positiveFormat:settings.negativeFormat;var money=format.replace(/%s/g,settings.symbol);money=money.replace(/%n/g,num);
var $destination=$([]);if(!destination){$destination=$this}else{$destination=$(destination)}$destination[$destination.is("input, select, textarea")?"val":"html"](money);
if(hasDecimals&&settings.eventOnDecimalsEntered&&originalDecimals.length>settings.roundToDecimalPlace){$destination.trigger("decimalsEntered",originalDecimals)
}if(settings.colorize){$destination.css("color",isPositive?"black":"red")}})};$.fn.toNumber=function(settings){var defaults=$.extend({name:"toNumber",region:"",global:true},$.formatCurrency.regions[""]);
settings=jQuery.extend(defaults,settings);if(settings.region.length>0){settings=$.extend(settings,getRegionOrCulture(settings.region))}settings.regex=generateRegex(settings);
return this.each(function(){var method=$(this).is("input, select, textarea")?"val":"html";$(this)[method]($(this)[method]().replace("(","(-").replace(settings.regex,""))
})};$.fn.asNumber=function(settings){var defaults=$.extend({name:"asNumber",region:"",parse:true,parseType:"Float",global:true},$.formatCurrency.regions[""]);
settings=jQuery.extend(defaults,settings);if(settings.region.length>0){settings=$.extend(settings,getRegionOrCulture(settings.region))}settings.regex=generateRegex(settings);
settings.parseType=validateParseType(settings.parseType);var method=$(this).is("input, select, textarea")?"val":"html";var num=$(this)[method]();
num=num?num:"";num=num.replace("(","(-");num=num.replace(settings.regex,"");if(!settings.parse){return num}if(num.length==0){num="0"}if(settings.decimalSymbol!="."){num=num.replace(settings.decimalSymbol,".")
}return window["parse"+settings.parseType](num)};function getRegionOrCulture(region){var regionInfo=$.formatCurrency.regions[region];if(regionInfo){return regionInfo
}else{if(/(\w+)-(\w+)/g.test(region)){var culture=region.replace(/(\w+)-(\w+)/g,"$1");return $.formatCurrency.regions[culture]}}return null}function validateParseType(parseType){switch(parseType.toLowerCase()){case"int":return"Int";
case"float":return"Float";default:throw"invalid parseType"}}function generateRegex(settings){if(settings.symbol===""){return new RegExp("[^\\d"+settings.decimalSymbol+"-]","g")
}else{var symbol=settings.symbol.replace(".","\\.");return new RegExp(symbol+"|[^\\d"+settings.decimalSymbol+"-]","g")}}})(jQuery);
</script>
<style>
#header:hover
{
	text-decoration:none;
}
#navigation_button
{
	color:#666;
	display:inline-table;
}
#navigation_button:hover
{
	color:#6CF;
}
input, textarea
{
	text-transform:uppercase;
}
</style>

<!--main content-->
<center>

<?php  
    $branchid = mysql_query("select branch_name from user where id = '".$_SESSION['taplogin_id']."'");
	$branchid_q = mysql_fetch_assoc($branchid);

?>
<table width="1280" background="../img/top-logo-bg.jpg" height="37">
    <tr>
        <td style="padding-left:20px; color:#FFF"><strong>LOAN SYSTEM - <?php echo $_SESSION['login_branch'];  ?></strong></td>
        <td align="right" style="padding-right:20px" id="hideprint"><?php include('../include/userpanel.php'); ?></td> 
    </tr>
</table>
<?php
	//check the userright
	$userright_q = mysql_query("select * from accessright where staffid = '".$_SESSION['taplogin_id']."'");
	$get_userright = mysql_fetch_assoc($userright_q);
?>
<table width="1280" background="../img/menu-bg.jpg" height="47" id="hideprint">
    <tr>
        <td style="padding-left:10px">&nbsp;</td> 
    </tr>
</table>
</center>
<br><br>

