<?php
	include_once '../include/dbconnection.php';
    session_start();

	if (isset($_POST)) {
        $collection_id = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';

        $db = $_SESSION['login_database'];
		$login_branch = $_SESSION['login_branch'];

		$sql = "SELECT * FROM $db.collection WHERE id = '$collection_id'";
		$query = mysql_query($sql);
		$result = mysql_fetch_assoc($query);
		$collection_loan_code = $result['loan_code'];
		$tepi2_loan_code = $result['tepi2_loan_code'];
		$instalment = $result['instalment'];
		$tepi1 = $result['tepi1'];
		$tepi2 = $result['tepi2'];
		$instalment_month = $result['instalment_month'];
		$tepi1_month = $result['tepi1_month'];
		$tepi2_month = $result['tepi2_month'];

		if ($instalment > 0) {
			// $opening_balance = 0;
			// $total_instalment_collected = 0;
			// $total_settle = 0;
			// $capital_in = 0;
			// $total_bd_collected = 0;
			// $total_monthly = 0;
			// $total_instalment_payout = 0;
			// $total_expenses = 0;
			// $total_expenses_2 = 0;
			// $total_interest_paid_out = 0;
			// $return_capital = 0;

			// // Refer instalment report
			// $sql = "SELECT * FROM $db.instalment_balance WHERE pay_month = '$instalment_month'";
			// $query = mysql_query($sql);
			// if (mysql_num_rows($query) > 0) {
			// 	$result = mysql_fetch_assoc($query);
			// 	$opening_balance = $result['opening_balance'];
			// 	$total_instalment_collected = $result['collected'];
			// 	$total_settle = $result['settle'];
			// 	$capital_in = $result['capital_in'];
			// 	$total_bd_collected = $result['baddebt'];
			// 	$total_monthly = $result['monthly'];
			// 	$total_instalment_payout = $result['payout'];
			// 	$total_expenses = $result['expenses'];
			// 	$total_expenses_2 = $result['expenses_2'];
			// 	$total_interest_paid_out = $result['interest_paid_out'];
			// 	$return_capital = $result['return_capital'];
			// }

			// $sql = "SELECT
			// 			customer_loanpackage.loan_code,
			// 			customer_loanpackage.start_month,
			// 			customer_loanpackage.payout_date,
			// 			customer_loanpackage.loan_amount,
			// 			customer_loanpackage.loan_period,
			// 			customer_loanpackage.loan_total,
			// 			customer_loanpackage.loan_status,
			// 			customer_details.customercode2,
			// 			customer_details.name,
			// 			customer_details.nric,
			// 			customer_employment.company,
			// 			temporary_payment_details.monthly,
			// 			temporary_payment_details.loan_percent,
			// 			temporary_payment_details.loan_status,
			// 			temporary_payment_details.customer_loanid 
			// 		FROM
			// 			$db.customer_loanpackage
			// 		LEFT JOIN $db.customer_details ON customer_loanpackage.customer_id = customer_details.id
			// 		LEFT JOIN $db.customer_employment ON customer_employment.customer_id = customer_details.id
			// 		LEFT JOIN $db.temporary_payment_details ON temporary_payment_details.customer_loanid = customer_loanpackage.id 
			// 		WHERE
			// 			temporary_payment_details.loan_month = '$instalment_month'
			// 		AND temporary_payment_details.loan_code NOT IN (
			// 			SELECT
			// 				customer_loanpackage.loan_code 
			// 			FROM
			// 				$db.customer_loanpackage
			// 			LEFT JOIN $db.customer_details ON customer_loanpackage.customer_id = customer_details.id
			// 			LEFT JOIN $db.customer_employment ON customer_employment.customer_id = customer_details.id
			// 			LEFT JOIN $db.loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
			// 			WHERE
			// 				customer_loanpackage.loan_package = 'NEW PACKAGE'
			// 			AND loan_payment_details.month_receipt < '$instalment_month'
			// 			AND loan_payment_details.loan_status = 'SETTLE' 
			// 			GROUP BY customer_loanpackage.loan_code
			// 			ORDER BY customer_loanpackage.loan_code ASC
			// 		)
			// 		AND temporary_payment_details.loan_code NOT IN (
			// 			SELECT
			// 				customer_loanpackage.loan_code 
			// 			FROM
			// 				$db.customer_loanpackage
			// 			LEFT JOIN $db.customer_details ON customer_loanpackage.customer_id = customer_details.id
			// 			LEFT JOIN $db.customer_employment ON customer_employment.customer_id = customer_details.id
			// 			LEFT JOIN $db.loan_payment_details ON loan_payment_details.customer_loanid = customer_loanpackage.id 
			// 			WHERE
			// 				customer_loanpackage.loan_package = 'NEW PACKAGE'
			// 			AND loan_payment_details.month_receipt < '$instalment_month'
			// 			AND loan_payment_details.loan_status = 'BAD DEBT' 
			// 			GROUP BY customer_loanpackage.loan_code
			// 			ORDER BY customer_loanpackage.loan_code ASC
			// 		)
			// 		GROUP BY temporary_payment_details.loan_code
			// 		ORDER BY
			// 			customer_loanpackage.start_month = '$instalment_month', customer_loanpackage.payout_date ASC";
			// $query = mysql_query($sql);
			// while ($row = mysql_fetch_assoc($query)) {
			// 	$loan_code = $row['loan_code'];
			// 	$loan_amount = $row['loan_amount'];
			// 	$loan_percent = $row['loan_percent'];
			// 	$instalment_collected = $row['monthly'];

			// 	if ($row['start_month'] == $instalment_month) {
			// 		$instalment_payout = $loan_amount - ($loan_amount * 0.1);
			// 		$total_instalment_payout += $instalment_payout;
			// 	}

			// 	$sql = "SELECT * FROM $db.loan_payment_details WHERE month_receipt = '$instalment_month'";
			// 	$q = mysql_query($sql);
			// 	while ($res = mysql_fetch_assoc($q)) {
			// 		$receipt_no = $res['receipt_no'];
			// 		$loan_status = $res['loan_status'];

			// 		if ($receipt_no == $loan_code) {
			// 			if ($loan_status == 'SETTLE') {
			// 				$total_settle += $loan_percent;
			// 			} else if ($loan_status != 'SETTLE' && $loan_status != 'BAD DEBT') {
			// 				$total_instalment_collected += $instalment_collected;
			// 			}
			// 		}
			// 	}
			// }

			// $sql = "SELECT
			// 			late_interest_record.loan_code,
			// 			customer_details.customercode2,
			// 			customer_details.name,
			// 			late_interest_record.bd_date,
			// 			late_interest_record.amount as amount,
			// 			SUM(late_interest_payment_details.amount) as collected,
			// 			late_interest_payment_details.payment_date,
			// 			late_interest_record.balance as balance
			// 		FROM
			// 			$db.late_interest_record
			// 		LEFT JOIN $db.customer_details ON late_interest_record.customer_id = customer_details.id
			// 		LEFT JOIN $db.late_interest_payment_details ON late_interest_record.id = late_interest_payment_details.lid
			// 		WHERE late_interest_payment_details.month_receipt = '$instalment_month'
			// 		GROUP BY late_interest_record.loan_code
			// 		ORDER BY late_interest_record.bd_date ASC";
			// $q = mysql_query($sql);                     
			// while ($res = mysql_fetch_assoc($q)) { 
			// 	if ($login_branch == 'ANSENG' && $instalment_month == '2023-04') {
			// 		$total_bd_collected += 0;
			// 	} else {
			// 		$total_bd_collected += $res['collected'];
			// 	}
			// }

			// if ($login_branch == 'ANSENG' && $instalment_month == '2023-04') {
			// 	$total_bd_collected = 0;
			// }

			// $closing_balance_instalment = $opening_balance + $total_instalment_collected + $total_settle + $capital_in + $total_bd_collected + $total_monthly - $total_instalment_payout - $total_expenses - $total_expenses_2 - $total_interest_paid_out - $return_capital;
			// var_dump($closing_balance_instalment);

			$instalment_month_parts = explode('-', $instalment_month);
			$year = $instalment_month_parts[0];
			$month = $instalment_month_parts[1];
			$datetime = date('Y-m-d H:i:s');
			$tepi = $tepi1 + $tepi2;
			// $sql = "INSERT INTO $db.return_book_instalment SET
			// 			year = '$year',
			// 			month = '$month',
			// 			datetime = '$datetime',
			// 			loan_code = '$collection_loan_code',
			// 			instalment = '$instalment',
			// 			tepi = '$tepi1',
			// 			closing_balance = '$closing_balance_instalment'
			// 		";
			$sql = "INSERT INTO $db.return_book_instalment SET
						collection_id = '$collection_id',
						year = '$year',
						month = '$month',
						datetime = '$datetime',
						loan_code = '$collection_loan_code',
						instalment = '$instalment',
						tepi = '$tepi'
					";
			mysql_query($sql);
		}
        
		if ($tepi1 > 0) {

			$tepi1_month_parts = explode('-', $tepi1_month);
			$year = $tepi1_month_parts[0];
			$month = $tepi1_month_parts[1];
			$datetime = date('Y-m-d H:i:s');
			$amount = $tepi1 + $tepi2;

			// Check if monthly is paid
			$sql = "SELECT * FROM $db.monthly_payment_record WHERE loan_code = '$collection_loan_code'";
			$q = mysql_query($sql);
			if (mysql_num_rows($q) > 0) {
				$sql = "INSERT INTO $db.return_book_monthly SET
							collection_id = '$collection_id',
							year = '$year',
							month = '$month',
							datetime = '$datetime',
							loan_code = '$collection_loan_code',
							tepi = '$amount'
						";
				mysql_query($sql);
			}
		}

		if ($tepi2 > 0) {

			$tepi2_month_parts = explode('-', $tepi2_month);
			$year = $tepi2_month_parts[0];
			$month = $tepi2_month_parts[1];
			$datetime = date('Y-m-d H:i:s');
		
			// Check if monthly is paid
			$sql = "SELECT * FROM $db.monthly_payment_record WHERE loan_code = '$tepi2_loan_code'";
			$q = mysql_query($sql);
			if (mysql_num_rows($q) > 0) {
				$sql = "INSERT INTO $db.return_book_monthly SET
							collection_id = '$collection_id',
							year = '$year',
							month = '$month',
							datetime = '$datetime',
							loan_code = '$tepi2_loan_code',
							tepi = '$tepi2'
						";
				mysql_query($sql);
			}

			// =============================
			// Insert also for instalment_month
			// =============================
			if ($tepi1 == 0) {
				$instalment_month_parts = explode('-', $result['instalment_month']);
				$instalment_year = $instalment_month_parts[0];
				$instalment_month = $instalment_month_parts[1];
				$amount = $tepi1 + $tepi2;

				$sql = "INSERT INTO $db.return_book_monthly SET
							collection_id = '$collection_id',
							year = '$instalment_year',
							month = '$instalment_month',
							datetime = '$datetime',
							loan_code = '$collection_loan_code',
							tepi = '$amount'
						";
				mysql_query($sql);
			}
		}

		// if ($tepi2 > 0) {

		// 	$tepi2_month_parts = explode('-', $tepi2_month);
		// 	$year = $tepi2_month_parts[0];
		// 	$month = $tepi2_month_parts[1];
		// 	$datetime = date('Y-m-d H:i:s');

		// 	// =============================
		// 	//  1. Calculate next month YYMM based on current loan_code
		// 	// =============================
		// 	$currentYYMM = substr($collection_loan_code, 0, 4); // e.g. "2508"
		// 	$yy = intval(substr($currentYYMM, 0, 2));
		// 	$mm = intval(substr($currentYYMM, 2, 2));

		// 	$mm++;
		// 	if ($mm > 12) {
		// 		$mm = 1;
		// 		$yy++;
		// 	}
		// 	$nextYYMM = str_pad($yy, 2, '0', STR_PAD_LEFT) . str_pad($mm, 2, '0', STR_PAD_LEFT);

		// 	// =============================
		// 	//  2b. Fallback: find next available loan_code sequence
		// 	// =============================
		// 	$sql = "SELECT loan_code
		// 			FROM $db.monthly_payment_record
		// 			WHERE loan_code LIKE '{$nextYYMM}-%'
		// 			AND status != 'DELETED'
		// 			ORDER BY loan_code ASC";
		// 	$res = mysql_query($sql);

		// 	$usedSeq = [];
		// 	while ($row = mysql_fetch_assoc($res)) {
		// 		if (preg_match('/^' . preg_quote($nextYYMM, '/') . '\-(\d+)$/', $row['loan_code'], $matches)) {
		// 			$usedSeq[] = intval($matches[1]);
		// 		}
		// 	}

		// 	$nextSeq = 1;
		// 	while (in_array($nextSeq, $usedSeq)) {
		// 		$nextSeq++;
		// 	}

		// 	$new_loan_code = $nextYYMM . '-' . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);


		// 	// =============================
		// 	//  3. Check if monthly is paid, then insert into return_book_monthly
		// 	// =============================
		// 	$sql = "SELECT * 
		// 			FROM $db.monthly_payment_record 
		// 			WHERE loan_code = '$collection_loan_code'";
		// 	$q = mysql_query($sql);

		// 	if (mysql_num_rows($q) > 0) {
		// 		// Instead of regenerating a new loan_code here,
		// 		// reuse the one that was actually created/selected
		// 		$sql = "INSERT INTO $db.return_book_monthly SET
		// 					collection_id = '$collection_id',
		// 					year = '$year',
		// 					month = '$month',
		// 					datetime = '$datetime',
		// 					loan_code = '$new_loan_code',
		// 					tepi = '$tepi2'";
		// 		mysql_query($sql);

		// 		// Also update collection table to reference the SAME loan_code
		// 		$sql = "UPDATE $db.collection 
		// 				SET tepi2_loan_code = '$new_loan_code' 
		// 				WHERE id = '$collection_id'";
		// 		mysql_query($sql);
		// 	}

		// }
	}
?>