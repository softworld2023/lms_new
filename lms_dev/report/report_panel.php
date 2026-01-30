<?php
	session_start();
	/*The reason I wrote this extra code for this is because 
		client request some restriction to the users other
		than 'Boss'
	*/
	//include('../include/page_header.php'); 
?>

<a href="index.php" id="active-menu">Borrow Out</a>
<a href="payout.php">Actual Payout</a>
<a href="collection.php">Total Collection</a>
<a href="profit.php">Profit & Loss</a>
<a href="expenses.php">Expenses</a>
<a href="interest.php">Interest Earn</a>
<a href="latepayment.php">Late Payment Collections</a>
<?php
			if($_SESSION['login_level'] == 'Boss' || ($_SESSION['login_username'] == 'softworld' 
														|| $_SESSION['login_username'] == 'fong' 
														|| $_SESSION['login_username'] == 'staff'
														|| $_SESSION['login_username'] == 'staff'
														|| $_SESSION['login_username'] == 'waynechua'
														|| $_SESSION['login_username'] == 'ming'
														|| $_SESSION['login_username'] == 'fong'
														|| $_SESSION['login_username'] == 'wanpin'))
			{
		?>
			<a href="daily.php">Daily Collections</a>
			<a href="statement.php">Statement</a>
<?php
	}
?>
		