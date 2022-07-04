<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SK PRINT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
		@font-face {
			font-family: Dotum;
			src: url('{{asset('/fonts/dotum/Dotum.ttf')}}');			
		}
		body{ 
			font-family: Sans-serif;
            color:#333;
            text-align:left;
        }
		table {
		  border-collapse: collapse;
		}
		div {
		  border-style: none;
		  border-center-style: solid;
		 
		}
		td { font-size: 14px }
		header {
                position: fixed; 
                bottom: 0cm; 
				top : -1cm;
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }

            footer {
                position: fixed; 
                bottom: 0cm; 
                left: -2cm; 
                right: 0cm;
                height: 0.9cm;
            }
			.satu {
			   padding : 0;
			   margin : 0;
			   font-size: 27px;
			   }
			.dua {
			   padding : 0;
			   margin : 0;
			   font-size: 19px;
			   }
			.nomargin {
                margin-bottom: 1cm; 
                margin-left: 1cm; 
                margin-right: 1cm;
                margin-top: 0.5cm;
            }
    </style>
</head>
<body>
<div class="content animated fadeIn">
    <div class="page-inner">
		<div class="header">
		<img src="{{  public_path('img/header.png')  }}"  alt=""  style="width: 710px; height: 80px;" >
		</div>
        <div class="row nomargin">			
			@foreach ($pengajuan as $xx)
			<p align="center" class="satu" ><u>SURAT KEPUTUSAN</u></p>
			<p align="center" class="dua" >NO: {{ $xx->sk_no }}</p><br>
			@endforeach
            <div class="col-md-12">
                <div class="card">				
                    <div class="card-body">
                        <div class="row">						
                            <div>
							<table width=100%> 
							<tr>
							<td valign="top"  width=18% >Menimbang</td>
							<td valign="top" width=3% >:</td>
							<td>Bahwa dalam rangka meningkatkan kinerja di Perusahaan maka dipandang perlu untuk melakukan 	
							@foreach ($perihal as $item)
							@if ($item->promosi == 1)<b>{{ "Promosi"  }}</b> @endif
							@if ($item->demosi == 1)<b>{{ "Demosi"  }}</b>@endif
							@if ($item->mutasi == 1)<b>{{ "Mutasi"  }}</b>@endif
							@if ($item->perubahan_job == 1)<b>{{ "Perubahan Job Title"  }}</b>@endif
							@if ($item->perubahan_status == 1)<b>{{ "Perubahan Status"  }}</b>@endif
							@endforeach
							</td>							
							</tr>
							<tr>
							<td valign="top">Mengingat</td>
							<td valign="top">:</td>
							@foreach ($pengajuan as $pengajuan)
							<td>Kebutuhan Divisi <b>{{ $pengajuan->dept_baru }}</b> yang memerlukan <b>{{ $pengajuan->jab_baru }}</b>.</td>							
							</tr>
							<tr>
							<td height="30" colspan="3" align="center"><b>MEMUTUSKAN</b></td>
							</tr>
							<tr>
							<td valign="top">Menetapkan</td>
							<td valign="top">:</td>
							<td>								
								<table width=70% >
									<tr>
									<td width=30%>Nama</td>
									<td width=3% >:</td>
									<td width=30%>{{ $pengajuan->fullname }}</td>
									</tr>
									<tr>
									<td>Nik</td>
									<td>:</td>
									<td>{{ $pengajuan->employee_id }}</td>
									</tr>
									<tr>
									<td>Divisi</td>
									<td>:</td>
									<td>{{ $pengajuan->department_name }}</td>
									</tr>
									<tr>
									<td>Jabatan</td>
									<td>:</td>
									<td>{{ $pengajuan->job_title_name }}</td>
									</tr>
									<tr>
									<td>Grade Title</td>
									<td>:</td>
									<td>{{ $pengajuan->level_title_name }}</td>
									</tr>
									<tr>
									<td>Lokasi</td>
									<td>:</td>
									<td>{{ $pengajuan->region_city }}</td>
									</tr>
									<tr>
									<td>Grade</td>
									<td>:</td>
									<td>{{ $pengajuan->Kelas_lama }}</td>
									</tr>
									<tr>
									<td>Level</td>
									<td>:</td>
									<td>{{ $pengajuan->Level_lama }}</td>
									</tr>
									 <tr>
									<td>Tanggal Masuk Kerja</td>
									<td>:</td>
									<td>{{ tgl_indo($pengajuan->date_of_work ) }}</td>
									</tr>
									</table>							
							</td>
							</tr>							
							<tr>
							<td height="40" colspan="3">Bahwa terhitung mulai tanggal <b>{{ tgl_indo($pengajuan->Eff_date) }}</b> telah ditetapkan dari <b>{{ $pengajuan->job_title_name }}</b>
							ke <b>{{ $pengajuan->jab_baru }}</b>.
							</td>									
							</tr>
							</table>
							<table width=100%>
							<tr>
							<td valign="top" rowspan="2" width=20%>Dengan Catatan</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=3% >1.</td>
							<td width=70%>Apabila dikemudian hari ternyata terdapat kekeliruan di dalam Surat Keputusan ini,akan diadakan perbaikan seperlunya.</td>
							</tr>
							<tr>
							<td></td>
							<td valign="top">2.</td>
							<td>Petikan Surat Keputusan ini diberikan kepada pihak yang berkepentingan untuk diketahui dan dimaklumi sebagaimana mestinya.</td>
							</tr>
							</table><br>
							<table width=50% cellspacing="0" cellpadding="0">
							<tr>
							<td width=30% >Ditetapkan</td>
							<td width=5% >:</td>
							<td width=50% >Di Bekasi</td>
							</tr>
							<tr>
							<td colspan="3"><hr style="width:100%" ></hr></td>
							</tr>
							<tr>
							<td>Pada Tanggal</td>
							<td>:</td>
							<td>{{ tgl_indo(date('Y-m-d')) }}</td>
							</tr>
							</table><br>
						@endforeach
                        </div>
						</div>
                    </div>					
                    <div class="card-footer">
                    <table width="50%">
						<tr>
						<td align="left" width="25%"><b>Dibuat Oleh</b></td>
						<td align="left" width="25%"><b>Diketahui Oleh</b></td>						
						</tr> 
						<tr>
						<td height="50"></td>
						<td height="50"></td>					
						</tr> 
						<tr>
						<td align="left"><b><u>Aldo Omar</u></b></td>
						<td align="left"><b><u>Adhi S. Lukman</u></b></td>
						</tr> 
						<tr>
						<td align="left">HC Sr. Manager</td>
						<td align="left">HC Director</td>
						</tr> 
					</table><br><br>  					
                    </div>
					<table>
					<tr>
					<td colspan="2" style="font-size: 8px;" >Tembusan</td>
					</tr>
					<tr>
					<td width="5" style="font-size: 9px;">1.</td>
					<td style="font-size: 9px;">............</td>
					</tr>
					<tr>
					<td style="font-size: 9px;">2.</td>
					<td style="font-size: 9px;">............</td>
					</tr>
					<tr>
					<td style="font-size: 9px;">3.</td>
					<td style="font-size: 9px;">............</td>
					</tr>
					<tr>
					<td style="font-size: 9px;">4.</td>
					<td style="font-size: 9px;">............</td>
					</tr>
					</table>
                </div>			  
			</div>
		</div>
    </div>
</div>
<footer>
<img src="{{  public_path('img/footer.png')  }}"  alt=""  style="width: 850px; height: 80px;" >
</footer>
</body>
</html>
    