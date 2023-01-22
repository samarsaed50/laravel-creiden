<?php

namespace App\Models\Storages\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreStorageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name' => 'required|string',

        ];
    }

    public function attributes()
    {
        return [
            '' => __('labels.backend.storages.'),
        ];
    }
}
