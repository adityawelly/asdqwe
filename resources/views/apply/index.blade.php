@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Pelamar</h4>
            {{ Breadcrumbs::render('data-pelamar') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data Pelamar</h4>
                            @php
                                if (auth()->user()->hasRole('Super Admin')) {
                                    $grade_title_code = 'Admin';
                                }else{
                                    $grade_title_code = auth()->user()->employee->grade_title->grade_title_code;
                                }
                            @endphp
                            <button type="button" class="btn btn-success btn-round ml-2" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-file-export"></i> Export Data
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
					   <form class="form-horizontal" action="" method="GET">
                            <div class="form-group">
                                <label for="" class="label col-md-3">Filter Status Pelamar</label>
                                <div class="col-md-3">
                                    <select name="status_data" id="status-data" class="form-control selectpicker">
                                        <option></option>
                                        @foreach ($sp as $sp)
                                            <option value="{{ $sp->kode }}" {{ request()->status_data == $sp->kode ?'selected':'' }}>{{ $sp->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
								<label for="" class="label col-md-3">Posisi Yang Dilamar</label>
                                <div class="col-md-3">
                                    <select name="id_job" id="id-job" class="form-control selectpicker">
                                        <option></option>
										@foreach ($sr as $sr)
                                            <option value="{{ $sr->id }}" {{ request()->id_job == $sr->id ?'selected':'' }}>{{ $sr->job_title_name . ' (' . $sr->region_city .')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table-head-bg-primary" id="dttable" width="100%">
                                <thead>
                                    <tr>
									    <th class="content">Sts</th>
                                        <th class="content">Status</th>
										<th class="content">Opsi</th>
                                        <th class="content">Nama Pelamar</th>
                                        <th class="content">Kontak</th>
                                        <th class="content">Posisi Yang di Lamar</th>
										<th class="content">Region</th>
                                        <th class="content">Tanggal Upload CV</th>
										<th class="content">Tanggal Dilihat</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @foreach ($pengajuans as $item)
                                        <tr {{ $item->read == 0 ? "style=font-style:italic" : '' }} >
											<td class="content">{{ $item->insert_date }}</td>
                                            <td class="content">
											@if($item->status_data == 'BD')
                                                <span class="badge badge-primary">Belum Diproses</span>
											@elseif($item->status_data == 'WC')
											    <span class="badge badge-warning">Wawancara</span>
											@elseif($item->status_data == 'TS')
											    <span class="badge badge-danger">Tidak Sesuai</span>
											@else
												<span class="badge badge-success">Terpilih</span>
											@endif
                                            </td>
                                            <td class="content">
                                                <div class="btn-group-vertical">
                                                    <a href="{{ route('apply.detail', ['id' => $item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                    @can('modify-apply')
                                                        <form action="{{ route('apply.remove') }}" method="post" onsubmit="return confirm('Apa anda yakin ? Hal ini tidak dapat dikembalikan.');">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $item->id }}" required>
                                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td class="content">{{ $item->fullname }}</td>
                                            <td class="content">{{ $item->phone .'-'. $item->email }}</td>
                                            <td class="content">{{ $item->job_title_name }}</td>
											<td class="content">{{ $item->region_city }}</td>
											<td class="content">{{ date('d-F-Y', strtotime($item->insert_date)) }}</td>
											<td class="content">{{ $item->read_date != NULL ? date('d-F-Y (H:i)', strtotime($item->read_date)) : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody> --}}
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
    var url = "/apply";

    var dt = $('#dttable').dataTable({
		"order": [[ 7, "desc" ]],
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            },
        ],
        responsive: true,
        serverSide: true,
        ajax: url,
        columns: [
            {data: "insert_date", name: "insert_date", class: "content"},
            {data: "status_data", name: "status_data", class: "content"},
            {data: "action", name: "action", class: "content"},
            {data: "fullname", name: "fullname", class: "content"},
            {data: "phone", name: "phone", class: "content"},
            {data: "job_title_name", name: "job_title_name", class: "content"},
            {data: "region_city", name: "region_city", class: "content"},
            {data: "insert_date", name: "insert_date", class: "content"},
            {data: "read_date", name: "read_date", class: "content"},
        ]
    }).api();

    function dtableReload(){
        dt.ajax.reload(function(){console.log('reload url')}, false); //Reload isi datatables
    }

    //ini untuk filter
    //BEGIN::Filter
    $(document).on("change", "#id-job, #status-data", function(){ //Setiap ada perubahan pada #id-job dan atau #status-data
        url = "/apply?status_data=" + $("#status-data").val() + "&id_job=" + $("#id-job").val(); //Ambil Value

        dt.ajax.url( url ).load(); //Reload URL pada Datatables dengan variabel dt
        dtableReload();
    });
    //END::Filter

    var id;
    //BEGIN::Hapus record
    $(document).on("click", ".delete", function(e){
        e.preventDefault();
        id = $(this).attr('id');

        $("#hapus-modal").modal("show");
    });

    $('#hapus-form').on('submit', function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/apply/remove/" + id,
            cache: false,
            method: "DELETE",
            dataType: "json",
            success:function(data)
            {
                if(data.success){
                    toastr.options = {
                        "positionClass": "toast-top-center",
                    };
                    toastr.success(data.success);
                }
            },
            error:function(data){
                console.log(data);
            },
            complete:function(data){
                if(JSON.parse(data.responseText).success){
                    $('#hapus-modal').modal('hide');
                    dtableReload();
                }
            }
        });
    })
    //END::Hapus record
</script>
@endsection

@section('modals')
<div class="modal fade" id="hapus-modal" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="hapus-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus</h5>
            </div>
            <form id="hapus-form">
                <div class="modal-body">
                    <p>
                        Tekan tombol <span class="text-danger">Hapus</span>, jika anda yakin untuk menghapus data.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Export Data Pelamar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('apply.export') }}" method="post" id="exportFormModal">
                    @csrf
                    <div class="form-group">
                        <label for="">Dari Tanggal</label>
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input type="text" class="form-control datepicker" name="end_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Job Title<span class="required-label">*</span></label>
                        <select name="job_id" class="form-control selectpicker" required style="width: 100%">
                        <option value='all'>All</option>
						@foreach ($job as $item)
                            <option value="{{ $item->job_id }}">{{ $item->job_title_code.'-'.$item->job_title_name }}</option>
                        @endforeach
                        </select>
                    </div>
					<div class="form-group">
                        <label for="">Status Pelamar<span class="required-label">*</span></label>
                        <select name="status_data" class="form-control selectpicker" required style="width: 100%">
							<option value='all'>All</option>
                            <option value='BD'>Belum Diproses</option>
							<option value='TS'>Tidak Sesuai</option>
							<option value='WC'>Wawancara</option>
							<option value='TP'>Terpilih</option>
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
