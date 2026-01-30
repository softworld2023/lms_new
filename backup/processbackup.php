<?php
include("../include/dbconnection.php");
session_start();
ini_set("max_execution_time", "295200");

$dbhost   = "localhost";
$dbuser   = "root";
$dbpwd    = "";
$dbname   = "loansystem";
date_default_timezone_set("Asia/Singapore");

$dumpfile = "backup/". $dbname . "_" . date("d-m-Y_H_i_s") . ".sql";
$filename = $dbname . "_" . date("d-m-Y_H_i_s") . ".sql";

$insert_q = mysql_query("INSERT into db_backup SET filename = '".$filename."', backup_date = now()");

if($insert_q)
{

	exec("C://xampp/mysql/bin/mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");
	
}

$_SESSION['msg'] = "<div class='success'>The backup has been created successfully.</div>";
echo "<script>window.location = 'index.php';</script>";
?>