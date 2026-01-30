<?php
// payment/update_cash_in_out_content_ajax.php
header('Content-Type: text/plain; charset=UTF-8');
    session_start();
require_once '../include/dbconnection.php';

$date      = isset($_POST['date'])      ? $_POST['date']      : '';
$tableNo   = isset($_POST['table_no'])  ? (int)$_POST['table_no'] : 0;
$content   = isset($_POST['content'])   ? $_POST['content']   : '';
$b28_curr  = isset($_POST['b28_curr'])  ? $_POST['b28_curr']  : null;

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) { echo 'error'; exit; }
if ($tableNo < 1 || $tableNo > 8) { echo 'error'; exit; }

$db = isset($_SESSION['login_database']) ? $_SESSION['login_database'] : '';
if (!$db) { echo 'error'; exit; }

//
// If we are saving TABLE 8 and we received the live b28 value,
// inject it into the posted HTML so what you SEE is what gets SAVED.
//
$tableNo = isset($_POST['table_no']) ? (int)$_POST['table_no'] : 0;

if ($tableNo === 8 && isset($_POST['b28_curr'])) {
    $safeB28 = htmlspecialchars(trim($_POST['b28_curr']), ENT_QUOTES, 'UTF-8');
    // overwrite #b28 inner text once
    $content = preg_replace(
        '/(<[^>]*\bid=("|\')b28\2[^>]*>\s*)(.*?)(\s*<\/[^>]+>)/is',
        '$1' . $safeB28 . '$4',
        $content,
        1
    );
}

// Basic sanitize for storage (legacy mysql_* style to match your codebase)
$dateEsc    = mysql_real_escape_string($date);
$contentEsc = mysql_real_escape_string($content);

// Target table like `<db>.cash_in_out_table8`
$tableName = $db . '.cash_in_out_table' . $tableNo;

// Upsert by date (assumes `date` is UNIQUE/PRIMARY in these tables)
$sql = "INSERT INTO $tableName (`date`,`content`)
        VALUES ('$dateEsc', '$contentEsc')
        ON DUPLICATE KEY UPDATE `content`=VALUES(`content`)";

            $q = mysql_query($sql);
if (!$q) { echo 'error'; exit; }

                echo 'success';
