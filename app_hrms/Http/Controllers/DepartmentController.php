<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Exports\DepartmentExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\DepartmentRequest;
use App\Models\Employee;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('restore-department')) {
            $departments = Department::with(['division' => function($query){
                $query->withTrashed();
            }])->withTrashed()->get();
        }else{
            $departments = Department::with(['division' => function($query){
                $query->withTrashed();
            }])->get();
        }

        $divisions = Division::select('id', 'division_code', 'division_name')->get();

        return view('department.index', compact('departments', 'divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
		
		if($request->hasFile('foto')){
            $file = $request->file('foto');
            $file_name = $file->getClientOriginalExtension();
            $file->move(base_path('public/uploads/jobimg/'), $file_name);
        }else{
            $file_name = null;
        }
		
        Department::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Department berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::findOrFail($id);

        return response()->json($department);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, Department $department)
    {
		if($request->hasFile('foto')){
            $file = $request->file('foto');
            $file_name = $file->getClientOriginalExtension();
            $request->file('foto')->move(public_path('uploads/jobimg'), $file_name);
            $request_data['foto'] = $file_name;
        }else{
            $file_name = null;
        }
		
        $department->update([
				'department_code'=> $request->input('department_code'),
				'department_name'=> $request->input('department_name'),
				'department_description'=> $request->input('department_description'),
				'foto'=>$file_name ]);
		
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Department berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $department = Department::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()->can('restore-division')) {
            $department->forceDelete();
        }else{
            $department->delete();
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Department berhasil dihapus'
        ]);

        return response()->json(true);
    }

    /**
     * Restore soft deletes
     *
     * @param int $id
     * @return void
     */
    public function restore($id)
    {
        Department::withTrashed()->findOrFail($id)->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Departemen berhasil dikembalikan semula'
        ]);

        return response()->json();
    }

    /**
     * Export Excel
     *
     * @return void
     */
    public function excel()
    {
        return Excel::download(new DepartmentExport, 'departemen-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new DepartmentExport, 'departemen-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new DepartmentExport, 'departemen-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function assign(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $department->update([
            'head_manager' => $request->manager_id,
            'head_supervisor' => $request->supervisor_id
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Manager dan Supervisor Kepala berhasil disimpan'
        ]);
    }

    public function heads(Request $request)
    {
        $managers = [];
        $supervisors = [];

        $department = Department::with('manager', 'supervisor')->find($request->department_id);

        foreach ($this->_getManagers($request) as $manager) {
            $managers[] = [
                'id' => $manager->id,
                'text' => $manager->fullname
            ];
        }

        foreach ($this->_getSupervisors($request) as $supervisor) {
            $supervisors[] = [
                'id' => $supervisor->id,
                'text' => $supervisor->fullname
            ];
        }

        return response()->json(compact('managers', 'supervisors', 'department'));
    }

    private function _getManagers($request)
    {
        return Employee::whereHas('grade_title', function($query){
            $query->where('grade_title_name', 'Manager');
        })->whereHas('department', function($query) use($request) {
            $query->where('id', $request->department_id);
        })->get();
    }
    
    private function _getSupervisors($request)
    {
        return Employee::whereHas('grade_title', function($query){
            $query->where('grade_title_name', 'Supervisor');
        })->whereHas('department', function($query) use($request) {
            $query->where('id', $request->department_id);
        })->get();
    }

}
