<?php

namespace Modules\Iad\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateBidRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'ad_id' => 'required|min:1',
            'description' => 'required|min:10',
            'amount' => 'required'
        ];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }

    public function getValidator(){
        return $this->getValidatorInstance();
    }
    
}
