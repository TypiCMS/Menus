<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Menus\Models\Menu;

class ApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Menu::class)
            ->selectFields($request->input('fields.menus'))
            ->allowedSorts(['status_translated', 'name'])
            ->allowedFilters([
                AllowedFilter::custom('name', new FilterOr()),
            ])
            ->allowedIncludes(['image'])
            ->paginate($request->input('per_page'));

        return $data;
    }

    protected function updatePartial(Menu $menu, Request $request): JsonResponse
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
            $menu->$key = $value;
        }
        $saved = $menu->save();

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        $deleted = $menu->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
