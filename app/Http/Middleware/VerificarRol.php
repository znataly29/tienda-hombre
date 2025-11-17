<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * Maneja la solicitud y verifica si el usuario tiene alguno de los roles permitidos.
     * Pasar roles separados por '|' en la definición de la ruta: 'rol:admin|cliente'
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (! $request->user()) {
            if (\Illuminate\Support\Facades\Route::has('login')) {
                return redirect()->route('login');
            }
            return redirect('/catalogo');
        }

        $rolesPermitidos = explode('|', $roles);
        $rolUsuario = optional($request->user()->rol)->nombre;

        if (! in_array($rolUsuario, $rolesPermitidos)) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
