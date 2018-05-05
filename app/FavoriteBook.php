<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteBook extends Model
{
    protected $fillable = [
        'id', 'id_user', 'id_book'
    ];
}
