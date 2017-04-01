<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Menus\Composers\SidebarViewComposer;
use TypiCMS\Modules\Menus\Facades\Menus;
use TypiCMS\Modules\Menus\Repositories\EloquentMenu;
use TypiCMS\Modules\Menus\Repositories\EloquentMenulink;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.menus'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../config/config-menulinks.php', 'typicms.menulinks'
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
        $this->publishes([
            __DIR__.'/../../public' => public_path(),
        ], 'assets');

        AliasLoader::getInstance()->alias('Menus', Menus::class);

        Blade::directive('menu', function ($name) {
            return "<?php echo Menus::render($name) ?>";
        });

    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', SidebarViewComposer::class);

        $app->singleton('TypiCMS.menus', function (Application $app) {
            $with = [
                'menulinks' => function (HasMany $query) {
                    $query->published();
                },
                'menulinks.page',
            ];

            return $app->make('Menus')->with($with)->findAll();
        });

        $app->bind('Menus', EloquentMenu::class);
        $app->bind('Menulinks', EloquentMenulink::class);
    }
}
