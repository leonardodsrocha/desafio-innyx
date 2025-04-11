<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'preco' => number_format($this->preco, 2, '.', ','),
            'dt_validade' => Carbon::make($this->dt_validade)->format('d/m/Y'),
            'categoria' => new CategoriaResource($this->categoria),
            'preco' => number_format($this->preco, 2, '.', ','),
            'imagem_url' => asset($this->imagem)
        ];
    }
}
