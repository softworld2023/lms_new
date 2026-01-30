<?php
    include_once '../include/dbconnection.php';
	include_once '../include/mpdf/mpdf.php';
	session_start();

	$collection_id = $_GET['id'];
	$loan_code = $_GET['loan_code'];

	$db = $_SESSION['login_database'];

	$mpdf = new mPDF('','A4','','',4,4,5,3,3,3,'P');

	$html = '';

	$sql = "SELECT
                t1.*,
                t3.customercode2,
                t3.name,
				t4.fullname
            FROM
                $db.collection t1
            JOIN $db.customer_loanpackage t2 ON t2.loan_code = t1.loan_code
            JOIN $db.customer_details t3 ON t3.id = t2.customer_id
			JOIN $db.user t4 ON t4.id = t1.submitted_by_id
            WHERE t1.loan_code = '$loan_code'
			ORDER BY t1.id ASC";
	// var_dump($sql);
    $query = mysql_query($sql);
	while ($left = mysql_fetch_assoc($query)) {
		$right = mysql_fetch_assoc($query); // fetch second record (if available)

		$html .= '<table width="100%" style="border: 1px dotted black; border-collapse: collapse; font-size:8px;">';
		$html .= '	<tr>';

		// Function to generate one cell (left or right)
		$generateCell = function($data) {
			if (!$data) return '<td width="50%"></td>'; // Empty if no data

			$name = $data['name'];
			$date = date('d-M-Y', strtotime($data['datetime']));
			$salary = $data['salary'];
			$salary_type = $data['salary_type'];
			if($salary_type == 'settlement'){
				$instalment = $data['collection_amount'];
			}else{
				$instalment = $data['instalment'];
			}
			$instalment_type = $data['instalment_type'];
			$tepi1 = $data['tepi1'];
			$tepi2 = $data['tepi2'];
			$tepi2_bunga = $data['tepi2_bunga'];
			$balance_received = $data['balance_received'];
			$staff_name = $data['fullname'];

			$html = '<td width="50%" style="border: 1px dotted black; border-collapse: collapse; padding: 5px;">';
			$html .= '	<table width="100%" style="border-collapse: collapse; margin: 10px;">';
			$html .= '		<tr>';
			$html .= '			<td colspan="2" style="text-align: right; padding: 10px;"><b>Nama:</b> <span style="border-bottom: 1px dotted black; padding: 10px;">' . $name . '</span></td>';
			$html .= '			<td colspan="2" style="text-align: center; padding: 10px;"><b>Tarikh:</b> <span style="border-bottom: 1px dotted black; padding: 10px;">' . $date . '</span></td>';
			$html .= '		</tr>';
			$html .= '		<tr><td width="5%"></td><td width="40%" style="text-align: right; padding: 10px;"><b>Gaji/Adv/Bonus = RM</b></td><td width="40%" style="border-bottom: 1px solid black; padding: 10px;">' . $salary . '</td><td width="15%"></td></tr>';
			$html .= '		<tr><td></td><td style="text-align: right; padding: 10px;"><b>Instalment/Half Month = RM</b></td><td style="border-bottom: 1px solid black; padding: 10px;">' . $instalment . '</td><td></td></tr>';
			$html .= '		<tr><td></td><td style="text-align: right; padding: 10px;"><b>Tepi 1 =</b></td><td style="border-bottom: 1px solid black; padding: 10px;">' . $tepi1 . '</td><td></td></tr>';
			$html .= '		<tr><td></td><td style="text-align: right; padding: 10px;"><b>Tepi 2 =</b></td><td style="border-bottom: 1px solid black; padding: 10px;">' . $tepi2 . '</td><td></td></tr>';
			$html .= '		<tr><td></td><td style="text-align: right; padding: 10px;"><b>Tepi 2 bunga =</b></td><td style="border-bottom: 1px solid black; padding: 10px;">' . $tepi2_bunga . '</td><td></td></tr>';
			$html .= '		<tr><td></td><td style="text-align: right; padding: 10px;"><b>Balance Diterima =</b></td><td style="border-bottom: 1px double black; padding: 10px;">' . $balance_received . '</td><td></td></tr>';
			$html .= '		<tr><td></td><td style="text-align: right; padding: 10px;"><b>Tandatangan Penerima:</b></td><td style="border-bottom: 1px dotted black; padding: 10px;">' . $staff_name . '</td><td></td></tr>';
			$html .= '	</table>';
			$html .= '</td>';
			return $html;
		};

		// Generate left and right columns
		$html .= $generateCell($left);
		$html .= $generateCell($right);

		$html .= '	</tr>';
		$html .= '</table>';
	}

	$mpdf->WriteHTML($html);
	$mpdf->Output();
?>