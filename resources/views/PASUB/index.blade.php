@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Parameters PA</h4>
            {{ Breadcrumbs::render('PASUB') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Parameters PA</h4>
                            @can('create-pasub')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Parameters
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table Id="PASUB-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Bab</th> 
										<th>Nama Parameters</th> 
										<th>Grade</th>
										<th>Bobot</th>										
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($PASUB as $PASU)
                                        <tr>
                                            <td>{{ $PASU->id }}</td>
                                            <td>{{ $PASU->NamaBab }}</td>
											<td>{{ $PASU->Namasub }}</td>
											<td>{{ $PASU->grade_title_name }}</td>
											<td>{{ $PASU->Bobot }} %</td>
                                            <td>
												 @if($PASU->Status == 1)
													 <span class="badge badge-success" >Active</span>
												 @else
													 <span class="badge badge-danger" >Non Active</span>												 
                                                 @endif
											</td>
                                            <td>    
													<a href="{{ route('PASUB.detail', ['id' => $PASU->id]) }}" title="Clausul" class="btn btn-icon btn-round btn-sm btn-warning"><i class="fa fa-file-contract"></i></a>											
                                                    @can('update-pasub')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit('{{ $PASU->id }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-pasub')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $PASU->id }}', this, false)">
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
            Namasub: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#PASUB-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('PASUB') }}');
        modal.find('.modal-title').text('Tambah sub Parameters');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('PASUB') }}/'+id);
        $.ajax({
            url: '{{ url('PASUB') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('input[name=Namasub]').val(resp.Namasub);
				form.find('select[name=GradeId]').val(resp.GradeId).trigger('change');
				form.find('select[name=babid]').val(resp.babid).trigger('change');
				form.find('input[name=Bobot]').val(resp.Bobot);				
                modal.find('.modal-title').text('Edit Parameters');
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
                    url: '{{ url('PASUB') }}/'+id,
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
                    url: '{{ url('PASUB/restore') }}/'+id,
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
    <div class="modal fade" Id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" Id="modal">Edit Parameter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" Id="formModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Nama Parameters <span class="required-label">*</span></label>
                            <input type="text" name="Namasub" class="form-control">
                        </div>					
						<div class="form-group">
                            <label for="">Grade <span class="required-label">*</span></label>
                            <select name="GradeId" class="form-control selectpicker" style="width:100%">
                                <option></option>
                                @foreach ($grade as $grade)
                                    <option value="{{ $grade->id }}" {{ request()->GradeId == $grade->id ?'selected':'' }}>{{ $grade->grade_title_code.'-'.$grade->grade_title_name }}</option>
                                @endforeach
                            </select>
                        </div>
						<div class="form-group">
                            <label for="">Category <span class="required-label">*</span></label>
                            <select name="babid" class="form-control selectpicker" style="width:100%">
                                <option></option>
                                @foreach ($bab as $bab)
                                    <option value="{{ $bab->BabId }}">{{ $bab->NamaBab }}</option>
                                @endforeach
                            </select>
                        </div>
						<div class="form-group">
                            <label for="">Bobot Parameters <span class="required-label">*</span></label>
                            <input type="text" name="Bobot" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select class="form-control select2" name="status">
								<option value="1">Active</option>
								<option value="0">Non Active</option>
							</select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" Id="btnSubmit"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection