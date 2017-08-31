@extends('core::admin.master')

@section('title', $model->title)

@section('content')

    <a class="btn-back" href="{{ route('admin::edit-menu', $menu->id) }}" title="{{ __('Back to menu') }}">
        <span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('Back to menu') }}</span>
    </a>

    <h1>{{ $model->present()->title }}</h1>

    {!! BootForm::open()->put()->action(route('admin::update-menulink', [$menu->id, $model->id]))->multipart() !!}
    {!! BootForm::bind($model) !!}
        @include('menus::admin._form-menulink')
    {!! BootForm::close() !!}

@endsection
