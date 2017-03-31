<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

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
     * @return null
     */
    public function map()
    {
        Route::group(['namespace' => $this->namespace], function (Router $router) {
            /*
             * Admin routes
             */
            $router->group(['middleware' => 'admin', 'prefix' => 'admin'], function (Router $router) {
                $router->get('menus', 'AdminController@index')->name('admin::index-menus');
                $router->get('menus/create', 'AdminController@create')->name('admin::create-menu');
                $router->get('menus/{menu}/edit', 'AdminController@edit')->name('admin::edit-menu');
                $router->post('menus', 'AdminController@store')->name('admin::store-menu');
                $router->put('menus/{menu}', 'AdminController@update')->name('admin::update-menu');
                $router->patch('menus/{ids}', 'AdminController@ajaxUpdate')->name('admin::update-menu-ajax');
                $router->delete('menus/{ids}', 'AdminController@destroyMultiple')->name('admin::destroy-menu');

                $router->get('menulinks', 'MenulinksAdminController@index')->name('admin::index-menulinks');
                $router->get('menus/{menu}/menulinks/create', 'MenulinksAdminController@create')->name('admin::create-menulink');
                $router->get('menus/{menu}/menulinks/{menulink}/edit', 'MenulinksAdminController@edit')->name('admin::edit-menulink');
                $router->post('menus/{menu}/menulinks', 'MenulinksAdminController@store')->name('admin::store-menulink');
                $router->put('menus/{menu}/menulinks/{menulink}', 'MenulinksAdminController@update')->name('admin::update-menulink');
                $router->patch('menulinks/{ids}', 'MenulinksAdminController@ajaxUpdate')->name('admin::update-menulink-ajax');
                $router->delete('menulinks/{menulink}', 'MenulinksAdminController@destroy')->name('admin::destroy-menulink');
                $router->post('menulinks/sort', 'MenulinksAdminController@sort')->name('admin::sort-menulinks');
            });
        });
    }
}
