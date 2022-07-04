<!-- Logo Header -->
<div class="logo-header" data-background-color="blue">
    @if ($app_settings->get('use_logo'))
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('uploads/images/logo.png') }}" alt="navbar brand" class="navbar-brand" style="width:140px;height:40px;">
        </a>
    @else
        <a href="{{ url('/') }}" class="logo">
            <span alt="navbar brand" class="navbar-brand" style="color:#fff;">{{ $app_settings->get('company_name') }}</span>
        </a>
    @endif
    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
            <i class="icon-menu"></i>
        </span>
    </button>
    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
    <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
            <i class="icon-menu"></i>
        </button>
    </div>
</div>
<!-- End Logo Header -->
