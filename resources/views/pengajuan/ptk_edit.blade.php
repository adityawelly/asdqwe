@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Pengajuan PTK</h4>
            {{ Breadcrumbs::render('pengajuan-ptk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Form Permintaan Tenaga Kerja Nomor {{ $pengajuan->ReqNo }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.ptk.submit_edit') }}" method="POST" id="pengajuan_ptk" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">
                            <input type="hidden" name="ReqNo" value="{{ $pengajuan->ReqNo }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Jabatan (Job Title) <span class="required-label">*</span></label>
                                        <select name="JobTitle" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($job_titles as $item)
                                                <option value="{{ $item->id }}" {{ $pengajuan->JobTitle == $item->id ? 'selected':'' }}>{{ $item->job_title_code.'-'.$item->job_title_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Level Jabatan (Position Level) <span class="required-label">*</span></label>
                                        <select name="PositionLevel" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($grade_titles as $item)
                                                <option value="{{ $item->id }}" {{ $pengajuan->PositionLevel == $item->id ? 'selected':'' }}>{{ $item->grade_title_code.'-'.$item->grade_title_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Departemen (Department) <span class="required-label">*</span></label>
                                        <select name="DeptId" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($departments as $item)
                                                <option value="{{ $item->id }}" {{ $pengajuan->DeptId == $item->id ? 'selected':'' }}>{{ $item->department_code.'-'.$item->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lokasi Kerja (Working Location) <span class="required-label">*</span></label>
                                        <select name="WorkLocation" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($company_regions as $item)
                                                <option value="{{ $item->id }}" {{ $pengajuan->WorkLocation == $item->id ? 'selected':'' }}>{{ $item->region_city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Alasan Perekrutan <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($reasonOfHiring as $item)
                                            <input type="radio" name="reasons" value="{{ $item->lookup_value }}" required {{ $pengajuan->ReasonOfHiring == $item->lookup_value ? 'checked':'' }}> {{ $item->lookup_desc }}
                                            <br>
                                        @endforeach
                                        <div class="row" id="ReplacementArea">
                                            <div class="col-md-6">
                                                Diganti:
                                                <br>
                                                @foreach ($replacements as $item)
                                                    {{ $loop->iteration }}. <input type="text" name="replaced[]" placeholder="Tulis Disini" value="{{ $item->EmployeeReplaced }}"><br>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6">
                                                Pengganti:
                                                <br>
                                                @foreach ($replacements as $item)
                                                    {{ $loop->iteration }}. <input type="text" name="replacement[]" placeholder="Tulis Disini" value="{{ $item->EmployeeReplacement }}"><br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Uraian Pekerjaan <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($job_desc as $item)
                                            {{ $loop->iteration }}. 
                                            <input type="text" name="JobDesc[]" placeholder="Tulis Disini" style="width: 90%" value="{{ $item->JobDesc }}"><br>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="">Keahlian Khusus <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($skill_desc as $item)
                                            {{ $loop->iteration }}. 
                                            <input type="text" name="ParticularSkill[]" placeholder="Tulis Disini" style="width: 90%" value="{{ $item->SkillDesc }}"><br>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="">Status Karyawan <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($employeeStatus as $item)
                                            <input type="radio" name="EmploymentStatus" value="{{ $item->lookup_value }}" required 
                                            {{ $pengajuan->EmploymentStatus == $item->lookup_value ? 'checked':'' }}> {{ $item->lookup_desc }}
                                            <br>
                                        @endforeach
                                        Tulis Keterangan Disini:
                                        <br>
                                        <input type="text" name="EmploymentNote" style="width: 100%" value="{{ $pengajuan->EmploymentNote }}">
                                        <br>
                                        <small class="text-muted">Jika kontrak silahkan isi : Cth. 6 bulan atau periode 01-01-2020 s/d 01-06-2020</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Waktu Kerja <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($workingTime as $item)
                                            <input type="radio" name="WorkingTime" value="{{ $item->lookup_value }}" required 
                                            {{ $pengajuan->WorkingTime == $item->lookup_value ? 'checked':'' }}> {{ $item->lookup_desc }}
                                            <br>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="">Grade <span class="required-label">*</span></label>
                                        <select name="Grade" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($gradeOptions as $item)
                                                <option value="{{ $item }}" {{ $pengajuan->Grade == $item ? 'selected':'' }}>{{ strtoupper($item) }}</option>
                                            @endforeach
                                        </select>
                                        <span class="form-text">
                                            <div class="alert-info" style="background-color: #fff;padding-left: 10px;color: #000;">
                                                Keterangan : <br>
                                                III : Manager <br>
                                                IV : Supervisor <br>
                                                V : Staff <br>
                                                VI : Crew <br><br>
    
                                                *harap menghubungi Tim Recruitment (ext 235) untuk berkonsultasi mengenai level karyawan <br>
                                                *level karyawan berpengaruh pada pengalaman & benefit (salary) <br>
                                            </div>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Level <span class="required-label">*</span></label>
                                        <select name="Level" class="form-control selectpicker" required disabled>
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jumlah Orang <span class="required-label">*</span></label>
                                        <input type="text" class="form-control" name="ReqQty" value="{{ $pengajuan->ReqQty }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jenis Kelamin</label>
                                        <br>
                                        <table>
                                            <tr>
                                                <td>Laki</td>
                                                <td><input type="text" name="QtyMale" value="{{ $pengajuan->QtyMale }}" size="5"> orang</td>
                                            </tr>
                                            <tr>
                                                <td>Perempuan</td>
                                                <td><input type="text" name="QtyFemale" value="{{ $pengajuan->QtyFemale }}" size="5"> orang</td>
                                            </tr>
                                            <tr>
                                                <td>Laki-laki/Perempuan (Both)</td>
                                                <td><input type="text" name="QtyBoth" value="{{ $pengajuan->QtyBoth }}" size="5"> orang</td>
                                            </tr>
                                        </table>
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="">Pendidikan <span class="required-label">*</span></label>
                                        <select name="Education" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($last_educationOptions as $option)
                                                <option value="{{ $option }}" {{ $pengajuan->Education == $option ? 'selected':'' }}>{{ strtoupper($option) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jurusan</label>
                                        <input type="text" class="form-control" name="EducationFocus" value="{{ $pengajuan->EducationFocus }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Usia <span class="required-label">*</span></label>
                                        <div class="row">
                                            <div class="col-md-2">Min</div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="MinAge" value="{{ $pengajuan->MinAge }}" required>
                                            </div>
                                            <div class="col-md-2">Max</div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="MaxAge" value="{{ $pengajuan->MaxAge }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Pengalaman Kerja <span class="required-label">*</span></label>
                                        <input type="text" class="form-control" name="WorkingExperience" required value="{{ $pengajuan->WorkingExperience }}">
                                        <small class="form-text">Contoh : " ... tahun"</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Mulai Kerja <span class="required-label">*</span></label>
                                        <input type="text" class="form-control datepicker" name="ActiveDate" required value="{{ $pengajuan->ActiveDate }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Persiapan Peralatan dan Fasilitas Kerja <span class="required-label">*</span></label>
                                        <div class="row">
                                            <div class="col-md-3">
                                            @foreach ($facilities as $item)
                                                @if ($loop->index%4 == 0 && $loop->index != 0)
                                                    </div>
                                                    <div class="col-md-3">
                                                @endif
                                                <input type="checkbox" name="facilities[]" value="{{ $item->lookup_value }}" {{ $inserted_fac->contains('Description', $item->lookup_value) ? 'checked':'' }}>{{ $item->lookup_desc }}<br>
                                                @if ($loop->last)
                                                    </div>
                                                @endif
                                            @endforeach
                                            @php
                                                $unset_fac = $inserted_fac->whereNotIn('Description', $facilities->pluck('lookup_value'));
                                            @endphp
                                            @if ($unset_fac)
                                            <div class="col-md-3">
                                                @foreach ($unset_fac as $item)
                                                    @if ($item->Description)
                                                        <input type="checkbox" name="facilities[]" value="{{ $item->Description }}" checked>{{ $item->Description }}<br>
                                                    @endif
                                                @endforeach
                                            </div>
                                            @endif
                                            <div class="col-md-3">
                                                <p>Untuk yang belum ada tulis disini :</p>
                                                1. <input type="text" name="facilities[]" value=""><br>
                                                2. <input type="text" name="facilities[]" value=""><br>
                                                3. <input type="text" name="facilities[]" value=""><br>
                                                4. <input type="text" name="facilities[]" value=""><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <b>Catatan:</b>
                                                        <ol>
                                                            <li>Jika ada perubahan pengajuan segera informasi ke HCM</li>
                                                            <li>Perubahan pengajuan bisa dilakukan sebelum permintaan masuk ke bagian Pembelian</li>
                                                            <li>Pengajuan akan disesuaikan dengan matrix fasilitas yang berlaku di Perusahaan</li>
                                                            <li>Spesifikasi akan diverifikasi oleh GA</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Catatan</label>
                                        <textarea name="Notes" cols="30" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md pull-right"><i class="fas fa-save"></i> Ajukan</button>
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
    var dt = $('#training-submissions-table').dataTable({
        // responsive: true,
    }).api();

    $('input[name=reasons]').on('change', function(){
        var value = $(this).val();
        if (value == 'ReplcMut' || value == 'ReplcRsgn') {
            $('#ReplacementArea').show();
        }else{
            $('#ReplacementArea').hide();
        }
    });

    $('select[name=Grade]').on('change', function(e){
        var grade = $(this).val();
        changeLevelOptions(grade);
    });

    function changeLevelOptions(grade){
        var select_level = $('select[name=Level]');
        select_level.select2().empty();

        if (grade === 'I') {
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 1,
                        text: '1'
                    },
                ]
            });
        }else if(grade == 'II'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 2,
                        text: '2'
                    },
                ]
            });
        }else if(grade == 'III'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
					{
                        id: 3,
                        text: '3'
                    },
                    {
                        id: 4,
                        text: '4'
                    },
					{
                        id: 5,
                        text: '5'
                    },
					{
                        id: 6,
                        text: '6'
                    },
					 {
                        id: 7,
                        text: '7'
                    },
					{
                        id: 8,
                        text: '8'
                    },
					{
                        id: 9,
                        text: '9'
                    },
                ]
            });
        }else if(grade == 'IV'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                   {
                        id: 10,
                        text: '10'
                    },
					{
                        id: 11,
                        text: '11'
                    },
					{
                        id: 12,
                        text: '12'
                    },
                ]
            });
        }else if(grade == 'V'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 13,
                        text: '13'
                    },
					{
                        id: 14,
                        text: '14'
                    },
					{
                        id: 15,
                        text: '15'
                    },				
                ]
            });
        }else if(grade == 'VI'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 16,
                        text: '16'
                    },
					{
                        id: 17,
                        text: '17'
                    },
					{
                        id: 18,
                        text: '18'
                    },		
					
                ]
            });
        }
        select_level.trigger('change');
        select_level.attr('disabled', false);
    }

    $(document).ready(function(){
        changeLevelOptions('{{ $pengajuan->Grade }}');
        $('select[name=level]').val({{ $pengajuan->Level }}).trigger('change');
        if ($('input[name=QtyBoth]').val().length != 0) {
            $('input[name="QtyMale"]').attr('disabled', true);
            $('input[name="QtyFemale"]').attr('disabled', true);
        }
    });

    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

    $('input[name="QtyBoth"]').on('input', function(){
        var ini = $(this).val().length;
        if (ini != 0) {
            $('input[name="QtyMale"]').attr('disabled', true);
            $('input[name="QtyFemale"]').attr('disabled', true);
        }else{
            $('input[name="QtyMale"]').attr('disabled', false);
            $('input[name="QtyFemale"]').attr('disabled', false);
        }
    });
</script>
@endsection