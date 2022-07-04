<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PKWT PRINT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
       body{ 
			font-family: "Times New Roman", Times, serif;
			text-align: justify;
			margin: 25mm 20mm 25mm 20mm !important;
        }
		table{
		 
		  padding: 6px;
		  border-collapse: collapse;
		  table-layout: fixed;
		  width: 100%;		  
		}
		div {
		  border-style: none;
		  border-center-style: solid;
		 
		}
		td { font-size: 14px; text-align:justify;  padding:3px}
	
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: -1cm; 
                right: 0cm;
                height: 0.9cm;
            }
			.satu {
			   padding : 0;
			   margin : 0;
			   font-size: 20px;
			   }
			.bold-label {
				 text-align: center;
				 font-weight: bold;
			}
			.dua {
			   padding : 0;
			   margin : 0;
			   font-size: 16px;
			   }
			.nomargin {
                margin-bottom: 1cm; 
                margin-left: 1cm; 
                margin-right: 1cm;
                margin-top: 2cm;
    </style>
</head>
<body>
<div class="content animated fadeIn">
    <div class="page-inner">
       <!--
		<div class="header">
		<img src="{{  public_path('img/header2.png')  }}"  alt=""  style="width: 710px; height: 80px;">
		</div>
		-->
        <div class="row nomargin">			
			<p align="center" class="satu" ><i>PERJANJIAN KERJA WAKTU TERTENTU</i></p>
			@foreach ($isi_sk as $xx)
			<p align="center" class="dua" >NO: {{ $xx->pkwt_no }}</p><br><br>
			@endforeach
            <div class="col-md-12">
                <div class="card">				
                    <div class="card-body">
                        <div class="row">						
                            <div>
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="3">Perjanjian Kerja Waktu Tertentu (selanjutnya akan disebut “Perjanjian”) ini dibuat dan ditandatangani di Bekasi, pada tanggal {{ tgl_indonesia($xx->created_at) }}, oleh dan antara:</td>					
							</tr>
							<tr>
							<td valign="top" width=18% >Nama</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >Aldo Omar</td>							
							</tr>
							<tr>
							<td valign="top" width=18% >Jabatan</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >Human Capital Sr. Manager</td>							
							</tr>
							<tr>
							<td valign="top" width=18% >Alamat</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >Jl. Raya Bekasi Tambun KM. 39, 5 Bekasi, Jawa Barat 17510</td>							
							</tr>
							<tr>
							<td valign="top"  colspan="3">Dalam hal ini bertindak untuk dan atas nama serta mewakili PT. NIRAMAS UTAMA, untuk selanjutnya akan disebut PIHAK PERTAMA.</td>					
							</tr>
							<tr>
							<td valign="top" width=18% >Nama</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >{{ $xx->fullname }}</td>							
							</tr>
							<tr>
							<td valign="top" width=18% >No. KTP</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >{{ $xx->ID_number }}</td>							
							</tr>
							<tr>
							<td valign="top" width=18% >Jenis Kelamin</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >{{ $xx->sex }}</td>							
							</tr>
							<tr>
							<td valign="top" width=18% >Alamat</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=69% >{{ $xx->address }}</td>							
							</tr>
							<tr>
							<td valign="top"  colspan="3">Dalam hal ini bertindak untuk dan atas nama dirinya sendiri, untuk selanjutnya akan disebut PIHAK KEDUA.</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="3">PARA PIHAK dalam Perjanjian ini terlebih dahulu menerangkan hal-hal sebagai berikut:</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="3">Bahwa PIHAK PERTAMA adalah Perseroan Terbatas yang bergerak di bidang Manufacturing yang berkantor pusat di Jl. Raya Bekasi Tambun KM. 39, 5, 
							Bekasi Jawa Barat bermaksud dan berkehendak untuk mempekerjakan PIHAK KEDUA pada perusahaan PIHAK PERTAMA untuk jangka waktu tertentu.</td>
							</tr>
							<tr>
							<td valign="top"  colspan="3">Bahwa PIHAK KEDUA sepakat dan tidak berkeberatan untuk menerima maksud dan kehendak PIHAK PERTAMA untuk 
							mempekerjakan PIHAK KEDUA pada perusahaan PIHAK PERTAMA untuk jangka waktu tertentu.</td>	
							</tr>
							<tr>
							<td valign="top"  colspan="3">Berdasarkan hal-hal sebagaimana diuraikan diatas, maka PARA PIHAK sepakat dan tidak berkeberatan untuk 
							membuat dan menandatangani Perjanjian ini dengan syarat dan ketentuan-ketentuan sebagai berikut:</td>	
							</tr>
							</table><br>
							
                            <table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 1</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">PENGANGKATAN</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2">
							Bahwa PIHAK PERTAMA dengan ini menerima PIHAK KEDUA sebagai karyawan untuk posisi <b>{{ $xx->job_title_name }}</b> di PT. Niramas Utama, 
							penempatan Bekasi untuk jangka waktu tertentu.
							</td>								
							</tr>
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 2</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">TUGAS DAN TANGGUNG JAWAB</td>					
							</tr>							
							@foreach($pasal2 as $pasal2)
							<tr>
							<td valign="top" width="3%" >{{ $pasal2->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal2->isi_draft !!}</td>
							</tr>
							@endforeach
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 3</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">PERATURAN PERUSAHAAN</td>					
							</tr>							
							@foreach($pasal3 as $pasal3)
							<tr>
							<td valign="top" width="3%" >{{ $pasal3->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal3->isi_draft !!}</td>
							</tr>
							@endforeach					
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 4</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">WAKTU KERJA DAN LIBUR KERJA</td>					
							</tr>							
							@foreach($pasal4 as $pasal4)
							<tr>
							<td valign="top" width="3%" >{{ $pasal4->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal4->isi_draft !!}</td>
							</tr>
							@endforeach	
							@if($xx->hk_id == 5)
							<tr>								
							<td valign="top" width="3%"></td>
							<td valign="top" width="60%">
							<table class="table-nopad">
							<tbody>
							<tr>
							<td valign="top"  width="25%">Waktu kerja</td>
							<td valign="top"  width="3%">:</td>
							<td>Hari Senin s/d Jumat</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Libur kerja</td>
							<td valign="top"  width="3%">:</td>
							<td>Hari Sabtu, Minggu & Hari Besar Nasional</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Senin s/d Jumat</td>
							<td valign="top"  width="3%">:</td>
							<td>Jam 08.00 s/d 17.00 WIB</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Jam istirahat kerja</td>
							<td valign="top"  width="3%">:</td>
							<td>Jam 12.00 s/d 12.55 WIB</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Istirahat Hari Jumat</td>
							<td valign="top"  width="3%">:</td>
							<td>Jam 11.45 s/d 13.15 WIB</td>
							</tr>
							</tbody>
							</table>
						    </td>
							</tr>
							@else
							<tr>
						    <td valign="top" width="30%"></td>
							<td valign="top" width="60%">
							<table>
							<tbody>
							<tr>
							<td valign="top"  width="25%">Waktu kerja</td>
							<td valign="top"  width="3%">:</td>
							<td>Hari Senin s/d Sabtu</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Libur kerja</td>
							<td valign="top"  width="3%">:</td>
							<td>Hari Minggu & Hari Besar Nasional</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Senin s/d Sabtu</td>
							<td valign="top"  width="3%">:</td>
							<td>Jam 08.00 s/d 16.00 WIB</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Jam istirahat kerja</td>
							<td valign="top"  width="3%">:</td>
							<td>Jam 12.00 s/d 12.55 WIB</td>
							</tr>
							<tr>
							<td valign="top"  width="25%">Istirahat Hari Jumat</td>
							<td valign="top"  width="3%">:</td>
							<td>Jam 11.45 s/d 13.15 WIB</td>
							</tr>
							</tbody>
							</table>
						    </td>
							</tr>
							@endif
							@foreach($pasal41 as $pasal41)
							<tr>
							<td valign="top" width="3%" >{{ $pasal41->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal41->isi_draft !!}</td>
							</tr>
							@endforeach
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 5</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">GAJI, TUNJANGAN DAN FASILITAS</td>					
							</tr>							
							@foreach($pasal5 as $pasal5)
							<tr>
							<td valign="top" width="3%" >{{ $pasal5->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal5->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 6</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">CUTI</td>					
							</tr>							
							@foreach($pasal6 as $pasal6)
							<tr>
							<td valign="top" width="3%" >{{ $pasal6->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal6->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 7</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">PENILAIAN KINERJA KARYAWAN</td>					
							</tr>							
							@foreach($pasal7 as $pasal7)
							<tr>
							<td valign="top" width="3%" >{{ $pasal7->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal7->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 8</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">PEMUTUSAN HUBUNGAN KERJA</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2">PIHAK KEDUA memahami dan menyetujui bahwa Perjanjian ini dapat berakhir dengan ketentuan:</td>					
							</tr>						
							@foreach($pasal8 as $pasal8)
							<tr>
							<td valign="top" width="3%" >{{ $pasal8->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal8->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 9</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">JANGKA WAKTU DAN BERAKHIRNYA PERJANJIAN KERJA WAKTU TERTENTU</td>					
							</tr>
							<tr>
							<td valign="top" width="3%" >1)</td>
							<td valign="top" width="97%" >Bahwa jangka waktu Perjanjian ini berlaku selama {{ $xx->note_kontrak }} bulan, terhitung mulai:</td>
							</tr>
							<tr>
							<td valign="top" width="3%" >2)</td>
							<td valign="top" width="97%" >Tanggal <b>{{ tgl_indo($xx->sdate) }}</b> dan berakhir pada Tanggal <b>{{ tgl_indo($xx->edate) }}</b>.</td>
							</tr>								
							@foreach($pasal9 as $pasal9)
							<tr>
							<td valign="top" width="3%" >{{ $pasal8->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal8->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 10</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">PERPANJANGAN DAN PEMBAHARUAN PERJANJIAN KERJA WAKTU TERTENTU</td>					
							</tr>						
							@foreach($pasal10 as $pasal10)
							<tr>
							<td valign="top" width="3%" >{{ $pasal10->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal10->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 11</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">TRAINING dan IKATAN DINAS</td>					
							</tr>							
							@foreach($pasal11 as $pasal11)
							<tr>
							<td valign="top" width="3%" >{{ $pasal11->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal11->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 12</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">SERAH TERIMA</td>					
							</tr>							
							@foreach($pasal12 as $pasal12)
							<tr>
							<td valign="top" width="3%" >{{ $pasal12->urut.')' }}</td>
							<td valign="top" width="97%" >{!! $pasal12->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
							
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="2" class="bold-label">Pasal 13</td>					
							</tr>
							<tr>
							<td valign="top"  colspan="2" class="bold-label">PENUTUP</td>					
							</tr>							
							@foreach($pasal13 as $pasal13)
							<tr>
							<td valign="top" colspan="2">{!! $pasal13->isi_draft !!}</td>
							</tr>
							@endforeach			
							</table>
                        </div>
						</div>
                    </div>

                    <div class="card-footer">
                    <table width="75%">
						<tr>
						<td align="left" width="25%">PIHAK PERTAMA</td>
						<td align="left" width="25%"></td>
						<td align="left" width="35%">PIHAK KEDUA</td>						
						</tr> 
						<tr>
						<td height="50"></td>
						<td height="50"></td>
						<td height="50"></td>					
						</tr> 
						<tr>
						<td align="left"><b><u>Aldo Omar</u></b></td>
						<td align="left"></td>
						<td align="left"><b>{{ $xx->fullname }}</b></td>
						</tr> 
						<tr>
						<td align="left">Human Capital Sr. Manager</td>
						<td align="left"></td>
						<td align="left"></td>
						</tr> 
					</table><br><br>  					
                    </div>
				
                </div>			  
			</div>
		</div>
    </div>
</div>
<!--
<footer>
<img src="{{  public_path('img/footer.png')  }}"  alt=""  style="width: 850px; height: 80px;" >
</footer>
-->
</body>
</html>
    