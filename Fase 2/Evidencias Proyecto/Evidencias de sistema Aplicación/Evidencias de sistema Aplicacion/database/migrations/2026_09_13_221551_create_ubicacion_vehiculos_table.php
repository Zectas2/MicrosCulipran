<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ubicaciones_vehiculos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('recorrido_id')->constrained('recorridos')->cascadeOnDelete();
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->timestamp('registrado_en')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ubicaciones_vehiculos');
    }
};