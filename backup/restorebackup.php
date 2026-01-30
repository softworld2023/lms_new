<?php
session_start();
ini_set("max_execution_time", "295200");

$dbhost   = "localhost";
$dbuser   = "root";
$dbpwd    = "";
$dbname   = "loansystem";

$dumpfile = "backup/".$_GET['id'].".sql";

exec("C://xampp/mysql/bin/mysql -u $dbuser $dbname < $dumpfile");

$_SESSION['msg'] = "<div class='success'>Database has been restored successfully.</div>";
echo "<script>window.location = 'index.php';</script>";
?>