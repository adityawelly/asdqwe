<?php

namespace App\Http\Controllers;

use App\Models\LevelTitle;
use Illuminate\Http\Request;
use App\Exports\LevelTitleExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\LevelTitleRequest;

class LevelTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('restore-level_title')){
            $level_titles = LevelTitle::withTrashed()->get();
        }else{
            $level_titles = LevelTitle::all();
        }

        return view('level_title.index', compact('level_titles'));
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
    public function store(LevelTitleRequest $request)
    {
        LevelTitle::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Level Title berhasil ditambahkan'
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
        $level_title = LevelTitle::findOrFail($id);

        return response()->json($level_title);
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
    public function update(LevelTitleRequest $request, LevelTitle $level_title)
    {
        $level_title->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Level Title berhasil diupdate'
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
        $level_title = LevelTitle::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()->can('restore-level_title')) {
            $level_title->forceDelete();
        }else{
            $level_title->delete();
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Level Title berhasil dihapus'
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
        LevelTitle::withTrashed()->findOrFail($id)->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Level Title berhasil dikembalikan semula'
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
        return Excel::download(new LevelTitleExport, 'level-title-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new LevelTitleExport, 'level-title-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new LevelTitleExport, 'level-title-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
}
