<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Menus\Models\Menulink;
use TypiCMS\Modules\Menus\Repositories\EloquentMenulink as Repository;

class MenulinksApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get models.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $id = Request::input('menu_id');
        $models = $this->repository->where('menu_id', $id)->allNested();

        return response()->json($models, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $model = $this->repository->create(Request::all());
        $error = $model ? false : true;

        return response()->json([
            'error' => $error,
            'model' => $model,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $updated = $this->repository->update(request('id'), Request::all());

        return response()->json([
            'error' => !$updated,
        ]);
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

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
