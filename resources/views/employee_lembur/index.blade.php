@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Daftar Ketidakhadiran</h4>
            {{ Breadcrumbs::render('daftar-cuti') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            {{-- @can('request-leave')
                                <button class="btn btn-primary btn-round ml-auto" onclick="location.assign('{{ route('employee-leave.create') }}')">
                                    <i class="fa fa-plus"></i>
                                    Ajukan Cuti
                                </button>
                            @endcan --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="employee-leave-table" class="display table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">ID</th>
                                        <th class="content">Kategori</th>
                                        <th class="content">Tanggal Mulai</th>
                                        <th class="content">Tanggal Berakhir</th>
                                        <th class="content">Jam Mulai</th>
                                        <th class="content">Jam Berakhir</th>
                                        <th class="content">Keterangan</th>
                                        <th class="content">Durasi</th>
                                        <th class="content">Minus Tahunan ?</th>
                                        <th class="content">Dibuat</th>
                                        <th class="content">Status</th>
                                        <th class="content">Approval Oleh</th>
                                        <th class="content">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee_leaves as $employee_leave)
                                        <tr>
                                            <td class="content">{{ $employee_leave->id }}</td>
                                            <td class="content">{{ $employee_leave->leave->leave_name }}</td>
                                            <td class="content">{{ $employee_leave->start_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $employee_leave->end_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $employee_leave->start_time ?? '' }}</td>
                                            <td class="content">{{ $employee_leave->end_time ?? '' }}</td>
                                            <td class="content">{{ $employee_leave->reason }}</td>
                                            <td class="content">{{ $employee_leave->total }} Hari</td>
                                            <td class="content">{{ !$employee_leave->leave_id ? 'Tidak':$employee_leave->leave->minus_annual == 1 ? 'Ya':'Tidak' }}</td>
                                            <td class="content">{{ $employee_leave->created_at->diffForHumans() }}</td>
                                            <td class="content">
                                                @switch($employee_leave->status)
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
                                            <td class="content">
                                                @if ($employee_leave->approval_by == 0)
                                                    Auto By System
                                                @else
                                                    {{ $employee_leave->approved_by->fullname }}
                                                @endif
                                            </td>
                                            <td class="content">
                                                {{-- <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                        {{ $employee_leave->status != 'pending' ? 'disabled':'' }}>
                                                        <i class="fas fa-bars"></i> Opsi
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{ route('employee-leave.edit', $employee_leave->id) }}" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                        <a href="javascript:void(0)" onclick="remove({{ $employee_leave->id }})" class="dropdown-item"><i class="fas fa-trash-alt"></i> Hapus</a>
                                                    </div>
                                                </div> --}}
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
    var dt = $('#employee-leave-table').dataTable({
        // responsive: true,
    }).api();

    function remove(id) {
        swal({
            titleText: 'Apakah anda yakin?',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Jangan',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $.ajax({
                    url: '{{ url('employee-leave') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(resp){
                        location.reload()
                    },
                    error: (error)=>{
                        this.close();
                        console.error(error);
                        showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
                    }
                })
            }
        });
    }
</script>
@endsection
