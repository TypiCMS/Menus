<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Menus\Facades\Menus;
use TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;

class MenulinksAdminController extends BaseAdminController
{
    public function index(): View
    {
        $id = request('menu_id');
        $models = $this->model->where('menu_id', $id)->orderBy('position')->findAll()->nest();

        return response()->json($models, 200);
    }

    public function create(Menu $menu): View
    {
        $model = new Menulink();

        return view('menus::admin.create-menulink')
            ->with(compact('model', 'menu'));
    }

    public function edit(Menu $menu, Menulink $menulink): View
    {
        return view('menus::admin.edit-menulink')
            ->with([
                'menu' => $menu,
                'model' => $menulink,
            ]);
    }

    public function store(Menu $menu, MenulinkFormRequest $request): RedirectResponse
    {
        $data = $request->all();
        $data['parent_id'] = null;
        $data['page_id'] = $data['page_id'] ?? null;
        $data['position'] = $data['position'] ?? 0;
        $model = Menulink::create($data);

        return $this->redirect($request, $model);
    }

    public function update(Menu $menu, Menulink $menulink, MenulinkFormRequest $request): RedirectResponse
    {
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ?: null;
        $data['page_id'] = $data['page_id'] ?: null;
        $menulink->update($menulink->id, $data);

        return $this->redirect($request, $menulink);
    }

    public function sort(): JsonResponse
    {
        $this->model->sort(request()->all());

        return response()->json([
            'error' => false,
            'message' => __('Items sorted'),
        ], 200);
    }
}
