<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PTK PRINT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:#333;
            text-align:left;
            font-size:10px;
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
							<tr><td>
                                <table class="table borderless" width=100%>
								<tbody>
								<tr>
								<td></td>
								</tr>
								<tr>
								<td><b>PT. NIRAMAS UTAMA</b></td>
								</tr>
								<tr>
								<td align="right">(Open/Close) Tgl ......................</td>
								</tr>
								<tr>
								<td></td>
								</tr>
								<tr>
								<td align="center"><b><u>PERMINTAAN TENAGA KERJA</b></u><br>Recruitment Request</td>
								</tr>
								<tr>
								<td align="right"><b>NO : {{ $pengajuan->ReqNo ?? '-' }}</b></td>
								</tr>
								</tbody>
								</table>
							</td></tr> 
							</table>
                            </div>                           
							<div class="col-md-6">          
								<table border="1" width="100%">
								<tbody>
								<tr>
								<td><b><u>Jabatan</b></u><br>Job Titles</td>
								<td>{{ $pengajuan->job_title_name }}</td>
								<td><b><u>Jumlah Orang</b></u><br>Headcount</td>
								<td>{{ $pengajuan->ReqQty }}</td>
								</tr>
								<tr>
								<td><b><u>Level Jabatan</b></u><br>Position Level</td>
								<td>{{ $pengajuan->grade_title_name }}</td>
								<td><b><u>Status Karyawan</b></u><br>Employee Status</td>
								<td>{{ $pengajuan->empStatus }} ({{ $pengajuan->EmploymentNote ?? '-' }})</td>
								</tr>
								<tr>
								<td><b><u>Departemen</b></u><br>Department</td>
								<td>{{ $pengajuan->department_name }}</td>
								<td><b><u>Waktu Kerja</b></u><br>Working Time</td>
								<td>
								<table width=100%>
                                <tbody>
                                <tr>
                                <td width="15"><input  type="radio" name="mutasi" {{ $pengajuan->WorkingTime == 'SHIFT' ? 'checked' : '' }} ></td>
                                <td>Shift</td>
                                </tr>
                                <tr>
                                <td><input  type="radio" name="mutasi" {{ $pengajuan->WorkingTime == 'NONSHIFT' ? 'checked' : '' }} ></td>
                                <td>Non Shift</td>
                                </tr>
                                </tbody>
                                </table>
								</td>
								</tr>
								<tr>
								<td><b><u>Lokasi Kerja</b></u><br>Working Location</td>
								<td>{{ $pengajuan->region_city }}</td>
								<td><b><u>Tingkat / Golongan</b></u><br>Grade / Level</td>
								<td>{{ $pengajuan->Grade .'-'. $pengajuan->Level }}</td>
								</tr>
								<tr>
								<td rowspan="6"><b><u>Alasan Perekrutan</b></u><br>Reason Of Hiring</td>
								<td rowspan="6" valign="top" >
								<table>
                                <tbody>
                                <tr>
                                <td width="12"><input type="checkbox" name="mutasi" {{ $pengajuan->ReasonOfHiring == 'NewPosition' ? 'checked' : '' }} ></td>
                                <td><b><u>Posisi Baru</u></b><br>New Position</td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" name="mutasi" {{ $pengajuan->ReasonOfHiring == 'ContractExt' ? 'checked' : '' }} ></td>
                                <td><b><u>Perpanjangan Kontrak</u></b><br>Contract Extension:</td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" name="mutasi" {{ $pengajuan->ReasonOfHiring == 'ReplcMut' ? 'checked' : '' }} ></td>
                                <td><b><u>Menggantikan Karyawan Mutasi</u></b><br>Replacement Mutation Employee’s Name:</td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" name="mutasi" {{ $pengajuan->ReasonOfHiring == 'ReplcRsgn' ? 'checked' : '' }} ></td>
                                <td><b><u>Menggantikan Karyawan Resign</u></b><br>Replacement Resign Employee’s Name</td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" name="mutasi" {{ $pengajuan->ReasonOfHiring == 'ReplcTemp' ? 'checked' : '' }} ></td>
                                <td><b><u>Penggantian Karyawan Sementara</u></b><br>Replacement Temporary Employee’s</td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" name="mutasi" {{ $pengajuan->ReasonOfHiring == 'ReplcDead' ? 'checked' : '' }} > </td>
                                <td><b><u>Menggantikan Karyawan Meninggal Dunia</u></b><br>Replacement Dead Employee’s</td>
                                </tr>
                                </tbody>
                                </table><br><br><br>
								@foreach ($replacements as $item)
									@if ($item->EmployeeReplaced <> NULL)
										 @if ($item->EmployeeReplacement == NULL)
                                                {{ $loop->iteration }}. {{ $item->EmployeeReplaced.' diganti oleh ___________'}} <br>
										 @else
											    {{ $loop->iteration }}. {{ $item->EmployeeReplaced.' diganti oleh '.$item->EmployeeReplacement }} <br>
										 @endif
									@endif
                                @endforeach
								</td>
								<td align="center" colspan="2"><b><u>Kualifikasi</b></u><br>Qualification</td>
								</tr>
								<tr>
								<td><b><u>Jenis Kelamin</b></u><br>Gender</td>
								<td>
								Laki-Laki : {{ $pengajuan->QtyMale }} Orang<br>
								Perempuan : {{ $pengajuan->QtyFemale }} Orang<br>
								L/P :{{ $pengajuan->QtyBoth }} Orang
								</td>
								</tr>
								<tr>
								<td height=30px><b><u>Pendidikan</b></u><br>Education</td>
								<td>{{ $pengajuan->Education .' - '. $pengajuan->EducationFocus}}</td>
								</tr>
								<tr>
								<td><b><u>Usia</b></u><br>Age</td>
								<td>{{ $pengajuan->MinAge.' - '.$pengajuan->MaxAge .' Tahun' }}</td>
								</tr>
								<tr>
								<td><b><u>Pengalaman Kerja</b></u><br>Working Experience</td>
								<td>{{ $pengajuan->WorkingExperience }}</td>
								</tr>
								<tr>
								<td><b><u>Tanggal Aktif</b></u><br>Active Date</td>
								<td>{{  date('d F Y', strtotime($pengajuan->ActiveDate)) }}</td>
								</tr>
								<tr>
								<td><b><u>Uraian Pekerjaan</b></u><br>Job Description</td>
								<td colspan="3">
								@foreach ($job_desc as $item)
                                            {{ $loop->iteration }}. {{ $item->JobDesc ?? '-' }} <br>
                                @endforeach
								</td>
								<tr>
								<td><b><u>Keahlian Khusus</b></u><br>Particular Skill</td>
								<td colspan="3">
								@foreach ($skill_desc as $item)
                                            {{ $loop->iteration }}. {{ $item->SkillDesc ?? '-' }} <br>
                                @endforeach 
								</td>
							    </tr>
								</tr>
								<tr>
								<td><b><u>Peralatan dan <br>Fasilitas Kerja</b></u><br>Equipments and <br> Facilities</td>
								<td colspan="3">								
								<table width=50%>								
								<tr>
								<td style="vertical-align:top">
								@foreach ($facilities as $item)								
								@if ($loop->index%5 == 0 && $loop->index != 0)
								</td>
								<td style="vertical-align:top">
								@endif								
								{{ $loop->iteration }}. {{ $item->Description }}<br>								
								@if ($loop->last)
                                </td>
                                @endif
								@endforeach
								</tr>
								</table><br>
								                              
								* Jika ada perubahan pengajuan segera informasi ke HCM <br>
								* Perubahan pengajuan bisa dilakukan sebelum permintaan masuk ke bagian Pembelian <br>
								* Pengajuan akan disesuaikan dengan matrix fasilitas yang berlaku di Perusahaan<br>
								* Spesifikasi akan diverifikasi oleh GA								
								</td>
								</tr>
								<tr>
								<td rowspan="2"><b><u>Catatan</b></u><br>Note</td>
								<td colspan="2" rowspan="2">{{ $pengajuan->Notes }}</td>
								<td align="center">Tanggal Terima<br>HCM</td>
								</tr>
								<tr>
								<td height=30px></td>
								</tr>
								<tr>
								<td><b><u>Atasan Langsung</b></u><br>Direct superior</td>
								<td>{{ $pengajuan->fullname }}</td>
								<td><b><u>Jabatan Atasan Langsung</b></u><br>Title of Direct superior</td>
								<td>{{ $pengajuan->jabatan }}</td>
								</tr>
								<tr>
								@foreach ($ats_jab as $xx)
								<td><b><u>Atasan Tidak Langsung</b></u><br>Indirect superior</td>
								<td>{{ $xx->fullname }}</td>
								<td><b><u>Jabatan Atasan Tidak Langsung</b></u><br>Title of Indirect superior</td>
								<td>{{ $xx->level_title_name }}</td>
								@endforeach
								</tr>
								</tbody>
								</table>
							<br>                                  
                            </div>                         
                        </div>
                    </div>
					@endforeach
                    <div class="card-footer">					
                    </div>
					<table border="1" width=100%>
						<tbody> 
						<tr>
						<td><b>Diminta Oleh</b></td>
						@if (!empty($mgr_lsng))
						<td><b>Diperiksa Oleh</b></td> 	
						@endif
						@if (!empty($dir_lsng))
						<td colspan="2" ><b>Disetujui Oleh</b></td> 
						@else
						<td><b>Disetujui Oleh</b></td>
						@endif
						<td colspan="2" ><b>Diketahui Oleh</b></td>   
						</tr> 
						<tr> 
						<td>Requested By</td>
						@if (!empty($mgr_lsng))
						<td>Verified By</td> 
						@endif
						@if (!empty($dir_lsng))
						<td colspan="2" >Approved By</td>
						@else
						<td>Approved By</td>
						@endif
						<td colspan="2" >Acknowledge by</td>  
						</tr> 
						<tr> 
						<td height=70px>
						<table width=100%>						
						<tr><td align=center><font size='8px' color=blue><b>{{ 'Created By' }}<b></font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $pengajuan->fullname }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $pengajuan->CreatedDate ? date('d-m-Y', strtotime($pengajuan->CreatedDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $pengajuan->CreatedDate ? date('H:i:s', strtotime($pengajuan->CreatedDate)):'-' }}</font></td></tr>
						</table>
						</td>
						<td height=70px>
						@foreach ($ats_td_lsng as $item)
						<table width=100%>
						<tr><td align=center><font size='8px' color=blue><b>{{ $item->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $item->fullname }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $item->ApprovalDate ? date('d-m-Y', strtotime($item->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $item->ApprovalDate ? date('H:i:s', strtotime($item->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						</td>
						@foreach ($mgr_lsng as $mgr_lsng)
						<td height=70px>					
						<table width=100%>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_lsng->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_lsng->fullname }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_lsng->ApprovalDate ? date('d-m-Y', strtotime($mgr_lsng->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_lsng->ApprovalDate ? date('H:i:s', strtotime($mgr_lsng->ApprovalDate)):'-' }}</font></td></tr>
						</table>												
						</td>
						@endforeach
						@foreach ($dir_lsng as $dir_lsng)
						<td height=70px>						
						<table width=100%>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_lsng->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_lsng->fullname }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_lsng->ApprovalDate ? date('d-m-Y', strtotime($dir_lsng->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_lsng->ApprovalDate ? date('H:i:s', strtotime($dir_lsng->ApprovalDate)):'-' }}</font></td></tr>
						</table>						
						</td> 
						@endforeach
						<td height=70px>
						@foreach ($mgr_hc as $mgr_hc)
						<table width=100%>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_hc->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_hc->fullname }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_hc->ApprovalDate ? date('d-m-Y', strtotime($mgr_hc->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $mgr_hc->ApprovalDate ? date('H:i:s', strtotime($mgr_hc->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						</td> 
						<td height=70px>
						@foreach ($dir_hc as $dir_hc)
						<table width=100%>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_hc->ApprovalSts == 1 ? 'Approved By':'Disapproved By' }}<b></font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_hc->fullname }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_hc->ApprovalDate ? date('d-m-Y', strtotime($dir_hc->ApprovalDate)):'-' }}</font></td></tr>
						<tr><td align=center><font size='8px' color=blue><b>{{ $dir_hc->ApprovalDate ? date('H:i:s', strtotime($dir_hc->ApprovalDate)):'-' }}</font></td></tr>
						</table>
						@endforeach
						</td>
						</tr>
						<tr> 
						<td>{{ $pengajuan->jabatan }}</td>
						<td>{{ $xx->level_title_name }}</td>
						@if (!empty($mgr_lsng))
						<td>{{ $mgr_lsng->level_title_name }}</td>
						@endif
						@if (!empty($dir_lsng))
						<td>{{ $dir_lsng->jabatan_direktur }}</td> 
						@endif
						<td>HC Manager</td> 
						<td>HC Direktur</td> 
						</tr> 
						<tr> 
						<td>Date:{{ $pengajuan->CreatedDate ? date('d-m-Y', strtotime($pengajuan->CreatedDate)):'-' }}</td>
						<td>Date:{{ $item->ApprovalDate ? date('d-m-Y', strtotime($item->ApprovalDate)):'-' }}</td>
						@if (!empty($mgr_lsng))
						<td>Date:{{ $mgr_lsng->ApprovalDate ? date('d-m-Y', strtotime($mgr_lsng->ApprovalDate)):'-' }}</td>
						@endif
						@if (!empty($dir_lsng))
						<td>Date:{{ $dir_lsng->ApprovalDate ? date('d-m-Y', strtotime($dir_lsng->ApprovalDate)):'-' }}</td>
						@endif
						@if (!empty($mgr_hc))
						<td>Date:{{ $mgr_hc->ApprovalDate ? date('d-m-Y', strtotime($mgr_hc->ApprovalDate)):'-' }}</td>
						<td>Date:{{ $dir_hc->ApprovalDate ? date('d-m-Y', strtotime($dir_hc->ApprovalDate)):'-' }}</td>
						@else
						<td>Date:</td>
						<td>Date:</td>
						@endif
						</tr> </tbody> </table> 
				</div>			  
			</div>
		</div>
    </div>
	</div>
</body>
</html>
    