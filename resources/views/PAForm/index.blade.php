@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Form Penilaian Tahunan</h4>
            {{ Breadcrumbs::render('PAFORM') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
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
                                        <th class="content">ID</th>
										<th class="content">NIK</th>
                                        <th class="content">Nama</th>
                                        <th class="content">Periode</th>
                                        <th class="content">Tahun / Semester</th>
										<th class="content">Department</th>
                                        <th class="content">Jabatan</th>
                                        <th class="content">Atasan Langsung</th>
										<th class="content">Batas Waktu Penilaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)									
                                        <tr>
										    <td class="content">{{ $item->ApprovedAll }}</td>
                                            <td class="content">
												@if ($item->ReqSts == 1 && $item->ApprovedAll == NULL)
                                                     <span class="badge badge-warning" >[Belum Dinilai]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 0)
													 <span class="badge badge-success" >[Menunggu Persetujuan]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 1)
													 <span class="badge badge-primary" >[Disetujui]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 2)
													 <span class="badge badge-danger" >[Ditolak]</span>													 
                                                 @endif
											</td>
                                            <td class="content">
                                                <div class="btn-group-vertical">												
													<a href="{{ route('PAForm.edit', ['id' => $item->PaId]) }}" class="btn btn-sm btn-default"><i class="fa fa-pen"></i> @if ($item->ReqSts == 1) Nilai @else Ubah @endif</a>
                                                    <a href="{{ route('PAForm.detail', ['id' => $item->PaId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                    @can('cetak-fpk')
                                                    <a href="{{ route('PAForm.cetak', ['id' => $item->PaId]) }}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Cetak</a> 
                                                    @endcan
                                                    @can('modify-ptk')
                                                        <form action="{{ route('PAForm.remove') }}" method="post" onsubmit="return confirm('Apa anda yakin ? Hal ini tidak dapat dikembalikan.');">
                                                            @csrf
                                                            <input type="hidden" name="PaId" value="{{ $item->PaId }}" required>
                                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td class="content">{{ $item->PaId }}</td>
                                            <td class="content">{{ $item->employee_id }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
											<td class="content">{{ $item->periode_name }}</td>
											<td class="content">{{ $item->tahun .' / '. $item->semester }}</td>
                                            <td class="content">{{ $item->department_name }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
											<td class="content">{{ $item->nama_atasan }}</td>
											<td class="content">{{ $item->edate }}</td>
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
		"order": [[ 5, "asc" ]],
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
                <form action="#" method="post" id="exportFormModal">
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