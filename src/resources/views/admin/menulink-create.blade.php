@extends('core::admin.master')

@section('title', trans('menus::global.New menulink'))

@section('main')

    <h1>
        <a href="{{ route('admin.menus.edit', $menu->id) }}" title="{{ trans('menus::global.Back to menu') }}">
            <span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ trans('menus::global.Back to menu') }}</span>
        </a>
        @lang('menus::global.New menulink')
    </h1>

    {!! BootForm::open()->action(route('admin.menus.menulinks.index', $menu->id))->multipart() !!}
        @include('menus::admin._menulink-form')
    {!! BootForm::close() !!}

@stop
