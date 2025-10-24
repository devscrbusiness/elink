<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // 1. Eliminar la clave foránea existente que depende del índice único.
            $table->dropForeign(['business_id']);
            // 2. Ahora sí, eliminar el índice único.
            $table->dropUnique(['business_id']);
            // 3. Volver a crear la clave foránea, pero sin la restricción de unicidad.
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Para revertir, hacemos los pasos en orden inverso.
            // 1. Eliminar la clave foránea que acabamos de crear.
            $table->dropForeign(['business_id']);
            // 2. Volver a añadir el índice único.
            $table->unique('business_id');
            // 3. Volver a crear la clave foránea original que dependía del índice único.
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }
};