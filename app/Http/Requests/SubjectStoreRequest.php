<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $data = $this->request->all();
        return [
            'name' =>[
                'required',
                'string',
                Rule::unique('subjects','name')->whereNull('deleted_at')
            ]
        ];
    }

    public function attributes()
    {
        return ['name' => 'materia'];
    }
}
