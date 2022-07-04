@extends('layouts.error')

@section('content')
<div class="wrapper not-found">
    <h1 class="animated fadeIn">403</h1>
    <div class="desc animated fadeIn"><span>OOPS!</span><br/>Anda tidak dapat mengakses halaman ini</div>
    <a href="javascript:void(0)" onclick="location.assign('{{ url('/') }}');" class="btn btn-primary btn-back-home mt-4 animated fadeInUp">
        <span class="btn-label mr-2">
            <i class="flaticon-home"></i>
        </span>
        Kembali ke dashboard
    </a>
</div>
@endsection