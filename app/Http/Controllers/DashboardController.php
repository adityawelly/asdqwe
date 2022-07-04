<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use App\Models\Department;
use App\Models\GradeTitle;
use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $app_settings = cache('app_settings') ?? new \App\GlobalSetting();

        $count = cache()->remember('dashboard-count', $app_settings->get('cache_expire_in'), function() {
            return [
                'employee' => Employee::all()->count(),
                'department' => Department::all()->count(),
                'company_region' => CompanyRegion::all()->count(),
                'division' => Division::all()->count()
            ];
        });

        $sex = cache()->remember('dashboard-sex', $app_settings->get('cache_expire_in'), function() {
            return [
                'count' => [
                    EmployeeDetail::whereHas('employee')->where('sex', 'Laki - Laki')->count(),
                    EmployeeDetail::whereHas('employee')->where('sex', 'Perempuan')->count()
                ],
                'color' => $this->_random_colors(2)
            ];
        });
		
		
		
        $jkar = cache()->remember('dashboard-jukar', $app_settings->get('cache_expire_in'), function() {
		
		/*
		$jukar_def = DB::select("select count(id) as hitung from  employees  where year(date_of_work) < 2021 and deleted_at is null
				  and id not in (select employee_id from employee_retirements)"); 
				  
		foreach($jukar_def as $jukar)
		{
			$def = $jukar->hitung;
		}
		*/
		/*
            $jan = DB::select("select * from v_karyawan where bulan = 1 and tahun < 2022");
			foreach($jan as $jkaryo)
			{
				$hitjan = $jkaryo->total_karyawan;
				$bln = $jkaryo->bulan;
				$thn = $jkaryo->tahun;
				$sex = $jkaryo->sex_id;
				
				DB::table('grafik')->where('tahun', $thn)
				->where('bln', $bln)
				->where('jk', $sex)
				->update([
				'jumlah' => $hitjan,
				]);
			}
			
			$feb = DB::select("select * from v_karyawan where bulan = 2 and tahun < 2022");
			foreach($feb as $jkaryo)
			{
				$hitfeb = $jkaryo->total_karyawan;
				$bln = $jkaryo->bulan;
				$thn = $jkaryo->tahun;
				$sex = $jkaryo->sex_id;
				
				DB::table('grafik')->where('tahun', $thn)
				->where('bln', $bln)
				->where('jk', $sex)
				->update([
				'jumlah' => $hitfeb,
				]);
			}
			
			$mar = DB::select("select * from v_karyawan where bulan = 3 and tahun < 2022");
			foreach($mar as $jkaryo)
			{
				$hitmar = $jkaryo->total_karyawan;
				$bln = $jkaryo->bulan;
				$thn = $jkaryo->tahun;
				$sex = $jkaryo->sex_id;
				
				DB::table('grafik')->where('tahun', $thn)
				->where('bln', $bln)
				->where('jk', $sex)
				->update([
				'jumlah' => $hitmar,
				]);
			}
			
			*/
            
			$count = [];
            $jkarr = DB::table('grafik')->orderBy(DB::raw('bulan'), 'asc')->get();
			
            foreach ($jkarr as $jkari) {
                $count[] = $jkari->jumlah;
            }
            return [
                'count' => $count,
                'color' => $this->_random_colors(2)
            ];
        });
        
        $grade_title = cache()->remember('dashboard-grade_title', $app_settings->get('cache_expire_in'), function() {
            $grade_titles = GradeTitle::withCount('employees')->get();
            return [
                'count' => $grade_titles->pluck('employees_count'),
                'label' => $grade_titles->pluck('grade_title_name'),
                'color' => $this->_random_colors(count($grade_titles))
            ];
        });

        $employee_status = cache()->remember('dashboard-employee_status', $app_settings->get('cache_expire_in'), function() {
            return [
                'count' => [
                    Employee::where('status', 'Kontrak')->count(),
                    Employee::where('status', 'Probation')->count(),
                    Employee::where('status', 'Tetap')->count()
                ],
                'label' => ['Kontrak', 'Probation', 'Tetap'],
                'color' => $this->_random_colors(3)
            ];
        });

        $employee_year_of_service = cache()->remember('dashboard-employee_year_of_service', $app_settings->get('cache_expire_in'), function() {
            $employee_year_of_services = DB::table('employees')->selectRaw("COUNT(date_of_work) AS jml, (YEAR(CURDATE())-YEAR(date_of_work)) AS lama_kerja")
                                            ->where('deleted_at', '=', null)
                                            ->whereNotIn('id', function($query){
                                                $query->select('employee_id')->from('employee_retirements');
                                            })
                                            ->groupBy(DB::raw('lama_kerja'))
                                            ->orderBy(DB::raw('lama_kerja'), 'asc')
                                            ->get();
            $count = [];
            $label = [];
            foreach ($employee_year_of_services->split(7) as $collect) {
                $count[] = $collect->sum('jml');
                if ($collect->count() > 1) {
                    $label[] = $collect->min('lama_kerja').'-'.$collect->max('lama_kerja');
                }else {
                    $label[] = $collect->min('lama_kerja');
                }
            }
            return [
                'count' => $count,
                'label' => $label,
                'color' => $this->_random_colors(1)
            ];
        });

        return view('pages.dashboard', compact('count','sex', 'grade_title', 'employee_status', 'jkar','employee_year_of_service'));
    }

    public function events()
    {
        return view('pages.event');
    }

    private function _random_colors($size){
        $colors = [];
        for ($i=0; $i < $size; $i++) { 
            $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        return $colors;
    }

    public function about_index()
    {
        return view('pages.about');
    }
}
