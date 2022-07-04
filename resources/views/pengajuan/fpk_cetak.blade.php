<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FPK PRINT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:#333;
            text-align:left;
            font-size:14px;
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
            <h2 class="page-title" align="center" >FORM PEMBAHARUAN KARYAWAN</h2>
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
                            <div>
                                <table width=100% frame="box">
								<tr><td width=50%>
								<table class="table borderless">
								<tr>
								<th align='left'>Kepada</th>
								<th width=1%>:</th>
								<th align='left'>Adhi S Lukman</th>
								</tr>
								<tr>
								<th align='left'>Tembusan</th>
								<th width=1%>:</th>
								<th align='left'>HCM/OD</th>
								</tr>
								<tr>
								<th align='left'>NIK</th>
								<th width=1%>:</th>
								<th align='left'>{{ $pengajuan->employee_id }}</th>
								</tr>
								<tr>
								<th align='left'>Nama Lengkap</th>
								<th width=1%>:</th>
								<th align='left'>{{ $pengajuan->fullname }}</th>
								</tr>
								<tr>
								<th align='left'>Tgl. Lahir</th>
								<th width=1%>:</th>
								<th align='left'>{{ date('d-F-Y', strtotime($pengajuan->date_of_birth)) }}</th>
								</tr>
								<tr>
								<th align='left'>Tgl. Masuk</th>
								<th width=1%>:</th>
								<th align='left'>{{ date('d-F-Y', strtotime($pengajuan->date_of_work ))}}</th>
								</tr>
								</table>
								</td><td width=50%>
								<table class="table borderless">
								<tr>
								<th align='left'>No. Dokumen</th>
								<th width=1%>:</th>
								<th align='left'>{{ $pengajuan->fpk_no ?? '-' }}</th>
								</tr>
								<tr>
								<th align='left'></th>
								<th width=1% style="color:white">:</th>
								<th></th>
								</tr>
								<tr>
								<th align='left'>Homebase</th>
								<th width=1%>:</th>
								<th align='left'>{{ $pengajuan->Lokasi_lama }}</th>
								</tr>
								<tr>
								<th align='left'>Agama</th>
								<th width=1%>:</th>
								<th align='left'>{{ $pengajuan->religion }}</th>
								</tr>
								<tr>
								<th align='left'>Pendidikan</th>
								<th width=1%>:</th>
								<th align='left'>{{ $pengajuan->last_education .'-'. $pengajuan->education_focus }}</th>
								</tr>
								<tr>
								<th align='left'></th>
								<th width=1% style="color:white">:</th>
								<th></th>
								</tr>
								</table>
								</td></tr> 
								</table><br>
                            </div>                           
                            <div class="col-md-6">
								<table  border="1" frame="box" width=100%>
								<tr>
								<td colspan=3 align='center'><b>HAL :</b></td>
								</tr> 
								<tr>
								<td width="33%"><table width=100%><tr><td width="7%"><input type="checkbox" id="hal1" name="promosi" value="1"{{ $pengajuan->promosi == '1' ? 'checked' : '' }} /></td><td>Promosi</td></tr></table></td>
								<td width="33%"><table width=100%><tr><td width="7%"><input type="checkbox" id="hal2" name="perubahan_job" value="1"{{ $pengajuan->perubahan_job == '1' ? 'checked' : '' }} /></td><td>Perubahan Job Title</td></tr></table></td>
								<td width="33%"><table width=100%><tr><td width="7%"><input type="checkbox" id="hal3" name="penyesuaian_comben" value="1" {{ $pengajuan->penyesuaian_comben == '1' ? 'checked' : '' }} /></td><td>Penyesuaian Comben</td></tr></table></td>
								</tr> 
								<tr>
								<td><table width=100%><tr><td width="7%"><input type="checkbox" id="hal8" name="perubahan_status" value="1" {{ $pengajuan->perubahan_status == '1' ? 'checked' : '' }} /></td><td>Perubahan Status</td></tr></table></td>
								<td><table width=100%><tr><td width="7%"><input type="checkbox" id="hal4" name="demosi" value="1"{{ $pengajuan->demosi == '1' ? 'checked' : '' }} /></td><td>Demosi</td></tr></table></td>
								<td><table width=100%><tr><td width="7%"><input type="checkbox" id="hal5" name="perpanjangan_kontrak" value="1" {{ $pengajuan->perpanjangan_kontrak == '1' ? 'checked' : '' }} /></td><td>Perpanjangan Kontrak Kerja</td></tr></table></td>
								</tr> 
								<tr>
								<td><table width=100%><tr><td width="7%"><input type="checkbox" id="hal6" name="habis_kontrak" value="1" {{ $pengajuan->habis_kontrak == '1' ? 'checked' : '' }} /></td><td>Habis Kontrak</td></tr></table></td>
								<td><table width=100%><tr><td width="7%"><input type="checkbox" id="hal7" name="mutasi" value="1" {{ $pengajuan->mutasi == '1' ? 'checked' : '' }} /></td><td>Mutasi</td></tr></table></td>
								<td></td>
								</tr> 					
								</table><br>
                            </div>
							<div class="col-md-6">          
								<table border="1" width="100%" >
											<tr>
												<td align="center" width="33%"><b>PERUBAHAN</b></td>
												<td align="center" width="33%"><b>LAMA</b></td>
												<td align="center" width="33%"><b>BARU</b></td>
											</tr>
											<tr>
												<td>Departement / Divisi</td>
												<td>{{ $pengajuan->department_name }}</td>
												<td>{{ $pengajuan->dept_baru }}</td>
											</tr>
											<tr>
												<td>Jabatan</td>
												<td>{{ $pengajuan->job_title_name }}</td>
												<td>{{ $pengajuan->jab_baru }}</td>
											</tr>
											<tr>
												<td>Kelas / Golongan</td>
												<td>{{ $pengajuan->Kelas_lama }}</td>
												<td>{{ $pengajuan->Kelas_baru }}</td>
											</tr>
											<tr>
												<td>Grade / Level</td>
												<td>{{ $pengajuan->Level_lama ?? '-' }}</td>
												<td>{{ $pengajuan->Level ?? '-' }}</td>
											</tr>											
											<tr>
												<td>Lokasi Kerja</td>
												<td>{{ $pengajuan->Lokasi_lama }}</td>
												<td>{{ $pengajuan->region_city }}</td>
											</tr>
											<tr>
												<td>Atasan</td>
												<td>{{ $pengajuan->nama_creator }}</td>
												<td>{{ $pengajuan->nama_atasan_baru ?? '-'}}</td>
											</tr>
											<tr>
												<td>Status Karyawan</td>
												<td>{{ $pengajuan->Status_lama }}</td>
												<td>{{ $pengajuan->Status_baru ?? '-'}}</td>
											</tr>
											<tr>
												<td>Gaji Pokok</td>
												<td>{{ number_format($pengajuan->Gapok_lama, 2) ?? '-' }}</td>
												<td>{{ number_format($pengajuan->Gapok_baru, 2) ?? '-' }}</td>
											</tr>
										<!--
											<tr>
												<td>Tunjangan Transport</td>
												<td>{{ $pengajuan->Tuport_lama ?? '-'}}</td>
												<td>{{ $pengajuan->Tuport_baru ?? '-'}}</td>
											</tr>
										-->
											<tr>
												<td>Tunjangan Makan</td>
												<td>{{ $pengajuan->Tukan_lama }}</td>
												<td>{{ $pengajuan->Tukan_baru ?? '-' }}</td>
											</tr>
											<tr>
												<td height="50">Fasilitas Kerja</td>
												<td colspan=2> 
												@foreach ($facilities as $item)
													{{ $loop->iteration }}. {{ $item->Description }} <br>
												@endforeach
												</td>												
											</tr>
											<tr>
												<td><b>Tanggal Efektif</b></td>
												<td colspan=2 >{{ $pengajuan->Eff_date ? date('d-m-Y', strtotime($pengajuan->Eff_date)):'-' }}</td>												
											</tr>
											<tr>
												<td colspan=2><u>Berikan alasan / pertimbangan terhadap usulan perubahan :</u><br><br>{{ $pengajuan->Notes }}</td>
												<td>Diterima<br><br>Recruitment Section</td>												
											</tr>
											<tr>
												<td colspan=3><u>Note</u><br>Apabila pengajuan status karyawan ditolak, silahkan isi masa kontrak berikutnya: ... Bulan</td>										
											</tr>
							</table><br>                                  
                            </div>                         
                        </div>
                    </div>
					@endforeach
                    <div class="card-footer">
                   <table border='1' width="100%">
						<tr>
						<td align="center" width="33%"><b>Diusulkan</b></td>
						<td align="center" width="33%"><b>Disetujui</b></td>
						<td align="center" width="33%"><b>Diketahui</b></td>
						</tr> 
						<tr>
						<td height="50">
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>Created By<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $pengajuan->nama_creator }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $pengajuan->Insert_date ? date('d-m-Y', strtotime($pengajuan->Insert_date)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $pengajuan->Insert_date ? date('H:i:s', strtotime($pengajuan->Insert_date)):'-' }}</font></td></tr>
						</table>
						</td>
						<td height="50">
						@foreach ($mgr_lsng as $mgr_lsng)
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_lsng->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_lsng->fullname }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_lsng->ApprovalDate ? date('d-m-Y', strtotime($mgr_lsng->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_lsng->ApprovalDate ? date('H:i:s', strtotime($mgr_lsng->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						</td>
						<td height="50">
						@foreach ($mgr_hc as $mgr_hc)
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_hc->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_hc->fullname }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_hc->ApprovalDate ? date('d-m-Y', strtotime($mgr_hc->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $mgr_hc->ApprovalDate ? date('H:i:s', strtotime($mgr_hc->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						</td>
						</tr> 
						<tr>
						<td align="center"><i>Atasan Langsung</i></td>
						<td align="center">
					    @foreach ($mgr_jab as $xx)    
						<i>{{ $xx->level_title_name }}</i>
						@endforeach
						</td>
						<td align="center"><i>Manager HC</i></td>
						</tr> 
						<tr>
						<td height="50">
						@foreach ($ats_td_lsng as $ats_td_lsng)
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>{{ $ats_td_lsng->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $ats_td_lsng->fullname }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $ats_td_lsng->ApprovalDate ? date('d-m-Y', strtotime($ats_td_lsng->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $ats_td_lsng->ApprovalDate ? date('H:i:s', strtotime($ats_td_lsng->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						</td>
						<td height="50">
						@if(!empty($dir_lsng))
						@foreach ($dir_lsng as $dir_lsng)
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_lsng->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_lsng->fullname   }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_lsng->ApprovalDate ? date('d-m-Y', strtotime($dir_lsng->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_lsng->ApprovalDate ? date('H:i:s', strtotime($dir_lsng->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						@endif
						</td>
						<td height="50">
						@foreach ($dir_hc as $dir_hc)
						@if ($dir_hc->ApprovalSts == 1)
						<table width=100%>
						<tr><td align=center><font size='10px' color=blue><b>{{ 'Approved By'}}<b></font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_hc->fullname }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_hc->ApprovalDate ? date('d-m-Y', strtotime($dir_hc->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=blue><b>{{ $dir_hc->ApprovalDate ? date('H:i:s', strtotime($dir_hc->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@else
						<table width=100%>
						<tr><td align=center><font size='10px' color=red><b>{{ 'Disapproved By'}}<b></font></td></tr>
						<tr><td align=center><font size='10px' color=red><b>{{ $dir_hc->fullname }}</font></td></tr>
						<tr><td align=center><font size='10px' color=red><b>{{ $dir_hc->ApprovalDate ? date('d-m-Y', strtotime($dir_hc->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='10px' color=red><b>{{ $dir_hc->ApprovalDate ? date('H:i:s', strtotime($dir_hc->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endif
						@endforeach
						</td>
						</tr> 
						<tr>
						<td align="center"><i>Atasan Tidak Langsung</i></td>
						<td align="center">
						@if(!empty($dir_jab))
						@foreach ($dir_jab as $xx)    
						<i>{{ $xx->level_title_name }}</i>
						@endforeach
						@endif
						</td>
						<td align="center"><i>Direktur HC</i></td>
						</tr> 
					</table>  
					
                    </div>
					
                </div>			  
			</div>
		</div>
    </div>
	</div>
</body>
</html>
    