@if ($menu = Menus::getMenu($name))
    @include('menus::public._list', ['menulinks' => $menu->menulinks, 'class' => $menu->class])
@endif
