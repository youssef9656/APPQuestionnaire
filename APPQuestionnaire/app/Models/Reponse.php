<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;

    protected $table = 'reponses';

    protected $fillable = [
        'id_user',
        'id_test',
        'id_question',
        'reponse',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class, 'id_test');
    }
}
