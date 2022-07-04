@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Job</h4>
            {{ Breadcrumbs::render('input-job') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Deskripsi Lowongan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Job Title</dt>
                                    <dd>{{ $pengajuan->job_title_name }}</dd>
                                    <dt>Level Jabatan</dt>
                                    <dd>{{ $pengajuan->grade_title_name }}</dd>
                                    <dt>Departemen</dt>
                                    <dd>{{ $pengajuan->department_name }}</dd>                                  
                                    <dt>Lokasi Kerja</dt>
                                    <dd>{{ $pengajuan->region_city }}</dd>                                  
                                    <dt>Sistem Kerja</dt>
                                    <dd>{{ $pengajuan->working_time }}</dd>
									<dt>Jenis Kelamin</dt>                               
									@if($pengajuan->gender == 'L')
									<dd>Pria</dd>
									@elseif($pengajuan->gender == 'P')
									<dd>Wanita</dd>
									@else
									<dd>Pria / Wanita</dd>
									@endif								
                                </dl>
								<dl>
                                    <dt>Kualifikasi Umum</dt>
                                    <dd>
                                        @foreach ($skill_desc as $item)
                                            {{ $loop->iteration }}. {{ $item->JobReq ?? '-' }} <br>
                                        @endforeach
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
								<dl>
                                    <dt>Kualifikasi Khusus</dt>
                                    <dd>
                                        @foreach ($job_spec as $item)
                                            {{ $loop->iteration }}. {{ $item->JobSpec ?? '-' }} <br>
                                        @endforeach
                                    </dd>
                                </dl>
								<dl>
                                    <dt>Uraian Pekerjaan</dt>
                                    <dd>
                                        @foreach ($job_desc as $item)
                                            {{ $loop->iteration }}. {{ $item->JobDesc ?? '-' }} <br>
                                        @endforeach
                                    </dd>
                                </dl>
								
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            Status Lowongan
                                        </h4>
                                    </div>
                                    <div class="card-body">
										@if($pengajuan->status == 0)
                                        <form action="{{ route('job.close') }}" method="post" onsubmit="return confirm('Anda yakin, hal ini tidak akan bisa dikembalikan ?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                                            <div class="form-group">
                                                <label for="">Status</label>
                                                    <select name="status" class="form-control selectpicker" required>
				                                    <option></option>
				                                    <option value="0">Open</option>
				                                    <option value="1">Close</option>
				                                </select>
                                            </div>
                                            <button class="btn btn-danger"><i class="fa fa-times"></i>Simpan</button>
                                        </form>
										@else
										<h2><b>Closed {{ date('d-m-Y H:i', strtotime($pengajuan->deadline)) }}</b></h2>
										@endif
                                    </div>
                                </div>
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

        $(document).ready(function(){
            countBoth();
        });

        $('form').on('submit', function(){
            $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
        });

        var OutStandMale = $('input[name=OutStandMale]');
        var OutStandFemale = $('input[name=OutStandFemale]');

        function countBoth() {
            var firstVal = OutStandMale.val();
            var secondVal = OutStandFemale.val();
            firstVal = firstVal == '' ? 0:parseInt(firstVal);
            secondVal = secondVal == '' ? 0:parseInt(secondVal);
            $('input[name=OutStandBoth]').val(firstVal+secondVal);
        }

        OutStandMale.on('input', function(){
            countBoth();
        });

        OutStandFemale.on('input', function(){
            countBoth();
        });
    </script>
    @endsection