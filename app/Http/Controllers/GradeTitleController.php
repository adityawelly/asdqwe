<?php

namespace App\Http\Controllers;

use App\Models\GradeTitle;
use Illuminate\Http\Request;
use App\Exports\GradeTitleExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\GradeTitleRequest;

class GradeTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('restore-grade_title')){
            $grade_titles = GradeTitle::withTrashed()->get();
        }else{
            $grade_titles = GradeTitle::all();
        }

        return view('grade_title.index', compact('grade_titles'));
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
    public function store(GradeTitleRequest $request)
    {
        GradeTitle::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Grade Title berhasil ditambahkan'
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
        $grade_title = GradeTitle::findOrFail($id);

        return response()->json($grade_title);
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
    public function update(GradeTitleRequest $request, GradeTitle $grade_title)
    {
        $grade_title->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Grade Title berhasil diupdate'
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
        $grade_title = GradeTitle::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()->can('restore-grade_title')) {
            $grade_title->forceDelete();
        }else{
            $grade_title->delete();
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Grade Title berhasil dihapus'
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
        GradeTitle::withTrashed()->findOrFail($id)->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Grade Title berhasil dikembalikan semula'
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
        return Excel::download(new GradeTitleExport, 'grade-title-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new GradeTitleExport, 'grade-title-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new GradeTitleExport, 'grade-title-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
}
