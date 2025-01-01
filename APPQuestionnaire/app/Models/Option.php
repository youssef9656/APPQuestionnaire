<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_question',
        'text_option',
        'text_associé',
        'question_type',
        'ordre_question',
    ];

    // Relation avec les questions
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question', 'id_question');
    }
}
