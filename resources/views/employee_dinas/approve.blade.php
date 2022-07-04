@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Approval Izin Dinas Luar</h4>
            {{ Breadcrumbs::render('approve-dinas') }}
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
                            <table id="employee-leave-table" width="100%" class="table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">Aksi</th>
                                        <th class="content">Status</th>
                                        <th class="content">Nama Karyawan</th>
                                        <th class="content">Tanggal Mulai</th>
                                        <th class="content">Tanggal Berakhir</th>
                                        <th class="content">Jam Mulai</th>
                                        <th class="content">Jam Berakhir</th>
                                        <th class="content">Tujuan</th>
                                        <th class="content">Total Hari</th>
                                        <th class="content">Approval Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee_dinas as $employee_dinas)
                                        <tr>
                                            <td class="content">
                                                @if ($employee_dinas->status === 'new')
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-bars"></i> Opsi
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="javascript:void(0)" onclick="approve({{ $employee_dinas->id }}, 'apv')" class="dropdown-item"><i class="fas fa-check-circle"></i> Setujui</a>
                                                            <a href="javascript:void(0)" onclick="approve({{ $employee_dinas->id }}, 'rjt')" class="dropdown-item"><i class="fas fa-times-circle"></i> Tolak</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="content">
                                                @switch($employee_dinas->status)
                                                    @case('new')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                        @break
                                                    @case('apv')
                                                        <span class="badge badge-success">Diterima</span>
                                                        @break
                                                    @case('rjt')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                        @break
                                                    @default
                                                        
                                                @endswitch
                                            </td>
                                            <td class="content">{{ $employee_dinas->fullname }}</td>
                                            <td class="content">{{ tgl_indo($employee_dinas->start_date) }}</td>
                                            <td class="content">{{ tgl_indo($employee_dinas->end_date) }}</td>
                                            <td class="content">{{ $employee_dinas->start_time ?? '' }}</td>
                                            <td class="content">{{ $employee_dinas->end_time ?? '' }}</td>
                                            <td class="content">{{ $employee_dinas->reason }}</td>
                                            <td class="content">{{ $employee_dinas->total }} Hari</td>
                                            <td class="content"></td>
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
    var dt = $('#employee-leave-table').dataTable({
        order: [[1, 'desc']]
    }).api();

    var dt2 = $('#quota-teams-table').dataTable({
        order: [[1, 'desc']]
    }).api();

    function approve(id, val) {
        swal({
            titleText: 'Apakah anda yakin?',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                if (val == 'rjt') {
                    swal({
                        title: 'Masukan Alasan',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-danger',
                        confirmButtonText: 'Oke',
                    }).then((result) => {
                        if (result.value) {
                            do_approve(id, val, result.value);
                        }
                    });
                }else{
                    do_approve(id, val);
                }
            }
        });
    }

    function do_approve(id, val, reason = null) {
        $.ajax({
            url: '{{ url('employee-dinas') }}/'+id+'/approve',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status: val,
                reason: reason,
            },
            success: function(resp){
                showNotification('info', 'Halaman akan direfresh...');
                location.reload();
            },
            error: (error)=>{
                console.error(error);
                showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
            }
        });
    }
</script>
@endsection
