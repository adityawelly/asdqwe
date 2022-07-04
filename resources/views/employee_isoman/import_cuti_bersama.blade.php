@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Import Cuti Bersama</h4>
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
                            <form action="{{ route('employee-leave.cuti_upload_do') }}" method="post">
                                @csrf
                                <input type="hidden" name="sessionTmp" value="{{ $sessionTmp }}">
                                <table id="report-leave-table" class="display table-head-bg-primary">
                                    <thead>
                                        <tr>
                                            <th class="content">No</th>
                                            <th class="content">NIK</th>
                                            <th class="content">Tanggal Cuti Bersama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $importData = session()->get($sessionTmp);
                                        @endphp
                                        @foreach ($importData as $item)
                                            <tr>
                                                <td class="content">{{ $loop->iteration }}</td>
                                                <td class="content">
                                                    {{ $item->nik }}
                                                </td>
                                                <td class="content">
                                                    {{ $item->date }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Submit</button>
                                </div>
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
