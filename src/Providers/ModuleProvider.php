<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;
use TypiCMS\Modules\Menus\Repositories\EloquentMenulink;
use TypiCMS\Modules\Menus\Repositories\EloquentMenu;
use TypiCMS\Modules\Menus\Repositories\MenulinkCacheDecorator;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.menus'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../config/menulinksconfig.php', 'typicms.menulinks'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['menus' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'menus');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'menus');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/menus'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Menus',
            'TypiCMS\Modules\Menus\Facades\Facade'
        );
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Menus\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Menus\Composers\SidebarViewComposer');

        $app->singleton('TypiCMS.menus', function (Application $app) {
            $with = [
                'menulinks' => function (HasMany $query) {
                    $query->online();
                },
                'menulinks.page',
            ];

            return $app->make('Menus')->with($with)->findAll();
        });

        $app->bind('Menus', EloquentMenu::class);
        $app->bind('Menulinks', EloquentMenulink::class);
    }
}
