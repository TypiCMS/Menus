<?php
namespace TypiCMS\Modules\Menus\Repositories;

use App;
use Categories;
use Config;
use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Log;
use Notification;
use Request;
use TypiCMS\Modules\Menulinks\Models\Menulink;
use TypiCMS\Repositories\RepositoriesAbstract;

class EloquentMenu extends RepositoriesAbstract implements MenuInterface
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all menus
     *
     * @return array with key = menu name and value = menu model
     */
    public function allMenus()
    {
        $with = [
            'menulinks.translations',
            'menulinks.page.translations',
        ];
        $menus = $this->make($with)
            ->whereHas(
                'translations',
                function (Builder $query) {
                    $query->where('status', 1);
                    $query->where('locale', App::getLocale());
                }
            )
            ->get();

        $menusArray = array();
        foreach ($menus as $menu) {

            // remove offline items from each menu
            $menu->menulinks = $menu->menulinks->filter(function (Menulink $menulink) {
                if ($menulink->status == 1) {
                    return true;
                }
            });

            $menusArray[$menu->name] = $menu;
        }

        return $menusArray;
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
            $menu = App::make('TypiCMS.menus')[$name];
        } catch (ErrorException $e) {
            Log::info('No menu found with name “' . $name . '”');
            return null;
        }

        $menu->menulinks = $this->prepare($menu->menulinks);

        $menu->menulinks->nest();

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
     * 1. if menulink has url field, take it
     * 2. if menulink has a page, take the uri of the page
     *
     * @param Model   $menulink
     * @return string uri
     */
    public function setHref($menulink)
    {
        if ($menulink->url) {
            return $menulink->url;
        }

        if ($menulink->page) {
            return '/' . $menulink->page->uri;
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
        $activeUri = Request::path();
        if ($activeUri != '/') {
            $activeUri = '/' . $activeUri;
        }
        $classArray = preg_split('/ /', $menulink->class, null, PREG_SPLIT_NO_EMPTY);
        // add active class if item uri equals current uri
        // or current uri contain item uri
        // item uri must be bigger than 3 to avoid homepage link always active ('/', '/lg')
        if ($menulink->href == $activeUri ||
                (
                    strlen($menulink->href) > 3 &&
                    preg_match('@^'.$menulink->href.'@', $activeUri)
                )
            ) {
            $classArray[] = 'active';
        }
        return implode(' ', $classArray);
    }
}
