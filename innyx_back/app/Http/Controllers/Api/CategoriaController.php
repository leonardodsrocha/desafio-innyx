<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategoriaRequest;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Http\Resources\CategoriaResource;

class CategoriaController extends Controller
{
    public readonly Categoria $categoria;

    public function __construct()
    {
        $this->categoria = new Categoria();
    }

    public function all()
    {
        return CategoriaResource::collection($this->categoria->all());
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $page = $request->query('page', 1);
        $nome = $request->query('nome');

        $query = $this->categoria->query();

        if ($nome) {
            $query->where('nome', 'like', '%' . $nome . '%');
        }

        $categorias = $query->paginate($perPage, ['*'], 'page', $page);
        return CategoriaResource::collection($categorias);
    }

    public function store(StoreUpdateCategoriaRequest $request)
    {
        $categoria = $this->categoria->create($request->validated());

        return new CategoriaResource($categoria);
    }

    public function update(StoreUpdateCategoriaRequest $request, string $id)
    {
        $categoria = $this->categoria->findOrFail($id);
        $categoria->update($request->validated());

        return new CategoriaResource($categoria);
    }

    public function show(string $id)
    {
        $categoria = $this->categoria->findOrFail($id);

        return new CategoriaResource($categoria);
    }

    public function destroy(string $id)
    {
        $this->categoria->findOrFail($id)->delete();
        return response(null, 204);
    }
}
