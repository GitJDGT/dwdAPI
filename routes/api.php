<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AppointmentController as APC1;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Al utilizar un sistema de autenticacion como SANCTUM debemos migrar una tabla que contendra los Tokens que validaran los inicios de sesion
// y tambien definir que la conexion que se establecera con estas rutas es por medio de API, y no Web, si no hacemos este proceso en Postman o
// el software que intente acceder a la informacion tendra un error de tipo 500 (Error del servidor) porque intentara conectarse al Login y tal no existe.

Route::apiResource('v1/appointments', APC1::class)
//-> middleware('auth:sanctum')
;


// Hacemos una ruta que se redireccione a un nuevo controlador para gestionar el tema de la autenticacion e inicio de sesion en la API, este no esta
// protegido por SANCTUM ya que cualquier usuario invitado (Guest Session) debe poder visualizar el inicio de sesion (Log IN). Para esto invocamos el uso
// de Tokens para las sesiones en nuestro MODELO de USUARIO.

Route::post('login', [\App\Http\Controllers\Api\LoginController::class, 'login']) -> name('login');

Route::post('register', [\App\Http\Controllers\Api\RegisterController::class, 'register']) -> name('register');


// Ruta para el cierre de sesion, esta si se encuentra protegida, puesto que solo los usuarios con una sesion valida deben poder visualizar esta ruta.

Route::middleware('auth:sanctum') -> post ('logout', [\App\Http\Controllers\Api\LoginController::class, 'logout']) -> name('logout');
