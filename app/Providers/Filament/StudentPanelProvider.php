<?php

namespace App\Providers\Filament;

use App\Filament\Auth\StudentLogin;
use App\Filament\Student\Pages\Survey;
use App\Http\Middleware\StudentAuthenticate;
use App\Models\Student;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use C6Digital\PasswordlessLogin\PasswordlessLoginPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StudentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('student')
            ->path('')
            ->login(StudentLogin::class)
            ->navigation(false)
            //->topNavigation()
            ->maxContentWidth(MaxWidth::Full)
            ->colors([
                'primary' => Color::Purple,
            ])
            ->brandLogo(asset('/storage/logo_light.svg'))
            ->darkModeBrandLogo(asset('/storage/logo_dark.svg'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('/storage/favicon.svg'))
            ->discoverResources(in: app_path('Filament/Student/Resources'), for: 'App\\Filament\\Student\\Resources')
            ->discoverPages(in: app_path('Filament/Student/Pages'), for: 'App\\Filament\\Student\\Pages')
            ->pages([
                Survey::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Student/Widgets'), for: 'App\\Filament\\Student\\Widgets')
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
            ])
            ->authMiddleware([
                Authenticate::class,
                //StudentAuthenticate::class,
            ])
            ->plugins([
                PasswordlessLoginPlugin::make()
                    ->allowPasswordInLocalEnvironment()
                //FilamentShieldPlugin::make()
            ]);
    }
}
