<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Exam extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['subject_id', 'name','user_id','low','medium','high','minutes'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function options(): HasManyThrough
    {
        return $this->hasManyThrough(Option::class, Question::class);
    }
}
