<?php
session_start();
require_once("include/dbconnection.php");
require 'include/plugin/PasswordHash.php';

$empty = mysql_query("TRUNCATE TABLE customer_details");

if($empty)
{
	echo "cleared1";
	$empty2 = mysql_query("TRUNCATE TABLE customer_employment");
	if($empty2)
	{
		echo "cleared2";
		$empty3 = mysql_query("TRUNCATE TABLE customer_address");
		if($empty3)
		{
			echo "cleared3";
			$empty4 = mysql_query("TRUNCATE TABLE customer_account");
			if($empty4)
			{
				echo "cleared4";
				$empty5 = mysql_query("TRUNCATE TABLE customer_emergency");
				if($empty5)
				{
					echo "cleared5";
					$empty6 = mysql_query("TRUNCATE TABLE customer_financial");
					if($empty6)
					{
						echo "cleared6";
						$empty7 = mysql_query("TRUNCATE TABLE customer_relative");
						if($empty7)
						{
							echo "cleared7";
							$empty8 = mysql_query("TRUNCATE TABLE customer_spouse");
							if($empty8)
							{
								echo "cleared8";
								$empty9 = mysql_query("TRUNCATE TABLE customer_loanpackage");
								if($empty9)
								{
									echo "cleared9";
									$empty10 = mysql_query("TRUNCATE TABLE loan_payment_details");
									if($empty10)
									{
										echo "cleared10";
									}
								}
							}
						}
					}
				}
			}
		}
	}
}


?>