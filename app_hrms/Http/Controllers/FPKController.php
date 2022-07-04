<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use Exception;
use App\Models\Employee;
use App\Models\EmployeeMaster;
use App\Models\JobTitle;
use App\Exports\FPKExport;
use App\Models\Department;
use App\Models\GradeTitle;
use App\Models\GroupJobtitle;
use App\Models\GroupAtasan;
use App\Models\HCApproval;
use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Notifications\NotifNewFPK;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\NotifApprovalFPK;

class FPKController extends Controller
{
    
    public function cetak($id)
    {
			$employee_id = auth()->user()->employee->id;
						
		    $pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru,
			b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
			b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
			f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
			job_titles as f , departments as g, departments as h, company_regions as i, employees as j
			where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
			a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
			and a.Atasan_baru=j.id and  a.ReqId = ?',[$id]);
			
			$atasan = DB::table('f_job_newer')->where('ReqId', $id)->get();
			foreach ($atasan as $atasan) {
				$nik = $atasan->Nik;
				$ds = $atasan->DirectSuperior;
			}
			
			$ats_lsg = DB::table('employees')->where('id', $ds)->get();
			foreach ($ats_lsg as $ats_lsg) {
					$ids = $ats_lsg->direct_superior;			
				}
				
			$mgr = DB::table('employees')->where('id', $ids)->get();
			foreach ($mgr as $mgr) {
					$ism = $mgr->direct_superior;			
				}
			
			$dir = DB::table('employees')->where('id', $ism)->get();
			foreach ($dir as $dir) {
					$idm = $dir->direct_superior;			
				}
				
			$facilities = DB::table('f_job_newer_facility')->where('Req_id', $id)->get();
						
			if (!empty($ds))
			{			
			$ats_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ds
                        order by a.`ApprovalDate` desc)", [$id]);	
			}
			
