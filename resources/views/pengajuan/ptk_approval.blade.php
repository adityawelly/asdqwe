@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Approval PTK</h4>
            {{ Breadcrumbs::render('pengajuan-ptk') }}
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
                            <table class="display table table-head-bg-primary" id="dttable">
                                <thead>
                                    <tr>
									    <th class="content">Sts</th>
                                        <th class="content">Status</th>
                                        <th class="content">Opsi</th>
                                        <th class="content">PTK ID</th>
                                        <th class="content">No</th>
                                        <th class="content">Job Title</th>
                                        <th class="content">Position Level</th>
                                        <th class="content">Department</th>
                                        <th class="content">Jumlah</th>
                                        <th class="content">Requestor</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($arr_data as $item)
										@if($item->CurrentApprovalFlag == 1 && $item->ReqSts == 0)
                                    <tr>
									    <td class="content">{{ $item->ReqSts }}</td>
                                        <td class="content">{!! ptk_status($item->ReqSts) !!}</td>
                                        <td class="content">
                                            <a href="{{ route('pengajuan.ptk.detail', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary">Detail</a> <br/>
                                            @if($item->CurrentApprovalFlag == 1 && $item->ReqSts == 0)
                                            <span class='badge badge-danger'>Waiting Your Approval</span>
                                            @endif
                                        </td>
                                        <td class="content">{{ $item->ReqId }}</td>
                                        <td class="content">{{ $item->ReqNo ?? '-' }}</td>
                                        <td class="content">{{ $item->JobTitle }}</td>
                                        <td class="content">{{ $item->grade_title_name }}</td>
                                        <td class="content">{{ $item->department_name }}</td>
                                        <td class="content">{{ $item->ReqQty }}</td>
                                        <td class="content">{{ $item->fullname }}</td>

                                    </tr>
										@endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
			            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">History Approval PTK</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dttable" class="display table table-head-bg-secondary">
                                <thead>
                                    <tr>
                                        <th class="content">Sts</th>
                                        <th class="content">Status</th>
                                        <th class="content">Opsi</th>
                                        <th class="content">PTK ID</th>
                                        <th class="content">No</th>
                                        <th class="content">Job Title</th>
                                        <th class="content">Position Level</th>
                                        <th class="content">Department</th>
                                        <th class="content">Jumlah</th>
                                        <th class="content">Requestor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($arr_data as $item)
                                    <tr>
									    <td class="content">{{ $item->ReqSts }}</td>
                                        <td class="content">{!! ptk_status($item->ReqSts) !!}</td>
                                        <td class="content">
                                            <a href="{{ route('pengajuan.ptk.detail', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary">Detail</a> <br/>
                                            @if($item->CurrentApprovalFlag == 1 && $item->ReqSts == 0)
                                            <span class='badge badge-danger'>Waiting Your Approval</span>
                                            @endif
                                        </td>
                                        <td class="content">{{ $item->ReqId }}</td>
                                        <td class="content">{{ $item->ReqNo ?? '-' }}</td>
                                        <td class="content">{{ $item->JobTitle }}</td>
                                        <td class="content">{{ $item->grade_title_name }}</td>
                                        <td class="content">{{ $item->department_name }}</td>
                                        <td class="content">{{ $item->ReqQty }}</td>
                                        <td class="content">{{ $item->fullname }}</td>
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
    var dt = $('#dttable').dataTable({
        "order": [[ 0, "asc" ]],
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            },
        ],
		responsive: true
    }).api();
</script>
@endsection