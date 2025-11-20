<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Actualizar teléfono del usuario
     */
    public function actualizarTelefono(Request $request)
    {
        $validated = $request->validate([
            'telefono' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Teléfono actualizado exitosamente');
    }
}
