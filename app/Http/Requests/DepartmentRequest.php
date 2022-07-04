<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'department_name' => 'required',
            'division_id' => 'required|exists:divisions,id',
			//'images' => 'nullable|image|max:512'
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'department_code' => 'required|unique:departments'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'department_code' => [
                    'required',
                    Rule::unique('departments')->ignore($this->department)
                ]
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'department_code.unique' => 'Kode Departemen telah terpakai'
        ];
    }
}
