<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
        $requete = $this->app->runningInConsole() ? null : $this->app->make(Request::class);
        $this->configurerHttps($requete);
        $this->configurerSessionsHebergement($requete);

        View::composer('layouts.application', function ($view): void {
            $view->with(
                'notificationsNonLues',
                auth()->check()
                    ? Notification::query()->where('user_id', auth()->id())->whereNull('read_at')->count()
                    : 0
            );
        });

        $racineWeb = $this->detecterRacineWeb();

        if ($racineWeb !== null) {
            $this->app->usePublicPath($racineWeb);
            $this->configurerStockagePublic($racineWeb);
        }
    }

    /**
     * Liens, formulaires et assets en https quand APP_URL l'indique.
     */
    private function configurerHttps(?Request $requete): void
    {
        $appUrl = (string) config('app.url', '');

        if (str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');

            return;
        }

        if ($requete?->isSecure()) {
            URL::forceScheme('https');
        }
    }

    /**
     * InfinityFree : SESSION_DOMAIN=null (texte), cookies Secure sur HTTP, sessions BDD instables.
     */
    private function configurerSessionsHebergement(?Request $requete): void
    {
        $domain = env('SESSION_DOMAIN');
        if ($domain === null || $domain === '' || strtolower((string) $domain) === 'null') {
            config(['session.domain' => null]);
        }

        config(['session.secure' => $this->cookieSessionDoitEtreSecure($requete)]);
        config(['session.path' => '/']);
        config(['session.same_site' => env('SESSION_SAME_SITE', 'lax')]);

        if ($this->estHebergementPartage()) {
            config(['session.driver' => 'file']);
        }

        foreach ([
            storage_path('framework/sessions'),
            storage_path('framework/cache'),
            storage_path('framework/views'),
        ] as $dossier) {
            if (! is_dir($dossier)) {
                @mkdir($dossier, 0755, true);
            }
        }
    }

    /**
     * Sur InfinityFree, trustProxies peut indiquer HTTPS alors que le navigateur est en HTTP :
     * un cookie Secure n'est alors jamais renvoyé → erreur 419.
     */
    private function cookieSessionDoitEtreSecure(?Request $requete): bool
    {
        $secureEnv = env('SESSION_SECURE_COOKIE');
        if ($secureEnv !== null && $secureEnv !== '') {
            return filter_var($secureEnv, FILTER_VALIDATE_BOOLEAN);
        }

        $appUrl = (string) config('app.url', '');
        if (str_starts_with($appUrl, 'http://')) {
            return false;
        }

        if (str_starts_with($appUrl, 'https://')) {
            return true;
        }

        return $requete?->isSecure() ?? false;
    }

    private function estHebergementPartage(): bool
    {
        $appUrl = (string) config('app.url', '');
        if (str_contains($appUrl, 'free.nf') || str_contains($appUrl, 'infinityfree')) {
            return true;
        }

        $hote = (string) ($_SERVER['HTTP_HOST'] ?? '');

        return str_contains($hote, 'free.nf') || str_contains($hote, 'infinityfree');
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
