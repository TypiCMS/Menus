<div class="btn-toolbar mb-4">
    <button class="btn btn-sm btn-primary mr-2" value="true" id="exit" name="exit" type="submit">{{ __('Save and exit') }}</button>
    <button class="btn btn-sm btn-light mr-2" type="submit">{{ __('Save') }}</button>
    @include('core::admin._lang-switcher-for-form')
</div>

{!! BootForm::hidden('id') !!}
{!! BootForm::hidden('menu_id')->value($menu->id) !!}
{!! BootForm::hidden('position') !!}
{!! BootForm::hidden('parent_id') !!}

<div class="form-row">

    <div class="col-sm-6">
        {!! TranslatableBootForm::text(__('Title'), 'title') !!}
        <div class="form-group">
            {!! TranslatableBootForm::hidden('status')->value(0) !!}
            {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!}
        </div>
        {!! TranslatableBootForm::textarea(__('Description'), 'description')->rows(3) !!}
        <file-manager related-table="{{ $model->getTable() }}" :related-id="{{ $model->id ?? 0 }}"></file-manager>
        <file-field type="image" field="image_id" :init-file="{{ $model->image ?? 'null' }}"></file-field>
    </div>

    <div class="col-sm-6">
        {!! BootForm::select(__('Page'), 'page_id', Pages::allForSelect())->addClass('custom-select') !!}
        {!! BootForm::select(__('Section'), 'section_id', ['' => ''])->addClass('custom-select') !!}
        {!! TranslatableBootForm::text(__('Url'), 'url')->placeholder('http://') !!}
        {!! BootForm::select(__('Target'), 'target', ['' => __('Active tab'), '_blank' => __('New tab')])->addClass('custom-select') !!}
        {!! BootForm::text(__('Class'), 'class') !!}
        {!! BootForm::text(__('Icon class'), 'icon_class') !!}
    </div>

</div>
@push('js')
<script>
var selectPage = document.getElementById('page_id');
var selectSection = document.getElementById('section_id');
var selectedSectionId = parseInt('{{ $model->section_id }}');
function initSelect() {
    for (var i = 0; i < selectSection.length; i++) {
        if (selectSection.options[i].value !== '') {
            selectSection.remove(i);
        }
    }
}
function getSections() {
    initSelect();
    var pageId = selectPage.options[selectPage.selectedIndex].value;
    if (!pageId) {
        return;
    }

    // Get sections and create <option> elements.
    axios.get('/api/pages/'+pageId+'/sections?sort=position&fields[page_sections]=id,position,title').then(function(response){
        var sections = response.data.data;
        for (var i = 0; i < sections.length; i++) {
            var option = document.createElement('option');
            option.value = sections[i].id;
            option.innerHTML = sections[i].title_translated+' (#'+sections[i].id+')';
            if (sections[i].id === selectedSectionId) {
                option.selected = true;
            }
            selectSection.appendChild(option);
        }
    });
}
selectPage.onchange = getSections;
getSections();
</script>
@endpush
