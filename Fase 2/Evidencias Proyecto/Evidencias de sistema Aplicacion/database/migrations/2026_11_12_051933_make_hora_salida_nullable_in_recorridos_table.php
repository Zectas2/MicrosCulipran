<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Le quitamos el candado de "NOT NULL" a la columna en PostgreSQL
        DB::statement('ALTER TABLE recorridos ALTER COLUMN hora_salida DROP NOT NULL');
    }

    public function down(): void
    {
        // Revertir en caso de emergencia
        DB::statement('ALTER TABLE recorridos ALTER COLUMN hora_salida SET NOT NULL');
    }
};
