<?php include('../include/page_header.php'); 

$sql=mysql_query("SELECT * FROM profile_kpkt");
$result_1 = mysql_fetch_assoc($sql);


?>
<style type="text/css">
<!--
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
#update
{
	background:url(../img/add-enquiry/submit-btn.jpg);
	width:109px;
	height:30px;
	border:none;
	cursor:pointer;
}
#update:hover
{
	background:url(../img/add-enquiry/submit-roll-over.jpg);
}
input
{
	height:30px;
}
-->
</style>

<center>
<table width="1280">
	<tr>
    	<td width="65"><img src="../img/report/report.png" style="height:47px"></td>
        <td>Reports</td>
        <td align="right">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="subnav">
			<a href="index.php" id="active-menu">KPKT</a>
			<a href="monthly.php">Monthly</a>
			<a href="instalment.php">Instalment</a>
			<?php
            if($_SESSION['login_level'] != 'Staff')
            {
        ?>
			<a href="statement_report.php">Statement</a>
						 <?php
            }
        ?>
		</div>	
        </td>
        <td align="right" style="padding-right:10px">&nbsp;</td>
    </tr>
</table>
<div id="message" style="width:1280px; text-align:left">
	<?php
    if($_SESSION['msg'] != '')
    {
        echo $_SESSION['msg'];
        $_SESSION['msg'] = '';
    }
    ?>						
</div>
<br />


<table width="1280" id="list_table">
		<tr>
    	<th>Report List</th>
        <th></th>
    </tr>
	<tr>
     	<td colspan="2">       
     		<form method="post">
            <table width="100%" border='0'>
                <tr>              
                    <td style="font-size: 16px;"><br>SELECT YEAR
               
<select 
         name="select"
         class="form-control"
         id="dropdownYear"
         style="width: 120px;height: 30px; font-size:16px;"
         onchange="getProjectReportFunc()">
</select>
                   <input class="btn btn-blue" type="submit" name="search" value="SEARCH" id="search" ></td> 


<!-- <a href="lampiran_b1.php" target="_blank">Lampiran B1</a> -->
                
 <td style="font-size: 16px;"><br>SELECT DATE
               
