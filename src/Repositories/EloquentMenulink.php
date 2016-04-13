<?php

namespace TypiCMS\Modules\Menus\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentMenulink extends RepositoriesAbstract implements MenulinkInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a menu’s items and children.
     *
     * @param int  $id
     * @param bool $all published or all
     *
     * @return Collection
     */
    public function allFromMenu($id = null, $all = false)
    {
        $query = $this->model->with('translations')
            ->order()
            ->where('menu_id', $id);

        // All posts or only published
        if (!$all) {
            $query->where('status', 1);
        }

        $models = $query->get()->nest();

        return $models;
    }

    /**
     * Get sort data.
     *
     * @param int   $position
     * @param array $item
     *
     * @return array
     */
    protected function getSortData($position, $item)
    {
        return [
            'position'  => $position,
            'parent_id' => $item['parent_id'],
        ];
    }
}
