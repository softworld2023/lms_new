<?php
    include_once '../include/dbconnection.php';
    session_start();
    
    if (isset($_POST)) {
        $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
        $customer_ic = isset($_POST['customer_ic']) ? $_POST['customer_ic'] : '';

        $html = '';
        $db_name_arr = array();

        $login_branch = $_SESSION['login_branch']; 
        
        if ($login_branch == 'KTL' || $login_branch == 'TSY') {
			$db_name_arr = array('majusama', 'anseng', 'yuwang', 'ktl', 'tsy');
		} else {
			$db_name_arr = array('majusama', 'anseng', 'yuwang');
		}

        $count = 0;

        foreach ($db_name_arr as $db_name) {
            $sql = "SELECT
                        t1.*,
                        t2.loan_code,
                        SUM(t2.loan_amount) AS loan_amount,
                        t3.company
                    FROM $db_name.customer_details t1
                    LEFT JOIN $db_name.customer_loanpackage t2 ON t2.customer_id = t1.id
                    LEFT JOIN $db_name.customer_employment t3 ON t3.customer_id = t1.id
                    WHERE 1=1";
    
            if ($customer_name != '') {
                $sql .= " AND t1.name = '$customer_name'";
            }
    
            if ($customer_ic != '') {
                $sql .= " AND t1.nric = '$customer_ic'";
            }
 
            $q = mysql_query($sql);

            while ($row = mysql_fetch_assoc($q)) {
                $id = $row['id'];
                if (empty($id)) {
                    continue;
                }
                $name = $row['name'];
                $loan_amount = !empty($row['loan_amount']) ? $row['loan_amount'] : 0;
                $count++;
    
                $action = '<a href="../customers/view_customer.php?id=' . $id . '" title="View Particular">
                                <img src="../../img/customers/view-icon.png" />
                            </a>
                            <a href="../customers/history.php?id=' . $id . '" title="History">
                                <img src="../../img/customers/history-icon.png" />
                            </a>
                            <a href="../customers/add_loan.php?id=' . $id . '" title="Apply Loan">
                                <img src="../../img/customers/apply-loan.png" />
                            </a>
                            <a href="../payment/add_monthly.php?id=' . $id . '" title="Add Monthly">
                                <img src="../../img/apply-loan/add-btn.jpg" />
                            </a>
                            <a href="../customers/edit_cust.php?id=' . $id . '" title="Edit">
                                <img src="../../img/customers/edit-icon.png" />
                            </a>';
                
                if ($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' || $_SESSION['login_username'] == 'fong' || $_SESSION['login_username'] == 'staff' || $_SESSION['login_username'] == 'huizhen')) {
                    $action .= '<a href="javascript:deleteConfirmation(\'' . $name . '\', \'' . $id . '\')">
                                    <img src="../../img/customers/delete-icon.png" title="Delete">
                                </a>';
                }
    
                $html .= '<tr>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;border-left:1px solid black;color: black;">' . $count . '</td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['customercode2'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['loan_code'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $name . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['nric'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['company'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>' . $row['branch_name'] . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black;"><b>RM ' . number_format($loan_amount, 2) . '</b></td>
                            <td style="border-right:1px solid black;border-bottom: 1px solid black;color: black; text-align: center;">' . $action . '</td>
                        </tr>';
            }
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