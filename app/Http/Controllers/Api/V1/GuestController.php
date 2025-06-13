<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AppointmentCollection;

class GuestController extends Controller
{
    /**
     * En esta funcion retornamos una coleccion de recursos (Appointments)
     * para una sesion de invitado (Guest Session).
     */
    public function guestIndex()
    {
        // Muestra toda la coleccion de appointments registrados sin importar el usuario.
        
        return new AppointmentCollection(Appointment::latest() -> paginate(9));
    }
}
