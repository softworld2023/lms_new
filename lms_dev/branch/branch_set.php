<?php
session_start();
require_once("../include/dbconnection.php");

if ($_SESSION['login_branchid'] == '') {
session_destroy();
}

?>
<meta http-equiv="refresh" content="0; url='../home/'">