<?php
    include('../include/page_header.php');
    session_start();

    $selected_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
	$selected_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('m');

    $db = $_SESSION['login_database'];

   
?>

<style>
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
#search1
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search1:hover
{
	background:url(../img/enquiry/search-btn-roll-over.jpg);
}
#yearlyLedger {
  background: #fa900f;
  background-image: -webkit-linear-gradient(top, #fa900f, #fa900f);
  background-image: -moz-linear-gradient(top, #fa900f, #fa900f);
  background-image: -ms-linear-gradient(top, #fa900f, #fa900f);
  background-image: -o-linear-gradient(top, #fa900f, #fa900f);
  background-image: linear-gradient(to bottom, #fa900f, #fa900f);
  font-family: Arial;
  color: #ffffff;
  font-size: 14px;
  padding: 8px 20px 8px 20px;
  border: solid #ffbb0f 0px;
  text-decoration: none;
  cursor:pointer;
}

#yearlyLedger:hover {
  background: #f5a94c;
  background-image: -webkit-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -moz-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -ms-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -o-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: linear-gradient(to bottom, #f5a94c, #f5a94c);
  text-decoration: none;
}

.customBtn {
   /* border-top: 1px solid #96d1f8; */
   border: none;
   background: #1a63ffff;
   padding: 6px 14px;
   color: white;
   font-size: 14px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
}
.customBtn:hover {
   border-top-color: #fa9c00;
   background: #fa9c00;
   color: #ccc;
}
.customBtn:active {
   border-top-color: #fa9c00;
   background: #fa9c00;
}	
   
.customBtn a {
    color: #ffffffff;
}

#tbl-closing {
    border-collapse:collapse;
    border:none;	
}

#tbl-closing tr th {
    height:36px;
    background:#666;
    text-align:left;
    padding-left:10px;
    color:#FFF;
}

#tbl-closing tr td {
    height:35px;
    padding-left:10px;
    padding-right:10px;
}

#btn-search-return-book-monthly {
    cursor: pointer;
}

#print-only-header {
    display: none;
    text-align: center;
    margin-bottom: 20px;
}

</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Closed Cash In / Out History</td>
        <td align="right">
        </td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
	
	<tr>
    	<td colspan="3">
        <div class="subnav">
			<a href="index.php">Ledger Listing</a>
			<a href="payment_monthly.php">Monthly Listing</a>
            <a href="payment_instalment.php" >Instalment Listing</a>
			<a href="lateIntPayment.php">Late Payment Listing</a>
			<a href="collection.php">Collection</a>
            <a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="close_listing.php" id="active-menu">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
			<a href="return_book_monthly.php">Monthly</a>
            <a href="return_book_instalment.php">Return Book</a>
			<a href="account_book_monthly.php">Account Book (Monthly)</a>
            <a href="account_book_instalment.php">Account Book (Instalment)</a>
		</div>
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

<table width="1280" id="tbl-closing">
    <thead>
        <tr class="print-header follow-page-header">
            <th colspan="7" style="text-align:center; font-weight:bold; font-size:16px; display:none;" id="continued-header">
            </th>
        </tr>
        <tr style="page-break-before: always; margin-top: 30px;">
            <th width="20%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
            <th width="50%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Closing Date</th>
            <th width="30%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Action</th>
        </tr>
        <tr class="print-header follow-page-header">
            <th colspan="7" style="text-align:center; font-weight:bold; font-size:16px; display:none;" id="continued-header">
            </th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<br>

<script>
$(document).ready(function(){
    $.ajax({
        url: 'get_closing_cash_in_out_ajax.php',
        type: 'GET',
        dataType: 'json',
        success: function(resp){
            if(resp.status === 'success'){
                var tbody = $('#tbl-closing tbody');
                tbody.empty();

                if(resp.data.length === 0){
                    tbody.append('<tr><td colspan="3" style="text-align:center;">No records found</td></tr>');
                } else {
                    $.each(resp.data, function(i, item){
                        tbody.append(`
                            <tr>
                                <td style="border:1px solid black; text-align:center;">${item.no}</td>
                                <td style="border:1px solid black; text-align:center;">${item.closing_date}</td>
                                <td style="border:1px solid black; text-align:center;">
                                    <button style="margin: 2px;" class="customBtn" onclick="printClosing('${item.closing_date}')">Print</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            } else {
                alert('Error: ' + resp.message);
            }
        },
        error: function(){
            alert('Failed to fetch closing data.');
        }
    });
});

// Example print function
function printClosing(date){
    window.open('print_cash_in_out.php?date=' + date , '_blank');
}
</script>