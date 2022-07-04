@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Approval Karyawan Bekerja Dari Rumah (<i>Work From Home</i>)</h4>
            {{ Breadcrumbs::render('approve-wfh') }}
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
					
					   <form action="{{ route('employee-wfh.approve_update') }}" method="post" id="formOpname">
                            @csrf
                            <div class="form-group row">
                                <label for="" class="control-label col-md-1">Status</label>
                                <div class="col-md-3">
                                    <select name="status" class="form-control selectpicker" required>
                                        <option></option>
                                        <option value="apv">Approved All</option>
										<option value="rjt">Disapproved All</option>
                                    </select>
                                </div>&nbsp;&nbsp;
                                <label for="" class="control-label col-md-1">Note</label>
                                <div class="col-md-4">
                                    <textarea name="approval_note" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        <div class="table-responsive">
                            <table id="employee-leave-table" width="100%" class="table-head-bg-primary">
                                <thead>
                                    <tr>
										<th><input type="checkbox" name="all_check"></th>
                                        <th class="content">Aksi</th>
                                        <th class="content">Status</th>
                                        <th class="content">Nama Karyawan</th>
                                        <th class="content">Tanggal Mulai</th>
                                        <th class="content">Tanggal Berakhir</th>
                                        <th class="content">Jam Mulai</th>
                                        <th class="content">Jam Berakhir</th>
                                        <th class="content">Tujuan</th>
                                        <th class="content">Hari Kerja</th>
										<th class="content">Hari Libur</th>
                                        <th class="content">Approval Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee_wfh as $employee_wfh)
                                        <tr>
											<td class="content">
                                                    <input type="checkbox" name="ids[]" value="{{ $employee_wfh->id }}">
                                            </td>
                                            <td class="content">
                                                @if ($employee_wfh->status === 'new')
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-bars"></i> Opsi
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="javascript:void(0)" onclick="approve({{ $employee_wfh->id }}, 'apv')" class="dropdown-item"><i class="fas fa-check-circle"></i> Setujui</a>
                                                            <a href="javascript:void(0)" onclick="approve({{ $employee_wfh->id }}, 'rjt')" class="dropdown-item"><i class="fas fa-times-circle"></i> Tolak</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="content">
                                                @switch($employee_wfh->status)
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
                                            <td class="content">{{ $employee_wfh->fullname }}</td>
                                            <td class="content">{{ tgl_indo($employee_wfh->start_date) }}</td>
                                            <td class="content">{{ tgl_indo($employee_wfh->end_date) }}</td>
                                            <td class="content">{{ $employee_wfh->start_time ?? '' }}</td>
                                            <td class="content">{{ $employee_wfh->end_time ?? '' }}</td>
                                            <td class="content">{{ $employee_wfh->reason }}</td>
                                            <td class="content">{{ $employee_wfh->total }} Hari</td>
											<td class="content">{{ $employee_wfh->total_libur }} Hari</td>
                                            <td class="content"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
					  </form>
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
	
	$('input[name=all_check]').on('change', function(){
        if ($(this).attr('checked')) {
            $(this).attr('checked', false);
            $("input[name='ids[]']").attr("checked", false);
        }else{
            $(this).attr("checked", true);
            $("input[name='ids[]']").attr("checked", true);
        }
    });

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
            url: '{{ url('employee-wfh') }}/'+id+'/approve',
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
	
	
	var form = $('#formOpname');

    form.find('button[type=submit]').on('click', function(e){
        e.preventDefault();
        swal({
            titleText: 'Apakah anda yakin?',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.value) {
                $(this).attr('disabled', true).addClass('is-loading');
                form.submit();
            }
        });
    });
</script>
@endsection
