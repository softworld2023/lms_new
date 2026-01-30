<?php
session_start();

$id = $_GET['id'];
$filename = 'backup/'.$id.'.sql';

$download_name = basename($filename);
		
if(file_exists($filename))
{
	header('Content-Description: File Transfer');
	header('Content-Type: text/plain');
	header('Content-Transfer-Encoding: Binary');
	header("Content-Disposition: attachment; filename=".$download_name);
	header('X-SendFile:'.$filename);
	header('Pragma: no-cache');
	header("Expires: 0");
	flush();
	readfile($filename);
}	
?>