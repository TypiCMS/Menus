<?php

namespace TypiCMS\Modules\Menus\Models;

use TypiCMS\Modules\Core\Models\BaseTranslation;

class MenulinkTranslation extends BaseTranslation
{
    protected $fillable = [
        'title',
        'url',
        'status',
    ];

    /**
     * get the parent model.
     */
    public function menulink()
    {
        return $this->belongsTo('TypiCMS\Modules\Menus\Models\Menulink');
    }

    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Menus\Models\Menulink', 'menulink_id');
    }
}
