<?php
namespace TypiCMS\Modules\Menus\Composers;

use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->menus['content']->put('menus', [
            'weight' => config('typicms.menus.sidebar.weight'),
            'request' => $view->prefix . '/menus*',
            'route' => 'admin.menus.index',
            'icon-class' => 'icon fa fa-fw fa-bars',
            'title' => 'Menus',
        ]);
    }
}
