<?php
    include('../include/page_header.php');
    session_start();

    $selected_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
	$selected_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('m');

    $db = $_SESSION['login_database'];
    $login_branch = $_SESSION['login_branch'];

    // $payout_month = $selected_year . '-' . $selected_month;

    // $opening_balance = 0;
    // $total_instalment_collected = 0;
    // $total_settle = 0;
    // $capital_in = 0;
    // $total_bd_collected = 0;
    // $total_monthly = 0;
    // $total_instalment_payout = 0;
    // $total_expenses = 0;
    // $total_expenses_2 = 0;
    // $total_interest_paid_out = 0;
    // $return_capital = 0;

    // $sql = "SELECT * FROM $db.instalment_balance WHERE pay_month = '$payout_month'";
    // $query = mysql_query($sql);
    // if (mysql_num_rows($query) > 0) {
    //     $result = mysql_fetch_assoc($query);
    //     $opening_balance = $result['opening_balance'];
    //     $total_instalment_collected = $result['collected'];
    //     $total_settle = $result['settle'];
    //     $capital_in = $result['capital_in'];
    //     $total_bd_collected = $result['baddebt'];
    //     $total_monthly = $result['monthly'];
    //     $total_instalment_payout = $result['payout'];
    //     $total_expenses = $result['expenses'];
    //     $total_expenses_2 = $result['expenses_2'];
    //     $total_interest_paid_out = $result['interest_paid_out'];
    //     $return_capital = $result['return_capital'];
    // }

    // $sql = "SELECT
	// 			customer_loanpackage.loan_code,
	// 			customer_loanpackage.start_month,
	// 			customer_loanpackage.payout_date,
	// 			customer_loanpackage.loan_amount,
	// 			customer_loanpackage.loan_period,
	// 			customer_loanpackage.loan_total,
	// 			customer_loanpackage.loan_status,
	// 			customer_details.customercode2,
	// 			customer_details.name,
	// 			customer_details.nric,
	// 			customer_employment.company,
	// 			temporary_payment_details.monthly,
	// 			temporary_payment_details.loan_percent,
	// 			temporary_payment_details.loan_status,
	// 			temporary_payment_details.customer_loanid 
	// 		FROM
	// 			$db.customer_loanpackage
	// 		LEFT JOIN $db.customer_details ON customer_loanpackage.customer_id = customer_details.id
	// 		LEFT JOIN $db.customer_employment ON customer_employment.customer_id = customer_details.id
	// 		LEFT JOIN $db.temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
	// 		WHERE
	// 			temporary_payment_details.loan_month = '$payout_month'
	// 		AND temporary_payment_details.loan_code NOT IN (
    //             SELECT
    //                 customer_loanpackage.loan_code 
    //             FROM
    //                 $db.customer_loanpackage
    //             LEFT JOIN $db.customer_details ON customer_loanpackage.customer_id = customer_details.id
    //             LEFT JOIN $db.customer_employment ON customer_employment.customer_id = customer_details.id
    //             LEFT JOIN $db.loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
    //             WHERE
    //                 customer_loanpackage.loan_package = 'NEW PACKAGE'
    //             AND loan_payment_details.month_receipt < '$payout_month'
    //             AND loan_payment_details.loan_status = 'SETTLE' 
    //             GROUP BY customer_loanpackage.loan_code
    //             ORDER BY customer_loanpackage.loan_code ASC
    //         )
	// 		AND temporary_payment_details.loan_code NOT IN (
    //             SELECT
	// 		        customer_loanpackage.loan_code 
    //             FROM
    //                 $db.customer_loanpackage
    //             LEFT JOIN $db.customer_details ON customer_loanpackage.customer_id = customer_details.id
    //             LEFT JOIN $db.customer_employment ON customer_employment.customer_id = customer_details.id
    //             LEFT JOIN $db.loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
    //             WHERE
    //                 customer_loanpackage.loan_package = 'NEW PACKAGE'
    //             AND loan_payment_details.month_receipt < '$payout_month'
    //             AND loan_payment_details.loan_status = 'BAD DEBT' 
    //             GROUP BY customer_loanpackage.loan_code
    //             ORDER BY customer_loanpackage.loan_code ASC
    //         )
	// 		GROUP BY temporary_payment_details.loan_code
	// 		ORDER BY
	// 			customer_loanpackage.start_month = '$payout_month', customer_loanpackage.payout_date ASC";
    // $query = mysql_query($sql);
    // while ($row = mysql_fetch_assoc($query)) {
    //     $loan_code = $row['loan_code'];
    //     $loan_amount = $row['loan_amount'];
    //     $loan_percent = $row['loan_percent'];
    //     $instalment_collected = $row['monthly'];

    //     if ($row['start_month'] == $payout_month) {
    //         $instalment_payout = $loan_amount - ($loan_amount * 0.1);
    //         $total_instalment_payout += $instalment_payout;
    //     }

    //     $sql = "SELECT * FROM $db.loan_payment_details WHERE month_receipt = '$payout_month'";
    //     $q = mysql_query($sql);
    //     while ($res = mysql_fetch_assoc($q)) {
    //         $receipt_no = $res['receipt_no'];
    //         $loan_status = $res['loan_status'];

    //         if ($receipt_no == $loan_code) {
    //             if ($loan_status == 'SETTLE') {
    //                 $total_settle += $loan_percent;
    //             } else if ($loan_status != 'SETTLE' && $loan_status != 'BAD DEBT') {
    //                 $total_instalment_collected += $instalment_collected;
    //             }
    //         }
    //     }
    // }

    // $sql = "SELECT
    //             late_interest_record.loan_code,
    //             customer_details.customercode2,
    //             customer_details.name,
    //             late_interest_record.bd_date,
    //             late_interest_record.amount as amount,
    //             SUM(late_interest_payment_details.amount) as collected,
    //             late_interest_payment_details.payment_date,
    //             late_interest_record.balance as balance
    //         FROM
    //             $db.late_interest_record
    //         LEFT JOIN $db.customer_details ON late_interest_record.customer_id = customer_details.id
    //         LEFT JOIN $db.late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
    //         WHERE late_interest_payment_details.month_receipt = '$payout_month'
    //         GROUP BY late_interest_record.loan_code
    //         ORDER BY late_interest_record.bd_date ASC";
    // $q = mysql_query($sql);                     
    // while ($res = mysql_fetch_assoc($q)) { 
    //     if ($login_branch == 'ANSENG' && $payout_month == '2023-04') {
    //         $total_bd_collected += 0;
    //     } else {
    //         $total_bd_collected += $res['collected'];
    //     }
    // }

    // if ($login_branch == 'ANSENG' && $payout_month == '2023-04') {
    //     $total_bd_collected = 0;
    // }

    // $closing_balance = $opening_balance + $total_instalment_collected + $total_settle + $capital_in + $total_bd_collected + $total_monthly - $total_instalment_payout - $total_expenses - $total_expenses_2 - $total_interest_paid_out - $return_capital;
    // echo 'Opening Balance = ' . $opening_balance . '<br>';
    // echo 'Instalment Collected = ' . $total_instalment_collected . '<br>';
    // echo 'Settle = ' . $total_settle . '<br>';
    // echo 'Capital In = ' . $capital_in . '<br>';
    // echo 'BD Collected = ' . $total_bd_collected . '<br>';
    // echo 'Monthly = ' . $total_monthly . '<br>';
    // echo 'Instalment Payout = ' . $total_instalment_payout . '<br>';
    // echo 'Expenses = ' . $total_expenses . '<br>';
    // echo 'Expenses 2 = ' . $total_expenses_2 . '<br>';
    // echo $total_interest_paid_out . '<br>';
    // echo $return_capital . '<br>';
    // echo $closing_balance . '<br>';

    $sql = "SELECT
                collection.*,
                customer_details.customercode2,
                customer_details.name
            FROM
                $db.collection
            JOIN $db.customer_loanpackage ON customer_loanpackage.loan_code = collection.loan_code
            JOIN $db.customer_details ON customer_details.id = customer_loanpackage.customer_id
            WHERE
                YEAR (collection.datetime) = '$selected_year'
            AND MONTH (collection.datetime) = '$selected_month'";
    $query = mysql_query($sql);
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

   #tbl-return-book-instalment {
		border-collapse:collapse;
		border:none;	
	}

	#tbl-return-book-instalment tr th {
		height:36px;
		background:#666;
		text-align:left;
		padding-left:10px;
		color:#FFF;
	}

	#tbl-return-book-instalment tr td {
		height:35px;
		padding-left:10px;
		padding-right:10px;
	}

    #btn-search-return-book-instalment {
        cursor: pointer;
    }

