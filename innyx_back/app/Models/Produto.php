<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'dt_validade',
        'categoria_id',
        'imagem',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
