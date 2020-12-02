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
    	//Arte pertence a apenas um Usuário
    	return $this->belongsTo(User::class, 'user', 'id');
    }

    public function art()
    {
    	//Arte pertence a apenas um Usuário
    	return $this->belongsTo(Art::class, 'art', 'id');
    }
}