#print-only-header {
    display: none;
    text-align: center;
}

@media print {
    body * {
        visibility: hidden;
    }

    #print-only-header,
    #print-only-header *,
    #tbl-return-book-instalment,
    #tbl-return-book-instalment * {
        visibility: visible;
    }

    /* Fix the header */
    #print-only-header {
        display: block;
        /* margin-bottom: 10px; */
        position: relative; /* Fix: no absolute */
        margin-top: -14%;
        margin-left: 25%;
        margin-right: 50px;
        width: 100%;
    }

    /* Fix table layout */
    #tbl-return-book-instalment {
        margin-top: 20%;
        margin-left: 50px;
        margin-right: 50px;
        width: 135%; /* Fix: avoid 135% width */
    }

    /* Prevent rows from splitting across pages */
    #tbl-return-book-instalment tr {
        page-break-inside: avoid;
    }

    /* Make table header repeat on each page */
    #tbl-return-book-instalment thead {
        display: table-header-group;
    }

    #tbl-return-book-instalment tfoot {
        display: table-footer-group;
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

    #continued-header {
        display: table-cell !important;
    }
}


</style>
</style>
<body>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Return Book</td>
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
                    	<input type="button" id="search" name="search" value="" onclick="searchReturnBookInstalment();"/>
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
            <a href="return_book_monthly.php">Monthly</a>
            <a href="return_book_instalment.php" id="active-menu">Return Book</a>
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
                         <input class="btn btn-blue" type="button" id="btn-search-return-book-instalment" name="search_return_book_instalment" value="Search" style="width: 100px; height: 30px; font-size:16px;" onclick="searchYearMonth();">
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
    <div style="font-size: 20px; font-weight: bold;">RETURN BOOK</div>
    <div id="print-only-date" style="margin-top: 5px; margin-bottom: -150px; font-size: 16px;"></div>
</div>
<table width="1280" id="tbl-return-book-instalment" >
    <thead>
        <tr class="print-header follow-page-header">
            <th colspan="7" style="text-align:center; font-weight:bold; font-size:16px; display:none;" id="continued-header">
            </th>
        </tr>
        <tr>
            <th width="5%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Date</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Agreement No.</th>
            <th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Customer ID</th>
            <th width="35%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Name</th>
            <th width="15%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Instalment</th>
            <th width="15%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Tepi</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <!-- <tfoot id="print-footer">
        <tr><td colspan="7" style="height: 80px;"></td></tr>
    </tfoot> -->

</table>
<br>

</body>
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

        searchReturnBookInstalment();
        updatePrintOnlyDate();
        $('#selected_year, #selected_month').change(updatePrintOnlyDate);
    });

    function searchReturnBookInstalment() {
        $.ajax({
            url: 'get_return_book_instalment_listing_ajax.php',
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
                $('#tbl-return-book-instalment tbody').html(response);
            }
        });
    }

    function searchYearMonth() {
        let year = $('#selected_year').val();
        let month = $('#selected_month').val();
        window.location.href = 'return_book_instalment.php?year=' + year + '&month=' + month;
    }

</script>