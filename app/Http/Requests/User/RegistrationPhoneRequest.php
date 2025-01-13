<?php namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationPhoneRequest extends FormRequest
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
            'phone' => 'required|numeric|unique:users,phone',
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
            'phone.required' => 'Номер телефону обов’язковий',
            'phone.numeric' => 'Номер телефону має бути числом',
            'phone.unique' => 'Користувач з таким номером вже існує',
        ];
    }
}
