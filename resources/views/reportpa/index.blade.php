@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Laporan Penilaian Karyawan</h4>
            {{ Breadcrumbs::render('reportpa') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('export-reportpa')
                                <div class="btn-group ml-auto">
                                   <button type="button" class="btn btn-sm btn-round btn-success" data-toggle="modal" data-target="#exportModal">
                                        <i class="fas fa-file-export"></i> Export
                                    </button> 
									
									 
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="report-leave-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">Sts</th>
                                        <th class="content">Status</th>
										<th class="content">NIK</th>
                                        <th class="content">Nama</th>
										<th class="content">Department</th>
                                        <th class="content">Jabatan</th>
                                        <th class="content">Atasan Langsung</th>
                                        <th class="content">Periode</th>
                                        <th class="content">Tahun / Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach ($pengajuans as $item)									
                                        <tr>
										    <td class="content">{{ $item->ApprovedAll }}</td>
                                            <td class="content">
												 @if ($item->ReqSts == 2 && $item->ApprovedAll == 0)
													 <span class="badge badge-success" >[Menunggu Persetujuan]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 1)
													 <span class="badge badge-primary" >[Disetujui]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 2)
													 <span class="badge badge-danger" >[Ditolak]</span>													 
                                                 @endif
											</td>                                       
                                            <td class="content">{{ $item->employee_id }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
											<td class="content">{{ $item->department_name }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
											<td class="content">{{ $item->nama_atasan }}</td>
											<td class="content">{{ $item->periode_name }}</td>
											<td class="content">{{ $item->tahun .' / '. $item->semester }}</td>
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
       var dt = $('#report-leave-table').dataTable({
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
    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Export Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reportpa.export') }}" method="post" id="exportFormModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Tahun <span class="required-label">*</span></label>
                            <select name="tahun" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                            </select>
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