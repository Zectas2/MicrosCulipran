<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rutas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre_ruta');
            $table->string('origen');
            $table->string('destino');
            $table->jsonb('trayecto_coordenadas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rutas');
    }
};