<?php

namespace App\Http\Resources;

use App\Models\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'answer_id' => $this->id,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject->name,
            'exam_id' => $this->exam_id,
            'exam_name' => $this->exam->name,
            'user_id' => $this->user_id,
            'minutes_assigns' => $this->minutes_assigns,
            'minutes' => $this->minutes,
            'student_code' => $this->student_code,
            'student_name' => $this->student_name,


            'answer_details' => array_map(function($detail){

                return [
                    'id' => $detail['id'],
                    'answer_id' => $detail['answer_id'],
                    'question_id' => $detail['question_id'],
                    'number' => $detail['number'],
                    'question' => $detail['question'],
                    'option1' => $detail['option1'],
                    'option2' => $detail['option2'],
                    'option3' => $detail['option3'],
                    'answer' => $detail['answer'],
                    'level' => $detail['level'],
                    'is_correct' => $detail['is_correct'],
                    'correct_answer' => Option::where('question_id',$detail['question_id'])
                        ->where('is_answer',1)
                        ->first()
//                    'number_answer' => $detail['number_answer']
                ];
            }, $this->answer_details->toArray())
        ];
    }
}
