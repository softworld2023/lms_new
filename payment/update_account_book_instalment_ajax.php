<?php
    include_once '../include/dbconnection.php';
    session_start();

    if (isset($_POST)) {
        $selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y');
        $selected_month = isset($_POST['month']) ? $_POST['month'] : date('m');
        $initial_closing_balance = isset($_POST['initial_closing_balance']) ? $_POST['initial_closing_balance'] : 0;

        $db = $_SESSION['login_database'];
        
        $sql = "SELECT * FROM $db.account_book_instalment WHERE year = '$selected_year' AND month = '$selected_month'";
        $query = mysql_query($sql);
        if (mysql_num_rows($query) > 0) {
            // Update account_book_instalment
            $sql = "UPDATE $db.account_book_instalment SET
                        initial_closing_balance_instalment = '$initial_closing_balance'";
            mysql_query($sql);
        } else {
            // Insert into account_book_instalment
            $sql = "INSERT INTO $db.account_book_instalment SET
                        year = '$selected_year',
                        month = '$selected_month',
                        initial_closing_balance_instalment = '$initial_closing_balance'";
            mysql_query($sql);
        }
        exit;
    }
?>