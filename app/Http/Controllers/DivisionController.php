<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use App\Exports\DivisionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\DivisionRequest;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('restore-division')) {
            $divisions = Division::withTrashed()->get();
        }else{
            $divisions = Division::all();
        }

        return view('division.index', compact('divisions'));
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
    public function store(DivisionRequest $request)
    {
        Division::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Divisi berhasil ditambahkan'
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
        $division = Division::findOrFail($id);

        return response()->json($division);
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
    public function update(DivisionRequest $request, Division $division)
    {
        $division->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Divisi berhasil diupdate'
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
        $division = Division::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()->can('restore-division')) {
            $division->forceDelete();
        }else{
            DB::transaction(function () use ($division){
                $division->delete();
                $division->departments()->delete();
            });
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Divisi & Departemen yang terhubung berhasil dihapus'
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
        $division = Division::withTrashed()->findOrFail($id);

        $division->restore();
        $division->departments()->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Divisi berhasil dikembalikan semula'
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
        return Excel::download(new DivisionExport, 'division-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new DivisionExport, 'division-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new DivisionExport, 'division-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function get_departments(Division $division)
    {
        $data = [];
        foreach ($division->departments as $department) {
            $data[] = [
                'id' => $department->id,
                'text' => $department->department_code.'-'.$department->department_name
            ];
        }

        return response()->json($data);
    }
}
