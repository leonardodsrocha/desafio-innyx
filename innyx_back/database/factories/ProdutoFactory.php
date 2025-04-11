<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition()
    {
        $categorias = Categoria::pluck('id')->toArray();

        return [
            'nome' => $this->faker->name,
            'descricao' => $this->faker->sentence,
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'dt_validade' => $this->faker->dateTimeBetween('now', '+1 year'),
            'categoria_id' => $this->faker->randomElement($categorias),
            'imagem' => function () {
                $faker = \Faker\Factory::create();
                $filename = $faker->image('public/imagens', 400, 300, null, false);
                $path = 'imagens/' . $filename;
                Storage::disk('public')->put($path, file_get_contents('public/imagens/' . $filename));
                return 'api/' . $path;
            },
        ];
    }
}
