<?php


$number = $loan2['balance'];
echo strtoupper(NumberAsString4($number));

function NumberAsString4($number)

{

$Strs = array();

$StrsA = array();

   $Result="";          // Generated result

   $Str1="";            // Temp string

   $Str2="";            // Temp string

   $n=$number;           // Working copy

   $Billions=0;

   $Millions=0;

   $Thousands=0;

   $Hundreds=0;

   $Tens=0;

   $Ones=0;

   $Point=0;

   $HaveValue=0;        // Flag needed to know if to process "0"

   if ($number == 0 or strlen($number) == 0)

   {

     return "Zero";

   }



   // Initialize strings

   // Strings are "externalized" to simplify

   // changing text or translating

   if (count($Strs)==0)

   {

      $Strs["space"]=" ";

      $Strs["and"]="and";
	  
	  $Strs["cents"]="cents";

      $Strs["point"]="Point";

      $Strs["n0"]="Zero";

      $Strs["n1"]="One";

      $Strs["n2"]="Two";

      $Strs["n3"]="Three";

      $Strs["n4"]="Four";

      $Strs["n5"]="Five";

      $Strs["n6"]="Six";

      $Strs["n7"]="Seven";

      $Strs["n8"]="Eight";

      $Strs["n9"]="Nine";

      $Strs["n10"]="Ten";

      $Strs["n11"]="Eleven";

      $Strs["n12"]="Twelve";

      $Strs["n13"]="Thirteen";

      $Strs["n14"]="Fourteen";

      $Strs["n15"]="Fifteen";

      $Strs["n16"]="Sixteen";

      $Strs["n17"]="Seventeen";

      $Strs["n18"]="Eighteen";

      $Strs["n19"]="Nineteen";

      $Strs["n20"]="Twenty";

      $Strs["n30"]="Thirty";

      $Strs["n40"]="Forty";

      $Strs["n50"]="Fifty";

      $Strs["n60"]="Sixty";

      $Strs["n70"]="Seventy";

      $Strs["n80"]="Eighty";

      $Strs["n90"]="Ninety";

      $Strs["n100"]="Hundred";

      $Strs["nK"]="Thousand";

      $Strs["nM"]="Million";

      $Strs["nB"]="Billion";

   }

   

   // Save strings to an array once to improve performance

   if (count($StrsA)==0)

   {

      // Arrays start at 1, to 1 contains 0

      // 2 contains 1, and so on

      

      $StrsA[1]=$Strs["n0"];

      $StrsA[2]=$Strs["n1"];

      $StrsA[3]=$Strs["n2"];

      $StrsA[4]=$Strs["n3"];

      $StrsA[5]=$Strs["n4"];

      $StrsA[6]=$Strs["n5"];

      $StrsA[7]=$Strs["n6"];

      $StrsA[8]=$Strs["n7"];

      $StrsA[9]=$Strs["n8"];

      $StrsA[10]=$Strs["n9"];

      $StrsA[11]=$Strs["n10"];

      $StrsA[12]=$Strs["n11"];

      $StrsA[13]=$Strs["n12"];

      $StrsA[14]=$Strs["n13"];

      $StrsA[15]=$Strs["n14"];

      $StrsA[16]=$Strs["n15"];

      $StrsA[17]=$Strs["n16"];

      $StrsA[18]=$Strs["n17"];

      $StrsA[19]=$Strs["n18"];

      $StrsA[20]=$Strs["n19"];

      $StrsA[21]=$Strs["n20"];

      $StrsA[31]=$Strs["n30"];

      $StrsA[41]=$Strs["n40"];

      $StrsA[51]=$Strs["n50"];

      $StrsA[61]=$Strs["n60"];

      $StrsA[71]=$Strs["n70"];

      $StrsA[81]=$Strs["n80"];

      $StrsA[91]=$Strs["n90"];

   }



   // How many billions?

  

   $Billions=floor($n/1000000000);

   if ($Billions)

   {

      $n=$n-(1000000000*$Billions);

      $Str1=NumberAsString4($Billions).$Strs["space"].$Strs["nB"];

      if (strlen($Result))

         $Result=$Result.$Strs["space"];

      $Result=$Result.$Str1;

      $Str1="";

      $HaveValue=1;

   }



   // How many millions?

   $Millions=floor($n/1000000);

   if ($Millions)

   {

      $n=$n-(1000000*$Millions);

      $Str1=NumberAsString4($Millions).$Strs["space"].$Strs["nM"];

      if (strlen($Result))

         $Result=$Result.$Strs["space"];

      $Result=$Result.$Str1;

      $Str1="";

      $HaveValue=1;

   }



   // How many thousands?

   $Thousands=floor($n/1000);

   if ($Thousands)

   {

      $n=$n-(1000*$Thousands);

      $Str1=NumberAsString4($Thousands).$Strs["space"].$Strs["nK"];

      if (strlen($Result))

         $Result=$Result.$Strs["space"];

      $Result=$Result.$Str1;

      $Str1="";

      $HaveValue=1;

   }

 // Anything after the decimal point?

   $dogdoo = strpos($number,".");

   if ($dogdoo !== FALSE)

      $Point=substr($number, -(strlen($number) - strrpos($number, ".") - 1) );

   // How many hundreds?

   $Hundreds=floor($n/100);

   if ($Hundreds)

   {

      $n=$n-(100*$Hundreds);
	  
	  // How many tens?

		$Tens1=floor($n/10);
		
		if ($Tens1)
		
		  $n2=$n-(10*$Tens);
		
		
		
		// How many ones?
		
		$Ones1=floor($n/1);
		
		if ($Ones1)
		
		  $n1=$n-($Ones1);
		  
		  if($Tens1 == 0 && $Ones1 == 0 && $Point == 0)
		  {
		  	$Str1=$Strs["and"].$Strs["space"].NumberAsString4($Hundreds).$Strs["space"].$Strs["n100"];
		  }else
		  {

      		$Str1=NumberAsString4($Hundreds).$Strs["space"].$Strs["n100"];
		   }

      if (strlen($Result))

         $Result=$Result.$Strs["space"];

      $Result=$Result.$Str1;

      $Str1="";

      $HaveValue=1;

   }   



   // How many tens?

   $Tens=floor($n/10);

   if ($Tens)

      $n=$n-(10*$Tens);

    

   // How many ones?

   $Ones=floor($n/1);

   if ($Ones)

      $n=$n-($Ones);

   

  

   

   // If 1-9

   $Str1="";

   if($Tens == 0)

   {

      if ($Ones == 0)

      {

         if (!$HaveValue)

            $Str1=$StrsA[0];

      }

      else
	  {

         // 1 is in 2, 2 is in 3, etc

         $Str1=$StrsA[$Ones+1];
		 
	  }

   }

   else if ($Tens == 1)

   // If 10-19

   {

		if($Point == '0')
		{
			// 10 is in 11, 11 is in 12, etc
			$Str1=$Strs["and"].$Strs["space"].$StrsA[$Ones+11];
	  		
		}else
		{
			// 10 is in 11, 11 is in 12, etc
	  		$Str1=$StrsA[$Ones+11];
		}

   }

   else

   {

      // 20 is in 21, 30 is in 31, etc

      $Str1=$StrsA[($Tens*10)+1];

      

      // Get "ones" portion

      if ($Ones)

         $Str2=NumberAsString4($Ones);
		 

		if($Point == '0')
		{
			// 10 is in 11, 11 is in 12, etc
			 $Str1=$Strs["and"].$Strs["space"].$Str1.$Strs["space"].$Str2;
		}else
		{
			// 10 is in 11, 11 is in 12, etc
			 $Str1=$Str1.$Strs["space"].$Str2;
		}

     

   }

   

   // Build result   

   if (strlen($Str1))

   {

      if (strlen($Result))

         $Result=$Result.$Strs["space"];

      $Result=$Result.$Str1;

   }



   // Is there a decimal point to get?

   if ($Point)

   {

      $Str2=NumberAsString4($Point);
	  if($Point == '0')
	  {
	  	$Result=$Result.$Strs["space"].$Strs["space"]." ONLY";
	  }else
	  {
		$Result=$Result.$Strs["space"].$Strs["and"].$Strs["space"].$Strs["cents"].$Strs["space"].$Str2.$Strs["space"]." ONLY";
	  }
   }

    

   return $Result;

}



?>