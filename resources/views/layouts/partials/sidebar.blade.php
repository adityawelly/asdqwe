@php
if (auth()->user()->hasRole('Super Admin')) {
    $is_superior = false;
}else{
    $is_superior = auth()->user()->employee->isSuperior();
}
@endphp
<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="{{ empty(auth()->user()->employee->photo) ? asset('uploads/images/profile-avatar-flat.png'):asset('uploads/images/users/'.auth()->user()->employee->photo) }}" alt="User Profile" class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ !empty(auth()->user()->employee->fullname) ? auth()->user()->employee->fullname:'Administrator' }}
                            <span class="user-level">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('account.index') }}">
                                    <span class="link-collapse">Profil Saya</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.notification') }}">
                                    <span class="link-collapse">Notifikasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.setting') }}">
                                    <span class="link-collapse">Pengaturan</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit()">
                                    <span class="link-collapse">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item {{ request()->is('/') ? 'active':'' }}">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Master Data</h4>
                </li>
                @can('read-employee','read-division','read-department','read-job-title','read-grade-title','read-level-title','read-company-region')
                <li class="nav-item {{ in_array(request()->segment(1), $app_settings->employee_menus) ? 'active':'' }}">
                    <a data-toggle="collapse" href="#karyawan">
                        <i class="fas fa-users"></i>
                        <p>Data Karyawan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ in_array(request()->segment(1), $app_settings->employee_menus) ? 'show':'' }}" id="karyawan">
                        <ul class="nav nav-collapse">
                            @can('read-employee')
                                <li>
                                    <a href="{{ route('employee.index') }}" class="{{ request()->is('employee') || request()->is('employee/*') ? 'active':'' }}">
                                        <span class="sub-item">Karyawan</span>
                                    </a>
                                </li>
                            @endcan
                            @can('read-division')
                                <li>
                                    <a href="{{ route('division.index') }}" class="{{ request()->is('division') ? 'active':'' }}">
                                        <span class="sub-item">Divisi</span>
                                    </a>
                                </li>
                            @endcan
                            @can('read-department')
                                <li>
                                    <a href="{{ route('department.index') }}" class="{{ request()->is('department') ? 'active':'' }}">
                                        <span class="sub-item">Department</span>
                                    </a>
                                </li>
                            @endcan
                            @can('read-job-title')
                                <li>
                                    <a href="{{ route('job-title.index') }}" class="{{ request()->is('job-title') ? 'active':'' }}">
                                        <span class="sub-item">Job Title</span>
                                    </a>
                                </li>
                            @endcan
                            @can('read-grade-title')
                                <li>
                                    <a href="{{ route('grade-title.index') }}" class="{{ request()->is('grade-title') ? 'active':'' }}">
                                        <span class="sub-item">Grade Title</span>
                                    </a>
                                </li>
                            @endcan
                            @can('read-level-title')
                                <li>
                                    <a href="{{ route('level-title.index') }}" class="{{ request()->is('level-title') ? 'active':'' }}">
                                        <span class="sub-item">Level Title</span>
                                    </a>
                                </li>
                            @endcan
                            @can('read-company-region')
                                <li>
                                    <a href="{{ route('company-region.index') }}" class="{{ request()->is('company-region') ? 'active':'' }}">
                                        <span class="sub-item">Lokasi Kerja</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
                @can('read-training')
                <li class="nav-item {{ request()->is('training*') ? 'active':'' }}">
                    <a href="{{ route('training.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Data Training</p>
                    </a>
                </li>
                @endcan
                <li class="nav-item {{ request()->is('events*') ? 'active':'' }}">
                    <a href="{{ url('events') }}">
                        <i class="fas fas fa-calendar-alt"></i>
                        <p>Events Calendar</p>
                    </a>
                </li>
                @can('upload-holiday')
                <li class="nav-item {{ request()->is('holiday*') ? 'active':'' }}">
                    <a href="{{ url('holiday') }}">
                        <i class="fas fas fa-calendar"></i>
                        <p>Hari Libur</p>
                    </a>
                </li>
                @endcan
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Form Karyawan</h4>
                </li>
				@role('Personnel')
                 <li class="nav-item {{ request()->is('leave') || request()->is('employee-leave/create/direct') ||request()->is('employee-dinas/create_direct') || request()->is('employee-wfh/create_direct') || request()->is('employee-dinas/create_direct') || request()->is('leave/opname') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#psn">
                        <i class="fas fa-user-tie"></i>
                        <p>Personnel</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('leave') || request()->is('employee-leave/create/direct') ||request()->is('employee-dinas/create_direct') || request()->is('employee-wfh/create_direct') || request()->is('employee-dinas/create_direct') || request()->is('leave/opname') ? 'show':'' }}" id="psn">
                        <ul class="nav nav-collapse">
							 <li>
                                <a href="{{ route('leave.index') }}" class="{{ request()->is('leave') ? 'active':'' }}">
                                    <span class="sub-item">Kategori Ketidakhadiran</span>
                                </a>
                             </li>
                             <li>
                                <a href="{{ route('employee-leave.create', 'direct') }}" class="{{ request()->is('employee-leave/create/direct') ? 'active':'' }}">
                                    <span class="sub-item">Input Izin &amp; Cuti</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-dinas.create_direct') }}" class="{{ request()->is('employee-dinas/create_direct') ? 'active':'' }}">
                                    <span class="sub-item">Input Dinas Luar</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-wfh.create_direct') }}" class="{{ request()->is('employee-wfh/create_direct') ? 'active':'' }}">
                                    <span class="sub-item">Input Bekerja Dari Rumah</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('leave.opname_index') }}" class="{{ request()->is('leave/opname') ? 'active':'' }}">
                                    <span class="sub-item">Cuti Opname</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
				@endrole
                <li class="nav-item {{ request()->is('employee-leave/create/ijin') || request()->is('employee-dinas/create') || request()->is('employee-lembur/create') || request()->is('employee-wfh/create') || request()->is('employee-leave/create/cuti') || request()->is('employee-leave') || request()->is('leave/quota') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#cuti">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Ketidakhadiran</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('employee-leave/create/ijin') || request()->is('employee-dinas/create') || request()->is('employee-lembur/create') || request()->is('employee-wfh/create') || request()->is('employee-leave/create/cuti') || request()->is('employee-isoman/create') || request()->is('employee-leave') || request()->is('leave/quota') ? 'show':'' }}" id="cuti">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('employee-leave.create', 'ijin') }}" class="{{ request()->is('employee-leave/create/ijin') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Izin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('employee-leave.create', 'cuti') }}" class="{{ request()->is('employee-leave/create/cuti') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Cuti</span>
                                </a>
                            </li>
							@role('User')
							<li>
                                <a href="{{ route('employee-wfh.create') }}" class="{{ request()->is('employee-wfh/create') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Bekerja Dari Rumah</span>
                                </a>
                            </li>
						    @endrole
							@role('Personnel')
							<li>
                                <a href="{{ route('employee-wfh.create') }}" class="{{ request()->is('employee-wfh/create') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Bekerja Dari Rumah</span>
                                </a>
                            </li>
						    @endrole
                            {{-- <li>
                                <a href="{{ route('employee-leave.create', 'khusus') }}" class="{{ request()->is('employee-leave/create/khusus') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Cuti Khusus</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-isoman.create') }}" class="{{ request()->is('employee-isoman/create') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Isoman</span>
                                </a>
                            </li> --}}
							<li>
                                <a href="{{ route('employee-dinas.create') }}" class="{{ request()->is('employee-dinas/create') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Dinas Luar</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-lembur.create') }}" class="{{ request()->is('employee-lembur/create') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Kerja Lembur</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('employee-leave.index') }}" class="{{ request()->is('employee-leave') ? 'active':'' }}">
                                    <span class="sub-item">Daftar Ketidakhadiran</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leave.quota_index') }}" class="{{ request()->is('leave/quota') ? 'active':'' }}">
                                    <span class="sub-item">Kuota Cuti</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('pengajuan/training') || request()->is('pengajuan/ptk') || request()->is('pengajuan/fpk') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#pengajuan">
                        <i class="fas fa-lightbulb"></i>
                        <p>Pengajuan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('pengajuan/training') || request()->is('pengajuan/ptk') || request()->is('pengajuan/fpk')  ? 'show':'' }}" id="pengajuan">
                        <ul class="nav nav-collapse">
                            @can('submission-training')
                            <li>
                                <a href="{{ route('pengajuan.training') }}" class="{{ request()->is('pengajuan/training') ? 'active':'' }}">
                                    <span class="sub-item">Pengajuan Training</span>
                                </a>
                            </li>
                            @endcan
                            @php
                                if (auth()->user()->hasRole('Super Admin')) {
                                    $grade_title_code = 'Admin';
                                }else{
                                    $grade_title_code = auth()->user()->employee->grade_title->grade_title_code;
                                }
                            @endphp
                            @if (($grade_title_code != 'GRD05' && $grade_title_code != 'Admin') || auth()->user()->can('modify-ptk'))
                            <li>
                                <a href="{{ route('pengajuan.ptk') }}" class="{{ request()->is('pengajuan/ptk') ? 'active':'' }}">
                                    <span class="sub-item">List PTK</span>
                                </a>
                            </li>
                            @endif
                            @if (($grade_title_code != 'GRD05' && $grade_title_code != 'Admin') || auth()->user()->can('modify-fpk'))
                            <li>
                                <a href="{{ route('pengajuan.fpk') }}" class="{{ request()->is('pengajuan/fpk') ? 'active':'' }}">
                                    <span class="sub-item">List FPK</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
				@if($is_superior || auth()->user()->can('training-spv-approval'))
				<li class="nav-item {{ request()->is('pengajuan/training/approval') ||  request()->is('pengajuan/ptk/approval')
					|| request()->is('pengajuan/fpk/approval') || request()->is('employee-dinas/approval') || request()->is('employee-wfh/approval')
				    || request()->is('employee-lembur/approval') ||  request()->is('employee-leave/approval') || request()->is('employee-isoman/approval') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#approval">
						<i class="fas fa-stamp"></i>
                        <p>Approval</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('pengajuan/training/approval') ||  request()->is('pengajuan/ptk/approval')
					|| request()->is('pengajuan/fpk/approval') || request()->is('employee-dinas/approval') || request()->is('employee-wfh/approval')
				    || request()->is('employee-lembur/approval') ||  request()->is('employee-leave/approval') || request()->is('employee-isoman/approval') ? 'show':'' }}" id="approval">
                        <ul class="nav nav-collapse">
							<li>
                                <a href="{{ route('employee-dinas.approval') }}" class="{{ request()->is('employee-dinas/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval Dinas Luar</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-isoman.approval') }}" class="{{ request()->is('employee-isoman/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval Isolasi Mandiri</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-wfh.approval') }}" class="{{ request()->is('employee-wfh/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval Bekerja Dari Rumah</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('employee-lembur.approval') }}" class="{{ request()->is('employee-lembur/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval Kerja Lembur</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('employee-leave.approval') }}" class="{{ request()->is('employee-leave/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval Ketidakhadiran</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pengajuan.training.approval') }}" class="{{ request()->is('pengajuan/training/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval Training</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pengajuan.ptk.approval') }}" class="{{ request()->is('pengajuan/ptk/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval PTK</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pengajuan.fpk.approval') }}" class="{{ request()->is('pengajuan/fpk/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval FPK</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
				@endif
				@role('Personnel')
				 <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <h4 class="text-section"> PKWT</h4>
                </li>
                <li class="nav-item {{ request()->is('PKWT') || request()->is('ListPKWT')  ? 'active':'' }}">
                    <a data-toggle="collapse" href="#pkwt">
                        <i class="fas fa-folder-open"></i>
                        <p>PKWT</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('ListPKWT') || request()->is('PKWT') ? 'show':'' }}" id="pkwt">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('ListPKWT.index') }}" class="{{ request()->is('ListPKWT') ? 'active':'' }}">
                                    <span class="sub-item">Data PKWT</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('PKWT.index') }}" class="{{ request()->is('PKWT') ? 'active':'' }}">
                                    <span class="sub-item">Draft PKWT</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
				@endrole
                @can('access-reports')
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fal fa-file-chart-line"></i>
                    </span>
                    <h4 class="text-section">Laporan &amp; Rekap</h4>
                </li>
                <li class="nav-item {{ request()->is('report/*') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#laporan">
                        <i class="fas fa-file-download"></i>
                        <p>Laporan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('report/*') ? 'show':'' }}" id="laporan">
                        <ul class="nav nav-collapse">
                            @can('report-leave')
                            <li>
                                <a href="{{ route('report.leave') }}" class="{{ request()->is('report/leave') ? 'active':'' }}">
                                    <span class="sub-item">Ketidakhadiran</span>
                                </a>
                            </li>
                            @endcan
                            @can('report-leave')
                            <li>
                                <a href="{{ route('report.dinas') }}" class="{{ request()->is('report/dinas') ? 'active':'' }}">
                                    <span class="sub-item">Rekap Dinas Luar</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('report.isoman') }}" class="{{ request()->is('report/isoman') ? 'active':'' }}">
                                    <span class="sub-item">Rekap Pengajuan Isoman</span>
                                </a>
                            </li>
                            @endcan
							@can('report-leave')
							<li>
                                <a href="{{ route('report.lembur') }}" class="{{ request()->is('report/lembur') ? 'active':'' }}">
                                    <span class="sub-item">Rekap Kerja Lembur</span>
                                </a>
                            </li>
							@endcan
							@can('report-leave')
                            <li>
                                <a href="{{ route('report.wfh') }}" class="{{ request()->is('report/wfh') ? 'active':'' }}">
                                    <span class="sub-item">Rekap Kerja Dari Rumah</span>
                                </a>
                            </li>
                            @endcan
							@can('report-leave')
                            <li>
                                <a href="{{ route('report.resign') }}" class="{{ request()->is('report/resign') ? 'active':'' }}">
                                    <span class="sub-item">DB Kuota Cuti Resign</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
				@can('modify-job')
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <h4 class="text-section">Job Portal</h4>
                </li>
                <li class="nav-item {{ request()->is('job') || request()->is('apply') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#job">
                        <i class="fas fa-folder-open"></i>
                        <p>Job Portal</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('job*') || request()->is('apply') ? 'show':'' }}" id="job">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('job.index') }}" class="{{ request()->is('job') ? 'active':'' }}">
                                    <span class="sub-item">Input Job</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('apply.index') }}" class="{{ request()->is('apply') ? 'active':'' }}">
                                    <span class="sub-item">Data Pelamar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
				@can('read-paform')
				<li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <h4 class="text-section">PA & KPI</h4>
                </li>
                <li class="nav-item {{ request()->is('PASUB') || request()->is('PAPeriode') || request()->is('PAForm') || request()->is('PAForm/approval') || request()->is('reportpa') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#PA">
                        <i class="fas fa-folder-open"></i>
                        <p>PA & KPI</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('PASUB') || request()->is('PAPeriode') || request()->is('PAForm') || request()->is('PAForm/approval') || request()->is('reportpa') ? 'active':'' }}" id="PA">
                        <ul class="nav nav-collapse">
							@can('read-pasub')
							<li>
                                <a href="{{ route('PASUB.index') }}" class="{{ request()->is('PASUB') ? 'active':'' }}">
                                    <span class="sub-item">Parameters PA</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('PAPeriode.index') }}" class="{{ request()->is('PAPeriode') ? 'active':'' }}">
                                    <span class="sub-item">Periode PA</span>
                                </a>
                            </li>
							@endcan
                            <li>
                                <a href="{{ route('PAForm.index') }}" class="{{ request()->is('PAForm') ? 'active':'' }}">
                                    <span class="sub-item">Form PA</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('PAForm.approval') }}" class="{{ request()->is('PAForm/approval') ? 'active':'' }}">
                                    <span class="sub-item">Approval PA</span>
                                </a>
                            </li>
							<li>
                                <a href="{{ route('reportpa.index') }}" class="{{ request()->is('reportpa') ? 'active':'' }}">
                                    <span class="sub-item">Report PA</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

				@endcan
                @can('access-settings')
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengaturan</h4>
                </li>
                <li class="nav-item {{ request()->is('acl*') ? 'active':'' }}">
                    <a data-toggle="collapse" href="#Role">
                        <i class="fas fa-lock"></i>
                        <p>Users, Roles, Permissions</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('acl*') ? 'show':'' }}" id="Role">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('role.index') }}" class="{{ request()->is('acl/role') ? 'active':'' }}">
                                    <span class="sub-item">List Roles</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('permission.index') }}" class="{{ request()->is('acl/permission') ? 'active':'' }}">
                                    <span class="sub-item">List Permission</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.index') }}" class="{{ request()->is('acl/user') ? 'active':'' }}">
                                    <span class="sub-item">List User</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('setting*') ? 'active':'' }}">
                    <a href="{{ route('setting.index') }}">
                        <i class="fas fa-cogs"></i>
                        <p>Setting Aplikasi</p>
                    </a>
                </li>
                @endcan
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Misc</h4>
                </li>
                <li class="nav-item {{ request()->is('about-hrms') ? 'active':'' }}">
                    <a href="{{ route('about-hrms') }}">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Tentang Aplikasi</p>
                    </a>
                </li>
				<li class="nav-item {{ request()->is('faq') ? 'active':'' }}">
                    <a href="{{ route('faq.index') }}">
                        <i class="fas fa-question-circle"></i>
                        <p>Frequently Asked Question</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
