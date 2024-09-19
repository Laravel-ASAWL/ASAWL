<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.string' => 'El campo nombre debe ser una cadena de caracteres',
            'name.max' => 'El campo nombre debe tener menos de :max caracteres',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.email' => 'El campo correo electrónico no es válido',
            'email.unique' => 'El campo correo electrónico ya está registrado',
            'password.required' => 'La campo contraseña es obligatorio',
            'password.min' => 'La campo contraseña debe tener al menos :min caracteres',
            'password.confirmed' => 'Las campo contraseñas no coinciden',
        ];
    }
}
