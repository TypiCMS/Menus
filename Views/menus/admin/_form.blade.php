@include('core::admin._buttons-form')

{{ BootForm::hidden('id'); }}

<div class="row">

    @if ($model->id)
    <div class="col-sm-6">
        <a href="{{ route('admin.menus.menulinks.create', $model->id) }}">
            <i class="fa fa-fw fa-plus-circle"></i>Add menu link
        </a>
        @include('menus.admin.menulinks')
    </div>
    @endif

    <div class="col-sm-6">

        <div class="form-group @if($errors->has('name'))has-error @endif">
            {{ Form::label('name', trans('validation.attributes.name'), array('class' => 'control-label')) }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
            {{ $errors->first('name', '<p class="help-block">:message</p>') }}
        </div>

        <div class="form-group @if($errors->has('class'))has-error @endif">
            {{ Form::label('class', trans('validation.attributes.class'), array('class' => 'control-label')) }}
            {{ Form::text('class', null, array('class' => 'form-control')) }}
            {{ $errors->first('class', '<p class="help-block">:message</p>') }}
        </div>

        @include('core::admin._tabs-lang')

        <div class="tab-content">

            @foreach ($locales as $lang)

            <div class="tab-pane fade @if ($locale == $lang)in active @endif" id="{{ $lang }}">
                <div class="form-group">
                    {{ Form::label($lang.'[title]', trans('validation.attributes.title')) }}
                    {{ BootForm::text(trans('labels.title'), $lang.'[title]') }}
                </div>
                {{ BootForm::checkbox(trans('labels.online'), $lang.'[status]') }}
            </div>

            @endforeach

        </div>
        
    </div>

</div>
