<?php

$ctr = $_POST["inputs"];
?>
<td><input type="text" name="r_name_<?php echo $ctr; ?>" id="r_name_<?php echo $ctr; ?>" style="width:300px;" tabindex="13"></td>
<td><input type="text" name="r_relationship_<?php echo $ctr; ?>" id="r_relationship_<?php echo $ctr; ?>" tabindex="13"></td>
<td><input type="text" name="r_workas_<?php echo $ctr; ?>" id="r_workas_<?php echo $ctr; ?>" tabindex="13"></td>
<td><input type="text" name="r_contact_<?php echo $ctr; ?>" id="r_contact_<?php echo $ctr; ?>" tabindex="13"></td>
<td><textarea name="r_address_<?php echo $ctr; ?>" id="r_address_<?php echo $ctr; ?>" style="width:300px; height:40px; color:#666666; font-size:13px" tabindex="13"></textarea></td>
<td><img src="../img/customers/delete-icon.png" width="20" id="del_<?php echo $ctr; ?>" name="del_<?php echo $ctr; ?>" onclick="deleteRow('<?php echo $ctr; ?>');" style="cursor:pointer;"></td>

