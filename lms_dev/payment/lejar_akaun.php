<?php 
include('../include/page_header.php'); 

?>

<style>
.submit_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'remove.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}
.app_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'sent.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}
.reject_style {
	color : #eee;
    padding:4px;
    border : none;
    background:transparent url("<?php echo IMAGE_PATH.'cancel-icon.png'; ?>") no-repeat;
    cursor: pointer;
    background-size:21px 21px;
    text-indent: -1000em;
	width:25px;
}

#list_table
{
	border-collapse:collapse;
	border:none;	
}

#list_table tr th
{
	height:36px;
	background:#666;
	text-align:left;
	padding-left:10px;
	color:#FFF;
}
#list_table tr td
{
	height:35px;
	padding-left:10px;
	padding-right:10px;
}

#rl
{
	width:318px;
	height:36px;
	background:url(../img/customers/right-left.jpg);
}
#back
{
	background:url(../img/back-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#back:hover
{
	background:url(../img/back-btn-roll-over.jpg);
}
#search
{
	background:url(../img/enquiry/search-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#search:hover
{
	background:url(../img/enquiry/search-btn-roll-over.jpg);
}
#yearlyLedger {
  background: #fa900f;
  background-image: -webkit-linear-gradient(top, #fa900f, #fa900f);
  background-image: -moz-linear-gradient(top, #fa900f, #fa900f);
  background-image: -ms-linear-gradient(top, #fa900f, #fa900f);
  background-image: -o-linear-gradient(top, #fa900f, #fa900f);
  background-image: linear-gradient(to bottom, #fa900f, #fa900f);
  font-family: Arial;
  color: #ffffff;
  font-size: 14px;
  padding: 8px 20px 8px 20px;
  border: solid #ffbb0f 0px;
  text-decoration: none;
  cursor:pointer;
}

#yearlyLedger:hover {
  background: #f5a94c;
  background-image: -webkit-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -moz-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -ms-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: -o-linear-gradient(top, #f5a94c, #f5a94c);
  background-image: linear-gradient(to bottom, #f5a94c, #f5a94c);
  text-decoration: none;
}
</style>
<center>
<table width="1280">
    

	
    <tr>
    	<td width="65" align="left"><input type="button" name="back" id="back" onClick="window.location.href='../payment/'" value=""></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td align="center" style="line-height:20px;">
		<strong>
		AKTA PEMBERI PINJAM WANG 1951<br />
		[ Subseksyen 118(1) ]<br />
		<u>LEJAR AKAUN PEMINJAM</u>
		</strong>
	</td>
  </tr>
</table>
<br /><br/>
<table width="1280" id="list_table">
	<tr>
    	<th width="35" height="80">Bil</th>
    	<th width="161">Nama Peminjam </th>
    	<th width="121">Jumlah Pinjaman (RM) </th>
        <th width="83">Baki Pinjaman (RM) </th>
        <th width="77">Tempoh Pinjaman (Bulan) </th>
        <th width="80">Tarikh Pinjaman </th>
        <th width="109">No Telefon Peminjam </th>
        <th width="74">Kaadar Faedah (%) </th>
        <th width="90">Bercagar/ Tanpa Cagaran </th>
        <th colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="4" id="list_table">
          <tr>
            <td colspan="2" align="center">Syarikat</td>
          </tr>
          <tr>
            <td width="47%" height="57">Bumi</td>
            <td width="53%"><p>Bukan Bumi </p>            </td>
          </tr>
        </table></th>
        <th colspan="2"><table width="98%" border="0" cellspacing="0" cellpadding="4" id="list_table">
          <tr>
            <td colspan="2" align="center">Individu</td>
          </tr>
          <tr>

            <td width="38%">Kaum</td>
            <td width="62%">Majikan*</td>
          </tr>
        </table></th>
     	
		<th width="79">Gaji (RM)</th>
	</tr>
<?php   
             
    $i=0;$total1= 0; $totalbaki=0;
   
    $rt=mysql_query("select * from customer_details ");     
     
    while($nt=mysql_fetch_assoc($rt)){
     
    $i++;
    
    $ctd_q = mysql_query("select * from customer_loanpackage WHERE customer_id = '".$nt['id']."'");   
    $ctd = mysql_fetch_assoc($ctd_q);
    
    $cto_q = mysql_query("select * from loan_payment_details WHERE customer_loanid = '".$ctd['id']."'");   
    $cto = mysql_fetch_assoc($cto_q);
    
	$ca = mysql_query("select * from customer_address WHERE customer_id = '".$nt['id']."'");   
    $cd = mysql_fetch_assoc($ca);
     
	$ce = mysql_query("select * from customer_employment WHERE customer_id = '".$nt['id']."'");   
    $ce_e = mysql_fetch_assoc($ce);
	
	$cf = mysql_query("select * from customer_financial WHERE customer_id = '".$nt['id']."'");   
    $cf_f = mysql_fetch_assoc($cf);   
	
	$cto_q1 = mysql_query("select * from loan_payment_details WHERE month = '1' AND customer_loanid = '".$ctd['id']."'  ");   
    $cto1 = mysql_fetch_assoc($cto_q1);
	
	$cto_2 = mysql_query("select * from loan_payment_details WHERE customer_loanid = '".$ctd['id']."' order by month DESC ");   
    $cto2 = mysql_fetch_assoc($cto_2);
	
	$total1 += $cto1['balance'];
	$totalbaki += $cto2['balance'];
   ?>
    <tr>
    	<td><?php echo $i ?></td>
    	<td><?php echo $nt['name'] ?></td>
    	<td><?php echo $cto1['balance']?></td>
        <td><?php echo $cto2['balance']?></td>
        <td><?php echo $ctd['loan_period'] ?></td>
        <td><?php echo $ctd['apply_date'] ?></td>
        <td><?php echo $cd['mobile_contact'] ?></td>
        <td><?php echo $cto['int_percent'] ?></td>
        <td>&nbsp;</td>
        <td width="74">&nbsp;</td>
        <td width="71">&nbsp;</td>
        <td width="66"><?php echo $nt['race']?></td>
        <td width="100"><?php echo $ce_e['company']?></td>
        <td><?php echo $cf_f['net_salary']?></td><?php }  ?>
    </tr>
 
    <tr>
    	<td>&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center" style="font-size:14px"><b>Jumlah </b></td>
      <td><?php echo number_format($total1, '2');?></td>
      <td><?php echo number_format ($totalbaki, '2'); ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
   	</tr>
    <tr>
      <td colspan="15" align="right"><input type="button" name="back" id="back" onclick="window.location.href='../home/'" value="" /></td>
    </tr>
    <tr>
    	<td colspan="15"><table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td width="2%" height="44">&nbsp;</td>
            <td width="98%"><p><b>Majikan * : Kerajaan/ Swasta/ Berniaga/ keja Sendiri/ Tidak Bekerja </b></p>
            <p>&nbsp;</p></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><b>Nota#:</b></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><table width="25%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td width="19%">&nbsp;</td>
                <td width="81%"><b>1 - Pinjaman Selesai</b> </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><b>2 - Pinjaman Semasa</b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><b>3 - Dalam Proses Dapat Balik </b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><b>4 - Dalam Tindakan Mahkamah </b></td>
              </tr>
            </table></td>
          </tr>
        </table>    	<p>&nbsp;</p>
   	    </td>
    </tr>
</table>
</center>
<script>
$(document).ready(function() {   
   $("#customer_name").autocomplete("auto_custName.php", {
   		width: 170,
		matchContains: true,
		selectFirst: false
	});
	
	 $("#loan_code").autocomplete("auto_loanCode.php", {
   		width: 70,
		matchContains: true,
		selectFirst: false
	});
  
});

$('#month').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%Y-%m", labelTitle: "Select Month"}).focus(); } ).
keydown(
	function(e)
	{
		var key = e.keyCode || e.which;
		if ( ( key != 16 ) && ( key != 9 ) ) // shift, del, tab
		{
			$(this).off('keydown').AnyTime_picker().focus();
			e.preventDefault();
		}
	} );
</script>
