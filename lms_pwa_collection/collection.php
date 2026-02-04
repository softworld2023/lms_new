<?php
include_once 'include/dbconnection.php';

// ini_set( 'display_errors', 1 );
// ini_set( 'display_startup_errors', 1 );
// error_reporting( E_ALL );
mysql_query("SET TIME_ZONE = '+08:00'");
$branch = strtoupper( $_POST[ 'branch' ] );
$db = '';
switch ( $branch ) {
    case 'MJ':
    $db = 'majusama' . $db_suffix;
    break;
    case 'MJ2':
    $db = 'majusama2' . $db_suffix;
    break;
    case 'ANSENG':
    $db = 'anseng' . $db_suffix;
    break;
    case 'YUWANG':
    $db = 'yuwang' . $db_suffix;
    break;
    case 'KTL':
    $db = 'ktl' . $db_suffix;
    break;
    case 'TSY':
    $db = 'tsy' . $db_suffix;
    break;
    case 'TSY2':
    $db = 'tsy2' . $db_suffix;
    break;
    case 'DEMO':
    $db = 'lms_demo';
    break;
    case 'DK':
    $db = 'dk' . $db_suffix;
    break;
}

if ( isset( $_POST ) && $_POST[ 'instalment_type' ] === 'Bad Debt' ) {

    if ( $db != '' ) {
        $branch_q = mysql_query( "SELECT * FROM $db.branch WHERE branch_name = '".$branch."'" );
        $branch = mysql_fetch_assoc( $branch_q );
        $branch_id = $branch[ 'id' ];

        $loan_code = isset( $_POST[ 'loan_code' ] ) ? $_POST[ 'loan_code' ] : '';
        if ($loan_code == '') {
            echo 'Loan code cannot be empty.';
            exit;
        }
        $lid_q = mysql_query( "SELECT id, package_id FROM $db.late_interest_record WHERE loan_code = '".$loan_code."'" );
        if ( mysql_num_rows( $lid_q ) == 0 ) {
            echo 'No bad debt found for the provided loan code.';
            exit;

        }
        $lids = mysql_fetch_assoc( $lid_q );
        $lid = $lids[ 'id' ];
        $package_id = $lids[ 'package_id' ];

        $date = date( 'Y-m-d' );
        $amount = isset( $_POST[ 'instalment' ] ) && !empty( $_POST[ 'instalment' ] ) ? $_POST[ 'instalment' ] : 0;
        $month_receipt = $_POST[ 'instalment_month' ];

        $username = $_COOKIE[ 'lms_collection_username' ];

        //late interest record
        //lir = loan interest
        $lir_q = mysql_query( "SELECT * FROM $db.late_interest_record
                                    WHERE id = '".$lid."'" );
        $lir = mysql_fetch_assoc( $lir_q );

        //cust info
        $cust_q = mysql_query( "SELECT * FROM $db.customer_details
                                    WHERE id = '".$lir[ 'customer_id' ]."'" );
        $cust = mysql_fetch_assoc( $cust_q );

        //check package receipt
        $rt_q = mysql_query( "SELECT * FROM $db.loan_scheme WHERE id = '".$package_id."'" );
        $get_rt = mysql_fetch_assoc( $rt_q );

        //is this trans something wrong?
        $trans = 'LATE INT - '.$cust[ 'name' ].' - '.$lir[ 'loan_code' ];

        //there's no wrong here bro...
            $balance = $lir['balance'] - $amount;
            
            if($balance <= 0)
            {
                $balance = 0;
                $update_q2 = mysql_query("UPDATE $db.customer_details SET blacklist = ' ' WHERE id = '".$lir['customer_id']."'");

            }
 
            //insert a new record in this table
            $insert_q = mysql_query("INSERT INTO $db.late_interest_payment_details SET
                                    lid = '".$lid."', 
                                    amount = '".$amount."', 
                                    balance = '".$balance."',
                                    payment_date = '".$date."', 
                                    month_receipt = '".$month_receipt."',
                                    created_by = '".$username."', 
                                    collection_status = 'PENDING',
                                    created_date = now()");
            
            if($insert_q)
            {
                $late_payment_id = mysql_insert_id();
                //then update the late_interest_record,
                // each customer has only one record
                $update = mysql_query("UPDATE $db.late_interest_record
                                        SET balance = '".$balance."'
                                        WHERE id = '".$lid."'");
                
                if($update)
                {
                    //After it's updated, insert a new records in cashbook
        $insert_c_q = mysql_query( "INSERT INTO $db.cashbook 
                                                SET type = 'RECEIVED2', 
                                                package_id = '".$package_id."', 
                                                table_id = '".$lid."', 
                                                transaction = '".$trans."', 
                                                amount = '".$amount."', 
                                                date = '".$date."', 
                                                branch_id = '".$branch_id."', 
                                                branch_name = '".$branch."', 
                                                staff_name = '".$username."', 
                                                receipt_no = 'LATE INT ".$lir[ 'loan_code' ]."', 
                                                created_date = now()" );
        if ( $insert_c_q ) {
            if ( $get_rt[ 'receipt_type' ] == '1' ) {
                $rcpmth = date( 'Y-m', strtotime( $date ) );
                $insert_b2q = mysql_query( "INSERT INTO $db.balance_rec 
                                                        SET package_id = '".$package_id."', 
                                                        interest = '".$amount."', 
                                                        bal_date = '".$date."', 
                                                        month_receipt = '".$rcpmth."', 
                                                        branch_id = '".$branch_id."', 
                                                        branch_name = '".$branch."'" );

                $datetime = date( 'Y-m-d H:i:s' );
                $upload_dir = "../collection_photo/$db/$loan_code/" . str_replace( array( '-', ' ', ':' ), '', $datetime );

                if ( isset( $_FILES[ 'photo' ] ) ) {
                    if ( !is_dir( $upload_dir ) ) {
                        // Create the directory if it doesn't exist
                                    mkdir($upload_dir, 0777, TRUE);
                                }

                                $uploaded_photo = $_FILES['photo'];

                                // Get the file name and extension
                                $filename = basename($uploaded_photo['name']);
                                // $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                $file_path = $upload_dir . '/' . $filename;

                                $is_uploaded = move_uploaded_file($uploaded_photo['tmp_name'], $file_path);
                            }else {
                                $filename = "";
                            }

                            $sql = "SELECT id FROM $db.user WHERE username = '$username'";
                            $query = mysql_query($sql);
                            $res = mysql_fetch_assoc($query);
                            $user_id = $res['id'];

                            $sql = "INSERT INTO $db.collection SET
                                        loan_code = '$loan_code',
                                        salary = 0,
                                        salary_type = 'Bad Debt',
                                        instalment = '$amount',
                                        instalment_type = 'Instalment',
                                        instalment_month = '$month_receipt',
                                        filename = '$filename',
                                        datetime = '$datetime',
                                        submitted_by_id = '$user_id'
                                    ";
                            // var_dump($sql);
                            $query = mysql_query($sql);
                            if ($query) {
                                $collection_id = mysql_insert_id();
                                $update_q = mysql_query("UPDATE $db.late_interest_payment_details SET collection_id = '".$collection_id."' WHERE id = '".$late_payment_id."'");
                                echo 'success';
                            } else {
                                echo 'fail';
                            }
                        }else {
                            echo 'success';
                        }
                    }   
                }
            }
        }
        exit();  

} else if (isset($_POST) && $_POST['instalment_type'] === "Monthly"){

        session_start();
        if ( $db != '' ) {
            $loan_code = $_POST['loan_code'];
            $customercode = $_POST['customercode'];
            $salary = isset($_POST['salary']) && !empty($_POST['salary']) ? $_POST['salary'] : 0;
            $salary_type = $_POST['salary_type'];
            $amount = isset($_POST['instalment']) && !empty($_POST['instalment']) ? $_POST['instalment'] : 0;
            $instalment_type = $_POST['instalment_type'];
            $instalment_month = $_POST['instalment_month'];
            $tepi2 = isset($_POST['tepi2']) && !empty($_POST['tepi2']) ? $_POST['tepi2'] : 0;
            $tepi2_month = $_POST['tepi2_month'];
            $tepi2_bunga = isset($_POST['tepi2_bunga']) && !empty($_POST['tepi2_bunga']) ? $_POST['tepi2_bunga'] : 0;
            $balance_received = isset($_POST['balance_received']) && !empty($_POST['balance_received']) ? $_POST['balance_received'] : 0;
            $username = $_COOKIE[ 'lms_collection_username' ];
            $sql = "SELECT id FROM $db.user WHERE username = '$username'";
            $query = mysql_query($sql);
            $res = mysql_fetch_assoc($query);
            $user_id = $res['id'];
            $date = date('Y-m-d', strtotime($_POST['instalment_month']));
            $date_ym = date('Y-m', strtotime($_POST['instalment_month']));

            if ($loan_code == '') {
                echo 'Loan code cannot be empty.';
                exit;
            }

            if ($amount < 0) {
                echo 'Monthly amount cannot be negative.';
                exit; 
            }
            
            $lir_q = mysql_query("SELECT * FROM $db.monthly_payment_record WHERE loan_code = '".$loan_code."' AND status = 'PAID' ORDER BY id ASC");
            $totalamount = 0;
            $balance = 0;

            while ($lir = mysql_fetch_assoc($lir_q)) {
                $totalamount += $lir['balance']; // sum all balances
            }

            // Now calculate balance against $amount
            $balance = $totalamount - $amount;
                
            if($balance < 0) {
                echo 'Fail to record. The maximum payout amount is ' . $totalamount .'.';
            }else {
                if ( isset( $_FILES[ 'photo' ] ) ) {
                    $datetime = date( 'Y-m-d H:i:s' );
                    $upload_dir = "../collection_photo/$db/$loan_code/" . str_replace( array( '-', ' ', ':' ), '', $datetime );
                    
                    if ( !is_dir( $upload_dir ) ) {
                        // Create the directory if it doesn't exist
                        mkdir($upload_dir, 0777, TRUE);
                    }

                    $uploaded_photo = $_FILES['photo'];

                    // Get the file name and extension
                    $filename = basename($uploaded_photo['name']);
                    // $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $file_path = $upload_dir . '/' . $filename;

                    $is_uploaded = move_uploaded_file($uploaded_photo['tmp_name'], $file_path);
                }else {
                    $filename = "";
                }
            
                $sql = "INSERT INTO $db.collection SET
                        loan_code = '$loan_code',
                        salary = '$salary',
                        salary_type = '$salary_type',
                        instalment = '0',
                        instalment_type = 'Monthly',
                        instalment_month = '".$date_ym."',
                        tepi1 = '$amount',
                        tepi1_month = '$date_ym',
                        tepi2 = '$tepi2',
                        tepi2_month = '$tepi2_month',
                        tepi2_bunga = '$tepi2_bunga',
                        balance_received = '$balance_received',
                        datetime = '".date('Y-m-d H:i:s')."',
                        filename = '$filename',
                        submitted_by_id = '$user_id',
                        status = 'PENDING'
                    ";
                $query = mysql_query($sql);
                if ($query) {
                    echo 'success';
                } else {
                    echo 'fail';
                }
            }    
        }
} else if (isset($_POST) && $_POST['instalment_type'] === "Half Month"){
    $branch = strtoupper($_POST['branch']);
    $loan_code = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
    $salary = isset($_POST['salary']) && !empty($_POST['salary']) ? $_POST['salary'] : 0;
    $salary_type = $_POST['salary_type'];
    $instalment = isset($_POST['instalment']) && !empty($_POST['instalment']) ? $_POST['instalment'] : 0;
    $instalment_type = $_POST['instalment_type'];
    $instalment_month = $_POST['instalment_month'];
    $tepi1 = isset($_POST['tepi1']) && !empty($_POST['tepi1']) ? $_POST['tepi1'] : 0;
    $tepi1_month = $_POST['tepi1_month'];
    $tepi2 = isset($_POST['tepi2']) && !empty($_POST['tepi2']) ? $_POST['tepi2'] : 0;
    $tepi2_month = $_POST['tepi2_month'];
    $tepi2_bunga = isset($_POST['tepi2_bunga']) && !empty($_POST['tepi2_bunga']) ? $_POST['tepi2_bunga'] : 0;
    $balance_received = isset($_POST['balance_received']) && !empty($_POST['balance_received']) ? $_POST['balance_received'] : 0;
    $customercode = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';

    $username = $_COOKIE['lms_collection_username'];
    $datetime = date('Y-m-d H:i:s');
    $filename = ''; // default empty filename

    if ($db != '') {
        if ($loan_code == '') {
            echo 'Loan code cannot be empty.';
            exit;
        }

        if ($salary < 0 || $instalment < 0) {
            echo 'All numbers cannot be negative.';
            exit;
        }

        // Handle photo upload if exists
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "../collection_photo/$db/$loan_code/" . str_replace(array('-', ' ', ':'), '', $datetime);

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $uploaded_photo = $_FILES['photo'];
            $filename = basename($uploaded_photo['name']);
            $file_path = $upload_dir . '/' . $filename;

            // Try upload
            if (!move_uploaded_file($uploaded_photo['tmp_name'], $file_path)) {
                echo 'Failed to upload receipt photo.';
                exit;
            }
        }

        // Continue with saving to DB
        $sql = "SELECT id FROM $db.user WHERE username = '$username'";
        $query = mysql_query($sql);
        $res = mysql_fetch_assoc($query);
        $user_id = $res['id'];

        $sql = "INSERT INTO $db.collection SET
            loan_code = '$loan_code',
            salary = '$salary',
            salary_type = '$salary_type',
            instalment = '$instalment',
            instalment_type = '$instalment_type',
            instalment_month = '$instalment_month',
            tepi1 = '$tepi1',
            tepi1_month = '$tepi1_month',
            tepi2 = '$tepi2',
            tepi2_month = '$tepi2_month',
            tepi2_bunga = '$tepi2_bunga',
            balance_received = '$balance_received',
            filename = '$filename',
            datetime = '$datetime',
            submitted_by_id = '$user_id'";

        $query = mysql_query($sql);
        echo $query ? 'success' : 'fail';
    }


}else{
        $branch = strtoupper($_POST['branch']);
        $loan_code = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
        $salary = isset($_POST['salary']) && !empty($_POST['salary']) ? $_POST['salary'] : 0;
        $salary_type = $_POST['salary_type'];
        $instalment = isset($_POST['instalment']) && !empty($_POST['instalment']) ? $_POST['instalment'] : 0;
        $instalment_type = $_POST['instalment_type'];
        $instalment_month = $_POST['instalment_month'];
        $tepi1 = isset($_POST['tepi1']) && !empty($_POST['tepi1']) ? $_POST['tepi1'] : 0;
        $tepi1_month = $_POST['tepi1_month'];
        $tepi2 = isset($_POST['tepi2']) && !empty($_POST['tepi2']) ? $_POST['tepi2'] : 0;
        $tepi2_month = $_POST['tepi2_month'];
        $tepi2_bunga = isset($_POST['tepi2_bunga']) && !empty($_POST['tepi2_bunga']) ? $_POST['tepi2_bunga'] : 0;
        $balance_received = isset($_POST['balance_received']) && !empty($_POST['balance_received']) ? $_POST['balance_received'] : 0;
        $customercode = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';

        $username = $_COOKIE['lms_collection_username'];

        $message = '';

        if ($db != '') {
            if ($loan_code == '') {
                echo 'Loan code cannot be empty.';
                exit;
            }

            if ($salary < 0 || $instalment < 0 || $tepi1 < 0 || $tepi2 < 0 || $tepi2_bunga < 0) {
                echo 'All numbers cannot be negative.';
                exit; 
            }

            if (!isset($_FILES['photo'])) {
                echo 'Receipt photo is required.';
                exit;
            }

            $datetime = date('Y-m-d H:i:s');
            $upload_dir = "../collection_photo/$db/$loan_code/" . str_replace(array('-', ' ', ':'), '', $datetime);

            if (!is_dir($upload_dir)) {
                // Create the directory if it doesn't exist
                        mkdir( $upload_dir, 0777, TRUE );
                    }

                    $uploaded_photo = $_FILES[ 'photo' ];

                    // Get the file name and extension
                    $filename = basename( $uploaded_photo[ 'name' ] );
                    // $file_ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
                    $file_path = $upload_dir . '/' . $filename;

                    $is_uploaded = move_uploaded_file( $uploaded_photo[ 'tmp_name' ], $file_path );
                    if ( $is_uploaded ) {
                        $sql = "SELECT id FROM $db.user WHERE username = '$username'";
                        $query = mysql_query( $sql );
                        $res = mysql_fetch_assoc( $query );
                        $user_id = $res[ 'id' ];

                        $sql = "INSERT INTO $db.collection SET
                            loan_code = '$loan_code',
                            salary = '$salary',
                            salary_type = '$salary_type',
                            instalment = '$instalment',
                            instalment_type = '$instalment_type',
                            instalment_month = '$instalment_month',
                            tepi1 = '$tepi1',
                            tepi1_month = '$tepi1_month',
                            tepi2 = '$tepi2',
                            tepi2_month = '$tepi2_month',
                            tepi2_bunga = '$tepi2_bunga',
                            balance_received = '$balance_received',
                            filename = '$filename',
                            datetime = '$datetime',
                            submitted_by_id = '$user_id'
                        ";
                        // var_dump( $sql );
                        $query = mysql_query( $sql );
                        if ( $query ) {
                            echo 'success';
                        } else {
                            echo 'fail';
                        }
                    } else {
                        echo 'Failed to upload receipt photo.';
                    }

                    exit;
                }
}

?>