<input type="date" name="date_from" id="date_from"> TO <input type="date" name="date_to" id="date_to"> 
                   <input class="btn btn-blue" type="submit" name="searchdate" value="SEARCH" id="searchdate" ></td>
                </tr>               
             	<?php 
    	if(isset($_POST['search'])){

	     echo '<tr><td style="font-size:16px;">1. <a href="home.php?select='.$_POST['select'].'" target="_blank">Lampiran B</a></td></tr><br>';
	     echo '<tr><td style="font-size:16px;">2. <a href="lampiran.php?select='.$_POST['select'].'" target="_blank">Lampiran B1</a></td></tr><br>';
	     echo '<tr><td style="font-size:16px;">3. <a href="cover_letter.php?select='.$_POST['select'].'" target="_blank">Covering Letter</a></td></tr>';
	  }else if(isset($_POST['searchdate'])){
echo '<tr><td></td><td style="font-size:16px;">1. <a href="lampiranb.php?datefrom='.$_POST['date_from'].'&dateto='.$_POST['date_to'].'" target="_blank">Lampiran B (FROM '.$_POST['date_from'].' TO '.$_POST['date_to'].')</a></td></tr><br>';
	  }
    ?> 
            </table>
        </form>
        <br>
     	</td>
     </tr>
	<tr>
    	<th>Profile</th>
        <th></th>
    </tr>
    <tr><td colspan="2">
    	<form action="action.php" method="post" enctype="multipart/form-data"><table width="1280" id="list_table">
    <tr>
   	  <td style="padding:0px">Nama</td>
        <td><input type="text" name="nama" value="<?php echo $result_1['nama']; ?>" style="width:230px" /></td>
        <td style="padding:0px">No Telephone</td>
        <td><input type="text" name="no_telephone" value="<?php echo $result_1['no_telephone']; ?>" style="width:230px" /></td>
      <td style="padding:0px">Tarikh Mula</td>
      <td><input type="text" name="tarikh_mula" id="tarikh_mula" value="<?php echo $result_1['tarikh_mula']; ?>" style="width:230px" /></td>
    </tr>
    <tr>
   	  <td style="padding:0px">Postal Address</td>
        <td rowspan="3"><textarea name="postal_address" id="postal_address" style="width:230px; height:90px"><?php echo $result_1['postal_address']; ?>
			</textarea></td>
                <td style="padding:0px">Telephone Bimbit</td>
        <td><input type="text" name="telefon_bimbit" value="<?php echo $result_1['telefon_bimbit']; ?>" style="width:230px" /></td>
      <td style="padding:0px">Tarikh Tamat</td>
      <td><input type="text" name="tarikh_tamat" id="tarikh_tamat" value="<?php echo $result_1['tarikh_tamat']; ?>" style="width:230px" /></td>
    </tr>
        <tr>
   	  <td style="padding:0px"></td>

        <td style="padding:0px">Email</td>
      <td><input type="text" name="email" id="email" value="<?php echo $result_1['email']; ?>" style="width:230px"/></td>
      <td style="padding:0px">No KP</td>
      <td><input type="text" name="no_kp" id="no_kp" value="<?php echo $result_1['no_kp']; ?>" style="width:230px"/></td>
    </tr> 
        <tr>
   	  <td style="padding:0px"></td>

        <td style="padding:0px">Catatan</td>
      <td><input type="text" name="catatan" id="catatan" value="<?php echo $result_1['catatan']; ?>" style="width:230px"/></td>
      <td style="padding:0px">Alamat Rumah</td>
      <td rowspan="3"><textarea name="alamat_rumah" id="alamat_rumah" style="width:230px; height:90px"><?php echo $result_1['alamat_rumah']; ?>
			</textarea></td></td>
    </tr>     
    <tr>
   	  <td style="padding:0px">No.SSM</td>
        <td><input type="text" name="no_ssm" value="<?php echo $result_1['no_ssm']; ?>" style="width:230px" /></td>
        <td style="padding:0px">Jenis Organisasi</td>
      <td><input type="text" name="jenis_organisasi" id="jenis_organisasi" value="<?php echo $result_1['jenis_organisasi']; ?>" style="width:230px"/></td>
      <td style="padding:0px"></td>
      <td></td>
    </tr>
    <tr>
   	  <td style="padding:0px">Alamat Operasi</td>
       <td rowspan="3"><textarea name="alamat_operasi" id="alamat_operasi" style="width:230px; height:90px"><?php echo $result_1['alamat_operasi']; ?>
			</textarea></td>
        <td style="padding:0px">Nama Pengurus</td>
      <td><input type="text" name="nama_pengurus" id="nama_pengurus" value="<?php echo $result_1['nama_pengurus']; ?>" style="width:230px"/></td>
      <td style="padding:0px"></td>
      <td></td>
    </tr>
        <tr>
   	  <td style="padding:0px"></td>
     
        <td style="padding:0px">No Rujukan</td>
      <td><input type="text" name="no_rujukan" id="no_rujukan" value="<?php echo $result_1['no_rujukan']; ?>" style="width:230px"/></td>
      <td style="padding:0px">Favourite City</td>
      <td><input type="text" name="favourite_city" id="favourite_city" value="<?php echo $result_1['favourite_city']; ?>" style="width:230px"/></td>
    </tr>
        <tr>
   	  <td style="padding:0px"></td>
      
        <td style="padding:0px">No Lesen</td>
      <td><input type="text" name="no_lesen" id="no_lesen" value="<?php echo $result_1['no_lesen']; ?>" style="width:230px"/></td>
      <td style="padding:0px">Password</td>
      <td><input type="text" name="password" id="password" value="<?php echo $result_1['password']; ?>" style="width:230px"/></td>
    </tr>
       <tr>
   	  <td style="padding:0px">Poskod</td>
        <td><input type="text" name="poskod" value="<?php echo $result_1['poskod']; ?>" style="width:230px" /></td>
       <td style="padding:0px">No Permit</td>
      <td><input type="text" name="no_permit" id="no_permit" value="<?php echo $result_1['no_permit']; ?>" style="width:230px"/></td>
    </tr>
        <tr>
   	  <td style="padding:0px">Bandar</td>
        <td><input type="text" name="bandar" value="<?php echo $result_1['bandar']; ?>" style="width:230px" /></td>
       <td style="padding:0px">Tamat Tempoh</td>
      <td><input type="text" name="tamat_tempoh" id="tamat_tempoh" value="<?php echo $result_1['tamat_tempoh']; ?>" style="width:230px"/></td>

    </tr>
            <tr>
        	<td colspan="5"></td>
        	<td></td>
    </tr>
    <tr><td colspan="6" style="padding:0px"><br><b>***Covering Letter</b></td></tr>
        <tr>
      <td style="padding:0px">Tarikh</td>
      <td><input type="text" name="cl_tarikh1" id="cl_tarikh1" value="<?php echo $result_1['cl_tarikh1']; ?>" style="width:230px"/></td>
          </tr>
                  <tr>
        	<td colspan="5"></td>
        	<td></td>
    </tr>
      <tr>
      <td colspan="6" style="padding:0px">PER: PERATURAN-PERATURAN PEMBERI PINJAM WANG (KAWALAN DAN PELESENAN) 2003 – REKOD TRANSAKSI <input type="text" name="cl_year" id="cl_year" value="<?php echo $result_1['cl_year']; ?>"/></td>
      </tr>
              <tr>
        	<td colspan="5"></td>
        	<td></td>
    </tr>
       <tr>
      <td colspan="6" style="padding:0px">Bagi mematuhi keperluan peraturan dibawah Subperaturan 16 (1), Peraturan-Peraturan Pemberi Pinjam Wang (Kawalan Dan Pelesenan) 2003, saya kemukakan Rekod Transaksi Lesen Semasa (Lampiran ’B’ dan ’B1’ ) bagi tempoh 1 Januari <input type="text" name="cl_tarikh2" id="cl_tarikh2" value="<?php echo $result_1['cl_tarikh2']; ?>"/> hingga 31 Disember <input type="text" name="cl_tarikh3" id="cl_tarikh3" value="<?php echo $result_1['cl_tarikh3']; ?>"/>.</td>
   
      </tr>
        <tr>
        	<td colspan="5"></td>
        	<td></td>
    </tr>
    </tr>
        <tr>
        	<td colspan="5"></td>
        	<td><input type="submit" name="update" id="update" value=""></td>
    </tr>
