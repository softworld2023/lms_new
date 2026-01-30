<?php


$d1 = new DateTime("2014-05-15");
$d2 = new DateTime();
$d3 = $d1->diff($d2);
$d4 = ($d3->y*12)+$d3->m;
echo $d4;
?>