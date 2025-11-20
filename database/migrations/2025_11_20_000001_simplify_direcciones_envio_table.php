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
            // Eliminar columnas que no necesitamos
            if (Schema::hasColumn('direcciones_envio', 'nombre_direccion')) {
                $table->dropColumn('nombre_direccion');
            }
            if (Schema::hasColumn('direcciones_envio', 'calle')) {
                $table->dropColumn('calle');
            }
            if (Schema::hasColumn('direcciones_envio', 'numero')) {
                $table->dropColumn('numero');
            }
            if (Schema::hasColumn('direcciones_envio', 'apartamento')) {
                $table->dropColumn('apartamento');
            }
            if (Schema::hasColumn('direcciones_envio', 'ciudad')) {
                $table->dropColumn('ciudad');
            }
            if (Schema::hasColumn('direcciones_envio', 'departamento')) {
                $table->dropColumn('departamento');
            }
            if (Schema::hasColumn('direcciones_envio', 'codigo_postal')) {
                $table->dropColumn('codigo_postal');
            }
            if (Schema::hasColumn('direcciones_envio', 'telefono')) {
                $table->dropColumn('telefono');
            }

            // Agregar nuevas columnas
            if (!Schema::hasColumn('direcciones_envio', 'direccion')) {
                $table->string('direccion')->after('usuario_id');
            }
            if (!Schema::hasColumn('direcciones_envio', 'barrio')) {
                $table->string('barrio')->after('direccion');
            }
            if (!Schema::hasColumn('direcciones_envio', 'tipo_inmueble')) {
                $table->enum('tipo_inmueble', ['casa', 'apartamento', 'oficina', 'otro'])->after('barrio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones_envio', function (Blueprint $table) {
            // Revertir cambios
            if (Schema::hasColumn('direcciones_envio', 'direccion')) {
                $table->dropColumn('direccion');
            }
            if (Schema::hasColumn('direcciones_envio', 'barrio')) {
                $table->dropColumn('barrio');
            }
            if (Schema::hasColumn('direcciones_envio', 'tipo_inmueble')) {
                $table->dropColumn('tipo_inmueble');
            }

            // Restaurar columnas originales
            $table->string('nombre_direccion');
            $table->string('calle');
            $table->string('numero', 50);
            $table->string('apartamento', 50)->nullable();
            $table->string('ciudad');
            $table->string('departamento');
            $table->string('codigo_postal', 20);
            $table->string('telefono', 20)->nullable();
        });
    }
};
