<?php
include( 'include/dbconnection.php' );

session_start();
$db = $_SESSION['login_database'];
mysql_select_db($db, $con);

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    die('No ID specified.');
}

// ini_set( 'display_errors', '1' );
// ini_set( 'display_startup_errors', '1' );
// error_reporting( E_ALL );

//select payment record
$pr_q = mysql_query( "SELECT * FROM loan_payment_details LEFT JOIN customer_loanpackage ON customer_loanpackage.id = loan_payment_details.customer_loanid WHERE loan_payment_details.id = '".$id."'" );
$pr = mysql_fetch_assoc( $pr_q );

$loanid = $pr[ 'customer_loanid' ];
$customer_id = $pr['customer_id'];
$loan_code = $pr['loan_code'];

//delete from cashbook
$delete_cb = mysql_query( "DELETE FROM cashbook WHERE package_id = '".$pr[ 'package_id' ]."' AND type = 'RECEIVED' AND table_id = '".$id."' AND code = '".$pr[ 'receipt_no' ]."' AND amount = '".$pr[ 'payment' ]."' AND date LIKE '%".$pr[ 'payment_date' ]."%'" );
if ( $delete_cb )
{
    //select from $bal1
    $bal1_q = mysql_query( "SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr[ 'package_id' ]."' AND received = '".$pr[ 'payment' ]."' AND date LIKE '%".$pr[ 'payment_date' ]."%' ORDER BY id DESC LIMIT 1" );
    $bal1 = mysql_fetch_assoc( $bal1_q );

    //delete from bal1
    $delete_b1 = mysql_query( "DELETE FROM balance_transaction WHERE id = '".$bal1[ 'id' ]."'" );
    if ( $delete_b1 )
    {
        //select from bal2
        $bal2_q = mysql_query( "SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr[ 'package_id' ]."' AND received = '".$pr[ 'payment' ]."' AND bal_date LIKE '%".$pr[ 'payment_date' ]."%' ORDER BY id DESC LIMIT 1" );
        $bal2 = mysql_fetch_assoc( $bal2_q );

        //delete from bal2
        $delete_b2 = mysql_query( "DELETE FROM balance_rec WHERE id = '".$bal2[ 'id' ]."'" );

        if ( $delete_b2 )
        {
            //update payment_rec
            $update_q = mysql_query( "UPDATE loan_payment_details SET month_receipt = '', payment = '', payment_date = '',loan_status = '', deleted_status = 'YES'  WHERE id = '".$id."'" );

            //delete from loan_payment_details	( next payment )
            $delete_payrec = mysql_query( "DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'" );
            $update_package = mysql_query( "UPDATE customer_loanpackage SET loan_status = 'Paid' WHERE loan_code = '".$pr[ 'receipt_no' ]."' " );

            $late_q = mysql_query( "SELECT * FROM late_interest_record WHERE loan_code = '".$pr[ 'receipt_no' ]."' " );
            $late = mysql_fetch_assoc( $late_q );

            //delete from bal2
            $late_q1 = mysql_query( "DELETE FROM late_interest_record WHERE id = '".$late[ 'id' ]."'" );

            $delete_bd = mysql_query( "DELETE FROM baddebt_record WHERE loan_code = '".$pr[ 'receipt_no' ]."' " );

            if ( $update_q )
            {
                $msg = 'Payment has been successfully delete from the record.<br>';

                $_SESSION[ 'msg' ] = "<div class='success'>".$msg.'</div>';
                echo "<script>window.location='discount.php?id=$customer_id&loan_code=$loan_code';</script>";
            }
        }
    }
}
?>