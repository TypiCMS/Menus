<?php

namespace TypiCMS\Modules\Menus\Models;

use Laracasts\Presenter\PresentableTrait;
use Spatie\Translatable\HasTranslations;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;

class Menu extends Base
{
    use HasTranslations;
    use Historable;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Menus\Presenters\ModulePresenter';

    protected $guarded = ['id', 'exit'];

    public $translatable = [
        'status',
    ];

    public function menulinks()
    {
        return $this->hasMany('TypiCMS\Modules\Menus\Models\Menulink')->orderBy('position', 'asc');
    }
}
