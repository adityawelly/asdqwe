<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JobTitleRequest extends FormRequest
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
        $rules = [
            'job_title_name' => 'required',
            'department_id' => 'required'
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'job_title_code' => 'required|unique:job_titles'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'job_title_code' => [
                    'required',
                    Rule::unique('job_titles')->ignore($this->job_title)
                ]
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'job_title_code.unique' => 'Kode Job TItle telah terpakai'
        ];
    }
}
