<footer class="footer">
    <div class="container-fluid">
        <div class="pull-left">
            Last Login : {{ date('d F Y H:i:s', strtotime(auth()->user()->last_login_at)) }} from {{ auth()->user()->last_login_ip }}
        </div>
        <div class="copyright ml-auto">
		Copyright <i class="fa fa-copyright" ></i> {{ date('Y') }}  | <a href="#">HCM PT. Niramas Utama</a>
        </div>				
    </div>
</footer>