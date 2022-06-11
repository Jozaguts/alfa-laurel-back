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
    public static function updateOrCreateAllOptions($allOptions)
    {
        array_map(function($options){
            array_map(function($option){
                static::updateOrCreate(
                    ['number' => $option['number'], 'question_id' => $option['question_id']],
                    [
                        'is_answer' => $option['is_answer'],
                        'option'=> $option['option'],
                    ]
                );
            },$options);
        },$allOptions);
    }
}
