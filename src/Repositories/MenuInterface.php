<?php
namespace TypiCMS\Modules\Menus\Repositories;

use TypiCMS\Repositories\RepositoryInterface;

interface MenuInterface extends RepositoryInterface
{

    /**
     * Render a menu
     *
     * @param  string $name menu name
     * @return string       html code of a menu
     */
    public function render($name);

    /**
     * Build a menu
     *
     * @deprecated
     * @param  string $name       menu name
     * @return string             html code of a menu
     */
    public function build($name);

    /**
     * Get a menu
     *
     * @param  string $name       menu name
     * @return Collection         nested collection
     */
    public function getMenu($name);
}
