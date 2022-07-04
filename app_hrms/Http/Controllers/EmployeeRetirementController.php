<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeRetirementExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\EmployeeRetirement;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeRetirementController extends Controller
{
    private $reasons;

    public function __construct() {
        $this->reasons = [
            'Habis Kontrak',
            'PHK - Pensiun Dini',
            'PHK - Sakit',
            'PHK - Kasus Pidana',
            'PHK - Berkelahi',
            'Resign Hamil',
            'Resign Kemauan Sendiri',
            'Resign Sakit',
            'Tanpa Keterangan',
            'Dinyatakan Tidak Sehat',
            'Alasan Lainnya'
        ];
    }

    public function index()
    {
        $employee_retirements = EmployeeRetirement::all();

        return view('employee_retirement.index', compact('employee_retirements'));
    }

    public function show($id)
    {
        $employee_retirement = EmployeeRetirement::findOrFail($id);

        return response()->json($employee_retirement);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date_of_retirement' => 'required|date',
            'reason' => [
                'required',
                Rule::in($this->reasons)
            ],
        ]);

        $employee_retirement = EmployeeRetirement::findOrFail($id);

        $employee_retirement->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Data Resign Karyawan berhasil diupdate'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $employee_retirement = EmployeeRetirement::findOrFail($id);

        $employee_retirement->delete();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Data Resign dihapus, karyawan dinyatakan bekerja kembali'
        ]);

        return response()->json(true);
    }

    public function resign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_of_retirement' => 'required|date',
            'reason' => [
                'required',
                Rule::in($this->reasons)
            ],
        ]);

        DB::beginTransaction();
        try {
            $employee_retirement = EmployeeRetirement::create($request->all());
            $employee_retirement->employee->user->delete();

            DB::commit();
            return response()->json([
                'type' => 'success',
                'msg' => $employee_retirement->employee->fullname.' telah dinyatakan resign.'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Export Excel
     *
     * @return void
     */
    public function excel()
    {
        return Excel::download(new EmployeeRetirementExport, 'employee-resign-'.date('Y-m-d').'.xlsx');
    }
}
