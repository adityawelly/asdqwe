@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Approval Training</h4>
            {{-- {{ Breadcrumbs::render('pengajuan-training') }} --}}
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
                            <table class="display table-head-bg-primary" id="training-submissions-table">
                                <thead>
                                    <tr>
                                        <th class="content">Opsi</th>
                                        <th class="content">Status</th>
                                        <th class="content">Diajukan Oleh</th>
                                        <th class="content">ID</th>
                                        <th class="content">Tipe</th>
                                        <th class="content">Kategori</th>
                                        <th class="content">Nama</th>
                                        <th class="content">Vendor</th>
                                        <th class="content">Dari</th>
                                        <th class="content">Sampai</th>
                                        <th class="content">Durasi</th>
                                        <th class="content">Biaya</th>
                                        <th class="content">Note</th>
                                        <th class="content">File</th>
                                        <th class="content">Reject Note</th>
                                        <th class="content">Peserta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($training_submissions as $training_submission)
                                        <tr>
                                            <td class="content">
                                                @if (in_array($training_submission->status, [10, 5]))
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-bars"></i> Opsi
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="javascript:void(0)" onclick="approval({{ $training_submission->id }}, 'approve')" class="dropdown-item"><i class="fas fa-check-circle"></i> Approve</a>
                                                            <a href="javascript:void(0)" onclick="approval({{ $training_submission->id }}, 'reject')" class="dropdown-item"><i class="fas fa-times-circle"></i> Reject</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="content">{!! status_text($training_submission->status) !!}</td>
                                            <td class="content">{{ $training_submission->submitted_by->fullname ?? '' }}</td>
                                            <td class="content">{{ $training_submission->id }}</td>
                                            <td class="content">{{ $training_submission->type }}</td>
                                            <td class="content">{{ $training_submission->category }}</td>
                                            <td class="content">{{ $training_submission->name }}</td>
                                            <td class="content">{{ $training_submission->vendor }}</td>
                                            <td class="content">{{ $training_submission->start_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $training_submission->end_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $training_submission->duration }}</td>
                                            <td class="content">{{ to_currency($training_submission->cost, 'IDR') }}</td>
                                            <td class="content">{{ $training_submission->note }}</td>
                                            <td class="content">
                                                @if ($training_submission->file)
                                                    <a href="{{ public_path('uploads/training_submissions/'.$training_submission->file) }}">{{ $training_submission->file }}</a>
                                                @else
                                                    Tidak Tersedia
                                                @endif
                                            </td>
                                            <td class="content">{{ $training_submission->reject_note }}</td>
                                            <td class="content">{{ $training_submission->employees->implode('fullname', ',') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var dt = $('#training-submissions-table').dataTable({
        // responsive: true,
    }).api();

    var modal = $('#approvalModal');

    function approval(id, status) {
        if (status == 'approve') {
            swal({
                titleText: 'Apakah anda yakin?',
                type: 'question',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true,
                preConfirm: ()=>{
                    $.ajax({
                        url: '{{ route('pengajuan.training.approve') }}',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            status: 'approve',
                            training_submission_id: id,
                            reject_note: ''
                        },
                        success: function(resp){
                            location.reload();
                        },
                        error: function(error){
                            console.error(error);
                            showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
                        }
                    });
                }
            });
        }else{
            modal.find('input[name=status]').val('reject');
            modal.find('input[name=training_submission_id]').val(id);
            modal.modal('toggle');
        }
    }

    modal.on("hidden.bs.modal", function (e) {
        modal.find('input[name=status]').val('');
        modal.find('input[name=training_submission_id]').val('');
        modal.find('textarea[name=reject_note]').val('');
    });

    var form = $('#formModal');

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

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action="{{ route('pengajuan.training.approve') }}" method="post" id="formModal">
                        @csrf
                        <input type="hidden" name="status">
                        <input type="hidden" name="training_submission_id">
                        <div class="form-group">
                            <label for="">Reject Notes</label>
                            <textarea name="reject_note" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection