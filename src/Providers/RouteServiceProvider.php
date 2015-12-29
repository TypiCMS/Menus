<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Menus\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->model('menus', 'TypiCMS\Modules\Menus\Models\Menu');
        $router->model('menulinks', 'TypiCMS\Modules\Menus\Models\Menulink');
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            /*
             * Admin routes
             */
            $router->resource('admin/menus', 'AdminController');
            $router->resource('admin/menus.menulinks', 'MenulinksAdminController');
            $router->post(
                'admin/menulinks/sort',
                ['as' => 'admin.menulinks.sort', 'uses' => 'MenulinksAdminController@sort']
            );

            /*
             * API routes
             */
            $router->resource('api/menus', 'ApiController');
            $router->resource('api/menulinks', 'MenulinksApiController');
        });
    }
}
