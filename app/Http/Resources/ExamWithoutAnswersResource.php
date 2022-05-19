<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamWithoutAnswersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'examen_id' => $this->id,
            'minutes' => $this->minutes,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'subject_id' => $this->subject_id,
            'low' => $this->low,
            'medium' => $this->medium,
            'high' => $this->high,
            'questions' => array_map(function($question) {
                return [
                    'id' => $question['id'],
                    'number' => $question['number'],
                    'level' => $question['level'],
                    'exam_id' => $question['exam_id'],
                    'question' => $question['question'],
                    'options' =>  array_map(function($option) use ($question) {
                        if ($question['id'] === $option['question_id']) {
                            return [
                                'id' => $option['id'],
                                'question_id' => $option['question_id'],
                                'option' => $option['option'],
                                'number' => $option['number'],
                            ];
                        }
                    }, array_filter($this->options->toArray(), function($option) use ($question) {
                    return $question['id'] === $option['question_id'];
                }))
                ];
            },$this->questions->toArray())
        ];
    }
}
