<?php

namespace App\Models\Users\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
     
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.request()->segment(3),
            'password' => 'required|string|min:6',
            'storage_id' => ['nullable','exists:storages,id']
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('labels.backend.customers.name'),
            'email' => __('labels.backend.customers.email'),
            'password' => __('labels.backend.customers.password'),
        ];
    }
}
