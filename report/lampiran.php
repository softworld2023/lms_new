<?php
session_start();
include("../include/dbconnection.php");
require('../include/mpdf/mpdf.php');
	$year = $_GET['select'];
$date_from = $year.'-01-01';
$date_to = $year.'-12-31';

$mpdf = new mPDF('','A4-L','','',2,2,5,5,5,5,'P');
$header='<div style="text-align:right;font-size:11px;">
			<b>LAMPIRAN B1</b>
		</div>
		<div style="text-align:center;font-size:10px;padding-bottom:5px;">
			<b>PERATURAN-PERATURAN PEMBERI PINJAM WANG (KAWALAN DAN PELESENAN) 2003 <br> [Subperaturan 16(1) Akta 400] <br> REKOD TRANSAKSI TAHUNAN MENGIKUT PERINCIAN PINJAMAN BAGI TAHUN '.$year.'
		</div>
		<div style="font-size:12px; padding-bottom:5px;">
			<table width="90%" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td  style="text-align:left;"><b>NAMA SYARIKAT PPW : </b></td>
				<td style="text-align:center; border-bottom:1px solid;"><b>MJ MAJUSAMA SDN BHD</b></td>
				<td style="text-align:right;"><b>NO. LESEN :&nbsp;&nbsp;</b></td>
				<td style="text-align:left; border-bottom:1px solid;"><b>&nbsp;&nbsp;WL4283/07/01-8/121020</b></td>
			</tr>
			</table>
		</div>';

				$mpdf->WriteHTML($header);
