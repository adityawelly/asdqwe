@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Kalender Event</h4>
            {{ Breadcrumbs::render('events') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/@fullcalendar/core@4.3.1/main.min.js"></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.3.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/list@4.3.0/main.min.js'></script>
<script src="https://unpkg.com/@fullcalendar/google-calendar@4.3.0/main.min.js"></script>
<script src='https://unpkg.com/@fullcalendar/bootstrap@4.4.0/main.min.js'></script>
@endpush

@push('css')
<link href='https://unpkg.com/@fullcalendar/core@4.3.1/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/daygrid@4.3.0/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/list@4.3.0/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/bootstrap@4.4.0/main.min.css' rel='stylesheet' />
@endpush

@section('script')
<script>
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [
            'bootstrap',
            'dayGrid', 
            'list', 
            'googleCalendar', 
        ],
        themeSystem: 'bootstrap',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        @if($app_settings->has('calendar_api_key'))
            googleCalendarApiKey: '{{ $app_settings->get('calendar_api_key') }}',
        @endif
        eventSources: [
            // {
            //     googleCalendarId: 'id.indonesian#holiday@group.v.calendar.google.com'
            // },
            @if($app_settings->has('calendar_google_id'))
            {
                googleCalendarId: '{{ $app_settings->get('calendar_google_id') }}'
            }
            @endif
        ]
    });
    calendar.render();
</script>
@endsection