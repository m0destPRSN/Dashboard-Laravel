<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterUserInfoRequest extends FormRequest
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

    public function rules()
    {
        return [
            'id' => 'required_without:phone|numeric|exists:users,id',
            'phone' => 'required_without:id|numeric|exists:users,phone',
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
        ];
    }


    /**
     * Custom error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required_without' => 'Потрібно вказати або id, або номер телефону',
            'id.numeric' => 'id має бути числом',
            'id.exists' => 'Користувача з таким id не існу',
            'phone.required_without' => 'Потрібно вказати або номер телефону або id',
            'phone.numeric' => 'Номер телефону має бути числом',
            'phone.exists' => 'Користувача з таким номером не існує',
            'first_name.required' => 'Ім’я обов’язкове',
            'second_name.required' => 'Прізвище обов’язкове',
        ];
    }

}
