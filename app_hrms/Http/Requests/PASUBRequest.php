<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PASUBRequest extends FormRequest
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
            'Namasub' => 'required'
        ];
		
        return $rules;
    }

/*
    public function messages()
    {
        return [
            'name.unique' => 'Nama Draf telah terpakai'
        ];
    }
*/
}
