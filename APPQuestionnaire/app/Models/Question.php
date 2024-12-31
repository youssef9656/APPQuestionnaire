<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['id_test', 'text', 'type'];
    public $timestamps = false;


    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
