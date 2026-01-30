<?php
session_start();

if ($_SESSION['login_branchid'] == '') {
	session_destroy();
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php
}else
{
include("../include/dbconnection.php");
include("../config.php");


if($_POST['action'] == 'deletefile')
{
	$filename = $_POST['filename'];
	$filelink = $_POST['filelink'];
	
	unlink($filelink.$filename);
}
}
?>