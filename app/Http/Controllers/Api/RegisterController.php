<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        
        $this -> validateRegister($request);

        // creamos el usuario
        $user = User::create([
            'name' => $request -> input('name'),
            'email' => $request -> input('email'),
            'password' => Hash::make($request -> input('password')),
        ]);

        return response() -> json([
            'message' => 'Register Success',
            'user' => $user,
        ], 201);
        
        // En caso de error lanzamos una excepcion
        if(!$user)
        {
            return response() -> json([

                'message' => 'Something went wrong',

            ], 422);
        }

    }

    public function validateRegister(Request $request)
    {
        return $request -> validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:4',
        ]);
    }
}
