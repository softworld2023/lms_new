<style>
#logout
{
	border:0; color:#FFF; background:none; font-weight:bold; cursor:pointer
}
#logout:hover
{
	color:#999;
}

.useracc
{
	color:FFF;
}
.useracc:hover
{
	text-decoration:underline;
}
</style>

<div id="userpanel" align="right" style="color:#FFF">
	<form method="post" action="../action.php">
    <span style="font-weight:bold;">Welcome,&nbsp;<a href="../useracc/" style="color:#FFF"><span class="useracc"><?php echo $_SESSION['login_name']; ?></span></a></span>
    &nbsp;&nbsp;<img src="../img/white-dot.png">&nbsp;&nbsp;
    <input type="submit" name="logout" id="logout" value="logout">
    </form>
</div>