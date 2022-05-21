<?php

namespace App\Http\Resources;

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
            'exam_id' => $this->exam_id,            
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
                    'number_answer' => $detail['number_answer']
                ];

            }, $this->answer_details->toArray())
        ];
    }
}
