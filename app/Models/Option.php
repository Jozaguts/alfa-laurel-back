<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Option extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['question_id', 'option', 'is_answer','number'];
    public function question(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Question::class);
    }
    public static function updateOrCreateAllOptions($options, $questionID)
    {
        array_map(function($option) use($questionID){
            static::updateOrCreate(
                ['number' => $option['number'], 'question_id' => $questionID],
                [
                    'is_answer' => $option['is_answer'],
                    'option'=> $option['option'],
                ]
            );
        },$options);
    }
}
