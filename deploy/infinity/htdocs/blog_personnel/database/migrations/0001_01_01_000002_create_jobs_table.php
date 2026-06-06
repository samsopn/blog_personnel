<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Tables jobs / failed_jobs supprimées : l'application n'utilise pas de file d'attente.
 * Voir QUEUE_CONNECTION=sync dans .env
 */
return new class extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
