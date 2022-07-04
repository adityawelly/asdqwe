<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:Internal,External',
            'category' => 'required|in:Technical,Softskill',
            'name' => 'required',
            'vendor' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'duration' => 'required',
            'participants' => 'required|exists:employees,id',
        ];
    }
}
