<?php
session_start();
include( '../include/dbconnection.php' );
include( '../config.php' );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

mysql_query( "SET TIME_ZONE = '+08:00'" );

$id = $_POST[ 'id' ];
$customerid = $_POST['customer_id'];
$loan_code = $_POST['loan_code'];
$discount = $_POST[ 'discount' ];

$id = intval( $id );
// assuming id is numeric

$result = mysql_query( "UPDATE customer_loanpackage SET discount = '$discount' WHERE id = $id" );

if ( $result ) {
    $msg = 'Discount has been successfully updated.<br>';
    $_SESSION[ 'msg' ] = "<div class='success'>{$msg}</div>";
    echo "<script>
            window.history.back();
        </script>";
} else {
    $_SESSION[ 'msg' ] = "<div class='error'>Failed to update discount: " . mysqli_error( $conn ) . '</div>';
    echo "<script>
            window.history.back();
        </script>";
}

?>