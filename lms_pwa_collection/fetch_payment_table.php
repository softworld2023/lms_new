<?php
include_once 'include/dbconnection.php';
session_start();
$month = isset($_POST['month_receipt']) ? $_POST['month_receipt'] : date('Y-m');
$cust_id = isset($_POST['cust_id']) ? $_POST['cust_id'] : '';
$db = $_SESSION['login_database'];
$sql = "SELECT * FROM $db.loan_payment_details 
        WHERE customer_loanid = '$cust_id' 
        AND month_receipt >= '$month'
        ORDER BY payment_date ASC";

$result = mysql_query($sql);

if (!$result) {
    die('Query failed: ' . mysql_error()); // Shows the SQL error
}

echo '<table border="1" cellpadding="5" cellspacing="0" width="100%">
        <tr>
            <th>Date</th>
            <th>Month Receipt</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>';

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        echo '<tr>
                <td style="width: 30%;">' . htmlspecialchars($row['payment_date']) . '</td>
                <td style="width: 30%; text-align: center;">' . htmlspecialchars($row['month_receipt']) . '</td>
                <td style="width: 30%; text-align: center;">' . number_format($row['payment'], 2) . '</td>
                <td style="text-align:center; vertical-align:middle; width: 10%;">
                    <a href="delete_payment.php?id=' . $row['id'] . '" onclick="return confirm(\'Delete this payment?\')" style="color:red; font-weight:bold; text-decoration:none; font-size:16px;">
                        &times;
                    </a>
                </td>
              </tr>
              <tr></tr>';
    }
} else {
    echo '<tr><td colspan="4">No records found for selected month.</td></tr>';
}

echo '</table>';
?>
