<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'id', 'name', 'slug', 'image', 'price', 'promotion_price', 'highlights', 'description', 'quantity', 'id_category', 'id_author', 'id_tag'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category', 'id_category');
    }
    public function author()
    {
        return $this->belongsTo('App\Author', 'id_author');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment', 'id_book');
    }
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'book_tags', 'id_book', 'id_tag');
    }
    public function users()
    {
        return $this->belongsToMany('App\User', 'favorite_books', 'id_book', 'id_user');
    }
    public function orderdetails()
    {
        return $this->hasMany('App\OrderDetail', 'id_book');
    }
    public function storage()
    {
        return $this->hasOne('App\Storage', 'id_book');
    }
}
