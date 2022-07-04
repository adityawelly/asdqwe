@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tentang Aplikasi</h4>
            {{ Breadcrumbs::render('about') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">HRMS System</h4>
                    </div>
                    <div class="card-body">
                        <p><b>HRMS System</b> adalah aplikasi untuk menunjang kegiatan harian karyawan PT. Niramas Utama (Bekasi) seperti cuti, izin, pengajuan training, dll</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Catatan Perubahan</h4>
                    </div>
                    <div class="card-body">
                        <ol class="activity-feed">
                            <li class="feed-item feed-item-primary">
                                <span class="text">26 Agustus 2020</span>
                                <br>
                                <span class="text">
                                    <b>Added !</b> Menambahkan info kuota cuti periode berjalan tim saat approval <br>
                                </span>
                            </li>
                            <li class="feed-item feed-item-primary">
                                <span class="text">02 Juli 2020</span>
                                <br>
                                <span class="text">
                                    <b>Added !</b> Menambahkan kolom reject note pada saat approval Ketidakhadiran untuk Atasan Langsung <br>
                                    <b>Fixed !</b> Delete pengajuan ketidakhadiran cuti/izin akan mengembalikan saldo existing/extend sesuai dengan pengajuannya <br>
                                    <b>Added !</b> Halaman tentang aplikasi, berisi tentang catatan perubahan secara timeline <br>
                                    <b>Changed !</b> Perubahan tampilan laporan ketidakhadiran, kotak filter sekarang bisa diexpand/collapse <br>
                                </span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    
</script>
@endsection
