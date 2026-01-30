<?php
    include_once '../include/dbconnection.php';
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    date_default_timezone_set('Asia/Kuala_Lumpur');

    $collection_id = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    $db = $_SESSION['login_database'];

    if($action == 'approve') {
        $sql = "SELECT * FROM $db.collection c LEFT JOIN $db.monthly_payment_record r ON r.loan_code = c.loan_code WHERE c.id = '$collection_id' ";
        $query = mysql_query($sql);
        $result = mysql_fetch_assoc($query);

        $loan_code = $result['loan_code'];
        $payment_date = date('Y-m-d');
        $customer_id = $result['customer_id'];
        $package_id = $result['package_id'];
        $tepi1 = $result['tepi1'];
        $tepi1_month = $result['tepi1_month'];

        $payout_amount = $tepi1;

        // Check the latest record first
        $sql = "SELECT id, balance FROM $db.monthly_payment_record WHERE loan_code = '$loan_code' AND balance > 0 AND status != 'DELETED'";
        $query = mysql_query($sql);
        $count = 0;
        $remaining_payout_amount = $payout_amount;
        while ($row = mysql_fetch_assoc($query)) {
            $mprid = $row['id'];
            $balance = $row['balance'];
                        
            $net_balance = $balance - $remaining_payout_amount;
            if ($net_balance < 0) {
                $remaining_payout_amount -= $balance;
                $net_balance = 0;
            }

            $sql = "INSERT INTO $db.monthly_payment_details SET
                            mprid = '$mprid', 
                            payment_amount = '$payout_amount', 
                            balance = '0',
                            payment_date = '$payment_date', 
                            created_by = '".$_SESSION['login_name']."', 
                            created_date = now(),
                            collection_id = '$collection_id',
                            collection_status = 'PENDING' ";
            $q = mysql_query($sql);

            if ($q) {
                if ($net_balance <= 0) {
                    $sql = "UPDATE $db.monthly_payment_record SET
                                balance = '" . number_format($net_balance, 2, '.', '') . "',
                                status = 'FINISHED',
                                payment_date = '$payment_date'
                                WHERE id = '$mprid'";
                    $q = mysql_query($sql);
                } else {
                    $sql = "UPDATE $db.monthly_payment_record SET
                                balance = '" . number_format($net_balance, 2, '.', '') . "',
                                status = 'PAID',
                                payment_date = '$payment_date'
                                WHERE id = '$mprid'";
                    $q = mysql_query($sql);
                }
            }
        }

        $msg = 'Payment has been successfully saved into record.<br>';
        $_SESSION['msg'] = "<div class='success'>".$msg."</div>";

        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'amount' => $tepi1]);
        exit;

    } 
?>