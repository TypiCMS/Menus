@if ($menulinks->count())
<ul>
    @foreach ($menulinks as $menulink)
        @include('menus::public._item')
    @endforeach
</ul>
@endif
