<?php
    include_once '../include/dbconnection.php';
    session_start();
    
    if (isset($_POST)) {
        $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
        $customer_ic = isset($_POST['customer_ic']) ? $_POST['customer_ic'] : '';

        $html = '';

        $sql = "SELECT
                    *
                FROM loansystem.reject_loan
                WHERE status = 'ACTIVE'
                ";

        $login_branch = $_SESSION['login_branch']; 
        
        if ($login_branch == "ANSENG" || $login_branch == "MAJUSAMA" || $login_branch == "YUWANG" || $login_branch == "DK") {
            $sql .= " AND (branch_name = 'ANSENG' OR branch_name = 'MAJUSAMA' OR branch_name = 'YUWANG' OR branch_name = 'DK')";
        }

        if ($customer_name != '') {
            $sql .= " AND customer_name = '$customer_name'";
        }

        if ($customer_ic != '') {
            $sql .= " AND customer_ic = '$customer_ic'";
        }

        $sql .= " ORDER BY reject_date ASC";
        $q = mysql_query($sql);

        $count = 0;

        while ($row = mysql_fetch_assoc($q)) {
            $count++;

            $action = '';

            if ($row['document'] != '') {
                $action .= '<a target="_blank" href="../reject/document/' . strtolower($row['branch_name']) . '/' . $row['id'] . '/' . $row['document'] . '">
                                <img src="../reject/view_doc.png" alt="" style="height:25px;" title="View Reject Document" />
                            </a>';
            }

            $action .= '<a href="../reject/edit_reject.php?id=' . $row['id'] . '" title="Edit" rel="shadowbox; width=600px; height=550px">
                            <img src="../../img/customers/edit-icon.png" />
                        </a>';
            $action .= '<a href="javascript:deleteConfirmation(\'' . $row['id'] . '\')">
                            <img src="../../img/customers/delete-icon.png" title="Delete">
                        </a>';

            $html .= '<tr>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">' . $count . '</td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['branch_name'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['customer_name'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['customer_ic'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['customer_company'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['reject_reason'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . ($row['reject_date'] != '' ? date('d/m/Y', strtotime($row['reject_date'])) : '') . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['customer_contact_number'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['customer_from'] . '</b></td>
                        <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;">' . $action . '</td>
                    </tr>';
        }

        if ($count == 0) {
            $html .= '<tr>
                        <td colspan="10" style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;text-align: center;">- No Records -</td>
                    </tr>';
        }

        header('Content-Type: text/html');
        echo $html;
        exit;
    }
?>