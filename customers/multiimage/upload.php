<?php
//If directory doesnot exists create it.
session_start();
$customer_id = $_SESSION['custid'];
if (!file_exists('../appform/'.$customer_id)) 
{
	mkdir('../appform/'.$customer_id, 0777, true);
}
$output_dir = "../appform/".$customer_id."/";

if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
   {
    
    	if(!is_array($_FILES["myfile"]['name'])) //single file
    	{
            $RandomNum   = time();
            
            $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
            $ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.
         
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt       = str_replace('.','',$ImageExt);
            $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

       	 	move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $NewImageName);
       	 	 //echo "<br> Error: ".$_FILES["myfile"]["error"];
       	 	 
	       	 	 $ret[$fileName]= $output_dir.$NewImageName;
    	}
    	else
    	{
            $fileCount = count($_FILES["myfile"]['name']);
    		for($i=0; $i < $fileCount; $i++)
    		{
                $image =$_FILES["myfile"]["name"][$i];
			$uploadedfile = $_FILES['myfile']['tmp_name'][$i];
			 
			
			if ($image) 
			{
			
				$filename = stripslashes($_FILES['myfile']['name'][$i]);
			
				$extension = getExtension($filename);
				$extension = strtolower($extension);
				
				
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
			{
			
				$change='<div class="msgdiv">Unknown Image extension </div> ';
				$errors=1;
			}
			else
			{
			
				
			
			
				if($extension=="jpg" || $extension=="jpeg" )
				{
				$uploadedfile = $_FILES['myfile']['tmp_name'][$i];
				$src = imagecreatefromjpeg($uploadedfile);
				
				}
				else if($extension=="png")
				{
				$uploadedfile = $_FILES['myfile']['tmp_name'][$i];
				$src = imagecreatefrompng($uploadedfile);
				
				}
				else 
				{
				$src = imagecreatefromgif($uploadedfile);
				}
				
				echo $scr;
				
				list($width,$height)=getimagesize($uploadedfile);
				
				
				$newwidth=600;
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				
				$newwidth1=25;
				$newheight1=($height/$width)*$newwidth1;
				$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
				
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
				
				
				$filename = "../images/". $_FILES['myfile']['name'][$i];
				
				//$filename1 = "../images/small/". $_FILES['myfile']['name'][$i];
				
				
				
				imagejpeg($tmp,$filename,100);
				
				//imagejpeg($tmp1,$filename1,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				imagedestroy($tmp1);
				}
			}
    	}
    }
    echo json_encode($ret);
 
}

?>