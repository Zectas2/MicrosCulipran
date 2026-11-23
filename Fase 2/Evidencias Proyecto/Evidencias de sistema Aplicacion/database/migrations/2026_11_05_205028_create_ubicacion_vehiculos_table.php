<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicacion_vehiculos', function (Blueprint $table) {
            $table->id(); // El ID propio de esta tabla puede seguir siendo numérico

            // CORRECCIÓN: Usamos foreignUuid para enlazar correctamente con el ID de recorridos
            $table->foreignUuid('recorrido_id')->constrained('recorridos')->onDelete('cascade');

            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->decimal('velocidad', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicacion_vehiculos');
    }
};
