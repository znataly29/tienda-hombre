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
            // Agregar nuevas columnas primero
            if (!Schema::hasColumn('direcciones_envio', 'direccion')) {
                $table->string('direccion')->nullable()->after('usuario_id');
            }
            if (!Schema::hasColumn('direcciones_envio', 'barrio')) {
                $table->string('barrio')->nullable()->after('direccion');
            }
            if (!Schema::hasColumn('direcciones_envio', 'tipo_inmueble')) {
                $table->enum('tipo_inmueble', ['casa', 'apartamento', 'oficina', 'otro'])->default('apartamento')->after('barrio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones_envio', function (Blueprint $table) {
            if (Schema::hasColumn('direcciones_envio', 'direccion')) {
                $table->dropColumn('direccion');
            }
            if (Schema::hasColumn('direcciones_envio', 'barrio')) {
                $table->dropColumn('barrio');
            }
            if (Schema::hasColumn('direcciones_envio', 'tipo_inmueble')) {
                $table->dropColumn('tipo_inmueble');
            }
        });
    }
};
