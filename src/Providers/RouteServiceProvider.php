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
            $router->get('admin/menus', ['as' => 'admin.menus.index', 'uses' => 'AdminController@index']);
            $router->get('admin/menus/create', ['as' => 'admin.menus.create', 'uses' => 'AdminController@create']);
            $router->get('admin/menus/{menu}/edit', ['as' => 'admin.menus.edit', 'uses' => 'AdminController@edit']);
            $router->post('admin/menus', ['as' => 'admin.menus.store', 'uses' => 'AdminController@store']);
            $router->put('admin/menus/{menu}', ['as' => 'admin.menus.update', 'uses' => 'AdminController@update']);

            $router->get('admin/menus/{menu}/menulinks', ['as' => 'admin.menus.menulinks.index', 'uses' => 'MenulinksAdminController@index']);
            $router->get('admin/menus/{menu}/menulinks/create', ['as' => 'admin.menus.menulinks.create', 'uses' => 'MenulinksAdminController@create']);
            $router->get('admin/menus/{menu}/menulinks/{menulink}/edit', ['as' => 'admin.menus.menulinks.edit', 'uses' => 'MenulinksAdminController@edit']);
            $router->post('admin/menus/{menu}/menulinks', ['as' => 'admin.menus.menulinks.store', 'uses' => 'MenulinksAdminController@store']);
            $router->put('admin/menus/{menu}/menulinks/{menulink}', ['as' => 'admin.menus.menulinks.update', 'uses' => 'MenulinksAdminController@update']);
            $router->post('admin/menulinks/sort', ['as' => 'admin.menulinks.sort', 'uses' => 'MenulinksAdminController@sort']);

            /*
             * API routes
             */
            $router->get('api/menus', ['as' => 'api.menus.index', 'uses' => 'ApiController@index']);
            $router->put('api/menus/{menu}', ['as' => 'api.menus.update', 'uses' => 'ApiController@update']);
            $router->delete('api/menus/{menu}', ['as' => 'api.menus.destroy', 'uses' => 'ApiController@destroy']);

            $router->get('api/menulinks', ['as' => 'api.menulinks.index', 'uses' => 'MenulinksApiController@index']);
            $router->put('api/menulinks/{menulink}', ['as' => 'api.menulinks.update', 'uses' => 'MenulinksApiController@update']);
            $router->delete('api/menulinks/{menulink}', ['as' => 'api.menulinks.destroy', 'uses' => 'MenulinksApiController@destroy']);
        });
    }
}
