{!! ModulesForm::text('code','Settings::attributes.setting.code',true,$setting->code,
array_merge(['help_text' => trans('Settings::labels.message.code_help_text')],$setting->exists?['readonly']:[])) !!}

{!! ModulesForm::select('category', 'Settings::attributes.setting.category', \Settings::getCategoriesList(), true, null, $setting->exists?['readonly']:[]) !!}

{!! ModulesForm::text('label','Settings::attributes.setting.label',true) !!}
