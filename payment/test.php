<?php 
$a = 'test 1';
$b = 'test 1 TEst3';

echo $a."<br><br>".$b;

$length = strlen($a);

$final = substr($b, $length);
echo "<br>".$final;
?>