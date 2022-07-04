@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Daftar Kuota Cuti</h4>
        </div>
        <div class="row">
            @can('create-leave')
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filter</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="form-group">
                                <label for="">Nama Karyawan</label>
                                <select name="employee_no" class="form-control selectpicker">
                                    <option></option>
                                    @foreach ($employees as $item)
                                        @if (request()->get('employee_no') == $item->registration_number)
                                            <option value="{{ $item->registration_number }}" selected>{{ $item->registration_number.'-'.$item->fullname }}</option>
                                        @else
                                            <option value="{{ $item->registration_number }}">{{ $item->registration_number.'-'.$item->fullname }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i> Filter</button>
                                <button class="btn btn-sm btn-danger" type="button" onclick="location.assign('{{ route('leave.quota_index') }}')"><i class="fa fa-times"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
            @can('create-leave')
            <div class="col-md-9">
            @else
            <div class="col-md-12">
            @endcan
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('create-leave')
                                @role('Personnel')
                                    <a class="btn btn-sm btn-warning btn-round ml-2" href="{{ asset('uploads/excel/template-leave-quota.xlsx') }}">
                                        <i class="fas fa-cloud-download-alt"></i> Unduh Template Quota
                                    </a>
                                    <button class="btn btn-sm btn-secondary btn-round ml-2" data-toggle="modal" data-target="#quotaModal">
                                        <i class="fas fa-file-import"></i> Import Quota
                                    </button>
                                    <a class="btn btn-sm btn-success btn-round ml-2" href="{{ route('leave.quota_export') }}">
                                        <i class="far fa-file-excel"></i> Export Excel
                                    </a>
                                @endrole
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-head-bg-primary datatables" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Tgl Join</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Kuota</th>
                                        <th>Kuota Terpakai</th>
                                        <th>Kuota Periode Sebelumnya</th>
										<th>Kuota (Potong Gaji)</th>
                                        <th>Sisa</th>
                                        <th>Status</th>
										<th>Kuota Cuti <br> Extend</th>
										<th>Cuti Extend <br> Terpakai</th>
                                        @can('create-leave')
                                            @role('Personnel')
                                                <th>Opsi</th>
												<th>History</th>
                                            @endrole
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotas as $item)
                                        <tr>
                                            <td class="content">{{ $item->employee_no }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->date_of_work)) }}</td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->start_date)) }}</td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->end_date)) }}</td>
                                            <td class="content">{{ $item->qty }}</td>
                                            <td class="content">{{ $item->used }}</td>
                                            <td class="content">{{ $item->qty_before }}</td>
											<td class="content">{{ $item->qty_paid }}</td>
                                            <td class="content">{{ $item->sisa_cuti }}</td>
                                            <td class="content">
                                                {!! $item->cur_period ? '<span class="badge badge-primary">Current</span>':'' !!}
                                                {!! $item->qty_extend != null ? '<span class="badge badge-success">Extended</span>':'' !!}
                                            </td>
											<td class="content">{{ $item->qty_extend }}</td>
											<td class="content">{{ $item->used_extend }}</td>										
                                            @can('create-leave')
                                                @role('Personnel')
                                                    <td class="content">
                                                        @if ($item->cur_period && $item->qty_extend == null && $item->sisa_cuti > 0)
                                                            <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#quotaExtendModal" 
                                                                data-id="{{ $item->employee_no }}" data-periode="{{ $item->period_id }}"
                                                                data-fullname="{{ $item->fullname }}" data-periodetxt="{{ $item->start_date.' s/d '.$item->end_date }}" 
                                                                data-start="{{ $item->start_date }}"
                                                                >
                                                                <i class="fa fa-plus"></i> Kuota Extend</button>
                                                        @endif
                                                    </td>
													<td class="content">
                                                        <a href="javascript:void(0)" onclick="view_list({{ $item->period_id }}, this)"><i class="fas fa-search"></i> Lihat Daftar Cuti</a>
													</td>
                                                @endrole
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('create-leave')
                @role('Personnel')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">List Kuota Extend</h4>
                            </div>
                            <div class="card-body">
                                <table class="display table-head-bg-primary datatables" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Kuota</th>
                                            <th>Kuota Terpakai</th>
                                            <th>Sisa</th>
                                            <th>Kadaluarsa Pada</th>
                                            <th>Status</th>
											<th>Extend Ke</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($extends as $item)
                                            <tr>
                                                <td class="content">{{ $item->employee_no }}</td>
                                                <td class="content">{{ $item->fullname }}</td>
                                                <td class="content">{{ date('d-m-Y', strtotime($item->start_date)) }}</td>
                                                <td class="content">{{ date('d-m-Y', strtotime($item->end_date)) }}</td>
                                                <td class="content">{{ $item->qty }}</td>
                                                <td class="content">{{ $item->used }}</td>
                                                <td class="content">{{ $item->qty-$item->used }}</td>
                                                <td class="content">
                                                    {{ date('d-m-Y', strtotime($item->expired_at)) }}
                                                </td>
                                                <td class="content">
                                                    @if ($item->status)
                                                        <span class="badge badge-success">Tersedia</span>
                                                    @else
                                                        <span class="badge badge-danger">Expired</span>
                                                    @endif
                                                </td>
												<td class="content">
												@if ($item->extend_ke == 2)
												<b>{{ $item->extend_ke }}</b>
												@else
												{{ $item->extend_ke }}
												@endif
												</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endrole
            @endcan
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var dt = $('.datatables').dataTable({
        paging: true
    }).api();

    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

    $('#quotaExtendModal').on('show.bs.modal', function(e) {
        var employee_no = $(e.relatedTarget).data('id');
        var period_id = $(e.relatedTarget).data('periode');
        var fullname = $(e.relatedTarget).data('fullname');
        var periodetxt = $(e.relatedTarget).data('periodetxt');
        var start_date = $(e.relatedTarget).data('start');

        $(e.currentTarget).find('input[name="employee_no"]').val(employee_no);
        $(e.currentTarget).find('input[name="period_id"]').val(period_id);
        $(e.currentTarget).find('input[name="fullname"]').val(fullname);
        $(e.currentTarget).find('input[name="periode"]').val(periodetxt);
        $(e.currentTarget).find('input[name="start_date"]').val(start_date);
    });
	
	var modal = $('#pageModal');
    var form = $('#formModal');
	
	function view_list(id) {
        var content = $('#listModal').find('.modal-body');
        $.ajax({
            url: '{{ route('employee-leave.list_data') }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                content.addClass('is-loading');
            },
            data: {
                period_id: id
            },
            success: function(resp){
                content.html(resp.html);
                $('#listModal').modal('toggle');
            },
            error: function(err){
                showNotification('danger', 'Terjadi Kesalahan! Silahkan muat ulang.');
            },
            complete: function(){
                content.removeClass('is-loading');
            }
        });
    }
