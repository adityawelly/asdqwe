@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Periode Penilaian</h4>
            {{ Breadcrumbs::render('periode') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data Periode</h4>
                            @can('create-pasub')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Periode
                                </button>
                            @endcan      
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="paperiode-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">ID</th>
                                        <th class="content">Nama Periode</th>
                                        <th class="content">Tahun</th>
										<th class="content">Semester</th>
                                        <th class="content">Status</th>
										<th class="content">Generate Form</th>
                                        <th class="content">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paperiode as $paperiode)
                                        <tr>
                                            <td>{{ $paperiode->id }}</td>
                                            <td>{{ $paperiode->periode_name }}</td>
                                            <td>{{ $paperiode->tahun }}</td>
											<td>@if($paperiode->semester == 1) I @else II @endif</td>
                                            <td align="center">
											@if ($paperiode->status == 1)
                                               <span class="badge badge-success" >[Open]</span>
										    @else
											   <span class="badge badge-danger" >[Close]</span>
										    @endif
											</td>
											<td align="center">
											@if ($paperiode->status == 1)
											 <a href="{{ route('PAPeriode.generate', ['id' => $paperiode->id]) }}" target="_self" class="btn btn-sm btn-secondary" onclick="return confirm('Anda Yakin Akan Generate PA?');"><i class="fa fa-check"></i> Generate Form PA</a>
                                            @else
											 <a href="{{ route('PAPeriode.generate', ['id' => $paperiode->id]) }}" target="_self" class="btn btn-sm btn-secondary" onclick="return confirm('Anda Yakin Akan Update PA?');"><i class="fa fa-check"></i> Update Form PA</a>
											@endif
											</td>
											<td>
                                            @can('update-pasub')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit('{{ $paperiode->id }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                           @endcan
                                           @can('delete-pasub')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $paperiode->id }}', this, false)">
                                                            <i class="fas fa-trash-alt"></i>
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
            periode_name: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#paperiode-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('PAPeriode') }}');
        modal.find('.modal-title').text('Tambah Periode');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('PAPeriode') }}/'+id);
        $.ajax({
            url: '{{ url('PAPeriode') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
				form.find('select[name=tahun]').val(resp.tahun).trigger('change');
                form.find('select[name=semester]').val(resp.semester).trigger('change');
                form.find('input[name=periode_name]').val(resp.periode_name);
				form.find('input[name=edate]').val(resp.edate);
                modal.find('.modal-title').text('Edit Periode Penilaian');
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
                    url: '{{ url('PAPeriode') }}/'+id,
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
                    url: '{{ url('PAPeriode/restore') }}/'+id,
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
                            <label for="">Nama Periode<span class="required-label">*</span></label>
                            <input type="text" name="periode_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Tahun Periode<span class="required-label">*</span></label>
                            <select name="tahun" class="form-control selectpicker" width="100%">
                                 <option></option>
                                   @foreach ($years as $year)
                                      <option value="{{ $year }}" {{ request()->tahun == $year ?'selected':'' }}>{{ $year }}</option>
                                   @endforeach
                           </select>
                        </div>
                        <div class="form-group">
                            <label for="">Semester Periode</label>
                            <select name="semester" class="form-control selectpicker" width="100%">
                                 <option value="1">I</option>
                                 <option value="2">II</option>
                           </select>
                        </div>
						<div class="form-group">
                            <label for="">Akhir Periode Penilaian</label>
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