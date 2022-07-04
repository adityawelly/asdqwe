<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Imports\EmployeeHKImport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;

class EmployeeHKController extends Controller
{
    public function import_hk(Request $request)
    {
        $validator = Validator::make([
            'file' => $request->file('file'),
            'ekstensi' => strtolower($request->file('file')->getClientOriginalExtension())
        ],[
            'file' => 'required|max:2048|file',
            'ekstensi' => 'required|in:xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json($validator);
        }

        $importer = new EmployeeHKImport;

        try {
            $importer->import($request->file('file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('import_error', $failures);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('alert',[
            'type' => 'success',
            'msg' => 'Berhasil mengimport: '.$importer->counter.' data hari kerja'
        ]);
    }
}
