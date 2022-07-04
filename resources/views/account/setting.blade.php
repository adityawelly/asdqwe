@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengaturan</h4>
            {{ Breadcrumbs::render('setting-account') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="nav flex-column nav-pills nav-primary" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a href="#v-pills-system" class="nav-link active" id="v-pills-system-tab" data-toggle="pill" role="tab" aria-controls="v-pills-system" aria-selected="true">Sistem</a>
                                    <a href="#v-pills-account" class="nav-link" id="v-pills-account-tab" data-toggle="pill" role="tab" aria-controls="v-pills-account" aria-selected="false">Akun</a>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-system" role="tabpanel" aria-labelledby="v-pills-system-tab">
                                        <form action="">
                                            <div class="form-group">
                                                <label for="">Notifikasi Sistem</label>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <select name="notif_position" class="form-control selectpicker">
                                                            <option></option>
                                                            <option value="top">Atas</option>
                                                            <option value="bottom">Bawah</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select name="notif_align" class="form-control selectpicker">
                                                            <option></option>
                                                            <option value="right">Kanan</option>
                                                            <option value="center">Tengah</option>
                                                            <option value="left">Kiri</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-default btn-sm btn-block" id="btnPreview"><i class="fas fa-eye"></i> Preview</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
    $(document).ready(function(){
        $('select[name=notif_position],select[name=notif_align]').val(function(){return getLocalStorage($(this).attr('name'))}).trigger('change');
    });
    $('select[name=notif_position],select[name=notif_align]').on('change', function(){
        var self = $(this);
        setLocalStorage(self.attr('name'), self.val());
    });
    $('#btnPreview').on('click', function(){
        showNotification('info', 'Uji notifikasi!');
    });
</script>
@endsection
