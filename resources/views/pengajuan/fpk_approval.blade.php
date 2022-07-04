@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Approval FPK</h4>
            {{ Breadcrumbs::render('pengajuan-fpk') }}
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
                                        <th class="content">Status</th>
                                        <th class="content">Opsi</th>
                                        <th class="content">Tgl Diajukan</th>
                                        <th class="content">ID</th>
                                        <th class="content">No FPK</th>
										<th class="content">NIK</th>
                                        <th class="content">Name</th>
                                        <th class="content">Perihal</th>
                                        <th class="content">Department</th>
                                        <th class="content">Jabatan</th>
                                        <th class="content">Diajukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)
                                    <tr>
                                            <td class="content">
												 @if ($item->Flag_proses == 1 && $item->ApprovedAll == 0)
                                                     <span class="badge badge-warning" >[Waiting Approval]</span>
												 @elseif ($item->Flag_proses == 2 && $item->ApprovedAll == 0)
													 <span class="badge badge-success" >[Open]</span>
												 @elseif ($item->Flag_proses == 2 && $item->ApprovedAll == 1)
													 <span class="badge badge-primary" >[Approved All]</span>
												 @elseif ($item->Flag_proses == 2 && $item->ApprovedAll == 2)
													 <span class="badge badge-danger" >[No Approved]</span>													 
                                                 @endif
                                            </td>
                                            <td class="content">
                                                <div class="btn-group-vertical">
                                                    @if ($item->Flag_proses <> 0)
                                                       <a href="{{ route('pengajuan.fpk.detail', ['id' => $item->ReqId]) }}" class="btn btn-sm btn-primary">Detail</a> <br>
			                                           <span class='badge badge-danger'>Waiting Your Approval</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->Insert_date)) }}</td>
                                            <td class="content">{{ $item->ReqId }}</td>
                                            <td class="content">{{ $item->fpk_no }}</td>
                                            <td class="content">{{ $item->employee_id }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
											<td class="content">
											@if ($item->promosi == 1){{ "Promosi"  }} <br> @endif
											@if ($item->demosi == 1){{ "Demosi"  }} <br> @endif
											@if ($item->mutasi == 1){{ "Mutasi"  }} <br> @endif
											@if ($item->perubahan_job == 1){{ "Perubahan Job Title"  }} <br> @endif
											@if ($item->perubahan_status == 1){{ "Perubahan Status"  }} <br> @endif
											@if ($item->penyesuaian_comben == 1){{ "Penyesuaian Comben"  }} <br> @endif
											@if ($item->perpanjangan_kontrak == 1){{ "Perpanjangan Kontrak"  }} <br> @endif
											@if ($item->habis_kontrak == 1){{ "Habis Kontrak"  }} <br> @endif
											</td>
                                            <td class="content">{{ $item->department_name }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
											<td class="content">{{ $item->nama_creator }}</td>
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
        // responsive: true,
    }).api();
</script>
@endsection