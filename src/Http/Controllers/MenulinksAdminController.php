<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
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
     * Show the form for creating a new resource.
     *
     * @param  $menu
     *
     * @return void
     */
    public function create($menu = null)
    {
        $model = $this->repository->getModel();

        return view('menus::admin.menulink-create')
            ->with(compact('model', 'menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $menu
     * @param  $model
     *
     * @return void
     */
    public function edit($menu = null, $model = null)
    {
        return view('menus::admin.menulink-edit')
            ->with(compact('model', 'menu'));
    }

    /**
     * Show resource.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu     $menu
     * @param \TypiCMS\Modules\Menus\Models\Menulink $model
     *
     * @return Redirect
     */
    public function show($menu = null, $model = null)
    {
        return Redirect::route('admin.menus.menulinks.edit', [$menu->id, $model->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Menus\Models\Menu                       $menu
     * @param \TypiCMS\Modules\Menus\Http\Requests\MenulinkFormRequest $request
     *
     * @return Redirect
     */
    public function store(Menu $menu = null, MenulinkFormRequest $request)
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
     * @return Redirect
     */
    public function update(Menu $menu = null, Menulink $model, MenulinkFormRequest $request)
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
     * @param \TypiCMS\Modules\Menus\Models\Menu     $parent
     * @param \TypiCMS\Modules\Menus\Models\Menulink $model
     *
     * @return Redirect
     */
    public function destroy($parent = null, $model = null)
    {
        if ($this->repository->delete($model)) {
            return back();
        }
    }

    /**
     * Sort list.
     *
     * @return \Illuminate\Support\Facades\Response
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
