<?php
// This is the end of cash in cash out. bye bye.
// Please continue this shit thing if u want feel the life like hell
include_once '../include/dbconnection.php';
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$date  = $_POST['date'] ? $_POST['date'] : date('Y-m-d');

function to_float($v, $default = 0.0) {
    if (!isset($v)) return $default;
    $s = str_replace(',', '', (string)$v);
    $f = (float)$s;
    return is_numeric($s) ? $f : $default;
}

$b7 = to_float(isset($_POST['b7']) ? $_POST['b7'] : null);
$b8 = to_float(isset($_POST['b8']) ? $_POST['b8'] : null);
$b9 = to_float(isset($_POST['b9']) ? $_POST['b9'] : null);

if ($_POST) {
    $db = $_SESSION['login_database'];

    // =============================
    //  1) Check if date exists
    // =============================
    $check_sql = "SELECT id FROM $db.cash_in_out_closing WHERE closing_date = '$date' LIMIT 1";
    $check_q = mysql_query($check_sql);
    $exists = mysql_num_rows($check_q) > 0;

    if ($exists) {
        // =============================
        //  2) Date exists → just update
        // =============================
        $update_sql = "
            UPDATE $db.cash_in_out_closing 
            SET pl = '$b7', mbb = '$b8', pbe = '$b9'
            WHERE closing_date = '$date'
        ";
        $update_q = mysql_query($update_sql);

        if ($update_q) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'fail', 'error' => mysql_error()]);
        }

    } else {
        // =============================
        //  3) Insert new record
        // =============================
        $insert_sql = "
            INSERT INTO $db.cash_in_out_closing 
            SET closing_date = '$date', pl = '$b7', mbb = '$b8', pbe = '$b9'
        ";
        $insert_q = mysql_query($insert_sql);

        if ($insert_q) {
            // =============================
            //  4) Update related tables
            // =============================
            $closing_ins_status_sql = "
                UPDATE $db.customer_loanpackage 
                SET closing_status = 'YES',
                closing_date ='$date'
                WHERE loan_status = 'Paid'
                  AND (closing_status != 'YES' OR closing_status IS NULL)
                  AND payout_date > '2025-11-01';
            ";
            $closing_ins_status = mysql_query($closing_ins_status_sql);

            $closing_mnth_status_sql = "
                UPDATE $db.monthly_payment_record 
                SET closing_status = 'YES',
                closing_date ='$date'
                WHERE (closing_status != 'YES' OR closing_status IS NULL)
                  AND monthly_date > '2025-11-01'
                  AND status != 'DELETED';
            ";
            $closing_mnth_status = mysql_query($closing_mnth_status_sql);

            $collection_status_sql = "
                UPDATE $db.collection 
                SET closing_status = 'YES',
                closing_date ='$date'
                WHERE (closing_status != 'YES' OR closing_status IS NULL)
                  AND datetime > '2025-11-01';
            ";
            $collection_status = mysql_query($collection_status_sql);

            if ($closing_ins_status && $closing_mnth_status && $collection_status) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'fail', 'error' => 'Update related tables failed']);
            }
        } else {
            echo json_encode(['status' => 'fail', 'error' => mysql_error()]);
        }
    }

    exit;
}
?>
