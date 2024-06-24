<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // id: Es la clave primaria de la tabla y se genera automáticamente.
        // user_id: Es una clave foránea que se asocia con la tabla de usuarios. Esto indica que cada cita está relacionada con un usuario específico.
        // title: Es un campo para almacenar el título de la cita.
        // slug: Es un campo único que es utilizando para crear URLs amigables.
        // timestamp: Es un campo de formato DATETIME que almacena la hora y fecha para la cual se programa la cita.
        // timestamps: Crea automáticamente las columnas created_at y updated_at para registrar la fecha y hora de creación y actualización de cada registro.

        Schema::create('appointments', function (Blueprint $table) {

            $table -> id();
            $table -> unsignedBigInteger('user_id');
            $table -> string('title');
            $table -> string('slug') -> unique();
            $table -> timestamp('scheduled_for');
            $table -> timestamps();

            // Este código establece una clave foránea en la columna user_id, que hace referencia a la columna id de la tabla users. La opción onDelete('cascade') indica que si un usuario se elimina, 
            // todas las citas asociadas a ese usuario también se eliminarán automáticamente.
            $table -> foreign('user_id') -> references('id') -> on('users') -> onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
