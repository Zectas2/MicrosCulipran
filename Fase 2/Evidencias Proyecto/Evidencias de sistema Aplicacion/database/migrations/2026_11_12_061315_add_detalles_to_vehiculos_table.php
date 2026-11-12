<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->string('marca')->nullable()->after('patente');
            $table->integer('anio')->nullable()->after('marca');
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['marca', 'anio']);
        });
    }
};
