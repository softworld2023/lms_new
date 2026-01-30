<?php 
$link = urldecode($_GET['link']);
?>
<html>
<style>
@media print{
	.no-print{
		display:none;
	}
	.print{
		 display:block;
	}
	
	@page{
		margin:30;
		size: auto;
	}
}
.btn {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  font-family: Arial;
  color: #ffffff;
  font-size: 13px;
  background: #ff8400;
  padding: 7px 23px 7px 23px;
  text-decoration: none;
}

.btn:hover {
  background: #ffaa00;
  text-decoration: none;
}
</style>
<div class="no-print"><!-- 
	<button class="btn" onClick="print()">Print <?php echo $link;?></button> -->
</div>
<div class="print" width="100%" height="100%">
	<center>
		<img class="zoom" src="<?php echo $link; ?>" alt='Image' title="<?php echo $link;?>" width='80%' height='80%'/>
	</center>
</div>

<script src="wheelzoom.js"></script>
<script>
	wheelzoom(document.querySelector('img.zoom'));
</script>





