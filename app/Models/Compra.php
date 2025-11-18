<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = ['usuario_id', 'numero_compra', 'monto_total', 'estado', 'detalles'];

    protected $casts = [
        'detalles' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot function para generar automáticamente el número de compra
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Obtener el último número de compra del usuario
            $ultimoNumero = Compra::where('usuario_id', $model->usuario_id)
                ->max('numero_compra') ?? 0;
            
            // Asignar el siguiente número secuencial
            $model->numero_compra = $ultimoNumero + 1;
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
