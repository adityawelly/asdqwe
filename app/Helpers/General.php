<?php

if (!function_exists('status_text')) {
    function status_text($code)
    {
        if ($code == 5) {
            return '<span class="badge badge-warning">Pending</span>';
        }elseif ($code == 10) {
            return '<span class="badge badge-success">Approved Atasan</span>';
        }elseif ($code == 15) {
            return '<span class="badge badge-danger">Rejected Atasan</span>';
        }elseif ($code == 20) {
            return '<span class="badge badge-success">Final Approved</span>';
        }elseif ($code == 25) {
            return '<span class="badge badge-danger">Rejected Training SPV</span>';
        }else {
            return '<span class="badge badge-default">Unavailable</span>';
        }
    }
}

if (!function_exists('to_currency')) {
    function to_currency($number, $code)
    {
        if (!$number) {
            return 'Tidak Tersedia';
        }
        if ($code == 'IDR') {
            return 'Rp. '.number_format($number, 0, ',', '.');
        }
    }
}

if (!function_exists('tgl_indonesia')) {
	function tgl_indonesia($string){
    // contoh : 2019-01-30 10:20:20
    
    $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];
 
    $date = explode(" ", $string)[0];
    $time = explode(" ", $string)[1];
    
    $tanggal = explode("-", $date)[2];
    $bulan = explode("-", $date)[1];
    $tahun = explode("-", $date)[0];
    
    
 
    return $tanggal . " " . $bulanIndo[abs($bulan)] . " " . $tahun;
	}
}

if (!function_exists('tgl_indo')) {
	function tgl_indo($date){
		$bulan = array (
			1 => 'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $date); 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
}

if (!function_exists('terbilang')) {
	function terbilang($angka) {
	   $angka=abs($angka);
	   $baca =array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	 
	   $terbilang="";
		if ($angka < 12){
			$terbilang= " " . $baca[$angka];
		}
		else if ($angka < 20){
			$terbilang= terbilang($angka - 10) . " belas";
		}
		else if ($angka < 100){
			$terbilang= terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
		}
		else if ($angka < 200){
			$terbilang= " seratus" . terbilang($angka - 100);
		}
		else if ($angka < 1000){
			$terbilang= terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
		}
		else if ($angka < 2000){
			$terbilang= " seribu" . terbilang($angka - 1000);
		}
		else if ($angka < 1000000){
			$terbilang= terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
		}
		else if ($angka < 1000000000){
		   $terbilang= terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
		}
		   return $terbilang;
	}
}

if (!function_exists('opname_status_text')) {
    function opname_status_text($code)
    {
        if ($code == 'new') {
            return '<span class="badge badge-primary">Baru</span>';
        }elseif ($code == 'paid') {
            return '<span class="badge badge-success">Dibayar</span>';
        }else{
            return '<span class="badge badge-default">Unavailable</span>';
        }
    }
}

if (!function_exists('random_string')) {
    function random_string($length = 1)
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}

if (!function_exists('ptk_status')) {
    function ptk_status($code, $text_only = false)
    {
        if ($code == 0) {
            return $text_only ? 'Open':'<span class="badge badge-success">Open</span>';
        }elseif ($code == 1) {
            return $text_only ? 'Close':'<span class="badge badge-secondary">Close</span>';
        }elseif ($code == 2) {
            return $text_only ? 'Ditolak':'<span class="badge badge-danger">Ditolak</span>';
        }else{
            return $text_only ? 'Unavailable':'<span class="badge badge-default">Unavailable</span>';
        }
    }
}

if (!function_exists('leave_status')) {
    function leave_status($status, $html = true)
    {
        if ($status == 'new') {
            $color = 'warning';
            $text = 'Menunggu';
        }elseif ($status == 'apv') {
            $color = 'success';
            $text = 'Diterima';
        }elseif ($status == 'rjt') {
            $color = 'danger';
            $text = 'Ditolak';
        }else{
            $color = 'default';
            $text = 'Undefined';
        }

        if ($html) {
            return '<span class="badge badge-'.$color.'">'.$text.'</span>';
        }

        return $text;
    }
}

if (!function_exists('fpk_status')) {
    function fpk_status($status, $status2, $status3, $status4)
    {
        if ($status == 1) {
            $text = 'Perpanjangan Kontrak Kerja';
        }
		if ($status2 == 1) {
            $text = 'Habis Kontrak';
        }
		if ($status3 == 1) {
            $text = 'Promosi';
        }
		if ($status4 == 1) {
            $text = 'Perubahan Status';
        }
        return $text;
    }
}

if (!function_exists('resign_status_text')) {
    function resign_status_text($code)
    {
        if ($code == 'rsgn') {
            return '<span class="badge badge-danger">Resign</span>';
        }elseif ($code == 'paid') {
            return '<span class="badge badge-success">Dibayar</span>';
        }else{
            return '';
        }
    }
}
