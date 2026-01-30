<?php
// Get the filename to be deleted
session_start();

$file=$_GET['file'];

// Check if the file has needed args
if ($file==NULL){
	$_SESSION['msg'] = "<div class='error'>You have not provided a file to delete!</div>";	
  	print("<script type='text/javascript'>window.location='index.php'</script>");
  	die();
}

// Delete the SQL file
if (!is_dir("backup/" . $file . '.sql')) {
	unlink("backup/" . $file . '.sql');
}

// Redirect
$_SESSION['msg'] = "<div class='success'>The backup has been deleted successfully.</div>";
echo "<script>window.location = 'index.php';</script>";
?>
