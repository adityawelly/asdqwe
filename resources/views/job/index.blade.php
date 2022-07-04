@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Input Job Vacancy</h4>
            {{ Breadcrumbs::render('input-job') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @php
                                if (auth()->user()->hasRole('Super Admin')) {
                                    $grade_title_code = 'Admin';
                                }else{
                                    $grade_title_code = auth()->user()->employee->grade_title->grade_title_code;
                                }
                            @endphp
                            
                            <a class="btn btn-primary btn-round ml-auto" href="{{ route('job.create') }}">
                                <i class="fa fa-plus"></i>
                                Post Job
                            </a>
                            <button type="button" class="btn btn-success btn-round ml-2" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-file-export"></i> Export Job
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-head-bg-primary" id="dttable" width="100%">
                                <thead>
                                    <tr>
										<th rowspan="2" class="content">Sts</th>
                                        <th rowspan="2" class="content">Status</th>
                                        <th rowspan="2" class="content">Opsi</th>
                                        <th rowspan="2" class="content">Job Id</th>
                                        <th rowspan="2" class="content">Job Name</th>
                                        <th rowspan="2" class="content">Lokasi Kerja</th> 
										<th colspan="4" class="content" style="text-align: center;" >Jumlah Pelamar</th>										
                                        <th rowspan="2" class="content">Tgl Dibuat</th>
                                    </tr>
									<tr>
                                        <th class="content" style="text-align: center;">Belum Diproses</th>
                                        <th class="content" style="text-align: center;">Wawancara</th>
                                        <th class="content" style="text-align: center;">Terpilih</th>
                                        <th class="content" style="text-align: center;">Tidak Sesuai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $item)
                                        <tr>
											<td class="content">{{ $item->status }}</td>
                                            <td class="content">
												@if($item->status == 0)
                                                <span class="badge badge-primary">Open</span>
												@else
												<span class="badge badge-danger">Close</span>
												@endif
                                            </td>
                                            <td class="content">
                                                <div class="btn-group-vertical">
													<a href="{{ route('job.detail', ['id' => $item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
													@if($item->status == 0)
                                                    <a href="{{ route('job.edit', ['id' => $item->id]) }}" class="btn btn-sm btn-default"><i class="fa fa-pen"></i> Ubah</a>                                                                                                       
														@can('modify-job')
															<form action="{{ route('job.remove') }}" method="post" onsubmit="return confirm('Apa anda yakin ? Hal ini tidak dapat dikembalikan.');">
																@csrf
																<input type="hidden" name="id" value="{{ $item->id }}" required>
																<button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus</button>
															</form>
														@endcan
													@endif
                                                </div>
                                            </td>
                                            <td class="content">{{ $item->id }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
                                            <td class="content">{{ $item->region_city }}</td>
											<td style="color: blue; text-align: center;" class="content">{{ $item->views .'  Pelamar' }}</td>
											<td style="color: orange; text-align: center;" class="content">{{ $item->wawancara .'  Pelamar' }}</td>
											<td style="color: green; text-align: center;" class="content">{{ $item->terpilih .'  Pelamar' }}</td>
											<td style="color: red; text-align: center;" class="content">{{ $item->tidak_sesuai .'  Pelamar' }}</td>
                                            <td class="content">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
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
        responsive: true,
    }).api();   
</script>
@endsection

@section('modals')
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Export Job List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.export') }}" method="post" id="exportFormModal">
                    @csrf
                    <div class="form-group">
                        <label for="">Input Dari</label>
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input type="text" class="form-control datepicker" name="end_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Status <span class="required-label">*</span></label>
                        <select name="status" class="form-control selectpicker" required style="width: 100%">
                            <option></option>
                            <option value="all">All</option>
                            <option value="0">Open</option>
                            <option value="1">Close</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection