
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Print Data Karyawan</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">

  <style>
	.logo-title{
		display: inline-block;
		margin-left: 5px;
		font-size: 14px;
	}
	@media print {
        body {-webkit-print-color-adjust: exact;}
    }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img src="{{ asset('uploads/images/logo.png') }}" width="60" height="70" style="display:inline-block;vertical-align: top;" />
          <div class="logo-title">
            <span style="font-size:20px"><strong>{{ $app_settings->get('company_name') }}</strong></span>
            <br>
            {{ $app_settings->get('company_address') }} <br>
            Telepon : {{ $app_settings->get('company_phone') }} <br>
            Email : {{ $app_settings->get('company_email') }}
          </div>
          <small class="pull-right">Dicetak oleh : {{ !empty(auth()->user()->employee->fullname) ? auth()->user()->employee->fullname:'Administrator' }} pada 
            {{ date('d-m-Y H:i:s') }}
          </small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
	<div class="row">
	<div style="text-align:center;">
		<h5><b>LAPORAN DATA KARYAWAN</b></h5>
	</div>
	</div>
    <!-- Table row -->
    <div class="row">
    <center>
	  <div class="table-responsive" style="width:95%;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Masuk Kerja</th>
                    <th>Nama Karyawan</th>
                    <th>Divisi</th>
                    <th>Department</th>
                    <th>Grade Title</th>
                    <th>Job Title</th>
                    <th>Grade</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Nomor KTP</th>
                    <th>Jenis Kelamin</th>
                    <th>No Telp</th>
                    <th>Rekening</th>
                    <th>NPWP</th>
                    <th>Post Gaji</th>
                    <th>Gaji Pokok</th>
                    <th>Tipe Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>{{ $employee->registration_number }}</td>
                        <td>{{ $employee->date_of_work }}</td>
                        <td>{{ $employee->fullname }}</td>
                        <td>{{ $employee->division->division_code }}</td>
                        <td>{{ $employee->department->department_code }}</td>
                        <td>{{ $employee->grade_title->grade_title_code }}</td>
                        <td>{{ !$employee->job_title ? 'Error':$employee->job_title->job_title_code }}</td>
                        <td>{{ $employee->grade }}</td>
                        <td>{{ $employee->level }}</td>
                        <td>{{ $employee->status }}</td>
                        <td>{{ $employee->employee_detail->place_of_birth }}</td>
                        <td>{{ $employee->employee_detail->date_of_birth }}</td>
                        <td>{{ $employee->employee_detail->ID_number }}</td>
                        <td>{{ $employee->employee_detail->sex }}</td>
                        <td>{{ $employee->employee_detail->phone_number }}</td>
                        <td>{{ '' }}</td>
                        <td>{{ $employee->employee_detail->npwp }}</td>
                        <td>{{ !$employee->employee_salary ? 'Error':$employee->employee_salary->salary_post }}</td>
                        <td>{{ !$employee->employee_salary ? 'Error':$employee->employee_salary->basic_salary }}</td>
                        <td>{{ !$employee->employee_salary ? 'Error':$employee->employee_salary->payroll_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </center>
      <!-- /.col -->
	  <div class="row no-print">
          <div class="margin pull-right">
            <small>*Untuk data yang lengkap silahkan gunakan export Excel</small>
			<button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Print & PDF</button>
            <button onclick="window.close()" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Tutup</button>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>