@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan PTK</h4>
            {{ Breadcrumbs::render('pengajuan-ptk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Form Permintaan Tenaga Kerja</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.ptk.submit') }}" method="POST" id="pengajuan_ptk" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Jabatan (Job Title) <span class="required-label">*</span></label>
                                        <select name="JobTitle" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($job_titles as $item)
                                                <option value="{{ $item->id }}">{{ $item->job_title_code.'-'.$item->job_title_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Level Jabatan (Position Level) <span class="required-label">*</span></label>
                                        <select name="PositionLevel" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($grade_titles as $item)
                                                <option value="{{ $item->id }}">{{ $item->grade_title_code.'-'.$item->grade_title_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Departemen (Department) <span class="required-label">*</span></label>
                                        <select name="DeptId" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($departments as $item)
                                                <option value="{{ $item->id }}">{{ $item->department_code.'-'.$item->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lokasi Kerja (Working Location) <span class="required-label">*</span></label>
                                        <select name="WorkLocation" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($company_regions as $item)
                                                <option value="{{ $item->id }}">{{ $item->region_city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Alasan Perekrutan <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($reasonOfHiring as $item)
                                            <input type="radio" name="reasons" value="{{ $item->lookup_value }}" required> {{ $item->lookup_desc }}
                                            <br>
                                        @endforeach
                                        <div class="row" id="ReplacementArea" style="display: none">
                                            <div class="col-md-6">
                                                Diganti:
                                                <br>
                                                1. <input type="text" name="replaced[]" placeholder="Tulis Disini"><br>
                                                2. <input type="text" name="replaced[]" placeholder="Tulis Disini"><br>
                                                3. <input type="text" name="replaced[]" placeholder="Tulis Disini">
                                            </div>
                                            <div class="col-md-6">
                                                Pengganti:
                                                <br>
                                                1. <input type="text" name="replacement[]" placeholder="Tulis Disini"><br>
                                                2. <input type="text" name="replacement[]" placeholder="Tulis Disini"><br>
                                                3. <input type="text" name="replacement[]" placeholder="Tulis Disini">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Uraian Pekerjaan <span class="required-label">*</span></label>
                                        <br>
                                        @for ($i = 1; $i < 6; $i++)
                                            {{ $i }}. <input type="text" name="JobDesc[]" placeholder="Tulis Disini" style="width: 90%"><br>
                                        @endfor
                                    </div>
                                    <div class="form-group">
                                        <label for="">Keahlian Khusus <span class="required-label">*</span></label>
                                        <br>
                                        @for ($i = 1; $i < 6; $i++)
                                            {{ $i }}. <input type="text" name="ParticularSkill[]" placeholder="Tulis Disini" style="width: 90%"><br>
                                        @endfor
                                    </div>
                                    <div class="form-group">
                                        <label for="">Status Karyawan <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($employeeStatus as $item)
                                            <input type="radio" name="EmploymentStatus" value="{{ $item->lookup_value }}" required> {{ $item->lookup_desc }}
                                            <br>
                                        @endforeach
                                        Tulis Keterangan Disini:
                                        <br>
                                        <input type="text" name="EmploymentNote" style="width: 100%">
                                        <br>
                                        <small class="text-muted">Jika kontrak silahkan isi : Cth. 6 bulan atau periode 01-01-2020 s/d 01-06-2020</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Waktu Kerja <span class="required-label">*</span></label>
                                        <br>
                                        @foreach ($workingTime as $item)
                                            <input type="radio" name="WorkingTime" value="{{ $item->lookup_value }}" required> {{ $item->lookup_desc }}
                                            <br>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="">Grade <span class="required-label">*</span></label>
                                        <select name="Grade" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($gradeOptions as $item)
                                                <option value="{{ $item }}">{{ strtoupper($item) }}</option>
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
                                        <input type="number" class="form-control" name="ReqQty" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jenis Kelamin</label>
                                        <br>
                                        <table>
                                            <tr>
                                                <td>Laki</td>
                                                <td><input type="text" name="QtyMale" size="5"> orang</td>
                                            </tr>
                                            <tr>
                                                <td>Perempuan</td>
                                                <td><input type="text" name="QtyFemale" size="5"> orang</td>
                                            </tr>
                                            <tr>
                                                <td>Laki-laki/Perempuan (Both)</td>
                                                <td><input type="text" name="QtyBoth" size="5"> orang</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Pendidikan <span class="required-label">*</span></label>
                                        <select name="Education" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($last_educationOptions as $option)
                                                <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jurusan</label>
                                        <input type="text" class="form-control" name="EducationFocus">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Usia <span class="required-label">*</span></label>
                                        <div class="row">
                                            <div class="col-md-2">Min</div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="MinAge" required>
                                            </div>
                                            <div class="col-md-2">Max</div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="MaxAge" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Pengalaman Kerja <span class="required-label">*</span></label>
                                        <input type="text" class="form-control" name="WorkingExperience" required>
                                        <small class="form-text">Contoh : " ... tahun"</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Mulai Kerja <span class="required-label">*</span></label>
                                        <input type="text" class="form-control datepicker" name="ActiveDate" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Persiapan Peralatan dan Fasilitas Kerja <span class="required-label">*</span></label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="Mobil">Mobil<br>
                                                <input type="checkbox" name="facilities[]" value="Meja">Meja<br>
                                                <input type="checkbox" name="facilities[]" value="Kursi">Kursi<br>
                                                <input type="checkbox" name="facilities[]" value="ID Card">ID Card<br>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="Laptop">Laptop<br>
                                                <input type="checkbox" name="facilities[]" value="PC">PC<br>
                                                <input type="checkbox" name="facilities[]" value="Tunjangan Komunikasi">Tunjangan Komunikasi<br>
                                                <input type="checkbox" name="facilities[]" value="Email">Email<br>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="Seragam">Seragam<br>
                                                <input type="checkbox" name="facilities[]" value="Masker (Kain Putih)">Masker (Kain Putih)<br>
                                                <input type="checkbox" name="facilities[]" value="Hairnet (Kain Biru)">Hairnet (Kain Biru)<br>
                                                <input type="checkbox" name="facilities[]" value="Sepatu Boot">Sepatu Boot<br>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="Apron">Apron<br>
                                                <input type="checkbox" name="facilities[]" value="Masker (Hijau)">Masker (Hijau)<br>
                                                <input type="checkbox" name="facilities[]" value="Hairnet (Hijau)">Hairnet (Hijau)<br>
                                                <input type="checkbox" name="facilities[]" value="Baju Astronot">Baju<br>
                                            </div>
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
                                    <div class="form-group">
                                        <label for="">Atasan Langsung <span class="required-label">*</span></label>
                                        <select name="DirectSuperior" class="form-control selectpicker">
                                            <option></option>
                                            <option value="{{ $direct_superior->id }}">
                                                {{ $direct_superior->fullname.'-'.$direct_superior->level_title->level_title_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Atasan Tidak Langsung <span class="required-label">*</span></label>
                                        <input type="text" name="InDirectSuperior" class="form-control" disabled>
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

    $('select[name=DirectSuperior]').on('change', function(){
        var id = $(this).val();

        $.ajax({
            url: '{{ route('pengajuan.ptk.get_indirect_superior') }}',
            type: 'GET',
            dataType:'JSON',
            data: {
                'id':id,
            },
            success: function(resp){
                $('input[name=InDirectSuperior]').val(resp.value);
            },
            error: function(err){
                showNotification('error', err.error.toString);
            }
        })
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