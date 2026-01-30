<?php 

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
$branch_q = mysql_query("SELECT * FROM branch WHERE branch_id != '13'");
while($branch = mysql_fetch_assoc($branch_q))
{
?>
	<tr>
		<td style="height:30px">
			<?php
				echo $branch['branch_name'];
			?>
	
			<a href="print_lampiranB.php?bid=<?php echo $branch['branch_id']; ?>&bname=<?php echo $branch['branch_name']; ?>">
			<?php
				echo "&nbsp; &nbsp; &nbsp;";
				echo "Lampiran B";
			?>
			</a>
			
			<a href="print_lampiranB1.php?bid=<?php echo $branch['branch_id']; ?>&bname=<?php echo $branch['branch_name']; ?>">
			<?php
				echo "&nbsp; &nbsp; &nbsp;";
				echo "Lampiran B1"; echo "<br>";
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
</center>
</body>
</html>