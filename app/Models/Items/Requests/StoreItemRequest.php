<?php

namespace App\Models\Items\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validation[''] = '';
        // rules

        return $validation;

    }

    public function attributes()
    {
        return [
            '' => __('labels.backend.items.'),
        ];
    }
}
