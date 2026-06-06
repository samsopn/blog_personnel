<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.application', function ($view): void {
            $view->with(
                'notificationsNonLues',
                auth()->check()
                    ? Notification::query()->where('user_id', auth()->id())->whereNull('read_at')->count()
                    : 0
            );
        });

        $racineWeb = $this->detecterRacineWeb();

        if ($racineWeb === null) {
            return;
        }

        $this->app->usePublicPath($racineWeb);
        $this->configurerStockagePublic($racineWeb);
    }

    /**
     * Détecte la racine web hors structure Laravel standard (public/).
     */
    private function detecterRacineWeb(): ?string
    {
        if (file_exists(base_path('public/build/manifest.json'))) {
            return null;
        }

        if (file_exists(base_path('build/manifest.json'))) {
            return base_path();
        }

        $racineWeb = dirname(base_path());
        if (file_exists($racineWeb.'/build/manifest.json')) {
            return $racineWeb;
        }

        return null;
    }

    /**
     * Sans `php artisan storage:link`, les fichiers doivent être écrits
     * directement dans htdocs/storage/ pour être accessibles via /storage/...
     */
    private function configurerStockagePublic(string $racineWeb): void
    {
        $dossierPublic = $racineWeb.'/storage';

        if (! is_dir($dossierPublic)) {
            @mkdir($dossierPublic, 0755, true);
        }

        config([
            'filesystems.disks.public.root' => $dossierPublic,
            'filesystems.disks.public.url' => rtrim((string) config('app.url'), '/').'/storage',
        ]);
    }
}
