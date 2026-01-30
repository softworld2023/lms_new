<?php
header('Content-Type: application/json; charset=UTF-8');

require_once '../include/dbconnection.php'; // must initialize legacy mysql_* connection
session_start();

$db = $_SESSION['login_database'];

$empty = array('val_mbb' => '', 'val_pbe' => '', 'val_pl' => '');

if (!isset($_POST['date'])) {
    echo json_encode($empty);
    exit;
}

$date = trim($_POST['date']);  // expected 'YYYY-MM-DD'
if ($date === '') {
    echo json_encode($empty);
    exit;
}

$dateEsc = mysql_real_escape_string($date);

/* 1) SAME-DAY close (preferred) */
$sqlSameDay = "
    SELECT pl, mbb, pbe
    FROM $db.cash_in_out_closing
    WHERE DATE(closing_date) = '{$dateEsc}'
    ORDER BY closing_date DESC
    LIMIT 1
";
$q1 = mysql_query($sqlSameDay);
if ($q1 && ($row = mysql_fetch_assoc($q1))) {
    echo json_encode(array(
        'val_mbb' => (isset($row['mbb']) && $row['mbb'] !== '') ? (string)$row['mbb'] : '',
        'val_pbe' => (isset($row['pbe']) && $row['pbe'] !== '') ? (string)$row['pbe'] : '',
        'val_pl'  => (isset($row['pl'])  && $row['pl']  !== '') ? (string)$row['pl']  : ''
    ));
    exit;
}

/* 2) CARRY FORWARD: latest row strictly BEFORE the selected date */
$sqlPrev = "
    SELECT pl, mbb, pbe
    FROM $db.cash_in_out_closing
    WHERE closing_date < '{$dateEsc}'
    ORDER BY closing_date DESC
    LIMIT 1
";
$q2 = mysql_query($sqlPrev);
if ($q2 && ($row2 = mysql_fetch_assoc($q2))) {
    echo json_encode(array(
        'val_mbb' => (isset($row2['mbb']) && $row2['mbb'] !== '') ? (string)$row2['mbb'] : '',
        'val_pbe' => (isset($row2['pbe']) && $row2['pbe'] !== '') ? (string)$row2['pbe'] : '',
        'val_pl'  => (isset($row2['pl'])  && $row2['pl']  !== '') ? (string)$row2['pl']  : ''
    ));
    exit;
}

/* 3) Nothing to carry forward */
echo json_encode($empty);
