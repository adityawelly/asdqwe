<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('karyawan', function ($trail) {
    $trail->push('Karyawan', route('employee.index'));
});

Breadcrumbs::for('resign', function ($trail) {
    $trail->parent('karyawan');
    $trail->push('Karyawan Resign', route('employee.retirement'));
});

Breadcrumbs::for('karyawan-create', function ($trail) {
    $trail->parent('karyawan');
    $trail->push('Tambah Karyawan', route('employee.create'));
});

Breadcrumbs::for('karyawan-edit', function ($trail, $id) {
    $trail->parent('karyawan');
    $trail->push('Edit Karyawan', route('employee.update', $id));
});

Breadcrumbs::for('karyawan-show', function ($trail, $id) {
    $trail->parent('karyawan');
    $trail->push('Profil Karyawan', route('employee.show', $id));
});

Breadcrumbs::for('divisi', function ($trail) {
    $trail->push('Divisi', route('division.index'));
});

Breadcrumbs::for('periode', function ($trail) {
    $trail->push('Periode Penilaian', route('PAPeriode.index'));
});


Breadcrumbs::for('faq', function ($trail) {
    $trail->push('FAQ', route('faq.index'));
});

Breadcrumbs::for('cuti', function ($trail) {
    $trail->push('Kategori Ketidakhadiran', route('leave.index'));
});

Breadcrumbs::for('approve-cuti', function ($trail) {
    $trail->push('Approve Ketidakhadiran', route('employee-leave.approval'));
});

Breadcrumbs::for('approve-dinas', function ($trail) {
    $trail->push('Approve Izin Dinas Luar', route('employee-dinas.approval'));
});

Breadcrumbs::for('approve-isoman', function ($trail) {
    $trail->push('Approve Pengajuan Isolasi Mandiri', route('employee-isoman.approval'));
});


Breadcrumbs::for('approve-wfh', function ($trail) {
    $trail->push('Approve Kerja Dari Rumah', route('employee-wfh.approval'));
});

Breadcrumbs::for('approve-lembur', function ($trail) {
    $trail->push('Approve Kerja Lembur', route('employee-lembur.approval'));
});
Breadcrumbs::for('pengajuan-ijin', function ($trail) {
    $trail->push('Pengajuan Izin', route('employee-leave.create', 'ijin'));
});

Breadcrumbs::for('pengajuan-cuti', function ($trail) {
    $trail->push('Pengajuan Cuti', route('employee-leave.create', 'cuti'));
});

Breadcrumbs::for('pengajuan-dinas', function ($trail) {
    $trail->push('Pengajuan Izin Dinas Luar', route('employee-dinas.create'));
});

Breadcrumbs::for('pengajuan-isoman', function ($trail) {
    $trail->push('Pengajuan Isolasi Mandiri', route('employee-isoman.create'));
});


Breadcrumbs::for('pengajuan-wfh', function ($trail) {
    $trail->push('Pengajuan Kerja Dari Rumah', route('employee-wfh.create'));
});

Breadcrumbs::for('Draft-PKWT', function ($trail) {
    $trail->push('Draft Perjanjian Kerja Waktu Tertentu', route('PKWT.index'));
});

Breadcrumbs::for('PASUB', function ($trail) {
    $trail->push('Parameters PA', route('PASUB.index'));
});

Breadcrumbs::for('PAFORM', function ($trail) {
    $trail->push('Form Penilaian', route('PAForm.index'));
});

Breadcrumbs::for('pengajuan-lembur', function ($trail) {
    $trail->push('Pengajuan Kerja Lembur', route('employee-lembur.create'));
});

Breadcrumbs::for('pengajuan-cuti-khusus', function ($trail) {
    $trail->push('Pengajuan Cuti Khusus', route('employee-leave.create', 'khusus'));
});

Breadcrumbs::for('daftar-cuti', function ($trail) {
    $trail->push('Daftar Ketidakhadiran', route('employee-leave.index'));
});

