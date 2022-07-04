@extends('layouts.app')

@section('content')
<!--
<style type="text/css">

table
        {
            width: 100%;
            display: block;
        }

        thead
        {
            display: block;
            width: 100%;

        }

        tbody
        {
            height: 500px;
            display: inline-block;
            width: 100%;
            overflow: auto;
        }

        th, td
        {
            width: 100px;
            text-align:center;
        }
</style>
-->
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Form Pembaharuan Karyawan</h4>
            {{ Breadcrumbs::render('pengajuan-fpk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
			@role('Personnel')
			          <div class="card">
	                    <div class="card-header card-primary">
	                        <div class="d-flex align-items-center">
	                            <h4 class="card-title">Filter Data</h4>
	                            <button class="btn btn-sm btn-round ml-auto" data-toggle="collapse" data-target="#collapseTable">
	                                <i class="fas fa-sort"></i>
	                            </button>
	                        </div>
	                    </div>
	                    <div class="card-body collapse" id="collapseTable">
	                        <form action="" id="filterForm">
	                            <div class="row">
	                                <div class="col-md-12">
	                                    <div class="form-group">
											<label for="">Status Pengajuan</label>
	                                        <select name="Flag_proses" class="form-control selectpicker" style="width: 100%">
	                                            <option value="all" selected>Semua</option>
												<option value="2">Open</option>
												<option value="1">Waiting Approval</option>
												<option value="3">Approved All</option>
												<option value="4">No Approved</option>
	                                        </select>
	                                    </div>
	                                </div>
									<!--
	                                <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label for="">Tanggal Mulai</label>
	                                        <input type="text" class="form-control datepicker" name="start_date" required placeholder="Pilih Tanggal">
	                                    </div>
	                                    <div class="form-group">
	                                        <label for="">Tanggal Akhir</label>
	                                        <input type="text" class="form-control datepicker" name="end_date" required placeholder="Pilih Tanggal">
	                                    </div>
	                                </div>
									-->
	                            </div>
	                            <div class="form-group">
	                                <button class="btn btn-primary btn-sm" onclick="location.assign('{{ route('pengajuan.fpk') }}')"><i class="fas fa-filter"></i> Filter</button>
	                            </div>
	                        </form>
	                    </div>
	                </div>
	            </div>
				@endrole
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @php
                                if (auth()->user()->hasRole('Super Admin')) {
                                    $grade_title_code = 'Admin';
                                }else{
                                    $grade_title_code = auth()->user()->employee->grade_title->grade_title_code;
                                }
                            @endphp
                            @if ($grade_title_code != 'GRD05' && $grade_title_code != 'Admin')
                            <a class="btn btn-primary btn-round ml-auto" href="{{ route('pengajuan.fpk.create') }}">
                                <i class="fa fa-plus"></i>
                                Tambah Pengajuan
                            </a>
                            @endif
                            <button type="button" class="btn btn-success btn-round ml-2" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-file-export"></i> Export File
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-head-bg-primary" id="dttable">
                                <thead>
                                    <tr>
									    <th class="content">Sts</th>
                                        <th class="content">Status</th>
                                        <th class="content">Opsi</th>
                                        <th class="content">Tgl Diajukan</th>
                                        <th class="content">ID</th>
                                        <th class="content">No FPK</th>
										<th class="content">NIK</th>
                                        <th class="content">Name</th>
                                        <th class="content">Jenis Pembaharuan</th>
                                        <th class="content">Department</th>
                                        <th class="content">Jabatan</th>
                                        <th class="content">Diajukan</th>
                                        <th class="content">SK / PKWT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)									
                                        <tr>
										    <td class="content">{{ $item->ApprovedAll }}</td>
                                            <td class="content">
												 @if ($item->Flag_proses == 1 && $item->ApprovedAll == 0)
                                                     <span class="badge badge-warning" >[Waiting Approval]</span>
												 @elseif ($item->Flag_proses == 2 && $item->ApprovedAll == 0)
													 <span class="badge badge-success" >[Open]</span>
												 @elseif ($item->Flag_proses == 2 && $item->ApprovedAll == 1)
													 <span class="badge badge-primary" >[Approved All]</span>
												 @elseif ($item->Flag_proses == 2 && $item->ApprovedAll == 2)
													 <span class="badge badge-danger" >[No Approved]</span>													 
                                                 @endif
                                            </td>
                                            <td class="content">
                                                <div class="btn-group-vertical">
                                                    @if ($item->Flag_proses <> 2 && $item->Insert_user == $employee_id)
                                                        <a href="{{ route('pengajuan.fpk.edit', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-default"><i class="fa fa-pen"></i> Ubah</a>
                                                    @else
														@can('edit-fpk-master')
														<a href="{{ route('pengajuan.fpk.edit', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-default"><i class="fa fa-pen"></i> Ubah</a>
														@endcan
                                                    @endif	
                                                    <a href="{{ route('pengajuan.fpk.detail', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                    @can('cetak-fpk')
                                                    <a href="{{ route('pengajuan.fpk.cetak', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Cetak</a> 
                                                    @endcan
                                                    @can('modify-ptk')
                                                        <form action="{{ route('pengajuan.fpk.remove') }}" method="post" onsubmit="return confirm('Apa anda yakin ? Hal ini tidak dapat dikembalikan.');">
                                                            @csrf
                                                            <input type="hidden" name="ReqId" value="{{ $item->ReqId }}" required>
                                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->Insert_date)) }}</td>
                                            <td class="content">{{ $item->ReqId }}</td>
                                            <td class="content">{{ $item->fpk_no }}</td>
                                            <td class="content">{{ $item->employee_id }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
											<td class="content">
											@if ($item->promosi == 1){{ "Promosi"  }} <br> @endif
											@if ($item->demosi == 1){{ "Demosi"  }} <br> @endif
											@if ($item->mutasi == 1){{ "Mutasi"  }} <br> @endif
											@if ($item->perubahan_job == 1){{ "Perubahan Job Title"  }} <br> @endif
											@if ($item->perubahan_status == 1){{ "Perubahan Status"  }} <br> @endif
											@if ($item->penyesuaian_comben == 1){{ "Penyesuaian Comben"  }} <br> @endif
											@if ($item->perpanjangan_kontrak == 1){{ "Perpanjangan Kontrak"  }} <br> @endif
											@if ($item->habis_kontrak == 1){{ "Habis Kontrak"  }} <br> @endif
											</td>
                                            <td class="content">{{ $item->department_name }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
											<td class="content">{{ $item->nama_creator }}</td>
											<td class="content">
                                                <div class="btn-group-vertical">
                                                @can('cetak-sk')
                                                 @if ($item->flag_jenis == 'OD' && $item->ApprovedAll == 1 && $item->habis_kontrak <> 1 && $item->penyesuaian_comben <> 1)
													  @if (($item->konter_sk > 0) && (!empty($item->sk_no)))
														<a href="{{ route('pengajuan.fpk.cetak_sk', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> Cetak SK</a><br>
														<a href="{{ route('pengajuan.fpk.edit_sk', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i> Ubah SK</a>
													  @elseif (($item->konter_sk == NULL) && ($item->sk_no == NULL))
													    <a href="{{ route('pengajuan.fpk.generate_sk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-success" onclick="return confirm('Anda Yakin Dibuatkan SK ?');"><i class="fa fa-check"></i> Buat SK </a><br>
													    <a href="{{ route('pengajuan.fpk.no_generate_sk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin Tidak Dibuatkan SK ?');"><i class="fa fa-times"></i> Tidak SK</a>
													  @endif
												 @elseif ($item->flag_jenis == 'RC' && $item->ApprovedAll == 1 && $item->perpanjangan_kontrak == 1 && $item->perubahan_job == 1) 
													  @if (($item->konter_sk > 0) && (!empty($item->sk_no)))
														<a href="{{ route('pengajuan.fpk.cetak_sk', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> Cetak SK</a><br>
														<a href="{{ route('pengajuan.fpk.edit_sk', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i> Ubah SK</a>
													  @elseif (($item->konter_sk == NULL) && ($item->sk_no == NULL))
													    <a href="{{ route('pengajuan.fpk.generate_sk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-success" onclick="return confirm('Anda Yakin Dibuatkan SK ?');"><i class="fa fa-check"></i> Buat SK </a><br>
													    <a href="{{ route('pengajuan.fpk.no_generate_sk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin Tidak Dibuatkan SK ?');"><i class="fa fa-times"></i> Tidak SK</a>
													  @endif
												 @elseif ($item->flag_jenis == 'RC' && $item->ApprovedAll == 1 && $item->perubahan_status == 1)
													  @if (($item->konter_sk > 0) && (!empty($item->sk_no)))
														<a href="{{ route('pengajuan.fpk.cetak_sk', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> Cetak SK</a><br>
														<a href="{{ route('pengajuan.fpk.edit_sk', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i> Ubah SK</a>
													  @elseif (($item->konter_sk == NULL) && ($item->sk_no == NULL))
													    <a href="{{ route('pengajuan.fpk.generate_sk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-success" onclick="return confirm('Anda Yakin Dibuatkan SK ?');"><i class="fa fa-check"></i> Buat SK </a><br>
													    <a href="{{ route('pengajuan.fpk.no_generate_sk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin Tidak Dibuatkan SK ?');"><i class="fa fa-times"></i> Tidak SK</a>
													  @endif
												 @elseif ($item->flag_jenis == 'RC' && $item->ApprovedAll == 1 && $item->perpanjangan_kontrak == 1)
														@if (($item->konter_pkwt > 0) && (!empty($item->pkwt_no)))
														<a href="{{ route('pengajuan.fpk.cetak_pkwt', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> Cetak PKWT</a><br>							
													    @elseif (($item->konter_pkwt == NULL) && ($item->pkwt_no == NULL))
													    <a href="{{ route('pengajuan.fpk.generate_pkwt', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin Dibuatkan PKWT?');"><i class="fa fa-check"></i> Draft PKWT </a><br>
													    @endif
												 @elseif ($item->flag_jenis == 'RC' && $item->ApprovedAll == 1 && $item->habis_kontrak == 1)
														@if (($item->konter_pkwt > 0) && (!empty($item->pkwt_no)))
														<a href="{{ route('pengajuan.fpk.cetak_sphk', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> Cetak SPHK</a><br>							
													    @elseif (($item->konter_pkwt == NULL) && ($item->pkwt_no == NULL))
													    <a href="{{ route('pengajuan.fpk.generate_sphk', ['id' => $item->ReqId]) }}" target="_self" class="btn btn-sm btn-warning" onclick="return confirm('Anda Yakin Dibuatkan SPHK?');"><i class="fa fa-check"></i> Draft SPHK </a><br>
													    @endif
												 @endif
												 
                                                @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var dt = $('#dttable').dataTable({
        "order": [[ 0, "asc" ]],
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            },
        ],
		responsive: true,
    }).api();   
</script>
@endsection

@section('modals')
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Export Data FPK</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pengajuan.fpk.export') }}" method="post" id="exportFormModal">
                    @csrf
                    <div class="form-group">
                        <label for="">Request Dari</label>
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Request Sampai</label>
                        <input type="text" class="form-control datepicker" name="end_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Status <span class="required-label">*</span></label>
                        <select name="status" class="form-control selectpicker" required style="width: 100%">
                            <option></option>
                            <option value="all">All</option>
                            <option value="1">Waiting Approval</option>
                            <option value="2">Open</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="true" name="is_approved_all">
                            <span class="form-check-sign">Approved All</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection