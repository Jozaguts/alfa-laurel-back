<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['number','question','level','exam_id','answer'];

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function options(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Option::class);
    }
    public static function updateOrCreateByExamId(int $exam_id) {

        array_map(function($question) use ($exam_id){
            $currentQuestion =  static::updateOrCreate(
                ['number' => $question['number'], 'exam_id' => $exam_id],
                [
                    'question'=> $question['question'],
                    'answer'=> (int)$question['answer'],
                    'level'=> $question['level'],
                ]
            );
            Option::updateOrCreateAllOptions($question['options'], $currentQuestion['id']);
        },request()['questions']);
    }

}
