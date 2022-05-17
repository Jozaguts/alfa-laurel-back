<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
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
        return [
            'subject_id' => 'required|exists:subjects,id',            
			'exam_id' => 'required|exists:exams,id',
			'user_id' => 'required|exists:users,id',
			'minutes_assigns' => 'required|integer',
			'minutes' => 'required|integer',
            'student_code' => 'required|max:20',
            'student_name' => 'required|max:100'
        ];
    }
}
