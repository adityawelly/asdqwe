@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Profil Karyawan</h4>
            {{ Breadcrumbs::render('karyawan-show', $employee->id) }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-profile">
                            <div class="card-header" style="background-image: url('{{ asset('img/blogpost.jpg') }}')">
                                <div class="profile-picture">
                                    <div class="avatar avatar-xl">
                                        <a href="{{ empty($employee->photo) ? 
                                                asset('uploads/images/profile-avatar-flat.png'):
                                                asset('uploads/images/users/'.$employee->photo) }}"
                                                data-lightbox="{{ time() }}" data-title="Foto Diri">
                                        <img src="{{ empty($employee->photo) ? 
                                                asset('uploads/images/profile-avatar-flat.png'):
                                                asset('uploads/images/users/'.$employee->photo) }}" alt="Foto Profil"
                                                class="avatar-img rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="user-profile text-center">
                                    <div class="name">{{ $employee->fullname }}</div>
                                    <div class="job">{{ $employee->grade_title->grade_title_name }}</div>
                                    {{-- <div class="desc">{{ $employee->division->division_name }}</div> --}}
                                    <span class="badge badge-primary">{{ $employee->user->email }}</span>
                                </div>
                            </div>
                            {{-- <div class="card-footer">
                                <div class="row user-stats text-center">
                                    <div class="col">
                                        <div class="number">125</div>
                                        <div class="title">Post</div>
                                    </div>
                                    <div class="col">
                                        <div class="number">25K</div>
                                        <div class="title">Followers</div>
                                    </div>
                                    <div class="col">
                                        <div class="number">134</div>
                                        <div class="title">Following</div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-primary">
                                <h5 class="card-title">Informasi Posisi</h5>
                            </div>
                            <div class="card-body">
                                <p>Divisi : {{ $employee->division->division_name }}</p>
                                <p>Direct Superior : {{ $employee->superior->fullname ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Diri</h4>
                            @can('update-employee')
                                <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-sm btn-round btn-success ml-auto">Edit Karyawan</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Nama Lengkap</dt>
                                    <dd>{{ $employee->fullname }}</dd>
                                    <dt>Tempat, Tanggal Lahir</dt>
                                    <dd>{{ $employee->employee_detail->place_of_birth.', '.date('d/m/Y', strtotime($employee->employee_detail->date_of_birth)) }}</dd>
                                    <dt>No KTP</dt>
                                    <dd>{{ $employee->employee_detail->ID_number }}</dd>
                                    <dt>Jenis Kelamin</dt>
                                    <dd>{{ $employee->employee_detail->sex }}</dd>
                                    <dt>No Telepon</dt>
                                    <dd>{{ $employee->employee_detail->phone_number }}</dd>
                                    <dt>Pendidikan Terakhir</dt>
                                    <dd>{{ $employee->employee_detail->last_education }}</dd>
                                    <dt>Jurusan</dt>
                                    <dd>{{ $employee->employee_detail->education_focus }}</dd>
                                    <dt>Alamat</dt>
                                    <dd>{{ $employee->employee_detail->address }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Nomor Induk Karyawan (NIK)</dt>
                                    <dd>
                                        @hasrole('Personnel|HCMTeam')
                                            {{ $employee->registration_number }}
                                        @else
                                            Tidak mempunyai hak akses
                                        @endrole
                                    </dd>
                                    <dt>Tanggal Mulai Bekerja</dt>
                                    <dd>{{ date('d/m/Y', strtotime($employee->date_of_work)) }} ({{ $employee->year_of_service }})</dd>
                                    <dt>Divisi</dt>
                                    <dd>{{ $employee->division->division_code }} - {{ $employee->division->division_name }}</dd>
                                    <dt>Departemen</dt>
                                    <dd>{{ $employee->department->department_code }} - {{ $employee->department->department_name }}</dd>
                                    <dt>Grade Title</dt>
                                    <dd>{{ $employee->grade_title->grade_title_code }} - {{ $employee->grade_title->grade_title_name }}</dd>
                                    <dt>Level Title</dt>
                                    <dd>{{ $employee->level_title->level_title_code }} - {{ $employee->level_title->level_title_name }}</dd>
                                    <dt>Job Title</dt>
                                    <dd>{{ $employee->job_title->job_title_code }} - {{ $employee->job_title->job_title_name }}</dd>
                                    <dt>Lokasi</dt>
                                    <dd>{{ $employee->company_region->region_city }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Payroll</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl>
                            <dt>Email</dt>
                            <dd>{{ $employee->user->email }}</dd>
                            <dt>Bank</dt>
                            <dd>
                                @role('Personnel')
                                    {{ $employee->employee_salary->bank ?? 'Belum diisi' }}
                                @else
                                    Tidak mempunyai hak akses
                                @endrole
                            </dd>
                            <dt>Nomor Rekening</dt>
                            <dd>
                                @role('Personnel')
                                    {{ $employee->employee_salary->bank_account_number ?? 'Belum diisi' }}
                                @else
                                    Tidak mempunyai hak akses
                                @endrole
                            </dd>
                            <dt>Salary</dt>
                            @can('read-salary')
                            <dd>Rp. {{ $employee->employee_salary->basic_salary }}</dd>
                            @else
                            <dd>Tidak mempunyai hak akses</dd>
                            @endcan
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    
</script>
@endsection