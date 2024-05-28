<?php
// app/Http/Controllers/DatosController.php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class DatosController extends Controller
{
    public function index(Request $request)
    {
        // Obtén los datos de la base de datos utilizando el modelo Usuario
        $usuarios = Usuario::all();

        // Devuelve los datos como respuesta JSON
        return response()->json($usuarios);
    }
}
