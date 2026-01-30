<?php
// ==========================================
//  get_closing_cash_in_out_ajax.php
//  Purpose: Fetch closing cash in/out records
// ==========================================
include('../include/dbconnection.php');
session_start();

// =============================
//  Initialize variables
// =============================
header('Content-Type: application/json');

$db = isset($_SESSION['login_database']) ? $_SESSION['login_database'] : '';
if (empty($db)) {
    echo json_encode(['status' => 'error', 'message' => 'No database selected']);
    exit;
}

// =============================
//  Fetch Data
// =============================
$sql = "SELECT * FROM $db.cash_in_out_closing ORDER BY closing_date DESC";
$result = mysql_query($sql);

// =============================
//  Build JSON response
// =============================
$data = [];
$no = 1;

while ($row = mysql_fetch_assoc($result)) {
    $data[] = [
        'no' => $no++,
        'closing_date' => $row['closing_date'],
        'id' => $row['id']
    ];
}

echo json_encode([
    'status' => 'success',
    'data' => $data
]);
exit;
?>