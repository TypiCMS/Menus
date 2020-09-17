<?php

namespace TypiCMS\Modules\Menus\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Files\Models\File;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\Modules\Menus\Presenters\ModulePresenter;
use TypiCMS\NestableCollection;

class Menu extends Base
{
    use HasTranslations;
    use Historable;
    use PresentableTrait;

    protected $presenter = ModulePresenter::class;

    protected $guarded = [];

    public $translatable = [
        'status',
    ];

    public function getMenu($name): ?self
    {
        try {
            $menu = app('TypiCMS.menus')->first(function (self $menu) use ($name) {
                return $menu->name === $name;
            });
        } catch (Exception $e) {
            Log::info('No menu found with name “'.$name.'”');

            return null;
        }

        return $menu;
    }

    public function prepare($items = null): NestableCollection
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
     */
    public function setHref(Menulink $menulink): string
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
     * Set the class and add active if needed.
     *
     * @param mixed $menulink
     */
    public function setClass($menulink): string
    {
        $classArray = preg_split('/ /', $menulink->class, null, PREG_SPLIT_NO_EMPTY);
        // add active class if current uri is equal to item uri or contains
        // item uri and is bigger than 3 to avoid homepage link always active ('/', '/lg')
        $pattern = $menulink->href;
        if (mb_strlen($menulink->href) > 3) {
            $pattern .= '*';
        }
        if (request()->is($pattern)) {
            $classArray[] = 'active';
        }

        return implode(' ', $classArray);
    }

    public function getThumbAttribute(): string
    {
        return $this->present()->image(null, 54);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function menulinks(): HasMany
    {
        return $this->hasMany(Menulink::class)->orderBy('position', 'asc');
    }
}
