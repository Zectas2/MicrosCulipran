<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tarifas_tramos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->string('punto_origen');
            $table->string('punto_destino');
            $table->integer('precio');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tarifas_tramos');
    }
};