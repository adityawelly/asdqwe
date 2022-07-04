<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GradeTitleRequest extends FormRequest
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
            'grade_title_name' => 'required'
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'grade_title_code' => 'required|unique:grade_titles'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'grade_title_code' => [
                    'required',
                    Rule::unique('grade_titles')->ignore($this->grade_title)
                ]
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'grade_title_code.unique' => 'Kode Grade Title telah terpakai'
        ];
    }
}
