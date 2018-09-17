@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}

<div class="row">

    @if ($model->id)
    <div class="col-sm-6 container-menulinks">
        <p>
            <a href="{{ route('admin::create-menulink', $model->id) }}">
                <i class="fa fa-fw fa-plus-circle"></i>@lang('New menulink')
            </a>
        </p>
        <div ng-cloak ng-controller="ListController">
            <div class="btn-toolbar">
                @include('core::admin._lang-switcher-for-list')
            </div>

            <item-list-tree url-base="{{ route('api::index-menulinks', $model->id) }}">

                <template slot="buttons">
                    @include('core::admin._lang-switcher-for-list')
                </template>

            </item-list-tree>

        </div>
    </div>
    @endif

    <div class="col-sm-6">
        {!! BootForm::text(__('Name'), 'name')->required() !!}
        {!! BootForm::text(__('Class'), 'class') !!}
        <div class="form-group">
            {!! TranslatableBootForm::hidden('status')->value(0) !!}
            {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
        </div>
    </div>

</div>
