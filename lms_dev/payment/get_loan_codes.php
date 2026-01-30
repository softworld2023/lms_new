<?php
session_start();
include("../include/dbconnection.php");

if (isset($_GET['customercode'])) {
    $customercode = $_GET['customercode'];

	$sql = "SELECT
				id
				FROM customer_details
				WHERE customercode2 = '".$customercode."'";
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);

    // Fetch loan codes for the given customercode
    $loan_query = mysql_query("SELECT loan_code FROM customer_loanpackage WHERE customer_id = '".$result['id']."'");
    $loan_codes = array();

    while ($row = mysql_fetch_assoc($loan_query)) {
        $loan_codes[] = $row;
    }

    echo json_encode($loan_codes);
}
?>