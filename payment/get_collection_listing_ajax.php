<?php
include_once '../include/dbconnection.php';
if (!isset($_SESSION)) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $loan_code     = isset($_POST['loan_code']) ? $_POST['loan_code'] : '';
    $customer_code = isset($_POST['customer_code']) ? $_POST['customer_code'] : '';
    $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

    // Date range (default today)
    $today     = date('Y-m-d');
    $date_from = isset($_POST['date_from']) && $_POST['date_from'] != '' ? $_POST['date_from'] : $today;
    $date_to   = isset($_POST['date_to'])   && $_POST['date_to']   != '' ? $_POST['date_to']   : $today;

    // Ensure from <= to
    if (strtotime($date_from) > strtotime($date_to)) {
        $tmp = $date_from; $date_from = $date_to; $date_to = $tmp;
    }

    // Inclusive full-day range
    $dt_from = $date_from . ' 00:00:00';
    $dt_to   = $date_to   . ' 23:59:59';

    $db = isset($_SESSION['login_database']) ? $_SESSION['login_database'] : '';
    if ($db == '') {
        header('Content-Type: text/html');
        echo '<tr><td colspan="12" style="border:1px solid black;text-align:center;">Session database not found.</td></tr>';
        exit;
    }

    $sql = "SELECT
                collection.*,
                customer_details.customercode2,
                customer_details.name
            FROM
                $db.collection
            LEFT JOIN $db.customer_loanpackage
                ON customer_loanpackage.loan_code = collection.loan_code
            LEFT JOIN $db.customer_details
                ON customer_details.id = customer_loanpackage.customer_id
                OR customer_details.customercode2 = customer_loanpackage.customer_id
            WHERE
                collection.datetime BETWEEN '$dt_from' AND '$dt_to'";

    if ($loan_code != '') {
        $sql .= " AND collection.loan_code = '$loan_code'";
    }

    if ($customer_code != '') {
        $sql .= " AND customer_details.customercode2 = '$customer_code'";
    }

    if ($customer_name != '') {
        $sql .= " AND customer_details.name = '$customer_name'";
        // If want partial match:
        // $sql .= " AND customer_details.name LIKE '%" . mysql_real_escape_string($customer_name) . "%'";
    }

    $sql .= " ORDER BY collection.id ASC";

    $query = mysql_query($sql);

    $html = '';
    $count = 0;

    if ($query) {
        while ($row = mysql_fetch_assoc($query)) {

            $collection_id = $row['id'];
            $datetime      = $row['datetime'];
            $loan_code     = $row['loan_code'];

            $customer_id = $row['customercode2'];
            $name        = $row['name'];

            if (empty($name)) {
                $sql2 = "SELECT *
                         FROM $db.monthly_payment_record mpr
                         LEFT JOIN $db.customer_details cust ON cust.id = mpr.customer_id
                         WHERE mpr.loan_code = '$loan_code'
                         ORDER BY mpr.id DESC
                         LIMIT 1";
                $cust_detail = mysql_fetch_assoc(mysql_query($sql2));
                $name = isset($cust_detail['name']) ? $cust_detail['name'] : '';
                $customer_id = isset($cust_detail['customercode2']) ? $cust_detail['customercode2'] : '';
            }

            $salary          = $row['salary'];
            $salary_type     = $row['salary_type'];
            $instalment      = (!empty($row['collection_amount']) && $row['collection_amount'] > 0) ? $row['collection_amount'] : $row['instalment'];
            $instalment_type = $row['instalment_type'];

            $tepi1           = $row['tepi1'];
            $tepi2           = $row['tepi2'];
            $tepi2_bunga     = $row['tepi2_bunga'];
            $balance_received= $row['balance_received'];

            $filename        = $row['filename'];
            $status          = $row['status'];

            $action = "";

            if (!empty($filename)) {
                $action .= '<a href="/lms_demo/collection_photo/' . $db . '/' . $loan_code . '/' . str_replace(array("-", " ", ":"), "", $datetime) . '/' . $filename . '" target="_blank" title="View photo" style="text-decoration:none;">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="24" height="24" fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M22 13.6477V8.976C22 7.72287 21.9342 6.64762 21.7345 5.74915C21.5323 4.83933 21.1798 4.05065 20.5646 3.43543C19.9494 2.82021 19.1607 2.46772 18.2508 2.26552C17.3524 2.06584 16.2771 2 15.024 2H8.976C7.72287 2 6.64762 2.06584 5.74915 2.26552C4.83933 2.46772 4.05065 2.82021 3.43543 3.43543C2.82021 4.05065 2.46772 4.83933 2.26552 5.74915C2.06584 6.64762 2 7.72287 2 8.976V15.024C2 16.2771 2.06584 17.3524 2.26552 18.2508C2.46772 19.1607 2.82021 19.9494 3.43543 20.5646C4.05065 21.1798 4.83933 21.5323 5.74915 21.7345C6.64762 21.9342 7.72287 22 8.976 22H15.024C16.2771 22 17.3524 21.9342 18.2508 21.7345C19.1607 21.5323 19.9494 21.1798 20.5646 20.5646C21.1798 19.9494 21.5323 19.1607 21.7345 18.2508C21.9342 17.3524 22 16.2771 22 15.024V13.6942C22.0004 13.6787 22.0004 13.6632 22 13.6477ZM4.21788 6.18305C4.066 6.86645 4 7.76851 4 8.976V11.096C4.71987 10.4038 5.39001 9.83748 6.03895 9.42453C6.82527 8.92417 7.6322 8.61574 8.50225 8.61574C9.3723 8.61574 10.1792 8.92417 10.9656 9.42453C11.7421 9.91865 12.5489 10.6324 13.435 11.5185L15.0603 13.1438C16.0436 12.3332 17.0078 11.7735 18.0456 11.6442C18.7292 11.559 19.3739 11.6673 20 11.9198V8.976C20 7.76851 19.934 6.86645 19.7821 6.18305C19.6328 5.51101 19.414 5.11327 19.1504 4.84964C18.8867 4.58602 18.489 4.36724 17.8169 4.21788C17.1336 4.066 16.2315 4 15.024 4H8.976C7.76851 4 6.86645 4.066 6.18305 4.21788C5.51101 4.36724 5.11327 4.58602 4.84964 4.84964C4.58602 5.11327 4.36724 5.51101 4.21788 6.18305Z" fill="#323232"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14 8C14 6.89543 14.8954 6 16 6C17.1046 6 18 6.89543 18 8C18 9.10457 17.1046 10 16 10C14.8954 10 14 9.10457 14 8Z" fill="#323232"/>
                            </svg>
                        </a>';
            }

            if ($instalment_type != 'Monthly') {
                $action .= '<a href="view_collection_pdf.php?id=' . $collection_id . '&loan_code=' . $loan_code . '" target="_blank" title="View pdf" style="text-decoration:none;">
                            <svg width="24px" height="24px" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.5 8H3V7H3.5C3.77614 7 4 7.22386 4 7.5C4 7.77614 3.77614 8 3.5 8Z" fill="#323232"/>
                                <path d="M7 10V7H7.5C7.77614 7 8 7.22386 8 7.5V9.5C8 9.77614 7.77614 10 7.5 10H7Z" fill="#323232"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1 1.5C1 0.671573 1.67157 0 2.5 0H10.7071L14 3.29289V13.5C14 14.3284 13.3284 15 12.5 15H2.5C1.67157 15 1 14.3284 1 13.5V1.5ZM3.5 6H2V11H3V9H3.5C4.32843 9 5 8.32843 5 7.5C5 6.67157 4.32843 6 3.5 6ZM7.5 6H6V11H7.5C8.32843 11 9 10.3284 9 9.5V7.5C9 6.67157 8.32843 6 7.5 6ZM10 11V6H13V7H11V8H12V9H11V11H10Z" fill="#323232"/>
                            </svg>
                        </a>';
            }

            if ($status == 'PENDING' && $instalment_type != 'Monthly' && $instalment_type != 'Half Month') {
                $action .= '<button id="btn-approve" onclick="approveCollection(\'' . $collection_id . '\')" title="Approve" style="cursor:pointer;background:transparent;border:none;">
                    <svg width="24px" height="24px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                      <path fill="#333333" d="M256,43.5C138.64,43.5,43.5,138.64,43.5,256S138.64,468.5,256,468.5S468.5,373.36,468.5,256S373.36,43.5,256,43.5z M378.81,222.92L249.88,351.86c-7.95,7.95-18.52,12.33-29.76,12.33s-21.81-4.38-29.76-12.33l-57.17-57.17c-8.3-8.3-12.87-19.35-12.87-31.11s4.57-22.81,12.87-31.11c8.31-8.31,19.36-12.89,31.11-12.89s22.8,4.58,31.11,12.89l24.71,24.7l96.47-96.47c8.31-8.31,19.36-12.89,31.11-12.89c11.75,0,22.8,4.58,31.11,12.89c8.3,8.3,12.87,19.35,12.87,31.11S387.11,214.62,378.81,222.92z"/>
                    </svg>
                </button>';
            }

            if ($status == 'PENDING' && $instalment_type == 'Monthly') {
                $action .= '<button id="btn-approve" onclick="approveCollection(\'' . $collection_id . '\')" title="Approve" style="cursor:pointer;background:transparent;border:none;">
                    <svg width="24px" height="24px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                      <path fill="#333333" d="M256,43.5C138.64,43.5,43.5,138.64,43.5,256S138.64,468.5,256,468.5S468.5,373.36,468.5,256S373.36,43.5,256,43.5z M378.81,222.92L249.88,351.86c-7.95,7.95-18.52,12.33-29.76,12.33s-21.81-4.38-29.76-12.33l-57.17-57.17c-8.3-8.3-12.87-19.35-12.87-31.11s4.57-22.81,12.87-31.11c8.31-8.31,19.36-12.89,31.11-12.89s22.8,4.58,31.11,12.89l24.71,24.7l96.47-96.47c8.31-8.31,19.36-12.89,31.11-12.89c11.75,0,22.8,4.58,31.11,12.89c8.3,8.3,12.87,19.35,12.87,31.11S387.11,214.62,378.81,222.92z"/>
                    </svg>
                </button>';
            }

            if ($status == 'PENDING' && $instalment_type == 'Half Month') {
                $action .= '<button id="btn-approve" onclick="approveCollection3(\'' . $collection_id . '\')" title="Approve" style="cursor:pointer;background:transparent;border:none;">
                    <svg width="24px" height="24px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                      <path fill="#333333" d="M256,43.5C138.64,43.5,43.5,138.64,43.5,256S138.64,468.5,256,468.5S468.5,373.36,468.5,256S373.36,43.5,256,43.5z M378.81,222.92L249.88,351.86c-7.95,7.95-18.52,12.33-29.76,12.33s-21.81-4.38-29.76-12.33l-57.17-57.17c-8.3-8.3-12.87-19.35-12.87-31.11s4.57-22.81,12.87-31.11c8.31-8.31,19.36-12.89,31.11-12.89s22.8,4.58,31.11,12.89l24.71,24.7l96.47-96.47c8.31-8.31,19.36-12.89,31.11-12.89c11.75,0,22.8,4.58,31.11,12.89c8.3,8.3,12.87,19.35,12.87,31.11S387.11,214.62,378.81,222.92z"/>
                    </svg>
                </button>';
            }

            // DELETE button logic (same as your latest code)
            if ($salary_type == 'Bad Debt') {
                $bad_debt_id = '';
                $bd_sql = "SELECT id FROM $db.late_interest_record WHERE loan_code = '$loan_code'";
                $bd_query = mysql_query($bd_sql);
                $bd_row = $bd_query ? mysql_fetch_assoc($bd_query) : null;
                if ($bd_row) $bad_debt_id = $bd_row['id'];

                if ($bad_debt_id != '') {
                    $action .= '<button id="btn-delete" onclick="deleteBadDebt(\'' . $bad_debt_id . '\')" title="Delete Bad Debt" style="cursor:pointer;background:transparent;border:none;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#323232" width="24px" height="24px" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                            </button>';
                }
            } else {
                if ($instalment_type == 'Half Month' && $status == 'APPROVED') {
                    $latest_sql = "SELECT id
                                   FROM $db.collection
                                   WHERE loan_code = '$loan_code'
                                     AND instalment_type = 'Half Month'
                                   ORDER BY datetime DESC
                                   LIMIT 1";
                    $latest_res = mysql_query($latest_sql);
                    $latest_row = $latest_res ? mysql_fetch_assoc($latest_res) : null;
                    $latest_halfmonth_id = $latest_row ? $latest_row['id'] : null;

                    if ($collection_id == $latest_halfmonth_id) {
                        $action .= '<button id="btn-delete" onclick="deleteCollection(\'' . $collection_id . '\')" title="Delete" style="cursor:pointer;background:transparent;border:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#323232" width="24px" height="24px" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>';
                    }
                } else {
                    $action .= '<button id="btn-delete" onclick="deleteCollection(\'' . $collection_id . '\')" title="Delete" style="cursor:pointer;background:transparent;border:none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#323232" width="24px" height="24px" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                </button>';
                }
            }

            $count++;

            $html .= '<tr>
                <td style="border-right:1px solid black;border-bottom:1px solid black;border-left:1px solid black;color:black;text-align:center;">' . $count . '</td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;"><b>' . date('d/m/Y', strtotime($datetime)) . '</b></td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;"><b>' . $loan_code . '</b></td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;"><b>' . $customer_id . '</b></td>
                <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>' . $name . '</b></td>';

            // Salary/instalment columns (kept from your logic)
            if ($salary_type == 'Gaji' && $instalment_type != 'Monthly') {
                $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($salary, 2) . '(' . $salary_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($instalment, 2) . ' (' . $instalment_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi1, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2_bunga, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($balance_received, 2) . '</b></td>';
            } else if ($salary_type == 'Short') {
                $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>Short</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($instalment, 2) . ' (' . $instalment_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi1, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2_bunga, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>-</b></td>';
            } else if ($salary_type == 'Bad Debt') {
                $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>Bad Debt</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($instalment, 2) . ' (' . $instalment_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>-</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>-</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>-</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>-</b></td>';
            } else if ($salary_type == 'settlement') {
                $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>Settlement</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($instalment, 2) . ' (' . $instalment_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi1, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2_bunga, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($balance_received, 2) . '</b></td>';
            } else if ($salary_type == 'Gaji' && $instalment_type == 'Monthly') {
                $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($salary, 2) . '(' . $salary_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>' . $instalment_type . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi1, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2_bunga, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($balance_received, 2) . '</b></td>';
            } else {
                $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($salary, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($instalment, 2) . ' (' . $instalment_type . ')</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi1, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($tepi2_bunga, 2) . '</b></td>
                          <td style="border-right:1px solid black;border-bottom:1px solid black;color:black;"><b>RM ' . number_format($balance_received, 2) . '</b></td>';
            }

            $html .= '<td style="border-right:1px solid black;border-bottom:1px solid black;color:black;text-align:center;">' . $action . '</td>
            </tr>';
        }
    }

    if ($count == 0) {
        $html .= '<tr>
            <td colspan="12" style="border-right:1px solid black;border-bottom:1px solid black;border-left:1px solid black;color:black;text-align:center;">- No Records -</td>
        </tr>';
    }

    header('Content-Type: text/html');
    echo $html;
    exit;
}
?>