$html='
		<div style="font-size:10px;">
			<table width="100%" align="center"  cellspacing="1" cellpadding="2" style="font-family:Time New Roman;font-size:10px;">
			<tr>
				<td rowspan="2" style = "text-align:center;border-top:1px solid;border-right:1px solid;border-left:1px solid;" width ="2%" >Bil</td>
				<td rowspan="2" width ="19%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Nama Peminjam</td>
				<td rowspan="2" width ="6%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Jumlah Pinjaman (Pokok) (RM) </td>
				<td rowspan="2" width ="6%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Jumlah Faedah yang dikenakan (Bunga) (RM)</td>
				<td rowspan="2" width ="6%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Baki Pinjaman (RM)</td>
				<td rowspan="2" width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Tempoh Pinjaman (Bulan)</td>
				<td rowspan="2" width ="6%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Tarikh Pinjaman</td>
				<td rowspan="2" width ="7%" style="text-align:center;border-top:1px solid;border-right:1px solid;">No. Telefon Peminjam</td>
				<td rowspan="2" width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Kadar Faedah Setahun (%)</td>
				<td rowspan="2" width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Bercagar / Tanpa Cagaran</td>
				<td rowspan="2" width ="5%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Jenis Harta Cagaran</td>
				<td colspan ="2" style="text-align:center;border-top:1px solid;border-right:1px solid;">Syarikat</td>
				<td colspan ="3" style="text-align:center;border-top:1px solid;border-right:1px solid;">Individu</td>
				<td rowspan="2" width ="3%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Nota #</td>
			</tr>
			<tr>
				<td style="text-align:center;border-top:1px solid;border-right:1px solid;">Bumi</td>
				<td style="text-align:center;border-top:1px solid;border-right:1px solid;">Bukan Bumi</td>
				<td style="text-align:center;border-top:1px solid;border-right:1px solid;">Kaum* </td>
				<td style="text-align:center;border-top:1px solid;border-right:1px solid;">Majikan**</td>
				<td width ="7%" style="text-align:center;border-top:1px solid;border-right:1px solid;">Pendapatan/ Gaji (RM)</td>
			</tr>';
						$sql_1 = mysql_query("SELECT
												customer_details.name,
													customer_loanpackage.loan_pokok,
													(customer_loanpackage.loan_total - customer_loanpackage.loan_pokok) AS loan_faedah,
													customer_address.mobile_contact,
													customer_details.race,
													customer_employment.c_workingtype,
													customer_financial.basic_salary,
													customer_loanpackage.loan_lejar_date,
													customer_loanpackage.id AS loanid,
													customer_loanpackage.loan_period,
													customer_details.blacklist
											FROM
												customer_details
												JOIN customer_loanpackage ON customer_loanpackage.customer_id = customer_details.id 
												JOIN customer_address ON customer_address.customer_id = customer_details.id
												JOIN customer_employment ON customer_employment.customer_id = customer_details.id
												JOIN customer_financial ON customer_financial.customer_id = customer_details.id
											WHERE
												customer_loanpackage.loan_lejar_date >= '".$date_from."' 
												AND customer_loanpackage.loan_code NOT LIKE 'MS%' 
												AND customer_loanpackage.loan_lejar_date <='".$date_to."' ORDER BY loan_lejar_date ASC");
						$ctr=0;
						while($result_1 = mysql_fetch_assoc($sql_1))
						{ 
							$ctr++;
							$race_1 = $result_1['race'];

							if($race_1 == 'Malay')
							{
								$race = 'M';
								
							}elseif ($race_1 == 'Chinese')
							{
								$race = 'C';
								
							}elseif ($race_1 == 'Indian') {
								$race = 'I';
								
							}

							$c_workingtype = $result_1['c_workingtype'];

							if($c_workingtype == 'PRIVATE')
							{
								$majikan = 'SWASTA';
							}elseif($c_workingtype == 'GOVERNMENT')
							{
								$majikan = 'KERAJAAN';
							}elseif($c_workingtype == 'SELF EMPLOYED')
							{
								$majikan = 'KERJA SENDIRI';
							}
							elseif($c_workingtype == 'RETIRED')
							{
								$majikan = 'TIDAK BERKERJA';
							}elseif($c_workingtype == 'OTHERS')
							{
								$majikan = 'KERAJAAN';
							}
							$sql2 = mysql_query("SELECT * FROM loan_lejar_details WHERE customer_loanid = '".$result_1['loanid']."' and payment ='0' ORDER BY id DESC LIMIT 1");
							$result_2 = mysql_fetch_assoc($sql2);
							$baki_pinjaman = $result_2['balance'];

							if($baki_pinjaman == '' || $baki_pinjaman == ' ')
							{
								$baki_pinjaman = '0.00';
								$nota = '1';
							}else
							{
								$nota = '2';
							}
							

							if($result_2['bad_debt']=='yes')
							{
								$nota = '3';
							}



							$total_loan_pokok+=$result_1['loan_pokok']; 
							$total_loan_faedah+=$result_1['loan_faedah'];
							$total_baki_pinjaman+=$baki_pinjaman;




	$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;">'.$ctr.'</td>
				<td style="text-align:left;border-top: 1px solid; border-right: 1px solid;">'.$result_1['name'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$result_1['loan_pokok'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$result_1['loan_faedah'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$baki_pinjaman.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$result_1['loan_period'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$result_1['loan_lejar_date'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$result_1['mobile_contact'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">18</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">TANPA</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">NA</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$race.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$majikan.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$result_1['basic_salary'].'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;">'.$nota.'</td>
				</tr>';}

				for ($i=$ctr+1; $i <=24; $i++) {

					$html.='<tr>
				<td style="text-align:center; border-left: 1px solid; border-right:1px solid;border-top: 1px solid;">'.$i.'</td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				<td style="text-align:center;border-top: 1px solid; border-right: 1px solid;"></td>
				</tr>';}
$html.='<tr>
				<td colspan = "2" style="text-align:center; border-left: 1px solid; border-right:1px solid;border-bottom: 1px solid;border-top: 1px solid;">JUMLAH KESELURUHAN</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;">'.number_format($total_loan_pokok,'2').'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;">'.number_format($total_loan_faedah,'2').'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;">'.number_format($total_baki_pinjaman,'2').'</td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				<td style="text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid;"></td>
				</tr>
			</table>
		</div>';
				
				$footer = '
		<div>
			<table width="100%" align="left"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:10px;">
				<tr>
					<td width = "21%">Kaum*:</td>
					<td>M - Melayu &nbsp;&nbsp;&nbsp; C - Cina &nbsp;&nbsp;&nbsp; I - India &nbsp;&nbsp;&nbsp; B - Bumiputera(Sabah/Sarawak) &nbsp;&nbsp;&nbsp; L - Lain-lain &nbsp;&nbsp;&nbsp; X - Bukan Warganegara </td>
				</tr>
				<tr>
					<td>Majikan**:</td>
					<td>Kerajaan / Swasta / Berniage / Kerja Sendiri / Tidak Bekerja / Pelajar</td>
				</tr>
				<tr>
					<td>Nota#:</td>
					<td>1. Pinjaman Selesai &nbsp;&nbsp;&nbsp;  2. Pinjaman Semasa  &nbsp;&nbsp;&nbsp;  3. Dalam Proses Dapat Balik  &nbsp;&nbsp;&nbsp;  4. Dalam Tindakan Mahkamah</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
				<td colspan="2"><p><span><b>Saya dengan ini mengaku bahawa butir-butir di atas adalah benar. Saya faham bahawa apa-apa kenyataan yang mengelirukan, atau perihal palsu butir-butir di atas adalah suatu kesalahan di bawah Peraturan-Peraturan Pemberi Pinjam Wang (Kawalan dan Pelesenan 2003) serta menurut Akta Akuan Berkanun 1960.</b></span></p></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
			</table>
		</div>
		<div>
			<table width="50%" align="left"  cellspacing="0" cellpadding="1" style="font-family:Time New Roman;font-size:10px;">
				<tr>
					<td width="23%">Tandatangan</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;">x</td>
				</tr>
				<tr>
					<td width="23%">Nama</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;">TIEW BOK KIAK</td>
				</tr>
				<tr>
					<td width="23%">Jawatan</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;">Pengarah</td>
				</tr>
				<tr>
					<td width="23%">Tarikh</td>
					<td width="2%">:</td>
					<td style="border-bottom:1px solid;"></td>
				</tr>
			</table>
		</div>';

$mpdf->WriteHTML($html);
$mpdf->WriteHTML($footer);
$mpdf->Output();

?>