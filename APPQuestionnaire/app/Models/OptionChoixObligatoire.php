<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionChoixObligatoire extends Model
{
    use HasFactory;

    protected $table = 'options_choix_obligatoire';

    protected $fillable = [
        'id_option',
        'id_question',
        'question_text',
        'question_type',
    ];
}
