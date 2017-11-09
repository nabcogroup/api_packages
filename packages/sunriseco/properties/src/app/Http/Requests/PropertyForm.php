<?php

namespace Sunriseco\Properties\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyForm extends FormRequest
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
            'code'  => 'required',
            'name'  =>  'required'
        ];

        return $validate;
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function() use ($validator) {
            $villas = $this->input('villas',null);
            if(is_array($villas) && sizeof($villas) > 0) {
                //validate entry
                foreach ($villas as $villa) {

                    if(!isset($villa['villa_no']) || is_null($villa['villa_no'])) {
                        $validator->errors()->add('villa_no','Villa No. is required');
                    }

                    if(is_null($villa['rate_per_month'])) {
                        $validator->errors()->add('rate_per_month','Rate/month is required');
                    }
                    else {
                        if(!is_numeric($villa['rate_per_month'])) {
                            $validator->errors()->add('rate_per_month','Rate/month should be numeric value');
                        }
                    }

                }
            }

        });
        return $validator;
    }


}
