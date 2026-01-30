<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<script type="text/javascript" src="../include/js/jquery-1.8.3.min.js"></script>
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon">
<title>Softworld - Loan System</title>

<style>
*{
	/* A universal CSS reset */
	margin:0;
	padding:0;
}
body 
{
  background:url(img/login/bg.jpg) no-repeat;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  font-family: Arial, Helvetica, sans-serif;
  font-size:12px;
  color:#FF0000;
}
input {
	
	text-align:center;
}
.style1 {color: #FFFFFF}
#box{
	background:url(../img/login/login-box.png);
	width:356px;
    height:399px;
    position: absolute;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

footer{
	font-size:11px;
	margin-left: 133px;
}
.style5 {color: #E7E7DE}
#login_btn
{
	background:url(../img/login/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
}
#login_btn:hover
{
	background:url(../img/login/submit-btn-roll-over.jpg);
}
</style>
</head>
<body>
<center>
<div id="box"><br>
<form action="action.php" method="post">
<table width="356" height="399">
	
	<tr>
	  <td height="80">&nbsp;</td>
    </tr>
    <tr>
      <td height="104" align="center" valign="bottom"><?php 
		if($_SESSION['error'])
		{
			echo $_SESSION['error'].'<br>';
			$_SESSION['error'] = '';
			session_unset();
		}else { echo '<br>'; }
	?><br></td>
    </tr>
    <tr>
      <td height="81" align="center">
      <table><tr><td><input type="text" id="username" name="username" placeholder="username" style="height: 23px; width: 235px;"></td></tr>
        <tr><td><br><input type="password" id="password" name="password" placeholder="password" style="height: 23px; width: 235px;"></td></tr>
        <tr><td align="right"><br><input type="submit" name="login" value="" id="login_btn" /></td>
    	</tr>
        </table>        </td>
    </tr>
    <tr>
      <td height="43">&nbsp;</td>
    </tr>
    <tr>
      <td align="right"></td>
    </tr>
</table>
</form>
</div>
</center>
<script>
$(document).ready(function() {
	document.getElementById('username').focus();
});
</script>
</body>
</html>