<?php

namespace TypiCMS\Modules\Menus\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class MenulinkFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'menu_id'    => 'required',
            'page_id'    => 'required_if:has_categories,1',
            'class'      => 'max:255',
            'icon_class' => 'max:255',
            'title.*'    => 'max:255',
            'url.*'      => 'url|max:255',
        ];
    }
}
