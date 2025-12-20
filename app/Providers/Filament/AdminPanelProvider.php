<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Illuminate\Http\Request;

use Filament\Pages\Dashboard;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Support\Facades\Route;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            ->maxContentWidth(Width::Full)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                Action::make('account')
                    ->icon(LucideIcon::Menu)
                    ->label(function(){
                        return Auth::user()->top_navigation === false ? 'Top Navigation: Off' : 'Top Navigation: On';
                    })
                    ->action(function(){
                        $user = Auth::user();
                        $user->top_navigation = !$user->top_navigation;
                        $user->save();

                        return redirect()->intended(route('filament.admin.pages.dashboard'));

                    }),
            ])
            ->topNavigation(function(){
                return Auth::user()->top_navigation;
            })
            ->brandName('Bengkel')
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                'Transaksi',
                'Master Data',
                'Stok',
                'Pengaturan',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            // ->widgets([
            //     AccountWidget::class,
            //     FilamentInfoWidget::class,
            // ])
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
