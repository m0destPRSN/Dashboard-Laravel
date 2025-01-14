<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'location' => 'required|regex:/^\-?\d{1,2}\.\d+,\s?\-?\d{1,3}\.\d+$/|unique:locations,location',
            'id_type' => 'required|exists:types,id',
            'id_category' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg',
            'start_time' => 'nullable|date_format:H:i|required_without:end_time',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time|required_without:start_time',
        ];
    }

    public function messages()
    {
        return [
            'location.required' => 'Поле локації є обов’язковим',
            'location.regex' => 'Локація має бути у форматі "широта, довгота" (наприклад, "50.258735, 28.603900")',
            'location.unique' => 'Ця локація вже існує в системі',
            'id_type.required' => 'Поле типу локації є обов’язковим',
            'id_type.exists' => 'Тип локації не знайдений',
            'id_category.required' => 'Поле категорії є обов’язковим',
            'id_category.exists' => 'Категорія не знайдена',
            'title.required' => 'Заголовок є обов’язковим',
            'description.string' => 'Опис має бути рядком',
            'photo.file' => 'Фото повинно бути файлом',
            'photo.mimes' => 'Фото повинно бути формату jpeg, png, jpg або gif',
            'photo.max' => 'Максимальний розмір файлу фото не повинен перевищувати 2 МБ',
            'start_time.date_format' => 'Невірний формат початкового часу',
            'start_time.required_without' => 'Поле початкового часу є обов’язковим, якщо вказано кінцевий час',
            'end_time.date_format' => 'Невірний формат кінцевого часу',
            'end_time.after_or_equal' => 'Кінцевий час не може бути до початкового часу',
            'end_time.required_without' => 'Поле кінцевого часу є обов’язковим, якщо вказано початковий час',
        ];
    }
}
