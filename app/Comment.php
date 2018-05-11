<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'id', 'content', 'id_user', 'id_book'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function book()
    {
        return $this->belongsTo('App\Book', 'id_book');
    }
    
    
}
