<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;
use TypiCMS\Modules\Menus\Repositories\MenulinkInterface;

class MenulinksAdminController extends BaseAdminController
{
    public function __construct(MenulinkInterface $menulink)
    {
        parent::__construct($menulink);
    }

    /**
     * Create form for a new resource.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu $menu
     *
     * @return \Illuminate\View\View
     */
    public function create(Menu $menu)
    {
        $model = $this->repository->getModel();

        return view('menus::admin.menulink-create')
            ->with(compact('model', 'menu'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu     $menu
     * @param \TypiCMS\Modules\Menus\Models\Menulink $menulink
     *
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu, Menulink $menulink)
    {
        return view('menus::admin.menulink-edit')
            ->with([
                'menu'  => $menu,
                'model' => $menulink,
            ]);
    }

    /**
     * Show resource.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu     $menu
     * @param \TypiCMS\Modules\Menus\Models\Menulink $menulink
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Menu $menu, Menulink $menulink)
    {
        return Redirect::route('admin.menus.menulinks.edit', [$menu->id, $menulink->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu                       $menu
     * @param \TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Menu $menu, MenulinkFormRequest $request)
    {
        $data = $request->all();
        $data['parent_id'] = null;
        $data['page_id'] = $data['page_id'] ?: null;
        $data['position'] = $data['position'] ?: 0;
        $model = $this->repository->create($data);

        return $this->redirect($request, $model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu                       $menu
     * @param \TypiCMS\Modules\Menus\Models\Menulink                   $model
     * @param \TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Menu $menu, Menulink $model, MenulinkFormRequest $request)
    {
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ?: null;
        $data['page_id'] = $data['page_id'] ?: null;
        $this->repository->update($data);

        return $this->redirect($request, $model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu     $menu
     * @param \TypiCMS\Modules\Menus\Models\Menulink $model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Menu $menu, Menulink $menulink)
    {
        if ($this->repository->delete($menulink)) {
            return back();
        }
    }

    /**
     * Sort list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort()
    {
        $this->repository->sort(Request::all());

        return response()->json([
            'error'   => false,
            'message' => trans('global.Items sorted'),
        ], 200);
    }
}
