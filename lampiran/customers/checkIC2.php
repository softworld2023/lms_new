<?php
session_start();
include("../include/dbconnection.php");

$ic = $_GET['ic'];


	$err_msg = "";	


if (strpos($ic,'-') !== false) {
	$dob = explode('-', $ic);
	$year = substr($dob[0], 0, 2);
	$month = substr($dob[0], 2, 2);
	$date = substr($dob[0], 4, 6);
	
	$yy = substr($dob[0], 0, 1);
	
	if($yy == '0')
	{
		$temp_y = $yy;	
	}else
	{
		$temp_y = $year;	
	}
	
	if($temp_y >= 18 && $temp_y <= 99)
	{
		$real_year = '19'.$year;	
	}else
	{
		$real_year = '20'.$year;	
	}
	
	$real_dob = $date.'-'.$month.'-'.$real_year;
}else
{
	$real_dob = '';		
}

echo $err_msg.'#'.$real_dob;
?>