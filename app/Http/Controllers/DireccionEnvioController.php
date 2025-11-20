<?php

namespace App\Http\Controllers;

use App\Models\DireccionEnvio;
use Illuminate\Http\Request;

class DireccionEnvioController extends Controller
{
    /**
     * Guardar una nueva dirección
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_direccion' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:50',
            'apartamento' => 'nullable|string|max:50',
            'ciudad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
        ]);

        // Si es la primera dirección, marcarla como principal
        $esLaPrimera = DireccionEnvio::where('usuario_id', auth()->id())->count() === 0;

        DireccionEnvio::create([
            'usuario_id' => auth()->id(),
            ...$validated,
            'es_principal' => $esLaPrimera,
        ]);

        return back()->with('success', 'Dirección agregada exitosamente');
    }

    /**
     * Actualizar dirección
     */
    public function update(Request $request, DireccionEnvio $direccion)
    {
        // Verificar que la dirección pertenece al usuario
        if ($direccion->usuario_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre_direccion' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:50',
            'apartamento' => 'nullable|string|max:50',
            'ciudad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
        ]);

        $direccion->update($validated);

        return back()->with('success', 'Dirección actualizada exitosamente');
    }

    /**
     * Marcar como principal
     */
    public function marcarPrincipal(DireccionEnvio $direccion)
    {
        // Verificar que la dirección pertenece al usuario
        if ($direccion->usuario_id !== auth()->id()) {
            abort(403);
        }

        // Quitar principal de todas las direcciones del usuario
        DireccionEnvio::where('usuario_id', auth()->id())
            ->update(['es_principal' => false]);

        // Marcar como principal
        $direccion->update(['es_principal' => true]);

        return back()->with('success', 'Dirección principal actualizada');
    }

    /**
     * Eliminar dirección
     */
    public function destroy(DireccionEnvio $direccion)
    {
        // Verificar que la dirección pertenece al usuario
        if ($direccion->usuario_id !== auth()->id()) {
            abort(403);
        }

        $direccion->delete();

        // Si era principal, asignar otra como principal
        if (DireccionEnvio::where('usuario_id', auth()->id())->count() > 0) {
            $nueva = DireccionEnvio::where('usuario_id', auth()->id())->first();
            $nueva->update(['es_principal' => true]);
        }

        return back()->with('success', 'Dirección eliminada exitosamente');
    }
}
