<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // datos del cliente, compras recientes, recomendaciones, etc.
        return view('cliente.dashboard', compact('user'));
    }
}
