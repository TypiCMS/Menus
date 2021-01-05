<li class="menu-{{ $name }}-item menu-{{ $name }}-item-{{ $menulink->id }} {{ $menulink->class }}" id="menuitem_{{ $menulink->id }}" role="menuitem">
    <a class="menu-{{ $name }}-link {{ $menulink->items->count() > 0 ? 'dropdown-toggle' : '' }}{{ !empty($menulink->parent) ? 'dropdown-item' : '' }}" href="{{ $menulink->items->count() > 0 ? '#' : url($menulink->href) }}" @if ($menulink->target === '_blank') target="_blank" rel="noopener noreferrer" @endif @if ($menulink->items->count() > 0) role="button" id="menuitem_{{ $menulink->id }}_id" data-bs-toggle="dropdown" aria-expanded="false" @endif>
        @if ($menulink->image !== null)
            <img src="{{ $menulink->present()->image }}" width="32" height="32" alt="">
        @endif
        {{ $menulink->title }}
    </a>
    @if ($menulink->items->count() > 0)
        <ul class="menu-{{ $name }}-dropdown dropdown-menu" aria-labelledby="menuitem_{{ $menulink->id }}_id">
            @foreach ($menulink->items as $menulink)
                @include('menus::public._item', ['menulink' => $menulink])
            @endforeach
        </ul>
    @endif
</li>
