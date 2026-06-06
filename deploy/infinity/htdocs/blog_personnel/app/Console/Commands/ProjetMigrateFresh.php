<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Réinitialise la base de données puis relance toutes les migrations.
 */
class ProjetMigrateFresh extends Command
{
    protected $signature = 'projet:migrate-fresh {--seed : Exécuter les seeders après migration}';

    protected $description = 'Vide et recrée la base de données du blog';

    public function handle(): int
    {
        if (! $this->confirm('Cela supprimera toutes les données. Continuer ?', true)) {
            return self::FAILURE;
        }

        $this->call('migrate:fresh', [
            '--force' => true,
            '--seed' => $this->option('seed'),
        ]);

        $this->components->info('La base de données est prête.');

        return self::SUCCESS;
    }
}
