@extends('layouts.auth')

@section('content')
<div class="container container-login animated fadeIn">
    <h3 class="text-center">Reset Password</h3>
    <div class="login-form">
        @include('layouts.partials.alert')
        <form action="{{ route('password.email') }}" method="post" >
            @csrf
            <div class="form-group form-show-validation">
                <label for="email" class="placeholder">Email<span class="required-label">*</span></label>
                <input id="email" name="email" type="text" class="form-control">
            </div>
            <div class="row form-sub m-0">
                <a href="{{ route('login') }}" class="link float-right">Ingat Password ? Silahkan Login</a>
            </div>
            <div class="form-action mb-3">
                <button type="submit" class="btn btn-primary btn-rounded btn-block">Kirim Link Reset Password</a>
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
                }
            },
            submitHandler: function(form){
                $('button[type=submit]').attr('disabled', true);
                $('button[type=submit]').addClass('is-loading');
                form.submit();
            }
        });
    });
</script>
@endsection
