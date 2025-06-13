<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

use App\Http\Resources\V1\AppointmentResource;
use App\Http\Resources\V1\AppointmentCollection;

use Illuminate\Support\Str;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * En esta funcion retornamos una coleccion de recursos (Appointments)
     * filtrados por el identificador del usuario.
     */
    public function index()
    {
        // Esta sentencia utiliza nuestro archivo AppointmentCollection para darle formato a la consulta del INDEX
        // es decir, que esta setencia aplica formato a la coleccion de recursos que mostraremos:
            
        return new AppointmentCollection(Appointment::query() -> where('user_id', Auth::user() -> id) -> latest() -> paginate(9));

        // Si quisieramos mostrar todas las citas usariamos algo como: return new AppointmentCollection(Appointment::latest() -> paginate(9));
            
        // La ruta para acceder a este metodo es GET => "api/v1/appointments".
    }

    /**
     * Esto registra una nueva cita en el sistema.
     */
    public function store(Request $request)
    {
        // Esto valida la solicitud para asegurarse de que los campos necesarios para crear una cita esten presentes
        // y tengan el formato correcto, luego se crea una cita basandose en el modelo APPOINTMENT y los datos  proporcionados
        // en la peticion, finalmente se devuelve la cita recien creada con el mensaje de estado 201 (Creado).
        // La ruta para acceder a este metodo es POST => "api/v1/appointments".

        // Para saber mas sobre las reglas de validacion: https://laravel.com/docs/10.x/validation#available-validation-rules.

        $request -> validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'scheduled_for' => 'required|date',
        ]);

        // Usando la libreria CARBON le damos manejo a las horas y fechas.
        // A continuacion obtenemos la fecha y hora programada de la solicitud:

        $scheduledFor = Carbon::parse($request -> input('scheduled_for'));

        // Con la siguiente validacion comprobamos que la cita tenga una duracion de 1 hora exacta.

        if($scheduledFor -> diffInMinutes($scheduledFor -> copy() -> addHour()) !== 60 )
        {
            return response() -> json(['message' => 'Appointment duration must be 1 hour.' ], 400);
        }

        // Validamos que la fecha corresponda a un dia de la semana (Lunes a Viernes) y que la hora corresponda a horario de oficina,
        // es decir, de 9 AM a 6 PM:

        if($scheduledFor -> isWeekday())
        {
            if($scheduledFor -> hour >= 9 && $scheduledFor -> hour <= 18)
            {
                // Validamos que no haya mas citas que se crucen en el rango de la cita actual, ya que solo debe permitirse programar 1 cita por hora, es decir,
                // que si hay una cita programada para las 6 PM de hoy, esta reserva el tiempo desde las 6 PM hasta las 7 PM y ninguna otra cita debe poder
                // registrarse en ese rango horario del mismo dia.

                $endOfAppointment = $scheduledFor -> copy() -> addHour();
                $beforeAppointment = $scheduledFor -> copy() -> subHour();

                $existingAppointments = Appointment::where(function ($query) use ($scheduledFor, $endOfAppointment) 
                {
                    $query -> where('scheduled_for', '<=', $endOfAppointment) -> where('scheduled_for', '>=', $scheduledFor);

                })->orWhere(function ($query) use ($scheduledFor, $beforeAppointment) 
                {
                    $query -> where('scheduled_for', '<=', $scheduledFor) -> where('scheduled_for', '>=', $beforeAppointment);

                })->exists();

                if ($existingAppointments) 
                {
                    return response()->json(['message' => 'An appointment is already scheduled for this range of hours.'], 400);
                }

                // Aqui creamos un SLUG para la cita ya que es requerido, usando la clase STR
                // ofrecida por Laravel.
        
                $slug = Str::slug($request -> input('title')).'-'.time();
        
                // Asignamos el SLUG al campo que corresponde de la peticion.
        
                $request -> merge(['slug' => $slug]);
        
                // Creamos la cita con los datos de la peticion. EL ALL SE PUEDE SUSTITUIR POR VALIDATED
        
                $appointment = Appointment::create($request -> all());
        
                return response() -> json(['message' => 'Appointment Created', 'Appointment' => $appointment], 201);

            }
            else
            {
                return response() -> json(['message' => 'Appointments must be booked between 9 AM and 6 PM'], 400);
            }
        }
        else
        {
            return response() -> json(['message' => 'Appointments must be booked on weekdays (Monday through Friday).'], 400);
        }
    }

    /**
     * Esto retorna la consulta de una cita.
     */
    public function show(Appointment $appointment)
    {
        // Le damos formato al resultado de la consulta con el RESOURCE que creamos.
        // La ruta para acceder a este metodo es GET => "api/v1/appointments/{appointment}" en esta ultima parte va el ID o identificador de la cita a visualizar.

        return new AppointmentResource($appointment);
    }

    /**
     * Esto actualiza una cita que ya este registrada en el sistema.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Se valida la solicitud, pero con algunas reglas adicionales. Se utiliza 'sometmes' para indicar
        // que los campos son requeridos solo si estan presentes en la solicitud. Se actualiza la cita existente con los
        // datos proporcionados en la solicitud y se devuelve la cita actualizada con el codigo de respuesta 200 (ok).
        // La ruta para acceder a este metodo es PUT o PATCH => "api/v1/appointments/{appointment}" en esta ultima parte va el ID o identificador de la cita a modificar.

        $request -> validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'title' => 'sometimes|required|string',
            'scheduled_for' => 'sometimes|required|date'
        ]);

        // Usando la libreria CARBON le damos manejo a la fecha y hora en la cual se re-programara la cita.
        // Obtenemos la fecha y hora de la solicitud (Las que se quieren aplicar ahora) y validamos que cumplan los criterios: 
        // Citas solo de Lunes a Viernes en horario de 9 AM a 6 PM, de 1 hora exacta de duracion y maximo 1 cita por hora.

        $scheduledFor = Carbon::parse($request -> input('scheduled_for'));

        if($scheduledFor -> diffInMinutes($scheduledFor -> copy() -> addHour()) !== 60 )
        {
            return response() -> json(['message' => 'Appointment duration must be 1 hour.' ], 400);
        }

        if($scheduledFor -> isWeekday())
        {
            if($scheduledFor -> hour >= 9 && $scheduledFor -> hour <= 18)
            {
                // Validamos que el rango horario este libre.

                $endOfAppointment = $scheduledFor -> copy() -> addHour();
                $beforeAppointment = $scheduledFor -> copy() -> subHour();

                $existingAppointments = Appointment::where(function ($query) use ($scheduledFor, $endOfAppointment) 
                {
                    $query -> where('scheduled_for', '<=', $endOfAppointment) -> where('scheduled_for', '>=', $scheduledFor);

                })->orWhere(function ($query) use ($scheduledFor, $beforeAppointment) 
                {
                    $query -> where('scheduled_for', '<=', $scheduledFor) -> where('scheduled_for', '>=', $beforeAppointment);

                })->exists();

                if ($existingAppointments) 
                {
                    return response()->json(['message' => 'An appointment is already scheduled for this range of hours.'], 400);
                }

                // Aqui creamos un SLUG NUEVO para la cita, usando la clase STR
                // ofrecida por Laravel.
        
                $slug = Str::slug($request -> input('title'));
        
                // Asignamos el SLUG al campo que corresponde de la peticion y luego le pedimos a la cita ya existente que actualice los valores que nos interesan
                // segun los valores que le esta pasando la peticion.
        
                $request -> merge(['slug' => $slug]);
        
                $appointment -> update($request -> only(['user_id', 'title', 'scheduled_for', 'slug']));
        
                return response() -> json(['message' => 'Appointment updated', 'Appointment' => $appointment], 200);

            }
            else
            {
                return response() -> json(['message' => 'Appointments must be booked between 9 AM and 6 PM'], 400);
            }
        }
        else
        {
            return response() -> json(['message' => 'Appointments must be booked on weekdays (Monday through Friday).'], 400);
        }

    }

    /**
     * Mediante esta funcion eliminamos citas (Appointments), la ejecucion de este metodo debe retornar una respuesta de estado 204.
     */
    public function destroy(Appointment $appointment)
    {
        // El identificador de la cita que se eliminara se recibe como parametro, ya que el FRONT-END es quien se encarga de enviar tal dato
        // para la API lo importante es recibir una cita y ejecutar su eliminacion, esto se puede hacer por la ruta de la cita la cual termina con un ID
        // por lo que si queremos eliminar la cita 86 accedemos a la ruta http://127.0.0.1:8000/api/v1/appointments/86 usando el metodo DELETE en Postman, por ejemplo.
        // La ruta para acceder a este metodo es DELETE => "api/v1/appointments/{appointment}" en esta ultima parte va el ID o identificador de la cita a eliminar.

        $appointment -> delete();

        return response() -> json([
            'message' => 'Deleted Successfully'
        ], 204);
    }
}