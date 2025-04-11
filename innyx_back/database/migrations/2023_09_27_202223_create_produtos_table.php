<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50)->unique();
            $table->string('descricao', 200)->nullable();
            $table->double('preco', 8, 2);
            $table->date('dt_validade');
            $table->unsignedBigInteger('categoria_id')->index();
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->text('imagem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};
