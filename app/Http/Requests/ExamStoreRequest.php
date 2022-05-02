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

            'file' =>  [Rule::requiredIf(request()->hasFile('file')),'file'],

            'questions.*.question' =>  [Rule::requiredIf(request()->hasFile('file')),'string'],
            'questions.*.number' =>  [Rule::requiredIf(!request()->hasFile('file')),'integer'],
            'questions.*.level' =>  [Rule::requiredIf(!request()->hasFile('file')),'string'],
            'questions.*.answer' =>  [Rule::requiredIf(!request()->hasFile('file')),'integer'],
            'questions.*.options.*.option' => [Rule::requiredIf(!request()->hasFile('file')),'string'],
            'questions.*.options.*.is_answer' => [Rule::requiredIf(!request()->hasFile('file')),'boolean'],
        ];
    }
}
