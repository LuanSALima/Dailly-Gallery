<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtChange extends Model
{
    use HasFactory;

    protected $table = 'art_changes';

    public function author()
    {
    	return $this->hasOneThrough(Art::class, User::class);
    }

    public function art()
    {
        return $this->belongsTo(Art::class, 'art', 'id');
    }
}
