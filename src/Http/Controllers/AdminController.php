<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Menus\Http\Requests\FormRequest;
use TypiCMS\Modules\Menus\Models\Menu;

class AdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('menus::admin.index');
    }

    public function create(): View
    {
        $model = new Menu();

        return view('menus::admin.create')
            ->with(compact('model'));
    }

    public function edit(Menu $menu): View
    {
        return view('menus::admin.edit')
            ->with(['model' => $menu]);
    }

    public function store(FormRequest $request): RedirectResponse
    {
        $menu = Menu::create($request->validated());

        return $this->redirect($request, $menu);
    }

    public function update(Menu $menu, FormRequest $request): RedirectResponse
    {
        $menu->update($request->validated());

        return $this->redirect($request, $menu);
    }
}
