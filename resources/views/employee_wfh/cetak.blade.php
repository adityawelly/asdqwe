<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dinas Luar</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:#333;
            text-align: justify;
            font-size:16px;
			line-height:1.5;
            margin:0;
        }
		table {
		  border-collapse: collapse;
		}
    </style>
</head>
<body>
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
			<table width="100%">
			<tr> 
			<td width="20%"><h4 class="page-title" align="left">PT. NIRAMAS UTAMA</h4></td>
			<td width="65%"><h2 class="page-title" align="center" >SURAT IJIN DINAS LUAR </h2></td>
			<td width="15%"><img src="{{ ('img/logo_baru.png') }}" ></td>
			</tr>
			</table>       
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title" align="center" ></h4>
                        </div>				
                    </div>
					@foreach ($pengajuan as $pengajuan)
                    <div class="card-body">
                        <div class="row">
							<div class="col-md-6"> 
								<p>Dengan ini kami menerangkan bahwa karyawan tersebut sedang melaksanakan dinas luar yang ditugaskan 
								oleh perusahaan. Demikian surat dinas ini kami buat untuk dapat dipergunakan sebagaimana mestinya.</p>
								<table class="table-borderless" width="100%" >
											<tr>
												<td width="30%">Nama / NIK</td>
												<td width="2%">:</td>
												<td width="68%"><b>{{ $pengajuan->fullname }}</b>  /  <b>{{ $pengajuan->employee_no }}</b></td>
											</tr>
											<tr>
												<td>Jabatan / Bagian</td>
												<td>:</td>
												<td><b>{{ $pengajuan->level_title_name }}</b> / <b>{{ $pengajuan->job_title_name }}</b></td>											
											</tr>
											<tr>
												<td>Jam Keluar</td>
												<td>:</td>
												<td><b>{{ $pengajuan->start_time }}</b> Jam Masuk  <b>{{ $pengajuan->end_time }}</b></td>
											</tr>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td><b>{{ tgl_indo($pengajuan->start_date) }}</b>  s/d  <b>{{ tgl_indo($pengajuan->end_date) }}</b></td>
											</tr>
											<tr>
												<td>Tujuan</td>
												<td>:</td>
												<td><b>{{ $pengajuan->reason }}</b></td>
											</tr>		
											
								</table><br>                                
                            </div>                         
                        </div>
                    </div>					
                    <div class="card-footer">
					<table border='1' width="75%" align="right">
						<tr>
						<td width="25%" align='center'>Diajukan Oleh</td>
						<td width="25%" align='center'>Disetujui Oleh</td>
						<td width="25%" align='center'>Diketahui Oleh</td>
						<td width="25%" align='center'>Diterima Oleh</td>
						</tr>
						<tr>
						<td height="75"></td>
						<td height="75">
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>Approved By<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $pengajuan->nama_atasan }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $pengajuan->approval_date ? date('d-m-Y', strtotime($pengajuan->approval_date)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $pengajuan->approval_date ? date('H:i:s', strtotime($pengajuan->approval_date)):'-' }}</font></td></tr>
						</table>
						</td>
						<td height="75"></td>
						<td height="75"></td>
						</tr>
						<tr>
						<td width="25%" align='center'><b>Karyawan</b></td>
						<td width="25%" align='center'><b>Atasan</b></td>
						<td width="25%" align='center'><b>Personalia</b></td>
						<td width="25%" align='center'><b>Security</b></td>
						</tr>
					</table>  
                    </div>
					@endforeach
                </div>			  
			</div>
		</div>
    </div>
	</div>
</body>
</html>
    