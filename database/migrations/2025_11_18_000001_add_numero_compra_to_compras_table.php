<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->integer('numero_compra')->nullable()->after('usuario_id');
        });

        // Asignar números de compra secuenciales por usuario
        $compras = \DB::table('compras')->orderBy('usuario_id')->orderBy('created_at')->get();
        $contadores = [];
        
        foreach ($compras as $compra) {
            if (!isset($contadores[$compra->usuario_id])) {
                $contadores[$compra->usuario_id] = 1;
            } else {
                $contadores[$compra->usuario_id]++;
            }
            
            \DB::table('compras')
                ->where('id', $compra->id)
                ->update(['numero_compra' => $contadores[$compra->usuario_id]]);
        }
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn('numero_compra');
        });
    }
};
