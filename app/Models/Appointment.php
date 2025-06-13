<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Creamos un par de Accesors para tener campos virtuales a los que podamos acceder: published_at y scheduled

    public function getPublishedAtAttribute()
    {
        return $this -> created_at -> format('d/m/Y');
    }
    
    public function getScheduledAttribute()
    {
        return $this -> scheduled_for;
    }


    // Esta funcion hace que cada cita pertenezca a un usuario para poder llamar luego los datos de dicho usuario como AUTOR
    // en las consultas de cada cita.

    public function user()
    {
        return $this -> belongsTo(User::class);
    }

    /**
     * Atributos que se pueden asignar en masa.
     *
     * @var array<int, string>
     */

    // Aqui se deben asignar los atributos del modelo que son asignables en un formulario o
    // de lo contrario la funcion de registrar no funcionara. En caso del SLUG que tambien es requerido segun la migracion
    // que creamos para nuestra tabla, ese no es rellenable, se generara de forma automatica antes de registrar una cita y
    // se asignara al campo que corresponde, vease en la funcion STORE.

    protected $fillable = [
        'user_id',
        'title',
        'scheduled_for',
        'slug'
    ];
}
