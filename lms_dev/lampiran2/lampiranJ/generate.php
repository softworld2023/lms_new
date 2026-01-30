<?php
session_start();
include("../include/dbconnection.php");
error_reporting(0);
//insert path to database connection
$id = $_GET['id'];

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
$loan = mysql_fetch_assoc($loan_q);

$pay_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loan['id']."' AND month = 1");
$pay = mysql_fetch_assoc($pay_q);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$id."'");
$cust = mysql_fetch_assoc($cust_q);
?>
<html>
<style>
@media print{
	.no-print{
		display:none;
	}
	.page {
		page-break-after: always;
	}
	@page { 
		margin: 0;
		size: A4;
	}
}
.no-print table tr td {
	font-family: Arial Black, Gadget, sans-serif;
}
.no-print table tr td {
	font-family: Verdana, Geneva, sans-serif;
}
.page table {
	font-size: medium;
}

.page table tr td p {
	font-size: 18px;
}
.page table {
	font-size: 20px;
}
</style>
<body>
<div class="no-print">
<button class="submit-btn" onClick="print();">Print</button>
<hr/>
</div>
<div class="page">

<table width="89%" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><p align="center">&nbsp;</p>
      <p align="center">&nbsp;</p>
      <p align="center">&nbsp;</p>
      <p align="center">SCHEDULE J</p>
      <p align="center">MONEYLENDERS ACT 1951</p>
      <p align="center">MONEYLENDERS (CONTROL  AND LICENSING )<br>
        REGULATIONS 2003</p>
      <p align="center">( Subregulation 10(1)  )</p>
      <p align="center">MONEYLENDING  AGREEMENT (UNSECURED LOAN )</p>
      <p style="text-align:justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; AN AGREEMENT made the day and year  stated in section 1 of the First Schedule hereto between the moneylender as  specified in section 2 of the First Schedule&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (&ldquo;the Lender&rdquo;) of the one part and  the borrower as specified in section 3 of the First Schedule (&ldquo;the Borrower&rdquo;)  of the other part.</p>
      <p style="text-align:justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WHEREAS the Lender is a licensed  moneylender under the Moneylenders Act 1951 hereby agrees to lend the Borrower  and the Borrower agrees to borrow from the Lender for the purpose of this  agreement a sum of money as specified in section 4 of the First Schedule (&ldquo;the  Principal Sum&rdquo;).</p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;NOW  IT IS AGREED as follows :</p>
      <p><u>Installment  Repayment</u></p>
      <p style="text-align:justify">1.The installment repayments in this Agreement shall be due  and payable without demand the first of which is to be made on the <span style="border-bottom:thin dotted #000">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('d/m/Y', strtotime($pay['next_paymentdate'])); ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> date of the first repayment) and thereafter on the <span style="border-bottom:thin dotted #000">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('d', strtotime($pay['next_paymentdate'])); ?>th&nbsp;&nbsp;&nbsp;&nbsp;</span> of each and every  subsequent month until the expiration of the said <span style="border-bottom:thin dotted #000">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $loan['loan_period']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> months from the date  hereof.</p>
      <p><u>Default</u></p>
      <p style="text-align:justify">2. (1) if default is made in the repayment upon the due date  of any sum of installment payable of the Lender under this agreement, whether  in respect of principal or interest, the Lender shall be entitled to charge  simple interest on the unpaid sum of installment which shall be calculated at  the rate of eight per centum per annum from day to day from the date of default  in repayment of the sum of installment until that sum of installment is paid,  and any interest so charge shall not be reckoned.</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>For the purpose of this Agreement as part of the interest  charged in respect of the loan.</p>
      <p>&nbsp;&nbsp; (2) The interest  shall be calculated in accordance with the following fomula :</p>
      <p align="center"> R&nbsp; =&nbsp; &nbsp; <u>8</u>&nbsp;&nbsp;&nbsp; X&nbsp;&nbsp;&nbsp; <u>D </u>&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp; S<br>
  100&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 365&nbsp;&nbsp; </p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Where,</p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; R = represents of interest to be paid<br>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; D = represents the number of the  days in default</p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; S = represents the sum of monthly installment which is overdue</p>
      <p>&nbsp;</p>
      <p><u>Right of action</u></p>
      <p>3. (1) If the Borrower :-</p>
      <p>(a)  fails to repay any sum of installment payable or any part thereof and any  interest payable under section 5 of the First Schedule for any period in excess  of twenty eight days its due date or :<br>
  &nbsp;<br>
        (b) commits an act of bankruptcy or enters into any  composition or :</p>
      <p>(c) arrangement with his creditors or, being a company,  enters into liquidation, whether compulsory or voluntary,</p>
      <p>The Lender may terminate this Agreement.</p>
      <p style="text-align:justify">&nbsp;&nbsp;&nbsp; (2) Upon the  occurrence of any of the events specified in subclause (1) herein the Lender  shall give the Borrower not less then fourteen days (14 days) notices in  writing to treat this Agreement as having been repudiated by the Borrower and  unless in the mean while such default alleged is rectified or such unpaid sum  of installmentand are paid, this Agreement shall the expiry of the said notice,  at the option of the Lender be deemed to be unnulled.<br>
  &nbsp;&nbsp;&nbsp;&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p style="text-align:justify">&nbsp;(3) In the events  this Agreement has been terminate or annulled, the Lender may claim
        the balance outstanding from the Borrower in accordance with  the provision under Order 45 of the Subordinate Court Rules 1990 [P.U.(A)  97/1990] in case the balance outstanding does not exceed two hundred and fifthy  thounsand ringgit or Order 79 of the Rules of the High Court 1980 [P.U.(A)  50/1980] in case the balance outstanding is higher than two hundred and fifty  thounsand ringgit</p>
      <p><u>Compliance with  written law</u></p>
      <p style="text-align:justify">4.&nbsp; The Lender shall,  in relation to the moneylending business, conform to the provisions and  requirements of the Moneylenders Act 1951 and any written law for the time  being in force affecting the business.</p>
      <p><u>&nbsp;</u></p>
      <p><u>Stamp duties and  attestation fees</u></p>
      <p>5.&nbsp; All stamp duties  and attestation fee incurred in connection with this Agreement shall be borne  by the Borrower.</p>
      <p><u>&nbsp;</u></p>
      <p><u>Services of  documents</u></p>
      <p style="text-align:justify">6.&nbsp; (1) Any notice,  request or demand required to be served by either party hereto to the other  under this Agreement shall be in writing and shall be deemed to be sufficiently  served :-</p>
      <ol>
        <li>if it  is sent by the party or his solicitors by A.R. registered post addressed to the  other party&rsquo;s address hereinbefore mentioned and in such case it shall be  deemed to have been received upon the expiry of a period of five days of  posting of such registered letter or :</li>
        <li>if it  is given by the party or his solicitors by hand to the other party or his  solicitors.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </li>
      </ol>
      <p>&nbsp;</p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp; (2) Any change of  address by either party shall be communicated to the other.</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p><u>Schedules</u></p>
