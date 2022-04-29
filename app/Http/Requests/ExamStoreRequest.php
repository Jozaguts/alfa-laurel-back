<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ExamStoreRequest extends FormRequest
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
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
//            'number' => 'required|integer',
            'questions.*.question' =>  [Rule::requiredIf(request()->hasFile('file')),'string'],
            'file' =>  [Rule::requiredIf(request()->hasFile('file')),'file'],
            'questions.*.option1' => [Rule::requiredIf(!request()->hasFile('file')),'string'],
            'questions.*.option2' =>  [Rule::requiredIf(!request()->hasFile('file')),'string'],
            'questions.*.option3' =>  [Rule::requiredIf(!request()->hasFile('file')),'string'],
            'questions.*.level' =>  [Rule::requiredIf(!request()->hasFile('file')),'string'],
            'questions.*.answer' =>  [Rule::requiredIf(!request()->hasFile('file')),'string'],
        ];
    }
}
