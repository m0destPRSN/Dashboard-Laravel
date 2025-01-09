<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationPhoneRequest extends FormRequest
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
            'id' => 'required_without:phone|numeric|exists:users,id',
            'phone' => 'required_without:id|numeric|exists:users,phone',
            'otp_code' => 'required|numeric|digits:4',
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
            'phone.unique' => 'Користувача з таким номером не існує',
            'otp_code.required'=>"Код обов'язковий",
            'otp_code.numeric' => 'Код має бути числом',
            'otp_code.digits' => 'Код OTP має містити рівно 4 цифри',
        ];
    }
}
