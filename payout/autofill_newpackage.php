<?php
session_start();
include("../include/dbconnection.php");

if(isset($_POST['loan_amount']) && isset($_POST['loan_period'])){
	
		$loan_period = $_POST['loan_period'];
		$loan_amount = str_replace(',','',$_POST['loan_amount']);
		
		$loan_period_month = $loan_period.'months';
		$loan_pokok_period_month = 'loan_pokok_'.$loan_period;
		$np_q = mysql_query("SELECT $loan_period_month FROM new_package WHERE loan_amount = '$loan_amount'");
		$np_row = mysql_fetch_row($np_q);
		$np= $np_row[0];

		$loan_pokok_q = mysql_query("SELECT wang_pokok FROM $loan_pokok_period_month WHERE loan_amount = $loan_amount");
		$loan_pokok_row = mysql_fetch_row($loan_pokok_q);
		$loan_pokok= $loan_pokok_row[0];
		
		$data_array = array();
			
			$data_array[0] = $np;
			$data_array[1] = number_format(($loan_period*$np),2);
			$data_array[2] = number_format($loan_pokok,2);

			echo json_encode($data_array);
		
		
	}
?>