<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if($_POST['action'] == 'update_discount')
{
	$id = $_POST['id'];
	$discount = $_POST['discount'];

    $id = intval($id); // assuming id is numeric

    $result = mysql_query("UPDATE customer_loanpackage SET discount = '$discount' WHERE id = $id");

	if ($result) {
        $msg = 'Discount has been successfully updated.<br>';
        $_SESSION['msg'] = "<div class='success'>{$msg}</div>";
    } else {
        $_SESSION['msg'] = "<div class='error'>Failed to update discount: " . mysqli_error($conn) . "</div>";
    }
}
?>