<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

date_default_timezone_set('Asia/Kuala_Lumpur');

if(isset($_POST['add_enquiry']))
{
	
	$enq_date = date('Y-m-d', strtotime($_POST['enq_date']));
	$time = date('H:i:s');
	$cust_name = addslashes(strtoupper($_POST['cust_name']));
	$icno = $_POST['nric'];
	$gender = $_POST['gender'];
	//$contact_no = $_POST['contact_no'];
	$email = $_POST['email'];
	$branch = addslashes(strtoupper($_POST['branch']));
	$remarks = addslashes(strtoupper($_POST['remarks']));
	$mobile = $_POST['mobile'];
	$home_p = $_POST['homephone'];
	$race = $_POST['race'];
	$other_race = addslashes(strtoupper($_POST['other_race_name']));
	$oldic = addslashes(strtoupper($_POST['oldicno']));
	$otherno = $_POST['otherno'];
	$otherno_detail = addslashes(strtoupper($_POST['no_oth']));
	
	$insert_q = mysql_query("INSERT INTO enquiry SET enq_date = '".$enq_date."', time = '".$time."', cust_name = '".$cust_name."', icno = '".$icno."', gender = '".$gender."', email = '".$email."', branch = '".$branch."', remarks = '".$remarks."', oldic = '".$oldic."', othersno = '".$otherno."', othersno_detail = '".$otherno_detail."', mobile = '".$mobile."', home = '".$home_p."', race = '".$race."', other_race = '".$other_race."' ");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Customer enquiry has been successfully saved into record.</div>";
		echo "<script>window.location='../enquiry/enquiry.php'</script>";	
	}
}else
if(isset($_POST['edit_enquiry']))
{
	$id = $_POST['id'];
	$enq_date = date('Y-m-d', strtotime($_POST['enq_date']));
	$time = date('H:i:s');
	$cust_name = addslashes(strtoupper($_POST['cust_name']));
	$icno = $_POST['nric'];
	$gender = $_POST['gender'];
	//$contact_no = $_POST['contact_no'];
	$email = $_POST['email'];
	$branch = addslashes(strtoupper($_POST['branch']));
	$remarks = addslashes(strtoupper($_POST['remarks']));
	$mobile = $_POST['mobile'];
	$home_p = $_POST['homephone'];
	$race = $_POST['race'];
	$other_race = addslashes(strtoupper($_POST['other_race_name']));
	$oldic = addslashes(strtoupper($_POST['oldicno']));
	$otherno = $_POST['otherno'];
	$otherno_detail = addslashes(strtoupper($_POST['no_oth']));
	
	$update_q = mysql_query("UPDATE enquiry SET enq_date = '".$enq_date."', time = '".$time."', cust_name = '".$cust_name."', icno = '".$icno."', gender = '".$gender."', email = '".$email."', branch = '".$branch."', remarks = '".$remarks."', oldic = '".$oldic."', othersno = '".$otherno."', othersno_detail = '".$otherno_detail."', mobile = '".$mobile."', home = '".$home_p."', race = '".$race."', other_race = '".$other_race."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$_SESSION['msg'] = "<div class='success'>Enquiry record has been successfully updated.</div>";
		echo "<script>window.location='../enquiry/'</script>";	
	}
}else
if($_POST['action'] == 'delete_enquiry')
{
	$id = $_POST['id'];
	
	$delete_q = mysql_query("DELETE FROM enquiry WHERE id = '".$id."'");
	
	if($delete_q)
	{
		$_SESSION['msg'] = "<div class='success'>Enquiry record has been deleted from database</div>";
		echo "<script>window.location='../enquiry/'</script>";	
	}
}
?>