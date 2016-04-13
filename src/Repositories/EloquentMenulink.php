<?php

namespace TypiCMS\Modules\Menus\Repositories;

use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentMenulink extends RepositoriesAbstract implements MenulinkInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
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
