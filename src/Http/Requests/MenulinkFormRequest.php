<?php

namespace TypiCMS\Modules\Menus\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class MenulinkFormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'image_id' => 'nullable|integer',
            'menu_id' => 'required',
            'parent_id' => 'nullable',
            'has_categories' => 'required|boolean',
            'page_id' => 'required_if:has_categories,1',
            'class' => 'nullable|max:255',
            'icon_class' => 'nullable|max:255',
            'status.*' => 'boolean',
            'title.*' => 'nullable|max:255',
            'url.*' => 'nullable|url|max:255',
        ];
    }
}
