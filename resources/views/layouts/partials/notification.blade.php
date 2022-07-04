@if (session('alert'))
    <script>
        showNotification('{{ session('alert')['type'] }}', "{{ trim(preg_replace('/\s\s+/', ' ', session('alert')['msg'])) }}");
    </script>
@endif

@if (session('status'))
    <script>
        showNotification('info', '{{ session('status') }}');
    </script>
@endif