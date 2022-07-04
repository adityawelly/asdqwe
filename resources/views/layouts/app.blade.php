<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $app_settings->get('company_name') }}</title>
    <link rel="icon" href="{{ asset('img/icon.ico') }}" type="image/x-icon"/>
    <link rel="manifest" href="{{ asset('manifest.json') }}">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Fonts and icons -->
	<script src="{{ asset('js/webfont.min.js') }}"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{ asset('css/fonts.min.css') }}']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	@stack('css')
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
</head>
<body>
    <div class="wrapper">
        <script>
            if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                var wrapper = document.getElementsByClassName('wrapper')[0];
                wrapper.className += ' sidebar_minimize';
            }
        </script>
		<div class="main-header">
			@include('layouts.partials.logo')
			@include('layouts.partials.navbar')
		</div>
		@include('layouts.partials.sidebar')
		<div class="main-panel">
			@yield('content')
			@include('layouts.partials.footer')
		</div>
	</div>
	@yield('modals')
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/custom.js') }}"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
	@stack('scripts')
	@yield('script')
	@include('layouts.partials.notification')
</body>
</html>
