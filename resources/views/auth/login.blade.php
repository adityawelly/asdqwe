@extends('layouts.auth')

@section('content')
<div class="container container-login animated fadeIn">
    @if ($app_settings->get('use_logo'))
        <center><img class="ml-auto mr-auto" src="{{ asset('uploads/images/logo.png') }}" alt="Brand Perusahaan" style="width:140px;height:40px;"></center>
    @else
        <h1 class="text-primary text-center" style="font-weight:bold">{{ $app_settings->get('company_name') }}</h1>
    @endif
    <h3 class="text-center">Masuk ke sistem</h3>
    <div class="login-form">
        @include('layouts.partials.alert')
        <form action="{{ route('login') }}" method="post" >
            @csrf
            <div class="form-group form-show-validation">
                <label for="email" class="placeholder">Email<span class="required-label">*</span></label>
                <input id="email" name="email" type="text" class="form-control">
            </div>
            <div class="form-group form-show-validation">
                <label for="password" class="placeholder">Password<span class="required-label">*</span></label>
                <input id="password" name="password" type="password" class="form-control">
                <span class="form-text text-sm text-right" onclick="show_password(this)" style="cursor:pointer">
                    <i class="icon-eye"></i> Lihat Password
                </span>
            </div>
            <div class="row form-sub m-0">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                    <label class="custom-control-label" for="remember">Ingat Saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="link float-right">Lupa Password ?</a>
            </div>
            <div class="form-action mb-3">
                <button type="submit" class="btn btn-primary btn-rounded btn-block">Login</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('form').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            submitHandler: function(form){
                $('button[type=submit]').attr('disabled', true);
                $('button[type=submit]').addClass('is-loading');
                form.submit();
            }
        });
    });
    function show_password(e) {
        var pass_field = $('input[name=password]');

        if (pass_field.attr('type') == 'password') {
            pass_field.attr('type', 'text');
            $(e).css('text-decoration-line', 'line-through');
        }else{
            pass_field.attr('type', 'password');
            $(e).css('text-decoration-line', 'none');
        }
    }
</script>
@endsection
