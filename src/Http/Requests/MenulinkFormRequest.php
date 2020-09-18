<?php

namespace TypiCMS\Modules\Menus\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class MenulinkFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'image_id' => 'nullable|integer',
            'menu_id' => 'required|integer',
            'parent_id' => 'nullable|integer',
            'page_id' => 'nullable|integer',
            'section_id' => 'nullable|integer',
            'class' => 'nullable|max:255',
            'icon_class' => 'nullable|max:255',
            'title.*' => 'nullable|max:255',
            'status.*' => 'boolean',
            'description.*' => 'nullable',
            'url.*' => 'nullable|url|max:255',
        ];
    }
}
