<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PAPeriodeRequest extends FormRequest
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
            'periode_name' => 'required'
        ];

        return $rules;
    }
/*
    public function messages()
    {
        return [
            'division_code.unique' => 'Kode divisi telah terpakai'
        ];
    }
*/
}
