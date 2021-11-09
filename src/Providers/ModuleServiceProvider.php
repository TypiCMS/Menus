<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Menus\Composers\SidebarViewComposer;
use TypiCMS\Modules\Menus\Facades\Menulinks;
use TypiCMS\Modules\Menus\Facades\Menus;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.menus');
        $this->mergeConfigFrom(__DIR__.'/../config/permissions.php', 'typicms.permissions');
        $this->mergeConfigFrom(__DIR__.'/../config/config-menulinks.php', 'typicms.menulinks');

        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'menus');

        $this->publishes([
            __DIR__.'/../../database/migrations/create_menus_tables.php.stub' => getMigrationFileName('create_menus_tables'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/menus'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../database/seeders/MenuSeeder.php' => database_path('seeders/MenuSeeder.php'),
            __DIR__.'/../../database/seeders/MenulinkSeeder.php' => database_path('seeders/MenulinkSeeder.php'),
        ], 'seeders');

        AliasLoader::getInstance()->alias('Menus', Menus::class);
        AliasLoader::getInstance()->alias('Menulinks', Menulinks::class);

        Blade::directive('menu', function ($name) {
            return "<?php echo view('menus::public._menu', ['name' => {$name}]) ?>";
        });

        View::composer('core::admin._sidebar', SidebarViewComposer::class);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind('Menus', Menu::class);
        $this->app->bind('Menulinks', Menulink::class);
    }
}
