<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Art extends Model
{
    use HasFactory;

    protected $table = 'art';

    //Função para retornar o autor da arte
    public function author()
    {
    	//Arte pertence a apenas um Usuário
    	return $this->belongsTo(User::class, 'author', 'id');
    }

    //Função para retornar os likes desta arte
    public function likes()
    {
    	//Arte possui varios likes
        return $this->hasMany(ArtLike::class, 'art', 'id');
    }

    //Função para retornar os favoritos desta arte
    public function favorites()
    {
        //Arte possui varios favoritos
        return $this->hasMany(ArtFavorite::class, 'art', 'id');
    }

    //Função para retornar os comentários desta arte
    public function comments()
    {
        //Arte possui varios comentários
        return $this->hasMany(ArtComment::class, 'art', 'id');
    }

    public function artChange()
    {
        return $this->hasOne(ArtChange::class, 'art', 'id');
    }
}
