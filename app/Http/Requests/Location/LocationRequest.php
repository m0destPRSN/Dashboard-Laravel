<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import the Rule class

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Set to true if you don't have specific authorization logic here
        // or implement your authorization logic (e.g., check user roles/permissions)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Get the location ID from the route parameter, if available (for updates)
        // For store (create) route, $this->route('location') will be null.
        $locationId = $this->route('location');

        return [
            'location' => [
                'required',
                'regex:/^\-?\d{1,2}\.\d+,\s?\-?\d{1,3}\.\d+$/',
                // Ensures location is unique, ignoring the current location's ID during an update.
                // For create, $locationId is null, so it checks for uniqueness among all records.
                Rule::unique('locations', 'location')->ignore($locationId),
            ],
            'id_type' => 'required|exists:types,id',
            'id_category' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500', // Kept from your existing rules

            // Rules for photo uploads (handles multiple files from 'photos[]' input)
            'photos' => 'nullable|array', // The 'photos' field itself should be an array if present
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Each file in the array

            // Rules for start and end times
            'start_time' => 'nullable|date_format:H:i|required_without:end_time',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time|required_without:start_time',

            // Rule for user_id:
            // - For POST (create) requests, 'user_id' is nullable (as it's set by Auth::id() in controller).
            // - For PUT/PATCH (update) requests, 'user_id' is required.
            // - In both cases, if 'user_id' is provided, it must exist in the 'users' table.
            'user_id' => [
                $this->isMethod('post') ? 'nullable' : 'required',
                'exists:users,id',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'location.required' => 'Поле локації є обов’язковим.',
            'location.regex' => 'Локація має бути у форматі "широта, довгота" (наприклад, "50.258735, 28.603900").',
            'location.unique' => 'Ця локація вже існує в системі.',
            'id_type.required' => 'Поле типу локації є обов’язковим.',
            'id_type.exists' => 'Обраний тип локації недійсний.',
            'id_category.required' => 'Поле категорії є обов’язковим.',
            'id_category.exists' => 'Обрана категорія недійсна.',
            'title.required' => 'Заголовок є обов’язковим.',
            'title.max' => 'Заголовок не може перевищувати 255 символів.',
            'description.string' => 'Опис має бути рядком.',
            'description.max' => 'Опис не може перевищувати 500 символів.',
            'photos.array' => 'Поле фотографій повинно бути масивом.',
            'photos.*.image' => 'Кожен файл повинен бути зображенням.',
            'photos.*.mimes' => 'Фото повинно бути формату jpeg, png, jpg, gif або svg.',
            'photos.*.max' => 'Максимальний розмір кожного фото не повинен перевищувати 2 МБ.',
            'start_time.date_format' => 'Невірний формат початкового часу (має бути ГГ:ХХ).',
            'start_time.required_without' => 'Поле початкового часу є обов’язковим, якщо вказано кінцевий час.',
            'end_time.date_format' => 'Невірний формат кінцевого часу (має бути ГГ:ХХ).',
            'end_time.after_or_equal' => 'Кінцевий час не може бути раніше початкового часу.',
            'end_time.required_without' => 'Поле кінцевого часу є обов’язковим, якщо вказано початковий час.',
            'user_id.required' => 'Поле власника локації є обов’язковим.',
            'user_id.exists' => 'Обраний власник локації недійсний.',
        ];
    }
}
