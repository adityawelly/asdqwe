@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Approval PA</h4>
            {{ Breadcrumbs::render('PAFORM') }}
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
                                        <th class="content">ID</th>
										<th class="content">NIK</th>
                                        <th class="content">Nama</th>
                                        <th class="content">Periode</th>
                                        <th class="content">Tahun / Semester</th>
										<th class="content">Department</th>
                                        <th class="content">Jabatan</th>
                                        <th class="content">Atasan Langsung</th>
										<th class="content">Batas Waktu Penilaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)
                                    <tr>
                                            <td class="content">
												 @if ($item->ReqSts == 1 && $item->ApprovedAll == NULL)
                                                     <span class="badge badge-warning" >[Belum Dinilai]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 0)
													 <span class="badge badge-success" >[Menunggu Persetujuan]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 1)
													 <span class="badge badge-primary" >[Disetujui]</span>
												 @elseif ($item->ReqSts == 2 && $item->ApprovedAll == 2)
													 <span class="badge badge-danger" >[Ditolak]</span>													 
                                                 @endif
                                            </td>
                                            <td class="content">
                                                <div class="btn-group-vertical">
                                                    @if ($item->ReqSts <> 0)
                                                       <a href="{{ route('PAForm.detail', ['id' => $item->PaId]) }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a> <br>
			                                           <span class='badge badge-danger'>Waiting Your Approval</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="content">{{ $item->PaId }}</td>
                                            <td class="content">{{ $item->employee_id }}</td>
                                            <td class="content">{{ $item->fullname }}</td>
											<td class="content">{{ $item->periode_name }}</td>
											<td class="content">{{ $item->tahun .' / '. $item->semester }}</td>
                                            <td class="content">{{ $item->department_name }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
											<td class="content">{{ $item->nama_atasan }}</td>
											<td class="content">{{ $item->edate }}</td>
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