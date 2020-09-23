<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Menus\Composers\SidebarViewComposer;
use TypiCMS\Modules\Menus\Facades\Menulinks;
use TypiCMS\Modules\Menus\Facades\Menus;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.menus');
        $this->mergeConfigFrom(__DIR__.'/../config/permissions.php', 'typicms.permissions');
        $this->mergeConfigFrom(__DIR__.'/../config/config-menulinks.php', 'typicms.menulinks');

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['menus' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'menus');

        $this->publishes([
            __DIR__.'/../database/migrations/create_menus_tables.php.stub' => getMigrationFileName('create_menus_tables'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/menus'),
        ], 'views');

        AliasLoader::getInstance()->alias('Menus', Menus::class);
        AliasLoader::getInstance()->alias('Menulinks', Menulinks::class);

        Blade::directive('menu', function ($name) {
            return "<?php echo view('menus::public._menu', ['name' => {$name}]) ?>";
        });

        /*
         * Sidebar view composer
         */
        $this->app->view->composer('core::admin._sidebar', SidebarViewComposer::class);
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        $app->bind('Menus', Menu::class);
        $app->bind('Menulinks', Menulink::class);
    }
}
