<?php
namespace TypiCMS\Modules\Menus\Providers;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lang;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Repositories\CacheDecorator;
use TypiCMS\Modules\Menus\Repositories\EloquentMenu;
use TypiCMS\Modules\Menus\Services\Form\MenuForm;
use TypiCMS\Modules\Menus\Services\Form\MenuFormLaravelValidator;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addNamespace('menus', __DIR__ . '/../views/');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'menus');
        $this->publishes([
            __DIR__ . '/../config/' => config_path('typicms/menus'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Menus',
            'TypiCMS\Modules\Menus\Facades\Facade'
        );
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Menus\Composers\SideBarViewComposer');

        $app->bind('TypiCMS\Modules\Menus\Repositories\MenuInterface', function (Application $app) {
            $repository = new EloquentMenu(new Menu);
            if (! Config::get('app.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['menus', 'menulinks', 'pages'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });

        $app->bind('TypiCMS\Modules\Menus\Services\Form\MenuForm', function (Application $app) {
            return new MenuForm(
                new MenuFormLaravelValidator($app['validator']),
                $app->make('TypiCMS\Modules\Menus\Repositories\MenuInterface')
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Get all menus.
        |--------------------------------------------------------------------------|
        */
        $this->app['TypiCMS.menus'] = $this->app->share(function (Application $app) {
            return $app->make('TypiCMS\Modules\Menus\Repositories\MenuInterface')->getAllMenus();
        });
    }
}
