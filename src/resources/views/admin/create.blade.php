@extends('core::admin.master')

@section('title', __('New menu'))

@section('content')

    @include('core::admin._button-back', ['module' => 'menus'])
    <h1>
        @lang('New menu')
    </h1>

    {!! BootForm::open()->action(route('admin::index-menus'))->multipart()->role('form') !!}
        @include('menus::admin._form')
    {!! BootForm::close() !!}

@endsection
