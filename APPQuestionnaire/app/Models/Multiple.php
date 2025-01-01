<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multiple extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'multiple';

    // Clé primaire
    protected $primaryKey = 'id_multiple';

    // Indique que nous n'utilisons pas les timestamps si vous ne les avez pas dans votre table
    public $timestamps = false;

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'id_question',
        'text_question',
        'nombre_de',
        'nombre_a',
    ];

    // Relation avec le modèle `Question`
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question', 'id_question');
    }
}
