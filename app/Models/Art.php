<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Art extends Model
{
    use HasFactory;

    protected $table = 'arte';

    //Função para retornar o autor da arte
    public function author()
    {
    	//Arte pertence a apenas um Usuário
    	return $this->belongsTo(User::class, 'author', 'id');
    }
}
