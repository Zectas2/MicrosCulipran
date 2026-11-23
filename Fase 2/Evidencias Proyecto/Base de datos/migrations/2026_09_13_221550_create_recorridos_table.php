<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recorridos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ruta_id')->constrained('rutas')->restrictOnDelete();
            $table->foreignUuid('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->foreignUuid('conductor_id')->constrained('usuarios')->restrictOnDelete();
            $table->timestamp('hora_salida');
            $table->enum('estado', ['programado', 'en_ruta', 'finalizado'])->default('programado');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('recorridos');
    }
};