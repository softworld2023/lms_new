<?php
session_start();
include("../include/dbconnection.php");

if (isset($_GET['customercode'])) {
    $customercode = $_GET['customercode'];

    $query = mysql_query("SELECT id, name, nric FROM customer_details WHERE customercode2 = '".$customercode."'");
    $result = mysql_fetch_assoc($query);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(null);
    }
}
?>