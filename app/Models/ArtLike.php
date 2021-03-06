<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtLike extends Model
{
    use HasFactory;

    protected $table = 'art_likes';

    public function user()
    {
    	//Like na arte pertence a apenas um Usuário
    	return $this->belongsTo(User::class, 'user', 'id');
    }

    public function art()
    {
    	//Like na arte pertence a apenas uma Arte
    	return $this->belongsTo(Art::class, 'art', 'id');
    }
}
