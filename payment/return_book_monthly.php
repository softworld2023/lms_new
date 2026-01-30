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
   padding: 10px 12px;
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

   #tbl-return-book-monthly {
		border-collapse:collapse;
		border:none;	
	}

	#tbl-return-book-monthly tr th {
		height:36px;
		background:#666;
		text-align:left;
		padding-left:10px;
		color:#FFF;
	}

	#tbl-return-book-monthly tr td {
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

@media print {

    body * {
        visibility: hidden;
    }   

    @page {
        margin: 10px;
        }

    #print-only-header,
    #print-only-header *,
    #tbl-return-book-monthly,
    #tbl-return-book-monthly * {
        visibility: visible;
    }

    #print-only-header {
        display: block;
        position: absolute;
        top: 3%;
        width: 100%;
    }

    #tbl-return-book-monthly {
        position: absolute;
        top: 8%;
        left: 2%;
        right: 20%;
        bottom: 2%;
        width: 96%;
        
    }
    

    table, tr, td, th {
        page-break-inside: avoid;
    }

    /* Hide everything else */
    .subnav,
    header,
    footer,
    #message,
    #search,
    #search1,
    #btn-search-return-book-instalment,
    input,
    select,
    label,
    .customBtn {
        display: none !important;
    }

    tr[id^='detail-'] {
        display: table-row !important;
    }

    #continued-header {
        display: table-cell !important;
    }
}
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Monthly</td>
        <td align="right">
        	<table>
				<tr>
                    <td align="right" style="padding-right:10px">Customer ID</td>
                    <td style="padding-right:30px"><input type="text" name="customer_code" id="customer_code" style="height:30px; width:70px"/></td>
					<td align="right" style="padding-right:10px">Customer Name</td>
                    <td style="padding-right:30px"><input type="text" name="customer_name" id="customer_name" style="height:30px" /></td>
                    <td align="right" style="padding-right:10px">Agreement No</td>
                    <td style="padding-right:30px"><input type="text" name="loan_code" id="loan_code" style="height:30px; width:70px" /></td>
                    <td style="padding-right:8px">
                    	<input type="button" id="search" name="search" value="" onclick="searchReturnBookMonthly();"/>
					</td>
                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
            </table>
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
			<a href="close_listing.php">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
            <a href="return_book_monthly.php" id="active-menu">Monthly</a>
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

<table width="1280" id="list_table">
	<tr>
     	<td colspan="2">       
             <table width="100%" border='0'>
                 <tr>              
                     <td style="font-size: 16px;"><br>YEAR
                         <select 
                             id="selected_year"
                             name="selected_year"
                             class="form-control"
                             style="width: 120px;height: 30px; font-size:16px;">
                         <?php
                             $current_year = idate('Y');
                             $future_year = idate('Y', strtotime($current_year. ' + 5 years'));
                             $past_year = idate('Y', strtotime($current_year. ' - 3 years'));

                             for ($i = $past_year; $i <= $future_year; $i++) {
                                 $selected = $selected_year == $i ? 'selected' : '';
                         ?>
                                 <option value='<?php echo $i; ?>' <?php echo $selected; ?>><?php echo $i; ?></option>
                         <?php
                             }
                         ?>
                         </select>
                         MONTH
                         <select id="selected_month" name="selected_month" style="width: 120px;height: 30px; font-size:16px;">
                         <?php

                             for ($i = 1; $i <= 12; $i++) {
                                 $month_with_zero = str_pad($i, 2, '0', STR_PAD_LEFT);
                                 $selected = $selected_month == $month_with_zero ? 'selected' : '';
                         ?>

                                 <option value='<?php echo $month_with_zero; ?>' <?php echo $selected; ?>><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                         <?php
                             }
                         ?>
                         </select>
                         <input class="btn btn-blue" type="button" id="btn-search-return-book-monthly" name="search_return_book_monthly" value="Search" style="width: 100px; height: 30px; font-size:16px;" onclick="searchYearMonth();">
                     </td>
                     <td style="font-size: 16px;"></td>
                     <td>
                        <input type="button" value="PRINT LISTING" onclick="window.print();" class="customBtn" style="margin-left:10px;">
                    </td>
                 </tr>
             </table>
        	<br>
     	</td>
     </tr>
 </table>
<div id="print-only-header">
    <div style="font-size: 20px; font-weight: bold;">MONTHLY</div>
    <div id="print-only-date" style="margin-top: 5px; font-size: 16px;"></div>
</div>


 <table width="1280" id="tbl-return-book-monthly">
    <thead>
        <tr class="print-header follow-page-header">
            <th colspan="7" style="text-align:center; font-weight:bold; font-size:16px; display:none;" id="continued-header">
            </th>
        </tr>
        <tr style="page-break-before: always; margin-top: 30px;">
            <th width="5%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Date</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Agreement No.</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Customer ID</th>
            <th width="35%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Name</th>
            <th width="20%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: right;">Total Amount</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">More Details</th>
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

    function updatePrintOnlyDate() {
        const year = $('#selected_year').val();
        const month = $('#selected_month').val();
        const monthName = new Date(`${year}-${month}-01`).toLocaleString('default', { month: 'long' });
        $('#print-only-date').text(`${monthName} ${year}`);
    }

    $(document).ready(function() {
        $("#customer_name").autocomplete("auto_custName.php", {
            width: 170,
            matchContains: true,
            selectFirst: false
        });
        
        $("#loan_code").autocomplete("auto_loanCode.php", {
            width: 70,
            matchContains: true,
            selectFirst: false
        });
        
        $("#customer_code").autocomplete("auto_custCode.php", {
            width: 70,
            matchContains: true,
            selectFirst: false
        });

        searchReturnBookMonthly();
         updatePrintOnlyDate();
        $('#selected_year, #selected_month').change(updatePrintOnlyDate);
    });

    function searchReturnBookMonthly() {
        $.ajax({
            url: 'get_return_book_monthly_listing_ajax.php',
            type: 'POST',
            data: {
                year: $('#selected_year').val(),
                month: $('#selected_month').val(),
                customer_name: $('#customer_name').val(),
                customer_code: $('#customer_code').val(),
                loan_code: $('#loan_code').val()
            },
            dataType: 'html',
            success: function(response) {
                // console.log(response);
                $('#tbl-return-book-monthly tbody').html(response);
            }
        });
    }

    function searchYearMonth() {
        let year = $('#selected_year').val();
        let month = $('#selected_month').val();
        window.location.href = 'return_book_monthly.php?year=' + year + '&month=' + month;
    }

    function toggleDetails(id) {
        var row = document.getElementById(id);
        if (row.style.display === 'none') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    }

    function expandAllDetailsForPrint() {
        document.querySelectorAll("tr[id^='detail-']").forEach(row => {
            row.style.display = 'table-row';
        });
    }

    function printWithExpandedDetails() {
        expandAllDetailsForPrint();

        // Let the browser render the expanded rows properly
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                window.print();
            });
        });
    }

    // // Attach to PRINT LISTING button
    // document.querySelector(".customBtn").addEventListener("click", function () {
    //     printWithExpandedDetails();
    // });

    // Optional: Collapse after printing
    window.onafterprint = function () {
        document.querySelectorAll("tr[id^='detail-']").forEach(row => {
            row.style.display = 'none';
        });
    };


</script>