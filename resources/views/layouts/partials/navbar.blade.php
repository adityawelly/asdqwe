<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">				
    <div class="container-fluid">
        <div class="collapse" id="search-nav">
		<!--
            <form class="navbar-left navbar-form nav-search mr-md-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pr-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" placeholder="Search ..." class="form-control">
                </div>
            </form>
			-->
        </div>
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
		
            <li class="nav-item toggle-nav-search hidden-caret">
                <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                    <i class="fa fa-search"></i>
                </a>
            </li>
			
 <!--
            <li class="nav-item dropdown hidden-caret">
                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                </a>
                <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                    <li>
                        <div class="dropdown-title d-flex justify-content-between align-items-center">
                            Messages 									
                            <a href="#" class="small">Mark all as read</a>
                        </div>
                    </li>
                    <li>
                        <div class="message-notif-scroll scrollbar-outer">
                            <div class="notif-center">
                                <a href="#">
                                    <div class="notif-img"> 
                                        <img src="{{ asset('img/jm_denis.jpg') }}" alt="Img Profile">
                                    </div>
                                    <div class="notif-content">
                                        <span class="subject">Jimmy Denis</span>
                                        <span class="block">
                                            How are you ?
                                        </span>
                                        <span class="time">5 minutes ago</span> 
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-img"> 
                                        <img src="{{ asset('img/chadengle.jpg') }}" alt="Img Profile">
                                    </div>
                                    <div class="notif-content">
                                        <span class="subject">Chad</span>
                                        <span class="block">
                                            Ok, Thanks !
                                        </span>
                                        <span class="time">12 minutes ago</span> 
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-img"> 
                                        <img src="{{ asset('img/mlane.jpg') }}" alt="Img Profile">
                                    </div>
                                    <div class="notif-content">
                                        <span class="subject">Jhon Doe</span>
                                        <span class="block">
                                            Ready for the meeting today...
                                        </span>
                                        <span class="time">12 minutes ago</span> 
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-img"> 
                                        <img src="{{ asset('img/talha.jpg') }}" alt="Img Profile">
                                    </div>
                                    <div class="notif-content">
                                        <span class="subject">Talha</span>
                                        <span class="block">
                                            Hi, Apa Kabar ?
                                        </span>
                                        <span class="time">17 minutes ago</span> 
                                    </div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a class="see-all" href="javascript:void(0);">See all messages<i class="fa fa-angle-right"></i> </a>
                    </li>
                </ul>
            </li>
    -->
            <li class="nav-item dropdown hidden-caret">
                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    @if (count($unreads) > 0)
                        <span class="notification">{{ count($unreads) }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">Anda punya {{ $countUnreads }} notifikasi baru</div>
                    </li>
                    <li>
                        <div class="notif-scroll scrollbar-outer">
                            <div class="notif-center">
                                @if (count($unreads) == 0)
                                    <a href="#">
                                        <div class="notif-content">
                                            <span class="block">
                                                Tidak ada notifikasi
                                            </span>
                                        </div>
                                    </a>
                                @else
                                    @foreach ($unreads as $item)
                                        <a href="#">
                                            <div class="notif-content">
                                                <span class="block">
                                                    {!! $item->data['msg'] !!}
                                                </span>
                                                <span class="time">
                                                    {{ \Carbon\Carbon::parse($item->data['send_at'])->diffForHumans(now()) }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </li>
                    <li>
                        <a class="see-all" href="{{ route('account.notification') }}">Lihat Semua<i class="fa fa-angle-right"></i> </a>
                    </li>
                    <li>
                        <form action="{{ route('account.marksRead') }}" method="post" id="marksReadForm">
                            @csrf
                            <input type="hidden" name="marksRead" value="true">
                        </form>
                        <a class="see-all text-primary" href="javascript:void(0)" onclick="document.getElementById('marksReadForm').submit()">Tandai Sudah Dibaca<i class="fa fa-check-circle"></i> </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown hidden-caret">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fas fa-layer-group"></i>
                </a>
                <div class="dropdown-menu quick-actions quick-actions-info animated fadeIn">
                    <div class="quick-actions-header">
                        <span class="title mb-1">Aksi Cepat</span>
                        <span class="subtitle op-8">Pintasan</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                        <div class="quick-actions-items">
                            <div class="row m-0">
                                <a class="col-6 col-md-4 p-0" href="{{ route('employee-leave.create', 'cuti') }}">
                                    <div class="quick-actions-item">
                                        <i class="flaticon-pen"></i>
                                        <span class="text">Pengajuan Cuti</span>
                                    </div>
                                </a>
                                <a class="col-6 col-md-4 p-0" href="{{ route('employee-leave.create', 'cuti') }}">
                                    <div class="quick-actions-item">
                                        <i class="flaticon-pen"></i>
                                        <span class="text">Pengajuan Izin</span>
                                    </div>
                                </a>
                                <a class="col-6 col-md-4 p-0" href="{{ route('employee-leave.index') }}">
                                    <div class="quick-actions-item">
                                        <i class="flaticon-list"></i>
                                        <span class="text">Daftar Ketidakhadiran</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{ asset(empty(auth()->user()->employee->photo) ? 'uploads/images/profile-avatar-flat.png' : 'uploads/images/users/'.auth()->user()->employee->photo) }}" alt="User Profile" class="avatar-img rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <a href="{{ asset(empty(auth()->user()->employee->photo) ? 'uploads/images/profile-avatar-flat.png' : 'uploads/images/users/'.auth()->user()->employee->photo) }}"
                                        data-lightbox="{{ time() }}" data-title="Foto Diri">
                                        <img src="{{ asset(empty(auth()->user()->employee->photo) ? 'uploads/images/profile-avatar-flat.png' : 'uploads/images/users/'.auth()->user()->employee->photo) }}" alt="User Profile" class="avatar-img rounded">
                                    </a>
                                </div>
                                <div class="u-text">
                                    <h4>{{ !empty(auth()->user()->employee->fullname) ? auth()->user()->employee->fullname:'Administrator' }}</h4>
                                    <p class="text-muted">{{ auth()->user()->email }}</p><a href="{{ route('account.index') }}" class="btn btn-xs btn-secondary btn-sm">Lihat Profil</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <form id="logoutForm" action="{{ route('logout') }}" method="post">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit()">Logout</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- End Navbar -->
