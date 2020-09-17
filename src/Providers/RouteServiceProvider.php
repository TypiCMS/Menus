<?php

namespace TypiCMS\Modules\Menus\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Menus\Http\Controllers\AdminController;
use TypiCMS\Modules\Menus\Http\Controllers\ApiController;
use TypiCMS\Modules\Menus\Http\Controllers\MenulinksAdminController;
use TypiCMS\Modules\Menus\Http\Controllers\MenulinksApiController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('menus', [AdminController::class, 'index'])->name('admin::index-menus')->middleware('can:read menus');
                $router->get('menus/create', [AdminController::class, 'create'])->name('admin::create-menu')->middleware('can:create menus');
                $router->get('menus/{menu}/edit', [AdminController::class, 'edit'])->name('admin::edit-menu')->middleware('can:update menus');
                $router->post('menus', [AdminController::class, 'store'])->name('admin::store-menu')->middleware('can:create menus');
                $router->put('menus/{menu}', [AdminController::class, 'update'])->name('admin::update-menu')->middleware('can:update menus');

                $router->get('menus/{menu}/menulinks/create', [MenulinksAdminController::class, 'create'])->name('admin::create-menulink')->middleware('can:create menus');
                $router->get('menus/{menu}/menulinks/{menulink}/edit', [MenulinksAdminController::class, 'edit'])->name('admin::edit-menulink')->middleware('can:update menus');
                $router->post('menus/{menu}/menulinks', [MenulinksAdminController::class, 'store'])->name('admin::store-menulink')->middleware('can:create menus');
                $router->put('menus/{menu}/menulinks/{menulink}', [MenulinksAdminController::class, 'update'])->name('admin::update-menulink')->middleware('can:update menus');
            });

            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('menus', [ApiController::class, 'index'])->middleware('can:read menus');
                    $router->patch('menus/{menu}', [ApiController::class, 'updatePartial'])->middleware('can:update menus');
                    $router->delete('menus/{menu}', [ApiController::class, 'destroy'])->middleware('can:delete menus');

                    $router->get('menus/{menu}/menulinks', [MenulinksApiController::class, 'index'])->middleware('can:read menus');
                    $router->patch('menus/{menu}/menulinks/{menulink}', [MenulinksApiController::class, 'updatePartial'])->middleware('can:update menus');
                    $router->post('menus/{menu}/menulinks/sort', [MenulinksApiController::class, 'sort'])->middleware('can:update menus');
                    $router->delete('menus/{menu}/menulinks/{menulink}', [MenulinksApiController::class, 'destroy'])->middleware('can:delete menus');
                });
            });
        });
    }
}
