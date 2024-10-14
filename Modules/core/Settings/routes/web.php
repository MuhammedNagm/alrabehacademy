<?php

Route::get('utilities/select2', 'UtilitiesController@select2');

Route::resource('settings', 'SettingsController');

Route::resource('custom-fields', 'CustomFieldSettingsController', [
    'parameters' => ['custom-fields' => 'customFieldSetting'],
    'except' => ['show']
]);

Route::get('settings/download/{setting}', 'SettingsController@fileDownload');



Route::get('cache-management', 'SettingsController@cacheIndex');
Route::post('cache-management/{action}', 'SettingsController@cacheAction');