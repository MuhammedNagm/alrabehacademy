<div class="row">
    <div class="col-md-12">
        @component('components.box')
            {!! ModulesForm::openForm($module, ['url' => url($resource_url.'/'.$module->hashed_id.'/license-key'),'method'=>'PUT']) !!}

            <div class="row">
                <div class="col-md-12">
                    {!! ModulesForm::text('license_key','Settings::labels.module.license_key',true,$module->license_key,
                    ['help_text'=>'']) !!}
                    {!! ModulesForm::formButtons(trans('Settings::labels.module.license_update') . $title_singular, [], ['show_cancel' => false])  !!}
                </div>
            </div>
            {!! ModulesForm::closeForm($module) !!}
        @endcomponent
    </div>
</div>
