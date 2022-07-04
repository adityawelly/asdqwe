@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Import Quota Check</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Mohon check data sebelum submit</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form action="{{ route('leave.quota_import_save') }}" method="post">
                                @csrf
                                <input type="hidden" name="reset_data" value="{{ $reset_data }}">
                                <input type="hidden" name="sessionTmp" value="{{ $sessionTmp }}">
                                <table id="report-leave-table" class="display table-head-bg-primary">
                                    <thead>
                                        <tr>
                                            <th class="content">No</th>
                                            <th class="content">NIK</th>
                                            <th class="content">Start Date</th>
                                            <th class="content">End Date</th>
                                            <th class="content">Kuota</th>
                                            <th class="content">Kuota Terpakai</th>
                                            <th class="content">Kuota Periode Sebelumnya</th>
                                            <th class="content">Opname</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotas as $item)
                                            <tr>
                                                <td class="content">{{ $loop->iteration }}</td>
                                                <td class="content">
                                                    {{ $item->nik }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->start_date }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->end_date }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->kuota }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->kuota_terpakai }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->kuota_periode_sebelumnya }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->opname }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Submit</button>
                            </form>
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
    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });
</script>
@endsection
