@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                    @role('Super Admin')
                        <h5 class="text-white op-7 mb-2">Halo, Administrator</h5>
                    @else
                        <h5 class="text-white op-7 mb-2">Halo, {{ auth()->user()->employee->fullname }}</h5>
                    @endrole
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    {{-- <a href="#" class="btn btn-white btn-border btn-round mr-2">Manage</a> --}}
                    <a href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit()" class="btn btn-danger btn-round"><i class="fas fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        @if ($app_settings->get('dashboard_banner'))
            <div class="alert alert-info" role="alert">
                <b>Info!</b><br>
                {{ $app_settings->get('dashboard_banner') }}
            </div>
        @endif
        <div class="row mt--2">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-stats card-round">
                            <div class="card-body ">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="flaticon-users"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Karyawan</p>
                                            <h4 class="card-title">{{ $count['employee'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="card card-stats card-round">
                            <div class="card-body ">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="flaticon-suitcase"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Departemen</p>
                                            <h4 class="card-title">{{ $count['department'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-12">
                        <div class="card card-stats card-round">
                            <div class="card-body ">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="flaticon-delivery-truck"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Regional</p>
                                            <h4 class="card-title">{{ $count['company_region'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="card card-stats card-round">
                            <div class="card-body ">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                                            <i class="flaticon-suitcase"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Divisi</p>
                                            <h4 class="card-title">{{ $count['division'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title text-center">Statistik Karyawan</h6>
                    </div>
                    <div class="card-body">
                        <div id="chart-container">
                            <canvas id="employee-sex-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title text-center">Statistik Masa Kerja</h6>
                    </div>
                    <div class="card-body">
                        <div id="chart-container">
                            <canvas id="employee-year-of-service-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title text-center">Statistik Status Kerja</h6>
                    </div>
                    <div class="card-body">
                        <div id="chart-container">
                            <canvas id="employee-status-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
		@role('Personnel')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title text-center">Statistik Karyawan</h6>
                    </div>
                    <div class="card-body">
                        <div id="chart-container">
                            <canvas id="jkar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		@endrole
    </div>
</div>
@endsection

@section('script')
<script>
    var employee_sex_chart = document.getElementById('employee-sex-chart').getContext('2d');
    // var employee_grade_title_chart = document.getElementById('employee-grade-title-chart').getContext('2d');
    //var jkar = document.getElementById('jkar').getContext('2d');
    var employee_year_of_service_chart = document.getElementById('employee-year-of-service-chart').getContext('2d');

    var employeeSexChart = new Chart(employee_sex_chart, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: {!! json_encode($sex['count']) !!},
                backgroundColor: {!! json_encode($sex['color']) !!},
            }],

            labels: [
            'Laki - Laki',
            'Perempuan'
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend : {
                position: 'bottom'
            },
            layout: {
                
            }
        }
    });

    // var employeeGradeTitleChart = new Chart(employee_grade_title_chart, {
    //     type: 'bar',
    //     data: {
    //         labels: {!! json_encode($grade_title['label']) !!},
    //         datasets: [{
    //             label: "Jumlah",
    //             data: {!! json_encode($grade_title['count']) !!},
    //             backgroundColor: {!! json_encode($grade_title['color'][0]) !!},
    //             borderColor: {!! json_encode($grade_title['color'][0]) !!}
    //         }],
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         scales: {
    //             yAxes: [{
    //                 ticks: {
    //                     beginAtZero:true,
    //                     callback: function(value) {if (value % 1 === 0) {return value;}}
    //                 }
    //             }]
    //         },
    //     }
    // });

    // var employeeStatusChart = new Chart(employee_status_chart, {
    //     type: 'doughnut',
    //     data: {
    //         datasets: [{
    //             data: {!! json_encode($employee_status['count']) !!},
    //             backgroundColor: {!! json_encode($employee_status['color']) !!}
    //         }],

    //         labels: {!! json_encode($employee_status['label']) !!}
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         legend : {
    //             position: 'bottom'
    //         },
    //         layout: {
                
    //         }
    //     }
    // });

    var employeeYearOfService = new Chart(employee_year_of_service_chart, {
			type: 'bar',
			data: {
				labels: {!! json_encode($employee_year_of_service['label']) !!},
				datasets : [{
					label: "Jumlah",
					backgroundColor: '{{ $employee_year_of_service['color'][0] }}',
					borderColor: '{{ $employee_year_of_service['color'][0] }}',
					data: {!! json_encode($employee_year_of_service['count']) !!},
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
                        },
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Dalam Tahun'
                        }
                    }]
				},
			}
		});
		
		var jkar = new Chart(jkar, {
			type: 'line',
			data: {
				labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				datasets : [{
					label: "Jumlah",
					//backgroundColor: '{{ $jkar['color'][0] }}',
					borderColor: '{{ $jkar['color'][0] }}',
					data: {!! json_encode($jkar['count']) !!},
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							 min: 0,
				             max: 250,
				             stepSize: 50
                        },
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Dalam Bulan'
                        }
                    }]
				},
			}
		});

</script>
@endsection