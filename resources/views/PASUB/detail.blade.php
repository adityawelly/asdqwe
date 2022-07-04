@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
		@foreach($PASUB as $PASUB)
            <h4 class="page-title">Skala Parameters 
				@if ($PASUB->GradeId == 5)
					<span class="badge badge-success">{{ $PASUB->grade_title_name }}</span>
				@elseif ($PASUB->GradeId == 4)
					<span class="badge badge-primary">{{ $PASUB->grade_title_name }}</span>
				@else
					<span class="badge badge-danger">{{ $PASUB->grade_title_name }}</span>
				@endif
			</h4>
		@endforeach
            {{ Breadcrumbs::render('PASUB') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Skala Parameters {{ $PASUB->Namasub }}</h4>
							@can('create-pasub')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Skala
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
					<table id="pkwt-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Parameters</th>                                       
                                        <th>Deskripsi</th> 
										<th class="content">Grade</th>
										<th class="content">Nilai</th>
										<th>Aksi</th>										
                                    </tr>
                                </thead>
                                <tbody>
								@foreach($PADTL as $PADTL)
                                    <tr>
                                        <td>{{ $PADTL->ParamsId }}</td>
                                        <td>{{ $PADTL->Namasub }}</td>
                                        <td>{!! $PADTL->Parameters !!}</td> 
										<td>
										@if ($PADTL->GradeId == 5)
											<span class="badge badge-success">{{ $PADTL->grade_title_name }}</span>
										@elseif ($PADTL->GradeId == 4)
											<span class="badge badge-primary">{{ $PADTL->grade_title_name }}</span>
										@else
											<span class="badge badge-danger">{{ $PADTL->grade_title_name }}</span>
										@endif
										</td>
										<td>{{ $PADTL->Nilai }}</td>
										
										<td>
													@can('update-pasub')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit('{{ $PADTL->ParamsId }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-pasub')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $PADTL->ParamsId }}', this, false)">
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
							
    @endsection

    @section('script')
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
	<script type="text/javascript">
			$(document).ready(function(e) {
			  $('.summernote').summernote();
			});
	</script>
    <script>
    var modal = $('#pageModal');
    var form = $('#formModal');

    var validatedForm = form.validate({
        rules: {           
			Nilai : "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#pkwt-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('PADTL') }}');
        modal.find('.modal-title').text('Tambah Skala PA');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('PADTL') }}/'+id);
        $.ajax({
            url: '{{ url('PADTL') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('input[name=Nilai]').val(resp.Nilai);
				form.find('#isi').val(resp.Parameters);
                modal.find('.modal-title').text('Edit Skala PA');
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
                    url: '{{ url('PADTL') }}/'+id,
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
                    url: '{{ url('PADTL/restore') }}/'+id,
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
						<input type="hidden" name="id" value="{{ $PASUB->id }}">
						<input type="hidden" name="GradeId" value="{{ $PASUB->GradeId }}">
						<input type="hidden" name="Bobot" value="{{ $PASUB->Bobot }}">
						<input type="hidden" name="BabId" value="{{ $PASUB->babid }}">
                        <div class="form-group">
                            <label for="">Nilai<span class="required-label">*</span></label>
                            <input type="number" name="Nilai" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Deskripsi</label>
                            <textarea name="Parameters" id="isi" class="form-control"></textarea>
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