<?php

namespace TypiCMS\Modules\Menus\Repositories;

use Illuminate\Database\Eloquent\Collection;
use TypiCMS\Modules\Core\Repositories\RepositoryInterface;

interface MenulinkInterface extends RepositoryInterface
{
    /**
     * Get a menu’s items and children.
     *
     * @param int  $id
     * @param bool $all published or all
     *
     * @return Collection
     */
    public function allFromMenu($id = null, $all = false);
}
