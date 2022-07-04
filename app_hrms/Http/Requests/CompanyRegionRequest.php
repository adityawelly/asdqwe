<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRegionRequest extends FormRequest
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
            'region_address' => 'required'
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'region_city' => 'required|unique:company_regions'
            ];
        }elseif ($this->getMethod() == 'PUT') {
            $rules += [
                'region_city' => [
                    'required',
                    Rule::unique('company_regions')->ignore($this->company_region)
                ]
            ];
        }

        return $rules;
    }
}