</script>
@endsection

@section('modals')
    <!-- Quota Modal -->
    <div class="modal fade" id="quotaModal" tabindex="-1" role="dialog" aria-labelledby="quotaModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Upload file import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('leave.quota_import') }}" method="post" id="formQuotaModal" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">File <span class="required-label">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".xlsx" required>
                        </div>
                        <div class="form-group">
                            <div class="progress-card" style="display:none">
                                <div class="progress-status">
                                    <span class="text-muted">Status</span>
                                    <span class="text-muted fw-bold"> 0%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-secondary" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="card">
                                <div class="card-body">
                                    <label class="text-primary"><i class="fas fa-lightbulb"></i> Good to know !</label>
                                    <ol>
                                        <li>Ekstensi file .xlsx</li>
                                        <li>Gunakan awalan (') petik satu untuk kolom berisi angka</li>
                                        <li>Kolom yang diwarna hijau opsional</li>
                                        <li>Format tanggal menggunakan dd/mm/yyyy</li>
                                        <li>Import tidak berhasil jika ada satu saja kesalahan dalam mengisi kolom template</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="reset_data" name="reset_data" value="true">
                                <label class="custom-control-label" for="reset_data">Reset Existing Data ? <span class="required-label">(Tindakan Berbahaya)</span></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Quota Extend Modal --}}
    <div class="modal fade" id="quotaExtendModal" tabindex="-1" role="dialog" aria-labelledby="quotaExtendTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Form Cuti Extend</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('leave.submit_extend') }}" method="post">
                        @csrf
                        <input type="hidden" name="employee_no" required>
                        <input type="hidden" name="period_id" required>
                        <input type="hidden" name="start_date" required>
                        <div class="form-group">
                            <label for="">Nama Karyawan</label>
                            <input type="text" name="fullname" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Periode</label>
                            <input type="text" name="periode" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Kuota Extend <span class="required-label">*</span></label>
                            <input type="number" name="qty" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Kadaluarsa</label>
                            <input type="text" name="expired_at" class="form-control datepicker">
                            <span class="form-text">Kosongkan jika ingin 6 bulan dari tanggal mulai periode</span>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	
	<!-- modal -->
	<div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="participantsModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Daftar Ketidakhadiran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
@endsection