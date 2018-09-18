<?php

namespace TypiCMS\Modules\Menus\Models;

use Exception;
use Illuminate\Support\Facades\Log;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;
use TypiCMS\Modules\Pages\Models\Page;
use TypiCMS\NestableTrait;

class Menulink extends Base
{
    use HasTranslations;
    use Historable;
    use NestableTrait;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Menus\Presenters\MenulinkPresenter';

    protected $guarded = ['id', 'exit'];

    public $translatable = [
        'title',
        'url',
        'status',
    ];

    /**
     * A menulink belongs to a menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * A menulink can belongs to a page.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * A menulink can have submenulinks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submenulinks()
    {
        return $this->hasMany(self::class, 'parent_id')->order();
    }

    /**
     * A menulink can have a parent.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get edit url of model.
     *
     * @return string|void
     */
    public function editUrl()
    {
        try {
            return route('admin::edit-menulink', [$this->menu_id, $this->id]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Get back office’s index of models url.
     *
     * @return string|void
     */
    public function indexUrl()
    {
        try {
            return route('admin::edit-menu', $this->menu_id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
