@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('role_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! ModulesForm::openForm($role) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! ModulesForm::text('name', 'User::attributes.role.name' ,true,$role->name,
                        array_merge(['help_text'=>''],
                        $role->exists?['readonly']:[])) !!}

                        {!! ModulesForm::text('label', 'User::attributes.role.label' ,true) !!}

                        {!! ModulesForm::checkbox('subscription_required', 'User::attributes.role.subscription_required', $role->subscription_required) !!}

                        {!! ModulesForm::checkbox('disable_login', 'User::attributes.role.disable_login', $role->disable_login) !!}

                        {!! ModulesForm::text('redirect_url', 'User::attributes.role.redirect_url') !!}
                        {!! ModulesForm::text('dashboard_url', 'User::attributes.role.dashboard_url') !!}



                        {!! ModulesForm::select('dashboard_theme', 'User::attributes.role.dashboard_theme',collect(\Theme::all())->pluck('caption','name')->toArray() , false,null,['help_text' => trans('User::messages.role.defaults_will_be_used')] ) !!}

                        {!! ModulesForm::customFields($role, 'col-md-12') !!}

                        {!! ModulesForm::formButtons() !!}
                    </div>
                    <div class="col-md-6 permissions">
                        <div class="text-right">
                            {!! ModulesForm::button( 'User::labels.toggle_collapse' ,['class'=>'btn btn-sm btn-primary','id'=>'toggle_collapse']) !!}
                            {!! ModulesForm::button( 'User::labels.check_all' ,['class'=>'btn btn-sm btn-success','id'=>'check_all']) !!}
                            {!! ModulesForm::button( 'User::labels.revoke_all' ,['class'=>'btn btn-sm btn-warning','id'=>'revoke_all']) !!}
                            <hr/>
                        </div>
                        <div class="">
                            <small class="text-muted">
                                <i class="fa fa-th-large"></i> @lang('User::labels.package')
                            </small>
                            <small class="text-muted m-l-10">
                                <i class="fa fa-square"></i> @lang('User::labels.model')
                            </small>
                            <hr/>
                        </div>
                        @foreach(\Modules\User\Facades\Roles::getPermissionsTree() as $name => $package)
                            <ul class="list-unstyled panel-group" id="{{ $name }}_accordion">
                                <li>
                                    <i class="fa fa-th-large"></i> {{ $name }}
                                    <ul class="list-unstyled" style="margin-left: 25px;">
                                        @foreach($package as $name => $model)
                                            <li>
                                                <a data-toggle="collapse" data-parent="#{{ $name }}_accordion"
                                                   href="#collapse_{{ $colID = $name.str_random() }}">
                                                    <i class="fa fa-square"></i> {{ $name }}</a>
                                                <ul class="list-inline panel-collapse collapse"
                                                    id="collapse_{{ $colID }}"
                                                    style="margin-left: 25px;">
                                                    @foreach($model as $id => $name)
                                                        <li>
                                                            {!! ModulesForm::checkbox('permissions[]',$name,$role->permissions->pluck('id')->contains($id),$id,['id'=>'perm_'.$id]) !!}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
                {!! ModulesForm::closeForm($role) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#check_all').click(function (e) {
                let $permissionInput = $('.permissions input');
                $permissionInput.prop('checked', true);
                $permissionInput.trigger('change');
            });
            $('#revoke_all').click(function (e) {
                let $permissionInput = $('.permissions input');

                $permissionInput.prop('checked', false);
                $permissionInput.trigger('change');
            });

            $('#toggle_collapse').click(function (e) {
                $('.panel-collapse').collapse('toggle');
            });
        })
    </script>
@endsection
