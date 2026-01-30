<?php
    include_once '../include/dbconnection.php';
    session_start();

    if ( isset( $_POST ) ) {
        $selected_year = isset( $_POST[ 'year' ] ) ? $_POST[ 'year' ] : date( 'Y' );
        $selected_month = isset( $_POST[ 'month' ] ) ? $_POST[ 'month' ] : date( 'm' );
        $loan_code = isset( $_POST[ 'loan_code' ] ) ? $_POST[ 'loan_code' ] : '';
        $customer_code = isset( $_POST[ 'customer_code' ] ) ? $_POST[ 'customer_code' ] : '';
        $customer_name = isset( $_POST[ 'customer_name' ] ) ? $_POST[ 'customer_name' ] : '';

        $db = $_SESSION[ 'login_database' ];

        $sql = "SELECT 
                    mpr.id,
                    mpr.loan_code,
                    cd.customercode2 AS customer_code,
                    cd.name AS customer_name,
                    mpr.payout_amount,
                    mpr.monthly_date
                FROM 
                    $db.monthly_payment_record mpr
                JOIN 
                    $db.customer_details cd 
                    ON cd.id = mpr.customer_id
                WHERE 
                    mpr.month = '$selected_year-$selected_month'
                    AND mpr.status != 'DELETED'
                ORDER BY mpr.monthly_date ASC";

        if ( $loan_code != '' ) {
            $sql .= " AND mpr.loan_code = '$loan_code'";
        }

        if ( $customer_code != '' ) {
            $sql .= " AND cd.customercode2 = '$customer_code'";
        }

        if ( $customer_name != '' ) {
            $sql .= " AND cd.name = '$customer_name'";
        }

        $query = mysql_query( $sql );

        $grouped_data = array();

        while ( $row = mysql_fetch_assoc( $query ) ) {
            // Unique key for customer
            $key = $row[ 'loan_code' ];

            if ( !isset( $grouped_data[ $key ] ) ) {
                $grouped_data[ $key ] = array(
                    'loan_code' => $row[ 'loan_code' ],
                    'customer_code' => $row[ 'customer_code' ],
                    'customer_name' => $row[ 'customer_name' ],
                    'first_monthly_date' => $row[ 'monthly_date' ],
                    'total_payout' => 0,
                    'records' => array()
                );
            }

            // Update earliest date
            if ( strtotime( $row[ 'monthly_date' ] ) < strtotime( $grouped_data[ $key ][ 'first_monthly_date' ] ) ) {
                $grouped_data[ $key ][ 'first_monthly_date' ] = $row[ 'monthly_date' ];
            }

            // Add payout amount
            $grouped_data[ $key ][ 'total_payout' ] += $row[ 'payout_amount' ];

            // Add detail record
            $grouped_data[ $key ][ 'records' ][] = array(
                'loan_code' => $row[ 'loan_code' ],
                'monthly_date' => $row[ 'monthly_date' ],
                'payout_amount' => $row[ 'payout_amount' ]
            );
        }

        $html = '';
        $count = 0;

        foreach ( $grouped_data as $group ) {
            $count++;

            $html .= '<tr>
                <td style="border:1px solid black; text-align:center; color: black; font-weight: bold;">' . $count . '</td>
                <td style="border:1px solid black; text-align:center; color: black; font-weight: bold;">' . date( 'd/m/Y', strtotime( $group[ 'first_monthly_date' ] ) ) . '</td>
                <td style="border:1px solid black; text-align:center; color: black; font-weight: bold;">' . htmlspecialchars( $group[ 'loan_code' ] ) . '</td>
                <td style="border:1px solid black; text-align:center; color: black; font-weight: bold;">' . htmlspecialchars( $group[ 'customer_code' ] ) . '</td>
                <td style="border:1px solid black; color: black; font-weight: bold;">' . htmlspecialchars( $group[ 'customer_name' ] ) . '</td>
                <td style="border:1px solid black; text-align:right; color: black; font-weight: bold;">RM ' . number_format( $group[ 'total_payout' ], 2 ) . '</td>
                <td style="border:1px solid black; text-align:center; color: black; font-weight: bold;">';

            if ( count( $group[ 'records' ] ) > 1 ) {
                $html .= '<button onclick="toggleDetails(\'detail-' . $count . '\')">More ▼</button>';
            } else {
                $html .= '-';
            }

            $html .= '</td></tr>';

            if ( count( $group[ 'records' ] ) > 1 ) {
                $html .= '<tr id="detail-' . $count . '" style="display:none;">
                    <td colspan="6">
                        <table border="1" width="100%" style="margin-top:10px;margin-bottom:10px;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">Date</th>
                                    <th style="text-align:center;">Agreement No.</th>
                                    <th style="text-align:right;">Monthly</th>
                                </tr>
                            </thead>
                            <tbody>';

                foreach ( $group[ 'records' ] as $record ) {
                    $html .= '<tr>
                        <td style="text-align:center;">' . date( 'd/m/Y', strtotime( $record[ 'monthly_date' ] ) ) . '</td>
                        <td style="text-align:center;">' . htmlspecialchars( $record[ 'loan_code' ] ) . '</td>
                        <td style="text-align:right;">RM ' . number_format( $record[ 'payout_amount' ], 2 ) . '</td>
                    </tr>';
                }

                $html .= '</tbody>
                        </table>
                    </td>
                </tr>';
            }
        }

        if ( $count == 0 ) {
            $html .= '<tr><td colspan="6" style="text-align:center;">- No Records -</td></tr>';
        }

        header( 'Content-Type: text/html' );
        echo $html;
        exit;
    }
?>