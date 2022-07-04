<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FAQRequest extends FormRequest
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
            'answered' => 'required'	
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'question' => 'required'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'question' => [
                    'required',                  
                ]
            ];
        }

        return $rules;
    }
}
