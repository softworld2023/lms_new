<?php

	session_start();
	include('../include/page_headercb.php'); 
?>
<style>
	#tblmain
	{
		font-size:11.5px;
	}

	#search {
		background:url(../img/enquiry/search-btn.jpg);
		width:109px;
		height:30px;
		border:none;
		cursor:pointer;
	}

	#search:hover {
		background:url(../img/enquiry/search-btn-roll-over.jpg);
	}

	#clear-bd-search, #clear-rejected-search {
		width:109px;
		height:30px;
		border:none;
		cursor:pointer;
	}
</style>
		<center>
			<table style="width:1200px">

				<?php
				$branch_q = mysql_query("SELECT * FROM branch") or die(mysql_error());
				while($branch = mysql_fetch_assoc($branch_q))
				{
				?>
					<tr>
						<td style="height:30px">
							<a href="branch_set.php?bid=<?php echo $branch['branch_id']; ?>&bname=<?php echo $branch['branch_name']; ?>&db_name=<?php echo $branch['db_name']; ?>"> 
							<?php
									echo $branch['branch_name']."<br>";
								?>
							</a>
						</td>
					</tr>
				<?php
				}
				?>
			</table>

			<?php
				$login_branch = $_SESSION['login_branch'];
				$branch_name_arr = array();
				$db_name_arr = array();
		
				if ($login_branch == 'KTL' || $login_branch == 'TSY'|| $login_branch == 'TSY2') {
					$branch_name_arr = array('MAJUSAMA', 'ANSENG', 'YUWANG', 'DK', 'KTL', 'TSY', 'TSY2');
					$db_name_arr = array('majusama', 'anseng', 'yuwang', 'ktl', 'tsy','dk', 'tsy2');
				} else {
					$branch_name_arr = array('MAJUSAMA', 'ANSENG', 'YUWANG', 'DK');
					$db_name_arr = array('majusama', 'anseng', 'yuwang', 'dk');
				}
			?>

			<form id="search-bd-form">
				<table style="width: 65%;">
					<tr>
						<td width="15%"><b>Bad Debt</b></td>
						<td align="left" width="15%">
							<select id="branch_list_bd">
								<?php
									for ($i = 0, $len = count($branch_name_arr); $i < $len; $i++) {
										$db_name = $db_name_arr[$i];
										$branch = $branch_name_arr[$i];
										$selected = $branch == $login_branch ? 'selected' : '';
								?>
										<option value="<?= $db_name; ?>" <?= $selected; ?>><?= $branch; ?></option>
								<?php
									}
								?>
							</select>
						</td>
						<td align="right" width="15%" style="padding-right: 10px;">Customer Name</td>
						<td align="left" width="15%">
							<input id="customer_name_bd" name="customer_name_bd" list="names_bd" style="height:30px;">
							<datalist id="names_bd">
							</datalist>
						</td>
						<td align="right" width="15%" style="padding-right: 10px;">Customer IC</td>
						<td align="left" width="15%">
							<input id="customer_ic_bd" name="customer_ic_bd" list="ic_bd" style="height:30px;">
							<datalist id="ic_bd">
							</datalist>
						</td>
						<td align="right" width="10%" style="padding-left: 10px;">
							<input type="submit" id="search" name="search" value=""/>
						</td>
						<td align="right" width="10%" style="padding-left: 10px;">
							<button type="button" id="clear-bd-search">Clear</button>
						</td>
					</tr>
				</table>
			</form>
			<br>
			<form id="search-rejected-form">
				<table style="width: 65%;">
					<tr>
						<td width="15%"><b>Reject List</b></td>
						<td align="left" width="15%">
							<select id="branch_list_rejected">
								<?php
									for ($i = 0, $len = count($branch_name_arr); $i < $len; $i++) {
										$db_name = $db_name_arr[$i];
										$branch = $branch_name_arr[$i];
										$selected = $branch == $login_branch ? 'selected' : '';
								?>
										<option value="<?= $db_name; ?>" <?= $selected; ?>><?= $branch; ?></option>
								<?php
									}
								?>
							</select>
						</td>
						<td align="right" width="15%" style="padding-right: 10px;">Customer Name</td>
						<td align="left" width="15%">
							<input id="customer_name_rejected" name="customer_name_rejected" list="names_rejected" style="height:30px;">
							<datalist id="names_rejected">
							</datalist>
						</td>
						<td align="right" width="15%" style="padding-right: 10px;">Customer IC</td>
						<td align="left" width="15%">
							<input id="customer_ic_rejected" name="customer_ic_rejected" list="ic_rejected" style="height:30px;">
							<datalist id="ic_rejected">
							</datalist>
						</td>
						<td align="right" width="10%" style="padding-left: 10px;">
							<input type="submit" id="search" name="search" value=""/>
						</td>
						<td align="right" width="10%" style="padding-left: 10px;">
							<button type="button" id="clear-rejected-search">Clear</button>
						</td>
					</tr>
				</table>
			</form>
		</center>
	</body>
</html>