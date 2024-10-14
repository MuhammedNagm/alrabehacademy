<div class="row">
    <div class="col-md-12">
        @component('components.box')
            {!! ModulesForm::openForm(null,['url' => url($resource_url.'/add'),'method'=>'POST']) !!}
            {!! ModulesForm::text('module_key','Settings::attributes.module.code',true,'',
            ['help_text'=>'']) !!}

            {!! ModulesForm::text('license_key','Settings::attributes.module.license',true,'',['help_text'=>'']) !!}

            {!! ModulesForm::formButtons(trans('Settings::labels.module.download'). $title_singular, [], ['show_cancel'=>false]) !!}
            {!! ModulesForm::closeForm() !!}
        @endcomponent
    </div>
</div>
