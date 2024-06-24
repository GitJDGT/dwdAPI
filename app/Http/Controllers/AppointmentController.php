<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class AppointmentController extends Controller
{
    // Configuramos el controlador que se disparara con la ruta WEB de prueba.

    public function index()
    {
        return view('index', ['appointments' => Appointment::latest() -> paginate()]);
    }

}
