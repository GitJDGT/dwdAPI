<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            // Se establece una estructura de datos falsos para pruebas
            
            'user_id' => rand(1, 10),

            // Es posible generar ejemplos de titulos mas consistentes usando words(),
            // ya que sentence creara oraciones con diferentes longitudes, sin embargo
            // siendo estos datos de ejemplo, podemos elegir lo que nos parezca mejor.
            'title' => $this -> faker -> words(5, true),

            'slug' => $this -> faker -> slug,

            // Generación de fechas futuras: Si deseamos que las citas se programen para el futuro, podríamos ajustar el código para generar fechas futuras. 
            // Por ejemplo, podríamos usar el método dateTimeBetween para obtener una fecha y hora entre ahora y un cierto número de días en el futuro.
            'scheduled_for' => $this -> faker -> dateTimeBetween('now', '+30 days'),
        ];
    }
}
