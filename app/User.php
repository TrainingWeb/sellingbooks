<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password' ,'avatar', 'age', 'address', 'phone', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order', 'id_user');
    }
    public function books()
    {
        return $this->belongsToMany('App\Book', 'favorite_books', 'id_user', 'id_book');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
