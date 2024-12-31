<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCourte extends Model
{
    use HasFactory;
    protected $table = 'question_courte'; // Assurez-vous que le nom de la table est correct

    protected $primaryKey = 'id_question_courte';
    protected $fillable = ['id_question', 'text_question', 'type_question', 'ordre_question'];
    public $timestamps = false;


    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }
}
