@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Daftar Notifikasi</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-head-bg-primary datatables" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Notifikasi</th>
                                        <th>Dikirim Pada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifs as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="content">{!! $item->data['msg'] !!}</td>
                                            <td class="content">{{ date('d-m-Y H:i:s', strtotime($item->data['send_at'])) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var dt = $('.datatables').dataTable({
        order: [[2, 'desc']],
    }).api();

    $(document).on('focus', '.dataTables_filter input', function() {
        $(this).unbind().bind('keyup', function(e) {
            if(e.keyCode === 13) {
                dt.search( this.value ).draw();
            }
        });
    });
</script>
@endsection
