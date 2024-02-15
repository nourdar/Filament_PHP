<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages;

class TenantFreePanelPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->id('second')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            // ->domain()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->profile()
            ->userMenuItems([
                // 'settings' => MenuItem::make()
                //     ->label('Paramètre')
                //     ->url('/admin/settings')
                //     ->icon('heroicon-o-cog-6-tooth'),
                'logout' => MenuItem::make()->label('Déconnexion'),
                // 'profile' => MenuItem::make()->label('Edit profile')
            ])
            ->breadcrumbs(true)
            ->font('Outfit')
            ->plugins([
                // \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),

                BreezyCore::make()->myProfile(
                    shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                    shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                    hasAvatars: false, // Enables the avatar upload form component (default = false)
                    slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                ),
                // SpotlightPlugin::make()
            ])
            ->favicon('images/logo-nameque.png')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
                OptimizeImages::class,
                InitializeTenancyByDomain::class,
                'universal'
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
