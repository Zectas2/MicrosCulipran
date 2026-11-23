<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('rut')->nullable()->after('email');
            $table->integer('edad')->nullable()->after('rut');
            $table->string('telefono')->nullable()->after('edad');
            $table->string('estado')->default('activo')->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['rut', 'edad', 'telefono', 'estado']);
        });
    }
};
