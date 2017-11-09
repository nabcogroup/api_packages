<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VillaForm extends FormRequest
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
        $validate = [
            'villa_no'          =>  'required',

            'rate_per_month'    =>  array('required','regex:/^\d+?|^\d+\.\d{2}?/'),

            'description'       =>  'required',

            'electricity_no'    =>  'required',

            'water_no'          =>  'required',

            'qtel_no'           =>  'required',

            'capacity'          =>  'required|numeric',

            'villa_class'       =>  'required',

            'location'          =>  'required'
        ];

        if($this->id == 0)
            $validate['villa_no'] =  'required|unique:villas';

        return $validate;
    }



}
