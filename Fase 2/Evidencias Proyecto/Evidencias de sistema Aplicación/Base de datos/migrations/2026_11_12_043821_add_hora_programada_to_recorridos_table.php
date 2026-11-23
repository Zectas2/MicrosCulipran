<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('recorridos', function (Blueprint $table) {
            $table->timestamp('hora_programada')->nullable()->after('conductor_id');
        });
        // Copiamos la hora_salida actual a la hora_programada para no romper los registros históricos
        DB::statement('UPDATE recorridos SET hora_programada = hora_salida');
    }

    public function down(): void
    {
        Schema::table('recorridos', function (Blueprint $table) {
            $table->dropColumn('hora_programada');
        });
    }
};
