<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Estas dos inclusiones se hacen para utilizar este controlador con el modelo de usuario
// y la clase ofrecida por LARAVEL para gestionar los inicios de sesion

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    // Funcion que ejecutara la tarea de iniciar sesion, se apoya de una funcion que valida si los datos
    // de la sesion son correctos (Validos) o incorrectos (Invalidos)

    public function login(Request $request)
    {
        $this -> validateLogin($request);

        // LOGIN TRUE (Valido)

        if (Auth::attempt($request -> only('email', 'password')))
        {
            return response() -> json([
                'token' => $request -> user() -> createToken($request -> name) -> plainTextToken,
                'message' => 'Login Success'
            ]);
        }

        // LOGIN FALSE (Invalido)

        return response() -> json([
            'message' => 'Unauthorized Login'
        ], 401);
    }

    // Esta funcion comprobara la validez de una sesion en base a unas condiciones que debe cumplir
    // dicha sesion, por ejemplo, tener un email, password y nombre, ya que los 3 son requeridos para iniciar
    // sesion de forma satisfactoria.

    public function validateLogin(Request $request)
    {
        return $request -> validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required'
        ]);
    }

}
