@extends('layouts.crud.create_edit')

@section('css')
@endsection

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tag_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-4">
            @component('components.box')
                {!! ModulesForm::openForm($tag) !!}

                {!! ModulesForm::text('name','Utility::attributes.tag.name',true) !!}

                {!! ModulesForm::text('slug','Utility::attributes.tag.slug',true) !!}

                {!! ModulesForm::select('module','Utility::attributes.tag.module', \Utility::getUtilityModules()) !!}

                {!! ModulesForm::radio('status','Modules::attributes.status',true, trans('Modules::attributes.status_options')) !!}

                {!! ModulesForm::customFields($tag, 'col-md-12') !!}

                {!! ModulesForm::formButtons() !!}

                {!! ModulesForm::closeForm($tag) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
@endsection
