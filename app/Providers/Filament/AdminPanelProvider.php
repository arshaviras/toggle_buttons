<?php

namespace App\Providers\Filament;

use App\Filament\Auth\AdminLogin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Brickx\MaintenanceSwitch\MaintenanceSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Platform;
use Illuminate\Contracts\Support\Htmlable;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            //->default()
            ->id('admin')
            ->path('admin')
            ->login(AdminLogin::class)
            ->authGuard('admin')
            ->maxContentWidth(MaxWidth::Full)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('/storage/logo_light.svg'))
            ->darkModeBrandLogo(asset('/storage/logo_dark.svg'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('/storage/favicon.svg'))
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchDebounce('750ms')
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                MaintenanceSwitchPlugin::make(),
                SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(['hy', 'en', 'ru']),
                FilamentShieldPlugin::make()
            ]);
    }
}
