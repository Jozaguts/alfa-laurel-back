<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'string',
            'code' =>'string|min:4',
            'email' => 'unique:users,email,'.$this->id,
            'password' => 'string|min:6|nullable',
            'paternal_name' => 'string',
            'maternal_name' => 'string',
            'birthday' => 'date',
            'address' => 'string',
            'phone' => 'digits:10,13',
            'contact_name' => 'string',
            'comments' => 'string|nullable',
        ];
    }
    public function attributes()
    {
        return [
            'phone' => 'Teléfono',
            'name' => 'Nombre',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
            'paternal_name' => 'Apellido Paterno',
            'maternal_name' => 'Apellido Materno',
            'birthday' => 'Fecha de nacimiento',
            'address' => 'Dirección',
            'contact_name' => 'Nombre de contacto',
            'comments' => 'Comentario',
        ];
    }
}
