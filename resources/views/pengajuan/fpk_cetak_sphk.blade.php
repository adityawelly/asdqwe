<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPHK PRINT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        html {
			margin: 20;
		}
		body{ 
			font-family: Arial, Helvetica, sans-serif;
			text-align: justify;
			margin: 7px;
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
		td { font-size: 16px; font-family: Arial, Helvetica, sans-serif; text-align:justify;  padding:3px}
	
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
			   font-size: 26px;
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
                margin-top: 0.5cm;
            }
    </style>
</head>
<body>
<div class="content animated fadeIn">
    <div class="page-inner">
       
		<div class="header">
		<img src="{{  public_path('img/header2.png')  }}"  alt=""  style="width: 710px; height: 80px;">
		</div>
		
        <div class="row nomargin">			
			<p align="center" class="satu" ><b><u>SURAT PEMBERITAHUAN</u></b></p>
			@foreach ($isi_sk as $xx)
			<p align="center" class="dua" >NO: {{ $xx->sphk_no }}</p><br><br>
			@endforeach
            <div class="col-md-12">
                <div class="card">				
                    <div class="card-body">
                        <div class="row">						
                            <div>
							<table width=100%> 
							<tr>
							<td valign="top"  colspan="3">Sesuai dengan informasi yang disampaikan oleh departement HCM maka diberitahukan kepada karyawan/ti yang Habis Kontrak (HK) pada Agustus 2021, yaitu :</td>					
							</tr>
							</table>	
							
							<table width=100% border="1" class="table-bordered"> 
							<tr>
							<td valign="top" width="5%">No</td>
							<td valign="top" width="12%">NIK</td>
							<td valign="top" width="25%">PKWT Akhir</td>
							<td valign="top" width="25%">Nama Karyawan</td>
							<td valign="top" width="25%">Jabatan</td>	
							<td valign="top" width="15%">Keterangan</td>							
							</tr>
							<tr>
							<td valign="top">1</td>
							<td valign="top">{{ $xx->registration_number }}</td>
							<td valign="top">{{ tgl_indo($xx->sdate) }}</td>
							<td valign="top">{{ $xx->fullname }}</td>
							<td valign="top">{{ $xx->job_title_name }}</td>	
							<td valign="top">HK</td>							
							</tr>
							</table>

							<table width=100%> 
							<tr>
							<td valign="top"  colspan="3">Untuk karyawan/ti dimohon mengembalikan Seragam & ID Card ke personalia. Demikian surat ini kami buat, atas perhatiannya kami ucapkan terima kasih.</td>					
							</tr>
							</table>
							
					<table width=100%> 
						<tr>
							<td valign="top" width="20%">Dikeluarkan di</td>		
							<td valign="top" width="3%">:</td>	
							<td valign="top" align="left" >Bekasi</td>							
						</tr>
						<tr>
							<td valign="top">Pada Tanggal</td>		
							<td valign="top">:</td>	
							<td valign="top" align="left">{{ tgl_indonesia($xx->created_at) }}</td>							
						</tr>
					</table>		
													
                    <div class="card-footer">
                    <table width="75%">
						<tr>
						<td align="left" width="25%">Hormat Kami,</td>
						<td align="left" width="25%"></td>
						<td align="left" width="35%"></td>						
						</tr> 
						<tr>
						<td height="50"></td>
						<td height="50"></td>
						<td height="50"></td>					
						</tr> 
						<tr>
						<td align="left"><b><u>Indriani</u></b></td>
						<td align="left"></td>
						<td align="left"><b></b></td>
						</tr> 
						<tr>
						<td align="left">IR & GA Asst. Manager</td>
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
    