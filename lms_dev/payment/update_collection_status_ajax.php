<?php
    include_once '../include/dbconnection.php';
    session_start();

    if (isset($_POST)) {
        $collection_id = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';

        $db = $_SESSION['login_database'];
        $user_id = $_SESSION['taplogin_id'];

        // Update collection
        $sql = "UPDATE $db.collection SET
                    status = 'APPROVED',
                    approved_by_id = '$user_id'
                WHERE id = '$collection_id'";
        $query = mysql_query($sql);
        
        // Update collection_status in late_interest_payment_details
        $sql2 = "UPDATE $db.late_interest_payment_details SET
                    collection_status = 'APPROVED'
                WHERE collection_id = '$collection_id'";
        $query2 = mysql_query($sql2);

        // Update collection_status in monthly_payment_details
        $sql3 = "UPDATE $db.monthly_payment_details SET
                    collection_status = 'APPROVED'
                WHERE collection_id = '$collection_id'";
        $query3 = mysql_query($sql3);
        exit;
    }
?>