<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DivisionRequest extends FormRequest
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
            'division_name' => 'required'
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'division_code' => 'required|unique:divisions'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'division_code' => [
                    'required',
                    Rule::unique('divisions')->ignore($this->division)
                ]
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'division_code.unique' => 'Kode divisi telah terpakai'
        ];
    }
}
