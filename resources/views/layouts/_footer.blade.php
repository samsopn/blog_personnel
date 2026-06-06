<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="site-footer-grid">
            <div>
                <p class="site-footer-brand">{{ config('app.name') }}</p>
                <p class="site-footer-desc">
                    Blog personnel dédié au développement web, aux technologies et au partage d'expériences.
                </p>
            </div>

            <div>
                <p class="site-footer-title">Navigation</p>
                <ul class="site-footer-links">
                    <li><a href="{{ route('accueil') }}">Accueil</a></li>
                    <li><a href="{{ route('recherche') }}">Recherche</a></li>
                    @auth
                        <li><a href="{{ route('user.favoris') }}">Mes favoris</a></li>
                        <li><a href="{{ route('user.profil') }}">Mon profil</a></li>
                    @else
                        <li><a href="{{ route('auth.connexion') }}">Connexion</a></li>
                        <li><a href="{{ route('auth.inscription') }}">Inscription</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <p class="site-footer-title">Informations</p>
                <ul class="site-footer-links">
                    <li><a href="{{ route('accueil') }}#publications">Derniers articles</a></li>
                    <li><span class="site-footer-muted">Articles, commentaires et favoris</span></li>
                    <li><span class="site-footer-muted">&copy; {{ date('Y') }} {{ config('app.name') }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
