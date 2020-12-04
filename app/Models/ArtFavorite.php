<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtFavorite extends Model
{
    use HasFactory;

    protected $table = 'art_favorites';

    public function user()
    {
    	//Favorito na arte pertence a apenas um Usuário
    	return $this->belongsTo(User::class, 'user', 'id');
    }

    public function art()
    {
    	//Favorito na arte pertence a apenas uma Arte
    	return $this->belongsTo(Art::class, 'art', 'id');
    }
}
