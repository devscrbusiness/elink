<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Ej: whatsapp, facebook, instagram, etc.
            $table->string('alias')->nullable(); // Para whatsapp, alias del link
            $table->string('url'); // URL real (no expuesta en público si es whatsapp)
            $table->string('greeting')->nullable(); // Saludo para whatsapp
            $table->boolean('is_public')->default(true); // Si se muestra en la página pública
            $table->unique(['business_id', 'alias']); // Alias único por negocio
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};