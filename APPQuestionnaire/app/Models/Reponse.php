<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;

    protected $table = 'reponses'; // Table associée
    protected $primaryKey = 'id_reponse'; // Clé primaire
    public $timestamps = false; // Si vous n'utilisez pas les timestamps

    protected $fillable = [
        'id_user', 'id_test', 'id_question', 'reponse', 'id_option_reponse', 'type_reponse','id_option_Ob'
    ];

    // Relation avec le modèle `User`
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relation avec le modèle `Test`
    public function test()
    {
        return $this->belongsTo(Test::class, 'id_test');
    }

    // Relation avec le modèle `Question`
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }

    // Relation avec le modèle `Option` (pour les réponses à choix multiples)
    public function option()
    {
        return $this->belongsTo(Option::class, 'id_option_reponse');
    }
}
