<div class="row">
    <div class="col-md-12">
        @component('components.box')
            {!! ModulesForm::openForm($setting, ['files'=>true]) !!}
            @include('Settings::settings.partials.shared_fields',['setting' => $setting])

            @include('Settings::settings.types_value.'.strtolower($setting->type))

            {!! ModulesForm::customFields($setting,'col-md-12') !!}

            {!! ModulesForm::formButtons('<i class="fa fa-save"></i> ' . $title_singular, [], ['show_cancel' => false])  !!}

            {!! ModulesForm::closeForm($setting) !!}
        @endcomponent
    </div>
</div>
