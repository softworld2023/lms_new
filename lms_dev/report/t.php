<?php
 include('../include/page_header.php'); 

$person_q = mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND date LIKE '%2015-04%' AND branch_id = '1' AND package_id = '1' ORDER BY receipt_no DESC");
$person1 = mysql_fetch_assoc($person_q);


$person2 = explode(' ', $person1['receipt_no']);
//$state = $state2[0];

$person = $person2[1];

echo $person;

$tq = mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND date LIKE '%2015-04%' AND branch_id = '1' AND package_id = '1' AND receipt_no LIKE '%04 %' ORDER BY receipt_no DESC");
while($t = mysql_fetch_assoc($tq))
{
	echo "<br>".$t['receipt_no'];
}
?>