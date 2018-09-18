@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

{!! BootForm::hidden('id') !!}

{!! BootForm::text(__('Name'), 'name')->required() !!}
{!! BootForm::text(__('Class'), 'class') !!}
<div class="form-group">
    {!! TranslatableBootForm::hidden('status')->value(0) !!}
    {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
</div>

@if ($model->id)

    <item-list-tree
        url-base="{{ route('api::index-menulinks', $model->id) }}"
        title="Menulinks"
    >

        <template slot="add-button">
            @include('core::admin._button-create', ['url' => route('admin::create-menulink', $model->id), 'module' => 'menulinks'])
        </template>

    </item-list-tree>

@endif
