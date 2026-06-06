<?php

use App\Models\Utilisateur;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->string('username', 30)->nullable()->unique()->after('name');
        });

        Utilisateur::query()->each(function (Utilisateur $utilisateur): void {
            if (filled($utilisateur->username)) {
                return;
            }

            $utilisateur->username = Utilisateur::genererUsernameUnique($utilisateur->name, $utilisateur->id);
            $utilisateur->saveQuietly();
        });
    }

    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
