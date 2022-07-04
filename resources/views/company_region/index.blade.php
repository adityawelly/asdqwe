@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Lokasi Kerja</h4>
            {{ Breadcrumbs::render('company-region') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('create-company-region')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Lokasi Kerja
                                </button>
                            @endcan
                            @can('export-company-region')
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('company-region.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                        <a class="dropdown-item" href="{{ route('company-region.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                        <a class="dropdown-item" href="{{ route('company-region.pdf') }}"><i class="far fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="company-region-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kota</th>
                                        <th>Alamat Regional</th>
                                        @can('delete-company-region')
                                            <th>Status</th>
                                        @endcan
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company_regions as $company_region)
                                        <tr>
                                            <td>{{ $company_region->id }}</td>
                                            <td>{{ $company_region->region_city }}</td>
                                            <td>{{ $company_region->region_address }}</td>
                                            @can('delete-company-region')
                                                <td>{!! $company_region->trashed() ? '<span class="badge badge-danger">Terhapus</span>':'<span class="badge badge-primary">Tersedia</span>' !!}</td>
                                            @endcan
                                            <td>
                                                @if ($company_region->trashed())
                                                    @can('restore-company-region')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Kembalikan" class="btn btn-icon btn-round btn-sm btn-success" onclick="restore('{{ $company_region->id }}', this)">
                                                            <i class="fas fa-recycle"></i>
                                                        </button>
                                                    @endcan
                                                @else
                                                    @can('update-company-region')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit('{{ $company_region->id }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-company-region')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $company_region->id }}', this, false)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                @endif
                                                @can('restore-company-region')
                                                    <button type="button" 
                                                        data-toggle="tooltip" data-placement="top" title="Hapus Permanen" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $company_region->id }}', this, true)">
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
            region_city: "required",
            region_address: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#company-region-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('company-region') }}');
        modal.find('.modal-title').text('Tambah Lokasi Kerja');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('company-region') }}/'+id);
        $.ajax({
            url: '{{ url('company-region') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('input[name=region_city]').val(resp.region_city);
                form.find('textarea[name=region_address]').val(resp.region_address);
                modal.find('.modal-title').text('Edit Lokasi Kerja');
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
                    url: '{{ url('company-region') }}/'+id,
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
                    url: '{{ url('company-region/restore') }}/'+id,
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
                            <label for="">Kota <span class="required-label">*</span></label>
                            <input type="text" name="region_city" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Alamat Regional <span class="required-label">*</span></label>
                            <textarea rows="3" name="region_address" class="form-control"></textarea>
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