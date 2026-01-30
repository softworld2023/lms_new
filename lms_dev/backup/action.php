<?php
session_start();
include("../include/dbconnection.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if(isset($_POST['save_time']))
{
	$time = $_POST['backuptime'];
	$sch = $_POST['backup_time'];
	
	$sql = mysql_query("UPDATE backup_setting SET backup_time = '".$time."', backup_sch = '".$sch."' WHERE id = '1'");
	
	if($sql)
	{
		$msg .= 'Backup time schedule setting has been successfully updated.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.location='index.php'</script>";
}
if(isset($_POST['update_reward']))
{

	$ctr = $_POST['ctr'];
	
	for($i=1;$i<=$ctr;$i++)
	{
		$point_setting = $_POST['point_setting'.$i];
		$id = $_POST['id'.$i];
		
		$save_q = mysql_query("UPDATE point_setting SET point_setting = '".$point_setting."', modified_date = now(), modified_by = '".$_SESSION['taplogin_id']."' WHERE id = '".$id."'");
		
	}
	
	if($save_q)
	{
		$msg .= 'Rewards Point Setting has been successfully updated.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.location='setting.php'</script>";
}
else
if(isset($_POST['update_deliveryfee']))
{
		$fees = $_POST['fees'];
		
		$save_q = mysql_query("UPDATE delivery_fee SET fees = '".$fees."', modified_date = now(), modified_by = '".$_SESSION['taplogin_id']."'");
			
	if($save_q)
	{
		$msg .= 'Delivery Fees Setting has been successfully updated.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.location='delivery_fee.php'</script>";
}
else
if(isset($_POST['update_minprice']))
{

	$price = $_POST['price'];
	$id = $_POST['id'];
	
	$save_q = mysql_query("UPDATE min_price SET price = '".$price."', modified_date = now(), modified_by ='".$_SESSION['taplogin_id']."' WHERE id = '".$id."'");
		
	if($save_q)
	{
		$msg .= 'Minimum Order Price Setting has been successfully updated.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.location='min_price.php'</script>";
}
else
if(isset($_POST['update_discount']))
{
	
	$id = $_POST['id'];
	$discount = $_POST['discount'];
	$date_start = $_POST['date_start'];
	$date_end = $_POST['date_end'];
	
	$save_q = mysql_query("UPDATE discount_setting SET discount = '".$discount."', date_start = '".$date_start."', date_end = '".$date_end."', modified_date = now(), modified_by = '".$_SESSION['taplogin_id']."' WHERE id = '".$id."'");

	if($save_q)
	{
		$msg .= 'Discount Setting has been successfully updated.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.location='discount.php'</script>";
}
else
if(isset($_POST['save_doc']))
{
	$ctr = $_POST['counter'];
	
	for($i = 1; $i <= $ctr; $i++)
	{
		//save data in doc_setting table
		$update_q = mysql_query("UPDATE doc_setting SET prefix = '".strtoupper($_POST["prefix_$i"])."', next_num = '".$_POST["next_num_$i"]."', digit_num = '".$_POST["digit_num_$i"]."', modified_date = now(), modified_by = '".$_SESSION['taplogin_id']."' WHERE id = '".$_POST["id_$i"]."'");			
	}
	
	if($update_q)
	{
		$msg .= 'Document Numbering Setting has been successfully updated.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.location='doc_setting.php'</script>";

}
else
if(isset($_POST['add_redempt']))
{
	$redempt_point = $_POST['redempt_point'];
	$description = addslashes($_POST['description']);
	$product = $_FILES["product"]["name"];
	$path = '../FILES/REDEMPTION/';
	
	$insert_q = mysql_query("INSERT INTO redemption_products SET redempt_point = '".$redempt_point."', description = '".$description."', product = '".$product."', upload_date = now(), upload_by = '".$_SESSION['taplogin_id']."'");
	
	if($insert_q)
	{
		move_uploaded_file($_FILES["product"]["tmp_name"],$path. $_FILES["product"]["name"]);
		$msg .= 'Redemption product information has been successfully added into the record.<br>';
	}
	
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.parent.location='redemption.php'</script>";
}
else
if(isset($_POST['edit_redempt']))
{
	$id = $_POST['id'];
	$redempt_point = $_POST['redempt_point'];
	$description = $_POST['description'];
	$product = $_FILES["product"]["name"];
	$prev_product = addslashes($_POST['prev_product']);
	$path = '../FILES/REDEMPTION/';
	
	if($product == '')
	{
		$update_q = mysql_query("UPDATE redemption_products SET redempt_point = '".$redempt_point."', description = '".$description."', upload_date = now(), upload_by = '".$_SESSION['taplogin_id']."' WHERE id = '".$id."'");
	}
	else
	{
		$update_q = mysql_query("UPDATE redemption_products SET redempt_point = '".$redempt_point."', description = '".$description."', product = '".$product."', upload_date = now(), upload_by = '".$_SESSION['taplogin_id']."' WHERE id = '".$id."'");
		
		unlink($path.$prev_product);
	}
	
	if($update_q)
	{
		move_uploaded_file($_FILES["product"]["tmp_name"],$path. $_FILES["product"]["name"]);

		$msg .= 'Redemption product information has been successfully updated.';
	}

	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	echo "<script>window.parent.location='redemption.php'</script>";

}else
if($_POST['action'] == 'delete_product')
{
	$id = $_POST['id'];
	$product = $_POST['name'];
	
	$path = '../FILES/REDEMPTION/';
	
	unlink($path.$product);
	
	//delete record in database
	$delete_q = mysql_query("DELETE FROM redemption_products WHERE id = '".$id."'");
	
	if($delete_q)
	{
		$_SESSION['msg'] = "<div class='success'>Record has been successfully deleted from database.</div>";	
	}
}
else
if(isset($_POST['sub-del']))
{
	$counter = $_POST['ctr'];
	
	for($i = 1; $i <= $counter; $i++)
	{
		if($_POST["id_$i"] != '')
		{
			$id = $_POST["id_$i"];
			
			$sql = mysql_query("SELECT * FROM redemption_products WHERE id = '".$id."'");
			$get_q = mysql_fetch_assoc($sql);
			
			$path = '../FILES/REDEMPTION/';
			$product = $get_q['product'];
			
			unlink($path.$product);
						
			//delete record in database
			$delete_q = mysql_query("DELETE FROM redemption_products WHERE id = '".$id."'");
		}
	}
	
	$_SESSION['msg'] = "<div class='success'>Records have been successfully deleted!</div>";
	echo "<script>window.parent.location='redemption.php'</script>";
}

?>