<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    use HasFactory;

    protected $table = 'user_follows';

    public function userFollowing()
    {
    	return $this->belongsTo(User::class, 'user_following', 'id');
    }

    public function userFollowed()
    {
    	return $this->belongsTo(User::class, 'user_followed', 'id');
    }
}
