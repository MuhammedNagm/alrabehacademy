@extends('layouts.crud.create_edit')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/flags.authy.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/form.authy.css"/>
@endsection

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('user_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! ModulesForm::openForm($user, ['files'=>true]) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! ModulesForm::text('name', 'User::attributes.user.name', true) !!}
                        {!! ModulesForm::email('email', 'User::attributes.user.email', true) !!}

                        @if ((\Settings::get('confirm_user_registration_email', false)))
                            {!! ModulesForm::checkbox('confirmed', 'User::attributes.user.confirmed', $user->confirmed) !!}
                        @endif
                     

                       {!! ModulesForm::radio('user_type','User::attributes.user.user_type',true, __('User::attributes.user.user_type_options'),$user->user_type?:'member') !!}


                        {!! ModulesForm::password('password','User::attributes.user.password',!$user->exists) !!}
                        {!! ModulesForm::password('password_confirmation', 'User::attributes.user.password_confirmation' ,!$user->exists) !!}
                                                {!! ModulesForm::text('address', 'User::attributes.user.address' ,false,null,[]) !!}


                        @if(\TwoFactorAuth::isActive())
                            {!! ModulesForm::checkbox('two_factor_auth_enabled', 'User::attributes.user.two_factor_auth_enabled' ,\TwoFactorAuth::isEnabled($user)) !!}

                            @if(!empty(\TwoFactorAuth::getSupportedChannels()))
                                {!! ModulesForm::radio('channel', 'User::attributes.user.channel' , false,\TwoFactorAuth::getSupportedChannels(),array_get($user->getTwoFactorAuthProviderOptions(),'channel', null)) !!}
                            @endif
                        @endif
                    </div>
                    <div id="country-div" class="col-md-4">
                        {!! ModulesForm::select('gender', 'User::attributes.user.select_gender', __('User::attributes.user.select_gender_options') ) !!}

                        {!! ModulesForm::text('job_title', 'User::attributes.user.job_title' ) !!}
                       @php
                       $departmentsList = \Modules\components\LMS\Models\Category::where('parent_id', null)->pluck('name', 'id')->toArray();
                        $countriesList = \Modules\Settings\Models\Country::pluck('name_ar', 'id')->toArray();
                        @endphp
                        
                        {!! ModulesForm::select('departments[]','User::attributes.user.choose_teacher_departments',$departmentsList,false,null,['multiple' => true],'select2') !!}

                        {!! ModulesForm::select('country_id','corals-admin::labels.auth.country',$countriesList,false,null,[],'select2') !!}

                       {{--  {!! ModulesForm::text('phone_country_code', 'User::attributes.user.phone_country_code' ,false,null,['id'=>'authy-countries']) !!} --}}
                        {!! ModulesForm::text('phone_number', 'User::attributes.user.phone_number' ,false,null,['id'=>'authy-cellphone']) !!}

                        {!! ModulesForm::checkboxes('roles[]', 'User::attributes.user.roles' ,true,\Roles::getRolesList(),$user->roles->pluck('id')->toArray()) !!}
                    </div>
                    <div class="col-md-4">
                        {!! ModulesForm::file('picture',  'User::attributes.user.picture',false,['accept'=>"image/x-png,image/gif,image/jpeg"] ) !!}

                        <img src="{{ $user->picture_thumb }}" class="img-circle img-responsive" width="150"
                             alt="User Picture"/>
                        @if($user->exists && $user->getFirstMedia('user-picture'))
                            {!! ModulesForm::checkbox('clear',  'User::attributes.user.default_picture' ) !!}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! ModulesForm::textarea('properties[about]', 'User::attributes.user.about' , false, null,[
                        'class'=>'limited-text',
                        'maxlength'=>250,
                        'help_text'=>'<span class="limit-counter">0</span>/250',
                        'rows'=>'4']) !!}
                    </div>
                </div>
                {!! ModulesForm::customFields($user) !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! ModulesForm::formButtons() !!}
                    </div>
                </div>
                {!! ModulesForm::closeForm($user) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/form.authy.js"></script>
    <script type="text/javascript">
        $('#country-div').bind("DOMSubtreeModified", function () {
            $(".countries-input").addClass('form-control');
        });
    </script>
@endsection
