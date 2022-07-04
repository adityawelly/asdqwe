@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Daftar Cuti Opname</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            <a class="btn btn-sm btn-success btn-round ml-auto" href="{{ route('leave.opname_export') }}">
                                <i class="far fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('leave.opname_update') }}" method="post" id="formOpname">
                            @csrf
                            <div class="form-group row">
                                <label for="" class="control-label col-md-1">Pilih Status</label>
                                <div class="col-md-4">
                                    <select name="status" class="form-control selectpicker" required>
                                        <option></option>
                                        <option value="paid">Potong Gaji</option>
                                    </select>
                                </div>
                                <label for="" class="control-label col-md-1">Keterangan</label>
                                <div class="col-md-4">
                                    <textarea name="note" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table-head-bg-primary datatables" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="all_check"></th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Note</th>
											<th>Potong Gaji</th>
                                            <th>Dibuat Pada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($opnames as $item)
                                            <tr>
                                                <td class="content">
                                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}">
                                                </td>
                                                <td class="content">{{ $item->employee_no }}</td>
                                                <td class="content">{{ $item->fullname }}</td>
                                                <td class="content">{{ date('d-m-Y', strtotime($item->start_date)) }}</td>
                                                <td class="content">{{ date('d-m-Y', strtotime($item->end_date)) }}</td>
                                                <td class="content">{{ $item->qty }}</td>
                                                <td class="content">
                                                    {!! opname_status_text($item->status) !!}
                                                    {!! resign_status_text($item->employee_id ? 'rsgn':'') !!}
                                                </td>
                                                <td class="content">{{ $item->note }}</td>
												<td class="content">{{ $item->potong_gaji }}</td>
                                                <td class="content">{{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</td>
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
    var dt = $('.datatables').dataTable({
        paging: false,
        columnDefs: [
            { "orderable": false, "targets": 0 }
        ],
        order: [],
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