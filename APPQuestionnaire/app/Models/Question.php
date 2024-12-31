<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_question';
    protected $fillable = ['id_test', 'text_question', 'type_question', 'ordre_question'];
    public $timestamps = false;

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function questionCourtes()
    {
        return $this->hasMany(QuestionCourte::class, 'id_question');
    }

    public function subQuestions()
    {
        return $this->hasMany(QuestionCourte::class, 'id_question', 'id_question');
    }
}
