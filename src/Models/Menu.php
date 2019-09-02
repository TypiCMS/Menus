<?php

namespace TypiCMS\Modules\Menus\Models;

use Exception;
use Illuminate\Support\Facades\Log;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\Modules\Menus\Presenters\ModulePresenter;

class Menu extends Base
{
    use HasTranslations;
    use Historable;
    use PresentableTrait;

    protected $presenter = ModulePresenter::class;

    protected $guarded = ['id', 'exit'];

    protected $appends = ['thumb'];

    public $translatable = [
        'status',
    ];

    /**
     * Get a menu.
     *
     * @param string $name menu name
     *
     * @return \TypiCMS\Modules\Menus\Models\Menu|null
     */
    public function getMenu($name)
    {
        try {
            $menu = app('TypiCMS.menus')->first(function (self $menu) use ($name) {
                return $menu->name == $name;
            });
        } catch (Exception $e) {
            Log::info('No menu found with name “'.$name.'”');

            return;
        }

        return $menu;
    }

    /**
     * Set href and classes for each items in collection.
     *
     * @param $items
     *
     * @return \TypiCMS\NestableCollection
     */
    public function prepare($items = null)
    {
        $items->each(function ($item) {
            $item->items = collect();
            $item->href = $this->setHref($item);
            $item->class = $this->setClass($item);
        });

        return $items;
    }

    /**
     * 1. If menulink has url field, take it.
     * 2. If menulink has a page, take the uri of the page in the current locale.
     *
     * @param $menulink
     *
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

        return '/';
    }

    /**
     * Take the classes from field and add active if needed.
     *
     * @param $menulink
     *
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
        if (request()->is($pattern)) {
            $classArray[] = 'active';
        }

        return implode(' ', $classArray);
    }

    /**
     * Append thumb attribute.
     *
     * @return string
     */
    public function getThumbAttribute()
    {
        return $this->present()->image(null, 54);
    }

    /**
     * This model belongs to one image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    /**
     * This model has many menu links.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menulinks()
    {
        return $this->hasMany(Menulink::class)->orderBy('position', 'asc');
    }
}
