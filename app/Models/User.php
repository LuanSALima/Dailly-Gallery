<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//Adicionado
//use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Função para retornar as artes deste author
    public function arts()
    {
        //Usuário possui varias Artes
        return $this->hasMany(Art::class, 'author', 'id');
    }

    //Função para retornar os likes deste user
    public function likes()
    {
        //Usuário cadastra varios likes
        return $this->hasMany(ArtLike::class, 'user', 'id');
    }

    //Função para retornar os favoritos deste user
    public function favorites()
    {
        //Usuário cadastra varios favoritos
        return $this->hasMany(ArtFavorite::class, 'user', 'id');
    }

    //Função para retornar os comentários deste user
    public function comments()
    {
        //Usuário cadastra varios comentários
        return $this->hasMany(ArtComment::class, 'user', 'id');
    }
}
