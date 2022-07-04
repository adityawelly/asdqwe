@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengaturan</h4>
            {{ Breadcrumbs::render('setting') }}
        </div>
        <div class="row">
            <div class="col-md-8">
                @include('layouts.partials.alert')
                <div class="alert alert-info" role="alert">
                    <b>Perhatian!</b> Semua perubahan akan diupdate dalam 15 menit atau <button class="btn btn-xs btn-danger btn-round" id="btnRefreshNow"><i class="fas fa-redo-alt"></i> Hapus Cache</button> untuk melihat perubahan sekarang
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="formSetting" action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Perusahaan <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="company_name" value="{{ $setting->get('company_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">Logo Perusahaan</label>
                                @if ($setting->get('company_logo'))
                                    <br>
                                    <img class="img-fluid mb-2" src="{{ asset('uploads/images/'.$setting->get('company_logo')) }}" alt="Logo Perusahaan" width="150" height="120">
                                @endif
                                <input type="file" class="form-control" name="company_logo" id="company_logo">
                                <small class="text-mute form-text">*Ukuran maks: 512kb, format .png, rekomendasi 140x40 pixels</small>
                            </div>
                            <div class="form-check">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="use_logo" name="use_logo" value="true" {{ $setting->get('use_logo') ? 'checked':'' }}>
                                    <label class="custom-control-label" for="use_logo">Gunakan logo untuk icon ?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Perusahaan</label>
                                <textarea rows="3" class="form-control" name="company_address">{{ $setting->get('company_address') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Email Perusahaan</label>
                                <input type="email" class="form-control" name="company_email" value="{{ $setting->get('company_email') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Telepon Perusahaan</label>
                                <input type="text" class="form-control" name="company_phone" value="{{ $setting->get('company_phone') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Default foto karyawan</label>
                                <div class="input-file input-file-image">
                                    <img class="img-upload-preview img-circle" width="150" height="150" src="{{ asset('uploads/images/profile-avatar-flat.png') }}" alt="preview">
                                    <input type="file" class="form-control form-control-file" id="default_avatar" name="default_avatar" accept=".png">
                                    <label for="default_avatar" class="btn btn-secondary btn-round btn-sm"><i class="fa fa-file-image"></i> Upload avatar</label>
                                    <small class="form-text text-muted">*Maksimal 512kb, format .png</small>
                                </div>    
                            </div>
                            <div class="form-group">
                                <label for="">Banner Dashboard</label>
                                <textarea name="dashboard_banner" rows="4" class="form-control" maxlength="191">{{ $setting->get('dashboard_banner') }}</textarea>
                            </div>
                            <fieldset>
                                <legend>Konfigurasi Kalender Event</legend>
                                <div class="form-group">
                                    <label for="">Google Calendar ID</label>
                                    <input type="text" class="form-control" name="calendar_google_id" value="{!! $setting->get('calendar_google_id') !!}">
                                </div>
                                <div class="form-group">
                                    <label for="">Calendar API Key</label>
                                    <input type="text" class="form-control" name="calendar_api_key" value="{!! $setting->get('calendar_api_key') !!}">
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="pull-right">
                            <button class="btn btn-primary btn-md" type="button" onclick="submitForm(this)"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header card-info">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Backup File & Database</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl>
                            <dt>Mode</dt>
                            <dd>Otomatis</dd>
                            <dt>Email Notifikasi</dt>
                            <dd>{{ config('backup.notifications.mail.to') }}</dd>
                            <dt>Backup Setiap</dt>
                            <dd>Setiap hari jam 02:00</dd>
                            <dt>Download Backup</dt>
                            <dd>
                                @foreach ($backups as $backup)
                                    {{ $loop->iteration }}. <a href="{{ route('setting.download_backup', basename($backup)) }}">{{ basename($backup) }}</a>
                                    <br>
                                @endforeach
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-info">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Pengaturan Server</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl>
                            <dt>Tanggal dan Jam</dt>
                            <dd>{{ date('d-m-Y H:i:s') }}</dd>
                            <dt>Timezone</dt>
                            <dd>{{ config('app.timezone') }}</dd>
                            <dt>Alamat URL</dt>
                            <dd>{{ config('app.url') }}</dd>
                            <dt>Database</dt>
                            <dd>{{ config('database.default') }}</dd>
                            <dt>Laravel Version</dt>
                            <dd>{{ App::VERSION() }}</dd>
                            <dt>Mode</dt>
                            <dd>{{ config('app.env') }}</dd>
                        </dl>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success btn-sm" id="btnFlushCache"><i class="fas fa-redo-alt"></i> Flush Cache</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-info">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Blast Reset Password</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.reset_password') }}" method="post" id="formResetPassword">
                            @csrf
                            <div class="form-group">
                                <label for="">Pilih Karyawan</label>
                                <select name="user_id[]" class="form-control selectpicker" multiple>
                                    <option></option>
                                    @foreach ($employees as $item)
                                        <option value="{{ $item->user->id }}">{{ $item->registration_number.'-'.$item->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="all_user" id="all_user" value="true">
                                    <label class="custom-control-label" for="all_user">Reset semua user ?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-redo-alt"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-primary">
                        <h4 class="card-title">Setting Approval PTK HC</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setting.setting_approval_ptk') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Approval Tingkat 1</label>
                                <select name="approval_1" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($employees as $item)
                                        <option value="{{ $item->id }}" {{ $approval_1->EmployeeId == $item->id ? 'selected':'' }}>{{ $item->registration_number.'-'.$item->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Approval Tingkat 2</label>
                                <select name="approval_2" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($employees as $item)
                                        <option value="{{ $item->id }}" {{ $approval_2->EmployeeId == $item->id ? 'selected':'' }}>{{ $item->registration_number.'-'.$item->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
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
    var form = $('#formSetting');

    var validatedForm = form.validate();

    function submitForm(e) {
        if (form.valid()) {
            $(e).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    }

    $('#btnRefreshNow').on('click', function(){
        var btn = $(this);

        $.ajax({
            url: '{{ route('setting.refresh') }}',
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function(){
                btn.addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                showNotification('success', 'Setting berhasil diupdate, silahkan refresh');
            },
            error: function(err){
                console.log(err);
                showNotification('danger', 'Terjadi kesalahan ! Silahkan refresh dan coba kembali');
            },
            complete: function(){
                btn.removeClass('is-loading').attr('disabled', false);
            }
        });
    });

    $('#btnFlushCache').on('click', function(){
        var btn = $(this);

        $.ajax({
            url: '{{ route('setting.flush') }}',
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function(){
                btn.addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                showNotification('success', 'Cache berhasil dibersihkan, silahkan refresh');
            },
            error: function(err){
                console.log(err);
                showNotification('danger', 'Terjadi kesalahan ! Silahkan refresh dan coba kembali');
            },
            complete: function(){
                btn.removeClass('is-loading').attr('disabled', false);
            }
        });
    });

    $('#formResetPassword').on('submit', function(){
        $(this).find('button[type=submit]').addClass('is-loading').attr('disabled', true);
    });
</script>
@endsection