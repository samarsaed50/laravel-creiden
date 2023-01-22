<?php

namespace App\Models\Admins\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',

        ];
    }

    public function attributes()
    {
        return [
            'name' => __('labels.backend.customers.name'),
            'email' => __('labels.backend.customers.email'),
            'phone' => __('labels.backend.customers.phone'),
            'password' => __('labels.backend.customers.password'),
        ];
    }
}
