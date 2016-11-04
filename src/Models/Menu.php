<?php

namespace TypiCMS\Modules\Menus\Models;

use Dimsav\Translatable\Translatable;
use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\History\Traits\Historable;

class Menu extends Base
{
    use Historable;
    use Translatable;
    use PresentableTrait;

    protected $presenter = 'TypiCMS\Modules\Menus\Presenters\ModulePresenter';

    protected $fillable = [
        'name',
        'class',
    ];

    /**
     * Translatable model configs.
     *
     * @var array
     */
    public $translatedAttributes = [
        'status',
    ];

    protected $appends = ['status'];

    /**
     * Relations.
     */
    public function menulinks()
    {
        return $this->hasMany('TypiCMS\Modules\Menus\Models\Menulink')->orderBy('position', 'asc');
    }

    /**
     * Append status attribute from translation table.
     *
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return $value;
    }
}
