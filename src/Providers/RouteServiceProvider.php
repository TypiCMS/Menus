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
            $router->get('admin/menus', 'AdminController@index')->name('admin::index-menus');
            $router->get('admin/menus/create', 'AdminController@create')->name('admin::create-menus');
            $router->get('admin/menus/{menu}/edit', 'AdminController@edit')->name('admin::edit-menus');
            $router->post('admin/menus', 'AdminController@store')->name('admin::store-menus');
            $router->put('admin/menus/{menu}', 'AdminController@update')->name('admin::update-menus');

            $router->get('admin/menus/{menu}/menulinks', ['as' => 'admin.menus.menulinks.index', 'uses' => 'MenulinksAdminController@index']);
            $router->get('admin/menus/{menu}/menulinks/create', ['as' => 'admin.menus.menulinks.create', 'uses' => 'MenulinksAdminController@create']);
            $router->get('admin/menus/{menu}/menulinks/{menulink}/edit', ['as' => 'admin.menus.menulinks.edit', 'uses' => 'MenulinksAdminController@edit']);
            $router->post('admin/menus/{menu}/menulinks', ['as' => 'admin.menus.menulinks.store', 'uses' => 'MenulinksAdminController@store']);
            $router->put('admin/menus/{menu}/menulinks/{menulink}', ['as' => 'admin.menus.menulinks.update', 'uses' => 'MenulinksAdminController@update']);
            $router->post('admin/menulinks/sort', ['as' => 'admin.menulinks.sort', 'uses' => 'MenulinksAdminController@sort']);

            /*
             * API routes
             */
            $router->get('api/menus', 'ApiController@index')->name('api::index-menus');
            $router->put('api/menus/{menu}', 'ApiController@update')->name('api::update-menus');
            $router->delete('api/menus/{menu}', 'ApiController@destroy')->name('api::destroy-menus');

            $router->get('api/menulinks', ['as' => 'api.menulinks.index', 'uses' => 'MenulinksApiController@index']);
            $router->put('api/menulinks/{menulink}', ['as' => 'api.menulinks.update', 'uses' => 'MenulinksApiController@update']);
            $router->delete('api/menulinks/{menulink}', ['as' => 'api.menulinks.destroy', 'uses' => 'MenulinksApiController@destroy']);
        });
    }
}
