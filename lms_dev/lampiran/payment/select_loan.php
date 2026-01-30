<body style="align:center">
<h3>Select a Loan Code<h3>
<hr><br>
<?php
session_start();
include("../include/dbconnection2.php");

$ic = $_GET['ic'];
$branch_id = $_GET['branch_id'];

$getcode_q = mysql_query("SELECT * FROM loan_payment_details WHERE nric = '".$ic."' AND branch_id = '".$branch_id."' GROUP BY customer_loanid", $db2);
while($getcode = mysql_fetch_assoc($getcode_q)){ ?>
	<a href="view_details.php?ic=<?php echo $ic; ?>&branch_id=<?php echo $branch_id; ?>&loanid=<?php echo $getcode['customer_loanid']; ?>" target="_parent"><?php echo $getcode['customer_loanid']; ?></a>&nbsp;
 
 <?php } ?>
 </body>