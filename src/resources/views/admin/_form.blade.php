@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}

<div class="row">

    @if ($model->id)
    <div class="col-sm-6">
        <a href="{{ route('admin.menus.menulinks.create', $model->id) }}">
            <i class="fa fa-fw fa-plus-circle"></i>Add menu link
        </a>
        @include('menus::admin.menulinks')
    </div>
    @endif

    <div class="col-sm-6">

        {!! BootForm::text(trans('validation.attributes.name'), 'name') !!}
        {!! BootForm::text(trans('validation.attributes.class'), 'class') !!}

        @include('core::admin._tabs-lang')

        <div class="tab-content">

            @foreach ($locales as $lang)

            <div class="tab-pane fade @if ($locale == $lang)in active @endif" id="{{ $lang }}">
                {!! BootForm::text(trans('validation.attributes.title'), $lang.'[title]') !!}
                <input type="hidden" name="{{ $lang }}[status]" value="0">
                {!! BootForm::checkbox(trans('validation.attributes.online'), $lang.'[status]') !!}
            </div>

            @endforeach

        </div>
        
    </div>

</div>
