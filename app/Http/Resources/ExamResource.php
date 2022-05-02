<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
         'name' => $this->name,
         'user_id' => $this->user_id,
         'subject_id' => $this->subject_id,
         'questions' => array_map(function($question) {
             return [
                 'id' => $question['id'],
                 'number' => $question['number'],
                 'level' => $question['level'],
                 'exam_id' => $question['exam_id'],
                 'answer' => 1, //todo se neceta enviar el valor 1 , 2 0 3 para determnar la respuesta en el formulario
                 //lo puedes tomar desde el request llega en int
                 'question' => $question['question'],
                 'options' => array_filter($this->options->toArray(), function($option) use ($question) {
                     return $question['id'] === $option['question_id'];
                 })
             ];
         },$this->questions->toArray())
       ];

    }
}
