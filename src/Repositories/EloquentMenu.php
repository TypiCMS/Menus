<?php
namespace TypiCMS\Modules\Menus\Repositories;

use Categories;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;
use TypiCMS\Modules\Menus\Models\Menu;

class EloquentMenu extends RepositoriesAbstract implements MenuInterface
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models
     *
     * @param  array       $with Eager load related models
     * @param  boolean     $all  Show published or all
     * @return Collection|NestedCollection
     */
    public function all(array $with = array(), $all = false)
    {
        $query = $this->make($with);

        if (! $all) {
            $query->online();
        }

        // Query ORDER BY
        $query->order();

        // Get
        return $query->get();
    }

    /**
     * Render a menu
     *
     * @param  string $name menu name
     * @return string       html code of a menu
     */
    public function render($name)
    {
        return view('menus::public._menu', ['name' => $name]);
    }

    /**
     * Build a menu
     *
     * @deprecated
     * @param  string $name       menu name
     * @return string             html code of a menu
     */
    public function build($name)
    {
        return $this->render($name);
    }

    /**
     * Get a menu
     *
     * @param  string $name menu name
     * @return Model  $menu nested collection
     */
    public function getMenu($name)
    {
        try {
            $menu = app('TypiCMS.menus')->filter(function(Menu $menu) use ($name) {
                return $menu->name == $name;
            })->first();
            $menu->menulinks = $this->prepare($menu->menulinks);
            $menu->menulinks->nest();
        } catch (Exception $e) {
            Log::info('No menu found with name “' . $name . '”');
            return null;
        }

        return $menu;
    }

    public function prepare($items = null)
    {
        $items->each(function ($item) {
            if ($item->has_categories) {
                $item->items = $this->prepare(Categories::allForMenu($item->page->uri));
            }
            $item->href = $this->setHref($item);
            $item->class = $this->setClass($item);
        });

        return $items;
    }

    /**
     * 1. If menulink has url field, take it.
     * 2. If menulink has a page, take the uri of the page in the current locale.
     *
     * @param Model $menulink
     * @return string uri
     */
    public function setHref($menulink)
    {
        if ($menulink->url) {
            return $menulink->url;
        }
        if ($menulink->page) {
            return $menulink->page->uri();
        }
        return '';
    }

    /**
     * Take the classes from field and add active if needed
     *
     * @param Model   $menulink
     * @return string classes
     */
    public function setClass($menulink)
    {
        $classArray = preg_split('/ /', $menulink->class, null, PREG_SPLIT_NO_EMPTY);
        // add active class if current uri is equal to item uri or contains
        // item uri and is bigger than 3 to avoid homepage link always active ('/', '/lg')
        $pattern = $menulink->href;
        if (strlen($menulink->href) > 3) {
            $pattern .= '*';
        }
        if (Request::is($pattern)) {
            $classArray[] = 'active';
        }
        return implode(' ', $classArray);
    }
}
