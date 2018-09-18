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
    public function index(Menu $menu, Request $request)
    {
        $userPreferences = $request->user()->preferences;

        $models = $this->repository
            ->where('menu_id', $menu->id)
            ->orderBy('position')
            ->findAll()
            ->map(function ($item) use ($userPreferences) {
                $item->data = $item->toArray();
                $item->isLeaf = $item->module === null ? false : true;
                $item->isExpanded = !array_get($userPreferences, 'Menulinks_'.$item->id.'_collapsed', false);

                return $item;
            })
            ->childrenName('children')
            ->nest();

        return $models;
    }

    public function update(Menu $menu, Menulink $menulink, Request $request)
    {
        $data = [];
        foreach ($request->all() as $column => $content) {
            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $data[$column.'->'.$key] = $value;
                }
            } else {
                $data[$column] = $content;
            }
        }

        foreach ($data as $key => $value) {
            $menulink->$key = $value;
        }
        $saved = $menulink->save();

        $this->repository->forgetCache();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    /**
     * Sort list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Menu $menu)
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
    public function destroy(Menu $menu, Menulink $menulink)
    {
        $deleted = $this->repository->delete($menulink);
        Menus::forgetCache();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
