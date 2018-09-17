<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Http\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Menus\Facades\Menus;
use TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;
use TypiCMS\Modules\Menus\Repositories\EloquentMenulink;

class MenulinksApiController extends BaseAdminController
{
    public function __construct(EloquentMenulink $menulink)
    {
        parent::__construct($menulink);
    }

    /**
     * Get models.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Menu $menu)
    {
        $models = $this->repository
            ->where('menu_id', $menu->id)
            ->orderBy('position')
            ->findAll()
            ->nest();

        return $models;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu                       $menu
     * @param \TypiCMS\Modules\Menus\Models\Menulink                   $menulink
     * @param \TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Menu $menu, Menulink $menulink, MenulinkFormRequest $request)
    {
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ?: null;
        $data['page_id'] = $data['page_id'] ?: null;
        $this->repository->update($menulink->id, $data);
        Menus::forgetCache();

        return $this->redirect($request, $menulink);
    }

    /**
     * Sort list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort()
    {
        $this->repository->sort(request()->all());
        Menus::forgetCache();

        return response()->json([
            'error' => false,
            'message' => __('Items sorted'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Menulinks\Models\Menulink $menulink
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Menulink $menulink)
    {
        $deleted = $this->repository->delete($menulink);
        Menus::forgetCache();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
