<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Añadimos los campos permitiendo nulos temporalmente por si hay usuarios antiguos
            $table->string('rut', 20)->nullable()->after('rol');
            $table->integer('edad')->nullable()->after('rut');
            $table->string('telefono', 20)->nullable()->after('edad');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['rut', 'edad', 'telefono']);
        });
    }
};