Breadcrumbs::for('departemen', function ($trail) {
    $trail->push('Departemen', route('department.index'));
});

Breadcrumbs::for('job-title', function ($trail) {
    $trail->push('Job Title', route('job-title.index'));
});

Breadcrumbs::for('grade-title', function ($trail) {
    $trail->push('Grade Title', route('grade-title.index'));
});

Breadcrumbs::for('level-title', function ($trail) {
    $trail->push('Level Title', route('level-title.index'));
});

Breadcrumbs::for('company-region', function ($trail) {
    $trail->push('Company Region', route('company-region.index'));
});

Breadcrumbs::for('role', function ($trail) {
    $trail->push('Role', route('role.index'));
});

Breadcrumbs::for('role-permissions', function ($trail, $id) {
    $trail->parent('role');
    $trail->push('Role Permissions', route('role.permissions', $id));
});

Breadcrumbs::for('permission', function ($trail) {
    $trail->push('Permission', route('permission.index'));
});

Breadcrumbs::for('user', function ($trail) {
    $trail->push('User', route('user.index'));
});

Breadcrumbs::for('setting', function ($trail) {
    $trail->push('Setting Aplikasi', route('setting.index'));
});

Breadcrumbs::for('user-edit', function ($trail, $id) {
    $trail->parent('user');
    $trail->push('Edit User', route('user.edit', $id));
});

Breadcrumbs::for('account', function ($trail) {
    $trail->push('Profil Saya', route('account.index'));
});

Breadcrumbs::for('setting-account', function ($trail) {
    $trail->push('Pengaturan', route('account.setting'));
});

Breadcrumbs::for('training', function ($trail) {
    $trail->push('Data Training', route('training.index'));
});

Breadcrumbs::for('report-leave', function ($trail) {
    $trail->push('Laporan Ketidakhadiran', route('report.leave'));
});

Breadcrumbs::for('report-dinas', function ($trail) {
    $trail->push('Rekap Izin Dinas Luar', route('report.dinas'));
});

Breadcrumbs::for('report-isoman', function ($trail) {
    $trail->push('Rekap Pengajuan Isolasi Mandiri', route('report.isoman'));
});

Breadcrumbs::for('reportpa', function ($trail) {
    $trail->push('Laporan Penilaian Karyawan', route('reportpa.index'));
});

Breadcrumbs::for('report-wfh', function ($trail) {
    $trail->push('Rekap Kerja Dari Rumah', route('report.wfh'));
});

Breadcrumbs::for('report-lembur', function ($trail) {
    $trail->push('Rekap Kerja Lembur Karyawan', route('report.lembur'));
});

Breadcrumbs::for('report-resign', function ($trail) {
    $trail->push('Kuota Cuti Karyawan Resign', route('report.resign'));
});

Breadcrumbs::for('events', function ($trail) {
    $trail->push('Kalender Event', url('events'));
});

Breadcrumbs::for('pengajuan-training', function ($trail) {
    $trail->push('Pengajuan Training', route('pengajuan.training'));
});

Breadcrumbs::for('pengajuan-ptk', function ($trail) {
    $trail->push('List PTK', route('pengajuan.ptk'));
});

Breadcrumbs::for('pengajuan-fpk', function ($trail) {
    $trail->push('List FPK', route('pengajuan.fpk'));
});

Breadcrumbs::for('input-job', function ($trail) {
    $trail->push('Input-Job', route('job.index'));
});

Breadcrumbs::for('data-pelamar', function ($trail) {
    $trail->push('Data-Pelamar', route('apply.index'));
});

Breadcrumbs::for('holiday', function ($trail) {
    $trail->push('Hari Libur', route('holiday.index'));
});

Breadcrumbs::for('about', function ($trail) {
    $trail->push('Tentang Aplikasi', route('about-hrms'));
});
