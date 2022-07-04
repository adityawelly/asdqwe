@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Grade Title</h4>
            {{ Breadcrumbs::render('grade-title') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('create-grade-title')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Grade Title
                                </button>
                            @endcan
                            @can('export-grade-title')
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('grade-title.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                        <a class="dropdown-item" href="{{ route('grade-title.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                        <a class="dropdown-item" href="{{ route('grade-title.pdf') }}"><i class="far fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="grade-title-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kode Grade Title</th>
                                        <th>Nama Grade Title</th>
                                        <th>Deskripsi Grade Title</th>
                                        @can('delete-grade-title')
                                            <th>Status</th>
                                        @endcan
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grade_titles as $grade_title)
                                        <tr>
                                            <td>{{ $grade_title->id }}</td>
                                            <td>{{ $grade_title->grade_title_code }}</td>
                                            <td>{{ $grade_title->grade_title_name }}</td>
                                            <td>{{ $grade_title->grade_title_description }}</td>
                                            @can('delete-grade-title')
                                                <td>{!! $grade_title->trashed() ? '<span class="badge badge-danger">Terhapus</span>':'<span class="badge badge-primary">Tersedia</span>' !!}</td>
                                            @endcan
                                            <td>
                                                @if ($grade_title->trashed())
                                                    @can('restore-grade-title')
                                                    <button type="button" 
                                                        data-toggle="tooltip" data-placement="top" title="Kembalikan" class="btn btn-icon btn-round btn-sm btn-success" onclick="restore('{{ $grade_title->id }}', this)">
                                                        <i class="fas fa-recycle"></i>
                                                    </button>
                                                    @endcan
                                                @else
                                                    @can('update-grade-title')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit('{{ $grade_title->id }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-grade-title')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $grade_title->id }}', this, false)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                @endif
                                                @can('restore-grade-title')
                                                    <button type="button" 
                                                        data-toggle="tooltip" data-placement="top" title="Hapus Permanen" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $grade_title->id }}', this, true)">
                                                        <i class="fas fa-window-close"></i>
                                                    </button>
                                                @endcan
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
            grade_title_code: "required",
            grade_title_name: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#grade-title-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('grade-title') }}');
        modal.find('.modal-title').text('Tambah Grade Title');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('grade-title') }}/'+id);
        $.ajax({
            url: '{{ url('grade-title') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('input[name=grade_title_code]').val(resp.grade_title_code);
                form.find('input[name=grade_title_name]').val(resp.grade_title_name);
                form.find('input[name=grade_title_description]').val(resp.grade_title_description);
                modal.find('.modal-title').text('Edit Grade Title');
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
                    url: '{{ url('grade-title') }}/'+id,
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

    function restore(id, el) {
        swal({
            titleText: 'Apakah anda yakin?',
            text: "Data akan dikembalikan",
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya, kembalikan!',
            cancelButtonText: 'Jangan',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $(el).addClass('is-loading').attr('disabled', true);
                $.ajax({
                    url: '{{ url('grade-title/restore') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
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
     <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
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
                            <label for="">Kode Grade Title <span class="required-label">*</span></label>
                            <input type="text" name="grade_title_code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Grade Title <span class="required-label">*</span></label>
                            <input type="text" name="grade_title_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Deskripsi Grade Title</label>
                            <input type="text" name="grade_title_description" class="form-control">
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