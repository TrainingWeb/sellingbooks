<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'id', 'name', 'slug', 'description', 'id_group'
    ];

    public function books()
    {
        return $this->hasMany('App\Book', 'id_category');
    }
    public function group()
    {
        return $this->belongsTo('App\Group', 'id_group');
    }
}
