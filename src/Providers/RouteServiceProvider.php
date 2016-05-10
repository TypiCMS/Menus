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
     * @return null
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            /*
             * Admin routes
             */
            $router->get('admin/menus', 'AdminController@index')->name('admin::index-menus');
            $router->get('admin/menus/create', 'AdminController@create')->name('admin::create-menu');
            $router->get('admin/menus/{menu}/edit', 'AdminController@edit')->name('admin::edit-menu');
            $router->post('admin/menus', 'AdminController@store')->name('admin::store-menu');
            $router->put('admin/menus/{menu}', 'AdminController@update')->name('admin::update-menu');

            $router->get('admin/menus/{menu}/menulinks', 'MenulinksAdminController@index')->name('admin::index-menulinks');
            $router->get('admin/menus/{menu}/menulinks/create', 'MenulinksAdminController@create')->name('admin::create-menulink');
            $router->get('admin/menus/{menu}/menulinks/{menulink}/edit', 'MenulinksAdminController@edit')->name('admin::edit-menulink');
            $router->post('admin/menus/{menu}/menulinks', 'MenulinksAdminController@store')->name('admin::store-menulink');
            $router->put('admin/menus/{menu}/menulinks/{menulink}', 'MenulinksAdminController@update')->name('admin::update-menulink');
            $router->post('admin/menulinks/sort', 'MenulinksAdminController@sort')->name('admin::sort-menulinks');

            /*
             * API routes
             */
            $router->get('api/menus', 'ApiController@index')->name('api::index-menus');
            $router->put('api/menus/{menu}', 'ApiController@update')->name('api::update-menu');
            $router->delete('api/menus/{menu}', 'ApiController@destroy')->name('api::destroy-menu');

            $router->get('api/menulinks', 'MenulinksApiController@index')->name('api::index-menulinks');
            $router->put('api/menulinks/{menulink}', 'MenulinksApiController@update')->name('api::update-menulink');
            $router->delete('api/menulinks/{menulink}', 'MenulinksApiController@destroy')->name('api::destroy-menulink');
        });
    }
}
