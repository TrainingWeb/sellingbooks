<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'id', 'name', 'slug',
    ];

    public function categories()
    {
        return $this->hasMany('App\Category', 'id_group');
    }
}
