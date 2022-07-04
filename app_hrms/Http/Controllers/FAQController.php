<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\FAQRequest;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = FAQ::all();     
        return view('faq.index', compact('faqs'));
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
    public function store(FAQRequest $request)
    {
        FAQ::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'FAQ berhasil ditambahkan'
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
        $faq = FAQ::findOrFail($id);

        return response()->json($faq);
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
    public function update(FAQRequest $request, FAQ $faq)
    {
        $faq->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'FAQ berhasil diupdate'
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
        $faq = FAQ::findOrFail($id);

        DB::transaction(function () use ($faq){
                $faq->delete();
            });
        

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'FAQ berhasil dihapus'
        ]);

        return response()->json(true);
    }
    /**
     * Export Excel
     *
     * @return void
     */
    public function excel()
    {
        return Excel::download(new FAQExport, 'faq-'.date('Y-m-d').'.xlsx');
    }

}
