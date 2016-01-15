@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}

<div class="row">

    @if ($model->id)
    <div class="col-sm-6 container-menulinks">
        <p>
            <a href="{{ route('admin.menus.menulinks.create', $model->id) }}">
                <i class="fa fa-fw fa-plus-circle"></i>Add menu link
            </a>
        </p>
        <div ng-app="typicms" ng-cloak ng-controller="ListController">
            @include('core::admin._tabs-lang-list')
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

        {!! BootForm::text(trans('validation.attributes.name'), 'name') !!}
        {!! BootForm::text(trans('validation.attributes.class'), 'class') !!}

        {!! TranslatableBootForm::text(trans('validation.attributes.title'), 'title') !!}
        {!! TranslatableBootForm::hidden('status')->value(0) !!}
        {!! TranslatableBootForm::checkbox(trans('validation.attributes.online'), 'status') !!}

    </div>

</div>
