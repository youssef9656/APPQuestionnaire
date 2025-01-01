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
        'is_correct' // Exemple de colonnes
    ];

    public $timestamps = false;

    // Relation avec options_choix_obligatoire
    public function optionsChoixObligatoire()
    {
        return $this->hasMany(OptionChoixObligatoire::class, 'id_option', 'id_option');
    }
}
