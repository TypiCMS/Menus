@component('core::admin._buttons-form', ['model' => $model])
@endcomponent

<file-manager related-table="{{ $model->getTable() }}" :related-id="{{ $model->id ?? 0 }}"></file-manager>
<file-field type="image" field="image_id" data="{{ $model->image }}"></file-field>

{!! BootForm::text(__('Name'), 'name')->required() !!}
{!! BootForm::text(__('Class'), 'class') !!}
<div class="form-group">
    {!! TranslatableBootForm::hidden('status')->value(0) !!}
    {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
</div>

@if ($model->id)

    <item-list-tree
        locale="{{ config('typicms.content_locale') }}"
        url-base="/api/menus/{{ $model->id }}/menulinks"
        fields="id,menu_id,page_id,position,parent_id,status,title,url"
        table="menulinks"
        title="Menulinks"
    >

        <template slot="add-button">
            @include('core::admin._button-create', ['url' => route('admin::create-menulink', $model->id), 'module' => 'menus'])
        </template>

    </item-list-tree>

@endif
