<?php

namespace App\Http\Controllers;

use App\Exports\TrainingExport;
use App\Http\Requests\TrainingRequest;
use App\Models\Training;
use Illuminate\Http\Request;
use App\Imports\TrainingImport;
use App\Mail\ApprovalTraining;
use App\Models\Employee;
use App\Models\TrainingSubmission;
use App\Notifications\NotifApprovalTraining;
use App\Notifications\NotifNewTraining;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainings = Training::all();
        $training_submissions = TrainingSubmission::with('employees', 'submitted_by')->get();

        return view('training.index', compact('trainings', 'training_submissions'));
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
    public function store(TrainingRequest $request)
    {
        DB::beginTransaction();
        try {
            $training = Training::create($request->except('participants'));
            $training->employees()->sync($request->participants);
            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Training berhasil diinput'
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('alert',[
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function show(Training $training)
    {
        return response()->json($training->load('employees'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function edit(Training $training)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function update(TrainingRequest $request, Training $training)
    {
        DB::beginTransaction();
        try {
            $training->update($request->except('participants'));
            $training->employees()->detach();
            $training->employees()->attach($request->participants);
            DB::commit();

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Training berhasil diupdate'
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function destroy(Training $training)
    {
        $training->delete();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Training berhasil dihapus'
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
        return Excel::download(new TrainingExport, 'training-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new TrainingExport, 'training-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new TrainingExport, 'training-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function import(Request $request)
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

        $importer = new TrainingImport;
        $reset_string = '';

        try {
            if ($request->has('reset_data')) {
                Training::query()->delete();
                $reset_string = ' reset data dan';
            }
            $importer->import($request->file('file'));

            return redirect()->back()->with('alert',[
                'type' => 'success',
                'msg' => 'Berhasil'.$reset_string.' mengimport: '.$importer->counter.' data training'
            ]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('import_error', $failures);
        } catch (Exception $e) {
            // dd($e);
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function participants(Request $request)
    {
        $training = Training::findOrFail($request->training_id);

        $html = '<ul class="list-group">';
        foreach ($training->employees as $employee) {
            $html .= '<li class="list-group-item"><a href="'.route('employee.show', $employee->id).'" target="_blank">'.$employee->registration_number.'</a> - '.$employee->fullname.'</span>';
            $html .= '</li>';
        }
        $html .= '</ul>';

        return response()->json([
            'html' => $html
        ]);
    }

    public function submission()
    {
        $training_submissions = TrainingSubmission::with('employees')
                                    ->where('submit_by', auth()->user()->employee->id)->get();

        return view('pengajuan.training', compact('training_submissions'));
    }

    public function submission_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'vendor' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'duration' => 'required|numeric|between:0,99.99',
            'notes' => 'nullable',
            'cost' => 'nullable',
            'participants' => 'required|exists:employees,id',
            'file' => 'nullable|file|max:1024|mimes:jpg,png,pdf'
        ]);

        $data = $request->except(['participants']);
        $participants = $request->participants;
        $requestor = auth()->user()->employee;
        
        $data['submit_by'] = $requestor->id ?? null;
        $data['status'] = 5;
        if ($data['cost']) {
            $data['cost'] = str_replace('.', '', $data['cost']);
        }

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time().'-training-submission.'.$file->getClientOriginalExtension();
                $file->move('uploads/training_submissions/', $filename);
                $data['file'] = $filename;
            }
            DB::transaction(function () use($data, $participants, $requestor){
                $training_submission = TrainingSubmission::create($data);
                foreach ($participants as $participant) {
                    DB::table('employees_training_submissions')->insert([
                        'training_submission_id' => $training_submission->id,
                        'employee_id' => $participant
                    ]);
                }
                $requestor->superior->user->notify(new NotifNewTraining($training_submission));
            });
            

            return redirect('pengajuan/training')->with('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan training berhasil. Mohon tunggu approval.'
            ]);
        } catch (Exception $ex) {
            if ($request->hasFile('file')) {
                unlink('uploads/training_submissions/'.$filename);
            }
            return redirect('pengajuan/training')->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function submission_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'vendor' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'duration' => 'required|numeric|between:0,99.99',
            'notes' => 'nullable',
            'cost' => 'nullable',
            'participants' => 'required|exists:employees,id',
            'file' => 'nullable|file|max:1024|mimes:jpg,png,pdf'
        ]);

        $submission = TrainingSubmission::findOrFail($id);

        if (!in_array($submission->status, [5,15,25])) {
            return redirect('pengajuan/training')->with('alert', [
                'type' => 'danger',
                'msg' => 'Aksi Tidak diperbolehkan'
            ]);
        }

        $data = $request->except(['participants']);
        $participants = $request->participants;
        // $grade_title_name = auth()->user()->employee->grade_title->grade_title_name;

        $data['status'] = 5;
        if ($submission->status == 25) {
            $data['status'] = 10;
        }

        try {
            if ($request->hasFile('file')) {
                $filename = time().'-training-submission';
                $request->file('file')->move('uploads/training_submissions/', $filename);
                $data['file'] = $filename;
            }
            DB::transaction(function () use($data, $participants, $submission){
                $submission->update($data);
                $submission->employees()->sync($participants);
                if ($submission->file) {
                    unlink('uploads/training_submissions/'.$submission->file);
                }
                auth()->user()->employee->superior->user->notify(new NotifNewTraining($submission));
            });

            return redirect('pengajuan/training')->with('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan berhasil diubah.'
            ]);
        } catch (\Exception $ex) {
            return redirect('pengajuan/training')->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function approval()
    {
        $training_submissions = TrainingSubmission::with('employees', 'submitted_by');
        $teams = Employee::where('direct_superior', auth()->user()->employee->id)->get();

        if (auth()->user()->can('training-spv-approval')) {
            $training_submissions_team = TrainingSubmission::with('employees', 'submitted_by')
                        ->whereIn('submit_by', $teams->pluck('id')->toArray())
                        ->where('status', 5);
            $training_submissions->where(function($query){
                $query->where('status', 10)
                        ->orWhere('status', 20);
            })->union($training_submissions_team);
        }else{
            $training_submissions->whereIn('submit_by', $teams->pluck('id')->toArray())
                                    ->where('status', 5);
        }
        $training_submissions = $training_submissions->get();

        return view('pengajuan.training_approval', compact('training_submissions'));
    }

    public function approve(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'reject_note' => 'nullable',
            'training_submission_id' => 'required|exists:training_submissions,id'
        ]);

        $id = $request->training_submission_id;
        
        if (auth()->user()->can('training-spv-approval')) {
            if ($request->status == 'approve') {
                $status_code = 20;
            }else{
                $status_code = 25;
            }
        }else{
            if ($request->status == 'approve') {
                $status_code = 10;
            }else{
                $status_code = 15;
            }
        }

        $training_submission = TrainingSubmission::findOrFail($id);

        $training_submission->update([
            'status' => $status_code,
            'reject_note' => $request->reject_note
        ]);

        $training_submission->submitted_by->user->notify(new NotifApprovalTraining($training_submission, [
            'sender_name' => auth()->user()->employee->fullname,
            'status' => $request->status,
        ]));

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Pengajuan training status diubah'
        ]);

        if ($request->status == 'approve') {
            return response()->json(true);
        }

        return redirect()->back();
    }

    public function training_submissions_delete($id)
    {
        $training_submission = TrainingSubmission::findOrFail($id);

        if (!in_array($training_submission->status, [5,15,25])) {
            session()->flash('alert', [
                'type' => 'danger',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);

            return response()->json(true);
        }

        $training_submission->delete();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Pengajuan training dihapus'
        ]);

        return response()->json(true);
    }

    public function training_submissions_edit($id)
    {
        $training_submission = TrainingSubmission::with('employees')->findOrFail($id);

        if (!in_array($training_submission->status, [5,15,25])) {
            abort(400);
        }

        return response()->json($training_submission);
    }

    public function submission_migrate(Request $request)
    {
        $training_submission = TrainingSubmission::with('employees')->findOrFail($request->id);
        $training_submission->status = 10;
        $data = $training_submission->toArray();
        unset($data['reject_note']);
        unset($data['file']);
        unset($data['cost']);
        unset($data['employees']);

        DB::beginTransaction();
        try {
            $training = Training::create($data);

            $training->employees()->sync($training_submission->employees->pluck('id')->toArray());

            $training_submission->update([
                'status' => 50
            ]);

            DB::commit();
            session()->flash('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan training dimigrasi ke training'
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            dd($ex);
            session()->flash('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
        return response()->json(true);
    }

}
