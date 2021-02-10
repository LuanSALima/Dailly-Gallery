<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtChange extends Model
{
    use HasFactory;

    protected $table = 'art_changes';

    public function art()
    {
        return $this->belongsTo(Art::class, 'art', 'id');
    }
    
    public function author()
    {
        return User::where('id', Art::where('id', $this->art)->first()->author)->first();
    }
}
