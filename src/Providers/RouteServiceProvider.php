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
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('menus', 'AdminController@index')->name('admin::index-menus')->middleware('can:see-all-menus');
                $router->get('menus/create', 'AdminController@create')->name('admin::create-menu')->middleware('can:create-menu');
                $router->get('menus/{menu}/edit', 'AdminController@edit')->name('admin::edit-menu')->middleware('can:update-menu');
                $router->post('menus', 'AdminController@store')->name('admin::store-menu')->middleware('can:create-menu');
                $router->put('menus/{menu}', 'AdminController@update')->name('admin::update-menu')->middleware('can:update-menu');
                $router->patch('menus/{ids}', 'AdminController@ajaxUpdate')->name('admin::update-menu-ajax')->middleware('can:update-menu');
                $router->delete('menus/{ids}', 'AdminController@destroyMultiple')->name('admin::destroy-menu')->middleware('can:delete-menu');

                $router->get('menulinks', 'MenulinksAdminController@index')->name('admin::index-menulinks')->middleware('can:update-menu');
                $router->get('menus/{menu}/menulinks/create', 'MenulinksAdminController@create')->name('admin::create-menulink')->middleware('can:create-menu');
                $router->get('menus/{menu}/menulinks/{menulink}/edit', 'MenulinksAdminController@edit')->name('admin::edit-menulink')->middleware('can:update-menu');
                $router->post('menus/{menu}/menulinks', 'MenulinksAdminController@store')->name('admin::store-menulink')->middleware('can:create-menu');
                $router->put('menus/{menu}/menulinks/{menulink}', 'MenulinksAdminController@update')->name('admin::update-menulink')->middleware('can:update-menu');
                $router->patch('menulinks/{ids}', 'MenulinksAdminController@ajaxUpdate')->name('admin::update-menulink-ajax')->middleware('can:update-menu');
                $router->delete('menulinks/{menulink}', 'MenulinksAdminController@destroy')->name('admin::destroy-menulink')->middleware('can:delete-menu');
                $router->post('menulinks/sort', 'MenulinksAdminController@sort')->name('admin::sort-menulinks')->middleware('can:update-menu');
            });
        });
    }
}
