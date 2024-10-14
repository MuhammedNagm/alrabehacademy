<div class="row">
    <div class="col-md-12">
        @component('components.box')
            {!! ModulesForm::openForm(null,['url' => url($resource_url.'/add'),'method'=>'POST','files'=>true]) !!}
            {!! ModulesForm::file('theme','Theme::attributes.theme.theme_file', true) !!}
            {!! ModulesForm::checkbox('update_if_exist','Theme::attributes.theme.theme_update', false) !!}
            {!! ModulesForm::formButtons(trans('Theme::attributes.theme.theme_upload') . $title_singular, [], ['show_cancel'=>false]); !!}
            {!! ModulesForm::closeForm() !!}
        @endcomponent
    </div>
</div>
