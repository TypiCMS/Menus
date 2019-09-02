<?php

namespace TypiCMS\Modules\Menus\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Menus\Facades\Menus;
use TypiCMS\Modules\Menus\Models\Menu;
use TypiCMS\Modules\Menus\Models\Menulink;
use TypiCMS\NestableCollection;

class MenulinksApiController extends BaseApiController
{
    public function index(Menu $menu, Request $request): NestableCollection
    {
        $userPreferences = $request->user()->preferences;

        $data = QueryBuilder::for(Menulink::class)
            ->translated($request->input('translatable_fields'))
            ->where('menu_id', $menu->id)
            ->orderBy('position')
            ->get()
            ->map(function ($item) use ($userPreferences) {
                $item->data = $item->toArray();
                $item->isLeaf = $item->module === null ? false : true;
                $item->isExpanded = !Arr::get($userPreferences, 'Menulinks_'.$item->id.'_collapsed', false);

                return $item;
            })
            ->childrenName('children')
            ->nest();

        return $data;
    }

    public function updatePartial(Menu $menu, Menulink $menulink, Request $request)
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

        return response()->json([
            'error' => !$saved,
        ]);
    }

    public function sort(Menu $menu, Request $request): JsonResponse
    {
        $data = $request->all();
        foreach ($data['item'] as $position => $item) {
            $menulink = Menulink::find($item['id']);
            $sortData = [
                'position' => (int) $position + 1,
                'parent_id' => $item['parent_id'],
            ];
            $menulink->update($sortData);
        }

        return response()->json([
            'error' => false,
            'message' => __('Items sorted'),
        ], 200);
    }

    public function destroy(Menu $menu, Menulink $menulink): JsonResponse
    {
        $deleted = $menulink->delete();

        return response()->json([
            'error' => !$deleted,
        ]);
    }
}
