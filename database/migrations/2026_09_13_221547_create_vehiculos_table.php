<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('patente', 10)->unique();
            $table->integer('capacidad');
            $table->enum('estado', ['operativo', 'en_mantencion', 'fuera_de_servicio'])->default('operativo');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vehiculos');
    }
};