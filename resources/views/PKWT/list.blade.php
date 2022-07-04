@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Perjanjian Kerja Waktu Tertentu (PKWT)</h4>
            {{ Breadcrumbs::render('departemen') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Buat PKWT
                                </button>
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#"><i class="far fa-file-excel"></i> Excel</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-database"></i> CSV</a>
                                        <a class="dropdown-item" href="#"><i class="far fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="list-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
									    <th>Status</th>
                                        <th>No PKWT</th>
                                        <th>Nama Karyawan</th>
                                        <th>Kontrak Ke</th>
                                        <th>Masa Kontrak</th>
                                        <th>Job Title</th>                                      
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pkwt as $pkwt)
                                        <tr>
										   <td>
										     @if($pkwt->kontrak_ke == 1)
                                                 <span class="badge badge-primary">Baru</span>
											 @else
                                                 <span class="badge badge-success">Perpanjangan</span>
                                             @endif
											</td>
                                            <td>{{ $pkwt->pkwt_no }}</td>
                                            <td>{{ $pkwt->employee->fullname }}</td>
                                            <td>{{ $pkwt->kontrak_ke }}</td>
                                            <td>{{ tgl_indo($pkwt->sdate) .' s/d '. tgl_indo($pkwt->edate) }}</td>
                                            <td>{{ $pkwt->job_title_id .' - '. $pkwt->job->job_title_name }}</td>
                                            <td> 
											<div class="btn-group-vertical">
											  <a href="{{ route('pengajuan.fpk.cetak_pkwt', ['id' => $pkwt->fpk_id]) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> Cetak</a> </td>
                                            </div>
											<td>
                                             
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
    var modal = $('#pageModal');
    var form = $('#formModal');

    var validatedForm = form.validate({
        rules: {
            list_code: "required",
            list_name: "required",
            division_id: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#list-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('list') }}');
        modal.find('.modal-title').text('Buat PKWT');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('list') }}/'+id);
        $.ajax({
            url: '{{ url('list') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('select[name=employee_id]').val(resp.division_id).trigger('change');
                form.find('input[name=sdate]').val(resp.list_code);
                form.find('input[name=edate]').val(resp.list_name);
                modal.find('.modal-title').text('Edit PKWT');
                form.append('@method('PUT')');
                modal.modal('toggle');
            },
            error: function(error){
                console.error(error);
                showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
            },
            complete: function(){
                $(el).removeClass('is-loading').attr('disabled', false);
            }
        })
    }
    
    function remove(id, el, flag) {
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
                $(el).addClass('is-loading').attr('disabled', true);
                $.ajax({
                    url: '{{ url('list') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _method: 'DELETE',
                        force: flag
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
 
    modal.on("hidden.bs.modal", function (e) {
        form.trigger('reset');
        validatedForm.resetForm();
    });
</script>
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="pageModal" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" id="formModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Pilih Karyawan<span class="required-label">*</span></label>
                            <select name="employee_id" class="form-control selectpicker" style="width:100%">
                                <option></option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Awal Kontrak<span class="required-label">*</span></label>
                            <input type="text" name="sdate" class="form-control datepicker">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Akhir Kontrak<span class="required-label">*</span></label>
                            <input type="text" name="edate" class="form-control datepicker">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection