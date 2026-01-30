<?php
session_start();
require_once("../include/dbconnection.php");
$_SESSION['login_branchid'] = $_GET['bid'];
$_SESSION['login_branch'] = $_GET['bname'];

?>
<meta http-equiv="refresh" content="0; url='../branch'">