<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_option'; // Remplacez par la clé primaire réelle

    protected $fillable = [
        'id_question',
        'text_option',
        'text_associé',
        'question_type',
        'ordre_question',
    ];

    public $timestamps = false;

    // Relation avec options_choix_obligatoire
    public function optionsChoixObligatoire(){
        return $this->hasMany(OptionChoixObligatoire::class, 'id_option', 'id_option');
    }
    // Relation avec les questions
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question', 'id_question');
    }
    public function associatedQuestion()
    {
        return $this->belongsTo(Question::class, 'text_associé', 'text_question');
    }


}
