<?php

namespace App\Models\Users\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
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
