<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Exports\CompanyRegionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CompanyRegionRequest;

class CompanyRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('restore-company_region')){
            $company_regions = CompanyRegion::withTrashed()->get();
        }else{
            $company_regions = CompanyRegion::all();
        }

        return view('company_region.index', compact('company_regions'));
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
    public function store(CompanyRegionRequest $request)
    {
        CompanyRegion::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'CompanyRegion berhasil ditambahkan'
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
        $company_region = CompanyRegion::findOrFail($id);

        return response()->json($company_region);
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
    public function update(CompanyRegionRequest $request, CompanyRegion $company_region)
    {
        $company_region->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Company Region berhasil diupdate'
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
        $company_region = CompanyRegion::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()-can('restore-company_region')) {
            $company_region->forceDelete();
        }else{
            $company_region->delete();
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Company Region berhasil dihapus'
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
        CompanyRegion::withTrashed()->findOrFail($id)->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Company Region berhasil dikembalikan semula'
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
        return Excel::download(new CompanyRegionExport, 'company-region-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new CompanyRegionExport, 'company-region-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new CompanyRegionExport, 'company-region-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
}
