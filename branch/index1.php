<?php 
session_start();
$_SESSION['login_database'] = 'loansystem';

include('../include/page_headercb.php'); 
?>
<style>
#tblmain
{
	font-size:11.5px;
}
</style>
<center>
<table style="width:1200px">

<?php
$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'  ");
while($branch = mysql_fetch_assoc($branch_q))
{
?>
	<tr>
		<td style="height:30px">
			<a href="branch_set.php?bid=<?php echo $branch['branch_id']; ?>&bname=<?php echo $branch['branch_name']; ?>&db_name=<?php echo $branch['db_name']; ?>">
				<?php
					echo $_SESSION['taplogin_id'];
					//echo $branch['branch_name']."<br>";
				?>
			</a>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td style="height:30px"><a href="../cashbookhq/">HQ (CASHBOOK)</a>
	</tr>
</table>
</center>
</body>
</html>