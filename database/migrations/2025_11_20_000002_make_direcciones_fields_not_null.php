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
        Schema::table('direcciones_envio', function (Blueprint $table) {
            // Cambiar las columnas de nullable a NOT NULL con valores por defecto
            $table->string('direccion')->nullable(false)->change();
            $table->string('barrio')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones_envio', function (Blueprint $table) {
            $table->string('direccion')->nullable()->change();
            $table->string('barrio')->nullable()->change();
        });
    }
};
