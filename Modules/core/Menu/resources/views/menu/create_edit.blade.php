<div class="row">
    <div class="col-md-12">
        @component('components.box')
            @slot('box_title')
                @if($root)
                    @lang('Menu::labels.root_menu_title',['title'=>$menu->name])
                @elseif($parent)
                    @lang('Menu::labels.parent_menu_title',['title'=> $parent->name])
                @else
                    @lang('Menu::labels.menu_item_title',['title'=>$menu->name])
                @endif
            @endslot

            @slot('box_actions')
                @if($menu->exists && $root)
                    {!! ModulesForm::link(url(config('menu.models.menu.resource_url') . '/create?parent=' . $menu->hashed_id),trans('Modules::labels.create'),
                    ['class'=>'btn btn-sm btn-success','data' => ['action' => 'load','load_to' => '#menu_form']]) !!}
                @endif
            @endslot

            {!! ModulesForm::openForm($menu,['url' => url(config('menu.models.menu.resource_url').'/'.$menu->hashed_id), 'data-page_action'=>'site_reload']) !!}
            {{ Form::hidden('parent_id', $menu->parent_id) }}
            {{ Form::hidden('root', $root) }}

            @if($root)
                {!! ModulesForm::text('key','Menu::attributes.menu.key',true) !!}
            @endif

            {!! ModulesForm::text('name','Menu::attributes.menu.name',true) !!}

            {!! ModulesForm::radio('status','Modules::attributes.status', true, trans('Modules::attributes.status_options')) !!}
            @if(!$root)
                {!! ModulesForm::text('url','Menu::attributes.menu.url') !!}

                {!! ModulesForm::text('active_menu_url','Menu::attributes.menu.active_menu_url',false,$menu->active_menu_url,['help_text'=> 'Menu::attributes.menu.active_menu_url_help']) !!}

                {!! ModulesForm::text('icon','Menu::attributes.menu.icon',false,str_replace('fa ','',$menu->icon),['class'=>'icp icp-auto',
                'help_text'=>'Menu::attributes.menu.icon_help']) !!}

                {!! ModulesForm::select('target', trans('Menu::attributes.menu.target'),trans('Menu::attributes.menu.target_options')) !!}

                {!! ModulesForm::select('roles[]',trans('Menu::attributes.menu.roles'), \Modules\User\Facades\Roles::getRolesList(),false,null,
                ['class'=>'','multiple'=>true,
                'help_text'=>'Menu::attributes.menu.roles_help'],'select2') !!}
            @endif
            {!! ModulesForm::textarea('description','Menu::attributes.menu.description',false,$menu->description,['rows'=>3]) !!}

            {!! ModulesForm::customFields($menu,'col-md-12') !!}

            {!! ModulesForm::formButtons(trans('Modules::labels.save', ['title'=> $title_singular]), [], ['href' => url('menus')])  !!}

            {!! ModulesForm::closeForm($menu) !!}
        @endcomponent
    </div>
</div>
