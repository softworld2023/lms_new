<?php 
session_start();
include('../include/page_headercb.php'); 
include('../include/dbconnection2.php');
include('../include/dbconnection.php');

?>
<style>
#tblmain
{
	font-size:11.5px;
}
</style>
<div id="message">
<?php
if($_SESSION['msg'] != '')
{
	echo $_SESSION['msg'];
    $_SESSION['msg'] = '';
}
?>
</div>
<left>
<div style="padding-left:80px;">
<table style="width:600px" >

<?php
$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'",$db1);
while($branch = mysql_fetch_assoc($branch_q))
{
?>
	<tr>
		<td style="height:30px">
			<?php
				echo $branch['branch_name'];
			?>
		</td>	
		<td style="height:30px">
			&nbsp; &nbsp; &nbsp;<a href="../lampiran/lampiranA/index.php?branch_id=<?php echo $branch['branch_id']; ?>">
			<?php
				echo "Lampiran A";
			?>
			</a>
		</td>
		<td style="height:30px">	
			&nbsp; &nbsp; &nbsp;<a href="../lampiran/lampiranB/index.php?branch_id=<?php echo $branch['branch_id']; ?>">
			<?php
				echo "Lampiran B"; 
			?>
			</a>
		</td>
		<td style="height:30px">	
			&nbsp; &nbsp; &nbsp;<a href="../lampiran/lampiranB1/index.php?branch_id=<?php echo $branch['branch_id']; ?>">
			<?php
				echo "Lampiran B1";
			?>
			</a>
		</td>
		<td style="height:30px">	
			&nbsp; &nbsp; &nbsp;<a href="../lampiran/lampiranJ/index.php?branch_id=<?php echo $branch['branch_id']; ?>">
			<?php
				echo "Lampiran J"; echo "<br>";
			?>
			</a>
			
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td style="height:30px">
	</tr>
</table>
</div>
</left>
</body>
</html>