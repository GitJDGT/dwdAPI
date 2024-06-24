<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://cdn.tailwindcss.com"></script>

        <title>Laravel</title>
    </head>

    <body class="bg-neutral-900 text-white">

        {{-- SOBRE LA INFORMACION:

            Cada atributo que llamamos aqui en el FOREACH es un atributo intrinseco (Es un campo de la Base de Datos) o un atributo del modelo
            ,el modelo basicamente es un aquetipo que utilizamos para crear cada una de las instancias de nuestro objeto, creo... entonces las factorys
            utilizan la migracion y el modelo para crear los datos de prueba? 

            En laravel hay Mutators y Accessors, los Mutators cambian el valor antes de que sea almacenado en base de datos, con los Accessor te puedes hacer "campos virtuales" que 
            como tal no estan almacenados en base de datos, pero que son accesibles como si lo estuvieran, este que usas es un Accessor (published_at y scheduled) y 
            por eso puedes acceder a esos campos que no está definidos en la migracion.
            
        --}}

        {{-- SOBRE LA VISTA Y ESTILOS:

            Creamos un grupo de DIVs con un contenedor y una grilla dentro del contenedor, la grilla contiene dentro unas filas y estas se dividen entre el calendario y un FOREACH
            que sera lo que traiga nuestros objetos para su visualizacion.

        --}}

        <div class="container mx-auto p-4">

            <div class="grid grid-cols-3 gap-5 my-20">

                @foreach ($appointments as $appointment)
        
                    <div class="bg-blue-600 hover:bg-blue-800 border border-blue-600 hover:border-white rounded p-4">
        
                        <h2 class="text-lg font-semibold">{{ $appointment -> title }}</h2>
                        
                        <p class="text-md">Created at: {{ $appointment -> published_at }}</p>

                        <p class="text-md">Scheduled for: {{ $appointment -> scheduled }}</p>
        
                    </div>
                    
                @endforeach

            </div>

            {{ $appointments -> links() }}

        </div>
        
    </body>
</html>