			if (!empty($ids))
			{
			$ats_td_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ids
                        order by a.`ApprovalDate` desc)", [$id]);	
			}
			if (!empty($ism))
			{
			$mgr_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ism
                        order by a.`ApprovalDate` desc)", [$id]);
            
            $mgr_jab = DB::select("(select a.`ApprovalSts`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ism
                        order by a.`ApprovalDate` desc)", [$id]);
			}
			if (!empty($idm))
			{
			$dir_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=0 and a.EmployeeId=$idm
                        order by a.`ApprovalDate` desc)", [$id]);
            
            $dir_jab = DB::select("(select a.`ApprovalSts`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=0 and a.EmployeeId=$idm
                        order by a.`ApprovalDate` desc)", [$id]);
			}
			
			$dir_hc = DB::select('(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=1 and a.EmployeeId=35
                        order by a.`ApprovalDate` desc)', [$id]);

			$mgr_hc = DB::select('(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`ReqId` = ? and a.IsHcFlag=1 and a.EmployeeId=9
                        order by a.`ApprovalDate` desc)', [$id]);

			$pdf = PDF::loadView('pengajuan.fpk_cetak', compact('pengajuan','ats_lsng','ats_td_lsng','mgr_lsng','mgr_jab','dir_lsng','dir_jab','facilities','dir_hc','mgr_hc'))->setPaper('a4', 'potrait');
		    return $pdf->stream();
	}
	
	
	public function generate_pkwt($id)
    {
		$employee_id = auth()->user()->employee->id;
		
				$pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru, k.hk,
							b.employee_id, b.job_title_code, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
							b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
							f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city from 
							f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
							job_titles as f , departments as g, departments as h, company_regions as i, employees as j, employee_hks as k
							where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
							a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
							and b.employee_id = k.employee_no and a.Atasan_baru=j.id and  a.ReqId = ?',[$id]);
				$pengajuan = $pengajuan[0];
		
				$bulan = date("m", strtotime($pengajuan->Insert_date));
				$tahun = date("Y", strtotime($pengajuan->Insert_date));
				
				$now = now();
				
				//$year = $now->year;
										
				if($bulan == 1 ) $romawibulan = "I";
				else if($bulan == 2 ) $romawibulan = "II";
				else if($bulan == 3 ) $romawibulan = "III";
				else if($bulan == 4 ) $romawibulan = "IV";
				else if($bulan == 5 ) $romawibulan = "V";
				else if($bulan == 6 ) $romawibulan = "VI";
				else if($bulan == 7 ) $romawibulan = "VII";
				else if($bulan == 8 ) $romawibulan = "VIII";
				else if($bulan == 9 ) $romawibulan = "IX";
				else if($bulan == 10 ) $romawibulan = "X";
				else if($bulan == 11 ) $romawibulan = "XI";
				else if($bulan == 12 ) $romawibulan = "XII";
										
				$maxNo = DB::select("select case
					when coalesce(max(left(`pkwt_no`, 3)), 0)+1 = 1000 then 1
					else coalesce(max(left(`pkwt_no`, 3)), 0)+1
					end as maxID from list_pkwt where pkwt_no like '%$tahun%'");
												
					$no = sprintf($maxNo[0]->maxID);
										
					$nomor = sprintf('%03d', $maxNo[0]->maxID).'/PKWT/NU/HC/'.$romawibulan.'/'.$tahun;
										
					DB::table('f_job_newer')->where(['ReqId' => $id ])->update([
						'pkwt_no' => $nomor,
						'konter_pkwt' => $no,
						'NextApproval' => NULL,
						]);	
						
					$tgl_skg = tgl_indo(date('Y-m-d'));
					$tgl_eff = tgl_indo($pengajuan->Eff_date);
					$jabatan_baru = $pengajuan->job_title_code;					
					$lokasi_baru = $pengajuan->Lokasi_baru;
					$employee = $pengajuan->Nik;
					$hk = $pengajuan->hk;
					$note = $pengajuan->note_kontrak;
					$sdate = $pengajuan->Eff_date;
					$kontrak_ke = $pengajuan->kontrak_ke;
					
					if($pengajuan->note_kontrak == 3)
					{
					   $edate = date('Y-m-d', strtotime('+3 month', strtotime($sdate)));
					}
					else if($pengajuan->note_kontrak == 6)
					{
						$edate = date('Y-m-d', strtotime('+6 month', strtotime($sdate)));
					}
					else if($pengajuan->note_kontrak == 9)
					{
						$edate = date('Y-m-d', strtotime('+9 month', strtotime($sdate)));
					}
					else if($pengajuan->note_kontrak == 12)
					{
						$edate = date('Y-m-d', strtotime('+12 month', strtotime($sdate)));
					}
					
					
					DB::table('list_pkwt')->insert([
						'fpk_id' => $id,
						'pkwt_no' => $nomor,
						'employee_id' => $employee,
						'job_title_id' => $jabatan_baru,
						'location_id' => $lokasi_baru,
						'hk_id' => $hk,
						'sdate' => $sdate,
						'edate' => $edate,
						'bulan' => $note,
						'no_reff' => $pengajuan->fpk_no,
						'kontrak_ke' => $kontrak_ke,
						'created_by' => $employee_id,
						'created_at' => $now,
					]);
					
					
				return redirect(route('pengajuan.fpk'))->with('alert', [
					'type' => 'success',
					'msg' => 'Berhasil Update Status SK',
					]);
	}
	
	
	public function generate_sphk($id)
    {
		$employee_id = auth()->user()->employee->id;
		
				$pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru, k.hk,
							b.employee_id, b.job_title_code, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
							b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
							f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city from 
							f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
							job_titles as f , departments as g, departments as h, company_regions as i, employees as j, employee_hks as k
							where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
							a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
							and b.employee_id = k.employee_no and a.Atasan_baru=j.id and  a.ReqId = ?',[$id]);
				$pengajuan = $pengajuan[0];
		
				$bulan = date("m", strtotime($pengajuan->Insert_date));
				$tahun = date("Y", strtotime($pengajuan->Insert_date));
				
				$now = now();
				
				//$year = $now->year;
										
				if($bulan == 1 ) $romawibulan = "I";
				else if($bulan == 2 ) $romawibulan = "II";
				else if($bulan == 3 ) $romawibulan = "III";
				else if($bulan == 4 ) $romawibulan = "IV";
				else if($bulan == 5 ) $romawibulan = "V";
				else if($bulan == 6 ) $romawibulan = "VI";
				else if($bulan == 7 ) $romawibulan = "VII";
				else if($bulan == 8 ) $romawibulan = "VIII";
				else if($bulan == 9 ) $romawibulan = "IX";
				else if($bulan == 10 ) $romawibulan = "X";
				else if($bulan == 11 ) $romawibulan = "XI";
				else if($bulan == 12 ) $romawibulan = "XII";
										
				$maxNo = DB::select("select case
					when coalesce(max(left(`sphk_no`, 3)), 0)+1 = 1000 then 1
					else coalesce(max(left(`sphk_no`, 3)), 0)+1
					end as maxID from list_habis_pkwt where sphk_no like '%$tahun%'");
												
					$no = sprintf($maxNo[0]->maxID);
										
					$nomor = sprintf('%03d', $maxNo[0]->maxID).'/SPHK/NU/'.$romawibulan.'/'.$tahun;
					
					DB::table('f_job_newer')->where(['ReqId' => $id ])->update([
						'pkwt_no' => $nomor,
						'konter_pkwt' => $no,
						'NextApproval' => NULL,
						]);	
														
					$tgl_skg = tgl_indo(date('Y-m-d'));
					$tgl_eff = tgl_indo($pengajuan->Eff_date);
					$jabatan_baru = $pengajuan->job_title_code;					
					$lokasi_baru = $pengajuan->Lokasi_baru;
					$employee = $pengajuan->Nik;
					$hk = $pengajuan->hk;
					$note = $pengajuan->note_kontrak;
					$sdate = $pengajuan->Eff_date;
					$kontrak_ke = $pengajuan->kontrak_ke;
					
					if($pengajuan->note_kontrak == 3)
					{
					   $edate = date('Y-m-d', strtotime('+3 month', strtotime($sdate)));
					}
					else if($pengajuan->note_kontrak == 6)
					{
						$edate = date('Y-m-d', strtotime('+6 month', strtotime($sdate)));
					}
					else if($pengajuan->note_kontrak == 9)
					{
						$edate = date('Y-m-d', strtotime('+9 month', strtotime($sdate)));
					}
					else if($pengajuan->note_kontrak == 12)
					{
						$edate = date('Y-m-d', strtotime('+12 month', strtotime($sdate)));
					}
					
					
					DB::table('list_habis_pkwt')->insert([
						'fpk_id' => $id,
						'sphk_no' => $nomor,
						'employee_id' => $employee,
						'job_title_id' => $jabatan_baru,
						'location_id' => $lokasi_baru,
						'sdate' => $sdate,
						'created_by' => $employee_id,
						'created_at' => $now,
					]);
					
					
				return redirect(route('pengajuan.fpk'))->with('alert', [
					'type' => 'success',
					'msg' => 'Berhasil Membuat Draft SPHK',
					]);
	}
	
	public function generate_sk($id)
    {
		$employee_id = auth()->user()->employee->id;
		
				$pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru,
							b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
							b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
							f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city from 
							f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
							job_titles as f , departments as g, departments as h, company_regions as i, employees as j
							where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
							a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
							and a.Atasan_baru=j.id and  a.ReqId = ?',[$id]);
				$pengajuan = $pengajuan[0];
		
				$bulan = date("m", strtotime($pengajuan->Insert_date));
				$tahun = date("Y", strtotime($pengajuan->Insert_date));
				
				//$now = now();
				
				//$year = $now->year;
										
				if($bulan == 1 ) $romawibulan = "I";
				else if($bulan == 2 ) $romawibulan = "II";
				else if($bulan == 3 ) $romawibulan = "III";
				else if($bulan == 4 ) $romawibulan = "IV";
				else if($bulan == 5 ) $romawibulan = "V";
				else if($bulan == 6 ) $romawibulan = "VI";
				else if($bulan == 7 ) $romawibulan = "VII";
				else if($bulan == 8 ) $romawibulan = "VIII";
				else if($bulan == 9 ) $romawibulan = "IX";
				else if($bulan == 10 ) $romawibulan = "X";
				else if($bulan == 11 ) $romawibulan = "XI";
				else if($bulan == 12 ) $romawibulan = "XII";
										
				$maxNo = DB::select("select case
					when coalesce(max(left(`sk_no`, 3)), 0)+1 = 1000 then 1
					else coalesce(max(left(`sk_no`, 3)), 0)+1
					end as maxID from f_job_newer where fpk_no like '%$tahun%'");
												
					$no = sprintf($maxNo[0]->maxID);
										
					$nomor = sprintf('%03d', $maxNo[0]->maxID).'/SK/HCM/NU/'.$romawibulan.'/'.$tahun;
										
					DB::table('f_job_newer')->where(['ReqId' => $id ])->update([
						'sk_no' => $nomor,
						'konter_sk' => $no,
						'NextApproval' => NULL,
						]);	
						
					$tgl_skg = tgl_indo(date('Y-m-d'));
					$tgl_eff = tgl_indo($pengajuan->Eff_date);
					$jabatan_lama = $pengajuan->dept_baru;
					$jabatan_baru = $pengajuan->jab_baru;					
					$level_lama = $pengajuan->job_title_name;
					$level_baru = $pengajuan->jab_baru;
					$grade_lama = $pengajuan->Level_lama;
					$grade_baru = $pengajuan->Level;
					$kelas_lama = $pengajuan->Kelas_lama;
					$kelas_baru = $pengajuan->Kelas_baru;
					$lokasi_lama = $pengajuan->Lokasi_lama;
					$lokasi_baru = $pengajuan->region_city;
					
					
						if ($pengajuan->promosi == 1)
						{
							$isi = '<b>Promosi</b>';
							$halx = $isi.' '.$jabatan_baru.'dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b>';
						}
						if ($pengajuan->demosi == 1)  
						{
							$isi = '<b>Demosi</b>';
							$halx = $isi.' '.$jabatan_baru.'dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b>';
						}
						if ($pengajuan->mutasi == 1) 
						{	
							if($level_lama != $level_baru)
							{						
								$isi = '<b>Mutasi</b>';
								$halx = $isi.' dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';
							}
							else
							{
								$isi = '<b>Mutasi Area</b>';
								$halx = $isi.' dari <b>'.$lokasi_lama.'</b> ke <b>'.$lokasi_baru.'</b>';
							}
						}
						if ($pengajuan->perubahan_job == 1) 
						{
						   $isi = '<b>Perubahan Job Title</b>';
						   $halx = $isi.' dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';
						}
						if ($pengajuan->perubahan_status == 1) 
						{
							$isi= '<b>Perubahan Status Tetap</b>';
							$halx= '<b>Karyawan Tetap</b>';
						}
						//promosi
						if (($pengajuan->promosi == 1) && ($pengajuan->perubahan_status == 1))
						{
							$isi= '<b>Promosi</b> dan <b>Perubahan Status Tetap</b>';
							$halx= 'Promosi dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b> dan Menjadi <b>Karyawan Tetap</b>';
						}
						if (($pengajuan->promosi == 1) && ($pengajuan->perubahan_job == 1))
						{
							$isi= '<b>Promosi</b> dan <b>Perubahan Job Title</b>';
							$halx= '<b>Perubahan Job Title</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b> dan <b>Promosi</b> dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b>';
						}
						if (($pengajuan->promosi == 1) && ($pengajuan->mutasi == 1))
						{
							if($level_lama != $level_baru)
							{						
								$isi = '<b>Promosi</b> dan <b>Mutasi</b>';
								$halx = '<b>Promosi</b> dari <b>Grade ('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan <b>Mutasi</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';
							}
							else
							{
								$isi = '<b>Promosi</b> dan <b>Mutasi Area</b>';
								$halx = '<b>Promosi</b> dari <b>Grade ('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan <b>Mutasi Area</b> dari <b>'.$lokasi_lama.'</b> ke <b>'.$lokasi_baru.'</b>';
							}
						}
						//endpromosi
						//mutasi
						if (($pengajuan->mutasi == 1) && ($pengajuan->perubahan_status == 1))
						{
							if($level_lama != $level_baru)
							{						
								$isi = '<b>Mutasi</b> dan <b>Perubahan Status Tetap</b>';
								$halx = '<b>Mutasi</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b> dan Menjadi <b>Karyawan Tetap</b>';
							}
							else
							{
								$isi = '<b>Promosi</b> dan <b>Mutasi Area</b>';
								$halx = '<b>Mutasi Area</b> dari <b>'.$lokasi_lama.'</b> ke <b>'.$lokasi_baru.'</b> dan Menjadi <b>Karyawan Tetap</b>';
							}							
						}
						if (($pengajuan->mutasi == 1) && ($pengajuan->perubahan_job == 1))
						{
							if($level_lama != $level_baru)
							{						
								$isi = '<b>Mutasi</b> dan <b>Perubahan Job Title</b>';
								$halx = '<b>Mutasi</b> dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan <b>Perubahan Job Title</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';
							}
							else
							{
								$isi = '<b>Perubahan Job Title</b> dan <b>Mutasi Area</b>';
								$halx = '<b>Mutasi Area</b> dari <b>'.$lokasi_lama.'</b> ke <b>'.$lokasi_baru.'</b> dan <b>Perubahan Job Title</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';
							}							
						}
						if (($pengajuan->mutasi == 1) && ($pengajuan->demosi == 1))
						{
							if($level_lama != $level_baru)
							{						
								$isi = '<b>Demosi</b> dan <b>Mutasi</b>';
								$halx = '<b>Demosi</b> dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan <b>Mutasi</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';
							}
							else
							{
								$isi = '<b>Demosi</b> dan <b>Mutasi Area</b>';
								$halx = '<b>Demosi</b> dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan <b>Mutasi Area</b> dari <b>'.$lokasi_lama.'</b> ke <b>'.$lokasi_baru.'</b>';
							}							
						}
						//endmutasi
						//demosi
						if (($pengajuan->demosi == 1) && ($pengajuan->perubahan_job == 1))
						{
							$isi = '<b>Demosi</b> dan <b>Perubahan Job Title</b>';
							$halx = '<b>Demosi</b>'.$jabatan_baru.'dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan <b>Perubahan Job Title</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b>';	
						}
						if (($pengajuan->demosi == 1) && ($pengajuan->perubahan_status == 1))
						{
							$isi = '<b>Demosi</b> dan <b>Perubahan Status</b>';
							$halx = '<b>Demosi</b> '.$jabatan_baru.'dari <b>Grade('.$grade_lama.')</b> ke <b>Grade ('.$grade_baru.')</b> dan Menjadi <b>Karyawan Tetap</b>';
						}
						//enddemosi
						//perubahan job
						if (($pengajuan->perubahan_job == 1) && ($pengajuan->perubahan_status == 1))
						{
							$isi = '<b>Perubahan Job Title</b> dan <b>Perubahan Status Tetap</b>';
							$halx = '<b>Perubahan Job Title</b> dari <b>'.$level_lama.'</b> ke <b>'.$level_baru.'</b> dan Menjadi <b>Karyawan Tetap</b>';
						}
						//endperubahan
						
										
					$header = "Bahwa untuk menyikapi dinamika organisasi serta kebutuhan atas pekerjaan maka dipandang perlu untuk melakukan $isi";
					$isi1 = "Kebutuhan Divisi <b>$jabatan_lama</b> yang memerlukan <b>$jabatan_baru</b>";
					$isi2 = "Bahwa terhitung mulai tanggal <b>$tgl_eff</b> telah ditetapkan $halx";
					$isi3 = "Apabila dikemudian hari ternyata terdapat kekeliruan di dalam Surat Keputusan ini,akan diadakan perbaikan seperlunya.";
					$isi4 = "Petikan Surat Keputusan ini diberikan kepada pihak yang berkepentingan untuk diketahui dan dimaklumi sebagaimana mestinya.";
					$footer = "Di Bekasi";
					
					
					
					DB::table('f_table_sk')->insert([
						'fpk_id' => $id,
						'sk_no' => $nomor,
						'header' => $header,
						'isi_atas' => $isi1,
						'isi_tengah'=> $isi2,
						'isi_bawah' => $isi3,
						'isi_footer' => $isi4,
						'arsip_1' => '',
						'arsip_2' => '',
						'arsip_3' => '',
						'arsip_4' => '',
						'arsip_5' => '',
						'arsip_6' => '',
						'arsip_7' => '',
						'arsip_8' => '',
						'arsip_9' => '',
						'arsip_10' => '',
						'footer' => $footer,
						'user_id' => $employee_id,
					]);
					
					
				return redirect(route('pengajuan.fpk'))->with('alert', [
					'type' => 'success',
					'msg' => 'Berhasil Update Status SK',
					]);
	}
	
	public function no_generate_sk($id)
    {
		$employee_id = auth()->user()->employee->id;
		
			$nomor = 0;
			DB::table('f_job_newer')->where(['ReqId' => $id])->update([
				'konter_sk' => $nomor,
				'NextApproval' => NULL,
				]);	
				
			$tdk = 'Tidak Dibuatkan SK nya';
			DB::table('f_table_sk')->insert([
						'fpk_id' => $id,
						'sk_no' => $tdk,
						'user_id' => $employee_id,
					]);
					
			return redirect(route('pengajuan.fpk'))->with('alert', [
				'type' => 'success',
				'msg' => 'Berhasil Update Status SK',
				]);
	}
	
	public function cetak_sk($id)
    {
			$employee_id = auth()->user()->employee->id;
			
			$pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru,
			b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
			b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
			f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city, k.level_title_name from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
			job_titles as f , departments as g, departments as h, company_regions as i, employees as j, level_titles as k
			where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
			a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
			and a.Atasan_baru=j.id and a.Level_lama = k.id and  a.ReqId = ?',[$id]);
			
			$isi_sk = DB::table('f_table_sk')->where('fpk_id', $id)->get();
			
			
			$perihal = DB::table('f_job_newer_perihal')->where('Seq_id', $id)->get();

			$pdf = PDF::loadView('pengajuan.fpk_cetak_sk', compact('pengajuan','perihal','isi_sk'))->setPaper('a4', 'potrait');
		    return $pdf->stream();
	}
	
	public function cetak_pkwt($id)
    {
			$employee_id = auth()->user()->employee->id;
			
			$isi_sk = DB::select('select a.*, b.registration_number, b.fullname, c.sex, c.ID_number, c.address, d.job_title_name, e.note_kontrak from list_pkwt as a, employees as b, employee_details as c, 
			job_titles as d, f_job_newer as e where a.employee_id = b.id and a.employee_id = c.employee_id and a.job_title_id = d.job_title_code and a.fpk_id = e.ReqId and a.fpk_id = ?',[$id]);

			$pasal2 = DB::table('pkwt_draft')->where('pkwt_id', 1)->get();
			$pasal3 = DB::table('pkwt_draft')->where('pkwt_id', 2)->get();
			$pasal4 = DB::table('pkwt_draft')->where('pkwt_id', 3)->where('urut',1)->get();
			$pasal41 = DB::table('pkwt_draft')->where('pkwt_id', 3)->where('urut','>',1)->get();
			$pasal5 = DB::table('pkwt_draft')->where('pkwt_id', 4)->get();
			$pasal6 = DB::table('pkwt_draft')->where('pkwt_id', 5)->get();
			$pasal7 = DB::table('pkwt_draft')->where('pkwt_id', 6)->get();
			$pasal8 = DB::table('pkwt_draft')->where('pkwt_id', 7)->get();
			$pasal9 = DB::table('pkwt_draft')->where('pkwt_id', 8)->get();
			$pasal10 = DB::table('pkwt_draft')->where('pkwt_id', 9)->get();
			$pasal11 = DB::table('pkwt_draft')->where('pkwt_id', 10)->get();
			$pasal12 = DB::table('pkwt_draft')->where('pkwt_id', 11)->get();
			$pasal13 = DB::table('pkwt_draft')->where('pkwt_id', 12)->get();
			

			$pdf = PDF::loadView('pengajuan.fpk_cetak_pkwt', compact('isi_sk','pasal2','pasal3','pasal4','pasal41','pasal5','pasal6','pasal7','pasal8','pasal9','pasal10','pasal11','pasal12','pasal13'))->setPaper('a4', 'potrait');
		    return $pdf->stream();
	}
	
	public function cetak_sphk($id)
    {
			$employee_id = auth()->user()->employee->id;
			
			$isi_sk = DB::select('select a.*, b.registration_number, b.fullname, c.sex, c.ID_number, c.address, d.job_title_name, e.note_kontrak from list_habis_pkwt as a, employees as b, employee_details as c, 
			job_titles as d, f_job_newer as e where a.employee_id = b.id and a.employee_id = c.employee_id and a.job_title_id = d.job_title_code and a.fpk_id = e.ReqId and a.fpk_id = ?',[$id]);

			$pdf = PDF::loadView('pengajuan.fpk_cetak_sphk', compact('isi_sk'))->setPaper('a4', 'potrait');
		    return $pdf->stream();
	}
	
	public function edit_sk($id)
    {
			$employee_id = auth()->user()->employee->id;
			
			$pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru,
			b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
			b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
			f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city, k.level_title_name from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
			job_titles as f , departments as g, departments as h, company_regions as i, employees as j, level_titles as k
			where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
			a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
			and a.Atasan_baru=j.id and a.Level_lama = k.id and  a.ReqId = ?',[$id]);
			
			$isi_sk = DB::table('f_table_sk')->where('fpk_id', $id)->get();
			
			
			$perihal = DB::table('f_job_newer_perihal')->where('Seq_id', $id)->get();

			return view('pengajuan.fpk_edit_sk',[
			'pengajuan' => $pengajuan,
			'perihal' => $perihal,
			'isi_sk' => $isi_sk,		
			]);
	}
	
	public function submit_edit_sk(Request $request)
    {
        DB::beginTransaction();
        try {
			
			$requestor = auth()->user()->employee;
            $ReqId = $request->ReqId;
			
            //Update table utama
            DB::table('f_table_sk')->where('fpk_id', $ReqId)->update([              
				'header' => $request->iheader,
                'isi_atas' => $request->isi_atas,
                'isi_tengah' => $request->isi_tengah,
				'isi_bawah' => $request->isi_bawah,
                'isi_footer' => $request->isi_footer,
                'footer' => $request->footer,
                'arsip_1' => $request->arsip_1,
				'arsip_2' => $request->arsip_2,
				'arsip_3' => $request->arsip_3,
				'arsip_4' => $request->arsip_4,
				'arsip_5' => $request->arsip_5,
				'arsip_6' => $request->arsip_6,
				'arsip_7' => $request->arsip_7,
				'arsip_8' => $request->arsip_8,
				'arsip_9' => $request->arsip_9,
				'arsip_10' => $request->arsip_10,
            ]);

            DB::commit();
            return redirect(route('pengajuan.fpk'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Edit Data SK',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
	
    public function index(Request $request)
    {
        $employee_id = auth()->user()->employee->id;
		
		
		if ($employee_id <> 6 or $employee_id <> 20 or $employee_id <> 9)
		{
        $query = "select a.ReqId , a.konter_pkwt, a.pkwt_no, a.konter_sk, a.sk_no, a.flag_jenis, a.fpk_no, a.Nik, a.Flag_data, a.ApprovedAll, a.Flag_proses,  a.Dept_lama, a.Insert_user, a.Jab_lama , a.Insert_date, 
			b.employee_id, b.fullname , b.job_title_name, b.department_name, d.fullname as nama_creator , c.* from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d
			where  a.Insert_user = d.id and a.ReqId=c.Seq_id and a.Nik=b.id and a.Flag_data<>3";

			if (!auth()->user()->can('modify-fpk')) {
				$query .= " and a.Insert_user = " . $employee_id;				
			}
			
			$query .=" ORDER BY a.ReqId DESC";
		}
		else if ($employee_id == 322 or $employee_id == 313 )
		{
		$query = "select a.ReqId , a.konter_pkwt, a.pkwt_no, a.konter_sk, a.sk_no, a.flag_jenis, a.fpk_no, a.Nik, a.Flag_data, a.ApprovedAll, a.Flag_proses,  a.Dept_lama, a.Insert_user, a.Jab_lama , a.Insert_date, 
			b.employee_id, b.fullname , b.job_title_name, b.department_name, d.fullname as nama_creator , c.* from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d
			where  a.Insert_user = d.id and a.ReqId=c.Seq_id and a.Nik=b.id and a.Flag_data<>3 and a.flag_jenis='RC' ORDER BY a.`Flag_proses`,  a.ApprovedAll ASC";
		}
		else
		{
		$query = "select a.ReqId , a.konter_pkwt, a.pkwt_no, a.konter_sk, a.sk_no, a.flag_jenis, a.fpk_no, a.Nik, a.Flag_data, a.ApprovedAll, a.Flag_proses,  a.Dept_lama, a.Insert_user, a.Jab_lama , a.Insert_date, 
			b.employee_id, b.fullname , b.job_title_name, b.department_name, d.fullname as nama_creator , c.* from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d
			where  a.Insert_user = d.id and a.ReqId=c.Seq_id and a.Nik=b.id and a.Flag_data<>3 ORDER BY a.`Flag_proses`,  a.ApprovedAll ASC";
		}
		
		if ($request->has('Flag_proses')) {
            $query->where('a.Flag_proses', $request->Flag_proses);
        }
		
        $pengajuans = DB::select($query);		
				
        return view('pengajuan.fpk', [
            'pengajuans' => $pengajuans,
            'employee_id' => $employee_id
        ]);
    }

    public function create()
    {
		$employee_id = auth()->user()->employee->id;
        $grade_titles = GradeTitle::all();
        $job_titles = JobTitle::all();
        $departments = Department::all();
		$employees = Employee::all();
        $company_regions = CompanyRegion::all();
		
        $reasonOfHiring = DB::table('lookups')->where([
            'category' => 'FPKROH'
        ])->get();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'FPKES'
        ])->get();
		
        $workingTime = DB::table('lookups')->where([
            'category' => 'PTWT'
        ])->get();
		$master = DB::select("SELECT * FROM employeemaster WHERE grade not in ('V', 'VI')");
		
		//$employee = DB::select("SELECT * FROM employees WHERE department_id in (select department_id from employees where id='$employee_id')");
		//(update 10 agustus 2020 - Nanda)
		if (auth()->user()->can('create-fpk-master')) {
		$employee = DB::select("SELECT * FROM employees WHERE division_id in (select division_id from employees where id='$employee_id') and id not in (select employee_id from employee_retirements)");
		}
		else {
		$employee = DB::select("SELECT * FROM employees WHERE id not in (select employee_id from employee_retirements) and direct_superior='$employee_id'");	
		}
		
		//$employee = $employee[0];
        $employees = Employee::with('level_title')->get();

        $gradeOptions = ['I', 'II', 'III', 'IV', 'V', 'VI'];
		$mealOptions = ['Ya', 'Tidak'];
		$masa_kontrak = [3, 6, 9, 12];
        $levelOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $KontrakOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];
        $last_educationOptions = [
            "SD", "SMP", "SMA", "SMK", "D3", "D4", "S1", "S2", "S3"
        ];
		$HalOptions = [
            "Kontrak", "Habis Kontrak", "Probation", "Tetap"
        ];
		
        $direct_superior = auth()->user()->employee->load('level_title');
		
		//$grade = DB::select("SELECT grade_title_id FROM employees WHERE id='$employee_id'");
		$MasterNext = DB::table('employees')->where('id', $employee_id)->get();
		foreach ($MasterNext as $master) {
			$App = $master->direct_superior;
		}
		
		$Approval = DB::select("select id, grade_title_id from employees where id='$App'");
		
        return view('pengajuan.fpk_create', [
            'grade_titles' => $grade_titles,
            'job_titles' => $job_titles,
			'mealOptions' => $mealOptions,
            'departments' => $departments,
            'company_regions' => $company_regions,
            'reasonOfHiring' => $reasonOfHiring,
            'employeeStatus' => $employeeStatus,
            'workingTime' => $workingTime,
            'employees' => $employees,
			'employee' => $employee,
			'masa_kontrak' => $masa_kontrak,			
			//'grade' => $grade,
            'gradeOptions' => $gradeOptions,
            'levelOptions' => $levelOptions,
            'KontrakOptions' => $KontrakOptions,
            'last_educationOptions' => $last_educationOptions,
			'HalOptions' => $HalOptions,
            'direct_superior' => $direct_superior,
			'Approval' => $Approval,
			'master' => $master,
        ]);
    }

    public function get_indirect_superior(Request $request)
    {
        $InDirectSuperior = Employee::findOrFail($request->id);
        $InDirectSuperior->load('level_title');

        return response()->json([
            'status' => 'success',
            'value' => $InDirectSuperior->superior->fullname . '-' . $InDirectSuperior->superior->level_title->level_title_name,
        ]);
    }
	
	public function get_dept_list(Request $request)
     {
            $jabatan = GroupJobtitle::where('department_code', $request->get('id'))
            ->pluck('name', 'id');   
			return response()->json($jabatan);
    }
	
	public function get_atasan_list(Request $request)
     {
            $atasan = GroupAtasan::where('job_title_code', $request->get('id'))->pluck('name', 'id');  
			return response()->json($atasan);
    }

	
	public function get_superior(Request $request)
    {
        $DirectSuperior = Employee::findOrFail($request->id);
        $DirectSuperior->load('level_title');

        return response()->json([
            'status' => 'success',
            'value' => $DirectSuperior->superior->fullname,
        ]);
    }
		
	public function get_data_employee(Request $request)
    {
        $EmployeeData = EmployeeMaster::findOrFail($request->id);
        return response()->json([
			'status' => 'success',
            'value1' =>  $EmployeeData->religion,
			'tgl_lahir' => date('d-F-Y',strtotime($EmployeeData->date_of_birth)),
			'tgl_masuk' => date('d-F-Y',strtotime($EmployeeData->date_of_work)),
			'departement' =>  $EmployeeData->department_code . '-' . $EmployeeData->department_name,
			'departementx' =>  $EmployeeData->department_code,
			'pendidikan' =>  $EmployeeData->last_education .'-'. $EmployeeData->education_focus,
			'kelas' =>  $EmployeeData->grade,
			'jabatan' =>  $EmployeeData->job_title_code .'-'. $EmployeeData->job_title_name,
			'jabatanx' =>  $EmployeeData->job_title_code,
			'lokasi' =>  $EmployeeData->region_city,
			'stat_kary' =>  $EmployeeData->stat_kary,
			'Gapok_lama' =>  $EmployeeData->basic_salary,
			'level_lama' =>  $EmployeeData->level,
			'tunkan' =>  $EmployeeData->meal_allowance,
			'loc_id' =>  $EmployeeData->loc_id,
        ]);
    }

    public function submit(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestor = auth()->user()->employee;

            $now = now();
			if (empty($request->Atasan_baru))
			{
				$atasan = $request->Atasan_lamax;
			}
			else 
			{
				$atasan = $request->Atasan_baru;
			}
			
			if (empty($request->Kelas_baru))
			{
				$kelas = $request->Kelas_lama;
			}
			else 
			{
				$kelas = $request->Kelas_baru;
			}
			
			//tambahan				
			if (!empty($request->Gapok_baru))
			{
				$Gapok = str_replace('.','', $request->Gapok_baru);
			}
			else 
			{
				$Gapok = $request->Gapok_lama;
			}
			//end tambahan 
			
			if (empty($request->Departement_baru))
			{
				$departement = $request->Departementx;
			}
			else 
			{
				$departement = $request->Departement_baru;
			}
			
			if (empty($request->Jabatan_baru))
			{
				$Jabatan = $request->Jabatanx;
			}
			else 
			{
				$Jabatan = $request->Jabatan_baru;
			}
			
			if (empty($request->Tukan_baru))
			{
				$tukan = $request->Tukan_lama;
			}
			else 
			{
				$tukan = $request->Tukan_baru;
			}
			if (empty($request->Level))
			{
				$level = $request->level_lama;
			}
			else 
			{
				$level = $request->Level;
			}
					
			if((!empty($request->penyesuaian_comben)) or (!empty($request->perubahan_job)) or (!empty($request->mutasi)))
			{
				$flag_hpk = 0;
			}
			else
			{
				$flag_hpk = 1;
			}
			
			if (!empty($request->perpanjangan_kontrak))
			{
				$flag_kontrak = 1;
			}
			else
			{
				$flag_kontrak = 0;
			}
			
			//Tambahan 21-8-2020
			
			if((!empty($request->perpanjangan_kontrak)) or (!empty($request->habis_kontrak)) or (!empty($request->perubahan_status)))
			{
				$flag_jenis = 'RC';
			}
			else
			{
				$flag_jenis = 'OD';
			}
			
			//
			
			$eff_date = $request->Effdate;
			$eff_date = substr($eff_date,6,4) . "-" . substr($eff_date,3,2) . "-" . substr($eff_date,0,2);
			
            $ReqId = DB::table('f_job_newer')->insertGetId([
                'Nik' => $request->NameEmployee,
                'Jab_lama' => $request->Jabatanx,
                'Kelas_lama' => $request->Kelas_lama,
                'Dept_lama' => $request->Departementx,
                'Lokasi_lama' => $request->Lokasi_lama,
                'Atasan_lama' => $request->Atasan_lamax,
                'Status_lama' => $request->Status_lama,
                'Gapok_lama' => $request->Gapok_lama,
                //'Tuport_lama' => $request->Tuport_lama,
                'Tukan_lama' => $request->Tukan_lama,
				'Level_lama' => $request->level_lama,
				'Jab_baru' => $Jabatan,
                'Kelas_baru' => $kelas,
                'Dept_baru' => $departement,
                'Lokasi_baru' => $request->Lokasi_baru,
                'Atasan_baru' => $atasan,
                'Status_baru' => $request->Status_baru,
                'Gapok_baru' => $Gapok,
                //'Tuport_baru' => $request->Tuport_baru,
                'Tukan_baru' => $tukan,
				'Level' => $level,
                'Eff_date' => $eff_date,
                'Notes' => $request->Notes,
				'DirectSuperior' => $request->DirectSuperior,
                'NextApproval' => $request->Approval,
                'Insert_user' => $requestor->id,
                'Insert_date' => $now,			
                'Flag_proses' => 0,
				'ApprovedAll' => 0,
				'HcFlag' => 0,
				'Flag_data' => 1,
				'Flag_proses' => 1,
				'flag_hpk' => $flag_hpk,
				'flag_mgr' => $request->flag_mgr,
				'flag_kontrak' => $flag_kontrak,
				'flag_jenis' => $flag_jenis,
				'note_kontrak' => $request->note_kontrak,
				'kontrak_ke' => $request->kontrak_ke,
            ]);
			
            DB::table('f_job_newer_perihal')->insert([
                'Seq_id' => $ReqId,
				'promosi' => $request->promosi,
				'demosi' => $request->demosi,
				'mutasi' => $request->mutasi,
				'perubahan_job' => $request->perubahan_job,
				'perpanjangan_kontrak' => $request->perpanjangan_kontrak,
                'habis_kontrak' => $request->habis_kontrak,
				'perubahan_status' => $request->perubahan_status,
				'penyesuaian_comben' => $request->penyesuaian_comben,
            ]);

				DB::table('f_job_newer_hpk')->insert([
					'Id' => $ReqId,
					'A1' => $request->A1,				
					'A2' => $request->A2,
					'A3' => $request->A3,
					'A4' => $request->A4,
					'A5' => $request->A5,
					'B1' => $request->B1,
					'B2' => $request->B2,
					'B3' => $request->B3,
					'B4' => $request->B4,
					'B5' => $request->B5,
					'kelebihan' => $request->kelebihan,
					'kekurangan' => $request->kekurangan,			
				]);
			
			
			DB::table('f_job_newer_approval')->insert([
					'ReqId' => $ReqId,
					'IsHcFlag' => 0,				
					'Flag' => 2,				
					'EmployeeId' => $request->Approval,
					'ApprovalSts' => 0,	
					'LevelId' => $request->LevelId,	
				]);
			
            
            $facilitiesData = [];
            			
            if (!empty($request->facilities))
			{
                foreach ($request->facilities as $item) {
                    $facilitiesData[] = [
                        'Req_id' => $ReqId,
                        'Description' => $item,
    					'Status' => 1,
                    ];
                }
                DB::table('f_job_newer_facility')->insert($facilitiesData);
			}
			
            $approval_by = $requestor->superior->user;


            $approval_by->notify(new NotifNewFPK([
                'email' => $approval_by->email,
                'RequestorName' => $requestor->fullname,
                'ReqNo' => $ReqId,
            ]));


            DB::commit();
            return redirect(route('pengajuan.fpk'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Submit FPK, menunggu approval',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function approval()
    {
        $EmployeeId = auth()->user()->employee->id;

/*		
		$cek=DB::select("SELECT count(*) as cek  FROM f_job_newer_approval WHERE Flag=3 and EmployeeId=$EmployeeId and ApprovalSts=0");
		foreach ($cek as $item) {
			$cekhc = $item->cek;
		}
		
		if($cekhc == 1)
		{
			$query = "select a.ReqId , a.ApprovedAll, a.fpk_no, a.Nik, a.Flag_data, a.Flag_proses,  a.Dept_lama, a.Insert_user, a.Jab_lama , a.Insert_date, 
			b.employee_id, b.fullname , b.job_title_name, b.department_name, d.fullname as nama_creator , c.* from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d
			where  a.Insert_user = d.id and a.ReqId=c.Seq_id and a.Nik=b.id and a.Flag_data<>3";	
		}
		else
		{
*/
		 $query = "select a.ReqId , a.ApprovedAll, a.fpk_no, a.Nik, a.Flag_data, a.Flag_proses,  a.Dept_lama, a.Insert_user, a.Jab_lama , a.Insert_date, 
			b.employee_id, b.fullname , b.job_title_name, b.department_name, d.fullname as nama_creator , c.* from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d
			where  a.Insert_user = d.id and a.ReqId=c.Seq_id and a.Nik=b.id and a.Flag_data<>3 and a.NextApproval='$EmployeeId' and a.ApprovedAll<>2 order by a.Flag_proses asc, a.ApprovedAll asc, a.Insert_date desc";		
//		}
		
        $pengajuans = DB::select($query);		
				
        return view('pengajuan.fpk_approval', [
            'pengajuans' => $pengajuans,			
        ]);
    }

    public function detail($id)
    {
        $pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru,
			b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
			b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
			f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city from 
			f_job_newer as a, employeemaster as  b, f_job_newer_perihal as  c, employees as d, job_titles as e, 
			job_titles as f , departments as g, departments as h, company_regions as i, employees as j
			where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
			a.ReqId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
			and a.Atasan_baru=j.id and  a.ReqId = ?',[$id]);
        $pengajuan = $pengajuan[0];

        $EmployeeId = auth()->user()->employee->id;

        $approval_log = DB::select('(select a.`ApprovalSts`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from f_job_newer_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`ReqId` = ?
                        order by a.`ApprovalDate` asc)', [$id]);
						
		
		
		$nik = $pengajuan->Nik;	   
        $facilities = DB::table('f_job_newer_facility')->where('Req_id', $id)->get();
        $perihal = DB::table('f_job_newer_perihal')->where('Seq_id', $id)->get();
		$employeemaster = DB::table('employeemaster')->where('id', $nik)->get();
        $penilaian = DB::table('f_job_newer_hpk')->where('Id', $id)->get();
		
		$MasterNext = DB::table('employees')->where('id', $EmployeeId)->get();
		foreach ($MasterNext as $master) {
			$App = $master->direct_superior;
		}
		
		$NextApproval = DB::select("select id, grade_title_id from employees where id='$App'");
		
		$cek=DB::select("SELECT count(*) as cek  FROM f_job_newer_approval WHERE Flag=3 and EmployeeId=$EmployeeId and ApprovalSts=0 and ReqId=$id");
	

        return view('pengajuan.fpk_detail',
		[
			'pengajuan' => $pengajuan,
			'perihal' => $perihal,
			'penilaian' => $penilaian,
			'NextApproval' => $NextApproval,
            'approval_log' => $approval_log,
			'cek'        => $cek,
			'employeemaster' => $employeemaster,
			'facilities' => $facilities,
			'EmployeeId' => $EmployeeId, 
        ]);
    }

    function submit_approval(request $request){
        $ReqId = $request->ReqId;
        $ApprovalNote = $request->ApprovalNote;
        $ApprovalSts = $request->ApprovalSts;
		$NextApproval = $request->NextApproval;
		$LevelId = $request->LevelId;
		$Assmen = 20;
		$Rcspv = 322;
		$EmployeeId = auth()->user()->employee->id;
		
        DB::begintransaction();
        try{
			
			$pengajuan = DB::table('f_job_newer')->where('ReqId', $ReqId)->first();
			
			$dept = $pengajuan->Dept_lama;
			
			$HcFlag = $pengajuan->HcFlag;
			
			
		if($HcFlag == 0)
		{
			DB::table('f_job_newer_approval')->where(['ReqId' => $ReqId , 'EmployeeId' => $EmployeeId])->update([
				'ApprovalSts' => $ApprovalSts,
				'ApprovalNotes' => $ApprovalNote,
				'ApprovalDate' => now(),
            ]);
			
			DB::table('f_job_newer')->where('ReqId', $ReqId)->update([
				'NextApproval' => $NextApproval,
				'Flag_proses' => 2,
            ]);
		}
		else if ($HcFlag == 1)
		{
			DB::table('f_job_newer_approval')->where(['ReqId' => $ReqId , 'EmployeeId' => $EmployeeId, 'IsHcFlag' => 1])->update([
				'ApprovalSts' => $ApprovalSts,
				'ApprovalNotes' => $ApprovalNote,
				'ApprovalDate' => now(),
            ]);
			
			DB::table('f_job_newer')->where('ReqId', $ReqId)->update([
				'NextApproval' => $NextApproval,
				'Flag_proses' => 2,
            ]);
		}
		else
		{
			DB::table('f_job_newer_approval')->where(['ReqId' => $ReqId , 'EmployeeId' => $EmployeeId, 'IsHcFlag' => 1])->update([
				'ApprovalSts' => $ApprovalSts,
				'ApprovalNotes' => $ApprovalNote,
				'ApprovalDate' => now(),
            ]);
			
			DB::table('f_job_newer')->where('ReqId', $ReqId)->update([
				'NextApproval' => NULL,
				'Flag_proses' => 2,
            ]);
		}
		
			
		if(!empty($NextApproval))
			{			
				$ReqNo = NULL;				
				if ($pengajuan->ApprovedAll == 0)
				{
					if ($ApprovalSts == 1 && $HcFlag==0)	
					{
					    if ($dept == 'DEP07' or $dept == 'DEP10' or $dept == 'DEP08' or $dept == 'DEP17') 
						{
							if ($NextApproval<>41)
							{
								DB::table('f_job_newer_approval')->insert([
									'ReqId' => $ReqId,
									'IsHcFlag' => 0,				
									'Flag' => 2,				
									'EmployeeId' => $NextApproval,
									'ApprovalSts' => 0,	
									'LevelId' => $LevelId,
								]);
							}
    						
								$next_approval = User::where('employee_id', $request->NextApproval)->first();
    						
	    						$next_approval->notify(new NotifNewFPK([
	                                'email' => $next_approval->email,
	                                'RequestorName' => $request->RequestorName,
	                                'ReqNo' => $ReqId,
	                            ]));						
							
						}
						else
						{
							if ($LevelId > 1 || $NextApproval<>36)
							{
							    	DB::table('f_job_newer_approval')->insert([
            							'ReqId' => $ReqId,
            							'IsHcFlag' => 0,				
            							'Flag' => 2,				
            							'EmployeeId' => $NextApproval,
            							'ApprovalSts' => 0,	
            							'LevelId' => $LevelId,
            						]);
            					
            						$next_approval = User::where('employee_id', $request->NextApproval)->first();
            						
            						$next_approval->notify(new NotifNewFPK([
                                        'email' => $next_approval->email,
                                        'RequestorName' => $request->RequestorName,
                                        'ReqNo' => $ReqId,
                                    ]));															
							}
						}
					}
					else if ($ApprovalSts == 2)
					{
						DB::table('f_job_newer')->where(['ReqId' => $ReqId])->update([
								'ApprovedAll' => 2,
							]);
					}
				    
				    //tambahan 03-11-2020
				    
				    else if ($ApprovalSts == 1 && $HcFlag == 1)	
					{
					     $next_approval = User::where('employee_id', $request->NextApproval)->first();
            						
            						$next_approval->notify(new NotifNewFPK([
                                        'email' => $next_approval->email,
                                        'RequestorName' => $request->RequestorName,
                                        'ReqNo' => $ReqId,
                                    ]));					
					}
				}
			
		
			}
				
			$hitung = DB::select("SELECT count(*) as jumlah FROM f_job_newer_approval WHERE ReqId='$ReqId' and IsHcFlag<>1 and LevelId <3 and ApprovalSts=1");
					foreach($hitung as $item) {					
					$hitungid = $item->jumlah;
					}
					
					if ($hitungid >= 1 && $HcFlag == 0)
					{
						
					 DB::statement("insert into f_job_newer_approval (EmployeeId, LevelId, ReqId, IsHcFlag , ApprovalSts, Flag) select id, grade_title_id, '$ReqId', 1, 0, 3  from employees where id in(9)");
					 
					 DB::table('f_job_newer')->where(['ReqId' => $ReqId])->update([
								'NextApproval' => 9,
								'HcFlag' => 1,
							]);
							
														
					}
					
			$hcm = DB::select("SELECT count(*) as jumlah FROM f_job_newer_approval WHERE ReqId='$ReqId' and EmployeeId=9 and IsHcFlag=1 and ApprovalSts=1");
					foreach($hcm as $item) {					
					$hcmid = $item->jumlah;
					}			
					if ($hcmid == 1 && $HcFlag == 1)
					{								
					 DB::statement("insert into f_job_newer_approval (EmployeeId, LevelId, ReqId, IsHcFlag , ApprovalSts, Flag) select id, grade_title_id, '$ReqId', 1, 0, 3  from employees where id in(35)");														
					 
					 		DB::table('f_job_newer')->where(['ReqId' => $ReqId])->update([
								'NextApproval' => 35,
								'HcFlag' => 2,
							]);
					}
			
			
			$final = DB::select("SELECT count(*) as jumlah FROM f_job_newer_approval WHERE ReqId='$ReqId' and EmployeeId=35 and IsHcFlag=1 and ApprovalSts=1");
				foreach($final as $item) {					
					$finalid = $item->jumlah;
					}
					
			if($finalid >= 1 && $HcFlag == 2)
			{
				$now = now();
				
				$year = $now->year;
				
				$maxReqNo = DB::select("select case
							when coalesce(max(left(`fpk_no`, 3)), 0)+1 = 1000 then 1
							else coalesce(max(left(`fpk_no`, 3)), 0)+1
							end as maxID from f_job_newer where fpk_no like '%$year%'");
							$ReqNo = sprintf('%03d', $maxReqNo[0]->maxID).'/FPK/'.$now->format('m').'/'.$now->year;
							
							DB::table('f_job_newer')->where(['ReqId' => $ReqId, 'fpk_no' => Null ])->update([
								'ApprovedAll' => 1,
								'NextApproval' => NULL,
								'fpk_no' => $ReqNo,
							]);
			
			    
			
				if ($pengajuan->flag_jenis == 'RC')
				{
				    
				    $users = User::where('employee_id', $Rcspv)->get();

                    foreach ($users as $user) {
                        $user->notify(new NotifApprovalFPK([
                            'email' => $user->email,
                            'ReqNo' => $ReqId,
                            'ApprovalSts' => $ApprovalSts,
                            'ApprovalBy' => $request->ApprovalBy
                        ]));
                    }
				    
				}

    				$users = User::where('employee_id', $Assmen)->get();
    
                    foreach ($users as $user) {
                        $user->notify(new NotifApprovalFPK([
                            'email' => $user->email,
                            'ReqNo' => $ReqId,
                            'ApprovalSts' => $ApprovalSts,
                            'ApprovalBy' => $request->ApprovalBy
                        ]));
                    }
				
			}
			

        	$users = User::where('employee_id', $EmployeeId)->get();

            foreach ($users as $user) {
                $user->notify(new NotifApprovalFPK([
                    'email' => $user->email,
                    'ReqNo' => $ReqId,
                    'ApprovalSts' => $ApprovalSts,
                    'ApprovalBy' => $request->ApprovalBy
                ]));
            }
            
			
            DB::commit();
            return redirect(route('pengajuan.fpk.approval'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Submit Approval',
            ]);
        }
        catch(Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function lampiran(Request $request)
    {
		$ReqId = $request->ReqId;
		
        if($request->hasFile('lampiran')){
            $file = $request->file('lampiran');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/file/'), $file_name);
            $nfile = 'file/' . $file_name;
			
			DB::table('f_job_newer')->where('ReqId', $request->ReqId)->update([
            'lampiran' => $nfile,
			]);	
        }

        return redirect(route('pengajuan.fpk'))->with('alert', [
            'type' => 'success',
            'msg' => 'File berhasil di upload'
        ]);
    }

    public function edit($id)
    {
        $grade_titles = GradeTitle::all();
        $job_titles = JobTitle::all();
        $departments = Department::all();
		$employees = Employee::all();
        $company_regions = CompanyRegion::all();
		
        $reasonOfHiring = DB::table('lookups')->where([
            'category' => 'FPKROH'
        ])->get();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'FPKES'
        ])->get();

        $workingTime = DB::table('lookups')->where([
            'category' => 'PTWT'
        ])->get();
		
		$facilities = DB::table('lookups')->where([
            'category' => 'PTFPK'
        ])->get();

        $employees = Employee::with('level_title')->get();
		
		$mealOptions = ['YA', 'TIDAK'];
        $gradeOptions = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        $levelOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $KontrakOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $last_educationOptions = [
            "SD", "SMP", "SMA", "SMK", "D3", "D4", "S1", "S2", "S3"
        ];
		$HalOptions = [
            "Kontrak", "Probation", "Tetap"
        ];
        
		$direct_superior = auth()->user()->employee->load('level_title');
		
        $pengajuan = DB::table('f_job_newer')->where('ReqId', $id)->first();
		
		
		
        if (!$pengajuan) {
            return redirect()->back()->with('alert', [
                'status' => 'danger',
                'msg' => 'Pengajuan tidak ditemukan'
            ]);
        }
        
        /*
        else {
            if ($pengajuan->Insert_user != auth()->user()->employee->id) {
                abort(403);
            }
        }
		*/
		
		
       $nik = $pengajuan->Nik;
	   $dept = $pengajuan->Dept_baru;
	   $jab = $pengajuan->Jab_baru;
	   
        $inserted_fac = DB::table('f_job_newer_facility')->where('Req_id', $id)->get();
        $perihal = DB::table('f_job_newer_perihal')->where('Seq_id', $id)->get();
		$employeemaster = DB::table('employeemaster')->where('id', $nik)->get();
        $penilaian = DB::table('f_job_newer_hpk')->where('Id', $id)->get();
		
		$listjabatan = DB::table('groupjobtitle')->where('department_code', $dept)->get();
		$listatasan = DB::table('groupatasan')->where('job_title_code', $jab)->get();


        return view('pengajuan.fpk_edit',[
			'pengajuan' => $pengajuan,
			'perihal' => $perihal,
			'mealOptions' => $mealOptions,
			'penilaian' => $penilaian,
            'job_titles' => $job_titles,
            'departments' => $departments,
            'company_regions' => $company_regions,      
            'employeeStatus' => $employeeStatus,
			'employeemaster' => $employeemaster,
            'employees' => $employees,
			'listjabatan' => $listjabatan,
			'listatasan' => $listatasan,
            'gradeOptions' => $gradeOptions,
            'levelOptions' => $levelOptions,
            'KontrakOptions' => $KontrakOptions,
            'last_educationOptions' => $last_educationOptions,
			'HalOptions' => $HalOptions,
            'direct_superior' => $direct_superior,
			'facilities' => $facilities,
            'inserted_fac' => $inserted_fac,

        ]);
    }

    public function submit_edit(Request $request)
    {
        DB::beginTransaction();
        try {
			
			$requestor = auth()->user()->employee;
            $ReqId = $request->ReqId;
			
			$now = now();
			
			if (empty($request->Lokasi_baru))
			{
				$lokasi = 1;
			}
			else
			{
				$lokasi = $request->Lokasi_baru;
			}
			
			if (!empty($request->Atasan_baru))
			{
				$atasan = $request->Atasan_baru;
			}
			else 
			{
				$atasan = $request->Atasan_lama;
			}
			
			//tambahan				
			if (!empty($request->Gapok_baru))
			{
				$Gapok = str_replace('.','', $request->Gapok_baru);
			}
			else 
			{
				$Gapok = $request->Gapok_lama;
			}
			//end tambahan 
			
			$eff_date = $request->Effdate;
			$eff_date = substr($eff_date,6,4) . "-" . substr($eff_date,3,2) . "-" . substr($eff_date,0,2);
		
			if((!empty($request->promosi)) or (!empty($request->perpanjangan_kontrak)) or (!empty($request->habis_kontrak)) or (!empty($request->perubahan_status)))
			{
				$flag_hpk = 1;
			}
			else
			{
				$flag_hpk = 0;
			}
            
            if (!empty($request->perpanjangan_kontrak))
			{
				$flag_kontrak = 1;
			}
			else
			{
				$flag_kontrak = 0;
			}

            //Update table utama
            DB::table('f_job_newer')->where('ReqId', $ReqId)->update([              
				'Jab_baru' => $request->Jabatan_baru,
                'Kelas_baru' => $request->Kelas_baru,
                'Dept_baru' => $request->Departement_baru,
                'Lokasi_baru' => $lokasi,
                'Atasan_baru' => $atasan,
                'Status_baru' => $request->Status_baru,
                'Gapok_baru' => $Gapok,
                //'Tuport_baru' => $request->Tuport_baru,
                'Tukan_baru' => $request->Tukan_baru,
				'Level' => $request->Level,
                'Eff_date' => $eff_date,
                'Notes' => $request->Notes,
                'Update_user' => $requestor->id,
                'Update_date' => $now,			
				'Flag_data' => 2,
				'flag_mgr' => $request->flag_mgr,
				'flag_hpk' => $flag_hpk,
				'flag_kontrak' => $flag_kontrak,
				'note_kontrak' => $request->note_kontrak,
				'kontrak_ke' => $request->kontrak_ke,
            ]);
			
			
			DB::table('f_job_newer_perihal')->where('Seq_id', $ReqId)->update([
				'promosi' => $request->promosi,
				'demosi' => $request->demosi,
				'mutasi' => $request->mutasi,
				'perubahan_job' => $request->perubahan_job,
				'perpanjangan_kontrak' => $request->perpanjangan_kontrak,
                'habis_kontrak' => $request->habis_kontrak,
				'perubahan_status' => $request->perubahan_status,
				'penyesuaian_comben' => $request->penyesuaian_comben,
            ]);
			
            DB::table('f_job_newer_hpk')->where('Id', $ReqId)->update([
				'A1' => $request->A1,
				'A2' => $request->A2,
				'A3' => $request->A3,
				'A4' => $request->A4,
				'A5' => $request->A5,
                'B1' => $request->B1,
				'B2' => $request->B2,
				'B3' => $request->B3,
				'B4' => $request->B4,
				'B5' => $request->B5,
				'kelebihan' => $request->kelebihan,
				'kekurangan' => $request->kekurangan,
            ]);

            
            DB::table('f_job_newer_facility')->where('Req_id', $ReqId)->delete();
            
            $facilitiesData = [];
            
            if (!empty($request->facilities))
			{
                foreach ($request->facilities as $item) {
                    $facilitiesData[] = [
                        'Req_id' => $ReqId,
                        'Description' => $item,
    					'Status' => 1,
                    ];
                }

                DB::table('f_job_newer_facility')->insert($facilitiesData);
			}

          
         
			$approval_by = $requestor->superior->user;

            $approval_by->notify(new NotifNewFPK([
                'email' => $approval_by->email,
                'RequestorName' => $requestor->fullname,
                'ReqNo' => $ReqId,
            ]));


            DB::commit();
            return redirect(route('pengajuan.fpk'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Edit FPK, menunggu approval',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'bail|nullable|date',
            'end_date' => 'bail|nullable|date',
            'status' => 'required',
        ]);

        $params = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ];

        $params['is_approved_all'] = $request->has('is_approved_all') ? true:false;

        return Excel::download(new FPKExport($params), 'fpk-export-'.date('Y-m-d').'.xlsx');
    }

    public function remove(Request $request)
    {
        $ReqId = $request->ReqId;
		
		$requestor = auth()->user()->employee;	
	    $now = now();

        DB::beginTransaction();
        try {
			
			DB::table('f_job_newer')->where('ReqId', $request->ReqId)->update([
            'Delete_date' => $now,
			'Delete_user' => $requestor->id,
            'Flag_data' => 3,
			]);
			
            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Hapus FPK',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
