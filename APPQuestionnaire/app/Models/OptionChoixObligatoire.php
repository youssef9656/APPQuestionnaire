<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionChoixObligatoire extends Model
{
    use HasFactory;

    protected $table = 'options_choix_obligatoire';

    protected $primaryKey = 'id_option_choix_obligatoire';

    protected $fillable = [
        'id_option',
        'id_question',
        'question_text',
        'question_type',
    ];

    public $timestamps = true;

    // Relation avec le modèle Option
    public function option()
    {
        return $this->belongsTo(Option::class, 'id_option', 'id_option');
    }

    // Relation avec le modèle Question
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question', 'id_question');
    }
}
