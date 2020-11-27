<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libros extends Model
{
    protected $fillable = [
        "titulo",
        "numPaginas",
        "sinopsis"
    ];
}
