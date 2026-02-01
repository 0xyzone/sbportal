<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use App\Livewire\ProfileCompleteness;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin;

class IndividualPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('individual')
            ->path('me')
            ->viteTheme('resources/css/filament/individual/theme.css')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailChangeVerification()
            ->emailVerification()
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(fn() => view('vendor.filament.components.brand'))
            ->brandLogoHeight('6rem')
            ->profile()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Individual/Resources'), for: 'App\Filament\Individual\Resources')
            ->discoverPages(in: app_path('Filament/Individual/Pages'), for: 'App\Filament\Individual\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Individual/Widgets'), for: 'App\Filament\Individual\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                ProfileCompleteness::class,
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
                TwoFactorAuthenticationPlugin::make()
                    ->enableTwoFactorAuthentication() // Enable Google 2FA
                    ->enablePasskeyAuthentication() // Enable Passkey
                    ->addTwoFactorMenuItem() // Add 2FA menu item
            ]);
    }
}