</table>
</form></td></tr>
     
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><!-- <input type="button" name="back" id="back" onClick="window.location.href='../home/'" value=""> --></td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
</table>
</center>
<script>
	$('#tamat_tempoh').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#tarikh_mula').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#tarikh_tamat').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
	$('#cl_tarikh1').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#cl_tarikh2').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
$('#cl_tarikh3').click( function(e) {$(this).off('click').AnyTime_picker({ format: "%d-%m-%Y", earliest:(new Date((new Date()).valueOf() + 1000*3600*24)), labelTitle: "Select Date"}).focus(); } ).
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
<script>
function deleteConfirmation(name, id){
	$id = id;
	$.confirm({
		'title'		: 'Delete Confirmation',
		'message'	: 'Are you sure want to delete this user: ' + name + ' ?',
		'buttons'	: {
			'Yes'	: {
			'class'	: 'blue',
			'action': function(){
				$.ajax({
						type: 'POST',
						data: {
							action: 'delete_staff',
							id: $id,
						},
						url: 'action.php',
						success: function(){
							location.reload();
						}
					})
				}
			},
			'No'	: {
				'class'	: 'gray',
				'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
			}
		}
	});
}

function showEdit(no)
{
	if(document.getElementById('edit_' + no).style.visibility == 'hidden')
	{
		document.getElementById('edit_' + no).style.visibility = 'visible';	
	}else
	if(document.getElementById('edit_' + no).style.visibility == 'visible')
	{
		document.getElementById('edit_' + no).style.visibility = 'hidden';
	}
}

function updateAmount(no, id)
{
	$amount = $('#amount_' + no).val();
	$id = id;
	
	$.ajax({
		type: 'POST',
		data: {
			action: 'update_amount',
			id: $id,
			amount: $amount,
		},
		url: 'action.php',
			success: function(){
			location.reload();
		}
	})
}

// mini jQuery plugin that formats to two decimal places
(function($) {
	$.fn.currencyFormat = function() {
    	this.each( function( i ) {
        $(this).change( function( e ){
        	if( isNaN( parseFloat( this.value ) ) ) return;
        	this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
    }
})( jQuery );

// apply the currencyFormat behaviour to elements with 'currency' as their class
$( function() {
    $('.currency').currencyFormat();
});

</script>
<script type="text/javascript">
	let select = document.getElementById('dropdownYear');
let currYear = new Date().getFullYear();
let futureYear = currYear+3;
let pastYear = currYear-3;
for(let year = pastYear; year <= futureYear; year++){
  let option = document.createElement('option');
  option.setAttribute('value', year);
  if(year === currYear){
    option.setAttribute('selected', true);
  }
  option.innerHTML = year;
  select.appendChild(option);
}
</script>
</body>
</html>