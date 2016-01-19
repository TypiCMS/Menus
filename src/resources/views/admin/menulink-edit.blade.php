@extends('core::admin.master')

@section('title', $model->title)

@section('main')

    <h1>
        <a href="{{ route('admin.menus.edit', $menu->id) }}" title="{{ trans('menus::global.Back to menu') }}">
            <span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ trans('menus::global.Back to menu') }}</span>
        </a>
        {{ $model->present()->title }}
    </h1>

    {!! BootForm::open()->put()->action(route('admin.menus.menulinks.update', [$menu->id, $model->id]))->multipart() !!}
    {!! BootForm::bind($model) !!}
        @include('menus::admin._menulink-form')
    {!! BootForm::close() !!}

@endsection
