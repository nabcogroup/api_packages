<?php

namespace Sunriseco\Tenants\App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TenantForm extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function messages()
    {
        $tenantType = $this->input('type');
        
        $message = array();
        
        if($tenantType == 'individual') {
            $message = [
                "reg_name"  =>  "Full Name is required",
                "reg_id"    =>  "Qatar Id is required",
                "reg_date"  =>  "Birthday is required"
            ];
        }
        else {
            $message = [
                "reg_name"  =>  "Business Name is required",
                "reg_id"    =>  "CR No is required",
                "reg_date"  =>  "Validity Date is required"
            ];
        }

        return [
            'full_name.required'        => $message['reg_name'],
            'email_address.required'    => 'Email is required',
            'email_address.email'    => 'Email must be valid email address',
            'reg_id.required'           => $message['reg_id'],
            'reg_date.required'         => $message['reg_date'],
            'email_address.email.required'       => 'Email must be valid',
            'mobile_no.required_without_all'                 =>  'Please enter mobile no or tel no',
            "address_instance.address_1.required"          =>  'Address is required',
            "address_instance.city.required"                =>  'City is required',
            "address_instance.postal_code.required"         =>  'Postal Code is required'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "type"          =>  "required",

            "full_name"     =>  "required",

            "reg_date"      =>  "required|date",

            "reg_id"        =>  "required",

            "email_address" =>  "required|email",

            "mobile_no"     =>  "required_without_all:register_tenant.tel_no",

            "tenant_address.address_1"        =>  'required',

            "tenant_address.city"             =>  'required',

            "tenant_address.postal_code"      =>  'required'
        ];
    }

    public function filterInput() {

        $result = $this->all();

        $result['reg_date'] = Carbon::parse($result['reg_date']);
        

        return $result;

    }
}
