<?php

namespace App\Providers;

use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Support\Facades\FilamentView;

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
        Model::unguard();
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->modalHeading('Available Panels')
                ->slideOver()
                ->visible(fn(): bool => auth()->user()?->hasAnyRole([
                    'super_admin',
                ]))
                ->panels([
                    'mukhiya',
                    'school',
                    'individual',
                ]);
        });

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        };
    }
}
