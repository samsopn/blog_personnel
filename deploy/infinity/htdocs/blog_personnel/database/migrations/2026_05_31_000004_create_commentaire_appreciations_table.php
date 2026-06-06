<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaire_appreciations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commentaire_id')->constrained('commentaires')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['commentaire_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaire_appreciations');
    }
};
