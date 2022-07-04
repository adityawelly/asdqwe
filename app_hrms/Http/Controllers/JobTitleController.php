<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;
use App\Exports\JobTitleExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\JobTitleRequest;
use App\Models\Department;

class JobTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('restore-job_title')) {
            $job_titles = JobTitle::withTrashed()->with('department')->get();
        }else{
            $job_titles = JobTitle::with('department')->get();
        }

        $departments = Department::all();

        return view('job_title.index', compact('job_titles', 'departments'));
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
    public function store(JobTitleRequest $request)
    {
        JobTitle::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Job Title berhasil ditambahkan'
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
        $job_title = JobTitle::findOrFail($id);

        return response()->json($job_title->load('department'));
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
    public function update(JobTitleRequest $request, JobTitle $job_title)
    {
        $job_title->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Job Title berhasil diupdate'
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
        $job_title = JobTitle::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()->can('restore-job_title')) {
            $job_title->forceDelete();
        }else{
            $job_title->delete();
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Job Title berhasil dihapus'
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
        JobTitle::withTrashed()->findOrFail($id)->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Job Title berhasil dikembalikan semula'
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
        return Excel::download(new JobTitleExport, 'job-title-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new JobTitleExport, 'job-title-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new JobTitleExport, 'job-title-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
}
