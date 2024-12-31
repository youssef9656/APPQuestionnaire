<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = 'test';

    public $timestamps = false;
    protected $primaryKey = 'id_test'; // Spécifiez la clé primaire

    protected $fillable = [
        'nom_test',
        'duree_test',
        'active',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'id_test');
    }

}
