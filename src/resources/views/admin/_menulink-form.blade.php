@section('titleLeftButton')
    <a href="{{ route('admin::edit-menu', $menu->id) }}" title="{{ __('menus::global.Back to menu') }}">
        <span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('menus::global.Back to menu') }}</span>
    </a>
@endsection

<div class="btn-toolbar">
    <button class="btn btn-primary" value="true" id="exit" name="exit" type="submit">@lang('validation.attributes.save and exit')</button>
    <button class="btn btn-default" type="submit">@lang('validation.attributes.save')</button>
    @include('core::admin._lang-switcher', ['js' => true])
</div>

<div class="row">

    {!! BootForm::hidden('id') !!}
    {!! BootForm::hidden('menu_id')->value($menu->id) !!}
    {!! BootForm::hidden('position') !!}
    {!! BootForm::hidden('parent_id') !!}

    <div class="col-sm-6">
        {!! TranslatableBootForm::text(__('validation.attributes.title'), 'title') !!}
        {!! TranslatableBootForm::text(__('validation.attributes.url'), 'url')->placeholder('http://') !!}
        {!! TranslatableBootForm::hidden('status')->value(0) !!}
        {!! TranslatableBootForm::checkbox(__('validation.attributes.online'), 'status') !!}
    </div>

    <div class="col-sm-6">
        {!! BootForm::select(__('validation.attributes.page_id'), 'page_id', Pages::allForSelect()) !!}
        {!! BootForm::hidden('has_categories')->value(0) !!}
        {!! BootForm::checkbox(__('validation.attributes.has_categories'), 'has_categories') !!}
        {!! BootForm::select(__('validation.attributes.target'), 'target', ['' => __('menus::global.Active tab'), '_blank' => __('menus::global.New tab')]) !!}
        {!! BootForm::text(__('validation.attributes.class'), 'class') !!}
        {!! BootForm::text(__('validation.attributes.icon_class'), 'icon_class') !!}
    </div>

</div>
