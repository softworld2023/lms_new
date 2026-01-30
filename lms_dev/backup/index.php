<?php include('../include/page_headercb.php'); 
//update date in database
$q = mysql_query("UPDATE backup_setting SET backup_date = now() WHERE id = 1");

function listdir_by_date($path)
{
	$dir = opendir($path);
	$list = array();
	while($file = readdir($dir)){
		if ($file != '.' and $file != '..'){
			// add the filename, to be sure not to
			// overwrite a array key
			$ctime = filemtime($path . $file) . ',' . $file;
			$list[$ctime] = $file;
		}
	}
	closedir($dir);
	krsort($list);
	return $list;
}
?>
<link rel="stylesheet" href="../include/js/countdown/jquery.countdown.css" />
<script src="../include/js/countdown/jquery.countdown.js"></script>
<style type="text/css">
<!--
.submit_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'remove.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}
.app_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'sent.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}
.reject_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'cancel-icon.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}

#list_table
{
	border-collapse:collapse;
	border:none;	
}

#list_table tr th
{
	height:36px;
	background:#666;
	text-align:left;
	padding-left:10px;
	color:#FFF;
}
#list_table tr td
{
	height:35px;
	padding-left:10px;
	padding-right:10px;
}

#rl
{
	width:318px;
	height:36px;
	background:url(../img/customers/right-left.jpg);
}
#back
{
	background:url(../img/back-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#back:hover
{
	background:url(../img/back-btn-roll-over.jpg);
}

#save_time
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#save_time:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/settings.png" style="height:47px"></td>
        <td>Setting: Backup &amp; Restore </td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="../setting/">System User</a><a href="../setting/approval.php">Loan Approval Level</a><a href="index.php" id="active-menu">System Backup</a>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
    </tr>
</table>
<div id="message" style="width:1280px; text-align:left">
	<?php
    if($_SESSION['msg'] != '')
    {
        echo $_SESSION['msg'];
        $_SESSION['msg'] = '';
    }
    ?>						
</div>
<br />
<table width="1280" id="list_table">
	<tr>
		<td colspan="5">
		<?php 
		$backup_q = mysql_query("SELECT * FROM backup_setting");
		$get_b = mysql_fetch_assoc($backup_q);
		
		date_default_timezone_set("Asia/Singapore");
		$ext_time = $get_b['backup_sch'];
		$ext_timer = explode(":", $get_b['backup_time']);
		
		if($ext_time == 'daily')
		{
			//$d1 = new DateTime(date('Y-m-d H:i:s'));  
			//$d2 = new DateTime(date('Y-m-d '.$ext_timer[0].':'.$ext_timer[1].':00'));
			/*$interval = $d1->diff($d2);
			$diff = explode(":", $interval->format('%H:%i:%s'));
			
			$hour = $diff[0] * 3600;
			$min = $diff[1] * 60;
			$sec = $diff[2];*/	
			$d1 = date('Y-m-d H:i:s');  
			$d2 = date('Y-m-d '.$ext_timer[0].':'.$ext_timer[1].':00');
			
			if(strtotime($d2) < strtotime($d1))
			{
				$d2 = date('Y-m-d '.$ext_timer[0].':'.$ext_timer[1].':00', strtotime(' +1 day'));
			}
			
			$_SESSION['date2'] = $d2;
		
			//$timer = $hour + $min + $sec;
			$timer = strtotime($d2) - strtotime($d1);
		}else
		if($ext_time == 'weekly')
		{
			$day = date("w");
			
			//$d1 = new DateTime(date('Y-m-d H:i:s'));
			//$d2 = new DateTime(date('Y-m-d '.$ext_timer[0].':'.$ext_timer[1].':00', strtotime('next Saturday')));
			
			$d11 = date('Y-m-d H:i:s');
			$d12 = date('Y-m-d '.$ext_timer[0].':'.$ext_timer[1].':00', strtotime('next Saturday'));
			
			/*$interval = $d1->diff($d2);
			$diff = explode(":", $interval->format('%d:%H:%i:%s'));
			
			$day = $diff[0] * 86400;
			$hour = $diff[1] * 3600;
			$min = $diff[2] * 60;
			$sec = $diff[3];*/
			
			//$timer = $day + $hour + $min + $sec;
			
			$timer = strtotime($d12) - strtotime($d11);
		}else
		if($ext_time == 'monthly') 
		{
			/*$d1 = new DateTime(date('Y-m-d H:i:s')); 
			$d2 = new DateTime(date('Y-m-t '.$ext_timer[0].':'.$ext_timer[1].':00'));  //get last day of the month
			
			$interval = $d1->diff($d2);
			$diff = explode(":", $interval->format('$d:%H:%i:%s'));
			
			$day = $diff[0] * 86400;
			$hour = $diff[1] * 3600;
			$min = $diff[2] * 60;
			$sec = $diff[3];
			$timer = $day + $hour + $min + $sec;*/
			
			$d11 = date('Y-m-d H:i:s'); 
			$d12 = date('Y-m-t '.$ext_timer[0].':'.$ext_timer[1].':00');  //get last day of the month
			
			$timer = strtotime($d12) - strtotime($d11);
		}
		
		?>
		<script type="application/javascript">
		$(function(){
			var note = $('#note'),
				ts = new Date(1999, 0, 1),
				newYear = true;
			
			if((new Date()) > ts){
				// The new year is here! Count towards something else.
				// Notice the *1000 at the end - time must be in milliseconds
				ts = (new Date()).getTime() + <?php echo $timer; ?>*1000;
			}
				
			$('#countdown').countdown({
				timestamp	: ts,
				callback	: function(days, hours, minutes, seconds){
					var message = "";
					
					message += days + " day" + ( days==1 ? '':'s' ) + ", ";
					message += hours + " hour" + ( hours==1 ? '':'s' ) + ", ";
					message += minutes + " minute" + ( minutes==1 ? '':'s' ) + " and ";
					message += seconds + " second" + ( seconds==1 ? '':'s' ) + " <br />";
					
					if(newYear){
						message += "left until the new year!";
					}
					else {
						message += "left to 10 days from now!";
					}
					
					note.html(message);
				}
			});
			
		});
		
		//script to refresh page
		var timeout = setTimeout(function(){document.getElementById('extract').submit();}, <?php echo $timer*1000; ?>+1000);
		function resetTimeout() {
			clearTimeout(timeout);
			timeout = setTimeout(function(){document.getElementById('extract').submit();}, <?php echo $timer*1000; ?>+1000);
			
		}
		</script>
		<form id="extract" method="post" action="autobackup_database.php">
		</form>
		<form method="post" action="action.php" enctype="multipart/form-data">
		<table style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">
			<tr>
			  <td style="padding-right:20px;">Backup Schedule</td>
			  <td style="padding-right:25px">: <select name="backup_time" id="backup_time" style="height:30px">
						<option value="">-Select-</option>
						<option value="daily"  <?php if($get_b['backup_sch'] == 'daily') { echo "selected"; } ?>>Daily</option>
						<option value="weekly"  <?php if($get_b['backup_sch'] == 'weekly') { echo "selected"; } ?>>Weekly</option>
						<option value="monthly"  <?php if($get_b['backup_sch'] == 'monthly') { echo "selected"; } ?>>Monthly</option>
					</select></td>
			  <td>&nbsp;</td>
			</tr>
			<tr>
				<td style="padding-right:20px">Backup Time</td>
				<td style="padding-right:25px">: <input type="text" name="backuptime" id="backuptime" style="text-align:left; height:30px" value="<?php echo $get_b['backup_time']; ?>" /></td>
				<td><input type="submit" id="save_time" name="save_time" value="" style=" border:none;cursor:pointer" /></td>
			</tr>
		</table>
		</form><br />
		<div class="info" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">Data will be extracted in:</div>
		<div id="countdown"></div>
		</div>

		</td>
	</tr>
	
	<tr>
		<td colspan="5">
		<form method="post" action="action.php" enctype="multipart/form-data">
		<span style="float:right; color:#609; font-weight:bold; font-size:13px; margin-top:-30px;"><label id="selected_item" style="position:fixed"></label></span>
		<table style="font-family:Arial, Helvetica, sans-serif; font-size:12px; border-collapse:collapse" width="100%">
			<tr style="background:#CCCCCC">
				<th width="80">No</th>
				<th>Filename</th>
				<th>Date</th>
				<th width="150"><a href="processbackup.php" title="Create Database Backup"><img src="../img/backup.png" width="25"></a></th>
			</tr>
			<?php
			$counter = 0;
		
			$file = array();
			$file = listdir_by_date("./backup/");
		
			foreach ($file as $key => $val) 
			{
				// Print the filenames that have .sql extension
				if (strpos($val,'.sql',1)) 
				{ 
					$counter++;
					
					// Get time and date from filename
					$date = substr($val, 11, 10);
					$time = substr($val, 22, 8);
						
					// Remove the sql extension part in the filename
					$filenameboth = str_replace('.sql', '', $val);
			?>	
			<tr>
				<td><?php echo $counter; ?></td>
				<td><?php echo $filenameboth; ?></td>
				<td><?php echo $date . " - " . str_replace('_', ':', $time); ?></td>
				<td>
					<a href="restorebackup.php?id=<?php echo $filenameboth; ?>"><img src="../img/restore.png" title="Restore Database" width="18"></a>&nbsp;
					<a href="download.php?id=<?php echo $filenameboth; ?>"><img src="../img/download.gif" title="Download SQL" width="18"></a>&nbsp;
					<a href="javascript:deleteConfirmation('<?php echo $filenameboth; ?>')"><img src="../img/delete-btn.png" title="Delete" width="20"></a>
				</td>
			</tr> 	
			<?php
				}
			}
			?>
		</table>
		</form>
		</td>
	</tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="5" align="right"><input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""></td>
    </tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
    </tr>
</table>
</center>
<script>
$('#backuptime').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%H:%i", labelTitle: "Select Time"}).focus(); } ).
keydown(
	function(e)
    {
    	var key = e.keyCode || e.which;
        if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
        {
        	$(this).off('keydown').AnyTime_picker().focus();
            e.preventDefault();
        }
    } );


function deleteConfirmation(filename){
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete backup file: ' + filename + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				window.location = 'delete.php?file=' + filename;
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