<?php

namespace App\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\Vite;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->spa()
            ->id('admin')
            ->path('/')
            ->login()
            ->spa()
            ->profile()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverResources(in: app_path('Resources'), for: 'App\\Resources')
            ->discoverPages(in: app_path('Pages'), for: 'App\\Pages')
            ->discoverWidgets(in: app_path('Widgets'), for: 'App\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->assets([
                // Css::make('app-css', Vite::useHotFile('hot')->asset('resources/css/app.css')),
                // Js::make('app-js', Vite::useHotFile('hot')->asset('resources/js/app.js')),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(trans('dashboard-groups.settings'))
                    ->icon('heroicon-o-cog'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->favicon(asset('favicons/favicon.png'));

        return $panel;
    }

    public function boot(): void
    {
        Table::$defaultCurrency = 'EGP';
    }
}