<p>7. The Schedule hereto shall form part of this Agreement and  shall be read, taken and construed as an essential part of this Agreement.</p>
      <p>&nbsp;</p>
      <p><u>Time essence of  Agreement</u></p>
      <p>8. Time shall be the essence of the Agreement in relation to  all provisions of this Agreement.<br>
      </p>
      <p><u>Person to be bound  by Agreement</u></p>
      <p>9. This Agreement shall be binding upon the successors in  title and permitted assigns of the Lender, the heirs, personal representatives,  successors in little and permitted assigns of the Borrower.</p>
      <p>&nbsp;</p>
      <p>IN WITNESS WHEREOF the parties hereto have hereunto set their hands the day and year first above  written.</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>SIGNED by the abovenamed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;)<br>
        Borrower&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;)<br>
        Name : <span style="border-bottom:thin dotted #000">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cust['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;)<br>
        I/C Co. Reg. No. <span style="border-bottom:thin dotted #000">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cust['nric']; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> )</p>
      <p>&nbsp;</p>
      <p>SIGNED by the abovenamed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )<br>
        Lender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  )<br>
        Name : &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&nbsp; )<br>
        I/C Co. Reg. No. &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&nbsp; )</p>
      <p><br>
      &nbsp;I, do solemnly and  sincerely declare that I have explained the terms of this Agreement to the  Borrower and it appears to me that the Borrower has understood the nature and  consequence of this Agreement.</p></td>
  </tr>
</table>
</div>

</body>
</html>
