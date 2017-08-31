@extends('core::admin.master')

@section('title', __('New menulink'))

@section('content')

    <a href="{{ route('admin::edit-menu', $menu->id) }}" title="{{ __('Back to menu') }}" class="btn-back">
        <span class="text-muted fa fa-arrow-circle-left"></span><span class="sr-only">{{ __('Back to menu') }}</span>
    </a>
    <h1>
        @lang('New menulink')
    </h1>

    {!! BootForm::open()->action(route('admin::store-menulink', $menu->id))->multipart() !!}
        @include('menus::admin._form-menulink')
    {!! BootForm::close() !!}

@endsection
