@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan PTK</h4>
            {{ Breadcrumbs::render('pengajuan-ptk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
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
                            <a class="btn btn-primary btn-round ml-auto" href="{{ route('pengajuan.ptk.create') }}">
                                <i class="fa fa-plus"></i>
                                Tambah Pengajuan
                            </a>
                            @endif
                            <button type="button" class="btn btn-success btn-round ml-2" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-file-export"></i> Export PTK
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
                                        <th class="content">PTK ID</th>
                                        <th class="content">No</th>
                                        <th class="content">Job Title</th>
                                        <th class="content">Position Level</th>
                                        <th class="content">Department</th>
                                        <th class="content">Jumlah</th>
                                        <th class="content">Requestor</th>
                                        <th class="content">Tgl Request</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)
                                        <tr>
										    <td class="content">{{ $item->ReqSts }}</td>
                                            <td class="content">
                                                {!! ptk_status($item->ReqSts) !!}
                                                @if ($item->ApprovedAll == 1)
                                                    <span class="badge badge-primary">Approved All</span>
                                                @endif
                                            </td>
                                            <td class="content">
                                                <div class="btn-group-vertical">
                                                    @if ($item->ReqSts == 2 && $item->EmployeeIdRequestor == $employee_id)
                                                        <a href="{{ route('pengajuan.ptk.edit', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-default"><i class="fa fa-pen"></i> Ubah</a>
                                                    @else
														@can('edit-ptk-master')
														<a href="{{ route('pengajuan.ptk.edit', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-default"><i class="fa fa-pen"></i> Ubah</a>
														@endcan
                                                    @endif	
                                                    <a href="{{ route('pengajuan.ptk.detail', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                    @can('cetak-ptk')
                                                    <a href="{{ route('pengajuan.ptk.cetak', ['id' => $item->ReqId]) }}" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Cetak</a> 
                                                    @endcan
                                                    @can('modify-ptk')
                                                        <form action="{{ route('pengajuan.ptk.remove') }}" method="post" onsubmit="return confirm('Apa anda yakin ? Hal ini tidak dapat dikembalikan.');">
                                                            @csrf
                                                            <input type="hidden" name="ReqId" value="{{ $item->ReqId }}" required>
                                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td class="content">{{ $item->ReqId }}</td>
                                            <td class="content">{{ $item->ReqNo ?? '-' }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
                                            <td class="content">{{ $item->grade_title_name }}</td>
                                            <td class="content">{{ $item->department_name }}</td>
                                            <td class="content">{{ $item->ReqQty }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->CreatedDate)) }}</td>
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
-->
@endsection

@section('modals')
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Export Data PTK</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pengajuan.ptk.export') }}" method="post" id="exportFormModal">
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
                            <option value="0">Open</option>
                            <option value="1">Close</option>
                            <option value="2">Cancel</option>
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