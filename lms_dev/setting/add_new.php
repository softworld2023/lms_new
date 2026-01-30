<?php include('../include/page_header.php'); ?>
<script type="text/javascript" src="../include/js/password_strength_plugin.js"></script>
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
input
{
	height:30px;
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
	text-align:right;
	padding-left:20px;
	padding-right:10px;
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
#reset
{
	background:url(../img/add-enquiry/clear-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#reset:hover
{
	background:url(../img/add-enquiry/clear-btn-roll-over.jpg);
}
#add_staff
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#add_staff:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
-->
</style>

<center>
<form method="post" action="action.php" onSubmit="return checkForm()">
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/settings.png" style="height:47px"></td>
        <td>User Setting: <strong>New User</strong></td>
    </tr>
    <tr>
    	<td colspan="2">
            <div id="message" style="width:100%;">
            <?php
            if($_SESSION['msg'] != '')
            {
                echo $_SESSION['msg'];
                $_SESSION['msg'] = '';
            }
            ?>
            </div>
		</td>
    </tr>
</table>
<table width="1280" id="list_table">
	<tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
    	<th width="15%">Staff Name</th>
        <td><input type="text" name="staffname" id="staffname" /></td>
    </tr>
    <tr>
    	<th>Username</th>
        <td><input type="text" name="username" id="username" /></td>
    </tr>
    <tr>
        <th>Password</th>
        <td><input type="password" name="password" id="password" /></td>
    </tr>
    <tr>
        <th>Confirm Password</th>
        <td><input type="password" name="confirm_password" id="confirm_password" /></td>
    </tr>
    <tr>
    	<th>Level</th>
        <td>
                <select name="level" id="level" style="height:30px">
                    <option value="">-Select-</option>
                    <option value="Boss">Boss</option>
                    <option value="Branch Manager">Branch Manager</option>
                <option value="Admin">Admin</option>
                    <option value="Staff">Staff</option>
                </select>        
		</td>
	</tr>
    <tr>
      <th>Branch</th>
      <td>
	  			<select name="branch" id="branch" style="height:30px">
					<option value="">-Select-</option>
					<?php
						$branch_q = mysql_query("SELECT * FROM branch");
						while($branch = mysql_fetch_assoc($branch_q))
						{
					?>
							<option value="<?php echo $branch['branch_id']; ?>"><?php echo $branch['branch_name']; ?></option>
					<?php 
						}
					?>
				</select>
	  </td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="add_staff" id="add_staff" value="">&nbsp;&nbsp;&nbsp;
        <input type="reset" id="reset" name="reset" value="">&nbsp;&nbsp;&nbsp;<input type="button" name="back" id="back" onClick="window.location.href='../setting/'" value="">        </td>
    </tr>
    <tr>
    	<td height="16" colspan="2">&nbsp;</td> 
    </tr>
</table>
</form>
</center>
<script>

$("#password").passStrength({
	userid:	"#username"
});

function checkForm()
{
	if((document.getElementById('staffname').value != '' && document.getElementById('username').value != '' && document.getElementById('level').value != '' && document.getElementById('password').value != '' && document.getElementById('confirm_password').value != ''))
	{
			if(document.getElementById('confirm_password').value !=  document.getElementById('password').value)
			{
				var msg = "<div class='error'>Password is not match!</div>";
				$('#message').empty();
				$('#message').append(msg); 
				$('#message').html();
				return false;
			}
	}else
	{
		var msg = "<div class='error'>Please fill in all the text fields!</div>";
		$('#message').empty();
		$('#message').append(msg); 
		$('#message').html();
		return false;
	}
}
</script>
</body>
</html>