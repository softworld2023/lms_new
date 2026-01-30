<?php
    include('../include/page_header.php');
    session_start();

    $selected_year = isset($_GET['year']) && $_GET['year'] != '' ? $_GET['year'] : date('Y');
	$selected_month = isset($_GET['month']) && $_GET['month'] != '' ? $_GET['month'] : date('m');

    $db = $_SESSION['login_database'];
        
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
   border-top: 1px solid #96d1f8;
   background: #fa9c00;
   background: -webkit-gradient(linear, left top, left bottom, from(#cc7e00), to(#fa9c00));
   background: -webkit-linear-gradient(top, #cc7e00, #fa9c00);
   background: -moz-linear-gradient(top, #cc7e00, #fa9c00);
   background: -ms-linear-gradient(top, #cc7e00, #fa9c00);
   background: -o-linear-gradient(top, #cc7e00, #fa9c00);
   padding: 5px 10px;
   -webkit-border-radius: 8px;
   -moz-border-radius: 8px;
   border-radius: 8px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
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
   color: #FFFFFF;
   }

   #tbl-collection {
		border-collapse:collapse;
		border:none;	
	}

	#tbl-collection tr th {
		height:36px;
		background:#666;
		text-align:left;
		padding-left:10px;
		color:#FFF;
	}

	#tbl-collection tr td {
		height:35px;
		padding-left:10px;
		padding-right:10px;
	}

    #btn-search-collection {
        cursor: pointer;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        z-index: 9999; /* High z-index to sit above content */
        display: none; /* Initially hidden */
        justify-content: center;
        align-items: center;
    }

    #spinner {
        width: 30px;
        height: 30px;
        border: 5px solid rgba(0, 0, 0, 0.1);
        border-top-color: #333;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 100px auto; /* Centers the spinner */
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/payment-received/payment-received.png"></td>
        <td>Collection</td>
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
                    	<input type="button" id="search" name="search" value="" onclick="searchCollection();"/>
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
			<a href="collection.php" id="active-menu">Collection</a>
			<a href="cash_in_out.php">Cash In / Cash Out</a>
			<a href="close_listing.php">Closing History</a>
			<a href="shortInstalment.php">Short Listing</a>
			<a href="half_month.php">Half Month Listing</a>
			<a href="return_book_monthly.php">Return Book (Monthly)</a>
            <a href="return_book_instalment.php">Return Book (Instalment)</a>
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
                         <input class="btn btn-blue" type="button" id="btn-search-collection" name="search_collection" value="Search" style="width: 100px; height: 30px; font-size:16px;" onclick="searchYearMonth();">
                     </td>
                     <td style="font-size: 16px;"></td>
                 </tr>
             </table>
        	<br>
     	</td>
     </tr>
 </table>

 <table width="1280" id="tbl-collection">
    <thead>
        <tr>
            <th width="2%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
            <th width="5%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Collection Date</th>
            <th width="5%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Agreement No.</th>
            <th width="5%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Customer ID</th>
            <th width="15%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Name</th>
            <th width="12%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Salary</th>
            <th width="14%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Type</th>
            <th width="8%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Tepi 1</th>
            <th width="8%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Tepi 2</th>
            <th width="7%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Tepi 2 (Bunga)</th>
            <th width="8%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Balance Received</th>
            <th width="13%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="overlay">
    <div id="spinner" style="display: none;"></div>
</div>

<script>
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

        searchCollection();
    });

    function searchCollection() {
        $.ajax({
            url: 'get_collection_listing_ajax.php',
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
                $('#tbl-collection tbody').html(response);
            }
        });
    }

    function searchYearMonth() {
        let year = $('#selected_year').val();
        let month = $('#selected_month').val();
        window.location.href = 'collection.php?year=' + year + '&month=' + month;
    }

    function showSpinner() {
        $('#spinner').show();
        $('.overlay').css('display', 'flex');
    }

    function approveCollection(collectionId) {
        if (confirm('Confirm approval?') == true) {
            showSpinner();
            $.ajax({
                url: 'collection_action.php',
                type: 'POST',
                data: {
                    action: 'approve',
                    collection_id: collectionId
                },
                dataType: 'json',
                success: function(response1) {
                    console.log(response1);
                    let instalment = response1.amount;

                    if (instalment > 0) {
                        $.ajax({
                            url: '../report/action_payment.php',
                            type: 'POST',
                            data: {
                                action: response1.action,
                                id: response1.id, 
                                amount: instalment,
                                date: response1.date,
                                period: response1.period,
                                month: response1.month,
                                nextdate: response1.next_paymentdate,
                                receipt: response1.receipt_no,
                                prev_receipt: response1.prev_receipt,
                                month_receipt: response1.month_receipt
                            },
                            success: function(response2) {
                                // console.log(response2);
                                $.ajax({
                                    url: 'update_collection_status_ajax.php',
                                    type: 'POST',
                                    data: {
                                        collection_id: collectionId
                                    },
                                    success: function(response3) {
                                        // console.log(response3);
                                        window.location.reload();

                                        $.ajax({
                                            url: 'update_return_book_ajax.php',
                                            type: 'POST',
                                            data: {
                                                collection_id: collectionId
                                            },
                                            success: function(response4) {
                                                console.log(response4);
                                                window.location.reload();
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        $.ajax({
                            url: 'update_collection_status_ajax.php',
                            type: 'POST',
                            data: {
                                collection_id: collectionId
                            },
                            success: function(response3) {
                                // console.log(response3);
                                window.location.reload();

                                $.ajax({
                                    url: 'update_return_book_ajax.php',
                                    type: 'POST',
                                    data: {
                                        collection_id: collectionId
                                    },
                                    success: function(response4) {
                                        console.log(response4);
                                        window.location.reload();
                                    }
                                });
                            }
                        });
                    }
                }
            });
        }
    }

    // function approveCollection2(collectionId) {
    //     if (confirm('Confirm approval?') == true) {
    //         showSpinner();
    //         $.ajax({
    //             url: 'collection_monthly_action.php',
    //             type: 'POST',
    //             data: {
    //                 action: 'approve',
    //                 collection_id: collectionId
    //             },
    //             dataType: 'json',
    //             success: function(response1) {
    //                 console.log(response1);
    //                 let instalment = response1.amount;

    //                 $.ajax({
    //                     url: 'update_collection_status_ajax.php',
    //                     type: 'POST',
    //                     data: {
    //                         collection_id: collectionId
    //                     },
    //                     success: function(response2) {
    //                         $.ajax({
    //                             url: 'update_return_book_ajax.php',
    //                             type: 'POST',
    //                             data: {
    //                                 collection_id: collectionId
    //                             },
    //                             success: function(response3) {
    //                                 console.log(response3);
    //                                 window.location.reload();
    //                             }
    //                         });
    //                     }
    //                 });
    //             }
    //         });
    //     }
    // }

    //for half month
    function approveCollection3(collectionId) {
        if (confirm('Confirm approval?') == true) {
            showSpinner();
            $.ajax({
                url: 'collection_action.php',
                type: 'POST',
                data: {
                    action: 'approve',
                    collection_id: collectionId
                },
                dataType: 'json',
                success: function(response1) {
                    console.log(response1);
                    let balance_half = response1.balance_half;
                    let instalment = response1.amount;
                    let ori_instalment = response1.ori_instalment;
                    if (balance_half == 0) {
                        $.ajax({
                            url: '../report/action_payment.php',
                            type: 'POST',
                            data: {
                                action: response1.action,
                                id: response1.id, 
                                amount: ori_instalment,
                                ori_instalment: ori_instalment,
                                date: response1.date,
                                period: response1.period,
                                month: response1.month,
                                nextdate: response1.next_paymentdate,
                                receipt: response1.receipt_no,
                                prev_receipt: response1.prev_receipt,
                                month_receipt: response1.month_receipt
                            },
                            success: function(response2) {
                                console.log(response2);
                                $.ajax({
                                    url: 'update_collection_status_ajax.php',
                                    type: 'POST',
                                    data: {
                                        collection_id: collectionId
                                    },
                                    success: function(response3) {
                                        console.log(response3);
                                        window.location.reload();

                                        $.ajax({
                                            url: 'update_return_book_ajax.php',
                                            type: 'POST',
                                            data: {
                                                collection_id: collectionId
                                            },
                                            success: function(response4) {
                                                console.log(response4);
                                                window.location.reload();
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        $.ajax({
                            url: 'update_collection_status_ajax.php',
                            type: 'POST',
                            data: {
                                collection_id: collectionId
                            },
                            success: function(response3) {
                                console.log(response3);
                                window.location.reload();

                                $.ajax({
                                    url: 'update_return_book_ajax.php',
                                    type: 'POST',
                                    data: {
                                        collection_id: collectionId
                                    },
                                    success: function(response4) {
                                        console.log(response4);
                                        window.location.reload();
                                    }
                                });
                            }
                        });
                    }
                }
            });
        }
    }

    function deleteCollection(collectionId) {
        if (confirm('Confirm delete this collection?') == true) {
            showSpinner();
            $.ajax({
                url: 'collection_action.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    collection_id: collectionId
                },
                dataType: 'text',
                success: function(response) {
                    console.log(response);
                    window.location.reload();
                }
            });
        }
    }

function deleteBadDebt(bad_debt_id) {
    console.log(bad_debt_id);
    if (confirm('Confirm delete this bad debt?')) {
        window.location.href = 'payLateInt.php?id=' + bad_debt_id;
    }
}

</script>