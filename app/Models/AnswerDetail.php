<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'answer_id',
        'question_id',
        'number',
        'question',
        'option1',
        'option2',
        'option3',
        'answer',
        'answer_correct',
        'level'
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
