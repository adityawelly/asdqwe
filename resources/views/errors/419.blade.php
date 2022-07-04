@extends('layouts.error')

@section('content')
<div class="wrapper not-found">
    <h1 class="animated fadeIn">419</h1>
    <div class="desc animated fadeIn"><span>OOPS!</span><br/>Sesi anda telah habis, silahkan login kembali dan muat ulang browser anda</div>
    <a href="javascript:void(0)" onclick="location.assign('{{ route('login') }}');" class="btn btn-primary btn-back-home mt-4 animated fadeInUp">
        <span class="btn-label mr-2">
            <i class="fas fa-sign-in-alt"></i>
        </span>
        Login
    </a>
</div>
@endsection