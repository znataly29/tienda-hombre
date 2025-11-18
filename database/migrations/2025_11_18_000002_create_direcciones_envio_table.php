<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direcciones_envio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre_direccion')->default('Mi dirección'); // Ej: "Casa", "Trabajo"
            $table->string('calle');
            $table->string('numero');
            $table->string('apartamento')->nullable();
            $table->string('ciudad');
            $table->string('departamento'); // Estado/Provincia
            $table->string('codigo_postal');
            $table->string('telefono')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direcciones_envio');
    }
};
