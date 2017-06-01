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
            <!-- Nested node template -->
            <div ui-tree="treeOptions">
                <ul ui-tree-nodes="" data-max-depth="3" ng-model="models" id="tree-root">
                    <li ng-repeat="model in models" ui-tree-node ng-include="'/views/partials/listItemMenulink.html'"></li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="col-sm-6">
        {!! BootForm::text(__('Name'), 'name')->required() !!}
        {!! BootForm::text(__('Class'), 'class') !!}
        {!! TranslatableBootForm::hidden('status')->value(0) !!}
        {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
    </div>

</div>
