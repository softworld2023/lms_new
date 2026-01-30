<?php
session_start();
include( '../lms_dev/include/dbconnection.php' );
include( '../lms_dev/config.php' );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

mysql_query( "SET TIME_ZONE = '+08:00'" );

$id = intval($_POST['id']);
$discount = $_POST['discount'];

$result = mysql_query("UPDATE customer_loanpackage SET discount = '$discount' WHERE id = $id");

if ($result) {
    echo "<div class='success'>Discount has been successfully updated.</div>";
} else {
    echo "<div class='error'>Failed to update discount: " . mysql_error() . "</div>";
}
?>