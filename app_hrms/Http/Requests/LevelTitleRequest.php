<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LevelTitleRequest extends FormRequest
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
            'level_title_name' => 'required',
            'level_title_type' => [
                'required',
                Rule::in(['Managerial', 'Non Managerial'])
            ]
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'level_title_code' => 'required|unique:level_titles'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'level_title_code' => [
                    'required',
                    Rule::unique('level_titles')->ignore($this->level_title)
                ]
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'level_title_code.unique' => 'Kode Level Title telah terpakai'
        ];
    }
}
