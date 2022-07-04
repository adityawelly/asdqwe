@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Hari Libur</h4>
            {{ Breadcrumbs::render('holiday') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary">
                    <label class="text-primary"><i class="fas fa-lightbulb"></i> Good to know !</label>
                    <br>
                    1. <b>Perhatian!</b> Upload Libur Cuti Bersama akan mengurangi kuota cuti tahunan sesuai periode tanggal libur dan hari kerja karyawan.
                    Harap lengkapi HK karyawan dahulu sebelum submit libur.
                    <br>
                    2. Menghapus Hari Libur <b>Cuti Bersama</b> akan menghapus ajuan cuti bersama karyawan dan akan dikembalikan saldonya pada kuota periode terkait.
                    <br>
                    3. Jika tanggal yang diupload sudah ada di sistem maka akan diabaikan
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Import Hari Libur</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('holiday.upload') }}" method="POST" 
                            enctype="multipart/form-data" onsubmit="$(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading')">
                            @csrf
                            <div class="form-group">
                                <a href="{{ asset('uploads/excel/template-hari-libur-2019.xlsx') }}" class="btn btn-sm btn-warning btn-round"><i class="fas fa-download"></i> Download Template</a>
                            </div>
                            <div class="form-group">
                                <label for="">Upload File</label>
                                <input type="file" accept=".xlsx" class="form-control" name="file" required>
                            </div>
                            {{-- <div class="form-group">
                                <label for="">Pilih Tahun</label>
                                <select name="tahun" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                @if ($employee_hk->count() > 0)
                                    <ul>
                                        @foreach ($employee_hk as $emp)
                                            <li>
                                                <span class="text text-danger">
                                                    {{ $emp->registration_number }} {{ $emp->fullname }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    belum ada informasi hari kerja silahkan upload dulu
                                @else
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="" method="GET">
                            <div class="form-group">
                                <label for="" class="label col-md-3">Filter Tahun</label>
                                <div class="col-md-3">
                                    <select name="tahun" class="form-control selectpicker" onchange="this.form.submit()">
                                        <option></option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ request()->tahun == $year ?'selected':'' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="holiday-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Libur</th>
                                        <th>Cuti Bersama (Minus annual)</th>
                                        <th>Hari Kerja</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($holidays as $holiday)
                                        <tr>
                                            <td>{{ $holiday->date->format('d-m-Y') }}</td>
                                            <td>{{ $holiday->date_desc }}</td>
                                            <td>{{ $holiday->is_mass_leave == 1?'Ya':'Tidak' }}</td>
                                            <td>{{ $holiday->hk != 0 ? $holiday->hk:'Semua' }}</td>
                                            <td>
                                                <form action="{{ route('holiday.delete') }}" method="post" onsubmit="return confirm('Apakah anda yakin ?')">
                                                    @csrf
                                                    <input type="hidden" name="date" value="{{ $holiday->date->format('Y-m-d') }}" required>
                                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
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
    var dt = $('#holiday-table').dataTable({
        responsive: true,
    }).api();
</script>
@endsection
