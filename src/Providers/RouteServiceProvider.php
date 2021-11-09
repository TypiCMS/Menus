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
    public function map(): void
    {
        /*
         * Admin routes
         */
        Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
            $router->get('menus', [AdminController::class, 'index'])->name('index-menus')->middleware('can:read menus');
            $router->get('menus/create', [AdminController::class, 'create'])->name('create-menu')->middleware('can:create menus');
            $router->get('menus/{menu}/edit', [AdminController::class, 'edit'])->name('edit-menu')->middleware('can:read menus');
            $router->post('menus', [AdminController::class, 'store'])->name('store-menu')->middleware('can:create menus');
            $router->put('menus/{menu}', [AdminController::class, 'update'])->name('update-menu')->middleware('can:update menus');

            $router->get('menus/{menu}/menulinks/create', [MenulinksAdminController::class, 'create'])->name('create-menulink')->middleware('can:create menulinks');
            $router->get('menus/{menu}/menulinks/{menulink}/edit', [MenulinksAdminController::class, 'edit'])->name('edit-menulink')->middleware('can:read menulinks');
            $router->post('menus/{menu}/menulinks', [MenulinksAdminController::class, 'store'])->name('store-menulink')->middleware('can:create menulinks');
            $router->put('menus/{menu}/menulinks/{menulink}', [MenulinksAdminController::class, 'update'])->name('update-menulink')->middleware('can:update menulinks');
        });

        /*
         * API routes
         */
        Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router) {
            $router->get('menus', [ApiController::class, 'index'])->middleware('can:read menus');
            $router->patch('menus/{menu}', [ApiController::class, 'updatePartial'])->middleware('can:update menus');
            $router->delete('menus/{menu}', [ApiController::class, 'destroy'])->middleware('can:delete menus');

            $router->get('menus/{menu}/menulinks', [MenulinksApiController::class, 'index'])->middleware('can:read menulinks');
            $router->patch('menus/{menu}/menulinks/{menulink}', [MenulinksApiController::class, 'updatePartial'])->middleware('can:update menulinks');
            $router->post('menus/{menu}/menulinks/sort', [MenulinksApiController::class, 'sort'])->middleware('can:update menulinks');
            $router->delete('menus/{menu}/menulinks/{menulink}', [MenulinksApiController::class, 'destroy'])->middleware('can:delete menulinks');
        });
    }
}
