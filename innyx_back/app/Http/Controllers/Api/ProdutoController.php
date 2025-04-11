<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProdutoRequest;
use Illuminate\Http\Request;
use App\Models\Produto;
use App\Http\Resources\ProdutoResource;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public readonly Produto $produto;

    public function __construct()
    {
        $this->produto = new Produto();
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $page = $request->query('page', 1);
        $nome = $request->query('nome');
        $descricao = $request->query('descricao');
        $categoria = $request->query('categoria');

        $query = $this->produto->query();

        if ($nome) {
            $query->where('nome', 'like', '%' . $nome . '%');
        }

        if ($descricao) {
            $query->where('descricao', 'like', '%' . $descricao . '%');
        }

        if ($categoria) {
            $query->whereHas('categoria', function ($queryCategoria) use ($categoria) {
                $queryCategoria->where('nome', 'like', '%' . $categoria . '%');
            });
        }

        $produtos = $query->paginate($perPage, ['*'], 'page', $page);
        return ProdutoResource::collection($produtos);
    }

    public function store(StoreUpdateProdutoRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = uniqid() . '.' . $imagem->getClientOriginalExtension();
            $nomeImagem;
            $imagem->move('imagens', $nomeImagem);
            $data['imagem'] = 'api/imagens/' . $nomeImagem;
        }
        $produto = $this->produto->create($data);

        return new ProdutoResource($produto);
    }

    public function update(StoreUpdateProdutoRequest $request, string $id)
    {
        $produto = $this->produto->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = uniqid() . '.' . $imagem->getClientOriginalExtension();
            $imagem->move('imagens', $nomeImagem);
            $data['imagem'] = 'imagens/' . $nomeImagem;
        }

        $produto->update($data);

        return new ProdutoResource($produto);
    }

    public function show(string $id)
    {
        $produto = $this->produto->findOrFail($id);

        return new ProdutoResource($produto);
    }

    public function destroy(string $id)
    {
        $this->produto->findOrFail($id)->delete();
        return response(null, 204);
    }
}
