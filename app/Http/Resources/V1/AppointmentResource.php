<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Se colocan en este RESOURCE unos datos en forma de Arreglo con clave-valor (Array -> Key-Value)
        // los nombres de los datos se asignan segun diseño y los valores que se les asignan provienen de
        // los campos de las migraciones y/o en este caso los Accesors que creamos

        return 
        [
            'id' => $this -> id,
            'title' => $this -> title,
            'slug' => $this -> slug,
            'scheduled_for' => $this -> scheduled_for,
            'published_at' => $this -> published_at,
            'author' => 
            [
                'name' => $this -> user -> name,
                'email' => $this -> user -> email,
                'user_id' => $this -> user -> id
            ]
        ];
    }
}
