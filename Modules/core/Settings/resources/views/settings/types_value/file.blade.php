{!! ModulesForm::file('value', 'Settings::attributes.setting.value') !!}

@if(!empty($setting->value))
    <br/>
    {!! ModulesForm::link(url('settings/download/'.$setting->hashed_id), 'Settings::labels.settings.file_download'. $setting->getOriginal('value'),['target'=>'_blank']) !!}
    <br/>
@endif
