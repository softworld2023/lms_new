<?php
include_once '../include/dbconnection.php';
session_start();

header('Content-Type: application/json');

if (!isset($_POST['date'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$date = $_POST['date'];
$db = $_SESSION['login_database'];

$check_sql = "SELECT id FROM $db.cash_in_out_closing WHERE closing_date = '$date' LIMIT 1";
$q = mysql_query($check_sql);

if ($q && mysql_num_rows($q) > 0) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}
exit;
?>
