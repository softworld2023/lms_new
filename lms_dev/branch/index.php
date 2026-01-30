<?php

	session_start();
	include('../include/page_headercb.php'); 
?>
<style>
	#tblmain
	{
		font-size:11.5px;
	}

	#btn-search {
		background:url(../img/enquiry/search-btn.jpg);
		width:109px;
		height:30px;
		border:none;
		cursor:pointer;
	}

	#btn-clear-search {
		width:109px;
		height:30px;
		border:none;
		cursor:pointer;
	}

	#btn-search:hover {
		background:url(../img/enquiry/search-btn-roll-over.jpg);
	}

	#bd_table, #rejected_table, #customer_table {
		border-collapse:collapse;
		border:none;	
	}

	#bd_table tr th, #rejected_table tr th, #customer_table tr th {
		height:36px;
		background:#666;
		text-align:left;
		padding-left:10px;
		color:#FFF;
	}

	#bd_table tr td, #rejected_table tr td, #customer_table tr td {
		height:35px;
		padding-left:10px;
		padding-right:10px;
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
			<br>

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

			<table style="width: 1200px;">
				<tr>
					<td align="left" width="100px">Customer Name</td>
					<td align="left" width="200px">
						<input id="customer_name" name="customer_name" list="customer_names" style="height:30px; width: 100%;">
						<datalist id="customer_names">
						</datalist>
					</td>
					<td align="left" width="80px" style="padding-left: 15px;">Customer IC</td>
					<td align="left" width="150px">
						<input id="customer_ic" name="customer_ic" list="customer_ics" style="height:30px; width: 100%;">
						<datalist id="customer_ics">
						</datalist>
					</td>
					<td align="left" width="100px" style="padding-left: 15px;">
						<input type="button" id="btn-search" name="search" value=""/>
					</td>
					<td align="left" width="100px" style="padding-left: 5px;">
						<button type="button" id="btn-clear-search">Clear</button>
					</td>
					<td width="30%"></td>
				</tr>
			</table>
			<div id="listing" style="width: 1200px; display: none;">
				<div style="text-align: left; padding-top: 5px; padding-bottom: 5px;"><b>Bad Debt</b></div>
				<table id="bd_table">
					<thead>
						<tr>
							<th width="2%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
							<th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
							<th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Name</th>
							<th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">IC</th>
							<th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Company Name</th>
							<th style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Contact Number</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Bad Debt Amount</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<br>
				<br>
				<div style="text-align: left; padding-top: 5px; padding-bottom: 5px;"><b>Reject List</b></div>
				<table id="rejected_table">
					<thead>
						<tr>
							<th width="2%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Name</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer IC</th>
							<th width="15%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer Company</th>
							<th width="15%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Reject Reason</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Date</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Contact Number</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer From</th>
							<th width="8%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<br>
				<br>
				<div style="text-align: left; padding-top: 5px; padding-bottom: 5px;"><b>Customer List</b></div>
				<table id="customer_table">
					<thead>
						<tr>
							<th width="2%" style="border:1px solid black; padding-left: 2px; padding-right: 2px; text-align: center;">No.</th>
							<th width="8%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Customer ID </th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Agreement No</th>
							<th width="20%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Name</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">I.C Number</th>
							<th width="15%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Company</th>
							<th width="5%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Branch</th>
							<th width="10%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black;">Total Loan</th>
							<th width="20%" style="border-right:1px solid black;border-bottom: 1px solid black;border-top:1px solid black; text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</center>

		<script>
			$(document).ready(function() {
				getCustomerDetails();
			});

			$('#btn-search').click(function() {
				$.ajax({
					url: 'get_bd_customer_listing_ajax.php',
					type: 'POST',
					data: {
						customer_name: $('#customer_name').val(),
						customer_ic: $('#customer_ic').val()
					},
					dataType: 'html',
					success: function(response) {
						// console.log(response);
						$('#bd_table tbody').html(response);
					}
				});

				$.ajax({
					url: 'get_rejected_customer_listing_ajax.php',
					type: 'POST',
					data: {
						customer_name: $('#customer_name').val(),
						customer_ic: $('#customer_ic').val()
					},
					dataType: 'html',
					success: function(response) {
						// console.log(response);
						$('#rejected_table tbody').html(response);
					}
				});

				$.ajax({
					url: 'get_customer_listing_ajax.php',
					type: 'POST',
					data: {
						customer_name: $('#customer_name').val(),
						customer_ic: $('#customer_ic').val()
					},
					dataType: 'html',
					success: function(response) {
						// console.log(response);
						$('#customer_table tbody').html(response);
					}
				});

				$('#listing').show();
			});

			$('#btn-clear-search').click(function() {
				$('#customer_name').val('');
				$('#customer_ic').val('');
				$('#listing').hide();
			});

			function getCustomerDetails() {
				$.ajax({
					url: 'get_customer_details_ajax.php',
					type: 'POST',
					dataType: 'json',
					success: function(response) {
						// console.log(response);
						let nameArr = response.name;
						let icArr = response.ic;
						let html = '';

						nameArr.forEach(name => {
							html += `<option value="${name}">`;
						});
						$('#customer_names').html(html);

						html = '';
						icArr.forEach(ic => {
							html += `<option value="${ic}">`;
						});
						$('#customer_ics').html(html);
					}
				});
			}

			function deleteConfirmation(id){
				$id = id;
				$.confirm({
					'title'		: 'Delete Confirmation',
					'message'	: 'Are you sure want to delete this?',
					'buttons'	: {
						'Yes'	: {
						'class'	: 'blue',
						'action': function() {
							$.ajax({
									type: 'POST',
									data: {
										action: 'delete_reject',
										id: $id,
									},
									url: '../reject/reject_delete_action.php',
									success: function(){
										location.reload();
									}
								})
							}
						},
						'No'	: {
							'class'	: 'gray',
							'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
						}
					}
				});
			}

			function deleteConfirmation(name, id) {
				$id = id;
				$.confirm({
					'title'		: 'Delete Confirmation',
					'message'	: 'Are you sure want to delete this customer: ' + name + ' ?<br><br>All of the records for this customer will be deteted from the database.',
					'buttons'	: {
						'Yes'	: {
						'class'	: 'blue',
						'action': function() {
							$.ajax({
									type: 'POST',
									data: {
										action: 'delete_customer',
										id: $id,
									},
									url: '../customers/action.php',
									success: function(){
										location.reload();
									}
								})
							}
						},
						'No'	: {
							'class'	: 'gray',
							'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
						}
					}
				});
			}
		</script>
	</body>
</html>