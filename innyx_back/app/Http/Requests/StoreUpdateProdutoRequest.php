<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'max:50',
                Rule::unique('produtos')->ignore($this->id)
            ],
            'descricao' => 'max:200',
            'preco' => 'required|max:200|gt:0',
            'dt_validade' => 'required|date|after:today',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
