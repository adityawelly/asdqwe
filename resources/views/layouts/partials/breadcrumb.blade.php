@if (count($breadcrumbs))
<ul class="breadcrumbs">
    <li class="nav-home">
        <a href="{{ url('/') }}">
            <i class="flaticon-home"></i>
        </a>
    </li>
    <li class="separator">
        <i class="flaticon-right-arrow"></i>
    </li>
    @foreach ($breadcrumbs as $breadcrumb)
        @if ($breadcrumb->url && !$loop->last)
            <li class="nav-item">
                <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
        @else
            <li class="nav-item" style="font-weight:bold">
                {{ $breadcrumb->title }}
            </li>
        @endif
    @endforeach
</ul>
@endif