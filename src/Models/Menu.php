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
            $menu = app('TypiCMS.menus')->first(function (Menu $menu) use ($name) {
                return $menu->name == $name;
            });
        } catch (Exception $e) {
            Log::info('No menu found with name “'.$name.'”');

            return;
        }

        return $menu;
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
